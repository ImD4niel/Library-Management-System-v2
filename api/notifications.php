<?php
/**
 * Notifications API
 * Handles real-time notifications and alerts
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection
require_once '../config/database.php';
require_once '../config/security.php';

// Verify user is logged in
if (!Security::isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

try {
    $action = Security::sanitizeInput($_POST['action'] ?? $_GET['action'] ?? '');
    
    switch ($action) {
        case 'get_notifications':
            getNotifications();
            break;
            
        case 'mark_read':
            markAsRead();
            break;
            
        case 'send_notification':
            sendNotification();
            break;
            
        case 'get_overdue_books':
            getOverdueBooks();
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
    
} catch (Exception $e) {
    error_log("Notifications API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}

/**
 * Get user notifications
 */
function getNotifications() {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT 
            notification_id,
            title,
            message,
            type,
            is_read,
            created_at
        FROM notifications 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 20
    ");
    
    $stmt->execute([$_SESSION['user_id']]);
    $notifications = $stmt->fetchAll();
    
    echo json_encode([
        'notifications' => $notifications,
        'unread_count' => count(array_filter($notifications, fn($n) => !$n['is_read']))
    ]);
}

/**
 * Mark notification as read
 */
function markAsRead() {
    global $pdo;
    
    $notification_id = (int)($_POST['notification_id'] ?? 0);
    
    if ($notification_id > 0) {
        $stmt = $pdo->prepare("
            UPDATE notifications 
            SET is_read = 1 
            WHERE notification_id = ? AND user_id = ?
        ");
        $stmt->execute([$notification_id, $_SESSION['user_id']]);
    }
    
    echo json_encode(['success' => true]);
}

/**
 * Send notification to user
 */
function sendNotification() {
    global $pdo;
    
    $user_id = (int)($_POST['user_id'] ?? 0);
    $title = Security::sanitizeInput($_POST['title'] ?? '');
    $message = Security::sanitizeInput($_POST['message'] ?? '');
    $type = Security::sanitizeInput($_POST['type'] ?? 'info');
    
    if ($user_id > 0 && !empty($title) && !empty($message)) {
        $stmt = $pdo->prepare("
            INSERT INTO notifications (user_id, title, message, type, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$user_id, $title, $message, $type]);
        
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
    }
}

/**
 * Get overdue books notifications
 */
function getOverdueBooks() {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT 
            b.borrow_id,
            bk.book_title,
            s.firstname,
            s.lastname,
            b.due_date,
            DATEDIFF(NOW(), STR_TO_DATE(b.due_date, '%d/%m/%Y')) as days_overdue
        FROM borrow b
        JOIN borrowdetails bd ON b.borrow_id = bd.borrow_id
        JOIN book bk ON bd.book_id = bk.book_id
        JOIN students s ON b.member_id = s.student_id
        WHERE STR_TO_DATE(b.due_date, '%d/%m/%Y') < CURDATE()
        AND bd.date_return IS NULL
        ORDER BY days_overdue DESC
    ");
    
    $stmt->execute();
    $overdue_books = $stmt->fetchAll();
    
    echo json_encode([
        'overdue_books' => $overdue_books,
        'count' => count($overdue_books)
    ]);
}
?>
