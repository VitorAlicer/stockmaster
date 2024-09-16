<?php
require_once(__DIR__."/../init.php");
require_once(__DIR__."/../lib/Connection.php");
require_once(__DIR__."/../lib/EmpresaDAO.php");
require_once(__DIR__."/../lib/ItemDAO.php");
require_once(__DIR__."/../lib/ParamDAO.php");
require_once(__DIR__."/../lib/PerfilDAO.php");
require_once(__DIR__."/../lib/PessoaDAO.php");
require_once(__DIR__."/../lib/ProgramaDAO.php");
require_once(__DIR__."/../lib/UsuarioDAO.php");


class Service {

   private $connection = null;
   private $daos = array();
   private $rootPath = "";

   public function __construct() {
      try {
         $this->connection = new Connection();
      } catch(Exception $e) {
         throw $e;
      }
   }

   public function __destruct() {
      if($this->daos) {
         foreach($this->daos as &$dao) {
            unset($dao);
         }
         unset($this->daos);
      }
      if(isset($this->connection)) {
         unset($this->connection);
         $this->connection = null;
      }
   }

   private function getDAO($key) {
      try {
         $dao = null;
         if(isset($this->daos[$key]))
             $dao = $this->daos[$key];
         else{
            $class = ucfirst($key) ."DAO";
            $dao = $this->daos[$key] = new $class($this->connection);
         }
         return $dao;
      } catch(Exception $e) {
         throw $e;
      }
   }

   private function getEmpresaDAO()       {return $this->getDAO("empresa");}
   private function getItemDAO()          {return $this->getDAO("item");}
   private function getParamDAO()         {return $this->getDAO("param");}
   private function getPerfilDAO()        {return $this->getDAO("perfil");}
   private function getPessoaDAO()        {return $this->getDAO("pessoa");}
   private function getProgramaDAO()      {return $this->getDAO("programa");}
   private function getUsuarioDAO()       {return $this->getDAO("usuario");}

   public function getParamQty() {
      return $this->getParamDAO()->getParamQty();
   }

   public function getParamList($search) {
      return $this->getParamDAO()->getParamList($search);
   }

   public function getParam($cod_empresa) {
      return $this->getParamDAO()->getParam($cod_empresa);
   }

   public function newDefaultParam($cod_empresa) {
      return $this->getParamDAO()->newDefaultParam($cod_empresa);
   }

   public function newParam($param) {
      return $this->getParamDAO()->newParam($param);
   }

   public function updateParam($param) {
      return $this->getParamDAO()->updateParam($param);
   }

   public function deleteParam($cod_empresa) {
      return $this->getParamDAO()->deleteParam($cod_empresa);
   }

   public function getSkin() {
      return $this->getParamDAO()->getSkin();
   }

   public function getPessoaList($search) {
      return $this->getPessoaDAO()->getPessoaList($search);
   }

   public function getPessoa($cod_pessoa) {
      return $this->getPessoaDAO()->getPessoa($cod_pessoa);
   }

   public function getPessoaById($id_federal) {
      return $this->getPessoaDAO()->getPessoaById($id_federal);
   }

   public function newPessoa($pessoa) {
      return $this->getPessoaDAO()->newPessoa($pessoa);
   }

   public function updatePessoa($pessoa) {
      return $this->getPessoaDAO()->updatePessoa($pessoa);
   }

   public function deletePessoa($cod_pessoa) {
      return $this->getPessoaDAO()->deletePessoa($cod_pessoa);
   }

   public function getAniversariantes() {
      return $this->getPessoaDAO()->getAniversariantes();
   }


   public function getEmpresaQty() {
      return $this->getEmpresaDAO()->getEmpresaQty();
   }

   public function getEmpresaList($search) {
      return $this->getEmpresaDAO()->getEmpresaList($search);
   }

   public function getEmpresaListNoParam() {
      return $this->getEmpresaDAO()->getEmpresaListNoParam();
   }

   public function getEmpresa($cod_empresa) {
      return $this->getEmpresaDAO()->getEmpresa($cod_empresa);
   }

   public function newEmpresa($empresa) {
      return $this->getEmpresaDAO()->newEmpresa($empresa);
   }

   public function updateEmpresa($empresa) {
      return $this->getEmpresaDAO()->updateEmpresa($empresa);
   }

   public function deleteEmpresa($cod_empresa) {
      return $this->getEmpresaDAO()->deleteEmpresa($cod_empresa);
   }

   public function getEmpresaMatriz() {
      return $this->getEmpresaDAO()->getEmpresaMatriz();
   }


   public function login($login, $senha) {
      return $this->getUsuarioDAO()->login($login, $senha);
   }

   public function getUsuarioQty($cod_empresa) {
      return $this->getUsuarioDAO()->getUsuarioQty($cod_empresa);
   }

   public function getUsuarioList($cod_empresa, $search) {
      return $this->getUsuarioDAO()->getUsuarioList($cod_empresa, $search);
   }

   public function getUsuarioListByFilter($filter) {
      return $this->getUsuarioDAO()->getUsuarioListByFilter($filter);
   }

   public function getUsuario($cod_empresa, $cod_usuario) {
      return $this->getUsuarioDAO()->getUsuario($cod_empresa, $cod_usuario);
   }

   public function getUsuarioByLogin($login) {
      return $this->getUsuarioDAO()->getUsuarioByLogin($login);
   }

   public function getUsuarioByPerfil($cod_empresa, $cod_perfil) {
      return $this->getUsuarioDAO()->getUsuarioByPerfil($cod_empresa, $cod_perfil);
   }

   public function newUsuario($usuario) {
      return $this->getUsuarioDAO()->newUsuario($usuario);
   }

   public function updateUsuario($usuario) {
      return $this->getUsuarioDAO()->updateUsuario($usuario);
   }

   public function deleteUsuario($cod_empresa, $login) {
      return $this->getUsuarioDAO()->deleteUsuario($cod_empresa, $login);
   }

   public function getURLFoto($param, $foto) {
      return $this->getUsuarioDAO()->getURLFoto($param, $foto);
   }

   public function isMultiempresa($login) {
      return $this->getUsuarioDAO()->isMultiempresa($login);
   }

 
   public function getMenuByUsuario($cod_empresa, $login) {
      return $this->getUsuarioDAO()->getMenuByUsuario($cod_empresa, $login);
   }

   public function getProgramaListByUsuario($cod_empresa, $login) {
      return $this->getUsuarioDAO()->getProgramaListByUsuario($cod_empresa, $login);
   }

   public function setProgramaListByUsuario($cod_empresa, $login, $lista_progr) {
      if($this->getUsuarioDAO()->deleteProgramaListByUsuario($cod_empresa, $login)) {
         return $this->getUsuarioDAO()->setProgramaListByUsuario($cod_empresa, $login, $lista_progr);
      }
      return false;
   }

   public function deleteProgramaListByUsuario($cod_empresa, $login) {
      return $this->getUsuarioDAO()->deleteProgramaListByUsuario($cod_empresa, $login);
   }

   
   public function getNomePerfil($cod_perfil) {
      return $this->getUsuarioDAO()->getNomePerfil($cod_perfil);
   }

   public function setPerfilListByUsuario($cod_empresa, $login, $lista_perfil) {
      return $this->getUsuarioDAO()->setPerfilListByUsuario($cod_empresa, $login, $lista_perfil);
   }

   public function deletePerfilListByUsuario($cod_empresa, $login) {
      return $this->getUsuarioDAO()->deletePerfilListByUsuario($cod_empresa, $login);
   }

   
   public function getItemQty() {
      return $this->getItemDAO()->getItemQty();
   }

   public function getItemList($search) {
      return $this->getItemDAO()->getItemList($search);
   }

   public function getItemListByFilter($filter) {
      return $this->getItemDAO()->getItemListByFilter($filter);
   }

   public function getItem($cod_item) {
      return $this->getItemDAO()->getItem($cod_item);
   }

   public function getItemByName($nome_item) {
      return $this->getItemDAO()->getItemByName($nome_item);
   }

   public function newItem($item) {
      return $this->getItemDAO()->newItem($item);
   }

   public function updateItem($item) {
      return $this->getItemDAO()->updateItem($item);
   }

   public function deleteItem($cod_item) {
      return $this->getItemDAO()->deleteItem($cod_item);
   }

   public function getDescricaoTipoItem($tipo) {
      return $this->getItemDAO()->getDescricaoTipoItem($tipo);
   }

   
   public function getVariacaoList($cod_item) {
      return $this->getItemDAO()->getVariacaoList($cod_item);
   }

   public function newVariacaoList($cod_item, $lista_var) {
      return $this->getItemDAO()->newVariacaoList($cod_item, $lista_var);
   }

   public function deleteVariacaoList($cod_item) {
      return $this->getItemDAO()->deleteVariacaoList($cod_item);
   }

   
   public function getItemUnidadeList() {
      return $this->getItemDAO()->getItemUnidadeList();
   }

   
   public function getPerfilQty() {
      return $this->getPerfilDAO()->getPerfilQty();
   }

   public function getPerfilList($search) {
      return $this->getPerfilDAO()->getPerfilList($search);
   }

   public function getPerfilListByFilter($filter) {
      return $this->getPerfilDAO()->getPerfilListByFilter($filter);
   }

   public function getPerfil($cod_perfil) {
      return $this->getPerfilDAO()->getPerfil($cod_perfil);
   }

   public function getPerfilByName($nome_perfil) {
      return $this->getPerfilDAO()->getPerfilByName($nome_perfil);
   }

   public function newPerfil($perfil) {
      return $this->getPerfilDAO()->newPerfil($perfil);
   }

   public function updatePerfil($perfil) {
      return $this->getPerfilDAO()->updatePerfil($perfil);
   }

   public function deletePerfil($cod_perfil) {
      return $this->getPerfilDAO()->deletePerfil($cod_perfil);
   }

  
   public function getProgramaQty() {
      return $this->getProgramaDAO()->getProgramaQty();
   }

   public function getProgramaList($search) {
      return $this->getProgramaDAO()->getProgramaList($search);
   }

   public function getPrograma($cod_programa) {
      return $this->getProgramaDAO()->getPrograma($cod_programa);
   }

   public function getProgramaByName($nome_programa) {
      return $this->getProgramaDAO()->getProgramaByName($nome_programa);
   }

   public function getProgramaBySigla($sigla) {
      return $this->getProgramaDAO()->getProgramaBySigla($sigla);
   }

   public function newPrograma($programa) {
      return $this->getProgramaDAO()->newPrograma($programa);
   }

   public function updatePrograma($programa) {
      return $this->getProgramaDAO()->updatePrograma($programa);
   }

   public function deletePrograma($cod_programa) {
      return $this->getProgramaDAO()->deletePrograma($cod_programa);
   }

   public function getDescricaoTipoPrograma($ind_tipo) {
      return $this->getProgramaDAO()->getDescricaoTipoPrograma($ind_tipo);
   }




   public function writeFile($fileName, $content) {
      $fp = fopen($this->rootPath.$fileName.".log", "w");
      fwrite($fp, $content);
      fclose($fp);
   }

}
