<?php
require_once(__DIR__."/../lib/GenericDAO.php");
require_once(__DIR__."/../inc/functions.php");

class ItemDAO extends GenericDAO {

   public function __construct($conn){
      parent::__construct($conn);
   }

   public function getItemQty(){
      $sql = "SELECT COUNT(*) AS cont FROM item ";
      $rs = $this->connection->singleResult($sql, null);
      return $rs->cont;
   }

   public function getItemList($search){
      $where = " WHERE TRUE ";
      if($search != "") {
         $where .= " AND nome_item LIKE '%".addslashes($search)."%' ";
      }
      $sql = "SELECT i.*, i.tipo AS item_tipo ".
             "  FROM item i ".
            $where.
             " ORDER BY i.nome_item ";
      
      $list = $this->connection->allResults($sql);
      return $list;
   }

   public function getItemListByFilter($filter){
      $where = " WHERE TRUE ";
      if(!empty($filter->nome_item)) $where .= " AND i.nome_item LIKE '%".addslashes($filter->nome_item)."%' ";
      if(!empty($filter->tipo)) $where .= " AND i.tipo = '".$filter->tipo."' ";
      $sql = "SELECT i.* ".
             "  FROM item i ".
            $where.
             " ORDER BY i.nome_item ";
     
      $list = $this->connection->allResults($sql);
      return $list;
   }

   public function getItem($cod_item){
      $sql = "SELECT * FROM item ".
             " WHERE cod_item = '#cod_item#' ";
      $parameters["cod_item"] = $cod_item;
     
      $row = $this->connection->singleResult($sql, $parameters);
      if(is_object($row) && $row->tipo == '2') { 
         $row->variacao = $this->getVariacaoList($cod_item);
      }
      return $row;
   }

   public function getItemByName($nome_item){
      $sql = "SELECT * FROM item ".
             " WHERE nome_item = '#nome_item#' ";
      $parameters["nome_item"] = addslashes($nome_item);
      
      $row = $this->connection->singleResult($sql, $parameters);
      if($row->tipo == '2') { 
         $row->variacao = $this->getVariacaoList($row->cod_item);
      }
      return $row;
   }

   private function setItem($item) {
      return array(
         "cod_item"           => $item->cod_item,
         "tipo"               => $item->tipo,
         "nome_item"          => mb_strtoupper(addslashes($item->nome_item)),
         "val_custo"          => $item->val_custo,
         "val_ref"            => $item->val_ref,
         "medida"             => $item->medida,
         "un"                 => $item->un,
         "controla_estoque"   => intval($item->controla_estoque)
      );
   }

   public function newItem($item){
      $sql = "INSERT INTO item (tipo, nome_item, val_custo, val_ref, medida, un, controla_estoque) ".
             "VALUES ('#tipo#', '#nome_item#', '#val_custo#', '#val_ref#', '#medida#', '#un#', '#controla_estoque#') ";
      $parameters = $this->setItem($item);
      debugSQL($sql, $parameters, $this->connection);
      $result = $this->connection->query($sql, $parameters, false);
      return $this->connection->getLastGeneratedId();
   }

   public function updateItem($item){
      $sql = "UPDATE item ".
               " SET tipo = '#tipo#', ".
                   " nome_item = '#nome_item#', ".
                   " val_custo = '#val_custo#', ".
                   " val_ref = '#val_ref#', ".
                   " medida = '#medida#', ".
                   " un = '#un#', ".
                   " controla_estoque = '#controla_estoque#' ".
             " WHERE cod_item = '#cod_item#' ";
      $parameters = $this->setItem($item);
      debugSQL($sql, $parameters, $this->connection);
      return $this->connection->query($sql, $parameters, false);
   }

   public function deleteItem($cod_item){
      $sql = "DELETE FROM item ".
             " WHERE cod_item = #cod_item# ";
      $parameters["cod_item"] = $cod_item;
      return $this->connection->execute($sql, $parameters);
   }

   public function getDescricaoTipoItem($tipo){
      switch($tipo){
         case '1': return "Normal";
         case '2': return "Com_Variacao";
         case '3': return "Com_Medida";
      }
   }
   public function getVariacaoList($cod_item) {
      $sql = "SELECT * ".
             "  FROM item_var ".
             " WHERE cod_item = '#cod_item#' ";
      $parameters["cod_item"] = $cod_item;
      $list = $this->connection->allResults($sql, $parameters);
      return $list;
   }

   public function newVariacaoList($cod_item, $lista_var) {
      if(count($lista_var) <= 0) return false;
      $sql = "INSERT INTO item_var (cod_item, cod_var, nome_var, custo_var, val_var) VALUES ";
      $seq = 0;
      foreach($lista_var as $row_var) {
         $seq++;
         $nome_var = mb_strtoupper(addslashes($row_var->nome_var));
         $sql .= "('$cod_item', '$seq', '$nome_var', '$row_var->custo_var', '$row_var->val_var'),";
      }
      $sql = rtrim($sql, ',');

      $parameters = null;
      $this->connection->query($sql, $parameters, false);
   }

   public function deleteVariacaoList($cod_item) {
      $sql = "DELETE FROM item_var ".
             " WHERE cod_item = '#cod_item#' ";
      $parameters["cod_item"] = $cod_item;
      
      return $this->connection->execute($sql, $parameters);
   }

 
   public function getItemUnidadeList() {
      return (object) array(
        'km',  //kilometro
        'm',   //metro
        'cm',  //centimetro
        'mm',  //milimetro
        'm²',
        'km²',
        'cm²',
        'mm²',
        'm³',
        'cm³',
        'mm³',
        'Kg',  //kilograma
        'g',   //grama
        'mg',  //miligrama
        'l',   //litro
        'ml',  //mililitro
        'pç',  //peça
        'd',   //dia
        'hr',  //hora
        'min', //minuto
        'seg', //segundo
        'ms',  //milisegundo
        'µs',  //microsegundo
        'A',   //ampere
        'V',   //volt
        'W',   //watt
        'J',   //joule
        'm/s', //metro por segundo
      );
   }

}
