<?php
require_once(__DIR__."/../lib/GenericDAO.php");
require_once(__DIR__."/../inc/functions.php");

class UsuarioDAO extends GenericDAO {

   public function __construct($conn){
      parent::__construct($conn);
   }

   public function login($login, $senha){
      $sql = "SELECT u.* FROM usuario u ".
             " WHERE u.login = '#login#' ".
             "   AND u.senha = '#senha#'";
      $parameters["login"] = $login;
      $parameters["senha"] = base64_encode(sha1($senha));
      $usuario = $this->connection->singleResult($sql, $parameters);
      if (is_object($usuario)) {
         unset($usuario->senha);

      }
      return $usuario;
   }

   public function getUsuarioQty($cod_empresa){
      $sql = "SELECT COUNT(*) AS cont FROM usuario WHERE cod_perfil > -1 ";
      if($cod_empresa != "0") $sql .= "AND cod_empresa = '#cod_empresa#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      
      $rs = $this->connection->singleResult($sql, $parameters);
      return $rs->cont;
   }

   public function getUsuarioList($cod_empresa, $search){
      $where = " WHERE u.cod_perfil > 0 ";
      if($cod_empresa != "0") $where .= " AND u.cod_empresa = '#cod_empresa#' ";
      if($search != "" && $search != null) {
         $where .= " AND (u.nome_usuario LIKE '%".addslashes($search)."%' ".
                   "  OR  u.login LIKE '%".$search."%') ";
      }
      $sql = "SELECT u.* ".
             "  FROM usuario u ".
             $where.
             " ORDER BY u.nome_usuario ";
      $parameters["cod_empresa"] = $cod_empresa;
      
      $list = $this->connection->allResults($sql, $parameters);
      return $list;
   }

   public function getUsuarioListByFilter($filter){
      $where = " WHERE TRUE ";
      if($filter->cod_empresa != "0") $where .= " AND u.cod_empresa = '".$filter->cod_empresa."' ";
      if($filter->nome        != "")  $where .= " AND u.nome_usuario LIKE '%".addslashes($filter->nome)."%' ";
      if($filter->login       != "")  $where .= " AND u.login LIKE '%".addslashes($filter->login)."%' ";
      $sql = "SELECT u.cod_empresa, u.nome_usuario, u.login, u.cod_usuario, u.cod_perfil ".
             "  FROM usuario u ".
             $where.
             " ORDER BY u.nome_usuario ";
      
      $list = $this->connection->allResults($sql);
      return $list;
   }

   public function getUsuario($cod_empresa, $cod_usuario){
      if (empty($cod_usuario)) return null;
      $sql = "SELECT u.* FROM usuario u ".
             " INNER JOIN empresa e ON e.cod_empresa = u.cod_empresa ".
             " WHERE u.cod_empresa = '#cod_empresa#' ".
             "   AND u.cod_usuario = '#cod_usuario#' ";
      $parameters = [
         "cod_empresa" => $cod_empresa,
         "cod_usuario" => $cod_usuario,
      ];
      
      $usuario = $this->connection->singleResult($sql, $parameters);
      if($usuario) $usuario->senha = null;
      return $usuario;
   }

   public function getUsuarioByLogin($login){
      if (empty($login)) return null;
      $sql = "SELECT * FROM usuario ".
             " WHERE login = '#login#' ";
      $parameters["login"] = $login;
      $usuario = $this->connection->singleResult($sql, $parameters);
      if($usuario) $usuario->senha = null;
      return $usuario;
   }

   public function getUsuarioByPerfil($cod_empresa, $cod_perfil){
      $sql = "SELECT login,nome_usuario FROM usuario ".
             " WHERE cod_empresa = '#cod_empresa#' ".
             "   AND cod_perfil  = '#cod_perfil#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      $parameters["cod_perfil"] = $cod_perfil;
      
      $lista = $this->connection->allResults($sql, $parameters, 'array');
      return $lista;
   }

   private function setUsuario($usuario) {
      return array(
         "cod_empresa"  => $usuario->cod_empresa,
         "login"        => $usuario->login,
         "cod_usuario"  => intval($usuario->cod_usuario),
         "nome_usuario" => addslashes(mb_strtoupper($usuario->nome_usuario)),
         "senha"        => base64_encode(sha1($usuario->senha)),
         "cod_perfil"   => $usuario->cod_perfil,
         "foto"         => $usuario->foto,
         "dt_cadastro"  => date("Y-m-d H:i:s")
      );
   }

   public function newUsuario($usuario){
      $sql = "INSERT INTO usuario (cod_empresa, login, cod_usuario, nome_usuario, senha, cod_perfil, dt_cadastro) ".
             "VALUES ('#cod_empresa#', '#login#', '#cod_usuario#', '#nome_usuario#', '#senha#', '#cod_perfil#', '#dt_cadastro#') ";
      $parameters = $this->setUsuario($usuario);
      $result = $this->connection->query($sql, $parameters, false);
      if(isset($result)) return $this->connection->getLastGeneratedId();
      return 0;
   }

   public function updateUsuario($usuario){
      $sql = "UPDATE usuario ".
             "   SET nome_usuario = '#nome_usuario#',";
      if(!empty($usuario->cod_usuario))   $sql .= " cod_usuario = '#cod_usuario#',";
      if(!empty($usuario->senha))         $sql .= " senha = '#senha#',";
      if(!empty($usuario->cod_perfil))    $sql .= " cod_perfil = '#cod_perfil#',";
      if(!empty($usuario->foto))          $sql .= " foto = '#foto#',";
      $sql = trim($sql, ',');
      $sql .= " WHERE cod_empresa = '#cod_empresa#' ".
              "   AND login = '#login#' ";
      $parameters = $this->setUsuario($usuario);
      
      return $this->connection->query($sql, $parameters, false);
   }

   public function deleteUsuario($cod_empresa, $login) {
      $sql = "DELETE FROM usuario ".
             " WHERE cod_empresa = '#cod_empresa#' ".
             "   AND login = '#login#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      $parameters["login"] = $login;
      return $this->connection->execute($sql, $parameters);
   }

   public function getURLFoto($param, $foto) {
      $url_foto = $param->raiz.$param->upload.'/foto/avatar.gif';
      $file_foto = realpath(fixPath(dirname(__DIR__).'/'.$param->upload.'/foto/'.$foto));
      if(file_exists($file_foto) && !is_dir($file_foto)) {
         $url_foto = $param->raiz.$param->upload.'/foto/'.$foto;
      }
      return fixPath($url_foto);
   }


   public function getMenuByUsuario($cod_empresa, $login) {
      $sql = "SELECT p.* ".
             "  FROM usuario_progr up ".
             " INNER JOIN programa p ON p.cod_programa = up.cod_programa AND p.ind_ativo = 1".
             " WHERE up.cod_empresa = '#cod_empresa#' ".
             "   AND up.login = '#login#' ".
             " ORDER BY p.ind_tipo, p.ordem";
      $parameters["cod_empresa"] = $cod_empresa;
      $parameters["login"]       = $login;
      
      $list = $this->connection->allResults($sql, $parameters);
      return $list;
   }

   public function getProgramaListByUsuario($cod_empresa, $login) {
      $sql = "SELECT cod_programa ".
             "  FROM usuario_progr ".
             " WHERE cod_empresa = '#cod_empresa#' ".
             "   AND login = '#login#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      $parameters["login"]       = $login;
      $list = $this->connection->allResults($sql, $parameters);
      $ar_progr = array();
      foreach($list as $progr) {
         array_push($ar_progr, $progr->cod_programa);
      }
      return $ar_progr;
   }

   public function setProgramaListByUsuario($cod_empresa, $login, $lista_progr) {
      $sql = "INSERT INTO usuario_progr (cod_empresa, login, cod_programa) VALUES ";
      $delim = '';
      foreach($lista_progr as $progr) {
         $sql .= $delim."('$cod_empresa', '$login', '$progr')";
         $delim = ',';
      }
      $parameters = null;
      
      $this->connection->execute($sql, $parameters);
   }

   public function deleteProgramaListByUsuario($cod_empresa, $login) {
      $sql = "DELETE FROM usuario_progr ".
             " WHERE cod_empresa = '#cod_empresa#' ".
             "   AND login = '#login#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      $parameters["login"] = $login;
      return $this->connection->execute($sql, $parameters);
   }


   public function getNomePerfil($cod_perfil){
      $descricao = "";
      switch($cod_perfil){
         case '-1': return "Root";
         case '1':  return "Administrador";
         case '2':  return "Financeiro";
         case '3':  return "Atendente";
         case '4':  return "Administrativo";
         case '5':  return "Producao";
      }
   }

   public function setPerfilListByUsuario($cod_empresa, $login, $lista_perfil) {
      $sql = "INSERT INTO perfil_usuario (cod_empresa, login, cod_perfil) VALUES ";
      foreach($lista_perfil as $perfil) {
         $sql .= "('$cod_empresa', '$login', '$perfil'),";
      }
      $sql = rtrim($sql, ',');
      $parameters = null;
      $this->connection->query($sql, $parameters, false);
   }

   public function deletePerfilListByUsuario($cod_empresa, $login) {
      $sql = "DELETE FROM perfil_usuario ".
             " WHERE cod_empresa = '#cod_empresa#' ".
             "   AND login = '#login#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      $parameters["login"] = $login;
      return $this->connection->execute($sql, $parameters);
   }

}
