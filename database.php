<?php
class database {

    private $connection;
    private $servername = "localhost"; 
    private $databasename = "json_project"; 
    private $username = "json_reader";
    private $password = "12345678";

    function __construct() {
        try {
            $this->connection = new PDO("mysql:host=$this->servername;dbname=$this->databasename", $this->username, $this->password);

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            throw new Exception("Error ". $e->getMessage());
        }
    }

    function register($name, $password) {
        try {
            $stmt = $this->connection->prepare("INSERT INTO users (name, password) VALUES (:name, :password)");

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            
        } catch(PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    function login($name, $password) {
        $stmt = $this->connection->prepare("SELECT id FROM users WHERE name = :name AND password = :password");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            return true;
        } 

        return false;
    }

    function close() {
        $this->connection->close();
    }
}
?>
