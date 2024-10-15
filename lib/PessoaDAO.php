<?php
// Inclui o arquivo da classe GenericDAO, garantindo que só será incluído uma vez
require_once(__DIR__."/../lib/GenericDAO.php");

// Define a classe PessoaDAO que herda de GenericDAO
class PessoaDAO extends GenericDAO {

   // Construtor da classe que recebe a conexão como parâmetro
   public function __construct($conn){
      // Chama o construtor da classe pai para inicializar a conexão
      parent::__construct($conn);
   }

   // Método que retorna uma lista de pessoas com base no tipo de pessoa e um termo de busca (nome)
   public function getPessoaList($tipo_pessoa, $search){
      // Inicializa a cláusula WHERE com uma condição sempre verdadeira
      $where = " WHERE 1=1 ";
      // Se o tipo de pessoa for fornecido, adiciona um filtro no tipo de pessoa
      if($tipo_pessoa != "") {
         $where .= " AND tipo_pessoa = '".$tipo_pessoa."' ";
      }
      // Se houver um termo de busca, adiciona um filtro no nome da pessoa
      if($search != "") {
         $where .= " AND nome_pessoa LIKE '%".addslashes($search)."%' ";
      }
      // SQL para selecionar todas as pessoas com as condições WHERE aplicadas
      $sql = "SELECT * ".
             "  FROM pessoa ".
             $where .
             " ORDER BY nome_pessoa"; // Ordena os resultados pelo nome da pessoa
      // Executa a query e retorna todos os resultados
      $lista = $this->connection->allResults($sql, "");
      return $lista;
   }

   // Método que retorna os dados de uma pessoa específica, baseada no código da pessoa
   public function getPessoa($cod_pessoa){
      // SQL para selecionar uma pessoa com base no código
      $sql = "SELECT * FROM pessoa WHERE cod_pessoa = '#cod_pessoa#' ";
      // Define o código da pessoa como parâmetro
      $parameters["cod_pessoa"] = $cod_pessoa;
      // Executa a query e obtém o resultado único
      $pessoa = $this->connection->singleResult($sql, $parameters);
      return $pessoa;
   }

   // Método que retorna os dados de uma pessoa específica, baseada no ID federal (CPF/CNPJ)
   public function getPessoaById($id_federal){
      // SQL para selecionar uma pessoa com base no ID federal
      $sql = "SELECT * FROM pessoa ".
             " WHERE id_federal = '#id_federal#' ";
      // Define o ID federal como parâmetro
      $parameters["id_federal"] = $id_federal;
      // Executa a query e obtém o resultado único
      $pessoa = $this->connection->singleResult($sql, $parameters);
      return $pessoa;
   }

   // Método privado que prepara os dados de uma pessoa para inserção ou atualização no banco
   private function setPessoa($pessoa) {
      // Retorna um array associativo com os valores da pessoa formatados
      return array(
         "cod_pessoa"       => $pessoa->cod_pessoa,
         "tipo_pessoa"      => $pessoa->tipo_pessoa,
         "nome_pessoa"      => mb_strtoupper(addslashes($pessoa->nome_pessoa)),
         "nome_fantasia"    => (!empty($pessoa->nome_fantasia)) ? mb_strtoupper(addslashes($pessoa->nome_fantasia)) : '',
         "cep"              => (!empty($pessoa->cep)) ? $pessoa->cep : '',
         "endereco"         => (!empty($pessoa->endereco)) ? mb_strtoupper(addslashes($pessoa->endereco)) : '',
         "numero"           => (!empty($pessoa->numero)) ? $pessoa->numero : '',
         "complemento"      => (!empty($pessoa->complemento)) ? mb_strtoupper(addslashes($pessoa->complemento)) : '',
         "bairro"           => (!empty($pessoa->bairro)) ? mb_strtoupper(addslashes($pessoa->bairro)) : '',
         "cidade"           => (!empty($pessoa->cidade)) ? mb_strtoupper(addslashes($pessoa->cidade)) : '',
         "uf"               => (!empty($pessoa->uf)) ? mb_strtoupper(addslashes($pessoa->uf)) : '',
         "fone"             => (!empty($pessoa->fone)) ? $pessoa->fone : '',
         "celular"          => (!empty($pessoa->celular)) ? $pessoa->celular : '',
         "dt_nasc"          => (!empty($pessoa->dt_nasc)) ? $pessoa->dt_nasc : 'NULL',
         "rg"               => (!empty($pessoa->rg)) ? $pessoa->rg : '',
         "id_federal"       => removeMask($pessoa->id_federal),
         "ie"               => (!empty($pessoa->ie)) ? $pessoa->ie : '',
         "im"               => (!empty($pessoa->im)) ? $pessoa->im : '',
         "dt_cadastro"      => $pessoa->dt_cadastro,
         "obs"              => (!empty($pessoa->obs)) ? $pessoa->obs : '',
         "profissao"        => (!empty($pessoa->profissao)) ? mb_strtoupper(addslashes($pessoa->profissao)) : '',
         "site"             => (!empty($pessoa->site)) ? $pessoa->site : '',
         "email"            => (!empty($pessoa->email)) ? $pessoa->email : '',
         "estado_civil"     => (!empty($pessoa->estado_civil)) ? intval($pessoa->estado_civil) : '',
         "nome_contato"     => (!empty($pessoa->nome_contato)) ? mb_strtoupper(addslashes($pessoa->nome_contato)) : '',
         "email_contato"    => (!empty($pessoa->email_contato)) ? $pessoa->email_contato : '',
         "obs_contato"      => (!empty($pessoa->obs_contato)) ? $pessoa->obs_contato : '',
         "sexo"             => (!empty($pessoa->sexo)) ? $pessoa->sexo : ''
      );
   }

   // Método que insere uma nova pessoa no banco de dados
   public function newPessoa($pessoa){
      // SQL para inserir uma nova pessoa na tabela 'pessoa'
      $sql = "INSERT INTO pessoa (tipo_pessoa, nome_pessoa, nome_fantasia, cep, endereco, numero, complemento, bairro, cidade, uf, celular, fone, dt_nasc, rg, id_federal, ie, im, dt_cadastro, obs, profissao, site, email, estado_civil, nome_contato, email_contato, obs_contato, sexo) ".
             "VALUES ('#tipo_pessoa#', '#nome_pessoa#', '#nome_fantasia#', '#cep#', '#endereco#', '#numero#', '#complemento#', '#bairro#', '#cidade#', '#uf#', '#celular#', '#fone#', #dt_nasc#, '#rg#', '#id_federal#', '#ie#', '#im#', '#dt_cadastro#', '#obs#', '#profissao#', '#site#', '#email#', '#estado_civil#', '#nome_contato#', '#email_contato#', '#obs_contato#', '#sexo#') ";
      // Define os parâmetros com os valores da pessoa
      $parameters = $this->setPessoa($pessoa);
      // Executa a query para inserir a nova pessoa
      $result = $this->connection->query($sql, $parameters, false);
      // Retorna o código da pessoa inserida
      $cod_pessoa = $this->connection->getLastGeneratedId();
      return $cod_pessoa;
   }

   // Método que atualiza os dados de uma pessoa no banco de dados
   public function updatePessoa($pessoa){
      // SQL para atualizar os dados de uma pessoa na tabela 'pessoa'
      $sql = "UPDATE pessoa ".
               " SET nome_pessoa   = '#nome_pessoa#', ".
                   " nome_fantasia = '#nome_fantasia#', ".
                   " cep           = '#cep#', ".
                   " endereco      = '#endereco#', ".
                   " numero        = '#numero#', ".
                   " complemento   = '#complemento#', ".
                   " bairro        = '#bairro#', ".
                   " cidade        = '#cidade#', ".
                   " uf            = '#uf#', ".
                   " celular       = '#celular#', ".
                   " fone          = '#fone#', ".
                   " dt_nasc       = #dt_nasc#, ".
                   " rg            = '#rg#', ".
                   " id_federal    = '#id_federal#', ".
                   " ie            = '#ie#', ".
                   " im            = '#im#', ".
                   " obs           = '#obs#', ".
                   " profissao     = '#profissao#', ".
                   " site          = '#site#', ".
                   " email         = '#email#', ".
                   " estado_civil  = '#estado_civil#', ".
                   " nome_contato  = '#nome_contato#', ".
                   " email_contato = '#email_contato#', ".
                   " obs_contato   = '#obs_contato#', ".
                   " sexo          = '#sexo#' ".
             " WHERE cod_pessoa = '#cod_pessoa#' ";
      // Define os parâmetros com os valores atualizados da pessoa
      $parameters = $this->setPessoa($pessoa);
      // Executa a query para atualizar a pessoa
      $result = $this->connection->query($sql, $parameters, false);
      return $result;
   }

   // Método que remove uma pessoa do banco de dados
   public function deletePessoa($cod_pessoa){
      // SQL para deletar uma pessoa com base no código
      $sql = "DELETE FROM pessoa WHERE cod_pessoa = '#cod_pessoa#' ";
      // Define o código da pessoa como parâmetro
      $parameters["cod_pessoa"] = $cod_pessoa;
      // Executa a query para deletar a pessoa
      return $this->connection->execute($sql, $parameters);
   }

   // Método privado para atualizar um campo específico de uma pessoa
   private function updateFieldPessoa($cod_pessoa, $campo, $valor) {
      // SQL para atualizar um campo específico
      $sql = "UPDATE pessoa ".
             "   SET #campo# = '#valor#' ".
             " WHERE cod_pessoa = '#cod_pessoa#' ";
      // Define os parâmetros
      $parameters = array(
            "cod_pessoa" => $cod_pessoa,
            "campo"      => $campo,
            "valor"      => $valor
      );
      // Executa a query para atualizar o campo
      $result = $this->connection->query($sql, $parameters, false);
      return $result;
   }

   // Método que retorna a lista de aniversariantes do dia
   public function getAniversariantes() {
      // SQL para selecionar os aniversariantes do dia
      $sql = "SELECT cod_pessoa, nome_pessoa, dt_nasc ".
             "  FROM pessoa ".
             " WHERE MONTH(dt_nasc) = MONTH(NOW()) ".
               " AND DAY(dt_nasc) = DAY(NOW()) ";
      // Executa a query e retorna os aniversariantes como um array
      $list = $this->connection->allResults($sql, "", "array");
      return $list;
   }

}
