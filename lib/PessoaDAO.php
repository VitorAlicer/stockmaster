<?php
require_once(__DIR__."/../lib/GenericDAO.php");

class PessoaDAO extends GenericDAO {

   public function __construct($conn){
      parent::__construct($conn);
   }

   public function getPessoaList($tipo_pessoa, $search){
      $where = " WHERE 1=1 ";
      if($tipo_pessoa != "") {
         $where .= " AND tipo_pessoa = '".$tipo_pessoa."' ";
      }
      if($search != "") {
         $where .= " AND nome_pessoa LIKE '%".addslashes($search)."%' ";
      }
      $sql = "SELECT * ".
             "  FROM pessoa ".
             $where .
             " ORDER BY nome_pessoa";
      $parameters = "";
      
      $lista = $this->connection->allResults($sql, $parameters);
      return $lista;
   }

   public function getPessoa($cod_pessoa){
      $sql = "SELECT * FROM pessoa WHERE cod_pessoa = '#cod_pessoa#' ";
      $parameters["cod_pessoa"] = $cod_pessoa;
      
      $pessoa = $this->connection->singleResult($sql, $parameters);
      return $pessoa;
   }

   public function getPessoaById($id_federal){
      $sql = "SELECT * FROM pessoa ".
             " WHERE id_federal = '#id_federal#' ";
      $parameters["id_federal"] = $id_federal;
     
      $pessoa = $this->connection->singleResult($sql, $parameters);
      return $pessoa;
   }

   private function setPessoa($pessoa) {
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

   public function newPessoa($pessoa){
      $sql = "INSERT INTO pessoa (tipo_pessoa, nome_pessoa, nome_fantasia, cep, endereco, numero, complemento, bairro, cidade, uf, celular, fone, dt_nasc, rg, id_federal, ie, im, dt_cadastro, obs, profissao, site, email, estado_civil, nome_contato, email_contato, obs_contato, sexo) ".
             "VALUES ('#tipo_pessoa#', '#nome_pessoa#', '#nome_fantasia#', '#cep#', '#endereco#', '#numero#', '#complemento#', '#bairro#', '#cidade#', '#uf#', '#celular#', '#fone#', #dt_nasc#, '#rg#', '#id_federal#', '#ie#', '#im#', '#dt_cadastro#', '#obs#', '#profissao#', '#site#', '#email#', '#estado_civil#', '#nome_contato#', '#email_contato#', '#obs_contato#', '#sexo#') ";
      $parameters = $this->setPessoa($pessoa);
      
      $result = $this->connection->query($sql, $parameters, false);
      $cod_pessoa = $this->connection->getLastGeneratedId();
      return $cod_pessoa;
   }

   public function updatePessoa($pessoa){
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
      $parameters = $this->setPessoa($pessoa);
      
      $result = $this->connection->query($sql, $parameters, false);
      return $result;
   }

   public function deletePessoa($cod_pessoa){
      $sql = "DELETE FROM pessoa WHERE cod_pessoa = '#cod_pessoa#' ";
      $parameters["cod_pessoa"] = $cod_pessoa;
     
      return $this->connection->execute($sql, $parameters);
   }

   private function updateFieldPessoa($cod_pessoa, $campo, $valor) {
      $sql = "UPDATE pessoa ".
             "   SET #campo# = '#valor#' ".
             " WHERE cod_pessoa = '#cod_pessoa#' ";
      $parameters = array(
            "cod_pessoa" => $cod_pessoa,
            "campo"      => $campo,
            "valor"      => $valor
      );
      $result = $this->connection->query($sql, $parameters, false);
      return $result;
   }

   public function getAniversariantes() {
      $sql = "SELECT cod_pessoa, nome_pessoa, dt_nasc ".
             "  FROM pessoa ".
             " WHERE MONTH(dt_nasc) = MONTH(NOW()) ".
               " AND DAY(dt_nasc) = DAY(NOW()) ";
      $parameters = "";
      $list = $this->connection->allResults($sql, $parameters, "array");
      return $list;
   }

}
