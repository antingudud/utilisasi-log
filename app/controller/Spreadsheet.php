<?php
namespace App\Controller;

use App\Core\ConnectDB;
use App\Core\Database\MysqliAdapter;
use App\View\View;
use App\Model\Repository\Transaction\Repo;
use App\Model\Repository\Device\DeviceRepo;

class SpreadsheetController
{

    public function index(array $data = null)
    {
        $params=['data' => [
            'devices' => ''
        ]];

        if(isset($data))
        {
            if(isset($data['data']))
            {
                $data = $data['data'];
                $params['data']['table'] = $data;
            } else if(!is_array($data))
            {
                // Do exception handling
            }
        }
        
        $view = new View('Spreadsheet/index', $params);
        return $view->render();
    }

    public function populateTable(Array $data)
    {
        $repo = new Repo(new MysqliAdapter(new ConnectDB));
        $validation = $this->validate($data);
        if($validation)
        {
            $year = $data['year'];
            $month = $data['month'];
            $devices = $data['devices'];
            if(is_string($devices))
            {
                $devices = [$devices];
            }
            $spreadsheet = json_decode(strval($repo->cookSpreadsheet($devices, $month, $year)), true);
        } else if(is_array($validation) && isset($validation['status']) && $validation['status'] === "exception")
        {
            // Do exception handling
        }
    }

    public function getDeviceList()
    {
        $adapter = new MysqliAdapter(new ConnectDB);
        $deviceRepo = new DeviceRepo($adapter);
        
        $res = $deviceRepo->fetchAll();
        return $res;
    }

    /** 
     * Validates input.
     * 
     * @param array $data Data must be an array with 3 subarrays with the keys of "month", "year", "devices"
     * @return bool|array Returns true if valid, returns error array if invalid.
     */
    protected function validate(Array $data)
    {
        $error = ["status" => "exception"];
        if(count($data) === 3)
        {
            if(isset($data['month'], $data['year'], $data['devices']))
            {
                $month = $data['month'];
                $year = $data['year'];
                $devices = $data['devices'];

                if(!filter_var($month, FILTER_VALIDATE_INT))
                {
                    $error["message"] = "Month should be a number.";
                    return $error;
                }
                else if(intval($month))
                {
                    $month = intval($month);
                    if($month < 1 || $month > 12)
                    {
                        $error["message"] = "Month should be a number ranging from 1 to 12.";
                        return $error;
                    }
                } else{
                    $error["message"] = "Month should be a number.";
                    return $error;
                }
                
                if(!filter_var($year, FILTER_VALIDATE_INT))
                {
                    $error["message"] = "Year should be a number.";
                    return $error;
                }
                else if(intval($year))
                {
                    $year = intval($year);
                    if(strlen($year) !== 4)
                    {
                        $error["message"] = "Year falls outside the valid range.";
                        return $error;
                        
                    }
                    if($year < 2011 || $year > date('Y'))
                    {
                        $error["message"] = "Year falls outside the valid range.";
                        return $error;
                    }
                } else{
                    $error["message"] = "Year should be a number.";
                    return $error;
                }

                if(is_array($devices))
                {
                    if(count($devices) === 0)
                    {
                        $error["message"] = "Empty request, please check your input again.";
                        return $error;
                    }
                    foreach ($devices as $key => $value) {
                        if(!is_string($value)){
                            $error["message"] = "Device ID's is not a string. Please check your inputs again.";
                            return $error;
                        }
                        if(strlen($value) > 8 || strlen(($value)) < 7)
                        {
                            $error["message"] = "Device ID's is less than 7 or more than 8 characters long. Please check your inputs again.";
                            return $error;
                        }
                        if(preg_match("/[^\w\d]+/u", $value))
                        {
                            $error["message"] = "Illegal character.";
                            return $error;
                        }
                    }
                } else if(is_string($devices))
                {
                    if(strlen($devices) > 8 || strlen(($devices)) < 7)
                    {
                        $error["message"] = "Device ID's is less than 7 or more than 8 characters long. Please check your input again.";
                        return $error;
                    }
                    if(preg_match("/[^\w\d]+/u", $devices))
                    {
                        $error["message"] = "Illegal character.";
                        return $error;
                    }
                } else
                {
                    $error["message"] = "Incomplete request, please check your input again.";
                    return $error;
                }
                
                return true;
            } else{
                $error["message"] = "Incomplete request, please check your input again.";
                return $error;
            }
        } else {
            $error["message"] = "Invalid request, please check your input again.";
            return $error;
        }
    }
}