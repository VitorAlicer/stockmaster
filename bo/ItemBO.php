<?php

// Inclui o arquivo de inicialização que pode configurar o ambiente da aplicação.
require_once(__DIR__."/../init.php");

// Inclui o arquivo que contém funções auxiliares que podem ser usadas em diferentes partes do sistema.
require_once(__DIR__."/../inc/functions.php");

// Inclui a classe Service.
require_once(__DIR__."/../lib/Service.php");

/**
 * Classe ItemBO (Business Object) é responsável por gerenciar operações relacionadas a itens,
 * incluindo criação, atualização, exclusão e gerenciamento de variações de itens.
 */

class ItemBO {

   private ?string $message;
   private bool $error;
   private Service $service;
   private array $post;
   private object $item;
   private array $vars;

   /**
    * Construtor da classe ItemBO.
    * Inicializa os atributos com valores padrões e cria a instância de Service.
    */

   public function __construct() {
      $this->message    = "";               // Inicia a mensagem como uma string vazia.
      $this->error      = false;            // Inicia o erro como falso (sem erros).
      $this->service    = new Service();    // Cria uma nova instância da classe Service.
      $this->post       = array();          // Inicia o array POST vazio.
      $this->item       = new stdClass();   // Cria um objeto vazio para o item.
      $this->vars       = array();          // Inicia o array de variações vazio.
   }

   /**
    * Destrutor da classe ItemBO.
    * Libera os recursos ao destruir os objetos.
    */
   public function __destruct() {
      unset($this->service);      // Remove a instância da classe Service.
      unset($this->item);         // Remove o objeto item.
      unset($this->vars);         // Remove o array de variações.
   }

   /**  Verifica se houve um erro em alguma operação. */
   public function isError() { return $this->error; }

   /** Obtém a mensagem de feedback da última operação executada.*/
   public function getMessage() { return $this->message; }

   /** Obtém o item configurado. */
   public function getItem() { return $this->item; }

   /** Obtém as variações do item. */
   public function getVars() { return $this->vars; }

   /** Configura os atributos do item com os dados recebidos via POST. */
   public function setItem($post):void {
      $this->post = $post;                                                    // Armazena os dados POST recebidos.
      $this->item->cod_item          = $post["cod_item"];                     // Define o código do item.
      $this->item->tipo              = $post["tipo"];                         // Define o tipo do item.
      $this->item->nome_item         = $post["nome_item"];                    // Define o nome do item.
      $this->item->val_custo         = invertDec($post["val_custo"], 'db');   // Inverte o valor de custo para o formato do banco.
      $this->item->val_ref           = invertDec($post["val_ref"], 'db');     // Inverte o valor de referência para o formato do banco.
      $this->item->medida            = $post["medida"];                       // Define a medida do item.
      $this->item->un                = $post["un"];                           // Define a unidade do item.
      $this->item->controla_estoque  = $post["controla_estoque"];             // Define se o item controla estoque.
      $this->item->num_vars          = trim($this->post["qtd_var"]);          // Define o número de variações do item.
   }

   /** Configura as variações do item com os dados recebidos via POST. */
   public function setVars() {
      if($this->item->num_vars == 0) return false;   // Se não há variações, retorna falso.
      $val_total = 0.0;                              // Inicializa o valor total como 0.
      $i = 0;                                        // Contador de variações.
      $cont = 0;                                     // Contador sequencial de variações.
      for(;;) {
         $i++;
         $nome_var = $this->post["nome_var".$i];     // Obtém o nome da variação.
         if($nome_var == '') continue;               // Se o nome da variação está vazio, pula para a próxima iteração.
         $cont++;
         
         // Cria um objeto para armazenar a variação.
         $row_var = new stdClass();
         $row_var->nome_var = $nome_var;                                            // Armazena o nome da variação.
         $row_var->custo_var  = invertDec($this->post["custo_var".$i], 'db');       // Inverte o valor de custo da variação.
         $row_var->val_var  = invertDec($this->post["val_var".$i], 'db');           // Inverte o valor da variação.
         
         // Adiciona a variação ao array de variações.
         array_push($this->vars, $row_var);

         // Se todas as variações foram processadas, sai do loop.
         if($cont == $this->item->num_vars) break;
      }
      return true;       // Retorna verdadeiro indicando que as variações foram configuradas com sucesso.
   }
   
   /** Cria um novo item e suas variações no banco de dados. */
   public function create():void {
      try {
         // Cria um novo item no banco de dados e obtém o código do item.
         $this->item->cod_item = $this->service->newItem($this->item);

         // Insere a lista de variações associadas ao item.
         $retorno = $this->service->newVariacaoList($this->item->cod_item, $this->vars);

         // Define uma mensagem de sucesso.
         $this->message = "Item `".$this->item->nome_item."` cadastrado com sucesso.";

      } catch(Exception $e) {
         // Se houver uma exceção, define o erro como verdadeiro e armazena a mensagem de erro.
         $this->error = true;
         $this->message = $e->getMessage();
      }
   }

   /** Atualiza um item existente e suas variações no banco de dados. */
   public function update():void {
      try {
         // Atualiza os dados do item.
         $retorno = $this->service->updateItem($this->item);

         // Remove as variações existentes do item.
         $retorno = $this->service->deleteVariacaoList($this->item->cod_item);

         // Insere as novas variações do item.
         $retorno = $this->service->newVariacaoList($this->item->cod_item, $this->vars);

         // Define uma mensagem de sucesso.
         $this->message = "Item `".$this->item->nome_item."` alterado com sucesso.";

      } catch(Exception $e) {
         // Se houver uma exceção, define o erro como verdadeiro e armazena a mensagem de erro.
         $this->error = true;
         $this->message = $e->getMessage();
      }
   }

   /** Remove um item e suas variações do banco de dados. */
   public function remove($cod_item, $nome_item):void {
      try {
         // Remove o item do banco de dados.
         $retorno = $this->service->deleteItem($cod_item);

         // Remove as variações associadas ao item.
         $retorno = $this->service->deleteVariacaoList($cod_item);

         // Define uma mensagem de sucesso.
         $this->message = "Item `".$nome_item."` excluido com sucesso.";

      } catch(Exception $e) {
         // Se houver uma exceção, define o erro como verdadeiro e armazena a mensagem de erro.
         $this->error = true;
         $this->message = $e->getMessage();
      }
   }
}
?>