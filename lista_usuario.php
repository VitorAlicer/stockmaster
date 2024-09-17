<?php

// FILTRO
if($mode == 'filter') {
   $filter = new stdClass();
   $filter->cod_empresa = $_REQUEST["cod_empresa"];
   $filter->nome        = $_REQUEST["nome"];
   $filter->login       = $_REQUEST["login"];

} else {
   $filter = new stdClass();
   $filter->cod_empresa = $empresa->cod_empresa;
   $filter->nome        = "";
   $filter->login       = "";
}

$lista = $service->getUsuarioListByFilter($filter);

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
                  <label for="nome"><?="nome"?></label>
                  <input type="text" class="form-control input-sm" name="nome" value="<?=$filter->nome?>"/>
               </div>
               <div class="form-group">
                  <label for="login">Login</label>
                  <input type="text" class="form-control input-sm" name="login" value="<?=$filter->login?>"/>
               </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
               <button type="submit" class="btn btn-info btn-xs">Filtrar</button>
            </div><!-- /.box-footer -->
         </div><!-- /.box -->
      </form>
   </div><!-- /.col-sm-3 -->


   <div class="col-sm-9">
      <div class="box">
         <div class="box-header">
            <h3 class="box-title">
               <button type="button" class="btn btn-success" onclick="NUrl.newCad('<?=$sigla?>')"><i class="fa fa-plus-circle"></i>&nbsp;<?="novo"?></label>
            </h3>
         </div><!-- /.box-header -->
         <div class="box-body table-responsive no-padding">
            <table class="table table-hover sort">
               <tr class="head-list">
                  <?=iif($empresa->cod_empresa == "0", '<th>emp</th>', "")?>
                  <th>nome</th>
                  <th>login</th>
                  <th>&nbsp;</th>
               </tr>
               <?php
               foreach($lista as $row) {
                  if(PERFIL != -1 && $row->cod_perfil == -1) continue;
                  if(PERFIL  > 1 && $row->cod_perfil == 1) continue;
               ?>
                  <tr class="hand" onclick="NUrl.viewCad('<?=$sigla?>','<?=$row->cod_empresa?>|<?=$row->login?>')">
                     <?=iif($empresa->cod_empresa == "0", '<td width="8" class="text-right"><span class="text-light-blue">'.$row->cod_empresa.'</span></td>', "")?>
                     <td width="*"><span class="text-light-blue"><?=$row->nome_usuario?></span></td>
                     <td width="15%"><span class="text-light-blue"><code><?=$row->login?></code></td>
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
