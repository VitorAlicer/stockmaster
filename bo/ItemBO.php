<?php
require_once(__DIR__."/../init.php");
require_once(__DIR__."/../inc/functions.php");
require_once(__DIR__."/../lib/Service.php");

class ItemBO {

   private ?string $message;
   private bool $error;
   private Service $service;
   private array $post;
   private object $item;
   private array $vars;

   public function __construct() {
      $this->message    = "";
      $this->error      = false;
      $this->service    = new Service();
      $this->post       = array();
      $this->item       = new stdClass();
      $this->vars       = array();
   }

   public function __destruct() {
      unset($this->service);
      unset($this->item);
      unset($this->vars);
   }

   public function isError() { return $this->error; }
   public function getMessage() { return $this->message; }
   public function getItem() { return $this->item; }
   public function getVars() { return $this->vars; }

   public function setItem($post):void {
      $this->post = $post;
      $this->item->cod_item          = $post["cod_item"];
      $this->item->tipo              = $post["tipo"];
      $this->item->nome_item         = $post["nome_item"];
      $this->item->val_custo         = invertDec($post["val_custo"], 'db');
      $this->item->val_ref           = invertDec($post["val_ref"], 'db');
      $this->item->medida            = $post["medida"];
      $this->item->un                = $post["un"];
      $this->item->controla_estoque  = $post["controla_estoque"];
      $this->item->num_vars          = trim($this->post["qtd_var"]);
   }

   public function setVars() {
      if($this->item->num_vars == 0) return false;
      $val_total = 0.0;
      $i = 0; // contador de post item
      $cont = 0; // contador sequencial
      for(;;) {
         $i++;
         $nome_var = $this->post["nome_var".$i];
         if($nome_var == '') continue;
         $cont++;

         $row_var = new stdClass();
         $row_var->nome_var = $nome_var;
         $row_var->custo_var  = invertDec($this->post["custo_var".$i], 'db');
         $row_var->val_var  = invertDec($this->post["val_var".$i], 'db');

         array_push($this->vars, $row_var);
         if($cont == $this->item->num_vars) break;
      }
      return true;
   }

   public function create():void {
      try {
         $this->item->cod_item = $this->service->newItem($this->item);
         $retorno = $this->service->newVariacaoList($this->item->cod_item, $this->vars);
         $this->message = "Item `".$this->item->nome_item."` cadastrado com sucesso.";
      } catch(Exception $e) {
         $this->error = true;
         $this->message = $e->getMessage();
      }
   }

   public function update():void {
      try {
         $retorno = $this->service->updateItem($this->item);
         $retorno = $this->service->deleteVariacaoList($this->item->cod_item);
         $retorno = $this->service->newVariacaoList($this->item->cod_item, $this->vars);
         $this->message = "Item `".$this->item->nome_item."` alterado com sucesso.";
      } catch(Exception $e) {
         $this->error = true;
         $this->message = $e->getMessage();
      }
   }

   public function remove($cod_item, $nome_item):void {
      try {
         $retorno = $this->service->deleteItem($cod_item);
         $retorno = $this->service->deleteVariacaoList($cod_item);
         $this->message = "Item `".$nome_item."` excluido com sucesso.";
      } catch(Exception $e) {
         $this->error = true;
         $this->message = $e->getMessage();
      }
   }

}
?>