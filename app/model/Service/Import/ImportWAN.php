<?php
namespace App\Model\Service\Import\ImportWAN;

use App\Model\Service\Import\ImportInterface;
use App\Model\Service\Log\Log;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use stdClass;
use \PhpOffice\PhpSpreadsheet\Calculation\Functions;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class ImportWAN implements ImportInterface
{
    private Log $log;
    private Object $file;
    private Object $worksheet;
    private Object $spreadsheet;
    private Array $range;
    private Array $rows;

    public function __construct(Log $log)
    {
        $this->log = $log;
    }
    
    /**
     * Read .xls spreadsheet and import the content into database.
     * @param Object $file The file to read
     */
    public function import(Object $file): Array
    {
        $this->file = $file;
        $this->processFile();
        
        $this->readSpreadsheet($this->file->tmp_name, "6");
        $this->range = $this->getRange();
        foreach($this->range as $key => $value)
        {
            $result[] = $this->createCollection($this->worksheet, $value);
        }
        foreach($result as $key => $value)
        {
            $newRes[] = $this->convert($value);
        }
        // print_r($newRes[0][0]);
        $this->rows = $newRes;
        $this->createCollectionObject();

        $this->spreadsheet->disconnectWorksheets();
        unset($this->spreadsheet);
        return $newRes;
    }

    private function processFile()
    {
        $newDir = str_replace($this->file->name, "WAN.xls", $this->file->tmp_name);
        $newName = "WAN.xls";
        $this->rewrite($newDir); 
        $this->file->name = $newName;
        $this->file->tmp_name = $newDir;
    }

    private function createCollectionObject()
    {
        foreach($this->rows as $key => $value)
        {
            foreach($value as $keys => $values)
            {
                $date = date("Y-m-d",($values[0][0] - 25569) * 86400);
                // CR Indihome
                $this->log->log($values[1][0], $values[1][1], $date, "43ert2sf");
                // CP Indihome
                $this->log->log($values[2][0], $values[2][1], $date, "mb894js");
                // PK Biznet
                $this->log->log($values[3][0], $values[3][1], $date, "t234gds");
                // PK Indosat
                $this->log->log($values[4][0], $values[4][1], $date, "4s234uy");
                // CK Orbit
                $this->log->log($values[5][0], $values[5][1], $date, "53gbf2f");
                // CK XL
                $this->log->log($values[6][0], $values[6][1], $date, "hkn6d34");
            }
        }
    }

    private function rewrite(String $dir): bool
    {
        return rename($this->file->tmp_name, $dir)? true : false;
    }

    /**
     * Convert array collection into database friendly format
     * @param Array $import array to convert
     * @return Array
     */
    private function convert(Array $import)
    {
        $i = 0;
        foreach($import[0][array_keys($import[0])[0]] as $key => $value)
        {
            $arr[] = [ 
                $import[0] [array_keys($import[0])[0]] [$i],
                $import[1] [array_keys($import[1])[0]] [$i],
                $import[2] [array_keys($import[2])[0]] [$i],
                $import[3] [array_keys($import[3])[0]] [$i],
                $import[4] [array_keys($import[4])[0]] [$i],
                $import[5] [array_keys($import[5])[0]] [$i],
                $import[6] [array_keys($import[6])[0]] [$i]
            ];
            $i++;
        }
        return $arr;
    }

    /**
     * get range of cells to iterate through based on the specied mode
     * 
     * @param String $mode Specify range to find based on the file
     * @return Array
     */
    private function getRange(String $mode = "wan"): Array
    {
        switch($mode)
        {
            case "wan":
                $a = 1;
                $b = 0;
                $string = "";
                $arr=[];
                $range=[["B","B"],["C","D"],["E","F"],["G","H"],["I","J"],["K","L"],["M","N"]];
                $newr=[];
                
                for($i = 108; $i <= 265; $i++)
                {
                    $tmp_arr = [];
                    $i++;
	                if($a % 3 == 0)
	                {
	                	$i--;
	                }

	                $tmp_arr[] = $i;

	                if($a % 4 == 0)
	                {
	                	$i--;
	                }

	                $i = $i + 30;
                    
	                $tmp_arr[] = $i;
                    array_push($arr, $tmp_arr);
	                $a++;
                }
                foreach($arr as $key => $value)
                {
                    $start = $value[0];
                    $end = $value[1];
                    $tmp_arr = [];
                    for($i = 0; $i < count($range); $i++)
                    {
                        $tmp_arr[] =  $range[$i][0] . $start . ":" . $range[$i][1] . $end;
                    }
                    $string = implode(',', $tmp_arr);
                    $string = explode(',', $string);
                    array_push($newr, $string);
                }
                return $newr;
                break;
            default:
                return false;
        }
    }

    private function readSpreadsheet(String $file, $sheet): Void
    {
        $reader = new Xls;

        $reader->setLoadSheetsOnly($sheet);
        $spreadsheet = $reader->load($file);
        $this->worksheet = $spreadsheet->getActiveSheet();
        $this->spreadsheet = $spreadsheet;
    }

    private function sheetToArray(Object $worksheet, String $range): Array
    {
        $array = $worksheet->rangeToArray(
            $range,
            0,
            TRUE,
            FALSE,
            FALSE
        );
        return $array;
    }
    private function createCollection(Object $worksheet, Array $range): Array
    {
        foreach($range as $key => $value)
        {
            $data[] = [$key => $this->sheetToArray($worksheet, $value)];
        }
        return $data;   
    }

    private function filter()
    {

    }
    private function sanitize()
    {

    }
}