<?php
require_once(__DIR__."/../lib/GenericDAO.php");

class PerfilDAO extends GenericDAO {

   public function __construct($conn) {
      parent::__construct($conn);
   }

   public function getPerfilQty(){
      $sql = "SELECT COUNT(*) AS cont FROM perfil ";
      $rs = $this->connection->singleResult($sql, null);
      return $rs->cont;
   }

   public function getPerfilList($search) {
      $where = " WHERE 1=1 ";
      if($search != "") {
         $where .= " AND nome_perfil LIKE '%".addslashes($search)."%' ";
      }
      $sql = "SELECT * ".
             "  FROM perfil ".
            $where.
             " ORDER BY cod_perfil ";
      $list = $this->connection->allResults($sql);
      return $list;
   }

   public function getPerfilListByFilter($filter) {
      $where = " WHERE 1=1 ";
      if($filter->nome_perfil != "") $where .= " AND nome_perfil LIKE '%".addslashes($filter->nome_perfil)."%' ";
      $sql = "SELECT * ".
             "  FROM perfil ".
            $where.
             " ORDER BY nome_perfil ";
      
      $list = $this->connection->allResults($sql);
      return $list;
   }

   public function getPerfil($cod_perfil) {
      $sql = "SELECT * FROM perfil ".
             " WHERE cod_perfil = '#cod_perfil#' ";
      $parameters["cod_perfil"] = $cod_perfil;
      $row = $this->connection->singleResult($sql, $parameters);
      return $row;
   }

   public function getPerfilByName($nome_perfil) {
      $sql = "SELECT * FROM perfil ".
             " WHERE nome_perfil = '#nome_perfil#' ";
      $parameters["nome_perfil"] = addslashes($nome_perfil);
      
      $row = $this->connection->singleResult($sql, $parameters);
      return $row;
   }

   private function setPerfil($perfil) {
      return array(
         "cod_perfil"  => $perfil->cod_perfil,
         "nome_perfil" => mb_strtoupper(addslashes($perfil->nome_perfil)),
         "ativo"      => $perfil->ativo
      );
   }

   public function newPerfil($perfil) {
      $sql = "INSERT INTO perfil (nome_perfil, ativo) ".
             "VALUES ('#nome_perfil#', '#ativo#') ";
      $parameters = $this->setPerfil($perfil);
      $result = $this->connection->query($sql, $parameters, false);
      return $this->connection->getLastGeneratedId();
   }

   public function updatePerfil($perfil) {
      $sql = "UPDATE perfil ".
             " SET nome_perfil = '#nome_perfil#', ".
                 " ativo = '#ativo#' ".
             " WHERE cod_perfil = '#cod_perfil#' ";
      $parameters = $this->setPerfil($perfil);
      
      return $this->connection->query($sql, $parameters, false);
   }

   public function deletePerfil($cod_perfil) {
      $sql = "DELETE FROM perfil ".
             " WHERE cod_perfil = #cod_perfil# ";
      $parameters["cod_perfil"] = $cod_perfil;
      return $this->connection->execute($sql, $parameters);
   }

}
