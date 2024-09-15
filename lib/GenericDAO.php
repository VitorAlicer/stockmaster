<?php
class GenericDAO {

   public $connection;

   public function __construct($conn){
      $this->connection = $conn;
   }

   public function logger($content){
      $fp = fopen("MYSQL.log", "a+");
      fwrite($fp, $content."\n");
      fclose($fp);
   }

   public function routeURL($title){
      $url = strtolower($title);
      $url = preg_replace("[áàâãäª]", "a", $url);
      $url = preg_replace("[éèêë&]", "e", $url);
      $url = preg_replace("[íìîï]", "i", $url);
      $url = preg_replace("[óòôõöº]", "o", $url);
      $url = preg_replace("[úùûü]", "u", $url);
      $url = str_replace("ç", "c", $url);

      
      $url = str_replace(" ", "-", $url);

      
      $url = preg_replace("[^a-z0-9\-]", "", $url);

      return $url;
   }

   public function getIP(){
      
       if (!empty($_SERVER['HTTP_CLIENT_IP'])){
         $ip=$_SERVER['HTTP_CLIENT_IP'];
       }
       
       elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
         $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
       }else{
         $ip=$_SERVER['REMOTE_ADDR'];
       }
       return $ip;
   }

   public function applyFilter($sql, $expression, $changeString, $changeField){
      $apply = new stdClass();
      if($changeField != null && $changeField != ""){
         $sql .= " AND ".$expression." ";
         $parameters[$changeString] = $changeField;
      }
      $apply->sql = $sql;
      $apply->parameters = $parameters[$changeString];
      return $apply;
   }

   public function applyOrder($sql, $orderField, $orderDirection, $orderDefault, $parameters){
      $apply = new stdClass();
      if(isset($orderField) && isset($orderDirection)){
         $sql .= " ORDER BY #orderField# #orderDirection# ";
         $parameters["orderField"] = strtolower($orderField);
         $parameters["orderDirection"] = $orderDirection;
      }else{
         $sql .= " ORDER BY ".$orderDefault." ";
      }
      $apply->sql = $sql;
      $apply->parameters = $parameters;
      return $apply;
   }

   public function applyPage($sql, $pageOffset, $pageSize, $parameters){
      $apply = new stdClass();
      if(isset($pageOffset) && isset($pageSize)){
         $sql .= " LIMIT #limitBegin#, #limitEnd#";
         $parameters["limitBegin"] = ($pageOffset - 1) * $pageSize;
         $parameters["limitEnd"] = $pageSize;
      }
      $apply->sql = $sql;
      $apply->parameters = $parameters;
      return $apply;
   }


   static function hasId($objId){
      return isset($objId) && $objId != null && $objId != "";
   }

   static function transfer($obj, $property){
      foreach($obj as $key => $value){
         if(isset($value) && $value != null && $value != ""){
            $index = strpos($key, $property);
            $dotIndex = strpos($key, ".");
            if(is_numeric($index) && $index == 0 && $dotIndex > 0){
               $innerKey = str_replace($property.".", "", $key);
               if(!isset($obj->$property))   $obj->$property = new stdClass();
               $obj->$property->$innerKey = $value;
               unset($obj->$key);
            }
         }else{
            unset($obj->$key);
         }
      }
   }

   static function fixBoolean($value){
      if(isset($value) && ($value == "true" || $value == "1")){
         $value = "1";
      }else{
         $value = "0";
      }
      return $value;
   }

   static function generateSQLIn($con, $column, $values){
      $sql = "";
      $count = count($values);

      if($values != null && $count > 0){
         $sql .= " AND $column IN (";

         for ($i = 0; $i < $count; $i++) {
            $current = $values[$i];

            if(is_numeric($current)){
               $sql .= $current;
            }else if(is_string($current)){
               $sql .= "'".$con::prepareParameter($current)."'";
            } else if(is_object($current) && isset($current->id) && $current->id != null){
               $sql .= $current->id;
            } else if(is_array($current) && isset($current["id"]) && $current["id"] != null){
               $sql .= $current["id"];
            }

            if($i < ($count-1)){
               $sql .= ", ";
            }
         }

         $sql .= ") ";
      }

      return $sql;
   }
}
