<?php
class Category extends ConnectDB {
    public $conn;
    private $unique;
    private $name;
    private $description;
    private $stmt;

    public function __construct($conn, $unique, $name, $description)
    {   
        $this->conn     = $conn;
        $this->conn     = $unique;
        $this->conn     = $name;
        $this->conn     = $description;
    }

    public function __destruct()
    {
        echo "<script>console.log('Destructed')</script>";
    }
}
?>