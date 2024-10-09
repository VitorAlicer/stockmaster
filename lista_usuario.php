<?php

// FILTRO
// Verifica se o modo é 'filter'. Se sim, prepara os filtros com base nos dados da requisição.
if($mode == 'filter') {
   // Cria um novo objeto padrão (stdClass) para armazenar os filtros.
   $filter = new stdClass();
   // Atribui valores das variáveis recebidas na requisição para o objeto de filtro.
   $filter->cod_empresa = $_REQUEST["cod_empresa"]; // Código da empresa
   $filter->nome        = $_REQUEST["nome"];        // Nome do usuário
   $filter->login       = $_REQUEST["login"];       // Login do usuário

} else {
   // Se não estiver no modo 'filter', inicializa os filtros com valores padrão.
   $filter = new stdClass();
   $filter->cod_empresa = $empresa->cod_empresa; // Define o código da empresa atual
   $filter->nome        = "";                      // Nome vazio
   $filter->login       = "";                      // Login vazio
}

// Chama o método getUsuarioListByFilter do serviço para obter a lista de usuários filtrados.
$lista = $service->getUsuarioListByFilter($filter);

?>

<!-- Content Header -->
<section class="content-header">
   <!-- Exibe o nome do menu e a descrição da lista. -->
   <h1><?=$programa->nome_menu?> <small><?=$programa->desc_list?></small></h1>
</section>

<!-- Main Content -->
<section class="content">

   <!-- Inclui o arquivo "alertas.php" para mostrar possíveis alertas. -->
   <?php include "alertas.php"; ?>

   <div id="boxFiltro" class="col-sm-3 hidden-print">
      <!-- Formulário de filtro para buscar usuários -->
      <form id="formFiltra" method="get" action="<?=ROOT?>">
         <!-- Campos ocultos para enviar a ação e o modo -->
         <input type="hidden" name="acao" value="<?=$sigla?>"/>
         <input type="hidden" name="mode" value="filter"/>

         <div class="box box-info">
            <div class="box-header">
               <!-- Título da caixa de filtro com ícone -->
               <h3 class="box-title"><i class="fa fa-filter"></i>&nbsp;<?="filtro"?></h3>
               <div class="box-tools pull-right">
                  <!-- Botão para minimizar o filtro -->
                  <div class="btn btn-box-tool" onclick="Shell.sizeFilter()"><i class="fa fa-minus"></i></div>
               </div>
            </div><!-- /.box-header -->
            <div class="box-body table-responsive">
               <div class="form-group">
                  <label for="nome"><?="nome"?></label>
                  <!-- Campo de entrada para o nome do usuário -->
                  <input type="text" class="form-control input-sm" name="nome" value="<?=$filter->nome?>"/>
               </div>
               <div class="form-group">
                  <label for="login">Login</label>
                  <!-- Campo de entrada para o login do usuário -->
                  <input type="text" class="form-control input-sm" name="login" value="<?=$filter->login?>"/>
               </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
               <!-- Botão para submeter o filtro -->
               <button type="submit" class="btn btn-info btn-xs">Filtrar</button>
            </div><!-- /.box-footer -->
         </div><!-- /.box -->
      </form>
   </div><!-- /.col-sm-3 -->

   <div class="col-sm-9">
      <div class="box">
         <div class="box-header">
            <h3 class="box-title">
               <!-- Botão para criar um novo usuário -->
               <button type="button" class="btn btn-success" onclick="NUrl.newCad('<?=$sigla?>')"><i class="fa fa-plus-circle"></i>&nbsp;<?="novo"?></label>
            </h3>
         </div><!-- /.box-header -->
         <div class="box-body table-responsive no-padding">
            <table class="table table-hover sort">
               <tr class="head-list">
                  <!-- Colunas da tabela para a lista de usuários -->
                  <?=iif($empresa->cod_empresa == "0", '<th>emp</th>', "")?>
                  <th>nome</th>
                  <th>login</th>
                  <th>&nbsp;</th>
               </tr>
               <?php
               // Loop através da lista de usuários e cria uma linha na tabela para cada item.
               foreach($lista as $row) {
                  // Verifica se o perfil do usuário não é -1 e se o código do perfil é -1, pula a iteração.
                  if(PERFIL != -1 && $row->cod_perfil == -1) continue;
                  // Se o perfil é maior que 1 e o código do perfil é 1, pula a iteração.
                  if(PERFIL  > 1 && $row->cod_perfil == 1) continue;
               ?>
                  <!-- Linha da tabela com ação de clique -->
                  <tr class="hand" onclick="NUrl.viewCad('<?=$sigla?>','<?=$row->cod_empresa?>|<?=$row->login?>')">
                     <!-- Exibe o código da empresa se o código da empresa for "0" -->
                     <?=iif($empresa->cod_empresa == "0", '<td width="8" class="text-right"><span class="text-light-blue">'.$row->cod_empresa.'</span></td>', "")?>
                     <!-- Célula para o nome do usuário -->
                     <td width="*"><span class="text-light-blue"><?=$row->nome_usuario?></span></td>
                     <!-- Célula para o login do usuário -->
                     <td width="15%"><span class="text-light-blue"><code><?=$row->login?></code></span></td>
                     <!-- Célula com ícone de seta -->
                     <td width="1%"><i class="fa fa-chevron-right"></i></td>
                  </tr>
               <?php
               }
               ?>
            </table>
         </div><!-- /.box-body -->
      </div><!-- /.box -->
   </div><!-- /.col-sm-9 -->

</section><!-- /.content -->
