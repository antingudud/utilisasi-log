<?php
class TransactionModel extends ConnectDB{
    protected function getTransac(){
        $sql = "SELECT 
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
                    user.userNIK = transaction.userNIK";
        $stmt = $this->connectTo()->query($sql);
        #$stmt->execute();

        #$getresult = $stmt->get_result();
        $result = $stmt->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    protected function getTransacFromCategory($category){
        $sql = "SELECT 
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

    protected function setTransac($download, $upload, $idDevice){
        $idTrx = substr(uniqid(),5);
        $username = "mamangus";
        $sql = "INSERT INTO
                    transaction(
                        idTrx,
                        dateTime,
                        download,
                        upload,
                        userNIK,
                        dateCreated,
                        dateModified,
                        groupId,
                        idDevice
                        )
                VALUES (
                    ?,
                    now(),
                    ?,
                    ?,
                    (SELECT userNIK FROM user WHERE username COLLATE utf8mb4_bin = ?),
                    now(),
                    '',
                    (SELECT groupId FROM user WHERE username COLLATE utf8mb4_bin = ?),
                    ?
                    )";
        $stmt = $this->connectTo()->prepare($sql);
        $stmt->bind_param("sddsss",$idTrx, $download, $upload, $username, $username, $idDevice);
        $stmt->execute();
    }
}
?>