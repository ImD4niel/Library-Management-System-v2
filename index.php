<?php include('header.php'); ?>
<?php include('components/navbar.php'); ?>

<!-- Hero Section -->
<div class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Digital Library Management System</h1>
                <p class="lead mb-4">Modern, secure, and efficient library automation platform with PWA capabilities, real-time search, and advanced analytics.</p>
                <div class="d-flex gap-3">
                    <a href="login.php" class="btn btn-light btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Student Login
                    </a>
                    <a href="librarian/login.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-user-shield me-2"></i>Admin Login
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-book-open display-1 opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container my-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="display-5 fw-bold">Modern Library Features</h2>
            <p class="lead text-muted">Everything you need for efficient library management</p>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-search text-primary fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Real-time Search</h5>
                    <p class="text-muted">Instant search across books, members, and categories with live results and autocomplete.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-mobile-alt text-success fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Progressive Web App</h5>
                    <p class="text-muted">Install as a mobile app, works offline, and provides native app experience.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-qrcode text-warning fs-2"></i>
                    </div>
                    <h5 class="fw-bold">QR Code Integration</h5>
                    <p class="text-muted">Generate and scan QR codes for quick book identification and member management.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-chart-bar text-info fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Analytics Dashboard</h5>
                    <p class="text-muted">Comprehensive statistics, usage reports, and real-time analytics for better insights.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-envelope text-danger fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Email Notifications</h5>
                    <p class="text-muted">Automated email reminders for due dates, overdue books, and system notifications.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="bg-dark bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-shield-alt text-dark fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Enhanced Security</h5>
                    <p class="text-muted">Modern security with PDO, password hashing, CSRF protection, and secure sessions.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- About Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-6 fw-bold mb-4">About Our Digital Library</h2>
                <p class="lead mb-4">
                    Our modern library management system provides a comprehensive solution for educational institutions, 
                    public libraries, and small to medium-sized library operations. Built with cutting-edge web technologies, 
                    it offers an intuitive interface for librarians and users while maintaining the highest standards of security and performance.
                </p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-users text-primary me-2"></i>
                            <span class="fw-bold">User Management</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-book text-success me-2"></i>
                            <span class="fw-bold">Book Inventory</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-line text-warning me-2"></i>
                            <span class="fw-bold">Analytics & Reports</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php') ?>