<?php
/**
 * Modern secure librarian login
 * Uses PDO prepared statements and password hashing
 */

if (isset($_POST['submit'])) {
    // Verify CSRF token
    if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        echo '<div class="alert alert-danger">Security token mismatch</div>';
        return;
    }
    
    $username = Security::sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    try {
        // Use prepared statement to prevent SQL injection
        $stmt = $pdo->prepare("SELECT user_id, password, firstname, lastname FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && Security::verifyPassword($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_type'] = 'librarian';
            $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
            
            header('Location: dashboard.php');
            exit();
        } else {
            echo '<div class="alert alert-danger">Access Denied - Invalid credentials</div>';
        }
    } catch (PDOException $e) {
        error_log("Librarian login error: " . $e->getMessage());
        echo '<div class="alert alert-danger">System error occurred</div>';
    }
}
?>