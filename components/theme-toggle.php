<?php
/**
 * Theme Toggle Component
 * Provides dark/light mode switching functionality
 */
?>

<!-- Theme Toggle Button -->
<button class="btn btn-outline-secondary theme-toggle" type="button" 
        data-bs-toggle="tooltip" data-bs-placement="bottom" 
        title="Toggle Dark Mode" aria-label="Toggle dark mode">
    <i class="fas fa-moon"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.querySelector('.theme-toggle');
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Set initial theme
    document.documentElement.setAttribute('data-theme', currentTheme);
    updateThemeIcon(currentTheme);
    
    // Theme toggle functionality
    themeToggle.addEventListener('click', function() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
        
        // Show toast notification
        if (window.libraryApp) {
            window.libraryApp.showToast(
                `Switched to ${newTheme} mode`, 
                'success'
            );
        }
    });
    
    function updateThemeIcon(theme) {
        const icon = themeToggle.querySelector('i');
        if (theme === 'dark') {
            icon.className = 'fas fa-sun';
            themeToggle.title = 'Switch to Light Mode';
        } else {
            icon.className = 'fas fa-moon';
            themeToggle.title = 'Switch to Dark Mode';
        }
    }
});
</script>
