<?php
namespace App\Controller;

use App\Core\ConnectDB;
use App\Core\Database\MysqliAdapter;
use App\Model\Repository\Device\DeviceRepo;
use App\Model\Transaction\Exception\DeviceInexistent;
use App\Model\Transaction\Exception\InvalidCategory;
use App\Model\Transaction\Exception\InvalidID;
use App\Model\Transaction\Exception\InvalidName;
use App\Model\Transaction\Exception\RecordExists;
use App\View\View;

class DeviceController
{
    public function index()
    {
        $adapter = new MysqliAdapter(new ConnectDB); $repo = new DeviceRepo($adapter);
        // $devices = ;
        $params = ["data" => [
            "devices" => $repo->fetchAll()
        ]];
        $view = new View('Device/index', $params);
        return $view->render();
    }

    public function getAll()
    {
        $adapter = new MysqliAdapter(new ConnectDB); $repo = new DeviceRepo($adapter);
        $devices = $repo->fetchAll();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($devices);
        die();
    }

    public function edit(Array $POST)
    {
        $status = [
            "status" => "",
            "action" => "updating",
            "message" => ""
        ];
        $adapter = new MysqliAdapter(new ConnectDB); $repo = new DeviceRepo($adapter);
        if(!isset($POST['data']))
        {
            return;
        }
        $data = $POST['data'];
        try
        {
            $repo->update($data['name'], $data['id'], $data['category']);
            $status["status"] = "success";
            $status["message"] = "Edit successful.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch (DeviceInexistent $e)
        {
            $status["status"] = "failed";
            $status["message"] = "Device not found, try refreshing the page.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch (InvalidID $e)
        {
            $status["status"] = "failed";
            $status["message"] = "Edit failed, try refreshing the page.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch (InvalidName $e)
        {
            $status["status"] = "failed";
            $status["message"] = "Invalid name.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch (InvalidCategory $e)
        {
            $status["status"] = "failed";
            $status["message"] = "Category not found.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch (RecordExists $e)
        {
            $status["status"] = "failed";
            $status["action"] = "updating";
            $status["message"] = "Device already exists.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch (\Exception $e)
        {
            $status["status"] = "failed";
            $status["action"] = "updating";
            $status["message"] = "Unknown error.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        }
    }

    public function add(Array $POST)
    {
        $status = [
            "status" => "",
            "action" => "creating",
            "message" => ""
        ];

        $adapter = new MysqliAdapter(new ConnectDB); $repo = new DeviceRepo($adapter);
        if(!isset($POST['data']))
        {
            return;
        }
        $data = $POST['data'];
        try
        {
            $repo->createNew($data['name'], $data['category']);
            $status["status"] = "success";
            $status["message"] = "Added new device successfully.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch ( RecordExists $e)
        {
            $status["status"] = "failed";
            $status["message"] = "Cannot add, device already exists.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch (InvalidName $e)
        {
            $status["status"] = "failed";
            $status["message"] = "Invalid name.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch (InvalidCategory $e)
        {
            $status["status"] = "failed";
            $status["message"] = "Category not found.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch (\Exception $e)
        {
            $status["status"] = "failed";
            $status["message"] = "Unknown error.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        }
    }

    public function remove(Array $POST)
    {
        $adapter = new MysqliAdapter(new ConnectDB); $repo = new DeviceRepo($adapter);

        $data = json_decode(file_get_contents('php://input'), true);
        $data = $data['data'];

        $status["action"] = "deletion";

        try
        {
            $repo->remove($data['id']);
        }catch (DeviceInexistent $e)
        {
            $status["status"] = "failed";
            $status["message"] = "Device not found, try refreshing the page.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch (InvalidID $e)
        {
            $status["status"] = "failed";
            $status["message"] = "Failed to remove, try refreshing the page.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch (InvalidName $e)
        {
            $status["status"] = "failed";
            $status["message"] = "Invalid name.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch (InvalidCategory $e)
        {
            $status["status"] = "failed";
            $status["message"] = "Category not found.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } catch(\Exception $e)
        {
            $status["status"] = "failed";
            $status["action"] = "deletion";
            $status["message"] = "Unknown error.";
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($status);
            die();
        } 
    }

    /**
     * @param array $GET $_GET device = id
     */
    public function detail(Array $GET)
    {
        if(isset($GET['device']))
        {
            $id = $GET['device'];
        } else
        {
            $id = "undefined";
        }
        // $idvalidate = $this->validateId($id);
        // if(is_array($idvalidate))
        // {
        //     header('Content-Type: application/json; charset=utf-8');
        //     echo json_encode($idvalidate);
        // }

        $adapter = new MysqliAdapter(new ConnectDB); $repo = new DeviceRepo($adapter);
        
        $params = ["data" => [
            "devices" => $repo->fetchById($id)
        ]];
        $view = new View('Device/device', $params);
        return $view->render();
    }

    /**
     * Validate if id.
     * 
     * * Check if id > 8 or id < 7
     * 
     * @param mixed $id
     * @return array|bool true or error message array
     */
    protected function validateId($id)
    {
        $error["status"] = "error";
        $error["action"] = "validation";
        $error["message"] = "";

        if(count($id) > 8 || count($id) < 7)
        {
            $error["message"] = "Too long.";
            return $error;
        }
        return true;
    }
}