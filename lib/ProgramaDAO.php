<?php
require_once(__DIR__."/../lib/GenericDAO.php");
require_once(__DIR__."/../inc/functions.php");

class ProgramaDAO extends GenericDAO {

   public function __construct($conn){
      parent::__construct($conn);
   }

   public function getProgramaQty(){
      $sql = "SELECT COUNT(*) AS cont FROM programa ";
      $parameters = null;
      $rs = $this->connection->singleResult($sql, $parameters);
      return $rs->cont;
   }

   public function getProgramaList($search){
      $where = " WHERE 1=1 ";
      if($search != "") {
         $where .= " AND nome_programa LIKE '%".addslashes($search)."%' ";
      }
      $sql = "SELECT * ".
             "  FROM programa ".
            $where.
             " ORDER BY ind_tipo, ordem ";
      $list = $this->connection->allResults($sql);
      return $list;
   }

   public function getPrograma($cod_programa){
      $sql = "SELECT * FROM programa ".
             " WHERE cod_programa = '#cod_programa#' ";
      $parameters["cod_programa"] = $cod_programa;
      $row = $this->connection->singleResult($sql, $parameters);
      return $row;
   }

   public function getProgramaByName($nome_programa){
      $sql = "SELECT * FROM programa ".
             " WHERE nome_programa = '#nome_programa#' ";
      $parameters["nome_programa"] = addslashes($nome_programa);
      
      $row = $this->connection->singleResult($sql, $parameters);
      return $row;
   }

   public function getProgramaBySigla($sigla){
      $sql = "SELECT * FROM programa ".
             " WHERE sigla = '#sigla#' ";
      $parameters["sigla"] = $sigla;
      
      $row = $this->connection->singleResult($sql, $parameters);
      return $row;
   }

   private function setPrograma($programa) {
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

   public function newPrograma($programa){
      $sql = "INSERT INTO programa (ind_tipo, ordem, nome_programa, nome_menu, icone_menu, ind_qtd, sigla, desc_list, desc_cad, ind_ativo) ".
             "VALUES ('#ind_tipo#', '#ordem#', '#nome_programa#', '#nome_menu#', '#icone_menu#', '#ind_qtd#', '#sigla#', '#desc_list#', '#desc_cad#', '#ind_ativo#') ";
      $parameters = $this->setPrograma($programa);
      $result = $this->connection->query($sql, $parameters, false);
      return $this->connection->getLastGeneratedId();
   }

   public function updatePrograma($programa){
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
      $parameters = $this->setPrograma($programa);
      return $this->connection->query($sql, $parameters, false);
   }

   public function deletePrograma($cod_programa){
      $sql = "DELETE FROM programa ".
             " WHERE cod_programa = #cod_programa# ";
      $parameters["cod_programa"] = $cod_programa;
      return $this->connection->execute($sql, $parameters);
   }

   public function getDescricaoTipoPrograma($ind_tipo){
      switch($ind_tipo){
         case "1": return "Cadastro";
         case "2": return "Tarefa";
         case "3": return "Consulta";
         case "4": return "Relatorio";
         case "8": return "Configuracao";
      }
   }

}
