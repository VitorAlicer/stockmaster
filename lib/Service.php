<?php
// Inclui arquivos necessários para a inicialização e conexões com o banco de dados
require_once(__DIR__."/../init.php");
require_once(__DIR__."/../lib/Connection.php");
require_once(__DIR__."/../lib/EmpresaDAO.php");
require_once(__DIR__."/../lib/ItemDAO.php");
require_once(__DIR__."/../lib/ParamDAO.php");
require_once(__DIR__."/../lib/PerfilDAO.php");
require_once(__DIR__."/../lib/PessoaDAO.php");
require_once(__DIR__."/../lib/ProgramaDAO.php");
require_once(__DIR__."/../lib/UsuarioDAO.php");

// Classe principal do serviço
class Service {

   private $connection = null; // Conexão com o banco de dados
   private $daos = array(); // Armazena instâncias de DAOs
   private $rootPath = ""; // Caminho raiz para arquivos de log

   // Construtor da classe
   public function __construct() {
      try {
         $this->connection = new Connection(); // Inicializa a conexão com o banco de dados
      } catch(Exception $e) {
         throw $e; // Lança exceção se houver erro na conexão
      }
   }

   // Destrutor da classe
   public function __destruct() {
      // Limpa os DAOs se existirem
      if($this->daos) {
         foreach($this->daos as &$dao) {
            unset($dao); // Desfaz a instância do DAO
         }
         unset($this->daos); // Remove a referência ao array de DAOs
      }
      // Limpa a conexão com o banco de dados
      if(isset($this->connection)) {
         unset($this->connection); // Desfaz a instância da conexão
         $this->connection = null; // Define como null
      }
   }

   // Método privado para obter uma instância de DAO
   private function getDAO($key) {
      try {
         $dao = null; // Inicializa a variável DAO
         // Verifica se o DAO já está instanciado
         if(isset($this->daos[$key]))
             $dao = $this->daos[$key]; // Usa a instância existente
         else{
            // Se não existir, cria uma nova instância do DAO
            $class = ucfirst($key) ."DAO"; // Constrói o nome da classe DAO
            $dao = $this->daos[$key] = new $class($this->connection); // Instancia e armazena no array
         }
         return $dao; // Retorna a instância do DAO
      } catch(Exception $e) {
         throw $e; // Lança exceção se houver erro
      }
   }

   // Métodos privados para obter DAOs específicos
   private function getEmpresaDAO()       {return $this->getDAO("empresa");}
   private function getItemDAO()          {return $this->getDAO("item");}
   private function getParamDAO()         {return $this->getDAO("param");}
   private function getPerfilDAO()        {return $this->getDAO("perfil");}
   private function getPessoaDAO()        {return $this->getDAO("pessoa");}
   private function getProgramaDAO()      {return $this->getDAO("programa");}
   private function getUsuarioDAO()       {return $this->getDAO("usuario");}

   // Métodos para manipulação de parâmetros
   public function getParamQty() {
      return $this->getParamDAO()->getParamQty(); // Retorna a quantidade de parâmetros
   }

   public function getParamList($search) {
      return $this->getParamDAO()->getParamList($search); // Retorna a lista de parâmetros com base na busca
   }

   public function getParam($cod_empresa) {
      return $this->getParamDAO()->getParam($cod_empresa); // Retorna um parâmetro específico
   }

   public function newDefaultParam($cod_empresa) {
      return $this->getParamDAO()->newDefaultParam($cod_empresa); // Cria um novo parâmetro padrão
   }

   public function newParam($param) {
      return $this->getParamDAO()->newParam($param); // Cria um novo parâmetro
   }

   public function updateParam($param) {
      return $this->getParamDAO()->updateParam($param); // Atualiza um parâmetro existente
   }

   public function deleteParam($cod_empresa) {
      return $this->getParamDAO()->deleteParam($cod_empresa); // Exclui um parâmetro
   }

   public function getSkin() {
      return $this->getParamDAO()->getSkin(); // Retorna a skin configurada
   }

   // Métodos para manipulação de pessoas
   public function getPessoaList($search) {
      return $this->getPessoaDAO()->getPessoaList($search); // Retorna a lista de pessoas com base na busca
   }

   public function getPessoa($cod_pessoa) {
      return $this->getPessoaDAO()->getPessoa($cod_pessoa); // Retorna uma pessoa específica
   }

   public function getPessoaById($id_federal) {
      return $this->getPessoaDAO()->getPessoaById($id_federal); // Retorna uma pessoa pelo ID federal
   }

   public function newPessoa($pessoa) {
      return $this->getPessoaDAO()->newPessoa($pessoa); // Cria uma nova pessoa
   }

   public function updatePessoa($pessoa) {
      return $this->getPessoaDAO()->updatePessoa($pessoa); // Atualiza uma pessoa existente
   }

   public function deletePessoa($cod_pessoa) {
      return $this->getPessoaDAO()->deletePessoa($cod_pessoa); // Exclui uma pessoa
   }

   public function getAniversariantes() {
      return $this->getPessoaDAO()->getAniversariantes(); // Retorna uma lista de aniversariantes
   }

   // Métodos para manipulação de empresas
   public function getEmpresaQty() {
      return $this->getEmpresaDAO()->getEmpresaQty(); // Retorna a quantidade de empresas
   }

   public function getEmpresaList($search) {
      return $this->getEmpresaDAO()->getEmpresaList($search); // Retorna a lista de empresas com base na busca
   }

   public function getEmpresaListNoParam() {
      return $this->getEmpresaDAO()->getEmpresaListNoParam(); // Retorna a lista de empresas sem parâmetros
   }

   public function getEmpresa($cod_empresa) {
      return $this->getEmpresaDAO()->getEmpresa($cod_empresa); // Retorna uma empresa específica
   }

   public function newEmpresa($empresa) {
      return $this->getEmpresaDAO()->newEmpresa($empresa); // Cria uma nova empresa
   }

   public function updateEmpresa($empresa) {
      return $this->getEmpresaDAO()->updateEmpresa($empresa); // Atualiza uma empresa existente
   }

   public function deleteEmpresa($cod_empresa) {
      return $this->getEmpresaDAO()->deleteEmpresa($cod_empresa); // Exclui uma empresa
   }

   public function getEmpresaMatriz() {
      return $this->getEmpresaDAO()->getEmpresaMatriz(); // Retorna a empresa matriz
   }

   // Métodos para manipulação de usuários
   public function login($login, $senha) {
      return $this->getUsuarioDAO()->login($login, $senha); // Realiza login de um usuário
   }

   public function getUsuarioQty($cod_empresa) {
      return $this->getUsuarioDAO()->getUsuarioQty($cod_empresa); // Retorna a quantidade de usuários de uma empresa
   }

   public function getUsuarioList($cod_empresa, $search) {
      return $this->getUsuarioDAO()->getUsuarioList($cod_empresa, $search); // Retorna a lista de usuários com base na busca
   }

   public function getUsuarioListByFilter($filter) {
      return $this->getUsuarioDAO()->getUsuarioListByFilter($filter); // Retorna a lista de usuários com base em um filtro
   }

   public function getUsuario($cod_empresa, $cod_usuario) {
      return $this->getUsuarioDAO()->getUsuario($cod_empresa, $cod_usuario); // Retorna um usuário específico
   }

   public function getUsuarioByLogin($login) {
      return $this->getUsuarioDAO()->getUsuarioByLogin($login); // Retorna um usuário pelo login
   }

   public function getUsuarioByPerfil($cod_empresa, $cod_perfil) {
      return $this->getUsuarioDAO()->getUsuarioByPerfil($cod_empresa, $cod_perfil); // Retorna usuários de um perfil específico
   }

   public function newUsuario($usuario) {
      return $this->getUsuarioDAO()->newUsuario($usuario); // Cria um novo usuário
   }

   public function updateUsuario($usuario) {
      return $this->getUsuarioDAO()->updateUsuario($usuario); // Atualiza um usuário existente
   }

   public function deleteUsuario($cod_empresa, $login) {
      return $this->getUsuarioDAO()->deleteUsuario($cod_empresa, $login); // Exclui um usuário
   }

   public function getURLFoto($param, $foto) {
      return $this->getUsuarioDAO()->getURLFoto($param, $foto); // Retorna a URL da foto do usuário
   }

   public function isMultiempresa($login) {
      return $this->getUsuarioDAO()->isMultiempresa($login); // Verifica se o usuário é multiempresa
   }

   // Métodos para manipulação de menus e programas de usuários
   public function getMenuByUsuario($cod_empresa, $login) {
      return $this->getUsuarioDAO()->getMenuByUsuario($cod_empresa, $login); // Retorna o menu de um usuário
   }

   public function getProgramaListByUsuario($cod_empresa, $login) {
      return $this->getUsuarioDAO()->getProgramaListByUsuario($cod_empresa, $login); // Retorna a lista de programas de um usuário
   }

   public function setProgramaListByUsuario($cod_empresa, $login, $lista_progr) {
      // Atualiza a lista de programas de um usuário
      if($this->getUsuarioDAO()->deleteProgramaListByUsuario($cod_empresa, $login)) {
         return $this->getUsuarioDAO()->setProgramaListByUsuario($cod_empresa, $login, $lista_progr);
      }
      return false; // Retorna false se a exclusão falhar
   }

   public function deleteProgramaListByUsuario($cod_empresa, $login) {
      return $this->getUsuarioDAO()->deleteProgramaListByUsuario($cod_empresa, $login); // Exclui a lista de programas de um usuário
   }

   // Métodos para manipulação de perfis
   public function getNomePerfil($cod_perfil) {
      return $this->getUsuarioDAO()->getNomePerfil($cod_perfil); // Retorna o nome de um perfil específico
   }

   public function setPerfilListByUsuario($cod_empresa, $login, $lista_perfil) {
      return $this->getUsuarioDAO()->setPerfilListByUsuario($cod_empresa, $login, $lista_perfil); // Atualiza a lista de perfis de um usuário
   }

   public function deletePerfilListByUsuario($cod_empresa, $login) {
      return $this->getUsuarioDAO()->deletePerfilListByUsuario($cod_empresa, $login); // Exclui a lista de perfis de um usuário
   }

   // Métodos para manipulação de itens
   public function getItemQty() {
      return $this->getItemDAO()->getItemQty(); // Retorna a quantidade de itens
   }

   public function getItemList($search) {
      return $this->getItemDAO()->getItemList($search); // Retorna a lista de itens com base na busca
   }

   public function getItemListByFilter($filter) {
      return $this->getItemDAO()->getItemListByFilter($filter); // Retorna a lista de itens com base em um filtro
   }

   public function getItem($cod_item) {
      return $this->getItemDAO()->getItem($cod_item); // Retorna um item específico
   }

   public function getItemByName($nome_item) {
      return $this->getItemDAO()->getItemByName($nome_item); // Retorna um item pelo nome
   }

   public function newItem($item) {
      return $this->getItemDAO()->newItem($item); // Cria um novo item
   }

   public function updateItem($item) {
      return $this->getItemDAO()->updateItem($item); // Atualiza um item existente
   }

   public function deleteItem($cod_item) {
      return $this->getItemDAO()->deleteItem($cod_item); // Exclui um item
   }

   public function getDescricaoTipoItem($tipo) {
      return $this->getItemDAO()->getDescricaoTipoItem($tipo); // Retorna a descrição de um tipo de item
   }

   // Métodos para manipulação de variações de itens
   public function getVariacaoList($cod_item) {
      return $this->getItemDAO()->getVariacaoList($cod_item); // Retorna a lista de variações de um item
   }

   public function newVariacaoList($cod_item, $lista_var) {
      return $this->getItemDAO()->newVariacaoList($cod_item, $lista_var); // Cria uma nova lista de variações para um item
   }

   public function deleteVariacaoList($cod_item) {
      return $this->getItemDAO()->deleteVariacaoList($cod_item); // Exclui a lista de variações de um item
   }

   // Métodos para manipulação de unidades de itens
   public function getItemUnidadeList() {
      return $this->getItemDAO()->getItemUnidadeList(); // Retorna a lista de unidades de itens
   }

   // Métodos para manipulação de perfis
   public function getPerfilQty() {
      return $this->getPerfilDAO()->getPerfilQty(); // Retorna a quantidade de perfis
   }

   public function getPerfilList($search) {
      return $this->getPerfilDAO()->getPerfilList($search); // Retorna a lista de perfis com base na busca
   }

   public function getPerfilListByFilter($filter) {
      return $this->getPerfilDAO()->getPerfilListByFilter($filter); // Retorna a lista de perfis com base em um filtro
   }

   public function getPerfil($cod_perfil) {
      return $this->getPerfilDAO()->getPerfil($cod_perfil); // Retorna um perfil específico
   }

   public function getPerfilByName($nome_perfil) {
      return $this->getPerfilDAO()->getPerfilByName($nome_perfil); // Retorna um perfil pelo nome
   }

   public function newPerfil($perfil) {
      return $this->getPerfilDAO()->newPerfil($perfil); // Cria um novo perfil
   }

   public function updatePerfil($perfil) {
      return $this->getPerfilDAO()->updatePerfil($perfil); // Atualiza um perfil existente
   }

   public function deletePerfil($cod_perfil) {
      return $this->getPerfilDAO()->deletePerfil($cod_perfil); // Exclui um perfil
   }

   // Métodos para manipulação de programas
   public function getProgramaQty() {
      return $this->getProgramaDAO()->getProgramaQty(); // Retorna a quantidade de programas
   }

   public function getProgramaList($search) {
      return $this->getProgramaDAO()->getProgramaList($search); // Retorna a lista de programas com base na busca
   }

   public function getPrograma($cod_programa) {
      return $this->getProgramaDAO()->getPrograma($cod_programa); // Retorna um programa específico
   }

   public function getProgramaByName($nome_programa) {
      return $this->getProgramaDAO()->getProgramaByName($nome_programa); // Retorna um programa pelo nome
   }

   public function getProgramaBySigla($sigla) {
      return $this->getProgramaDAO()->getProgramaBySigla($sigla); // Retorna um programa pela sigla
   }

   public function newPrograma($programa) {
      return $this->getProgramaDAO()->newPrograma($programa); // Cria um novo programa
   }

   public function updatePrograma($programa) {
      return $this->getProgramaDAO()->updatePrograma($programa); // Atualiza um programa existente
   }

   public function deletePrograma($cod_programa) {
      return $this->getProgramaDAO()->deletePrograma($cod_programa); // Exclui um programa
   }

   public function getDescricaoTipoPrograma($ind_tipo) {
      return $this->getProgramaDAO()->getDescricaoTipoPrograma($ind_tipo); // Retorna a descrição de um tipo de programa
   }

   // Método para escrever logs em arquivos
   public function writeFile($fileName, $content) {
      // Abre o arquivo de log em modo de escrita
      $fp = fopen($this->rootPath.$fileName.".log", "w");
      fwrite($fp, $content); // Escreve o conteúdo no arquivo
      fclose($fp); // Fecha o arquivo
   }
}
