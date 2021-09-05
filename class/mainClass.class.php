<?php
include_once "include/include.php";
class MainClass
{
    protected $data = array();

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = $value;
            }
        }

    }

    public function __get($property)
    {

        if (array_key_exists($property, $this->data))
            return $this->data[$property];

        else
            die("Invalid Property ! ");
    }

    protected static function connect()
    {
        $setNames = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8");
        $connect = new PDO("mysql:host=".HOST_NAME.";dbname=".DB_NAME,DB_USER, DB_PASS, $setNames);
        return $connect;
    }

    protected static function disconnect($connect)
    {
        unset($connect);
    }


}