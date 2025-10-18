<?php
/**
 * Dashboard Statistics API
 * Provides real-time statistics for the dashboard
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
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
    $stats = [];
    
    // Total books
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM book WHERE status != 'Lost'");
    $stats['total_books'] = $stmt->fetch()['total'];
    
    // Active members
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM students WHERE status = 'active'");
    $stats['active_members'] = $stmt->fetch()['total'];
    
    // Books currently borrowed
    $stmt = $pdo->query("
        SELECT COUNT(*) as total 
        FROM borrowdetails bd
        JOIN borrow b ON bd.borrow_id = b.borrow_id
        WHERE bd.date_return IS NULL
    ");
    $stats['books_borrowed'] = $stmt->fetch()['total'];
    
    // Overdue books
    $stmt = $pdo->query("
        SELECT COUNT(*) as total 
        FROM borrow b
        JOIN borrowdetails bd ON b.borrow_id = bd.borrow_id
        WHERE STR_TO_DATE(b.due_date, '%d/%m/%Y') < CURDATE()
        AND bd.date_return IS NULL
    ");
    $stats['overdue_books'] = $stmt->fetch()['total'];
    
    // Books borrowed this month
    $stmt = $pdo->query("
        SELECT COUNT(*) as total 
        FROM borrow 
        WHERE MONTH(date_borrow) = MONTH(CURDATE()) 
        AND YEAR(date_borrow) = YEAR(CURDATE())
    ");
    $stats['books_this_month'] = $stmt->fetch()['total'];
    
    // Popular categories
    $stmt = $pdo->query("
        SELECT c.category_name, COUNT(bd.book_id) as borrow_count
        FROM category c
        LEFT JOIN book b ON c.category_id = b.category_id
        LEFT JOIN borrowdetails bd ON b.book_id = bd.book_id
        GROUP BY c.category_id, c.category_name
        ORDER BY borrow_count DESC
        LIMIT 5
    ");
    $stats['popular_categories'] = $stmt->fetchAll();
    
    // Books borrowed by day (last 7 days)
    $stmt = $pdo->query("
        SELECT DATE(date_borrow) as date, COUNT(*) as count
        FROM borrow 
        WHERE date_borrow >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(date_borrow)
        ORDER BY date
    ");
    $stats['daily_borrows'] = $stmt->fetchAll();
    
    echo json_encode($stats);
    
} catch (PDOException $e) {
    error_log("Dashboard stats error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
} catch (Exception $e) {
    error_log("Dashboard stats error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>
