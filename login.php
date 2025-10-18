<?php
/**
 * Modern secure login system
 * Uses PDO prepared statements and password hashing
 */

include('dbcon.php');

$error_message = '';

if (isset($_POST['login'])) {
    // Verify CSRF token
    if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error_message = 'Security token mismatch. Please try again.';
    } else {
        $student_no = Security::sanitizeInput($_POST['student_no']);
        $password = $_POST['password'];
        
        try {
            // Use prepared statement to prevent SQL injection
            $stmt = $pdo->prepare("SELECT student_id, password, firstname, lastname FROM students WHERE student_no = ? AND status = 'active'");
            $stmt->execute([$student_no]);
            $user = $stmt->fetch();
            
            if ($user && Security::verifyPassword($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['student_id'];
                $_SESSION['user_type'] = 'student';
                $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
                
                header('Location: dashboard.php');
                exit();
            } else {
                // Login failed
                $error_message = 'Invalid credentials.';
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $error_message = 'System error occurred. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Digital Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-book-open text-primary" style="font-size: 3rem;"></i>
                            <h3 class="mt-3 mb-1">Student Login</h3>
                            <p class="text-muted">Access your library account</p>
                        </div>
                        
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo htmlspecialchars($error_message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                            
                            <div class="mb-3">
                                <label for="student_no" class="form-label">Student Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="student_no" name="student_no" 
                                           placeholder="Enter your student number" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Enter your password" required>
                                </div>
                            </div>
                            
                            <button type="submit" name="login" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted">Don't have an account? <a href="signup.php" class="text-decoration-none">Register here</a></p>
                            <hr>
                            <p class="text-muted small">
                                <a href="librarian/login.php" class="text-decoration-none">
                                    <i class="fas fa-user-shield me-1"></i>Admin Login
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>