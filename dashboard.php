<?php
/**
 * Student Dashboard
 * Modern dashboard for students to view their library activities
 */

include('dbcon.php');

// Check if user is logged in
if (!Security::isLoggedIn() || $_SESSION['user_type'] !== 'student') {
    header('Location: login.php');
    exit();
}

// Get student information
$student_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

// Get borrowed books
$stmt = $pdo->prepare("
    SELECT 
        b.book_title,
        b.author,
        b.isbn,
        br.date_borrow,
        br.due_date,
        bd.date_return,
        CASE 
            WHEN bd.date_return IS NULL AND STR_TO_DATE(br.due_date, '%d/%m/%Y') < CURDATE() THEN 'Overdue'
            WHEN bd.date_return IS NULL THEN 'Borrowed'
            ELSE 'Returned'
        END as status
    FROM borrow br
    JOIN borrowdetails bd ON br.borrow_id = bd.borrow_id
    JOIN book b ON bd.book_id = b.book_id
    WHERE br.member_id = ?
    ORDER BY br.date_borrow DESC
    LIMIT 10
");
$stmt->execute([$student_id]);
$borrowed_books = $stmt->fetchAll();

// Get statistics
$stats = [];
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM borrow br JOIN borrowdetails bd ON br.borrow_id = bd.borrow_id WHERE br.member_id = ? AND bd.date_return IS NULL");
$stmt->execute([$student_id]);
$stats['currently_borrowed'] = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM borrow br JOIN borrowdetails bd ON br.borrow_id = bd.borrow_id WHERE br.member_id = ? AND bd.date_return IS NOT NULL");
$stmt->execute([$student_id]);
$stats['returned'] = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM borrow br JOIN borrowdetails bd ON br.borrow_id = bd.borrow_id WHERE br.member_id = ? AND bd.date_return IS NULL AND STR_TO_DATE(br.due_date, '%d/%m/%Y') < CURDATE()");
$stmt->execute([$student_id]);
$stats['overdue'] = $stmt->fetch()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Digital Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-book-open me-2"></i>Digital Library
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search.php">
                            <i class="fas fa-search me-1"></i>Search Books
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="change_password.php"><i class="fas fa-key me-2"></i>Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h2 class="card-title">
                            <i class="fas fa-user-graduate me-2"></i>
                            Welcome, <?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?>!
                        </h2>
                        <p class="card-text">Student ID: <?php echo htmlspecialchars($student['student_no']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-book text-primary fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-primary"><?php echo $stats['currently_borrowed']; ?></h3>
                        <p class="text-muted mb-0">Currently Borrowed</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-success"><?php echo $stats['returned']; ?></h3>
                        <p class="text-muted mb-0">Books Returned</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-danger"><?php echo $stats['overdue']; ?></h3>
                        <p class="text-muted mb-0">Overdue Books</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrowed Books Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Recent Book Activity
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($borrowed_books)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-book-open text-muted" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mt-3">No books borrowed yet</h5>
                                <p class="text-muted">Start by searching for books you'd like to borrow.</p>
                                <a href="search.php" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Search Books
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Book Title</th>
                                            <th>Author</th>
                                            <th>Borrowed Date</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($borrowed_books as $book): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($book['book_title']); ?></strong>
                                                    <br><small class="text-muted">ISBN: <?php echo htmlspecialchars($book['isbn']); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                                <td><?php echo date('M j, Y', strtotime($book['date_borrow'])); ?></td>
                                                <td><?php echo $book['due_date']; ?></td>
                                                <td>
                                                    <?php
                                                    $status_class = '';
                                                    $status_icon = '';
                                                    switch ($book['status']) {
                                                        case 'Borrowed':
                                                            $status_class = 'bg-primary';
                                                            $status_icon = 'fas fa-book';
                                                            break;
                                                        case 'Overdue':
                                                            $status_class = 'bg-danger';
                                                            $status_icon = 'fas fa-exclamation-triangle';
                                                            break;
                                                        case 'Returned':
                                                            $status_class = 'bg-success';
                                                            $status_icon = 'fas fa-check';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge <?php echo $status_class; ?>">
                                                        <i class="<?php echo $status_icon; ?> me-1"></i>
                                                        <?php echo $book['status']; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light border-top mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary mb-2">Digital Library Management System</h6>
                    <p class="text-muted small mb-0">Modern library automation platform</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted small mb-0">
                        &copy; <?php echo date('Y'); ?> Digital Library System. All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>
