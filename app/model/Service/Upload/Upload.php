<?php
namespace App\Model\Service\Upload;
use App\Model\Service\Upload\UploadInterface;
use stdClass;

class Upload implements UploadInterface
{
    private $file;

    public function receiveFile(Array $file): Object
    {
        $target_dir = dirname(__DIR__, 4) . '/public/uploaded/';

        $this->file = $file;
        
        if(!$this->validate())
        {
            exit;
        }
        $this->sanitize();

        $target_file = $target_dir. basename($this->file["name"]);
        if( move_uploaded_file($this->file["tmp_name"], $target_file) )
        {
            $this->file["tmp_name"] = $target_file;
            chmod($target_file, 0640);
            $object = new stdClass();
            foreach($this->file as $key => $value)
            {
                $object->$key = $value;
            }
            return $object;
        }
    }
    
    private function validate(): bool
    {
        if(!is_uploaded_file($this->file["tmp_name"]))
        {
            header('HTTP/1.1 500 Internal Server Error');
            return false;
        }
        if($this->file["type"] != "application/vnd.ms-excel")
        {
            header('HTTP/1.1 500 Internal Server Error');
            return false;
        }
        if(empty($this->file))
        {
            header('HTTP/1.1 404 Not Found');
            return false;
        }
        return true;
    }
    private function sanitize(): void
    {
        $sanitized = filter_var($this->file["name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sanitized = str_replace(chr(0), '', $sanitized);
        $this->file["name"] = preg_replace('/[\/\\\]/', "", $sanitized);
    }
}