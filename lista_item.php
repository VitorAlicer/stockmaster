<?php

// Cria um objeto chamado $filter com propriedades 'nome_item' e 'tipo', inicializadas como strings vazias.
$filter = (object)[
   'nome_item' => '',
   'tipo'      => '',
];

// Verifica se a variável $mode é igual a 'filter' (indicando que um filtro foi aplicado).
if($mode == 'filter') {
   // Se o modo for 'filter', atribui os valores das requisições 'nome_item' e 'tipo' ao objeto $filter.
   $filter->nome_item = $_REQUEST["nome_item"];
   $filter->tipo      = $_REQUEST["tipo"];
}

// Chama o método getItemListByFilter do objeto $service, passando o objeto $filter como parâmetro, e armazena o resultado na variável $lista.
$lista = $service->getItemListByFilter($filter);
?>

<!-- Content Header -->
<section class="content-header">
   <!-- Exibe o nome do programa e a descrição da lista utilizando a variável $programa -->
   <h1><?=$programa->nome_menu?> <small><?=$programa->desc_list?></small></h1>
</section>

<!-- Main Content -->
<section class="content">

   <!-- Inclui o arquivo 'alertas.php' que provavelmente contém código para exibir mensagens de alerta ou notificação. -->
   <?php include "alertas.php"; ?>

   <!-- Div para o filtro de pesquisa, com classe 'hidden-print' para ocultá-lo em impressões. -->
   <div id="boxFiltro" class="col-sm-3 hidden-print">
      <!-- Formulário para aplicar o filtro. O método GET é usado para enviar dados ao servidor. -->
      <form id="formFiltra" method="get" action="<?=ROOT?>">
         <!-- Campos ocultos que definem a ação e o modo do formulário. -->
         <input type="hidden" name="acao" value="<?=$sigla?>"/>
         <input type="hidden" name="mode" value="filter"/>

         <!-- Caixa que contém os elementos do filtro. -->
         <div class="box box-info">
            <div class="box-header">
               <!-- Título da caixa de filtro com um ícone. -->
               <h3 class="box-title"><i class="fa fa-filter"></i>&nbsp;<?="filtro"?></h3>
               <div class="box-tools pull-right">
                  <!-- Botão para minimizar o filtro. -->
                  <div class="btn btn-box-tool" onclick="Shell.sizeFilter()"><i class="fa fa-minus"></i></div>
               </div>
            </div><!-- /.box-header -->
            <div class="box-body table-responsive">
               <div class="form-group">
                  <!-- Campo para inserir o nome do item. -->
                  <label for="nome_item">Nome</label>
                  <input type="text" class="form-control input-sm" name="nome_item" value="<?=$filter->nome_item?>"/>
               </div>
               <div class="form-group">
                  <!-- Campo para selecionar o tipo de item através de botões de rádio. -->
                  <label for="tipo">Tipo</label>
                  <div class="radioset">
                     <!-- Opção para selecionar todos os tipos. -->
                     <div class="radio">
                        <label>
                           <input type="radio" name="tipo" value="" <?=iif($filter->tipo == '', "checked", "")?> />&nbsp;<?="todas"?>
                        </label>
                     </div>
                     <!-- Opção para selecionar itens normais. -->
                     <div class="radio">
                        <label>
                           <input type="radio" name="tipo" value="1" <?=iif($filter->tipo == 1, "checked", "")?> />&nbsp;<?="normal"?>
                        </label>
                     </div>
                     <!-- Opção para selecionar itens com variação. -->
                     <div class="radio">
                        <label>
                           <input type="radio" name="tipo" value="2" <?=iif($filter->tipo == 2, "checked", "")?> />&nbsp;<?="com_variacao"?>
                        </label>
                     </div>
                     <!-- Opção para selecionar itens com medida. -->
                     <div class="radio">
                        <label>
                           <input type="radio" name="tipo" value="3" <?=iif($filter->tipo == 3, "checked", "")?> />&nbsp;<?="com_medida"?>
                        </label>
                     </div>
                  </div>
               </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
               <!-- Botão para enviar o formulário de filtro. -->
               <button type="submit" class="btn btn-info btn-xs"><?="filtrar"?></button>
            </div><!-- /.box-footer -->
         </div><!-- /.box -->
      </form>
   </div><!-- /.col-sm-3 -->

   <!-- Div para exibir a lista de itens, ocupando a maior parte da tela. -->
   <div class="col-sm-9">
      <div class="box">
         <div class="box-header">
            <h3 class="box-title">
               <!-- Botão para criar um novo item. -->
               <button type="button" class="btn btn-success hidden-print" onclick="NUrl.newCad('<?=$sigla?>')"><i class="fa fa-plus-circle"></i>&nbsp;<?="novo"?></button>
            </h3>
         </div><!-- /.box-header -->
         <div class="box-body table-responsive no-padding">
            <table class="table table-hover sort">
               <tr class="head-list">
                  <!-- Cabeçalho da tabela com títulos das colunas. -->
                  <th><?="codigo"?></th>
                  <th><?="descricao"?></th>
                  <th class="text-right"><?="venda"?></th>
                  <th class="text-center"><?="tipo"?></th>
                  <th>&nbsp;</th>
               </tr>
               <?php
               // Itera sobre a lista de itens e cria uma linha na tabela para cada item.
               foreach($lista as $row) {
               ?>
                  <tr class="hand" onclick="NUrl.viewCad('<?=$sigla?>','<?=$row->cod_item?>')">
                     <!-- Exibe o código do item alinhado à direita. -->
                     <td width="50" align="right"><span class="text-light-blue"><?=$row->cod_item?></span></td>
                     <!-- Exibe o nome do item. -->
                     <td width="*"><span class="text-light-blue"><?=$row->nome_item?></span></td>
                     <!-- Exibe o valor de venda do item, ou '-' se o tipo for 2 (com variação). -->
                     <td width="150" align="right"><span class="text-light-blue"><?=($row->tipo != 2 /*variacao*/) ? invertDec($row->val_ref, '') : '-'?></span></td>
                     <td width="100" align="center">
                        <?php
                        // Usa um switch para exibir um rótulo de tipo apropriado com base no tipo do item.
                        switch($row->tipo) {
                           case 1: echo '<span class="label bg-blue">Normal</span>'; break;
                           case 2: echo '<span class="label bg-yellow">Com Variacao</span>'; break;
                           case 3: echo '<span class="label bg-green">Com Medida</span>'; break;
                        }
                        ?>
                     </td>
                     <!-- Ícone indicando que a linha é clicável. -->
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
