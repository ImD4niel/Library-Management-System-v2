<?php 
/**
 * Modern Dashboard with Analytics
 * Features real-time statistics and interactive charts
 */

include('header.php'); 
include('session.php'); 
include('navbar_dashboard.php'); 
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar bg-white">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="books.php">
                            <i class="fas fa-book me-2"></i>Books
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="member.php">
                            <i class="fas fa-users me-2"></i>Members
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="borrow.php">
                            <i class="fas fa-hand-holding me-2"></i>Borrow
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="return.php">
                            <i class="fas fa-undo me-2"></i>Return
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Book
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Total Books</div>
                                    <div class="stat-value" id="total-books">-</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-book fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Active Members</div>
                                    <div class="stat-value" id="active-members">-</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Books Borrowed</div>
                                    <div class="stat-value" id="books-borrowed">-</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-hand-holding fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Overdue Books</div>
                                    <div class="stat-value" id="overdue-books">-</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-xl-8 col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Books Borrowed This Month</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="borrowedChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Popular Categories</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless" id="recentActivity">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="add_books.php" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add New Book
                                </a>
                                <a href="add_member.php" class="btn btn-success">
                                    <i class="fas fa-user-plus me-2"></i>Add Member
                                </a>
                                <a href="borrow.php" class="btn btn-warning">
                                    <i class="fas fa-hand-holding me-2"></i>Issue Book
                                </a>
                                <a href="return.php" class="btn btn-info">
                                    <i class="fas fa-undo me-2"></i>Return Book
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard data
    loadDashboardData();
    
    // Initialize charts
    initializeCharts();
    
    // Set up real-time updates
    setInterval(loadDashboardData, 30000); // Update every 30 seconds
});

async function loadDashboardData() {
    try {
        // Load statistics
        const statsResponse = await fetch('/api/dashboard_stats.php');
        const stats = await statsResponse.json();
        
        document.getElementById('total-books').textContent = stats.total_books || 0;
        document.getElementById('active-members').textContent = stats.active_members || 0;
        document.getElementById('books-borrowed').textContent = stats.books_borrowed || 0;
        document.getElementById('overdue-books').textContent = stats.overdue_books || 0;
        
        // Load recent activity
        const activityResponse = await fetch('/api/recent_activity.php');
        const activities = await activityResponse.json();
        
        const tbody = document.querySelector('#recentActivity tbody');
        tbody.innerHTML = activities.map(activity => `
            <tr>
                <td><span class="badge bg-${activity.type === 'borrow' ? 'success' : 'info'}">${activity.type}</span></td>
                <td>${activity.description}</td>
                <td>${activity.date}</td>
                <td><span class="badge bg-${activity.status === 'active' ? 'success' : 'secondary'}">${activity.status}</span></td>
            </tr>
        `).join('');
        
    } catch (error) {
        console.error('Error loading dashboard data:', error);
    }
}

function initializeCharts() {
    // Borrowed Books Chart
    const borrowedCtx = document.getElementById('borrowedChart').getContext('2d');
    new Chart(borrowedCtx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Books Borrowed',
                data: [12, 19, 3, 5],
                borderColor: 'rgb(37, 99, 235)',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    
    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Fiction', 'Science', 'History', 'Technology'],
            datasets: [{
                data: [30, 25, 20, 25],
                backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}
</script>

<?php include('footer.php') ?>
