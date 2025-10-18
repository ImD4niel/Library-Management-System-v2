<?php
/**
 * Modern Navigation Component
 * Responsive navbar with search, notifications, and user menu
 */
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <i class="fas fa-book-open text-primary me-2"></i>
            <span class="fw-bold">Digital Library</span>
        </a>

        <!-- Mobile toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Search -->
            <div class="navbar-nav me-auto">
                <div class="nav-item">
                    <div class="search-container position-relative">
                        <input type="text" class="form-control search-input" placeholder="Search books, members..." 
                               aria-label="Search" style="width: 300px;">
                        <div class="search-results position-absolute bg-white border rounded shadow" 
                             style="top: 100%; left: 0; right: 0; z-index: 1000; display: none;">
                            <!-- Search results will be populated here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side items -->
            <ul class="navbar-nav ms-auto">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative" href="#" id="notificationDropdown" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                              id="notificationBadge" style="display: none;">
                            0
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><h6 class="dropdown-header">Notifications</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li id="notificationList">
                            <div class="dropdown-item-text text-center text-muted">
                                <i class="fas fa-bell-slash me-2"></i>
                                No notifications
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#" id="markAllRead">Mark all as read</a></li>
                    </ul>
                </li>

                <!-- Theme Toggle -->
                <li class="nav-item">
                    <?php include 'theme-toggle.php'; ?>
                </li>

                <!-- User Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                             style="width: 32px; height: 32px; font-size: 14px;">
                            <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                        </div>
                        <span class="d-none d-md-inline"><?php echo $_SESSION['user_name'] ?? 'User'; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><h6 class="dropdown-header">Account</h6></li>
                        <li><a class="dropdown-item" href="profile.php">
                            <i class="fas fa-user me-2"></i>Profile
                        </a></li>
                        <li><a class="dropdown-item" href="settings.php">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main content wrapper -->
<main id="main-content" class="container-fluid">
    <!-- Page content goes here -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.querySelector('.search-input');
    const searchResults = document.querySelector('.search-results');
    
    if (searchInput && searchResults) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`/api/search.php?q=${encodeURIComponent(query)}`);
                    const results = await response.json();
                    displaySearchResults(results);
                } catch (error) {
                    console.error('Search error:', error);
                }
            }, 300);
        });
        
        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }
    
    // Notification functionality
    loadNotifications();
    setInterval(loadNotifications, 30000); // Check every 30 seconds
    
    // Mark all notifications as read
    const markAllReadBtn = document.getElementById('markAllRead');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markAllNotificationsRead();
        });
    }
});

function displaySearchResults(results) {
    const searchResults = document.querySelector('.search-results');
    if (!searchResults) return;
    
    if (results.length === 0) {
        searchResults.innerHTML = '<div class="p-3 text-muted text-center">No results found</div>';
    } else {
        searchResults.innerHTML = results.map(result => `
            <div class="search-result-item p-3 border-bottom" onclick="selectSearchResult('${result.url}')">
                <div class="fw-bold">${result.title}</div>
                <small class="text-muted">${result.description}</small>
            </div>
        `).join('');
    }
    
    searchResults.style.display = 'block';
}

function selectSearchResult(url) {
    window.location.href = url;
}

async function loadNotifications() {
    try {
        const response = await fetch('/api/notifications.php?action=get_notifications');
        const data = await response.json();
        
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationList = document.getElementById('notificationList');
        
        if (data.unread_count > 0) {
            notificationBadge.textContent = data.unread_count;
            notificationBadge.style.display = 'block';
        } else {
            notificationBadge.style.display = 'none';
        }
        
        if (data.notifications && data.notifications.length > 0) {
            notificationList.innerHTML = data.notifications.slice(0, 5).map(notification => `
                <a class="dropdown-item ${notification.is_read ? 'text-muted' : ''}" href="#">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-${getNotificationIcon(notification.type)} me-2 mt-1"></i>
                        <div class="flex-grow-1">
                            <div class="fw-bold">${notification.title}</div>
                            <small class="text-muted">${notification.message}</small>
                            <div class="small text-muted">${formatDate(notification.created_at)}</div>
                        </div>
                    </div>
                </a>
            `).join('');
        } else {
            notificationList.innerHTML = '<div class="dropdown-item-text text-center text-muted">No notifications</div>';
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

function getNotificationIcon(type) {
    const icons = {
        'info': 'info-circle',
        'success': 'check-circle',
        'warning': 'exclamation-triangle',
        'error': 'exclamation-circle'
    };
    return icons[type] || 'bell';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInHours = Math.floor((now - date) / (1000 * 60 * 60));
    
    if (diffInHours < 1) return 'Just now';
    if (diffInHours < 24) return `${diffInHours}h ago`;
    if (diffInHours < 48) return 'Yesterday';
    return date.toLocaleDateString();
}

async function markAllNotificationsRead() {
    try {
        const response = await fetch('/api/notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=mark_all_read'
        });
        
        if (response.ok) {
            loadNotifications(); // Refresh notifications
            if (window.libraryApp) {
                window.libraryApp.showToast('All notifications marked as read', 'success');
            }
        }
    } catch (error) {
        console.error('Error marking notifications as read:', error);
    }
}
</script>
