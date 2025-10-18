<?php
/**
 * Modern PDO Database Connection
 * Replaces deprecated mysql_* functions with secure PDO
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'library_management';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    private $pdo;
    
    public function __construct() {
        $this->connect();
    }
    
    private function connect() {
        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // Log error and show user-friendly message
            error_log("Database connection failed: " . $e->getMessage());
            die("Database connection failed. Please check your database configuration.");
        }
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }
    
    public function query($sql) {
        return $this->pdo->query($sql);
    }
    
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}

// Global database instance
try {
    $db = new Database();
    $pdo = $db->getConnection();
} catch (Exception $e) {
    error_log("Database initialization failed: " . $e->getMessage());
    die("Database initialization failed. Please check your configuration.");
}
?>
