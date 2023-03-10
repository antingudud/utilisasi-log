<?php
namespace App\Model\Import;

interface ImportInterface
{
    public function __construct();
    public function processFile();
    public function createCollectionObject();
    public function createCollection();
    public function rewrite();
    public function getRange();
    public function sheetToArray();
    public function readSpreadsheet();
}