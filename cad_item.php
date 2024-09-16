<?php
require_once("bo/ItemBO.php");

if($_POST) {
   try {
      $cod_item  = $_POST["cod_item"];
      $nome_item = $_POST["nome_item"];
      $tipo      = $_POST["tipo"];

      $item = new ItemBO();

      if($mode == "delete") {
         $item->remove($cod_item, $nome_item);

      } elseif($mode == "new" || $mode == "edit") {

         $item->setItem($_POST);
         if($tipo == '2') $item->setVars();

         switch($mode) {
            case "new": $item->create(); break;
            case "edit": $item->update(); break;
         }
      }

      if($item->isError()) {
         throw new Exception($item->getMessage());
      }

      $_SESSION["alerta_sucesso"] = $item->getMessage();
      header("Location:index.php?mode=list&acao=".$sigla);
      exit;
   } catch(Exception $e) {
      $_SESSION["alerta_erro"] = $e->getMessage();
   }
}

if($mode == "edit") {
   $titulo = "editar";
   $disabled = "disabled";
   $row = $service->getItem($codigo);
   $lista_var = $service->getVariacaoList($codigo);
   $cont_var = count($lista_var);
} else {
   $titulo = "novo";
   $disabled = "";
   $lista_var = null;
   $row = (object)[
      'cod_item'           => '',
      'nome_item'          => '',
      'tipo'               => 1,
      'medida'             => '',
      'un'                 => '',
      'val_custo'          => 0,
      'val_ref'            => 0,
      'controla_estoque'   => false,
      'nome_var'           => '',
      'custo_var'          => 0,
      'val_var'            => 0,
   ];
}

$lista_un = $service->getItemUnidadeList();

?>

<!-- Content Header (Page header) -->
<section class="content-header">
   <h1><?=$titulo?> <?=$programa->nome_menu?> <small><?=$programa->desc_cad?></small></h1>
</section>

<!-- Main content -->
<section class="content">

   <?php include "alertas.php"; ?>

   <div class="box box-info">
      <form id="formCad" class="form-horizontal" method="post" action="<?=ROOT?>" novalidate>
         <input type="hidden" id="acao" name="acao" value="<?=$sigla?>">
         <input type="hidden" id="mode" name="mode" value="<?=$mode?>">
         <input type="hidden" id="tipo_ant" name="tipo_ant" value="<?=$row->tipo?>">
         <input type="hidden" id="qtd_var" name="qtd_var" value="<?=$cont_var?>">

         <div class="box-header with-border">
            <h3 class="box-title"><button type="button" class="btn btn-primary pull-left" onclick="event.preventDefault();history.back();"><i class="fa fa-chevron-left"></i>&nbsp;Voltar</button></h3>
         </div><!-- /.box-header -->
         <div class="box-body">

            <?php include "avisos.php"; ?>

            <div class="form-group">
               <label for="cod_item" class="col-sm-2 control-label">Código</label>
               <div class="col-sm-2">
                  <input type="text" class="form-control" id="cod_item" name="cod_item" maxlength="20" size="10" value="<?=$row->cod_item?>" disabled/>
               </div>
            </div>
            <div class="form-group">
               <label for="nome_item" class="col-sm-2 control-label">Nome</label>
               <div class="col-sm-6">
                  <input type="text" class="form-control" id="nome_item" name="nome_item" maxlength="100" size="40" value="<?=$row->nome_item?>"/>
               </div>
            </div>
            <div class="form-group">
               <label for="tipo" class="col-sm-2 control-label">Tipo</label>
               <div class="col-sm-10 radioset">
                  <div class="radio"><label><input type="radio" name="tipo" onclick="setTipoItem('1')" value="1" <?=iif($row->tipo == 1, "checked", "")?> />&nbsp;Normal</label></div>
                  <div class="radio"><label><input type="radio" name="tipo" onclick="setTipoItem('2')" value="2" <?=iif($row->tipo == 2, "checked", "")?> />&nbsp;Com Variação</label></div>
                  <div class="radio"><label><input type="radio" name="tipo" onclick="setTipoItem('3')" value="3" <?=iif($row->tipo == 3, "checked", "")?> />&nbsp;Com Medida</label></div>
               </div>
            </div>
            <div class="form-group campoMedida">
               <label for="medida" class="col-sm-2 control-label">Medida</label>
               <div class="col-sm-2">
                  <input type="text" class="form-control" id="medida" name="medida" maxlength="50" size="40" value="<?=$row->medida?>"/>
               </div>
               <label for="un" class="col-sm-1 control-label">Unidade</label>
               <div class="col-sm-2">
                  <select class="form-control" id="un" name="un">
                     <option value="">Selecione...</option>
                     <?php
                     foreach($lista_un as $key => $value) {
                        echo '<option value="'.$value.'" '.iif($row->un == $value, 'selected', '').'>'.$value.'</option>';
                     }
                     ?>
                  </select>
               </div>
            </div>
            <div class="form-group campoValor">
               <label for="val_custo" class="col-sm-2 control-label">Valor Custo</label>
               <div class="col-sm-2">
                  <input type="text" class="form-control numeric" id="val_custo" name="val_custo" maxlength="50" size="40" value="<?=invertDec($row->val_custo, '')?>"/>
               </div>
            </div>
            <div class="form-group campoValor">
               <label for="val_ref" class="col-sm-2 control-label">Valor Venda</label>
               <div class="col-sm-2">
                  <input type="text" class="form-control numeric" id="val_ref" name="val_ref" maxlength="50" size="40" value="<?=invertDec($row->val_ref, '')?>"/>
               </div>
            </div>
            <div class="form-group">
               <label class="col-sm-2 control-label">&nbsp;</label>
               <div class="col-sm-3">
                  <div class="checkbox">
                     <label><input type="checkbox" id="controla_estoque" name="controla_estoque" value="1" <?=iif($row->controla_estoque, "checked", "")?>/> Controla estoque</label>
                  </div>
               </div>
            </div>
            <br><br>

            <!-- VARIACAO -->
            <div class="row">
               <div class="campoVar col-sm-9 col-sm-offset-1">
                  <div class="cad-filho nav-tabs-custom cad-orange">
                     <ul id="tabs" class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Variação</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                           <div class="add-row form-group">
                              <label for="nome_var" class="col-sm-2 control-label" style="width:75px">Descrição:&nbsp;</label>
                              <div class="col-sm-3">
                                 <input type="text" class="form-control" id="nome_var" maxlength="40" size="40"/>
                              </div>
                              <label for="val_var" class="col-sm-1 control-label">Custo:&nbsp;</label>
                              <div class="col-sm-2">
                                 <input type="text" class="form-control numeric" id="custo_var" maxlength="20" size="10"/>
                              </div>
                              <label for="val_var" class="col-sm-1 control-label">Venda:&nbsp;</label>
                              <div class="col-sm-2">
                                 <input type="text" class="form-control numeric" id="val_var" maxlength="20" size="10"/>
                              </div>
                              <div class="col-sm-1">
                                 <button type="button" class="btn btn-sm btn-success" onclick="addItemVar()"><i class="fa fa-plus"></i> Adicionar</button>
                              </div>
                           </div>
                           <br>
                           <table class="table table-hover table-condensed add-var">
                              <tr id="cab-var">
                                 <th class="text-left">Descrição</th>
                                 <th class="text-left">Valor Custo</th>
                                 <th class="text-left">Valor Venda</th>
                                 <th>&nbsp;</th>
                              </tr>
                              <?php
                              $cont_var = 0;
                              if($lista_var) {
                                 foreach($lista_var as $row_var) {
                                    $cont_var++;
                                    echo '<tr id="var'.$cont_var.'">'.
                                         ' <td class="text-left"><input class="nome_var" name="nome_var'.$cont_var.'" value="'.$row_var->nome_var.'" /></td>'.
                                         ' <td class="text-left"><input class="custo_var numeric" name="custo_var'.$cont_var.'" value="'.invertDec($row_var->custo_var, '').'" /></td>'.
                                         ' <td class="text-left"><input class="val_var numeric" name="val_var'.$cont_var.'" value="'.invertDec($row_var->val_var, '').'" /></td>'.
                                         ' <td class="text-left" width="30"><button type="button" class="btn btn-xs btn-danger" onclick="delItemVar('.$cont_var.')" title="Excluir"><i class="fa fa-trash"></i></button>'.
                                         '</tr>';
                                 }
                              }
                              ?>
                           </table>
                        </div><!-- /.tab-pane -->
                     </div><!-- /.tab-content -->
                  </div><!-- /.nav-tabs-custom -->
               </div><!-- /.col-sm-9 -->
            </div><!-- /.row -->
         </div><!-- /.box-body -->
         <div class="box-footer">
            <button type="button" class="btn btn-success pull-left submit" onclick="validForm()"><i class="fa fa-check"></i>&nbsp;Salvar</button>
            <?php
            if(PERFIL <= 1 /*Administrador*/ && $mode == "edit") {
               echo '<button type="button" class="btn btn-danger pull-right" onclick="NUrl.deleteCad(\''.$programa->nome_programa.'\',\''.$row->cod_item.'\')"><i class="fa fa-trash"></i>&nbsp;Excluir</button>';
            }
            ?>
         </div><!-- /.box-footer -->
      </form>
   </div><!-- /.box -->
</section><!-- /.content -->

<script>
   var form = document.getElementById('formCad'),
       modo = '<?=$mode?>',
       codItem = $('#cod_item'),
       nomeItem = $("#nome_item"),
       tipoItem = '<?=$row->tipo?>',
       codGrupo = $('#cod_grupo'),
       contVar = <?=$cont_var?>;

   function setTipoItem(tipo) {
      var campoMedida = $('.campoMedida'),
          campoValor = $('.campoValor'),
          campoVar = $('.campoVar');
      campoMedida.hide();
      campoValor.hide();
      campoVar.hide();
      tipoItem = tipo;
      switch(tipo) {
         case '1': //normal
            campoValor.show();
         break;
         case '2': //com variacao
            campoVar.show();
         break;
         case '3': //com medida
            campoMedida.show();
            campoValor.show();
         break;
      }
   }

   function addItemVar() {
      Shell.clearAvisos();
      // validacao
      var hasError = false;
      if($('#nome_var').val() == '') {
         ValidForm.addError("Informe o nome da Variação.", event.target);
         $('#nome_var').focus();
         hasError = true;
      }
      if(Shell.stringToFloat($('#custo_var').val()) == 0) {
         ValidForm.addError("Informe o valor de Custo da Variação.", event.target);
         $('#custo_var').focus();
         hasError = true;
      }
      if(Shell.stringToFloat($('#val_var').val()) == 0) {
         ValidForm.addError("Informe o valor de Venda da Variação.", event.target);
         $('#val_var').focus();
         hasError = true;
      }
      if(hasError) {
         ValidForm.showError();
         return false;
      }
      //
      contVar++;
      $('#cab-var').show();
      var trItem = '<tr id="var'+contVar+'">' +
                   ' <td class="text-left"><input class="nome_var" name="nome_var'+contVar+'" value="" /></td>' +
                   ' <td class="text-left"><input class="custo_var numeric" name="custo_var'+contVar+'" value="" /></td>' +
                   ' <td class="text-left"><input class="val_var numeric" name="val_var'+contVar+'" value="" /></td>' +
                   ' <td class="text-left" width="30"><button type="button" class="btn btn-xs btn-danger" onclick="delItemVar('+contVar+')" title="Excluir"><i class="fa fa-trash"></i></button>' +
                   '</tr>';
      trItem = $(trItem);
      trItem.find('.nome_var').val($('#nome_var').val());
      trItem.find('.custo_var').val($('#custo_var').val());
      trItem.find('.val_var').val($('#val_var').val());
      $('.add-var').append(trItem);
      $('#qtd_var').val(contVar);
      $('#nome_var').val('');
      $('#custo_var').val('');
      $('#val_var').val('');
      Shell.setNumericInputMask();
      return true;
   }

   function delItemVar(cont) {
      $('#var'+cont).remove();
      contVar--;
   }

   function validForm() {
      var modo = '<?=$mode?>',
          campo = null,
          erro = false;

      event.preventDefault();
      Shell.clearAvisos();

      campo = document.getElementById('nome_item');
      if(campo.value == '') {
         erro = true;
         Shell.addAvisoErro("Informe o nome do Item.");
         campo.focus();
      }

      if(erro) {
         Shell.showAvisoErro();
         return false;
      }

      Shell.disableEditingWarning();
      Shell.enableForm();
      form.submit();
      return true;
   }

   $(document).ready(function() {
      Shell.editingWarning();
      if(contVar == 0) $('#cab-var').hide();
      setTipoItem(tipoItem);
   });
</script>
