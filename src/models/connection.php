<?php
class Connection 
{
    private $host;
    private $login;
    private $pass;
    private $dbName;

    public function getHost(){
        return $this->host;
    }
    public function setHost($host){
        $this->host = $host;
    }

    public function getLogin(){
        return $this->login;
    }
    public function setLogin($login){
        $this->login = $login;
    }

    public function getPass(){
        return $this->pass;
    }
    public function setPass($pass){
        $this->pass = $pass;
    }

    public function getDbName(){
        return $this->dbName;
    }
    public function setDbName($dbName){
        $this->dbName = $dbName;
    }

    public function __construct($host=Null,$login=Null, $pass=Null, $dbName=Null)
    {
        $this->host = $host;
        $this->login = $login;
        $this->pass = $pass;
        $this->dbName = $dbName;
    }
}

?>