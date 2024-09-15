<?php

class Util {

   public const FORMATO_DATA = "d/m/Y";
   public const FORMATO_DATA_ABREV = "d/M/Y";
   public const FORMATO_DATA_HORA = "d/m/Y H:i";
   public const FORMATO_HORA = "H:i";
   private static $lang;
   public static $idiomaBO;

   public static function applyTimeFormat($value, $format) {
      if($value == '0000-00-00 00:00:00' ||
         $value == null) return '';
      $date = new DateTimeImmutable($value);
      return $date->format($format);
   }

   public static function getDate($value) {
      return Util::applyTimeFormat($value, Util::FORMATO_DATA);
   }

   public static function getDateAbrev($value) {
      return Util::applyTimeFormat($value, Util::FORMATO_DATA_ABREV);
   }

   public static function getDateTime($value) {
      return Util::applyTimeFormat($value, Util::FORMATO_DATA_HORA);
   }

   public static function getTime($value) {
      return Util::applyTimeFormat($value, Util::FORMATO_HORA);
   }

}
function autoloader($class_name) {
  require_once "lib/".$class_name.".php";
}
spl_autoload_register("autoloader");

function iif($expression, $true, $false) {
   return ($expression) ? $true : $false;
}

function cloneObjectsInArray($ar){
   $new = array();
   foreach ($ar as $key => $value) {
      $new[$key] = clone $value;
   }
   return $new;
}

function arrayToList($arr) {
   $new_arr = array();
   foreach($arr as $key => $value) {
      $new_arr[$key] = "'".$value."'";
   }
   return join(",", $new_arr);
}

function free($obj){
   if(isset($obj)) unset($obj);
}

function newTransactionToken() {
   $param = sha1(uniqid(rand(), true));
   return $param;
}

function getAge($date) {
   list($day, $month, $year) = explode('/', $date);
   $birth = mktime( 0, 0, 0, $month, $day, $year);
   $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
   $age = floor((((($today - $birth) / 60) / 60) / 24) / 365.25);
   return $age;
}

function getYearByDateDiff($date1, $date2) {
   $dt1 = new DateTime($date1);
   $dt2 = new DateTime($date2);
   $diff = $dt1->diff($dt2);
   return $diff->y;
}

function getDayByDateDiff($date1, $date2) {
   $dt1 = new DateTime($date1);
   $dt2 = new DateTime($date2);
   $diff = $dt1->diff($dt2);
   return $diff->d;
}

function getMonthBegin($date) {
   $date = invertDate($date, 'db');
   return date('Y-m-01', strtotime($date));
}

function getMonthEnd($date) {
   $date = invertDate($date, 'db');
   return date('Y-m-t', strtotime($date));
}

function getWeekDayName($date) {
   $ar_name = array("Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado");
   return $ar_name[date('w', strtotime($date))];
}

function dataHoraToDateTimeString($data, $hora, $formato) {
   if($formato != "")
      return DateTime::createFromFormat('d/m/Y H:i:s', $data.' '.$hora)->format($formato);
   return DateTime::createFromFormat('d/m/Y H:i:s', $data.' '.$hora)->format('Y-m-d H:i:s');
}

function dateTimeFormattedInOut($data, $formato_in, $formato_out) {
   return DateTime::createFromFormat($formato_in, $data)->format($formato_out);
}

function datetimeToDate($date) {
   if($date == '0000-00-00 00:00:00' || $date == null || is_null($date))
      return 0;
   return date('d/m/Y', strtotime($date));
}

function datetimeToYMD($date) {
   if($date == '0000-00-00 00:00:00' ||
      $date == null) return 0;
   return date('Ymd', strtotime($date));
}

function dateToYMD($date) {
   if($date == '0000-00-00' ||
      $date == null) return 0;
   return date('Ymd', strtotime($date));
}

function hourTo24($value) {
   if($value == "") return "";
   return date("H:i", strtotime($value));
}

function invertDate($date, $to) {
   if($to == "db") {
      
      try{
         if($date == "") throw new Exception("#");
         if(substr_count($date, '/') != 2) throw new Exception("#");
         
         $aux = explode('/', $date);
         if(! (is_array($aux) && checkdate($aux[1], $aux[0], $aux[2])) ) throw new Exception("#");
         $aux = $aux[2] .'-'. $aux[1] .'-'. $aux[0];
      } catch(Exception $e) {
         return null;
      }
   } else {
      
      try{
         if($date == "") throw new Exception("#");
         if(substr_count($date, '-') != 2) throw new Exception("#");
         
         $aux = explode('-', $date);
         if(! (is_array($aux) && checkdate($aux[1], $aux[2], $aux[0])) ) throw new Exception("#");
         $aux = $aux[2] .'/'. $aux[1] .'/'. $aux[0];
      } catch(Exception $e) {
         return "";
      }
   }
   return $aux;
}

function beginsWith($text, $str) {
   return substr($text, 0, strlen($str)) === $str;
}

function removeMask($text) {
   return preg_replace("/[^A-Za-z0-9 ]/", '', $text);
}

function stringPad($value, $qty, $char, $dir) {
   switch($dir) {
      case "left": $dir = STR_PAD_LEFT; break;
      case "both": $dir = STR_PAD_BOTH; break;
      default: $dir = STR_PAD_RIGHT;
   }
   return str_pad($value, $qty, $char, $dir);
}

function stripText($str){
   return $str;
}

function toUpper($text) {
   $encoding = mb_internal_encoding();
   return mb_strtoupper($text, $encoding);
}

function toLower($text) {
   $encoding = mb_internal_encoding();
   return mb_strtolower($text, $encoding);
}

function invertDec($value, $to) {
   if($to == "db") {
      if($value == '') return '0.00';
      $value = str_replace( '.', '', $value);
      $value = str_replace( ',', '.', $value);
      $value = floatval($value);
      return number_format($value, 2, '.', '');
   } else {
      if($value == '') return '0,00';
      return number_format($value, 2, ',', '.');
   }
}

function fixPath($file) {
   $file = str_replace('//', '/', $file);
   $file = str_replace('\\', '/', $file);
   return $file;
}

function getExtensionFile($file) {
   return pathinfo($file, PATHINFO_EXTENSION);
}

function createDirectory($path) {
   $path = fixPath($path);
   if(!is_dir($path)) {
      mkdir($path, 0755, true);
   }
   return $path;
}

function copyFile($src, $dst) {
   $src = fixPath($src);
   $dst = fixPath($dst);
   if(file_exists($src)) {
      return copy($src, $dst);
   }
   return false;
}


function removeDirectory($path) {
   $path = fixPath($path);
   $files = glob($path . '/*');
   foreach ($files as $file) {
      is_dir($file) ? removeDirectory($file) : unlink($file);
   }
   rmdir($path);
   return;
}


function removeFile($path) {
   try{
      unlink($path);
      return true;
   } catch(Exception $e) {
      saveLog($e->getMessage());
      return false;
   }
}


function formatFileSize($bytes, $precision = 2) {
   if ($bytes >= 1073741824) {
      $bytes = number_format($bytes / 1073741824, 2);
      $un = 'GB';
   } elseif ($bytes >= 1048576) {
      $bytes = number_format($bytes / 1048576, 2);
      $un = 'MB';
   } elseif ($bytes >= 1024) {
      $bytes = number_format($bytes / 1024, 2);
      $un = 'KB';
   } elseif ($bytes >= 1) {
      $un = 'B';
   } else {
      $bytes = 0;
      $un = 'B';
   }
   return round($bytes, $precision) .' '. $un;
}

function getUF() {
   return array(
      "AC" => "Acre",
      "AL" => "Alagoas",
      "AP" => "Amapá",
      "AM" => "Amazonas",
      "BA" => "Bahia",
      "CE" => "Ceará",
      "DF" => "Distrito Federal",
      "ES" => "Espírito Santo",
      "GO" => "Goiás",
      "MA" => "Maranhão",
      "MT" => "Mato Grosso",
      "MS" => "Mato Grosso do Sul",
      "MG" => "Minas Gerais",
      "PA" => "Pará",
      "PB" => "Paraíba",
      "PR" => "Paraná",
      "PE" => "Pernambuco",
      "PI" => "Piauí",
      "RJ" => "Rio de Janeiro",
      "RN" => "Rio Grande do Norte",
      "RS" => "Rio Grande do Sul",
      "RO" => "Rondônia",
      "RR" => "Roraima",
      "SC" => "Santa Catarina",
      "SP" => "São Paulo",
      "SE" => "Sergipe",
      "TO" => "Tocantins");
}

function debugObj($name, $obj){
   echo "<br/>".$name."=";
   print_r($obj);
   echo "<br/>";
}

function showDebug($var) {
   if(PERFIL == -1) {
      var_export($var);
      echo "<br>";
   }
}

function alert($msg){
   echo "<script>alert('".$msg."');</script>";
}

function debugSQL($sql, $parameters, $connection){
   if($parameters != null) {
      $sql = $connection->replaceParameters($sql, $parameters);
   }
   saveDebug($sql);
}

function saveDebug($message) {
   if ($logLine = fopen("_app_debug.log", "a")) {
      $message = date("[d-m-Y H:i:s] ")."[".(defined('USUARIO')?USUARIO:"SYSTEM")."] ".$message."\r\n";
      fwrite($logLine, $message);
      fclose($logLine);
   }
}

function saveLog($message) {
   if ($logLine = fopen("_app_errors.log", "a")) {
      $message = date("[d-m-Y H:i:s] ")."[".(defined('USUARIO')?USUARIO:"SYSTEM")."] ".$message."\r\n";
      fwrite($logLine, $message);
      fclose($logLine);
   }
}
