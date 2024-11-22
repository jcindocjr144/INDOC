<?php
class Database {
    private $host = "localhost";
    private $db_name = "ctuCordb";
    private $username = "root";
    private $password = "";
    public $db;

    public function __construct() {
        try {
            // Establishing a PDO connection
            $this->db = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    public function getDb() {
        return $this->db;
    }

    // Executes a query and returns a PDOStatement
    public function query($sql) {
        return $this->db->query($sql);
    }

    // Fetch all results from a query
    public function fetchAll($query) {
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single result from a query
    public function fetchOne($query) {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
?>
