/**
 * Modern JavaScript Application for Digital Library Management System
 * Handles PWA functionality, real-time features, and UI interactions
 */

class LibraryApp {
    constructor() {
        this.init();
    }

    init() {
        this.registerServiceWorker();
        this.setupEventListeners();
        this.initializeComponents();
        this.setupThemeToggle();
        this.setupNotifications();
    }

    /**
     * Register Service Worker for PWA functionality
     */
    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js');
                console.log('Service Worker registered successfully:', registration);
                
                // Handle updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            this.showUpdateNotification();
                        }
                    });
                });
            } catch (error) {
                console.error('Service Worker registration failed:', error);
            }
        }
    }

    /**
     * Setup event listeners for modern interactions
     */
    setupEventListeners() {
        // Loading overlay
        window.addEventListener('load', () => {
            this.hideLoading();
        });

        // Form submissions with loading states
        document.addEventListener('submit', (e) => {
            if (e.target.matches('form')) {
                this.showLoading();
            }
        });

        // Real-time search
        const searchInputs = document.querySelectorAll('.search-input');
        searchInputs.forEach(input => {
            input.addEventListener('input', this.debounce((e) => {
                this.performSearch(e.target.value);
            }, 300));
        });

        // Toast notifications
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-toast]')) {
                this.showToast(e.target.dataset.toast, e.target.dataset.type || 'info');
            }
        });

        // Dark mode toggle
        document.addEventListener('click', (e) => {
            if (e.target.matches('.theme-toggle')) {
                this.toggleTheme();
            }
        });
    }

    /**
     * Initialize modern components
     */
    initializeComponents() {
        // Initialize DataTables
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            $('.datatable').DataTable({
                responsive: true,
                pageLength: 25,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        }

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize modals
        const modalTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="modal"]'));
        modalTriggerList.map(function (modalTriggerEl) {
            return new bootstrap.Modal(modalTriggerEl);
        });
    }

    /**
     * Setup theme toggle functionality
     */
    setupThemeToggle() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        const themeToggle = document.querySelector('.theme-toggle');
        if (themeToggle) {
            themeToggle.innerHTML = savedTheme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        }
    }

    /**
     * Toggle between light and dark themes
     */
    toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        
        const themeToggle = document.querySelector('.theme-toggle');
        if (themeToggle) {
            themeToggle.innerHTML = newTheme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        }
    }

    /**
     * Setup notification system
     */
    setupNotifications() {
        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    /**
     * Show loading overlay
     */
    showLoading() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.style.display = 'flex';
        }
    }

    /**
     * Hide loading overlay
     */
    hideLoading() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }

    /**
     * Perform real-time search
     */
    async performSearch(query) {
        if (query.length < 2) {
            this.hideSearchResults();
            return;
        }

        try {
            const response = await fetch(`/api/search.php?q=${encodeURIComponent(query)}`);
            const results = await response.json();
            this.displaySearchResults(results);
        } catch (error) {
            console.error('Search error:', error);
        }
    }

    /**
     * Display search results
     */
    displaySearchResults(results) {
        const container = document.querySelector('.search-results');
        if (!container) return;

        if (results.length === 0) {
            container.innerHTML = '<div class="search-result-item text-muted">No results found</div>';
        } else {
            container.innerHTML = results.map(result => `
                <div class="search-result-item" onclick="this.selectResult('${result.id}')">
                    <div class="fw-bold">${result.title}</div>
                    <small class="text-muted">${result.description}</small>
                </div>
            `).join('');
        }
        
        container.style.display = 'block';
    }

    /**
     * Hide search results
     */
    hideSearchResults() {
        const container = document.querySelector('.search-results');
        if (container) {
            container.style.display = 'none';
        }
    }

    /**
     * Show toast notification
     */
    showToast(message, type = 'info') {
        const toastContainer = document.querySelector('.toast-container') || this.createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast show`;
        toast.innerHTML = `
            <div class="toast-header">
                <i class="fas fa-${this.getToastIcon(type)} text-${type} me-2"></i>
                <strong class="me-auto">${this.getToastTitle(type)}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    /**
     * Create toast container if it doesn't exist
     */
    createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
        return container;
    }

    /**
     * Get toast icon based on type
     */
    getToastIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    /**
     * Get toast title based on type
     */
    getToastTitle(type) {
        const titles = {
            success: 'Success',
            error: 'Error',
            warning: 'Warning',
            info: 'Information'
        };
        return titles[type] || 'Information';
    }

    /**
     * Show update notification
     */
    showUpdateNotification() {
        if (confirm('A new version of the app is available. Would you like to update?')) {
            window.location.reload();
        }
    }

    /**
     * Send notification
     */
    sendNotification(title, body, icon = '/assets/icons/icon-192x192.png') {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(title, {
                body: body,
                icon: icon,
                badge: '/assets/icons/icon-72x72.png'
            });
        }
    }

    /**
     * Debounce function for performance
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Generate QR Code for books/members
     */
    generateQRCode(data, containerId) {
        const container = document.getElementById(containerId);
        if (container && typeof QRCode !== 'undefined') {
            container.innerHTML = '';
            new QRCode(container, {
                text: data,
                width: 200,
                height: 200,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
        }
    }

    /**
     * Scan barcode/QR code using camera
     */
    async scanBarcode() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            // Implementation for barcode scanning would go here
            // This would require additional libraries like QuaggaJS or ZXing
            console.log('Camera access granted for barcode scanning');
        } catch (error) {
            console.error('Camera access denied:', error);
            this.showToast('Camera access is required for barcode scanning', 'error');
        }
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.libraryApp = new LibraryApp();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LibraryApp;
}
