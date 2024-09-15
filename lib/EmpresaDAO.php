<?php
require_once(__DIR__."/../lib/GenericDAO.php");

class EmpresaDAO extends GenericDAO {

   public function __construct($conn){
      parent::__construct($conn);
   }

   public function getEmpresaQty(){
      $sql = "SELECT COUNT(*) AS cont FROM empresa ";
      $parameters = null;
      $conta = $this->connection->singleResult($sql, $parameters);
      return $conta->cont;
   }

   public function getEmpresaList($search){
      $where = " WHERE 1=1 ";
      if($search != "" && $search != null) {
         $where .= " AND p.nome_pessoa LIKE '%".addslashes($search)."%' ";
      }
      $sql = "SELECT e.*, p.id_federal, p.nome_pessoa AS nome_empresa ".
             "  FROM empresa e ".
             " INNER JOIN pessoa p ON p.cod_pessoa = e.cod_empresa ".
             $where.
             " ORDER BY e.cod_empresa ";
      $list = $this->connection->allResults($sql);
      return $list;
   }

   public function getEmpresaListNoParam(){
      $sql = "SELECT e.cod_empresa, p.nome_pessoa AS nome_empresa ".
               "FROM empresa e, pessoa p ".
              "WHERE p.cod_pessoa = e.cod_empresa ".
                "AND NOT EXISTS (SELECT * FROM param WHERE cod_empresa = e.cod_empresa)";
      $list = $this->connection->allResults($sql);
      return $list;
   }

   public function getEmpresa($cod_empresa){
      $sql = "SELECT e.*, p.*, p.nome_pessoa AS nome_empresa ".
             "  FROM empresa e ".
             " INNER JOIN pessoa p ON p.cod_pessoa = e.cod_empresa ".
             " WHERE e.cod_empresa = '#cod_empresa#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      $empresa = $this->connection->singleResult($sql, $parameters);
      return $empresa;
   }

   private function setEmpresa($empresa) {
      return array(
         "cod_empresa" => $empresa->cod_empresa,
         "ind_matriz"  => $empresa->ind_matriz
      );
   }

   public function newEmpresa($empresa){
      $sql = "INSERT INTO empresa (cod_empresa, ind_matriz) ".
             "VALUES ('#cod_empresa#', '#ind_matriz#') ";
      $parameters = $this->setEmpresa($empresa);
      $result = $this->connection->query($sql, $parameters, false);
      return $this->connection->getLastGeneratedId();
   }

   public function updateEmpresa($empresa){
      $sql = "UPDATE empresa ".
             "   SET ind_matriz = '#ind_matriz#' ".
             " WHERE cod_empresa  = '#cod_empresa#' ";
      $parameters = $this->setEmpresa($empresa);
      return $this->connection->query($sql, $parameters, false);
   }

   public function deleteEmpresa($cod_empresa){
      $sql = "DELETE FROM empresa WHERE cod_empresa = '#cod_empresa#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      
      return $this->connection->execute($sql, $parameters);
   }

   public function getEmpresaMatriz(){
      $sql = "SELECT cod_empresa FROM empresa WHERE ind_matriz = '1' ";
      $parameters = null;
      $ret = $this->connection->singleResult($sql, $parameters);
      return $ret->cod_empresa;
   }

}
