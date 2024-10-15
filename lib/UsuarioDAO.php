<?php
// Inclui as dependências necessárias
require_once(__DIR__."/../lib/GenericDAO.php"); // Inclui a classe base para operações genéricas de acesso a dados
require_once(__DIR__."/../inc/functions.php"); // Inclui funções auxiliares

// Define a classe UsuarioDAO que estende GenericDAO
class UsuarioDAO extends GenericDAO {

   // Construtor da classe que inicializa a conexão com o banco de dados
   public function __construct($conn){
      parent::__construct($conn); // Chama o construtor da classe pai
   }

   // Método para realizar o login do usuário
   public function login($login, $senha){
      // Consulta SQL para selecionar um usuário com login e senha especificados
      $sql = "SELECT u.* FROM usuario u ".
             " WHERE u.login = '#login#' ".
             "   AND u.senha = '#senha#'";
      // Define os parâmetros da consulta
      $parameters["login"] = $login;
      $parameters["senha"] = base64_encode(sha1($senha)); // Codifica a senha para comparação
      $usuario = $this->connection->singleResult($sql, $parameters); // Executa a consulta e obtém o resultado
      if (is_object($usuario)) {
         unset($usuario->senha); // Remove a senha do objeto retornado por segurança
      }
      return $usuario; // Retorna o objeto do usuário
   }

   // Método para contar a quantidade de usuários em uma empresa
   public function getUsuarioQty($cod_empresa){
      // Consulta SQL para contar usuários com perfil válido
      $sql = "SELECT COUNT(*) AS cont FROM usuario WHERE cod_perfil > -1 ";
      if($cod_empresa != "0") $sql .= "AND cod_empresa = '#cod_empresa#' "; // Filtro por empresa, se aplicável
      $parameters["cod_empresa"] = $cod_empresa;

      $rs = $this->connection->singleResult($sql, $parameters); // Executa a consulta e obtém o resultado
      return $rs->cont; // Retorna a contagem de usuários
   }

   // Método para obter a lista de usuários com base em filtros
   public function getUsuarioList($cod_empresa, $search){
      $where = " WHERE u.cod_perfil > 0 "; // Filtro para incluir apenas usuários com perfil válido
      if($cod_empresa != "0") $where .= " AND u.cod_empresa = '#cod_empresa#' "; // Filtro por empresa
      if($search != "" && $search != null) {
         // Filtro por nome de usuário ou login
         $where .= " AND (u.nome_usuario LIKE '%".addslashes($search)."%' ".
                   "  OR  u.login LIKE '%".$search."%') ";
      }
      // Consulta SQL para obter a lista de usuários
      $sql = "SELECT u.* ".
             "  FROM usuario u ".
             $where.
             " ORDER BY u.nome_usuario "; // Ordena os resultados pelo nome do usuário
      $parameters["cod_empresa"] = $cod_empresa;

      $list = $this->connection->allResults($sql, $parameters); // Executa a consulta e obtém a lista de usuários
      return $list; // Retorna a lista de usuários
   }

   // Método para obter a lista de usuários com base em um filtro específico
   public function getUsuarioListByFilter($filter){
      $where = " WHERE TRUE "; // Inicia a cláusula WHERE sempre verdadeira
      if($filter->cod_empresa != "0") $where .= " AND u.cod_empresa = '".$filter->cod_empresa."' "; // Filtro por empresa
      if($filter->nome        != "")  $where .= " AND u.nome_usuario LIKE '%".addslashes($filter->nome)."%' "; // Filtro por nome de usuário
      if($filter->login       != "")  $where .= " AND u.login LIKE '%".addslashes($filter->login)."%' "; // Filtro por login
      // Consulta SQL para obter detalhes do usuário
      $sql = "SELECT u.cod_empresa, u.nome_usuario, u.login, u.cod_usuario, u.cod_perfil ".
             "  FROM usuario u ".
             $where.
             " ORDER BY u.nome_usuario "; // Ordena os resultados pelo nome do usuário

      $list = $this->connection->allResults($sql); // Executa a consulta e obtém a lista de usuários
      return $list; // Retorna a lista de usuários
   }

   // Método para obter um usuário específico pelo código da empresa e código do usuário
   public function getUsuario($cod_empresa, $cod_usuario){
      if (empty($cod_usuario)) return null; // Retorna null se o código do usuário estiver vazio
      // Consulta SQL para obter o usuário
      $sql = "SELECT u.* FROM usuario u ".
             " INNER JOIN empresa e ON e.cod_empresa = u.cod_empresa ".
             " WHERE u.cod_empresa = '#cod_empresa#' ".
             "   AND u.cod_usuario = '#cod_usuario#' ";
      $parameters = [
         "cod_empresa" => $cod_empresa,
         "cod_usuario" => $cod_usuario,
      ];

      $usuario = $this->connection->singleResult($sql, $parameters); // Executa a consulta e obtém o resultado
      if($usuario) $usuario->senha = null; // Remove a senha do objeto retornado por segurança
      return $usuario; // Retorna o objeto do usuário
   }

   // Método para obter um usuário pelo login
   public function getUsuarioByLogin($login){
      if (empty($login)) return null; // Retorna null se o login estiver vazio
      // Consulta SQL para obter o usuário pelo login
      $sql = "SELECT * FROM usuario ".
             " WHERE login = '#login#' ";
      $parameters["login"] = $login;
      $usuario = $this->connection->singleResult($sql, $parameters); // Executa a consulta e obtém o resultado
      if($usuario) $usuario->senha = null; // Remove a senha do objeto retornado por segurança
      return $usuario; // Retorna o objeto do usuário
   }

   // Método para obter uma lista de usuários com base em um perfil específico
   public function getUsuarioByPerfil($cod_empresa, $cod_perfil){
      // Consulta SQL para obter usuários com base no código da empresa e do perfil
      $sql = "SELECT login,nome_usuario FROM usuario ".
             " WHERE cod_empresa = '#cod_empresa#' ".
             "   AND cod_perfil  = '#cod_perfil#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      $parameters["cod_perfil"] = $cod_perfil;

      $lista = $this->connection->allResults($sql, $parameters, 'array'); // Executa a consulta e obtém a lista de usuários
      return $lista; // Retorna a lista de usuários
   }

   // Método privado para configurar os dados de um usuário antes de inserir ou atualizar no banco de dados
   private function setUsuario($usuario) {
      return array(
         "cod_empresa"  => $usuario->cod_empresa, // Código da empresa
         "login"        => $usuario->login, // Login do usuário
         "cod_usuario"  => intval($usuario->cod_usuario), // Código do usuário
         "nome_usuario" => addslashes(mb_strtoupper($usuario->nome_usuario)), // Nome do usuário em maiúsculas
         "senha"        => base64_encode(sha1($usuario->senha)), // Senha codificada
         "cod_perfil"   => $usuario->cod_perfil, // Código do perfil
         "foto"         => $usuario->foto, // Foto do usuário
         "dt_cadastro"  => date("Y-m-d H:i:s") // Data de cadastro do usuário
      );
   }

   // Método para criar um novo usuário no sistema
   public function newUsuario($usuario){
      // Consulta SQL para inserir um novo usuário
      $sql = "INSERT INTO usuario (cod_empresa, login, cod_usuario, nome_usuario, senha, cod_perfil, dt_cadastro) ".
             "VALUES ('#cod_empresa#', '#login#', '#cod_usuario#', '#nome_usuario#', '#senha#', '#cod_perfil#', '#dt_cadastro#') ";
      $parameters = $this->setUsuario($usuario); // Configura os parâmetros do usuário
      $result = $this->connection->query($sql, $parameters, false); // Executa a consulta de inserção
      if(isset($result)) return $this->connection->getLastGeneratedId(); // Retorna o ID do último usuário inserido
      return 0; // Retorna 0 se a inserção falhar
   }

   // Método para atualizar os dados de um usuário existente
   public function updateUsuario($usuario){
      // Consulta SQL para atualizar os dados do usuário
      $sql = "UPDATE usuario ".
             "   SET nome_usuario = '#nome_usuario#',";
      if(!empty($usuario->cod_usuario))   $sql .= " cod_usuario = '#cod_usuario#',"; // Atualiza o código do usuário, se fornecido
      if(!empty($usuario->senha))         $sql .= " senha = '#senha#',"; // Atualiza a senha, se fornecida
      if(!empty($usuario->cod_perfil))    $sql .= " cod_perfil = '#cod_perfil#',"; // Atualiza o código do perfil, se fornecido
      if(!empty($usuario->foto))          $sql .= " foto = '#foto#',"; // Atualiza a foto, se fornecida
      $sql = trim($sql, ','); // Remove a vírgula final
      $sql .= " WHERE cod_empresa = '#cod_empresa#' ".
              "   AND login = '#login#' "; // Adiciona condição para atualização
      $parameters = $this->setUsuario($usuario); // Configura os parâmetros do usuário

      return $this->connection->query($sql, $parameters, false); // Executa a consulta de atualização
   }

   // Método para deletar um usuário do sistema
   public function deleteUsuario($cod_empresa, $login) {
      // Consulta SQL para deletar um usuário
      $sql = "DELETE FROM usuario ".
             " WHERE cod_empresa = '#cod_empresa#' ".
             "   AND login = '#login#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      $parameters["login"] = $login;
      return $this->connection->execute($sql, $parameters); // Executa a consulta de deleção
   }

   // Método para obter a URL da foto de um usuário
   public function getURLFoto($param, $foto) {
      $url_foto = $param->raiz.$param->upload.'/foto/avatar.gif'; // URL padrão da foto
      // Verifica se a foto personalizada existe
      $file_foto = realpath(fixPath(dirname(__DIR__).'/'.$param->upload.'/foto/'.$foto));
      if(file_exists($file_foto) && !is_dir($file_foto)) {
         $url_foto = $param->raiz.$param->upload.'/foto/'.$foto; // Atualiza a URL se a foto personalizada existir
      }
      return fixPath($url_foto); // Retorna a URL da foto
   }

   // Método para obter o menu de programas de um usuário
   public function getMenuByUsuario($cod_empresa, $login) {
      // Consulta SQL para obter programas associados a um usuário
      $sql = "SELECT p.* ".
             "  FROM usuario_progr up ".
             " INNER JOIN programa p ON p.cod_programa = up.cod_programa AND p.ind_ativo = 1".
             " WHERE up.cod_empresa = '#cod_empresa#' ".
             "   AND up.login = '#login#' ".
             " ORDER BY p.ind_tipo, p.ordem"; // Ordena os resultados
      $parameters["cod_empresa"] = $cod_empresa;
      $parameters["login"]       = $login;

      $list = $this->connection->allResults($sql, $parameters); // Executa a consulta e obtém a lista de programas
      return $list; // Retorna a lista de programas
   }

   // Método para obter a lista de códigos de programas de um usuário
   public function getProgramaListByUsuario($cod_empresa, $login) {
      // Consulta SQL para obter códigos de programas associados a um usuário
      $sql = "SELECT cod_programa ".
             "  FROM usuario_progr ".
             " WHERE cod_empresa = '#cod_empresa#' ".
             "   AND login = '#login#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      $parameters["login"]       = $login;
      $list = $this->connection->allResults($sql, $parameters); // Executa a consulta e obtém a lista de programas
      $ar_progr = array(); // Array para armazenar os códigos dos programas
      foreach($list as $progr) {
         array_push($ar_progr, $progr->cod_programa); // Adiciona cada código ao array
      }
      return $ar_progr; // Retorna a lista de códigos de programas
   }

   // Método para configurar a lista de programas para um usuário
   public function setProgramaListByUsuario($cod_empresa, $login, $lista_progr) {
      // Consulta SQL para inserir a lista de programas para um usuário
      $sql = "INSERT INTO usuario_progr (cod_empresa, login, cod_programa) VALUES ";
      $delim = '';
      foreach($lista_progr as $progr) {
         $sql .= $delim."('$cod_empresa', '$login', '$progr')"; // Adiciona cada programa à consulta
         $delim = ','; // Define delimitador para múltiplos valores
      }
      $parameters = null;

      $this->connection->execute($sql, $parameters); // Executa a consulta de inserção
   }

   // Método para deletar a lista de programas de um usuário
   public function deleteProgramaListByUsuario($cod_empresa, $login) {
      // Consulta SQL para deletar programas de um usuário
      $sql = "DELETE FROM usuario_progr ".
             " WHERE cod_empresa = '#cod_empresa#' ".
             "   AND login = '#login#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      $parameters["login"] = $login;
      return $this->connection->execute($sql, $parameters); // Executa a consulta de deleção
   }

   // Método para obter a descrição de um perfil com base em seu código
   public function getNomePerfil($cod_perfil){
      $descricao = ""; // Inicializa a descrição
      switch($cod_perfil){
         case '-1': return "Root"; // Caso especial para Root
         case '1':  return "Administrador"; // Caso para Administrador
         case '2':  return "Financeiro"; // Caso para Financeiro
         case '3':  return "Atendente"; // Caso para Atendente
         case '4':  return "Administrativo"; // Caso para Administrativo
         case '5':  return "Producao"; // Caso para Produção
      }
   }

   // Método para configurar a lista de perfis para um usuário
   public function setPerfilListByUsuario($cod_empresa, $login, $lista_perfil) {
      // Consulta SQL para inserir a lista de perfis para um usuário
      $sql = "INSERT INTO perfil_usuario (cod_empresa, login, cod_perfil) VALUES ";
      foreach($lista_perfil as $perfil) {
         $sql .= "('$cod_empresa', '$login', '$perfil'),"; // Adiciona cada perfil à consulta
      }
      $sql = rtrim($sql, ','); // Remove a vírgula final
      $parameters = null;
      $this->connection->query($sql, $parameters, false); // Executa a consulta de inserção
   }

   // Método para deletar a lista de perfis de um usuário
   public function deletePerfilListByUsuario($cod_empresa, $login) {
      // Consulta SQL para deletar perfis de um usuário
      $sql = "DELETE FROM perfil_usuario ".
             " WHERE cod_empresa = '#cod_empresa#' ".
             "   AND login = '#login#' ";
      $parameters["cod_empresa"] = $cod_empresa;
      $parameters["login"] = $login;
      return $this->connection->execute($sql, $parameters); // Executa a consulta de deleção
   }

}
