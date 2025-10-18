<?php
/**
 * Real-time Search API
 * Provides AJAX search functionality for books and members
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
    $query = Security::sanitizeInput($_GET['q'] ?? '');
    $type = Security::sanitizeInput($_GET['type'] ?? 'all');
    
    if (strlen($query) < 2) {
        echo json_encode([]);
        exit();
    }
    
    $results = [];
    
    // Search books
    if ($type === 'all' || $type === 'books') {
        $stmt = $pdo->prepare("
            SELECT 
                book_id as id,
                book_title as title,
                author,
                isbn,
                'book' as type,
                CONCAT('Book: ', book_title, ' by ', author) as description
            FROM book 
            WHERE book_title LIKE ? 
               OR author LIKE ? 
               OR isbn LIKE ?
            AND status != 'Lost'
            ORDER BY book_title
            LIMIT 10
        ");
        
        $searchTerm = "%{$query}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        $books = $stmt->fetchAll();
        
        foreach ($books as $book) {
            $results[] = [
                'id' => $book['id'],
                'title' => $book['title'],
                'author' => $book['author'],
                'isbn' => $book['isbn'],
                'type' => $book['type'],
                'description' => $book['description'],
                'url' => "librarian/books.php?id={$book['id']}"
            ];
        }
    }
    
    // Search members/students
    if ($type === 'all' || $type === 'members') {
        $stmt = $pdo->prepare("
            SELECT 
                student_id as id,
                CONCAT(firstname, ' ', lastname) as title,
                student_no,
                course,
                'member' as type,
                CONCAT('Member: ', firstname, ' ', lastname, ' (', student_no, ')') as description
            FROM students 
            WHERE firstname LIKE ? 
               OR lastname LIKE ? 
               OR student_no LIKE ?
               OR course LIKE ?
            AND status = 'active'
            ORDER BY firstname, lastname
            LIMIT 10
        ");
        
        $searchTerm = "%{$query}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $members = $stmt->fetchAll();
        
        foreach ($members as $member) {
            $results[] = [
                'id' => $member['id'],
                'title' => $member['title'],
                'student_no' => $member['student_no'],
                'course' => $member['course'],
                'type' => $member['type'],
                'description' => $member['description'],
                'url' => "librarian/member.php?id={$member['id']}"
            ];
        }
    }
    
    // Search categories
    if ($type === 'all' || $type === 'categories') {
        $stmt = $pdo->prepare("
            SELECT 
                category_id as id,
                category_name as title,
                'category' as type,
                CONCAT('Category: ', category_name) as description
            FROM category 
            WHERE category_name LIKE ?
            ORDER BY category_name
            LIMIT 5
        ");
        
        $searchTerm = "%{$query}%";
        $stmt->execute([$searchTerm]);
        $categories = $stmt->fetchAll();
        
        foreach ($categories as $category) {
            $results[] = [
                'id' => $category['id'],
                'title' => $category['title'],
                'type' => $category['type'],
                'description' => $category['description'],
                'url' => "librarian/books.php?category={$category['id']}"
            ];
        }
    }
    
    // Limit total results
    $results = array_slice($results, 0, 20);
    
    echo json_encode($results);
    
} catch (PDOException $e) {
    error_log("Search API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
} catch (Exception $e) {
    error_log("Search API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>
