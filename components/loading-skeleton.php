<?php
/**
 * Loading Skeleton Components
 * Provides skeleton loading states for better UX
 */
?>

<!-- Table Skeleton -->
<div class="skeleton-table" style="display: none;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th><div class="skeleton skeleton-text" style="width: 100px; height: 20px;"></div></th>
                    <th><div class="skeleton skeleton-text" style="width: 120px; height: 20px;"></div></th>
                    <th><div class="skeleton skeleton-text" style="width: 80px; height: 20px;"></div></th>
                    <th><div class="skeleton skeleton-text" style="width: 90px; height: 20px;"></div></th>
                </tr>
            </thead>
            <tbody>
                <?php for($i = 0; $i < 5; $i++): ?>
                <tr>
                    <td><div class="skeleton skeleton-text" style="width: 80%; height: 16px;"></div></td>
                    <td><div class="skeleton skeleton-text" style="width: 70%; height: 16px;"></div></td>
                    <td><div class="skeleton skeleton-text" style="width: 60%; height: 16px;"></div></td>
                    <td><div class="skeleton skeleton-text" style="width: 50%; height: 16px;"></div></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Card Skeleton -->
<div class="skeleton-card" style="display: none;">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="skeleton skeleton-circle" style="width: 40px; height: 40px; margin-right: 15px;"></div>
                <div class="flex-grow-1">
                    <div class="skeleton skeleton-text" style="width: 60%; height: 20px; margin-bottom: 8px;"></div>
                    <div class="skeleton skeleton-text" style="width: 40%; height: 16px;"></div>
                </div>
            </div>
            <div class="skeleton skeleton-text" style="width: 100%; height: 16px; margin-bottom: 8px;"></div>
            <div class="skeleton skeleton-text" style="width: 80%; height: 16px; margin-bottom: 8px;"></div>
            <div class="skeleton skeleton-text" style="width: 90%; height: 16px;"></div>
        </div>
    </div>
</div>

<!-- Stats Skeleton -->
<div class="skeleton-stats" style="display: none;">
    <div class="row">
        <?php for($i = 0; $i < 4; $i++): ?>
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="skeleton skeleton-text" style="width: 60%; height: 16px; margin-bottom: 10px;"></div>
                    <div class="skeleton skeleton-text" style="width: 40%; height: 32px;"></div>
                </div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
</div>

<script>
// Loading skeleton utility functions
class LoadingSkeleton {
    static show(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.style.display = 'block';
        }
    }
    
    static hide(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.style.display = 'none';
        }
    }
    
    static showTableSkeleton(tableId) {
        const table = document.getElementById(tableId);
        if (table) {
            table.style.display = 'none';
            const skeleton = table.parentNode.querySelector('.skeleton-table');
            if (skeleton) {
                skeleton.style.display = 'block';
            }
        }
    }
    
    static hideTableSkeleton(tableId) {
        const table = document.getElementById(tableId);
        if (table) {
            table.style.display = 'block';
            const skeleton = table.parentNode.querySelector('.skeleton-table');
            if (skeleton) {
                skeleton.style.display = 'none';
            }
        }
    }
    
    static showCardSkeleton(cardId) {
        const card = document.getElementById(cardId);
        if (card) {
            card.style.display = 'none';
            const skeleton = card.parentNode.querySelector('.skeleton-card');
            if (skeleton) {
                skeleton.style.display = 'block';
            }
        }
    }
    
    static hideCardSkeleton(cardId) {
        const card = document.getElementById(cardId);
        if (card) {
            card.style.display = 'block';
            const skeleton = card.parentNode.querySelector('.skeleton-card');
            if (skeleton) {
                skeleton.style.display = 'none';
            }
        }
    }
}

// Auto-hide loading skeletons after content loads
document.addEventListener('DOMContentLoaded', function() {
    // Hide all skeleton loaders after a short delay
    setTimeout(() => {
        const skeletons = document.querySelectorAll('.skeleton-table, .skeleton-card, .skeleton-stats');
        skeletons.forEach(skeleton => {
            skeleton.style.display = 'none';
        });
    }, 1000);
});
</script>
