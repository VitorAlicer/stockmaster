<?php
class MysqlDB{
   private $con;
   private $str;
   private $usr;
   private $pas;

   function __construct(){
      try {

         $this->str = 'mysql:host=sm-mysql;dbname=stockmaster;charset=utf8';
         $this->usr = 'root';
         $this->pas = 'root';

         $this->con = new PDO($this->str, $this->usr, $this->pas);
         $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
         $this->logger("connect", $e);
         throw $e;
      }
   }

   function execute($query) {
      try {
         $this->con->exec($query);
         return true;
      } catch(PDOException $e) {
         $this->logger("execute", $e);
         throw $e;
      }
   }

   function query($query) {
      try {
         $stmt = $this->con->prepare($query);
         $stmt->execute();
         return $stmt;
      } catch(PDOException $e) {
         $this->logger("query", $e);
         throw $e;
      }
   }

   function getLastGeneratedId() {
      try {
         return $this->con->lastInsertId();
      } catch(PDOException $e) {
         $this->logger("getLastGeneratedId", $e);
         throw $e;
      }
   }

   function fetchRow($stmt) {
      try {
         return $stmt->fetch(PDO::FETCH_OBJ);
      } catch(PDOException $e) {
         $this->logger("fetchRow", $e);
         throw $e;
      }
   }

   function fetchRowArray($stmt) {
      try {
         return $stmt->fetch(PDO::FETCH_ASSOC);
      } catch(PDOException $e) {
         $this->logger("fetchRowArray", $e);
         throw $e;
      }
   }

   function rowCount($stmt) {
      return $stmt->rowCount();
   }

   public function freeResult($stmt) {
      $stmt->closeCursor();
      $stmt = null;
   }

   public function close() {

   }

   private function logger($func, $e) {
      logError::log_error($func, $e, defined('USUARIO')?USUARIO:'SYSTEM');
   }

}

class logError{
   public static function log_error($func, $error, $user) {
      if ($logLine = fopen("_mysql_errors.log", "a")) {
         $out = var_export($error, TRUE);
         $message = date("[d-m-Y H:i:s] ")."[".$user."] Funçao MySQL : '".$func."' produziu este erro:\r\n".$out."\r\n";
         fwrite($logLine, $message);
         fclose($logLine);
      }
   }
}

?>
