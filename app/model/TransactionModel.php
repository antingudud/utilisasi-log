<?php
class TransactionModel extends ConnectDB{
    protected function getTransac(){
        $sql = "SELECT 
                    idTrx,
                    UNIX_TIMESTAMP(dateTime), 
                    device.nameDevice, 
                    category.nameCategory, 
                    transaction.download, 
                    transaction.upload,
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
                    dateTime
                ASC";
        $stmt = $this->connectTo()->query($sql);
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
        $stmt = $this->connectTo()->prepare($sql);
        $stmt->bind_param("s", $category);
        $stmt->execute();

        $getresult = $stmt->get_result();
        $result = $getresult->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    protected function queryTransaction($query, $params, $action = null, $types = null){
        $stmt = $this->connectTo()->prepare($query);
        $types = $types ?: str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        if ($action == "select"){
            $array = Array();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            foreach($result as $row)
            {
                $array[] = $row;
            }
            echo json_encode($array);
        }
        $result = $stmt->get_result();
        return json_encode($result);
        echo ($this->connectTo()->error);
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
            $stmt = $this->connectTo()->prepare($sql);
            $stmt->bind_param($types, ...$id);
            $stmt->execute();
            return $respond;
        } 
        else {
            print "It's empty";
        }
    }
}
?>