<?php
// Inclui o arquivo da classe GenericDAO, garantindo que só será incluído uma vez
require_once(__DIR__."/../lib/GenericDAO.php");

// Define a classe PerfilDAO que herda de GenericDAO
class PerfilDAO extends GenericDAO {

   // Construtor da classe que recebe a conexão como parâmetro
   public function __construct($conn) {
      // Chama o construtor da classe pai para inicializar a conexão
      parent::__construct($conn);
   }

   // Método que retorna a quantidade de registros na tabela 'perfil'
   public function getPerfilQty(){
      // SQL para contar os registros na tabela 'perfil'
      $sql = "SELECT COUNT(*) AS cont FROM perfil ";
      // Executa a query e obtém o resultado (quantidade de registros)
      $rs = $this->connection->singleResult($sql, null);
      // Retorna o valor da contagem
      return $rs->cont;
   }

   // Método que retorna uma lista de perfis, com base em uma pesquisa (filtro de nome)
   public function getPerfilList($search) {
      // Inicializa a cláusula WHERE com uma condição sempre verdadeira
      $where = " WHERE 1=1 ";
      // Se houver um termo de busca, adiciona-o ao filtro de pesquisa (LIKE no nome do perfil)
      if($search != "") {
         $where .= " AND nome_perfil LIKE '%".addslashes($search)."%' ";
      }
      // SQL para selecionar todos os perfis com a condição WHERE aplicada
      $sql = "SELECT * ".
             "  FROM perfil ".
            $where. // Aplica o filtro de pesquisa
             " ORDER BY cod_perfil "; // Ordena os resultados pelo código do perfil
      // Executa a query e retorna todos os resultados
      $list = $this->connection->allResults($sql);
      return $list;
   }

   // Método que retorna uma lista de perfis, filtrada por um objeto de filtros
   public function getPerfilListByFilter($filter) {
      // Inicializa a cláusula WHERE com uma condição sempre verdadeira
      $where = " WHERE 1=1 ";
      // Se o nome do perfil estiver definido no filtro, aplica o filtro no SQL
      if($filter->nome_perfil != "") $where .= " AND nome_perfil LIKE '%".addslashes($filter->nome_perfil)."%' ";
      // SQL para selecionar perfis com o filtro aplicado
      $sql = "SELECT * ".
             "  FROM perfil ".
            $where. // Aplica o filtro de pesquisa
             " ORDER BY nome_perfil "; // Ordena os resultados pelo nome do perfil
      // Executa a query e retorna todos os resultados
      $list = $this->connection->allResults($sql);
      return $list;
   }

   // Método que retorna um perfil específico, baseado no código do perfil
   public function getPerfil($cod_perfil) {
      // SQL para selecionar um perfil com base no código
      $sql = "SELECT * FROM perfil ".
             " WHERE cod_perfil = '#cod_perfil#' ";
      // Define o código do perfil como parâmetro
      $parameters["cod_perfil"] = $cod_perfil;
      // Executa a query e obtém o resultado único
      $row = $this->connection->singleResult($sql, $parameters);
      return $row;
   }

   // Método que retorna um perfil específico, baseado no nome do perfil
   public function getPerfilByName($nome_perfil) {
      // SQL para selecionar um perfil com base no nome
      $sql = "SELECT * FROM perfil ".
             " WHERE nome_perfil = '#nome_perfil#' ";
      // Define o nome do perfil como parâmetro
      $parameters["nome_perfil"] = addslashes($nome_perfil);
      // Executa a query e obtém o resultado único
      $row = $this->connection->singleResult($sql, $parameters);
      return $row;
   }

   // Método privado que formata os dados do perfil para inserção/atualização
   private function setPerfil($perfil) {
      // Retorna um array associativo com os valores do perfil formatados
      return array(
         "cod_perfil"  => $perfil->cod_perfil, // Código do perfil
         "nome_perfil" => mb_strtoupper(addslashes($perfil->nome_perfil)), // Nome do perfil em maiúsculas
         "ativo"      => $perfil->ativo // Status ativo/inativo
      );
   }

   // Método que insere um novo perfil com base nos dados fornecidos
   public function newPerfil($perfil) {
      // SQL para inserir um novo perfil na tabela 'perfil'
      $sql = "INSERT INTO perfil (nome_perfil, ativo) ".
             "VALUES ('#nome_perfil#', '#ativo#') ";
      // Define os parâmetros com os valores do objeto perfil
      $parameters = $this->setPerfil($perfil);
      // Executa a query para inserir o novo perfil
      $result = $this->connection->query($sql, $parameters, false);
      // Retorna o ID gerado para o novo perfil
      return $this->connection->getLastGeneratedId();
   }

   // Método que atualiza um perfil existente
   public function updatePerfil($perfil) {
      // SQL para atualizar os dados de um perfil na tabela 'perfil'
      $sql = "UPDATE perfil ".
             " SET nome_perfil = '#nome_perfil#', ".
                 " ativo = '#ativo#' ".
             " WHERE cod_perfil = '#cod_perfil#' ";
      // Define os parâmetros com os valores do objeto perfil
      $parameters = $this->setPerfil($perfil);
      // Executa a query para atualizar o perfil
      return $this->connection->query($sql, $parameters, false);
   }

   // Método que deleta um perfil com base no código do perfil
   public function deletePerfil($cod_perfil) {
      // SQL para deletar um registro da tabela 'perfil'
      $sql = "DELETE FROM perfil ".
             " WHERE cod_perfil = #cod_perfil# ";
      // Define o código do perfil como parâmetro
      $parameters["cod_perfil"] = $cod_perfil;
      // Executa a query para deletar o perfil
      return $this->connection->execute($sql, $parameters);
   }

}
