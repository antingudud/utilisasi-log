<?php
namespace App\Model\Mapper\Generic;

use App\Model\Mapper\DataMapperInterface;
use App\Core\Database\AdapterInterface;

abstract class AbstractMapper implements DataMapperInterface
{
    protected $adapter;
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
    public function find(Array $filter = [], $one = FALSE)
    {

    }
    public function findById($id)
    {
        
    }
    public function insert()
    {

    }
    public function update()
    {
        
    }
    public function save()
    {

    }
    public function delete()
    {

    }
}