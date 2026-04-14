<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'mario_motors_db';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
           // $db = new PDO('mysql:host='.$db_host.';dbname='.$db_database, $db_user, $db_pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>