<?php

$titulo = "editar";
$disabled = "disabled";
$row = $service->getEmpresa($codigo);

?>

<!-- Content Header (Page header) -->
<section class="content-header">
   <h1><?=$titulo?> <?=$programa->nome_menu?> <small><?=$programa->desc_cad?></small></h1>
</section>

<!-- Main content -->
<section class="content">

<div class="box box-info">
   <form id="formCad" class="form-horizontal" method="post" action="<?=ROOT?>" novalidate>
      <input type="hidden" id="acao" name="acao" value="<?=$sigla?>">
      <input type="hidden" id="mode" name="mode" value="<?=$mode?>">
      <input type="hidden" name="tipo_pessoa" value="2">

      <div class="box-header with-border">
         <h3 class="box-title"><button type="button" class="btn btn-primary pull-left" onclick="event.preventDefault();history.back();"><i class="fa fa-chevron-left"></i>&nbsp;Voltar</button></h3>
      </div><!-- /.box-header -->
      <div class="box-body">

         <?php include "avisos.php"; ?>

         <div class="nav-tabs-custom">
            <ul id="tabs" class="nav nav-tabs">
               <li class="active"><a href="#tab_1" data-toggle="tab">Info</a></li>
               <li><a href="#tab_2" data-toggle="tab">Endereço</a></li>
            </ul>
            <div class="tab-content">

               <!-- INFO -->
               <div class="tab-pane active" id="tab_1">
                  <div class="form-group">
                     <label for="cod_pessoa" class="col-sm-2 control-label">Código</label>
                     <div class="col-sm-2">
                        <input type="text" class="form-control" id="cod_pessoa" name="cod_pessoa" maxlength="10" size="10" value="<?=$row->cod_empresa?>" disabled/>
                     </div>
                     <div class="col-sm-3">
                        <div class="checkbox">
                           <label><input type="checkbox" id="ind_matriz" name="ind_matriz" value="1" <?=iif($row->ind_matriz, "checked", "")?>/> Matriz</label>
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="nome_pessoa" class="col-sm-2 control-label">Razão Social</label>
                     <div class="col-sm-10">
                        <input type="text" class="form-control" id="nome_pessoa" name="nome_pessoa" maxlength="50" size="40" value="<?=$row->nome_empresa?>" <?=$disabled?>/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="nome_fantasia" class="col-sm-2 control-label">Nome Fantasia</label>
                     <div class="col-sm-10">
                        <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" maxlength="50" size="40" value="<?=$row->nome_fantasia?>" />
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="id_federal" class="col-sm-2 control-label">CNPJ</label>
                     <div class="col-sm-3 box-cnpj">
                        <input type="text" class="form-control cnpj" id="id_federal" name="id_federal" maxlength="20" size="20" value="<?=$row->id_federal?>" <?=$disabled?>/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="ie" class="col-sm-2 control-label">Ins. Estadual</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="ie" name="ie" maxlength="20" size="20" value="<?=$row->ie?>" />
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="im" class="col-sm-2 control-label">Ins. Municipal</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="im" name="im" maxlength="20" size="20" value="<?=$row->im?>" />
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="site" class="col-sm-2 control-label">Site</label>
                     <div class="col-sm-10">
                        <input type="text" class="form-control" id="site" name="site" maxlength="150" size="40" value="<?=$row->site?>"/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="email" class="col-sm-2 control-label">E-mail</label>
                     <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email" maxlength="150" size="40" value="<?=$row->email?>"/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="fone" class="col-sm-2 control-label">Fone</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control fone" id="fone" name="fone" maxlength="15" size="15" value="<?=$row->fone?>"/>
                     </div>
                  </div>
               </div><!-- /.tab-pane -->

               <!-- ENDERECO -->
               <div class="tab-pane" id="tab_2">
                  <div class="form-group">
                     <label for="cep" class="col-sm-2 control-label">CEP</label>
                     <div class="col-sm-3 box-cep">
                        <input type="text" class="form-control cep" id="cep" name="cep" maxlength="10" size="10" value="<?=$row->cep?>"/>
                        <span><a href="http://www.buscacep.correios.com.br/sistemas/buscacep/buscaCep.cfm" target="_blank">Não se lembra do CEP ?</a></span>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="endereco" class="col-sm-2 control-label">Endereço</label>
                     <div class="col-sm-10">
                        <input type="text" class="form-control" id="endereco" name="endereco" maxlength="150" size="50" value="<?=$row->endereco?>"/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="numero" class="col-sm-2 control-label">Número</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control numero" id="numero" name="numero" maxlength="11" size="5" value="<?=$row->numero?>"/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="complemento" class="col-sm-2 control-label">Complemento</label>
                     <div class="col-sm-4">
                        <input type="text" class="form-control" id="complemento" name="complemento" maxlength="20" size="10" value="<?=$row->complemento?>"/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="bairro" class="col-sm-2 control-label">Bairro</label>
                     <div class="col-sm-8">
                        <input type="text" class="form-control" id="bairro" name="bairro" maxlength="30" size="30" value="<?=$row->bairro?>"/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="cidade" class="col-sm-2 control-label">Cidade</label>
                     <div class="col-sm-8">
                        <input type="text" class="form-control" id="cidade" name="cidade" maxlength="30" size="30" value="<?=$row->cidade?>"/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="uf" class="col-sm-2 control-label">UF</label>
                     <div class="col-sm-8">
                        <?php
                           $uf_selected = $row->uf;
                           $uf_id = "uf";
                           include "inc/uf.php";
                        ?>
                     </div>
                  </div>
               </div><!-- /.tab-pane -->

            </div><!-- /.tab-content -->
         </div><!-- /.nav-tabs-custom -->
      </div><!-- /.box-body -->
      <div class="box-footer">
      </div><!-- /.box-footer -->
   </form>
</div><!-- /.box -->
</section><!-- /.content -->


