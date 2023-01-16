<?php
namespace App\Model\Service\Upload;

interface UploadInterface
{
    public function receiveFile(Array $file): Object;
}