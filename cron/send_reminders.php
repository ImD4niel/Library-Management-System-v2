<?php
/**
 * Automated Email Reminder System
 * Sends daily email reminders for due and overdue books
 * This script should be run via cron job daily
 */

// Include required files
require_once '../config/database.php';
require_once '../includes/email.php';

// Set timezone
date_default_timezone_set('UTC');

// Initialize email system
$emailSystem = new EmailNotification();

try {
    echo "Starting automated reminder system...\n";
    
    // Send due date reminders (books due in 1 day)
    sendDueReminders($emailSystem);
    
    // Send overdue notices (books overdue by 1+ days)
    sendOverdueNotices($emailSystem);
    
    echo "Reminder system completed successfully.\n";
    
} catch (Exception $e) {
    error_log("Reminder system error: " . $e->getMessage());
    echo "Error: " . $e->getMessage() . "\n";
}

/**
 * Send due date reminders
 */
function sendDueReminders($emailSystem) {
    global $pdo;
    
    echo "Checking for books due tomorrow...\n";
    
    $stmt = $pdo->query("
        SELECT 
            s.email,
            CONCAT(s.firstname, ' ', s.lastname) as member_name,
            b.book_title,
            br.due_date
        FROM borrow br
        JOIN borrowdetails bd ON br.borrow_id = bd.borrow_id
        JOIN book b ON bd.book_id = b.book_id
        JOIN students s ON br.member_id = s.student_id
        WHERE STR_TO_DATE(br.due_date, '%d/%m/%Y') = DATE_ADD(CURDATE(), INTERVAL 1 DAY)
        AND bd.date_return IS NULL
        AND s.email IS NOT NULL
        AND s.email != ''
    ");
    
    $due_books = $stmt->fetchAll();
    $sent_count = 0;
    
    foreach ($due_books as $book) {
        if ($emailSystem->sendDueReminder(
            $book['email'],
            $book['member_name'],
            $book['book_title'],
            $book['due_date']
        )) {
            $sent_count++;
            echo "Sent due reminder to: {$book['member_name']} for book: {$book['book_title']}\n";
        }
    }
    
    echo "Sent {$sent_count} due date reminders.\n";
}

/**
 * Send overdue notices
 */
function sendOverdueNotices($emailSystem) {
    global $pdo;
    
    echo "Checking for overdue books...\n";
    
    $stmt = $pdo->query("
        SELECT 
            s.email,
            CONCAT(s.firstname, ' ', s.lastname) as member_name,
            b.book_title,
            DATEDIFF(CURDATE(), STR_TO_DATE(br.due_date, '%d/%m/%Y')) as days_overdue
        FROM borrow br
        JOIN borrowdetails bd ON br.borrow_id = bd.borrow_id
        JOIN book b ON bd.book_id = b.book_id
        JOIN students s ON br.member_id = s.student_id
        WHERE STR_TO_DATE(br.due_date, '%d/%m/%Y') < CURDATE()
        AND bd.date_return IS NULL
        AND s.email IS NOT NULL
        AND s.email != ''
        AND DATEDIFF(CURDATE(), STR_TO_DATE(br.due_date, '%d/%m/%Y')) % 7 = 0
    ");
    
    $overdue_books = $stmt->fetchAll();
    $sent_count = 0;
    
    foreach ($overdue_books as $book) {
        if ($emailSystem->sendOverdueNotice(
            $book['email'],
            $book['member_name'],
            $book['book_title'],
            $book['days_overdue']
        )) {
            $sent_count++;
            echo "Sent overdue notice to: {$book['member_name']} for book: {$book['book_title']} ({$book['days_overdue']} days overdue)\n";
        }
    }
    
    echo "Sent {$sent_count} overdue notices.\n";
}

/**
 * Log reminder activity
 */
function logReminderActivity($type, $member_email, $book_title, $status) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO reminder_logs (type, member_email, book_title, status, sent_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$type, $member_email, $book_title, $status]);
    } catch (PDOException $e) {
        error_log("Failed to log reminder activity: " . $e->getMessage());
    }
}

// Create reminder logs table if it doesn't exist
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS reminder_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            type VARCHAR(50) NOT NULL,
            member_email VARCHAR(255) NOT NULL,
            book_title VARCHAR(255) NOT NULL,
            status ENUM('sent', 'failed') NOT NULL,
            sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
} catch (PDOException $e) {
    error_log("Failed to create reminder_logs table: " . $e->getMessage());
}
?>
