<?php
namespace App\Model\Transaction;
use App\Core\ConnectDB;
class TransactionModel{
    protected $db;
    function __construct()
    {
        $this->db = (new ConnectDB)->connectTo();
    }

    protected function getTransac(){
        $sql = "SELECT 
                    idTrx,
                    UNIX_TIMESTAMP(dateTime), 
                    device.nameDevice, 
                    category.nameCategory, 
                    TRIM(transaction.download)+0 as download, 
                    TRIM(transaction.upload)+0 as upload,
                    user.fullname,
                    transaction.dateCreated, 
                    transaction.dateModified
                FROM 
                    device 
                RIGHT JOIN 
                    transaction 
                ON 
                    device.idDevice = transaction.idDevice 
                LEFT JOIN 
                    category 
                ON 
                    category.idCategory = device.idCategory
                LEFT JOIN
                    user
                ON
                    user.userNIK = transaction.userNIK
                ORDER BY
                    dateTime,
                    device.nameDevice
                ASC";
        $stmt = $this->db->query($sql);
        #$stmt->execute();

        #$getresult = $stmt->get_result();
        $result = $stmt->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    protected function getTransacFromCategory($category){
        $sql = "SELECT 
                    idTrx,
                    transaction.dateTime, 
                    device.nameDevice, 
                    category.nameCategory, 
                    transaction.download, 
                    transaction.upload,
                    user.fullname,
                    transaction.dateCreated, 
                    transaction.dateModified
                FROM 
                    device 
                LEFT JOIN 
                    transaction 
                ON 
                    device.idDevice = transaction.idDevice 
                LEFT JOIN 
                    category 
                ON 
                    category.idCategory = device.idCategory
                LEFT JOIN
                    user
                ON
                    user.userNIK = transaction.userNIK
                WHERE
                    category.nameCategory = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $category);
        $stmt->execute();

        $getresult = $stmt->get_result();
        $result = $getresult->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    protected function queryTransaction($query, $params, $action = null, $types = null){
        $stmt = $this->db->prepare($query);
        $types = $types ?: str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        if ($action == "select"){
            header('Content-Type: application/json');
            $array = Array();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            foreach($result as $row)
            {
                $array[] = $row;
            }
            return json_encode($array, JSON_NUMERIC_CHECK);
        }
        return $stmt;
    }

    protected function prepared_select($query, $params = [], $types = "") {
        $array = Array();
        $result = $this->queryTransaction($query, $params, null , $types)->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach($result as $data)
        {
            $array[] = $data;
        }
        echo json_encode($array, JSON_NUMERIC_CHECK);
    }

    function prepared_query($query, $params, $types = "")
{
    $types = $types ?: str_repeat("s", count($params));
    $stmt = $this->db->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt;
}

    protected function delTransac($id){
        if(count($id) >= 1){
            $placeholders = str_repeat('?,', count($id) - 1) . '?';
            $types = str_repeat("s", count($id));
            $respond = "success";

            $sql = "DELETE FROM
                        transaction
                    WHERE
                        idTrx
                    IN
                        (
                         " . $placeholders . "   
                        )";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($types, ...$id);
            $stmt->execute();
            return $respond;
        } 
        else {
            print "It's empty";
        }
    }

    protected function create(Array $params){
        $query = "INSERT INTO transaction( idTrx, dateTime, download, upload, userNIK, dateCreated, dateModified, groupId, idDevice ) VALUES ( ?, ?, ?, ?, (SELECT userNIK FROM user WHERE username COLLATE utf8mb4_bin = ?), now(), '', (SELECT groupId FROM user WHERE username COLLATE utf8mb4_bin = ?), ? )";
        $this->queryTransaction($query, $paramsReqeust = [$params['idTrx'], $params['date'], $params['download'], $params['upload'], $params['username'], $params['username'], $params['idDevice']], $types = "sddssss");
    }

    protected function read(String $query, Array $params, String $action){
        $this->queryTransaction($query, $params, $action);
    }
    
    protected function update(Array $idCount, Array $params, String $types){
        $dlulPlaceholder = "";
        foreach ($idCount as $index => $idx){
            $dlulPlaceholder .= "WHEN idTrx = ? THEN ? ";
        }
        $query = "UPDATE transaction SET download = CASE ". $dlulPlaceholder . "END, upload = CASE " . $dlulPlaceholder . "END, dateModified = now() WHERE idTrx IN ( " . str_repeat('?,', count($idCount) - 1) . '?' . " )";
        print_r($query);
        echo "<br>";
        print_r($params);
        echo "<br>";
        print_r($types);
        $this->queryTransaction($query, $params, "", $types);
    }

    protected function delete($id){
        $placeholders = str_repeat('?,', count($id) - 1) . '?';
        $types = str_repeat("s", count($id));
        $query = "DELETE FROM
                        transaction
                    WHERE
                        idTrx
                    IN
                        (" . $placeholders . ")";
        $this->queryTransaction($query, $id, $types);
    }

    protected function selectUpdate($id){
        $params = $id;
        $query = "SELECT idTrx, UNIX_TIMESTAMP(dateTime), category.nameCategory, device.nameDevice, download, upload FROM device RIGHT JOIN transaction ON device.idDevice = transaction.idDevice LEFT JOIN category ON category.idCategory = device.idCategory WHERE idTrx";
        if(count($params) > 1){
            $placeholders = str_repeat('?,', count($params) - 1) . '?';
            $query .= " IN ( {$placeholders} ) ORDER BY dateTime ASC";
            $this->prepared_select($query, $params);
        } else {
            $query .= " = ?";
            $this->prepared_select($query, $params);
        }
    }

    public function selectAlternateForm(){
        $query = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(dateTime), '%a, %e %b %Y') AS date, TRIM(download_CR_Indihome)+0 AS dl_CR_Indihome, TRIM(upload_CR_Indihome)+0 AS ul_CR_Indihome, TRIM(download_CP_Indihome)+0 AS dl_CP_Indihome, TRIM(upload_CP_Indihome)+0 AS ul_CP_Indihome, TRIM(download_PK_Biznet)+0 AS dl_PK_Biznet, TRIM(upload_PK_Biznet)+0 AS ul_PK_Biznet, TRIM(download_PK_Indosat)+0 AS dl_PK_Indosat, TRIM(upload_PK_Indosat)+0 AS ul_PK_Indosat, TRIM(download_CK_Orbit)+0 AS dl_CK_Orbit, TRIM(upload_CK_Orbit)+0 AS ul_CK_Orbit, TRIM(download_CK_XL)+0 AS dl_CK_XL, TRIM(upload_CK_XL)+0 AS ul_CK_XL FROM `util_pivotted` WHERE ? ORDER By dateTime ASC";
        $params = [1];
        return $this->prepared_select($query, $params);
    }

    public function selectMainForm(){
        $query = "SELECT idTrx, DATE_FORMAT(dateTime, '%a, %e %b %Y') AS date, device.nameDevice, category.nameCategory, TRIM(transaction.download)+0 as download, TRIM(transaction.upload)+0 as upload, user.fullname, transaction.dateCreated, transaction.dateModified FROM device RIGHT JOIN transaction ON device.idDevice = transaction.idDevice LEFT JOIN category ON category.idCategory = device.idCategory LEFT JOIN user ON user.userNIK = transaction.userNIK WHERE 1 = ? ORDER BY dateTime, device.nameDevice ASC";
        return $this->prepared_query($query, [1])->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}