<?php

// Define uma classe chamada Util
class Util {

   // Constantes para diferentes formatos de data e hora
   public const FORMATO_DATA = "d/m/Y"; // formato de data "dia/mês/ano"
   public const FORMATO_DATA_ABREV = "d/M/Y"; // formato de data abreviado "dia/mês/ano"
   public const FORMATO_DATA_HORA = "d/m/Y H:i"; // formato de data e hora "dia/mês/ano horas:minutos"
   public const FORMATO_HORA = "H:i"; // formato de hora "horas:minutos"

   // Declara uma variável estática privada chamada $lang
   private static $lang;

   // Declara uma variável pública estática chamada $idiomaBO
   public static $idiomaBO;

   // Método estático que aplica um formato de data/hora em um valor
   public static function applyTimeFormat($value, $format) {
      // Verifica se o valor é uma data inválida ou nulo
      if($value == '0000-00-00 00:00:00' || $value == null) return '';

      // Cria um novo objeto DateTimeImmutable com o valor fornecido
      $date = new DateTimeImmutable($value);

      // Retorna o valor formatado de acordo com o formato especificado
      return $date->format($format);
   }

   // Método estático que retorna a data no formato definido em FORMATO_DATA
   public static function getDate($value) {
      return Util::applyTimeFormat($value, Util::FORMATO_DATA);
   }

   // Método estático que retorna a data abreviada no formato FORMATO_DATA_ABREV
   public static function getDateAbrev($value) {
      return Util::applyTimeFormat($value, Util::FORMATO_DATA_ABREV);
   }

   // Método estático que retorna data e hora no formato FORMATO_DATA_HORA
   public static function getDateTime($value) {
      return Util::applyTimeFormat($value, Util::FORMATO_DATA_HORA);
   }

   // Método estático que retorna apenas a hora no formato FORMATO_HORA
   public static function getTime($value) {
      return Util::applyTimeFormat($value, Util::FORMATO_HORA);
   }
}

// Função que faz o autoload das classes a partir do diretório lib
function autoloader($class_name) {
  require_once "lib/".$class_name.".php"; // Carrega o arquivo da classe
}

// Registra a função de autoload
spl_autoload_register("autoloader");

// Função que imita um operador ternário (iif)
function iif($expression, $true, $false) {
   return ($expression) ? $true : $false; // Retorna $true se $expression for verdadeira, caso contrário $false
}

// Função que clona objetos dentro de um array
function cloneObjectsInArray($ar){
   $new = array(); // Cria um novo array
   foreach ($ar as $key => $value) {
      $new[$key] = clone $value; // Clona cada objeto e o adiciona ao novo array
   }
   return $new; // Retorna o array clonado
}

// Converte um array em uma string de valores separados por vírgula
function arrayToList($arr) {
   $new_arr = array(); // Novo array
   foreach($arr as $key => $value) {
      $new_arr[$key] = "'".$value."'"; // Adiciona aspas simples em torno de cada valor
   }
   return join(",", $new_arr); // Junta os valores com vírgula
}

// Função que libera uma variável de objeto
function free($obj){
   if(isset($obj)) unset($obj); // Se o objeto estiver definido, remove-o
}

// Gera um token de transação único
function newTransactionToken() {
   $param = sha1(uniqid(rand(), true)); // Gera um hash único com sha1
   return $param;
}

// Função que calcula a idade a partir de uma data de nascimento
function getAge($date) {
   list($day, $month, $year) = explode('/', $date); // Separa dia, mês e ano
   $birth = mktime( 0, 0, 0, $month, $day, $year); // Cria o timestamp da data de nascimento
   $today = mktime(0, 0, 0, date('m'), date('d'), date('Y')); // Cria o timestamp da data atual
   $age = floor((((($today - $birth) / 60) / 60) / 24) / 365.25); // Calcula a diferença em anos
   return $age; // Retorna a idade
}

// Calcula a diferença em anos entre duas datas
function getYearByDateDiff($date1, $date2) {
   $dt1 = new DateTime($date1); // Cria objetos DateTime para as datas
   $dt2 = new DateTime($date2);
   $diff = $dt1->diff($dt2); // Calcula a diferença
   return $diff->y; // Retorna a diferença em anos
}

// Calcula a diferença em dias entre duas datas
function getDayByDateDiff($date1, $date2) {
   $dt1 = new DateTime($date1);
   $dt2 = new DateTime($date2);
   $diff = $dt1->diff($dt2); // Calcula a diferença
   return $diff->d; // Retorna a diferença em dias
}

// Obtém o primeiro dia do mês de uma data
function getMonthBegin($date) {
   $date = invertDate($date, 'db'); // Inverte o formato da data
   return date('Y-m-01', strtotime($date)); // Retorna o primeiro dia do mês
}

// Obtém o último dia do mês de uma data
function getMonthEnd($date) {
   $date = invertDate($date, 'db');
   return date('Y-m-t', strtotime($date)); // Retorna o último dia do mês
}

// Retorna o nome do dia da semana para uma data
function getWeekDayName($date) {
   $ar_name = array("Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"); // Array com os nomes dos dias
   return $ar_name[date('w', strtotime($date))]; // Retorna o nome do dia da semana correspondente
}

// Converte data e hora para uma string no formato desejado
function dataHoraToDateTimeString($data, $hora, $formato) {
   if($formato != "")
      return DateTime::createFromFormat('d/m/Y H:i:s', $data.' '.$hora)->format($formato); // Formata a data e hora
   return DateTime::createFromFormat('d/m/Y H:i:s', $data.' '.$hora)->format('Y-m-d H:i:s'); // Formato padrão
}

// Converte data entre diferentes formatos
function dateTimeFormattedInOut($data, $formato_in, $formato_out) {
   return DateTime::createFromFormat($formato_in, $data)->format($formato_out); // Converte de um formato para outro
}

// Converte datetime para data no formato d/m/Y
function datetimeToDate($date) {
   if($date == '0000-00-00 00:00:00' || $date == null || is_null($date))
      return 0;
   return date('d/m/Y', strtotime($date)); // Retorna a data formatada
}

// Converte datetime para formato YMD
function datetimeToYMD($date) {
   if($date == '0000-00-00 00:00:00' || $date == null) return 0;
   return date('Ymd', strtotime($date)); // Retorna no formato YMD
}

// Converte data para formato YMD
function dateToYMD($date) {
   if($date == '0000-00-00' || $date == null) return 0;
   return date('Ymd', strtotime($date));
}

// Converte hora para o formato de 24 horas
function hourTo24($value) {
   if($value == "") return "";
   return date("H:i", strtotime($value)); // Converte a hora
}

// Inverte o formato da data entre DB e padrão brasileiro
function invertDate($date, $to) {
   if($to == "db") {
      // Conversão para o formato de banco de dados (Y-m-d)
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
      // Conversão para o formato padrão (d/m/Y)
      try{
         if($date == "") throw new Exception("#");
         if(substr_count($date, '-') != 2) throw new Exception("#");
         $aux = explode('-', $date);
         if(! (is_array($aux) && checkdate($aux[1], $aux[2], $aux[0])) ) throw new Exception("#");
         $aux = $aux[2] .'/'. $aux[1] .'/'. $aux[0];
      } catch(Exception $e) {
         return null;
      }
   }
   return $aux;
}

// Função que verifica se um texto começa com uma determinada string
function beginsWith($text, $str) {
   // Usa substr para pegar o início do texto com o tamanho da string comparada e faz a verificação
   return substr($text, 0, strlen($str)) === $str;
}

// Função que remove caracteres especiais de uma string
function removeMask($text) {
   // Substitui todos os caracteres que não sejam letras, números ou espaços por vazio
   return preg_replace("/[^A-Za-z0-9 ]/", '', $text);
}

// Função que preenche uma string com um caractere até atingir um comprimento desejado
function stringPad($value, $qty, $char, $dir) {
   // Verifica a direção do preenchimento e ajusta o argumento de STR_PAD
   switch($dir) {
      case "left": $dir = STR_PAD_LEFT; break; // Preenche à esquerda
      case "both": $dir = STR_PAD_BOTH; break; // Preenche em ambos os lados
      default: $dir = STR_PAD_RIGHT; // Preenche à direita por padrão
   }
   // Retorna a string preenchida de acordo com os parâmetros
   return str_pad($value, $qty, $char, $dir);
}

// Função que retorna o texto sem modificação (aqui, poderia ser usada para tratamentos futuros)
function stripText($str) {
   return $str;
}

// Função que converte texto para maiúsculas, preservando a codificação de caracteres
function toUpper($text) {
   $encoding = mb_internal_encoding(); // Obtém a codificação interna
   return mb_strtoupper($text, $encoding); // Converte o texto para maiúsculas
}

// Função que converte texto para minúsculas, preservando a codificação de caracteres
function toLower($text) {
   $encoding = mb_internal_encoding(); // Obtém a codificação interna
   return mb_strtolower($text, $encoding); // Converte o texto para minúsculas
}

// Função que inverte o formato de um número decimal entre representação brasileira e de banco de dados
function invertDec($value, $to) {
   if($to == "db") { // Para banco de dados (formato com ponto como separador decimal)
      if($value == '') return '0.00';
      $value = str_replace('.', '', $value); // Remove os pontos (separador de milhar)
      $value = str_replace(',', '.', $value); // Substitui a vírgula por ponto (separador decimal)
      $value = floatval($value); // Converte para número de ponto flutuante
      return number_format($value, 2, '.', ''); // Retorna o número formatado
   } else { // Para formato brasileiro (vírgula como separador decimal)
      if($value == '') return '0,00';
      return number_format($value, 2, ',', '.'); // Formata o valor para o padrão brasileiro
   }
}

// Função que corrige o caminho de um arquivo, substituindo barras duplas ou invertidas
function fixPath($file) {
   $file = str_replace('//', '/', $file); // Substitui barras duplas por uma única
   $file = str_replace('\\', '/', $file); // Substitui barras invertidas por barras normais
   return $file;
}

// Função que retorna a extensão de um arquivo
function getExtensionFile($file) {
   return pathinfo($file, PATHINFO_EXTENSION); // Usa pathinfo para obter a extensão do arquivo
}

// Função que cria um diretório, se ele não existir
function createDirectory($path) {
   $path = fixPath($path); // Corrige o caminho do diretório
   if(!is_dir($path)) {
      mkdir($path, 0755, true); // Cria o diretório com permissões 0755
   }
   return $path; // Retorna o caminho do diretório
}

// Função que copia um arquivo de um local para outro
function copyFile($src, $dst) {
   $src = fixPath($src); // Corrige o caminho de origem
   $dst = fixPath($dst); // Corrige o caminho de destino
   if(file_exists($src)) { // Verifica se o arquivo de origem existe
      return copy($src, $dst); // Copia o arquivo
   }
   return false; // Retorna false se o arquivo não existir
}

// Função que remove um diretório e todo o seu conteúdo
function removeDirectory($path) {
   $path = fixPath($path); // Corrige o caminho do diretório
   $files = glob($path . '/*'); // Obtém todos os arquivos e subdiretórios dentro do diretório
   foreach ($files as $file) {
      is_dir($file) ? removeDirectory($file) : unlink($file); // Remove arquivos ou subdiretórios recursivamente
   }
   rmdir($path); // Remove o diretório
   return;
}

// Função que remove um arquivo
function removeFile($path) {
   try {
      unlink($path); // Tenta remover o arquivo
      return true; // Retorna true se bem-sucedido
   } catch(Exception $e) {
      saveLog($e->getMessage()); // Salva o erro no log
      return false; // Retorna false se falhar
   }
}

// Função que formata o tamanho de um arquivo em unidades legíveis (B, KB, MB, GB)
function formatFileSize($bytes, $precision = 2) {
   if ($bytes >= 1073741824) {
      $bytes = number_format($bytes / 1073741824, 2); // Converte para GB
      $un = 'GB';
   } elseif ($bytes >= 1048576) {
      $bytes = number_format($bytes / 1048576, 2); // Converte para MB
      $un = 'MB';
   } elseif ($bytes >= 1024) {
      $bytes = number_format($bytes / 1024, 2); // Converte para KB
      $un = 'KB';
   } else {
      $un = 'B'; // Se for menor que 1 KB, mantém como bytes
   }
   return round($bytes, $precision) .' '. $un; // Retorna o tamanho formatado com a unidade correspondente
}

// Função que retorna um array com os estados brasileiros (UFs) e seus nomes
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
      "TO" => "Tocantins"
   );
}

// Função de depuração que exibe o nome e o conteúdo de um objeto
function debugObj($name, $obj) {
   echo "<br/>".$name."="; // Exibe o nome do objeto
   print_r($obj); // Exibe o conteúdo do objeto
   echo "<br/>";
}

// Função que exibe uma variável se o perfil for de depuração
function showDebug($var) {
   if(PERFIL == -1) {
      var_export($var); // Exporta a variável de forma legível
      echo "<br>";
   }
}

// Função que exibe uma mensagem de alerta no navegador
function alert($msg) {
   echo "<script>alert('".$msg."');</script>"; // Usa JavaScript para exibir um alerta com a mensagem
}

// Função que depura uma consulta SQL e seus parâmetros
function debugSQL($sql, $parameters, $connection) {
   if($parameters != null) {
      $sql = $connection->replaceParameters($sql, $parameters); // Substitui os parâmetros na consulta SQL
   }
   saveDebug($sql); // Salva a consulta no log de depuração
}

// Função que salva mensagens de depuração em um arquivo de log
function saveDebug($message) {
   if ($logLine = fopen("_app_debug.log", "a")) { // Abre o arquivo de log em modo de adição
      $message = date("[d-m-Y H:i:s] ")."[".(defined('USUARIO')?USUARIO:"SYSTEM")."] ".$message."\r\n"; // Formata a mensagem com data e usuário
      fwrite($logLine, $message); // Escreve a mensagem no log
      fclose($logLine); // Fecha o arquivo de log
   }
}

// Função que salva mensagens de erro em um arquivo de log
function saveLog($message) {
   if ($logLine = fopen("_app_errors.log", "a")) { // Abre o arquivo de log de erros em modo de adição
      $message = date("[d-m-Y H:i:s] ")."[".(defined('USUARIO')?USUARIO:"SYSTEM")."] ".$message."\r\n"; // Formata a mensagem com data e usuário
      fwrite($logLine, $message); // Escreve a mensagem no log
      fclose($logLine); // Fecha o arquivo de log
   }
}