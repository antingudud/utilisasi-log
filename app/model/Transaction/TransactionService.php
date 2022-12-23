<?php
namespace App\Model\Transaction;
use App\Model\Transaction\TransactionModel;

class TransactionService extends TransactionModel{
    public function create_service($download, $upload, $date, $idDevice){
        $idTrx = substr(uniqid(),5);
        $username = "dummy";
        $this->create(["download" => $download, "upload" => $upload, "date" => $date, "idDevice" => $idDevice, "idTrx" => $idTrx, "username" => $username]);
    }
    public function read_service(Array $params){
        $query = $params['query'];
        $parametres = $params['params'];
        $action = $params['action'];
        $this->read($query, $parametres, $action);
    }
    public function delete_service($id){
        $this->delete($id);
    }
    public function update_service(Array $params){
        $idTrx = $params['idTrx'];
        $download = array_map("floatval", $params['download']);
        $upload = array_map("floatval", $params['upload']);
        $dl = [];
        $ul = [];
        
        $parametres = ['idTrx' => $idTrx, 'download' => $download, 'upload' => $upload];
        $types = str_repeat('sd', count($download) * 2) . str_repeat('s', count($idTrx));

        foreach($idTrx as $index=>$id){
            $idTrx[$index] = substr($idTrx[$index], 0, 8);
            array_push($dl, $idTrx[$index]);
            array_push($dl, $download[$index]);
            
            array_push($ul, $idTrx[$index]);
            array_push($ul, $upload[$index]);
            print_r($idTrx[$index] . "<br>");
        }
        
        $this->update($params['idTrx'], array_merge($dl, $ul, $idTrx), $types);
    }
    public function selectUpdate_service($id){
        $this->selectUpdate($id);
    }
    public function selectAlternateForm_service(){
        return $this->selectAlternateForm();
    }
    public function selectMainForm_service(){
        return $this->selectMainForm();
    }
}