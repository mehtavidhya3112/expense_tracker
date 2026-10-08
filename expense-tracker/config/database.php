<?php
/**
 * Database Connection Class
 * Uses PDO for secure, prepared-statement based access to MySQL.
 *
 * IMPORTANT: Update the credentials below to match your local
 * MySQL / XAMPP / WAMP / MAMP setup before running the project.
 */

class Database
{
    private $host = "localhost";
    private $db_name = "expense_tracker";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            die(
                "Database connection failed: " . $exception->getMessage() .
                "<br>Please make sure MySQL is running and that you have imported " .
                "database/schema.sql, then check config/database.php."
            );
        }

        return $this->conn;
    }
}
