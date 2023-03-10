<?php

namespace App\Model\Mapper\Transaction;

use App\Core\Database\AdapterInterface;

interface TransactionMapperInterface
{
    public function __construct(AdapterInterface $db);
    public function save();
    public function remove();
    public function find();
}