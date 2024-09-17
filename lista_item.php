<?php

// FILTRO
$filter = (object)[
   'nome_item' => '',
   'tipo'      => '',
];
if($mode == 'filter') {
   $filter->nome_item = $_REQUEST["nome_item"];
   $filter->tipo      = $_REQUEST["tipo"];
}

$lista = $service->getItemListByFilter($filter);
?>

<!-- Content Header -->
<section class="content-header">
   <h1><?=$programa->nome_menu?> <small><?=$programa->desc_list?></small></h1>
</section>

<!-- Main Content -->
<section class="content">

   <?php include "alertas.php"; ?>

   <div id="boxFiltro" class="col-sm-3 hidden-print">
      <form id="formFiltra" method="get" action="<?=ROOT?>">
         <input type="hidden" name="acao" value="<?=$sigla?>"/>
         <input type="hidden" name="mode" value="filter"/>

         <div class="box box-info">
            <div class="box-header">
               <h3 class="box-title"><i class="fa fa-filter"></i>&nbsp;<?="filtro"?></h3>
               <div class="box-tools pull-right">
                  <div class="btn btn-box-tool" onclick="Shell.sizeFilter()"><i class="fa fa-minus"></i></div>
               </div>
            </div><!-- /.box-header -->
            <div class="box-body table-responsive">
               <div class="form-group">
                  <label for="nome_item">Nome</label>
                  <input type="text" class="form-control input-sm" name="nome_item" value="<?=$filter->nome_item?>"/>
               </div>
               <div class="form-group">
                  <label for="tipo">Tipo</label>
                  <div class="radioset">
                     <div class="radio"><label><input type="radio" name="tipo" value=""  <?=iif($filter->tipo == '', "checked", "")?> />&nbsp;<?="todas"?></label></div>
                     <div class="radio"><label><input type="radio" name="tipo" value="1" <?=iif($filter->tipo == 1, "checked", "")?> />&nbsp;<?="normal"?></label></div>
                     <div class="radio"><label><input type="radio" name="tipo" value="2" <?=iif($filter->tipo == 2, "checked", "")?> />&nbsp;<?="com_variacao"?></label></div>
                     <div class="radio"><label><input type="radio" name="tipo" value="3" <?=iif($filter->tipo == 3, "checked", "")?> />&nbsp;<?="com_medida"?></label></div>
                  </div>
               </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
               <button type="submit" class="btn btn-info btn-xs"><?="filtrar"?></button>
            </div><!-- /.box-footer -->
         </div><!-- /.box -->
      </form>
   </div><!-- /.col-sm-3 -->

   <div class="col-sm-9">
      <div class="box">
         <div class="box-header">
            <h3 class="box-title">
               <button type="button" class="btn btn-success hidden-print" onclick="NUrl.newCad('<?=$sigla?>')"><i class="fa fa-plus-circle"></i>&nbsp;<?="novo"?></label>
            </h3>
         </div><!-- /.box-header -->
         <div class="box-body table-responsive no-padding">
            <table class="table table-hover sort">
               <tr class="head-list">
                  <th><?="codigo"?></th>
                  <th><?="descricao"?></th>
                  <th class="text-right"><?="venda"?></th>
                  <th class="text-center"><?="tipo"?></th>
                  <th>&nbsp;</th>
               </tr>
               <?php
               foreach($lista as $row) {
               ?>
                  <tr class="hand" onclick="NUrl.viewCad('<?=$sigla?>','<?=$row->cod_item?>')">
                     <td width="50" align="right"><span class="text-light-blue"><?=$row->cod_item?></span></td>
                     <td width="*"><span class="text-light-blue"><?=$row->nome_item?></span></td>
                     <td width="150" align="right"><span class="text-light-blue"><?=($row->tipo != 2 /*variacao*/) ? invertDec($row->val_ref, '') : '-'?></span></td>
                     <td width="100" align="center">
                        <?php
                        switch($row->tipo) {
                           case 1: echo '<span class="label bg-blue">Normal</span>'; break;
                           case 2: echo '<span class="label bg-yellow">Com Variacao</span>'; break;
                           case 3: echo '<span class="label bg-green">Com Medida</span>'; break;
                        }
                        ?>
                     </td>
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
