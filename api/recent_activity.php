<?php
/**
 * Recent Activity API
 * Provides recent library activities for the dashboard
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
    $activities = [];
    
    // Recent book borrows
    $stmt = $pdo->query("
        SELECT 
            'borrow' as type,
            CONCAT('Book "', b.book_title, '" borrowed by ', s.firstname, ' ', s.lastname) as description,
            b_borrow.date_borrow as date,
            'active' as status
        FROM borrow b_borrow
        JOIN borrowdetails bd ON b_borrow.borrow_id = bd.borrow_id
        JOIN book b ON bd.book_id = b.book_id
        JOIN students s ON b_borrow.member_id = s.student_id
        WHERE bd.date_return IS NULL
        ORDER BY b_borrow.date_borrow DESC
        LIMIT 10
    ");
    
    $borrows = $stmt->fetchAll();
    foreach ($borrows as $borrow) {
        $activities[] = [
            'type' => $borrow['type'],
            'description' => $borrow['description'],
            'date' => date('M j, Y', strtotime($borrow['date'])),
            'status' => $borrow['status']
        ];
    }
    
    // Recent book returns
    $stmt = $pdo->query("
        SELECT 
            'return' as type,
            CONCAT('Book "', b.book_title, '" returned by ', s.firstname, ' ', s.lastname) as description,
            bd.date_return as date,
            'completed' as status
        FROM borrowdetails bd
        JOIN borrow b_borrow ON bd.borrow_id = b_borrow.borrow_id
        JOIN book b ON bd.book_id = b.book_id
        JOIN students s ON b_borrow.member_id = s.student_id
        WHERE bd.date_return IS NOT NULL
        ORDER BY bd.date_return DESC
        LIMIT 5
    ");
    
    $returns = $stmt->fetchAll();
    foreach ($returns as $return) {
        $activities[] = [
            'type' => $return['type'],
            'description' => $return['description'],
            'date' => date('M j, Y', strtotime($return['date'])),
            'status' => $return['status']
        ];
    }
    
    // Recent new members
    $stmt = $pdo->query("
        SELECT 
            'member' as type,
            CONCAT('New member registered: ', firstname, ' ', lastname) as description,
            date_added as date,
            'active' as status
        FROM students
        WHERE date_added >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        ORDER BY date_added DESC
        LIMIT 5
    ");
    
    $members = $stmt->fetchAll();
    foreach ($members as $member) {
        $activities[] = [
            'type' => $member['type'],
            'description' => $member['description'],
            'date' => date('M j, Y', strtotime($member['date'])),
            'status' => $member['status']
        ];
    }
    
    // Sort by date (most recent first)
    usort($activities, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    
    // Limit to 15 most recent activities
    $activities = array_slice($activities, 0, 15);
    
    echo json_encode($activities);
    
} catch (PDOException $e) {
    error_log("Recent activity error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
} catch (Exception $e) {
    error_log("Recent activity error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>
