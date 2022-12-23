<?php
namespace App\Model;

class Transac
{
    public function getAlterForm()
    {
        return (new TransacService)->getAlterForm();
    }
}

class TransacService {
    private $mapper;

    function __construct()
    {
        $this->mapper = (new TransacMapper);
    }
    public function getAlterForm()
    {
        $result = $this->mapper->select(["DATE_FORMAT(dateTime, '%a, %e %b %Y') AS date", "TRIM(download_CR_Indihome)+0 AS dl_CR_Indihome", "TRIM(upload_CR_Indihome)+0 AS ul_CR_Indihome", "TRIM(download_CP_Indihome)+0 AS dl_CP_Indihome", "TRIM(upload_CP_Indihome)+0 AS ul_CP_Indihome", "TRIM(download_PK_Biznet)+0 AS dl_PK_Biznet", "TRIM(upload_PK_Biznet)+0 AS ul_PK_Biznet", "TRIM(download_PK_Indosat)+0 AS dl_PK_Indosat", "TRIM(upload_PK_Indosat)+0 AS ul_PK_Indosat", "TRIM(download_CK_Orbit)+0 AS dl_CK_Orbit", "TRIM(upload_CK_Orbit)+0 AS ul_CK_Orbit", "TRIM(download_CK_XL)+0 AS dl_CK_XL", "TRIM(upload_CK_XL)+0 AS ul_CK_XL"], 'util_pivotted', [1=>1], "", "ORDER By dateTime ASC")->fetch_all(MYSQLI_ASSOC);
        return $result;
    }
}

use App\Model\DataMapper;
class TransacMapper extends DataMapper
{

}