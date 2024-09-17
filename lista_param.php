<?php

$search = '';
if (!empty($_REQUEST["search"])) $search = $_REQUEST["search"];
$lista = $service->getParamList($search);

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
            <th>cod</th>
            <th>razão social</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            </tr>
            <?php
            foreach($lista as $row) {
            ?>
               <tr class="hand" onclick="NUrl.viewCad('<?=$sigla?>','<?=$row->cod_empresa?>')">
                  <td width="8" class="text-right"><span class="text-light-blue"><?=$row->cod_empresa?></span></td>
                  <td width="*"><span class="text-light-blue"><?=$row->nome_empresa?></span></td>
                  <td width="1%"><?=iif($row->ind_matriz, '<span class="label pull-right bg-blue">MATRIZ</span>', '&nbsp;')?></td>
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
   </div><!-- /.box -->
</section><!-- /.content -->
