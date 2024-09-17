<?php

if($_POST) {

   $post = new stdClass();
   $post->cod_programa  = $_POST["cod_programa"];
   $post->ind_tipo      = $_POST["ind_tipo"];
   $post->ordem         = $_POST["ordem"];
   $post->nome_programa = $_POST["nome_programa"];
   $post->nome_menu     = $_POST["nome_menu"];
   $post->icone_menu    = $_POST["icone_menu"];
   $post->ind_qtd       = $_POST["ind_qtd"];
   $post->sigla         = $_POST["sigla"];
   $post->desc_list     = $_POST["desc_list"];
   $post->desc_cad      = $_POST["desc_cad"];
   $post->ind_ativo     = $_POST["ind_ativo"];

   switch($mode) {
      case "new":
         $retorno = $service->newPrograma($post);
         $msg_sistema = $programa->nome_programa." ".$post->nome_programa." cadastrado com sucesso";
      break;
      case "edit":
         $retorno = $service->updatePrograma($post);
         $msg_sistema = $programa->nome_programa." ".$post->nome_programa." alterado com sucesso";
      break;
      case "delete":
         $retorno = $service->deletePrograma($post->cod_programa);
         $msg_sistema = $programa->nome_programa." ".$post->nome_programa." excluido com sucesso";
      break;
   }

   $_SESSION["alerta_sucesso"] = $msg_sistema;
   header("Location:index.php?mode=list&acao=".$sigla);
   exit;
}

if($mode == "edit") {
   $titulo = "editar";
   $disabled = "disabled";
   $row = $service->getPrograma($codigo);
} else {
   $titulo = "novo";
   $row = new stdClass();
   $row->ind_tipo = 0;
}

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

   <div class="box-header with-border">
      <h3 class="box-title"><button type="button" class="btn btn-primary pull-left" onclick="event.preventDefault();history.back();"><i class="fa fa-chevron-left"></i>&nbsp;Voltar</button></h3>
   </div><!-- /.box-header -->
      <div class="box-body">

         <?php include "avisos.php"; ?>

         <div class="nav-tabs-custom">
            <ul id="tabs" class="nav nav-tabs">
               <li class="active"><a href="#tab_1" data-toggle="tab">Info</a></li>
            </ul>
            <div class="tab-content">

               <!-- INFO -->
               <div class="tab-pane active" id="tab_1">
                  <div class="form-group">
                     <label for="cod_programa" class="col-sm-2 control-label">Código</label>
                     <div class="col-sm-2">
                        <input type="text" class="form-control" id="cod_programa" name="cod_programa" maxlength="20" size="10" value="<?=$row->cod_programa?>" disabled/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="ind_tipo" class="col-sm-2 control-label"><?="tipo"?></label>
                     <div class="col-sm-10 radioset">
                        <div class="radio"><label><input type="radio" name="ind_tipo" value="1" <?=iif($row->ind_tipo == 1, "checked", "")?> />&nbsp;Cadastro</label></div>
                        <div class="radio"><label><input type="radio" name="ind_tipo" value="2" <?=iif($row->ind_tipo == 2, "checked", "")?> />&nbsp;Tarefa</label></div>
                        <div class="radio"><label><input type="radio" name="ind_tipo" value="3" <?=iif($row->ind_tipo == 3, "checked", "")?> />&nbsp;Consulta</label></div>
                        <div class="radio"><label><input type="radio" name="ind_tipo" value="4" <?=iif($row->ind_tipo == 4, "checked", "")?> />&nbsp;Relatório</label></div>
                        <div class="radio"><label><input type="radio" name="ind_tipo" value="8" <?=iif($row->ind_tipo == 8, "checked", "")?> />&nbsp;Configuração</label></div>
                        <div class="radio"><label><input type="radio" name="ind_tipo" value="9" <?=iif($row->ind_tipo == 9, "checked", "")?> />&nbsp;API</label></div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="ordem" class="col-sm-2 control-label">Ordem no Menu</label>
                     <div class="col-sm-2">
                        <input type="number" class="form-control" id="ordem" name="ordem" maxlength="3" size="10" value="<?=$row->ordem?>"/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="nome_programa" class="col-sm-2 control-label">Título</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="nome_programa" name="nome_programa" maxlength="1000" size="40" value="<?=$row->nome_programa?>"/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="nome_menu" class="col-sm-2 control-label">Nome no Menu</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="nome_menu" name="nome_menu" maxlength="40" size="40" value="<?=$row->nome_menu?>"/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="icone_menu" class="col-sm-2 control-label">Ícone no Menu</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="icone_menu" name="icone_menu" maxlength="100" size="40" value="<?=$row->icone_menu?>"/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-2 control-label">&nbsp;</label>
                     <div class="col-sm-3">
                        <div class="checkbox">
                           <label><input type="checkbox" id="ind_qtd" name="ind_qtd" value="1" <?=iif($row->ind_qtd, "checked", "")?>/> Mostra quantidade de registro</label>
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="path" class="col-sm-2 control-label">Sigla</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="sigla" name="sigla" maxlength="200" size="40" value="<?=$row->sigla?>"/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="desc_list" class="col-sm-2 control-label">Descrição na Listagem</label>
                     <div class="col-sm-6">
                        <textarea class="form-control" id="desc_list" name="desc_list" rows="3"><?=$row->desc_list?></textarea>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="desc_cad" class="col-sm-2 control-label">Descrição no Cadastro</label>
                     <div class="col-sm-6">
                        <textarea class="form-control" id="desc_cad" name="desc_cad" rows="3"><?=$row->desc_cad?></textarea>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-2 control-label">&nbsp;</label>
                     <div class="col-sm-3">
                        <div class="checkbox">
                           <label><input type="checkbox" id="ind_ativo" name="ind_ativo" value="1" <?=iif($row->ind_ativo, "checked", "")?>/> Ativo</label>
                        </div>
                     </div>
                  </div>
               </div><!-- /.tab-pane -->

            </div><!-- /.tab-content -->
         </div><!-- /.nav-tabs-custom -->
      </div><!-- /.box-body -->
      <div class="box-footer">
         <?php
         if(PERFIL < 1 /*Root*/) {
            echo '<button type="button" class="btn btn-success pull-left submit"><i class="fa fa-check"></i>&nbsp;Salvar</button>';
         }
         if(PERFIL < 1 /*Root*/ && $mode == "edit") {
            echo '<button type="button" class="btn btn-danger pull-right" onclick="NUrl.deleteCad(\''.$programa->nome_programa.'\',\''.$row->cod_programa.'\')"><i class="fa fa-trash"></i>&nbsp;Excluir</button>';
         }
         ?>
      </div><!-- /.box-footer -->
   </form>
</div><!-- /.box -->
</section><!-- /.content -->

<script>
   $(document).ready(function() {
      Shell.editingWarning();
      ValidForm.setForm('#formCad', '.submit', {});
   });
</script>
