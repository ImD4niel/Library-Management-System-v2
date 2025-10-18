<?php
/**
 * Accessibility Components and Features
 * Enhances accessibility for screen readers and keyboard navigation
 */
?>

<!-- Skip to main content link -->
<a href="#main-content" class="skip-link btn btn-primary position-absolute" 
   style="top: -40px; left: 6px; z-index: 1000; transition: top 0.3s;">
    Skip to main content
</a>

<!-- Screen reader only text -->
<span class="sr-only">Screen reader only text</span>

<!-- Focus management script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Skip link functionality
    const skipLink = document.querySelector('.skip-link');
    if (skipLink) {
        skipLink.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.focus();
                target.scrollIntoView();
            }
        });
    }
    
    // Keyboard navigation for dropdowns
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close any open dropdowns or modals
            const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
            openDropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });
    
    // ARIA live region for dynamic content
    const liveRegion = document.createElement('div');
    liveRegion.setAttribute('aria-live', 'polite');
    liveRegion.setAttribute('aria-atomic', 'true');
    liveRegion.className = 'sr-only';
    liveRegion.id = 'live-region';
    document.body.appendChild(liveRegion);
    
    // Announce changes to screen readers
    window.announceToScreenReader = function(message) {
        const liveRegion = document.getElementById('live-region');
        if (liveRegion) {
            liveRegion.textContent = message;
            setTimeout(() => {
                liveRegion.textContent = '';
            }, 1000);
        }
    };
    
    // Enhanced form validation with ARIA
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const invalidFields = form.querySelectorAll(':invalid');
            if (invalidFields.length > 0) {
                e.preventDefault();
                invalidFields[0].focus();
                window.announceToScreenReader('Please correct the errors in the form');
            }
        });
    });
    
    // Enhanced table navigation
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
        // Add role and aria-label if missing
        if (!table.getAttribute('role')) {
            table.setAttribute('role', 'table');
        }
        
        // Add caption if missing
        if (!table.querySelector('caption')) {
            const caption = document.createElement('caption');
            caption.textContent = 'Data table';
            table.insertBefore(caption, table.firstChild);
        }
    });
    
    // Enhanced button accessibility
    const buttons = document.querySelectorAll('button, .btn');
    buttons.forEach(button => {
        if (!button.getAttribute('aria-label') && !button.textContent.trim()) {
            button.setAttribute('aria-label', 'Button');
        }
    });
    
    // Enhanced modal accessibility
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (focusableElements.length > 0) {
                focusableElements[0].focus();
            }
        });
    });
    
    // High contrast mode detection
    if (window.matchMedia && window.matchMedia('(prefers-contrast: high)').matches) {
        document.body.classList.add('high-contrast');
    }
    
    // Reduced motion detection
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.body.classList.add('reduced-motion');
    }
});
</script>

<!-- High contrast and reduced motion styles -->
<style>
/* High contrast mode */
.high-contrast {
    --bs-primary: #0000ff;
    --bs-secondary: #808080;
    --bs-success: #008000;
    --bs-danger: #ff0000;
    --bs-warning: #ffff00;
    --bs-info: #00ffff;
}

.high-contrast .btn {
    border-width: 2px;
    font-weight: bold;
}

.high-contrast .card {
    border-width: 2px;
}

/* Reduced motion */
.reduced-motion * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
}

/* Focus indicators */
*:focus {
    outline: 2px solid #2563eb;
    outline-offset: 2px;
}

.btn:focus,
.form-control:focus,
.form-select:focus {
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
}

/* Screen reader only class */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Skip link styles */
.skip-link:focus {
    top: 6px;
}

/* Enhanced table accessibility */
table[role="table"] {
    border-collapse: collapse;
}

table[role="table"] th,
table[role="table"] td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
}

table[role="table"] th {
    background-color: #f8f9fa;
    font-weight: 600;
}

/* Enhanced form accessibility */
.form-control:invalid {
    border-color: #dc3545;
}

.form-control:invalid:focus {
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.25);
}

/* Loading states for screen readers */
.loading[aria-busy="true"] {
    position: relative;
}

.loading[aria-busy="true"]::after {
    content: "Loading...";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255, 255, 255, 0.9);
    padding: 1rem;
    border-radius: 0.5rem;
    font-weight: bold;
}
</style>
