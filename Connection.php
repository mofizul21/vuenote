<?php

class Connection{
    private $dbhost = "localhost";
    private $dbname = "vuetut";
    private $dbuser = "root";
    private $dbpass = "123456";
    public $db;

    public function __construct()
    {
       $this->connectDB(); 
    }

    private function connectDB(){
        try {
            $this->db = new PDO("mysql:host={$this->dbhost}; dbname={$this->dbname}", $this->dbuser, $this->dbpass);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
           echo "Connection error: ".$e->getMessage();
        }
    }
}