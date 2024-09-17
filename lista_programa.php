<?php

$search = '';
if (!empty($_REQUEST["search"])) $search = $_REQUEST["search"];
$lista = $service->getProgramaList($search);

?>

<!-- Content Header -->
<section class="content-header">
   <h1><?=$programa->nome_menu?> <small><?=$programa->desc_list?></small></h1>
</section>

<!-- Main Content -->
<section class="content">

   <?php include "alertas.php"; ?>

   <div class="box">

      <div class="box-header">
         <h3 class="box-title">
            <button type="button" class="btn btn-success" onclick="NUrl.newCad('<?=$sigla?>')"><i class="fa fa-plus-circle"></i>&nbsp;<?="novo"?></label>
         </h3>
         <div class="box-tools">
            <form id="formFiltra" method="get" action="<?=ROOT?>">
               <input type="hidden" name="acao" value="<?=$sigla?>"/>
               <input type="hidden" name="mode" value="list"/>
               <div class="input-group input-group-sm search">
                  <input type="text" id="search" name="search" class="form-control pull-right" placeholder="Procurar..." value="<?=$search?>">
                  <div class="input-group-btn">
                     <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
               </div>
            </form>
         </div>
      </div><!-- /.box-header -->

      <div class="box-body table-responsive no-padding">
         <table class="table table-hover sort">
            <tbody>
            <tr class="head-list">
            <th>nome</th>
            <th class="text-center">tipo</th>
            <th>sigla</th>
            <th class="text-center">ordem</th>
            <th>&nbsp;</th>
            </tr>
            <?php
            foreach($lista as $row) {
               if(!$row->ind_ativo)
                  $cor = '#D8D8D8';
               else {
                  switch($row->ind_tipo) {
                     case 1:  $cor = '#A9E2F3'; break;
                     case 2:  $cor = '#F2F5A9'; break;
                     case 3:  $cor = '#D0A9F5'; break;
                     case 4:  $cor = '#F5A9BC'; break;
                     case 8:  $cor = '#BCF5A9'; break;
                     default: $cor = '#FFFFFF';
                  }
               }
            ?>
               <tr style="background-color:<?=$cor?>;" class="hand" onclick="NUrl.viewCad('<?=$sigla?>','<?=$row->cod_programa?>')">
                  <td width="*"><span class="text-light-blue"><?=$row->nome_programa?></span></td>
                  <td width="5%"><span class="label pull-right bg-blue"><?=$service->getDescricaoTipoPrograma($row->ind_tipo)?></span></td>
                  <td width="5%"><code><?=$row->sigla?></code></td>
                  <td width="1%"><span class="pull-right badge bg-blue"><?=$row->ordem?></span></td>
                  <td width="1%"><i class="fa fa-chevron-right"></i></td>
               </tr>
            <?php
            }
            ?>
            </tbody>
            <tfoot>
            </tfoot>
         </table>
      </div><!-- /.box-body -->

   </div>

</section><!-- /.content -->
