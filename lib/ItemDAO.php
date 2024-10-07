<?php
// Inclui o arquivo GenericDAO.php, que contém a classe genérica de acesso ao banco de dados.
require_once(__DIR__."/../lib/GenericDAO.php");
// Inclui o arquivo de funções auxiliares.
require_once(__DIR__."/../inc/functions.php");

// Define a classe ItemDAO que herda de GenericDAO, para gerenciar itens no banco de dados.
class ItemDAO extends GenericDAO {

   // Construtor da classe que chama o construtor da classe pai (GenericDAO) para inicializar a conexão.
   public function __construct($conn){
      parent::__construct($conn);
   }

   // Método para obter a quantidade total de itens no banco de dados.
   public function getItemQty(){
      // SQL para contar o número de registros na tabela de itens.
      $sql = "SELECT COUNT(*) AS cont FROM item ";
      // Executa o SQL e armazena o resultado.
      $rs = $this->connection->singleResult($sql, null);
      // Retorna o valor da contagem.
      return $rs->cont;
   }

   // Método para obter a lista de itens com base em um termo de busca.
   public function getItemList($search){
      // Inicializa a cláusula WHERE com "TRUE" para facilitar a adição de condições.
      $where = " WHERE TRUE ";
      // Se houver uma busca, adiciona uma condição para pesquisar o nome do item.
      if($search != "") {
         $where .= " AND nome_item LIKE '%".addslashes($search)."%' ";
      }
      // SQL para buscar os itens e ordená-los pelo nome.
      $sql = "SELECT i.*, i.tipo AS item_tipo ".
             "  FROM item i ".
            $where.
             " ORDER BY i.nome_item ";
      
      // Executa o SQL e retorna a lista de itens.
      $list = $this->connection->allResults($sql);
      return $list;
   }

   // Método para obter a lista de itens com base em filtros específicos.
   public function getItemListByFilter($filter){
      // Inicializa a cláusula WHERE com "TRUE" para adicionar condições dinamicamente.
      $where = " WHERE TRUE ";
      // Adiciona uma condição para o nome do item, se estiver presente no filtro.
      if(!empty($filter->nome_item)) $where .= " AND i.nome_item LIKE '%".addslashes($filter->nome_item)."%' ";
      // Adiciona uma condição para o tipo de item, se presente no filtro.
      if(!empty($filter->tipo)) $where .= " AND i.tipo = '".$filter->tipo."' ";
      // SQL para buscar os itens com base nos filtros aplicados.
      $sql = "SELECT i.* ".
             "  FROM item i ".
            $where.
             " ORDER BY i.nome_item ";
      
      // Executa o SQL e retorna a lista de itens filtrados.
      $list = $this->connection->allResults($sql);
      return $list;
   }

   // Método para obter um item específico com base no código do item.
   public function getItem($cod_item){
      // SQL para buscar o item com base no código.
      $sql = "SELECT * FROM item ".
             " WHERE cod_item = '#cod_item#' ";
      // Define o parâmetro do código do item.       
      $parameters["cod_item"] = $cod_item;
      // Executa o SQL e armazena o resultado.
      $row = $this->connection->singleResult($sql, $parameters);
      // Se o tipo do item for 2, busca também a lista de variações associadas ao item.
      if(is_object($row) && $row->tipo == '2') { 
         $row->variacao = $this->getVariacaoList($cod_item);
      }
      // Retorna o item com suas informações.
      return $row;
   }

   // Método para obter um item com base no nome.
   public function getItemByName($nome_item){
      // SQL para buscar o item com base no nome.
      $sql = "SELECT * FROM item ".
             " WHERE nome_item = '#nome_item#' ";
      // Define o parâmetro do nome do item.       
      $parameters["nome_item"] = addslashes($nome_item);
      // Executa o SQL e armazena o resultado.
      $row = $this->connection->singleResult($sql, $parameters);
      // Se o tipo do item for 2, busca também a lista de variações associadas ao item.
      if($row->tipo == '2') { 
         $row->variacao = $this->getVariacaoList($row->cod_item);
      }
      // Retorna o item com suas informações.
      return $row;
   }

   // Método privado para mapear os dados de um item para um array associativo.
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

   // Método para adicionar um novo item ao banco de dados.
   public function newItem($item){
      // SQL para inserir um novo item.
      $sql = "INSERT INTO item (tipo, nome_item, val_custo, val_ref, medida, un, controla_estoque) ".
             "VALUES ('#tipo#', '#nome_item#', '#val_custo#', '#val_ref#', '#medida#', '#un#', '#controla_estoque#') ";
      // Mapeia o item para os parâmetros.
      $parameters = $this->setItem($item);
      // Chama a função para debugar o SQL.
      debugSQL($sql, $parameters, $this->connection);
      // Executa o SQL de inserção.
      $result = $this->connection->query($sql, $parameters, false);
      // Retorna o ID gerado para o novo item.
      return $this->connection->getLastGeneratedId();
   }

   // Método para atualizar as informações de um item existente.
   public function updateItem($item){
      // SQL para atualizar os dados de um item.
      $sql = "UPDATE item ".
               " SET tipo = '#tipo#', ".
                   " nome_item = '#nome_item#', ".
                   " val_custo = '#val_custo#', ".
                   " val_ref = '#val_ref#', ".
                   " medida = '#medida#', ".
                   " un = '#un#', ".
                   " controla_estoque = '#controla_estoque#' ".
             " WHERE cod_item = '#cod_item#' ";
      // Mapeia o item para os parâmetros.       
      $parameters = $this->setItem($item);
      // Chama a função para debugar o SQL.
      debugSQL($sql, $parameters, $this->connection);
      // Executa o SQL de atualização.
      return $this->connection->query($sql, $parameters, false);
   }

   // Método para deletar um item do banco de dados com base no código.
   public function deleteItem($cod_item){
      // SQL para deletar o item.
      $sql = "DELETE FROM item ".
             " WHERE cod_item = #cod_item# ";
      // Define o parâmetro do código do item.
      $parameters["cod_item"] = $cod_item;
      // Executa o SQL de deleção.
      return $this->connection->execute($sql, $parameters);
   }

   // Método para obter a descrição do tipo de item.
   public function getDescricaoTipoItem($tipo){
      // Retorna a descrição do tipo de item com base no código.
      switch($tipo){
         case '1': return "Normal";
         case '2': return "Com_Variacao";
         case '3': return "Com_Medida";
      }
   }

   // Método para obter a lista de variações associadas a um item.
   public function getVariacaoList($cod_item) {
      // SQL para buscar as variações de um item com base no código.
      $sql = "SELECT * ".
             "  FROM item_var ".
             " WHERE cod_item = '#cod_item#' ";
      // Define o parâmetro do código do item.       
      $parameters["cod_item"] = $cod_item;
      // Executa o SQL e retorna a lista de variações.
      $list = $this->connection->allResults($sql, $parameters);
      return $list;
   }

   // Método para adicionar variações a um item no banco de dados.
   public function newVariacaoList($cod_item, $lista_var) {
      // Se a lista de variações estiver vazia, não faz nada.
      if(count($lista_var) <= 0) return false;
      // SQL para inserir múltiplas variações para um item.
      $sql = "INSERT INTO item_var (cod_item, cod_var, nome_var, custo_var, val_var) VALUES ";
      $seq = 0;
      // Para cada variação, adiciona uma linha ao SQL.
      foreach($lista_var as $row_var) {
         $seq++;
         // Prepara o nome da variação.
         $nome_var = mb_strtoupper(addslashes($row_var->nome_var));
         // Adiciona os valores ao SQL.
         $sql .= "('$cod_item', '$seq', '$nome_var', '$row_var->custo_var', '$row_var->val_var'),";
      }
      // Remove a última vírgula do SQL.
      $sql = rtrim($sql, ',');
      // Executa o SQL de inserção.
      $parameters = null;
      $this->connection->query($sql, $parameters, false);
   }

   // Método para deletar todas as variações de um item.
   public function deleteVariacaoList($cod_item) {
      // SQL para deletar as variações de um item.
      $sql = "DELETE FROM item_var ".
             " WHERE cod_item = '#cod_item#' ";
      // Define o parâmetro do código do item.       
      $parameters["cod_item"] = $cod_item;
      // Executa o SQL de deleção.
      return $this->connection->execute($sql, $parameters);
   }

   // Método para obter a lista de unidades de medida disponíveis.
   public function getItemUnidadeList() {
      // Retorna um objeto contendo uma lista de unidades de medida.
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
