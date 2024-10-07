<?php
// Define a classe MysqlDB para gerenciar a conexão com o banco de dados MySQL usando PDO.
class MysqlDB{
   // Propriedades privadas para armazenar a conexão, string de conexão, usuário e senha.
   private $con; // Armazena a instância da conexão PDO.
   private $str; // Armazena a string de conexão para o banco de dados.
   private $usr; // Armazena o nome de usuário do banco de dados.
   private $pas; // Armazena a senha do banco de dados.

   // Construtor da classe MysqlDB que inicializa a conexão com o banco de dados.
   function __construct(){
      try {
         // Define a string de conexão com o banco de dados MySQL, especificando host, nome do banco e charset.
         $this->str = 'mysql:host=sm-mysql;dbname=stockmaster;charset=utf8';
         // Define o nome de usuário para o banco de dados.
         $this->usr = 'root';
         // Define a senha para o banco de dados.
         $this->pas = 'root';
         // Cria uma nova instância da conexão PDO com os parâmetros fornecidos.
         $this->con = new PDO($this->str, $this->usr, $this->pas);
         // Configura a conexão PDO para lançar exceções em caso de erro.
         $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
         // Chama o método logger para registrar o erro em um log.
         $this->logger("connect", $e);
         // Lança a exceção para ser tratada externamente.
         throw $e;
      }
   }

   // Método para executar uma query SQL que não retorna resultados (ex.: INSERT, UPDATE, DELETE).
   function execute($query) {
      try {
         // Executa a query SQL diretamente na conexão.
         $this->con->exec($query);
         // Retorna true se a query foi executada com sucesso.
         return true;
      } catch(PDOException $e) {
         // Chama o logger para registrar o erro e lança a exceção.
         $this->logger("execute", $e);
         throw $e;
      }
   }

   // Método para preparar e executar uma query SQL que pode retornar resultados (ex.: SELECT).
   function query($query) {
      try {
         // Prepara a query SQL.
         $stmt = $this->con->prepare($query);
         // Executa a query preparada.
         $stmt->execute();
         // Retorna o statement (stmt) com os resultados.
         return $stmt;
      } catch(PDOException $e) {
         // Chama o logger para registrar o erro e lança a exceção.
         $this->logger("query", $e);
         throw $e;
      }
   }

   // Método para obter o último ID gerado após uma inserção no banco de dados.
   function getLastGeneratedId() {
      try {
         // Retorna o ID da última inserção.
         return $this->con->lastInsertId();
      } catch(PDOException $e) {
         // Chama o logger para registrar o erro e lança a exceção.
         $this->logger("getLastGeneratedId", $e);
         throw $e;
      }
   }

   // Método para buscar uma única linha de resultados como objeto.
   function fetchRow($stmt) {
      try {
         // Retorna a próxima linha do resultado como um objeto.
         return $stmt->fetch(PDO::FETCH_OBJ);
      } catch(PDOException $e) {
         // Chama o logger para registrar o erro e lança a exceção.
         $this->logger("fetchRow", $e);
         throw $e;
      }
   }

   // Método para buscar uma única linha de resultados como array associativo.
   function fetchRowArray($stmt) {
      try {
         // Retorna a próxima linha do resultado como um array associativo.
         return $stmt->fetch(PDO::FETCH_ASSOC);
      } catch(PDOException $e) {
         // Chama o logger para registrar o erro e lança a exceção.
         $this->logger("fetchRowArray", $e);
         throw $e;
      }
   }

   // Método para contar o número de linhas afetadas por uma operação.
   function rowCount($stmt) {
      // Retorna o número de linhas afetadas pela query.
      return $stmt->rowCount();
   }

   // Método para liberar o resultado de uma query e fechar o cursor.
   public function freeResult($stmt) {
      // Fecha o cursor associado ao statement.
      $stmt->closeCursor();
      // Define a variável do statement como null.
      $stmt = null;
   }

   // Método para fechar a conexão (aqui está vazio, mas pode ser implementado conforme necessário).
   public function close() {

   }

   // Método privado para registrar erros no log.
   private function logger($func, $e) {
      // Chama o método estático log_error da classe logError para registrar o erro em um arquivo.
      logError::log_error($func, $e, defined('USUARIO')?USUARIO:'SYSTEM');
   }

}

// Classe logError para gerenciar o registro de erros em um arquivo de log.
class logError{
   // Método estático para registrar erros em um arquivo de log.
   public static function log_error($func, $error, $user) {
      // Abre o arquivo _mysql_errors.log no modo de adição.
      if ($logLine = fopen("_mysql_errors.log", "a")) {
         // Exporta o erro para uma string legível.
         $out = var_export($error, TRUE);
         // Cria a mensagem de log com a data, usuário e descrição do erro.
         $message = date("[d-m-Y H:i:s] ")."[".$user."] Funçao MySQL : '".$func."' produziu este erro:\r\n".$out."\r\n";
         // Escreve a mensagem no arquivo de log.
         fwrite($logLine, $message);
         // Fecha o arquivo de log.
         fclose($logLine);
      }
   }
}

?>
