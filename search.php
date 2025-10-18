<?php
/**
 * Book Search Page for Students
 * Allows students to search and view available books
 */

include('dbcon.php');

// Check if user is logged in
if (!Security::isLoggedIn() || $_SESSION['user_type'] !== 'student') {
    header('Location: login.php');
    exit();
}

$search_query = '';
$books = [];
$categories = [];

// Get categories for filter
$stmt = $pdo->query("SELECT * FROM category ORDER BY category_name");
$categories = $stmt->fetchAll();

// Handle search
if (isset($_GET['search']) && !empty($_GET['q'])) {
    $search_query = Security::sanitizeInput($_GET['q']);
    $category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
    
    $sql = "SELECT b.*, c.category_name FROM book b 
            LEFT JOIN category c ON b.category_id = c.category_id 
            WHERE (b.book_title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
    $params = ["%{$search_query}%", "%{$search_query}%", "%{$search_query}%"];
    
    if ($category_filter > 0) {
        $sql .= " AND b.category_id = ?";
        $params[] = $category_filter;
    }
    
    $sql .= " AND b.status != 'Lost' ORDER BY b.book_title";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $books = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books - Digital Library System</title>
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
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="search.php">
                            <i class="fas fa-search me-1"></i>Search Books
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
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
        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fas fa-search me-2"></i>Search Books
                        </h4>
                        
                        <form method="GET" action="">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control form-control-lg" name="q" 
                                               placeholder="Search by title, author, or ISBN..." 
                                               value="<?php echo htmlspecialchars($search_query); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select form-select-lg" name="category">
                                        <option value="">All Categories</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['category_id']; ?>" 
                                                    <?php echo (isset($_GET['category']) && $_GET['category'] == $category['category_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['category_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" name="search" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <?php if (isset($_GET['search'])): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>Search Results
                                <?php if (!empty($search_query)): ?>
                                    for "<?php echo htmlspecialchars($search_query); ?>"
                                <?php endif; ?>
                                <span class="badge bg-primary ms-2"><?php echo count($books); ?> books found</span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($books)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-search text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted mt-3">No books found</h5>
                                    <p class="text-muted">Try adjusting your search terms or category filter.</p>
                                </div>
                            <?php else: ?>
                                <div class="row g-3 p-3">
                                    <?php foreach ($books as $book): ?>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($book['category_name']); ?></span>
                                                        <span class="badge bg-<?php echo $book['book_copies'] > 0 ? 'success' : 'danger'; ?>">
                                                            <?php echo $book['book_copies'] > 0 ? 'Available' : 'Unavailable'; ?>
                                                        </span>
                                                    </div>
                                                    
                                                    <h6 class="card-title fw-bold"><?php echo htmlspecialchars($book['book_title']); ?></h6>
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($book['author']); ?>
                                                    </p>
                                                    <p class="card-text text-muted small mb-2">
                                                        <i class="fas fa-barcode me-1"></i>ISBN: <?php echo htmlspecialchars($book['isbn']); ?>
                                                    </p>
                                                    <p class="card-text text-muted small mb-3">
                                                        <i class="fas fa-building me-1"></i><?php echo htmlspecialchars($book['publisher_name']); ?>
                                                    </p>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="fas fa-copy me-1"></i><?php echo $book['book_copies']; ?> copies
                                                        </small>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i><?php echo $book['copyright_year']; ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Quick Access Categories -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">
                                <i class="fas fa-th-large me-2"></i>Browse by Category
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <?php foreach ($categories as $category): ?>
                                    <div class="col-md-3 col-sm-6">
                                        <a href="?search=1&q=&category=<?php echo $category['category_id']; ?>" 
                                           class="text-decoration-none">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-book text-primary mb-2" style="font-size: 2rem;"></i>
                                                    <h6 class="card-title"><?php echo htmlspecialchars($category['category_name']); ?></h6>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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
