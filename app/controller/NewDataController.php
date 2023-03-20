<?php
namespace App\Controller;

use App\Core\ConnectDB;
use App\Core\Database\MysqliAdapter;
use App\Model\Repository\Transaction\Repo;
use App\Model\Repository\User\Repo as UserRepo;
use App\Model\Service\Log\Log;
use App\View\View;

class NewDataController
{
    public function index()
    {
        $View = (new View('resources/components/new'));
        return $View->render();
    }

    /**
     * Submit array of data to the database.
     * Data array must be like:
     * ['data' => [
     *      'download' => $download,
     *      'upload' => $upload,
     *      'date' => $date,
     *      'idDevice' => $idDevice
     *      ]
     * ]
     * 
     * @param array $data
     * @return bool
     */
    public function submit(Array $data)
    {
        $db = new ConnectDB;
        $adapter = new MysqliAdapter($db);
        $transact_repo = new Repo($adapter);
        $transact_repo->setMapper();
        $transact_repo->setDeviceRepo();

        $service = new Log($transact_repo, new UserRepo($adapter));

        $data = $data['data'];

        $download = floatval($data['download']);
        $upload = floatval($data['upload']);
        $date = strval($data['date']);
        $idDevice = strval($data['device']);

        return $service->log($download, $upload, $date, $idDevice);
    }
}