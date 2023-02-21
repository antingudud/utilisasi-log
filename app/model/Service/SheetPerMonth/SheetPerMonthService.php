<?php
namespace App\Model\Service\SheetPerMonth;

use App\Core\Database\AdapterInterface;
use App\Model\Repository\Transaction\Repo;

class SheetPerMonth
{
    protected $year;
    protected $month;
    protected $repo;

    public function __construct(?Int $selectedYear = NULL, ?Int $selectedMonth = NULL, AdapterInterface $adapter)
    {
        $this->year = $selectedYear;
        $this->month = $selectedMonth;
        $this->repo = new Repo($adapter);
    }

    public function getPrettySheet(): Array
    {
        $year = $this->year? $this->year : date('Y');
        $month = $this->month? $this->month : date('n');

        return $this->repo->getSpreadsheetView($year, $month);
    }
}