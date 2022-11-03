<?php
function insertData()
{
    global $conn;
    $unique = substr(uniqid(),5,8);
    $name = uniqid();
    $description = uniqid();

    $qry = "INSERT INTO category (idCategory, nameCategory, description) VALUES (?,?,?)";
    $stmt = $conn->prepare($qry);
    $stmt-> bind_param("sss", $unique, $name, $description);
    $stmt->execute();
}
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

    public function insert($conn, $unique, $name, $description, $stmt){
        $this->connectTo()->conn             = $conn;
        $this->unique           = $unique;
        $this->name             = $name;
        $this->description      = $description;
        $qry                    = "INSERT INTO category (idCategory, nameCategory, description) VALUES (?,?,?)";
        $this->stmt             = $stmt = $conn->prepare($qry);
        $stmt->bind_param("sss", $unique, $name, $description);
        $stmt->execute();

    }

    public function __destruct()
    {
        echo "<script>console.log('Destructed')</script>";
    }
}
class Query extends ConnectDB{
    public function getDevice(){
        $sql = "SELECT device.nameDevice, transaction.idDevice, category.nameCategory FROM device LEFT JOIN 
        transaction ON device.idDevice = transaction.idDevice LEFT JOIN category ON category.idCategory = device.idCategory";
        $fetchDevice = $this->connectTo()->query($sql);
        $deviceName = $fetchDevice->fetch_assoc();
        return $deviceName;
    }
}
?>