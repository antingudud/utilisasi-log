<?php
class Transaction extends TransactionModel{
    public function index(){
        View::render('Transaction/index.php');
    }
    public function new(){
        View::render('Transaction/new.php');
    }
    public function edit(){ 
        View::render('Transaction/update.php');
    }
    public function showTransac(){
        $result = $this->getTransac();
        return $result;
    }
    public function submitIndex($params){
        $idTrx = substr(uniqid(),5);
        $username = "dummy";
        $query = "INSERT INTO transaction(
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
                        ?,
                        ?,
                        ?,
                        (SELECT userNIK FROM user WHERE username COLLATE utf8mb4_bin = ?),
                        now(),
                        '',
                        (SELECT groupId FROM user WHERE username COLLATE utf8mb4_bin = ?),
                        ?
                        )";
        TransactionModel::queryTransaction($query, $paramsReqeust = [$idTrx, $params['date'], $params['download'], $params['upload'], $username, $username, $params['idDevice']], $types = "sddssss");
    }
    public function deleteIndex($id){
        $placeholders = str_repeat('?,', count($id) - 1) . '?';
        $types = str_repeat("s", count($id));
        $query = "DELETE FROM
                        transaction
                    WHERE
                        idTrx
                    IN
                        (" . $placeholders . ")";
        TransactionModel::queryTransaction($query, $id, $types);
    }
    public function getTransaction($query, $params, $action){
        TransactionModel::queryTransaction($query, $params, $action);
    }
}
?>