<?php
namespace App\Controller;

use App\Core\ConnectDB;
use App\Core\Database\MysqliAdapter;
use App\View\View;
use App\Model\Repository\Transaction\Repo;
use App\Model\Repository\Device\DeviceRepo;
use App\Model\Repository\User\Repo as UserRepo;
use DateTime;
use App\Model\Service\Log\Log;
use App\Model\Transaction\Exception\InvalidDate;
use App\Model\Transaction\Exception\InvalidValue;
use App\Model\Transaction\Exception\RecordExists;

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

    public function makeTable(Array $data)
    {
        if(isset($data['data']))
        {
            $data = $data['data'];
            $params['data']['table'] = $data;
            
            $view = new View('Spreadsheet/index', $params);
            return $view->renderOnlyContent();
        } else
        {
            $params['data']['table'] = $data;
            
            $view = new View('Spreadsheet/index', $params);
            return $view->renderOnlyContent();
        }
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
            } else if(is_array($devices) && count($devices) > 5)
            {
                $devices = array_slice($devices, 0 ,5);
            }
            $spreadsheet = json_decode(strval($repo->cookSpreadsheet($devices, $month, $year)), true);
        } else if(is_array($validation) && isset($validation['status']) && $validation['status'] === "exception")
        {
            // Do exception handling
        }
        echo json_encode($spreadsheet);
    }

    public function getDeviceList()
    {
        $adapter = new MysqliAdapter(new ConnectDB);
        $deviceRepo = new DeviceRepo($adapter);
        
        $data = $deviceRepo->fetchAll();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('data' => $data));
        die();
        // $res = $deviceRepo->fetchAll();
        // return $res;
    }

    public function edit(Array $data)
    {
        $adapter = new MysqliAdapter(new ConnectDB); 
        $repo = new Repo($adapter); $repo->setMapper(); $repo->setDeviceRepo();
        $repoUsr = new UserRepo($adapter); $service = new Log($repo, $repoUsr);
        $status["status"] = [];
        $status["action"] = "recording data";
        $status["message"] = "Data successfully recorded.";

        if(isset($data['date']) && count($data) >= 3)
        {
            try
            {
                $this->validateDate($data["date"]);
            } catch (InvalidDate $e)
            {
                $status["status"] = "failed";
                $status["message"] = "Invalid date.";
                return $this->notif($status["status"], $status["action"], $status["message"]);
            }
            $date = (DateTime::createFromFormat('D, j M Y', $data['date']))->format('Y-m-d');
            $ids = [];
            foreach($data as $key => $value)
            {
                if($key === "date") {continue;}
                if(preg_match('/download|upload/', $key, $matches))
                {
                    $id = preg_replace('/download|upload/', '', $key);
                    $ids[$id][$matches[0]] = $value;
                    $ids[$id]['date'] = $date;
                }
            }

            $attendanceList = [];
            foreach($ids as $key => $value)
            {
                if($repo->exists($key, $value['date']))
                {
                    $attendanceList["existing"][$key]['date'] = $value['date'];
                    $attendanceList["existing"][$key]['download'] = $value['download'];
                    $attendanceList["existing"][$key]['upload'] = $value['upload'];
                    continue;
                }
                $attendanceList["new"][$key]['date'] = $value['date'];
                $attendanceList["new"][$key]['download'] = $value['download'];
                $attendanceList["new"][$key]['upload'] = $value['upload'];
            }

            // TODO separate this from the controller. It looks ugly 
            if(isset($attendanceList['new']))
            {
                $adapter->beginTransaction();
                try {
                    foreach($attendanceList['new'] as $key => $value)
                    {
                        $service->log($value['download'], $value['upload'], $value['date'], $key);
                    }
                    $adapter->commitTransaction();
                    $status["logging"] = "success";
                } catch(\App\Model\Transaction\Exception\RecordExists $e)
                {
                    $status["logging"] = "failed";
                    $adapter->rollbackTransaction();
                } catch (\Exception $th) {
                    $status["logging"] = "failed";
                    $adapter->rollbackTransaction();
                }
            }
            if(isset($attendanceList['existing']))
            {
                $updateList = Array();
                foreach($attendanceList['existing'] as $idDevice => $details)
                {
                    $updateList[] = array(
                        "idDevice" => $idDevice,
                        "download" => $details["download"],
                        "upload" => $details["upload"],
                        "dateTime" => $details["date"]
                    );
                }
                try
                {
                    $repo->update($updateList);
                    $status["updating"] = "success";
                } catch(InvalidValue $e)
                {
                    $status["updating"] = "failed";
                    $status["message"] = "Invalid values.";
                } catch(\Exception $e)
                {
                    $status["updating"] = "failed";
                }
            }

            if( isset($status['logging'], $status['updating']) )
            {
                if( ( $status["logging"] == "success" || $status["logging"] === TRUE) && ( $status["updating"] == "success" || $status["updating"] === TRUE) ) 
                {
                    $status["status"] = "success";
                } else if ( ( $status["logging"] == "success" || $status["logging"] === TRUE  ) || ( $status["updating"] == "success" || $status["updating"] === TRUE ) )
                {
                    $status["status"] = "partial success";
                } else if( ( $status["logging"] == "failed" || $status["logging"] === FALSE ) && ( $status["updating"] == "failed" || $status["updating"] === FALSE ) )
                {
                    $status["status"] = "failed";
                    $status["message"] = "Failed to save data.";
                } else if( ( $status["logging"] == "failed" || $status["logging"] === FALSE ) || ( $status["updating"] == "failed" || $status["updating"] === FALSE ) )
                {
                    $status["status"] = "failed";
                    $status["message"] = "Failed to save data.";
                }  else
                {
                    $status["status"] = "failed";
                    $status["action"] = "not run";
                    $status["message"] = "Failed to save data.";
                }
            } else if( isset($status["updating"]) )
            {
                $update = $status["updating"];
                if($update == "success" || $update === true)
                {
                    $status["status"] = "success";
                } else
                {
                    $status["status"] = "failed";
                    $status["message"] = "Failed to save data.";
                }
            } else if( isset($status["logging"]) )
            {
                $log = $status["logging"];
                if($log == "success" || $log === true)
                {
                    $status["status"] = "success";
                } else
                {
                    $status["status"] = "failed";
                    $status["message"] = "Failed to save data.";
                }
            } else {
                $status["status"] = "failed";
                $status["message"] = "Not run";
            }

            return $this->notif($status["status"], $status["action"], $status["message"]);
        }
    }

    /** 
     * Validates table generation input.
     * 
     * @param array $data Data must be an array with 3 subarrays with the keys of "month", "year", "devices"
     * @return bool|array Returns true if valid, returns error array if invalid.
     */
    protected function validate(Array $data)
    {
        $error = ["status" => "failed"];
        $error["action"] = "validation";
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

    /**
     * Validate date. The required format is for example "Wed, 1 Mar 2023"
     * 
     * @param string $date Date to be validated
     * @return string|InvalidDate $date if successful. InvalidDate if false
     */
    protected function validateDate(String $date)
    {    
        $dateFormat = 'D, j M Y';
        $dateTime = DateTime::createFromFormat($dateFormat, $date);
        if($dateTime && $dateTime->format($dateFormat) == $date)
        {
            return $date;
        } else
        {
            throw new InvalidDate('D, j M Y');
        }
    }

    /**
     * Send response to notification handler
     * 
     * @param string $status success|failed|error|exception
     * @param string $action input|validation|whatever
     * @param string $message whatever
     */
    protected function notif(string $status, string $action, string $message)
    {
        if(!isset($status, $action, $message))
        {
            $status = "error";
            $action = "unknown";
            $message = "Unknown error.";
            http_response_code(500);
            echo json_encode(["status" => $status, "action" => $action, "message" => $message]);
            die();
        }
        if(preg_match("/failed|error|exception/", $status))
        {
            http_response_code(500);
        } else if(preg_match("/ok|success/", $status))
        {
            http_response_code(200);
        } else {http_response_code(500);}
        echo json_encode(["status" => $status, "action" => $action, "message" => $message]);
        die();
    }
}