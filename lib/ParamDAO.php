<?php
require_once(__DIR__."/../lib/GenericDAO.php");
require_once(__DIR__."/../inc/functions.php");

class ParamDAO extends GenericDAO {

   public function __construct($conn){
      parent::__construct($conn);
   }

   public function getParamQty(){
      $sql = "SELECT COUNT(*) AS cont FROM param ";
      $parameters = null;
      $conta = $this->connection->singleResult($sql, $parameters);
      return $conta->cont;
   }

   public function getParamList($search){
      $sql = "SELECT p.*, e.*, x.*, x.nome_pessoa AS nome_empresa ".
               "FROM param p, empresa e, pessoa x ".
              "WHERE e.cod_empresa = p.cod_empresa ".
                "AND x.cod_pessoa = e.cod_empresa";
      $list = $this->connection->allResults($sql);
      return $list;
   }

   public function getParam($cod_empresa){
      if($cod_empresa == 0) $cod_empresa = '1';
      $sql = "SELECT p.*, e.nome_pessoa AS nome_empresa ".
             "  FROM param p ".
             " INNER JOIN pessoa e ON e.cod_pessoa = p.cod_empresa ".
             " WHERE p.cod_empresa = '#cod_empresa#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      $result = $this->connection->singleResult($sql, $parameters);
      return $result;
   }

   private function setParam($param) {
      return array(
         "cod_empresa"     => $param->cod_empresa,
         "dt_impl_sis"     => date("Y-m-d"),

         "expediente_ini"  => $param->expediente_ini,
         "expediente_fin"  => $param->expediente_fin,
         "intervalo_ini"   => $param->intervalo_ini,
         "intervalo_fin"   => $param->intervalo_fin,
         "dias_uteis"      => $param->dias_uteis,

         "raiz"            => $param->raiz,
         "skin"            => $param->skin,
         "upload"          => $param->upload,

         "pedido"          => intval($param->pedido),
         "ped_gera_fat"    => intval($param->ped_gera_fat),
         "ped_gera_nf"     => intval($param->ped_gera_nf),
         "ped_gera_rec"    => intval($param->ped_gera_rec),
         "ped_status_gera" => intval($param->ped_status_gera),

         "fatura"          => intval($param->fatura),
         "fat_gera_nf"     => intval($param->fat_gera_nf),

         "nota_fiscal"     => intval($param->nota_fiscal),

         "caixa_oper"      => intval($param->caixa_oper),
         "caixa_inicial"   => floatval($param->caixa_inicial),
         "caixa_data"      => ($param->caixa_data == NULL) ? "null" : '"'.$param->caixa_data.'"',
         "caixa_perfil"    => $param->caixa_perfil,

         "fluxo_caixa"     => intval($param->fluxo_caixa),
         "saldo_inicial"   => floatval($param->saldo_inicial),
         "saldo_data"      => ($param->saldo_data == NULL) ? "null" : '"'.$param->saldo_data.'"'

      );
   }

   public function newDefaultParam($cod_empresa) {
      $sql = "INSERT INTO param (cod_empresa, dt_impl_sis, raiz, skin, upload, caixa_oper, caixa_inicial, caixa_data, caixa_perfil, expediente_ini, expediente_fin, intervalo_ini, intervalo_fin, dias_uteis, fluxo_caixa, saldo_inicial, saldo_data, pedido, ped_gera_fat, ped_gera_nf, ped_gera_rec, ped_status_gera, fatura, fat_gera_nf, nota_fiscal) ".
             "VALUES ('#cod_empresa#', '#dt_impl_sis#', '#raiz#', '#skin#', '#upload#', '#caixa_oper#', '#caixa_inicial#', #caixa_data#, '#caixa_perfil#', '#expediente_ini#', '#expediente_fin#', '#intervalo_ini#', '#intervalo_fin#', '#dias_uteis#', '#fluxo_caixa#', '#saldo_inicial#', #saldo_data#, '#pedido#', '#ped_gera_fat#', '#ped_gera_nf#', '#ped_gera_rec#', '#ped_status_gera#', '#fatura#', '#fat_gera_nf#', '#nota_fiscal#') ";
      $parameters = array(
         "cod_empresa"     => $cod_empresa,
         "dt_impl_sis"     => date("Y-m-d"),

         "expediente_ini"  => "08:00",
         "expediente_fin"  => "18:00",
         "intervalo_ini"   => "12:00",
         "intervalo_fin"   => "13:00",
         "dias_uteis"      => "1,2,3,4,5,6",

         "raiz"            => "",
         "skin"            => "skin-blue",
         "upload"          => "/file/empresa".$cod_empresa."/",

         "pedido"          => 0,
         "ped_gera_fat"    => 0,
         "ped_gera_nf"     => 0,
         "ped_gera_rec"    => 0,
         "ped_status_gera" => 0,

         "fatura"          => 0,
         "fat_gera_nf"     => 0,

         "nota_fiscal"     => 0,

         "caixa_oper"      => 0,
         "caixa_inicial"   => 0,
         "caixa_data"      => "null",
         "caixa_perfil"    => "",

         "fluxo_caixa"     => 0,
         "saldo_inicial"   => 0,
         "saldo_data"      => "null"
      );
      
      $result = $this->connection->query($sql, $parameters, false);
      createDirectory(__DIR__."/..".$parameters["upload"]);
      createDirectory(__DIR__."/..".$parameters["upload"]."anexo");
      createDirectory(__DIR__."/..".$parameters["upload"]."anexo/pedido");
      createDirectory(__DIR__."/..".$parameters["upload"]."foto");
      createDirectory(__DIR__."/..".$parameters["upload"]."report");
      copyFile(__DIR__."/../file/empresa/foto/admin.jpg", __DIR__."/..".$parameters["upload"]."foto/admin.jpg");
      copyFile(__DIR__."/../file/empresa/foto/avatar.gif", __DIR__."/..".$parameters["upload"]."foto/avatar.gif");
      copyFile(__DIR__."/../file/empresa/foto/super.jpg", __DIR__."/..".$parameters["upload"]."foto/super.jpg");
      return $result; 
   }

   public function newParam($param){
      $sql = "INSERT INTO param (cod_empresa, raiz, skin, upload, caixa_oper, caixa_inicial, caixa_data, caixa_perfil, expediente_ini, expediente_fin, intervalo_ini, intervalo_fin, dias_uteis, fluxo_caixa, saldo_inicial, saldo_data, pedido, ped_gera_fat, ped_gera_nf, ped_gera_rec, ped_status_gera, fatura, fat_gera_nf, nota_fiscal) ".
             "VALUES ('#cod_empresa#', '#raiz#', '#skin#', '#upload#', '#caixa_oper#', '#caixa_inicial#', #caixa_data#, '#caixa_perfil#', '#expediente_ini#', '#expediente_fin#', '#intervalo_ini#', '#intervalo_fin#', '#dias_uteis#', '#fluxo_caixa#', '#saldo_inicial#', #saldo_data#, '#pedido#', '#ped_gera_fat#', '#ped_gera_nf#', '#ped_gera_rec#', '#ped_status_gera#', '#fatura#', '#fat_gera_nf#', '#nota_fiscal#') ";
      $parameters = $this->setParam($param);
      
      $result = $this->connection->query($sql, $parameters, false);
      return $result; 
   }

   public function updateParam($param){
      $sql = "UPDATE param ".
               " SET raiz           = '#raiz#', ".
                   " skin           = '#skin#', ".
                   " upload         = '#upload#', ".
                   " caixa_oper     = '#caixa_oper#', ".
                   " caixa_inicial  = '#caixa_inicial#', ".
                   " caixa_data     = #caixa_data#, ".
                   " caixa_perfil   = '#caixa_perfil#', ".
                   " expediente_ini = '#expediente_ini#', ".
                   " expediente_fin = '#expediente_fin#', ".
                   " intervalo_ini  = '#intervalo_ini#', ".
                   " intervalo_fin  = '#intervalo_fin#', ".
                   " dias_uteis     = '#dias_uteis#', ".
                   " fluxo_caixa    = '#fluxo_caixa#', ".
                   " saldo_inicial  = '#saldo_inicial#', ".
                   " saldo_data     = #saldo_data#, ".
                   " pedido         = '#pedido#', ".
                   " ped_gera_fat   = '#ped_gera_fat#', ".
                   " ped_gera_nf    = '#ped_gera_nf#', ".
                   " ped_gera_rec   = '#ped_gera_rec#', ".
                   " ped_status_gera= '#ped_status_gera#', ".
                   " fatura         = '#fatura#', ".
                   " fat_gera_nf    = '#fat_gera_nf#', ".
                   " nota_fiscal    = '#nota_fiscal#' ".
             " WHERE cod_empresa    = '#cod_empresa#' ";
      $parameters = $this->setParam($param);
      return $this->connection->query($sql, $parameters, false);
   }

   public function deleteParam($cod_empresa){
      $sql = "DELETE FROM param ".
             " WHERE cod_empresa = #cod_empresa# ";
      $parameters["cod_empresa"] = $cod_empresa;
      removeDirectory(__DIR__."/.."."/file/empresa".$cod_empresa."/");
      return $this->connection->execute($sql, $parameters);
   }

   public function getSkin() {
      return (object) array(
          1 => 'skin-blue',
          2 => 'skin-blue-light',
          3 => 'skin-yellow',
          4 => 'skin-yellow-light',
          5 => 'skin-green',
          6 => 'skin-green-light',
          7 => 'skin-purple',
          8 => 'skin-purple-light',
          9 => 'skin-red',
         10 => 'skin-red-light',
         11 => 'skin-black',
         12 => 'skin-black-light'
      );
   }

}
