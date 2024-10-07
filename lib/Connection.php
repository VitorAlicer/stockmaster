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

// Declara a classe `Connection` para gerenciar conexões e interações com o banco de dados.
class Connection{

   // Define as variáveis para armazenar a conexão com o banco e os resultados.
   var $connection;
   var $results;

   // Construtor da classe. Inicializa a conexão e um array de resultados.
   function __construct(){
      try {
         // Cria uma nova instância de MysqlDB
         $this->connection = new MysqlDB();
         // Inicializa o array de resultados como vazio.
         $this->results = array();
      } catch(Exception $e) {
          // Se houver erro ao conectar, lança a exceção para tratamento externo.
         throw $e;
      }
   }

   // Método para executar uma consulta SQL. Aceita parâmetros opcionais para a consulta.
   function query ($query, $parameters=null){
      // Se houver parâmetros, substitui-os na consulta.
      if(isset($parameters)){
         $query = $this->replaceParameters($query, $parameters);
      }
      // Executa a consulta e armazena o retorno.
      $stmt = $this->connection->query($query);
      return $stmt;
   }

   // Método para substituir os parâmetros na consulta pelo valor correspondente.
   public function replaceParameters ($query, $parameters){
      // Se não houver parâmetros, retorna a consulta como está.
      if($parameters == null) return $query;
      // Para cada parâmetro, substitui na consulta as ocorrências pelo valor.
      foreach($parameters as $name => $value){
         $query = str_replace("#$name#", $value, $query);
      }
      return $query;
   }

   // Retorna o último ID gerado na inserção de dados.
   function getLastGeneratedId (){
      return $this->connection->getLastGeneratedId();
   }

   // Busca uma única linha de resultado da consulta.
   function fetchRow ($stmt){
      return $this->connection->fetchRow($stmt);
   }

   // Busca uma única linha de resultado da consulta em formato de array.
   function fetchRowArray ($stmt){
      return $this->connection->fetchRowArray($stmt);
   }

   // Executa uma consulta de atualização/remoção/inserção.
   function execute ($query, $parameters=null){
      // Se houver parâmetros, substitui-os na consulta.
      if(isset($parameters)){
         $query = $this->replaceParameters($query, $parameters);
      }
      // Executa a consulta.
      return $this->connection->execute($query);
   }

   // Executa uma consulta e retorna o primeiro resultado da consulta.
   function singleResult ($query, $parameters=null){
      // Executa a consulta.
      $stmt = $this->query($query, $parameters);
      // Retorna a primeira linha.
      return $this->fetchRow($stmt);
   }

   // Executa uma consulta e retorna todos os resultados.
   // O parâmetro $type determina se os resultados serão retornados como objetos ou arrays.
   function allResults ($query, $parameters=null, $type="obj"){
      // Executa a consulta.
      $stmt = $this->query($query, $parameters);
      if($stmt != null){
         // Inicializa um array para armazenar os resultados.
         $list = array();
         // Itera sobre os resultados e os armazena no array.
         while($obj = ($type=="array") ? $this->fetchRowArray($stmt) : $this->fetchRow($stmt)){
            array_push($list, $obj);
         }
         return $list;
      }
      return null;
   }

   // Conta o número de linhas retornadas por uma consulta.
   function rowCount ($query){
      // Executa a consulta.
      $stmt = $this->query($query);
      if($stmt != null){
         // Retorna a contagem de linhas.
         return $this->connection->rowCount($stmt);
      }
      return null;
   }

   // Libera o recurso associado a um statement (resultado da consulta).
   private function freeResult ($stmt){
      if($stmt != null){
          // Libera o resultado da memória.
         $this->connection->freeResult($stmt);
      }
   }
   
   // Destrutor da classe, liberando os recursos associados às consultas e à conexão.
   function __destruct (){
      // Se houver resultados armazenados, libera-os.
      if($this->results) {
         foreach($this->results as &$stmt){
            $this->freeResult($stmt);
         }
         unset($this->results);
      }
      // Fecha a conexão com o banco de dados.
      if($this->connection)
         $this->connection->close();
      unset($this->connection);
   }
}

