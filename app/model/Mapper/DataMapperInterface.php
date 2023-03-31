<?php

namespace App\Model\Mapper;

interface DataMapperInterface
{
    public function find(Array $filter = [], $one = FALSE);
    public function findById($id);
    public function insert();
    public function update();
    public function save();
    public function delete();
}