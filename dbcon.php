<?php
/**
 * Legacy database connection - DEPRECATED
 * Use config/database.php for new PDO connections
 */

// Include modern database configuration
require_once 'config/database.php';
require_once 'config/security.php';

// Legacy compatibility - redirect to modern connection
$connection = $pdo;
?>