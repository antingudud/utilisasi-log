<?php
class Session {
    private bool $isStarted = false;

    protected function setUp()
    {
        if (session_status() === PHP_SESSION_ACTIVE){
            session_destroy();
        }
    }

    public function isStarted() : bool
    {
        $this->isStarted = session_status() === PHP_SESSION_ACTIVE;
        return $this->isStarted;
    }

    public function start() : bool
    {
        if($this->isStarted)
        {
            return true;
        }

        if(session_status() === PHP_SESSION_ACTIVE)
        {
            $this->isStarted = true;
            return true;
        }

        session_start();
        $this->isStarted = true;
        return true;
    }

    public function has(string $key)
    {

    }

    public function get(string $key)
    {

    }

    public function set(string $key, mixed $value)
    {

    }

    public function clear()
    {

    }

    public function remove(string $key)
    {

    }
}
?>