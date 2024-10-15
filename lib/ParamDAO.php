<?php
// Inclui o arquivo da classe GenericDAO, garantindo que só será incluído uma vez
require_once(__DIR__."/../lib/GenericDAO.php");
// Inclui o arquivo com funções auxiliares, garantindo que só será incluído uma vez
require_once(__DIR__."/../inc/functions.php");

// Define a classe ParamDAO que herda de GenericDAO
class ParamDAO extends GenericDAO {

   // Construtor da classe que recebe a conexão como parâmetro
   public function __construct($conn){
      // Chama o construtor da classe pai para inicializar a conexão
      parent::__construct($conn);
   }

   // Método que retorna a quantidade de registros na tabela 'param'
   public function getParamQty(){
      // SQL para contar os registros na tabela 'param'
      $sql = "SELECT COUNT(*) AS cont FROM param ";
      // Não há parâmetros para a query
      $parameters = null;
      // Executa a query e obtém o resultado (quantidade de registros)
      $conta = $this->connection->singleResult($sql, $parameters);
      // Retorna o valor da contagem
      return $conta->cont;
   }

   // Método que retorna uma lista de parâmetros, com base em uma pesquisa
   public function getParamList($search){
      // SQL para obter dados das tabelas 'param', 'empresa' e 'pessoa'
      $sql = "SELECT p.*, e.*, x.*, x.nome_pessoa AS nome_empresa ".
               "FROM param p, empresa e, pessoa x ".
              "WHERE e.cod_empresa = p.cod_empresa ".
                "AND x.cod_pessoa = e.cod_empresa";
      // Executa a query e retorna todos os resultados
      $list = $this->connection->allResults($sql);
      return $list;
   }

   // Método que retorna um parâmetro específico, baseado no código da empresa
   public function getParam($cod_empresa){
      // Se o código da empresa for 0, define como '1'
      if($cod_empresa == 0) $cod_empresa = '1';
      // SQL para obter dados do parâmetro e o nome da empresa
      $sql = "SELECT p.*, e.nome_pessoa AS nome_empresa ".
             "  FROM param p ".
             " INNER JOIN pessoa e ON e.cod_pessoa = p.cod_empresa ".
             " WHERE p.cod_empresa = '#cod_empresa#' ";
      // Define os parâmetros da query
      $parameters["cod_empresa"] = $cod_empresa;
      // Executa a query e obtém o resultado único
      $result = $this->connection->singleResult($sql, $parameters);
      return $result;
   }

   // Método privado que formata os dados do parâmetro para inserção/atualização
   private function setParam($param) {
      // Retorna um array associativo com os valores do parâmetro
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

   // Método que insere um novo parâmetro padrão para uma empresa
   public function newDefaultParam($cod_empresa) {
      // SQL para inserir um novo registro na tabela 'param'
      $sql = "INSERT INTO param (cod_empresa, dt_impl_sis, raiz, skin, upload, caixa_oper, caixa_inicial, caixa_data, caixa_perfil, expediente_ini, expediente_fin, intervalo_ini, intervalo_fin, dias_uteis, fluxo_caixa, saldo_inicial, saldo_data, pedido, ped_gera_fat, ped_gera_nf, ped_gera_rec, ped_status_gera, fatura, fat_gera_nf, nota_fiscal) ".
             "VALUES ('#cod_empresa#', '#dt_impl_sis#', '#raiz#', '#skin#', '#upload#', '#caixa_oper#', '#caixa_inicial#', #caixa_data#, '#caixa_perfil#', '#expediente_ini#', '#expediente_fin#', '#intervalo_ini#', '#intervalo_fin#', '#dias_uteis#', '#fluxo_caixa#', '#saldo_inicial#', #saldo_data#, '#pedido#', '#ped_gera_fat#', '#ped_gera_nf#', '#ped_gera_rec#', '#ped_status_gera#', '#fatura#', '#fat_gera_nf#', '#nota_fiscal#') ";
      // Define os parâmetros com valores padrão
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
      
      // Executa a query para inserir o novo parâmetro
      $result = $this->connection->query($sql, $parameters, false);
      
      // Cria diretórios para armazenar arquivos da empresa
      createDirectory(__DIR__."/..".$parameters["upload"]);
      createDirectory(__DIR__."/..".$parameters["upload"]."anexo");
      createDirectory(__DIR__."/..".$parameters["upload"]."anexo/pedido");
      createDirectory(__DIR__."/..".$parameters["upload"]."foto");
      createDirectory(__DIR__."/..".$parameters["upload"]."report");
      
      // Copia arquivos de exemplo para o diretório da empresa
      copyFile(__DIR__."/../file/empresa/foto/admin.jpg", __DIR__."/..".$parameters["upload"]."foto/admin.jpg");
      copyFile(__DIR__."/../file/empresa/foto/avatar.gif", __DIR__."/..".$parameters["upload"]."foto/avatar.gif");
      copyFile(__DIR__."/../file/empresa/foto/super.jpg", __DIR__."/..".$parameters["upload"]."foto/super.jpg");
      
      return $result; 
   }

   // Método que insere um novo parâmetro com base nos dados fornecidos
   public function newParam($param){
      // SQL para inserir um novo parâmetro na tabela 'param'
      $sql = "INSERT INTO param (cod_empresa, raiz, skin, upload, caixa_oper, caixa_inicial, caixa_data, caixa_perfil, expediente_ini, expediente_fin, intervalo_ini, intervalo_fin, dias_uteis, fluxo_caixa, saldo_inicial, saldo_data, pedido, ped_gera_fat, ped_gera_nf, ped_gera_rec, ped_status_gera, fatura, fat_gera_nf, nota_fiscal) ".
             "VALUES ('#cod_empresa#', '#raiz#', '#skin#', '#upload#', '#caixa_oper#', '#caixa_inicial#', #caixa_data#, '#caixa_perfil#', '#expediente_ini#', '#expediente_fin#', '#intervalo_ini#', '#intervalo_fin#', '#dias_uteis#', '#fluxo_caixa#', '#saldo_inicial#', #saldo_data#, '#pedido#', '#ped_gera_fat#', '#ped_gera_nf#', '#ped_gera_rec#', '#ped_status_gera#', '#fatura#', '#fat_gera_nf#', '#nota_fiscal#') ";
      
      // Define os parâmetros com os valores do objeto param
      $parameters = $this->setParam($param);
      
      // Executa a query para inserir o novo parâmetro
      $result = $this->connection->query($sql, $parameters, false);
      return $result; 
   }

   // Método que atualiza um parâmetro existente
   public function updateParam($param){
      // SQL para atualizar os dados de um parâmetro na tabela 'param'
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
      
      // Define os parâmetros com os valores do objeto param
      $parameters = $this->setParam($param);
      
      // Executa a query para atualizar o parâmetro
      return $this->connection->query($sql, $parameters, false);
   }

   // Método que deleta um parâmetro com base no código da empresa
   public function deleteParam($cod_empresa){
      // SQL para deletar um registro da tabela 'param'
      $sql = "DELETE FROM param ".
             " WHERE cod_empresa = #cod_empresa# ";
      
      // Define o código da empresa como parâmetro
      $parameters["cod_empresa"] = $cod_empresa;
      
      // Remove o diretório associado à empresa
      removeDirectory(__DIR__."/.."."/file/empresa".$cod_empresa."/");
      
      // Executa a query para deletar o parâmetro
      return $this->connection->execute($sql, $parameters);
   }

   // Método que retorna uma lista de skins (temas visuais)
   public function getSkin() {
      // Retorna um objeto com diferentes skins disponíveis
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