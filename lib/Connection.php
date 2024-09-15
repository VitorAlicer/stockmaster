<?php
require_once(__DIR__."/../lib/MYSQL.PDO.php");

/*
metodos da abstração do SGBD(DBMS):

$this->connection->query($query)
$this->connection->getLastGeneratedId()
$this->connection->fetchRow($stmt)
$this->connection->fetchRowArray($stmt)
$this->connection->rowCount($stmt)
$this->connection->freeResult($stmt)
$this->connection->close()

*/


class Connection{
   var $connection;
   var $results;

   function __construct(){
      try {
         $this->connection = new MysqlDB();
         $this->results = array();
      } catch(Exception $e) {
         throw $e;
      }
   }

   function query ($query, $parameters=null){
      if(isset($parameters)){
         $query = $this->replaceParameters($query, $parameters);
      }
      $stmt = $this->connection->query($query);
      return $stmt;
   }

   public function replaceParameters ($query, $parameters){
      if($parameters == null) return $query;
      foreach($parameters as $name => $value){
         $query = str_replace("#$name#", $value, $query);
      }
      return $query;
   }

   function getLastGeneratedId (){
      return $this->connection->getLastGeneratedId();
   }

   function fetchRow ($stmt){
      return $this->connection->fetchRow($stmt);
   }

   function fetchRowArray ($stmt){
      return $this->connection->fetchRowArray($stmt);
   }

   function execute ($query, $parameters=null){
      if(isset($parameters)){
         $query = $this->replaceParameters($query, $parameters);
      }
      return $this->connection->execute($query);
   }

   function singleResult ($query, $parameters=null){
      $stmt = $this->query($query, $parameters);
      return $this->fetchRow($stmt);
   }

   function allResults ($query, $parameters=null, $type="obj"){
      $stmt = $this->query($query, $parameters);
      if($stmt != null){
         $list = array();
         while($obj = ($type=="array") ? $this->fetchRowArray($stmt) : $this->fetchRow($stmt)){
            array_push($list, $obj);
         }
         return $list;
      }
      return null;
   }

   function rowCount ($query){
      $stmt = $this->query($query);
      if($stmt != null){
         return $this->connection->rowCount($stmt);
      }
      return null;
   }

   private function freeResult ($stmt){
      if($stmt != null){
         $this->connection->freeResult($stmt);
      }
   }

   function __destruct (){
      if($this->results) {
         foreach($this->results as &$stmt){
            $this->freeResult($stmt);
         }
         unset($this->results);
      }
      if($this->connection)
         $this->connection->close();
      unset($this->connection);
   }
}

