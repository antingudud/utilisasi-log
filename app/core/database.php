<?php
namespace App\Core;
class ConnectDB
{
    private $host;
    private $user;
    private $password;
    private $database;
    private $charset;
    
    function __construct()
    {
        $configFile = file_get_contents(dirname(__DIR__, 1 ) . "/config/config.json");
        $configContent = json_decode($configFile);
        $this->host = $configContent->{'host'};
        $this->user = $configContent->{'user'};
        $this->password = $configContent->{'password'};
        $this->database = $configContent->{'database'};
        $this->charset = $configContent->{'charset'};
    }
    function connectTo()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $conn = new \mysqli($this->host, $this->user, $this->password, $this->database);
            $conn->set_charset($this->charset);
            $conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
            return $conn;
        } catch (\mysqli_sql_exception $e) {
            throw new \mysqli_sql_exception($e->getMessage(), $e->getCode());
        }
    }
}