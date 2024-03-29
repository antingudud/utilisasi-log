<?php

namespace App\Model\Mapper\Transaction;

use App\Model\Transac;
use App\Core\Database\AdapterInterface;
use App\Model\Mapper\Device\DeviceMapper;
use App\Model\Mapper\User\UserMapper;

class Mapper
{
    private $db;
    private $user;
    private $device;
    private $transactionCollection;
    // private 

    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    public function getInstance(AdapterInterface $adapter)
    {
        $this->db = $adapter;
        return $this;
    }

    public function setUserMapper()
    {
        $this->user = new UserMapper($this->db);
        return $this;
    }

    public function setDeviceMapper()
    {
        $this->device = new DeviceMapper($this->db);
        return $this;
    }

    /**
     * @param array $filter WHERE clause
     * @param bool $one Map one transaction or not
     */
    public function find(Array $filter = [], $one = FALSE)
    {
        $rows = $this->db->select(['*'], "transaction", $filter)->fetch_all(MYSQLI_ASSOC);
        if($one)
        {
            return $this->createTransaction($rows[0]);
        }

        return $this->createTransactionCollection($rows);
    }

    public function save(Transac $tr)
    {
        $modified = $tr->getDateModified()?: '0000-00-00 00:00:00';

        if($this->exists($tr))
        {
            return $this->db->update(
                "transaction",
                ['download' => $tr->getDownload(), 'upload' => $tr->getUpload(), 'dateModified' => $modified],
                ['idTrx' => $tr->getId()],
                "ddss"
            );
        }

        return $this->db->insert
        (
            "transaction",
            [
                'idTrx' => $tr->getId(),
                'dateTime' => $tr->getDate(),
                'download' => $tr->getDownload(),
                'upload' => $tr->getUpload(),
                'userNIK' => $tr->getUser()->getNIK(),
                'dateCreated' => $tr->getDateCreated(),
                'dateModified' => $modified,
                'groupId' => $tr->getUser()->getGroupId(),
                'idDevice' => $tr->getDevice()->getIdDevice()
            ]
        );
    }
    public function remove(Transac $tr)
    {
        return $this->db->delete("transaction", ['idTrx' => $tr->getId()]);
    }
    
    public function exists(Transac $tr)
    {
        return $this->db->select(['COUNT(*)'], 'transaction', ['idTrx' => $tr->getId()])->fetch_row()[0]? true : false;
    }
    
    protected function createTransaction(array $row = [])
    {
        $tr = new Transac;
        
        $user = $this->user->find(['userNIK' => $row['userNIK']], true);
        $device = $this->device->find(['idDevice' => $row['idDevice']], true);

        $tr->setId($row['idTrx']);
        $tr->setDate($row['dateTime']);
        $tr->setDownload($row['download']);
        $tr->setUpload($row['upload']);
        $tr->addUser($user);
        $tr->setDateCreated($row['dateCreated']);
        $tr->setDateModified($row['dateModified']);
        $tr->setDevice($device);

        return $tr;
    }
    protected function createTransactionCollection(array $rows)
    {
        /*
        * Iterate through rows.
        * Create a Transaction object for each rows, with column names/values as properties.
        * Push Transaction object to collection.
        * Return collections's content.
        */
        return $this->transactionCollection;
    }
}
