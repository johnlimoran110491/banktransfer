<?php
require('db_config.php');
class MySQL extends MYSQLDBCONFIG{
    
    
    private $connection;
    
    public function __construct(){
        $db = new MYSQLDBCONFIG();
        
        try{
            $this->connection = new PDO("mysql:host=$db->dbhost;dbname=$db->dbname", $db->dbuser, $db->dbpwd);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die("Can't connect to database");
        }
    }
    
    public function Connect(){
        return $this->connection;
    }
}
?>