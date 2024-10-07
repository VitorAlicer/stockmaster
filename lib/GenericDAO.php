<?php
// Define a classe GenericDAO que contém métodos utilitários para acesso a dados genérico.
class GenericDAO {

   // Atributo público para armazenar a conexão com o banco de dados.
   public $connection;

   // Construtor da classe, que recebe uma conexão com o banco de dados e a armazena no atributo connection.
   public function __construct($conn){
      $this->connection = $conn;
   }

   // Método para registrar logs em um arquivo chamado "MYSQL.log".
   public function logger($content){
      // Abre o arquivo em modo de escrita, adicionando conteúdo ao final (a+).
      $fp = fopen("MYSQL.log", "a+");
      // Escreve o conteúdo no arquivo com uma nova linha.
      fwrite($fp, $content."\n");
      // Fecha o arquivo após a escrita.
      fclose($fp);
   }

   // Método que converte uma string em uma URL "amigável", removendo acentos e caracteres especiais.
   public function routeURL($title){
      // Converte o título para minúsculas.
      $url = strtolower($title);
      // Substitui caracteres acentuados específicos por suas versões sem acento.
      $url = preg_replace("[áàâãäª]", "a", $url);
      $url = preg_replace("[éèêë&]", "e", $url);
      $url = preg_replace("[íìîï]", "i", $url);
      $url = preg_replace("[óòôõöº]", "o", $url);
      $url = preg_replace("[úùûü]", "u", $url);
      // Substitui "ç" por "c".
      $url = str_replace("ç", "c", $url);
      // Substitui espaços por traços.
      $url = str_replace(" ", "-", $url);
      // Remove qualquer caractere que não seja alfanumérico ou traço.
      $url = preg_replace("[^a-z0-9\-]", "", $url);
      // Retorna a URL formatada.
      return $url;
   }

   // Método que retorna o endereço IP do cliente (usuário).
   public function getIP(){
      // Verifica diferentes variáveis de servidor para obter o IP do cliente.
       if (!empty($_SERVER['HTTP_CLIENT_IP'])){
         $ip=$_SERVER['HTTP_CLIENT_IP'];  // Verifica se o IP do cliente está definido.
       }
       elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
         $ip=$_SERVER['HTTP_X_FORWARDED_FOR']; // Verifica se o IP do cliente está atrás de um proxy.
       }else{
         $ip=$_SERVER['REMOTE_ADDR'];// Se nenhuma das anteriores, pega o IP remoto direto.
       }
       // Retorna o endereço IP.
       return $ip;
   }

   // Método para aplicar um filtro SQL com base em uma expressão e um campo dinâmico.
   public function applyFilter($sql, $expression, $changeString, $changeField){
      // Cria um novo objeto stdClass para armazenar o SQL e os parâmetros.
      $apply = new stdClass();
      // Se o campo de alteração (changeField) não for nulo ou vazio, aplica o filtro.
      if($changeField != null && $changeField != ""){
         // Adiciona a expressão SQL ao comando.
         $sql .= " AND ".$expression." ";
         // Define o parâmetro a ser utilizado no SQL.
         $parameters[$changeString] = $changeField;
      }
      // Armazena o SQL modificado e o parâmetro aplicado no objeto stdClass.
      $apply->sql = $sql;
      $apply->parameters = $parameters[$changeString];
      // Retorna o objeto contendo o SQL e os parâmetros.
      return $apply;
   }

   // Método para aplicar ordenação ao SQL com base em campos e direções de ordem.
   public function applyOrder($sql, $orderField, $orderDirection, $orderDefault, $parameters){
      // Cria um novo objeto stdClass para armazenar o SQL e os parâmetros.
      $apply = new stdClass();
      // Verifica se os campos de ordenação e direção são definidos.
      if(isset($orderField) && isset($orderDirection)){
          // Adiciona a cláusula ORDER BY com campos dinâmicos no SQL.
         $sql .= " ORDER BY #orderField# #orderDirection# ";
         // Armazena os valores de ordenação nos parâmetros.
         $parameters["orderField"] = strtolower($orderField);
         $parameters["orderDirection"] = $orderDirection;
      }else{
         // Se não houver campos de ordenação, aplica a ordenação padrão.
         $sql .= " ORDER BY ".$orderDefault." ";
      }
      // Armazena o SQL modificado e os parâmetros no objeto stdClass.
      $apply->sql = $sql;
      $apply->parameters = $parameters;
      // Retorna o objeto contendo o SQL e os parâmetros.
      return $apply;
   }

   // Método para aplicar paginação ao SQL com base no offset e tamanho da página.
   public function applyPage($sql, $pageOffset, $pageSize, $parameters){
      // Cria um novo objeto stdClass para armazenar o SQL e os parâmetros.
      $apply = new stdClass();
      // Verifica se os valores de offset e tamanho da página foram definidos.
      if(isset($pageOffset) && isset($pageSize)){
         // Adiciona a cláusula LIMIT no SQL para paginação.
         $sql .= " LIMIT #limitBegin#, #limitEnd#";
         // Calcula os valores de início e fim do limite.
         $parameters["limitBegin"] = ($pageOffset - 1) * $pageSize;
         $parameters["limitEnd"] = $pageSize;
      }
      // Armazena o SQL modificado e os parâmetros no objeto stdClass.
      $apply->sql = $sql;
      $apply->parameters = $parameters;
      // Retorna o objeto contendo o SQL e os parâmetros.
      return $apply;
   }

   // Método estático para verificar se um ID é válido (não nulo ou vazio).
   static function hasId($objId){
      return isset($objId) && $objId != null && $objId != "";
   }

   // Método estático para transferir propriedades de um objeto para outro.
   static function transfer($obj, $property){
      // Itera sobre as propriedades do objeto.
      foreach($obj as $key => $value){
         // Verifica se o valor da propriedade é definido e não está vazio.
         if(isset($value) && $value != null && $value != ""){
            // Verifica se a chave contém o nome da propriedade.
            $index = strpos($key, $property);
            // Verifica se há um ponto na chave (indicando uma subpropriedade).
            $dotIndex = strpos($key, ".");
            // Se a chave começa com o nome da propriedade e contém um ponto.
            if(is_numeric($index) && $index == 0 && $dotIndex > 0){
               // Remove o nome da propriedade da chave para obter a subpropriedade.
               $innerKey = str_replace($property.".", "", $key);
               // Se a propriedade não estiver definida no objeto, cria um novo stdClass.
               if(!isset($obj->$property))   $obj->$property = new stdClass();
               // Define a subpropriedade no objeto.
               $obj->$property->$innerKey = $value;
               // Remove a chave original do objeto.
               unset($obj->$key);
            }
         }else{
            // Se o valor não é definido ou está vazio, remove a chave do objeto.
            unset($obj->$key);
         }
      }
   }

   // Método estático para corrigir valores booleanos, retornando "1" para true e "0" para false.
   static function fixBoolean($value){
      if(isset($value) && ($value == "true" || $value == "1")){
         $value = "1"; // Converte para "1" se o valor for "true" ou "1".
      }else{
         $value = "0"; // Caso contrário, define como "0".
      }
      return $value;
   }

   // Método estático para gerar uma cláusula SQL "IN" com base em uma lista de valores.
   static function generateSQLIn($con, $column, $values){
      $sql = "";
      $count = count($values); // Conta quantos valores estão na lista.

       // Se há valores definidos, inicia a construção da cláusula IN.
      if($values != null && $count > 0){
         $sql .= " AND $column IN (";

         // Itera sobre os valores da lista.
         for ($i = 0; $i < $count; $i++) {
            $current = $values[$i];
            // Adiciona o valor ao SQL, verificando se é numérico ou string.
            if(is_numeric($current)){
               $sql .= $current;
            }else if(is_string($current)){
               $sql .= "'".$con::prepareParameter($current)."'"; // Prepara a string para o SQL.
            } else if(is_object($current) && isset($current->id) && $current->id != null){
               $sql .= $current->id; // Usa o ID do objeto se existir.
            } else if(is_array($current) && isset($current["id"]) && $current["id"] != null){
               $sql .= $current["id"]; // Usa o ID de um array, se existir.

            }
            // Adiciona uma vírgula entre os valores, exceto no último.
            if($i < ($count-1)){
               $sql .= ", ";
            }
         }
         // Fecha a cláusula IN.
         $sql .= ") ";
      }
      // Retorna a cláusula SQL gerada.
      return $sql;
   }
}
