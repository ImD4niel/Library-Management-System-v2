<?php
/**
 * Librarian module database connection
 * Uses modern PDO connection
 */

// Include modern database configuration
require_once '../config/database.php';
require_once '../config/security.php';

// PDO connection for librarian module
$connection = $pdo;
?>