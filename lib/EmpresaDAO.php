<?php
require_once(__DIR__."/../lib/GenericDAO.php");

// A classe EmpresaDAO estende a classe genérica GenericDAO, herdando suas funcionalidades.
class EmpresaDAO extends GenericDAO {

   // Construtor da classe. Inicializa o objeto da classe pai passando a conexão como parâmetro.
   public function __construct($conn){
      // Chama o construtor da classe pai (GenericDAO) passando a conexão.
      parent::__construct($conn);
   }

   // Método para obter a quantidade de empresas registradas no banco de dados.
   public function getEmpresaQty(){
      // Define a consulta SQL para contar o número de registros na tabela "empresa".
      $sql = "SELECT COUNT(*) AS cont FROM empresa ";
      // Não há parâmetros dinâmicos nesta consulta, então $parameters é definido como null.
      $parameters = null;
      // Executa a consulta e armazena o resultado.
      $conta = $this->connection->singleResult($sql, $parameters);
      // Retorna o valor do campo 'cont' que contém a contagem de empresas.
      return $conta->cont;
   }

    // Método para buscar uma lista de empresas com a opção de pesquisar por nome.
   public function getEmpresaList($search){
      // Define a condição inicial "WHERE 1=1" para facilitar a adição de outras condições.
      $where = " WHERE 1=1 ";
      // Se houver uma string de busca, adiciona uma condição à cláusula WHERE.
      if($search != "" && $search != null) {
         // Usa addslashes para escapar caracteres especiais na busca e evitar SQL Injection.
         $where .= " AND p.nome_pessoa LIKE '%".addslashes($search)."%' ";
      }
      // Monta a consulta SQL para selecionar empresas e seus detalhes relacionados à tabela "pessoa".
      $sql = "SELECT e.*, p.id_federal, p.nome_pessoa AS nome_empresa ".
             "  FROM empresa e ".
             " INNER JOIN pessoa p ON p.cod_pessoa = e.cod_empresa ".
             $where.
             " ORDER BY e.cod_empresa ";
      // Executa a consulta e retorna todos os resultados.       
      $list = $this->connection->allResults($sql);
      return $list;
   }

   // Método para buscar uma lista de empresas que não possuem parâmetros configurados.
   public function getEmpresaListNoParam(){
      // Monta a consulta SQL para selecionar empresas sem registros na tabela "param".
      $sql = "SELECT e.cod_empresa, p.nome_pessoa AS nome_empresa ".
               "FROM empresa e, pessoa p ".
              "WHERE p.cod_pessoa = e.cod_empresa ".
                "AND NOT EXISTS (SELECT * FROM param WHERE cod_empresa = e.cod_empresa)";
      // Executa a consulta e retorna todos os resultados.          
      $list = $this->connection->allResults($sql);
      return $list;
   }

   // Método para buscar os detalhes de uma empresa específica.
   public function getEmpresa($cod_empresa){
      // Monta a consulta SQL para selecionar uma empresa com base no código fornecido.
      $sql = "SELECT e.*, p.*, p.nome_pessoa AS nome_empresa ".
             "  FROM empresa e ".
             " INNER JOIN pessoa p ON p.cod_pessoa = e.cod_empresa ".
             " WHERE e.cod_empresa = '#cod_empresa#' ";
      // Prepara os parâmetros substituindo o valor do código da empresa na consulta.       
      $parameters["cod_empresa"] = $cod_empresa;
      // Executa a consulta e retorna os detalhes da empresa.
      $empresa = $this->connection->singleResult($sql, $parameters);
      return $empresa;
   }

   // Método privado para preparar os parâmetros para inserção ou atualização de uma empresa.
   private function setEmpresa($empresa) {
      // Retorna um array associativo com os valores de `cod_empresa` e `ind_matriz` da empresa.
      return array(
         "cod_empresa" => $empresa->cod_empresa,
         "ind_matriz"  => $empresa->ind_matriz
      );
   }

    // Método para inserir uma nova empresa no banco de dados.
   public function newEmpresa($empresa){
      // Monta a consulta SQL para inserir uma nova empresa.
      $sql = "INSERT INTO empresa (cod_empresa, ind_matriz) ".
             "VALUES ('#cod_empresa#', '#ind_matriz#') ";
      // Prepara os parâmetros usando o método `setEmpresa`.       
      $parameters = $this->setEmpresa($empresa);
      // Executa a consulta de inserção sem esperar um retorno direto.
      $result = $this->connection->query($sql, $parameters, false);
      // Retorna o último ID gerado pela inserção.
      return $this->connection->getLastGeneratedId();
   }

   // Método para atualizar os dados de uma empresa existente.
   public function updateEmpresa($empresa){
      // Monta a consulta SQL para atualizar os dados de uma empresa.
      $sql = "UPDATE empresa ".
             "   SET ind_matriz = '#ind_matriz#' ".
             " WHERE cod_empresa  = '#cod_empresa#' ";
      // Prepara os parâmetros usando o método `setEmpresa`.       
      $parameters = $this->setEmpresa($empresa);
      // Executa a consulta de atualização.
      return $this->connection->query($sql, $parameters, false);
   }

   // Método para excluir uma empresa do banco de dados.
   public function deleteEmpresa($cod_empresa){
      // Monta a consulta SQL para deletar uma empresa com base no código fornecido.
      $sql = "DELETE FROM empresa WHERE cod_empresa = '#cod_empresa#' ";
      // Prepara os parâmetros substituindo o valor do código da empresa na consulta.
      $parameters["cod_empresa"] = $cod_empresa;
      // Executa a consulta de exclusão.
      return $this->connection->execute($sql, $parameters);
   }

   // Método para buscar o código da empresa que é matriz.
   public function getEmpresaMatriz(){
      // Monta a consulta SQL para selecionar a empresa que é matriz (ind_matriz = 1).
      $sql = "SELECT cod_empresa FROM empresa WHERE ind_matriz = '1' ";
      // Não há parâmetros dinâmicos nesta consulta, então $parameters é definido como null.
      $parameters = null;
      // Executa a consulta e retorna o código da empresa matriz.
      $ret = $this->connection->singleResult($sql, $parameters);
      return $ret->cod_empresa;
   }

}
