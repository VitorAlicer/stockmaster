<?php
// Inclui os arquivos necessários: a classe GenericDAO e funções auxiliares
require_once(__DIR__."/../lib/GenericDAO.php");
require_once(__DIR__."/../inc/functions.php");

// Define a classe ProgramaDAO, que herda da classe GenericDAO
class ProgramaDAO extends GenericDAO {

   // Construtor da classe, inicializa a conexão com o banco de dados
   public function __construct($conn){
      // Chama o construtor da classe pai (GenericDAO) para inicializar a conexão
      parent::__construct($conn);
   }

   // Método que retorna a quantidade total de registros na tabela 'programa'
   public function getProgramaQty(){
      // SQL para contar o número total de programas
      $sql = "SELECT COUNT(*) AS cont FROM programa ";
      $parameters = null; // Nenhum parâmetro necessário para esta consulta
      // Executa a query e obtém o resultado único
      $rs = $this->connection->singleResult($sql, $parameters);
      return $rs->cont; // Retorna a contagem de programas
   }

   // Método que retorna uma lista de programas com base em um termo de busca
   public function getProgramaList($search){
      // Inicializa a cláusula WHERE com uma condição sempre verdadeira
      $where = " WHERE 1=1 ";
      // Se houver um termo de busca, adiciona um filtro no nome do programa
      if($search != "") {
         $where .= " AND nome_programa LIKE '%".addslashes($search)."%' ";
      }
      // SQL para selecionar todos os programas com as condições WHERE aplicadas
      $sql = "SELECT * ".
             "  FROM programa ".
            $where.
             " ORDER BY ind_tipo, ordem "; // Ordena os resultados pelo tipo e pela ordem
      // Executa a query e retorna todos os resultados
      $list = $this->connection->allResults($sql);
      return $list;
   }

   // Método que retorna os dados de um programa específico, baseado no código do programa
   public function getPrograma($cod_programa){
      // SQL para selecionar um programa com base no código
      $sql = "SELECT * FROM programa ".
             " WHERE cod_programa = '#cod_programa#' ";
      // Define o código do programa como parâmetro
      $parameters["cod_programa"] = $cod_programa;
      // Executa a query e obtém o resultado único
      $row = $this->connection->singleResult($sql, $parameters);
      return $row;
   }

   // Método que retorna os dados de um programa específico, baseado no nome do programa
   public function getProgramaByName($nome_programa){
      // SQL para selecionar um programa com base no nome
      $sql = "SELECT * FROM programa ".
             " WHERE nome_programa = '#nome_programa#' ";
      // Define o nome do programa como parâmetro, escapando caracteres especiais
      $parameters["nome_programa"] = addslashes($nome_programa);
      // Executa a query e obtém o resultado único
      $row = $this->connection->singleResult($sql, $parameters);
      return $row;
   }

   // Método que retorna os dados de um programa específico, baseado na sigla
   public function getProgramaBySigla($sigla){
      // SQL para selecionar um programa com base na sigla
      $sql = "SELECT * FROM programa ".
             " WHERE sigla = '#sigla#' ";
      // Define a sigla como parâmetro
      $parameters["sigla"] = $sigla;
      // Executa a query e obtém o resultado único
      $row = $this->connection->singleResult($sql, $parameters);
      return $row;
   }

   // Método privado que prepara os dados de um programa para inserção ou atualização no banco
   private function setPrograma($programa) {
      // Retorna um array associativo com os valores do programa formatados
      return array(
         "cod_programa"  => $programa->cod_programa,
         "ind_tipo"      => $programa->ind_tipo,
         "ordem"         => $programa->ordem,
         "nome_programa" => addslashes($programa->nome_programa),
         "nome_menu"     => addslashes($programa->nome_menu),
         "icone_menu"    => $programa->icone_menu,
         "ind_qtd"       => intval($programa->ind_qtd),
         "sigla"         => addslashes($programa->sigla),
         "desc_list"     => addslashes($programa->desc_list),
         "desc_cad"      => addslashes($programa->desc_cad),
         "ind_ativo"     => intval($programa->ind_ativo)
      );
   }

   // Método que insere um novo programa no banco de dados
   public function newPrograma($programa){
      // SQL para inserir um novo programa na tabela 'programa'
      $sql = "INSERT INTO programa (ind_tipo, ordem, nome_programa, nome_menu, icone_menu, ind_qtd, sigla, desc_list, desc_cad, ind_ativo) ".
             "VALUES ('#ind_tipo#', '#ordem#', '#nome_programa#', '#nome_menu#', '#icone_menu#', '#ind_qtd#', '#sigla#', '#desc_list#', '#desc_cad#', '#ind_ativo#') ";
      // Define os parâmetros com os valores do programa
      $parameters = $this->setPrograma($programa);
      // Executa a query para inserir o novo programa
      $result = $this->connection->query($sql, $parameters, false);
      // Retorna o código do programa inserido
      return $this->connection->getLastGeneratedId();
   }

   // Método que atualiza os dados de um programa no banco de dados
   public function updatePrograma($programa){
      // SQL para atualizar os dados de um programa na tabela 'programa'
      $sql = "UPDATE programa ".
             "   SET ind_tipo      = '#ind_tipo#', ".
             "       ordem         = '#ordem#', ".
             "       nome_programa = '#nome_programa#', ".
             "       nome_menu     = '#nome_menu#', ".
             "       icone_menu    = '#icone_menu#', ".
             "       ind_qtd       = '#ind_qtd#', ".
             "       sigla         = '#sigla#', ".
             "       desc_list     = '#desc_list#', ".
             "       desc_cad      = '#desc_cad#', ".
             "       ind_ativo     = '#ind_ativo#' ".
             " WHERE cod_programa = '#cod_programa#' ";
      // Define os parâmetros com os valores atualizados do programa
      $parameters = $this->setPrograma($programa);
      // Executa a query para atualizar o programa
      return $this->connection->query($sql, $parameters, false);
   }

   // Método que remove um programa do banco de dados
   public function deletePrograma($cod_programa){
      // SQL para deletar um programa com base no código
      $sql = "DELETE FROM programa ".
             " WHERE cod_programa = #cod_programa# ";
      // Define o código do programa como parâmetro
      $parameters["cod_programa"] = $cod_programa;
      // Executa a query para deletar o programa
      return $this->connection->execute($sql, $parameters);
   }

   // Método que retorna uma descrição legível do tipo de programa com base em um indicador numérico
   public function getDescricaoTipoPrograma($ind_tipo){
      // Retorna uma string descritiva baseada no valor de ind_tipo
      switch($ind_tipo){
         case "1": return "Cadastro"; // Tipo 1 é "Cadastro"
         case "2": return "Tarefa";   // Tipo 2 é "Tarefa"
         case "3": return "Consulta"; // Tipo 3 é "Consulta"
         case "4": return "Relatorio";// Tipo 4 é "Relatório"
         case "8": return "Configuracao"; // Tipo 8 é "Configuração"
      }
   }

}
