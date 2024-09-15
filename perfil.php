<?php

$erro = false;
$row = null;

if($_POST) {

   session_start();

   $usuario = new stdClass();
   $usuario->cod_empresa  = $_POST["cod_empresa"];
   $usuario->login        = $_POST["login"];
   $usuario->nome_usuario = $_POST["nome_usuario"];
   $usuario->senha        = $_POST["nova_senha"];

   if($_POST["atual_senha"] != "" && $_POST["nova_senha"] != "") {
      $retorno = $service->login($usuario->login, $_POST["atual_senha"]);
      if(!$retorno) {
         $_SESSION["alerta_erro"] = "Atual Senha informada não confere.";
         $erro = true;
      }
   }

   if (!$erro) {
      if($_FILES['file']['name'] != "") {
         $storeFolder = fixPath(__DIR__.'/'.$param->upload.'/foto/');
         if(!is_dir($storeFolder)) {
            mkdir($storeFolder, 0755, true);
         }
         $targetFile = $storeFolder.$_POST["atual_foto"];
         removeFile($targetFile);
         $tempFile = $_FILES['file']['tmp_name'];
         $fileName = $_FILES['file']['name'];
         $fileSize = $_FILES['file']['size'];
         $ext = getExtensionFile($fileName);
         $fileName = $usuario->login.'_'.time().'.'.$ext;
         $targetFile = $storeFolder.'/'.$fileName;
         move_uploaded_file($tempFile, $targetFile);
         $usuario->foto = $fileName;
      }
      $retorno = $service->updateUsuario($usuario);
      $sessao->usuario = $service->getUsuarioByLogin($usuario->login);
      $sessao->usuario->nome_perfil = "";
      $_SESSION["alerta_sucesso"] = "Perfil alterado com sucesso";
      header("Location:index.php?acao=perfil");
      exit;
   }

}

$row = $sessao->usuario;

?>
<!-- Content Header (Page header) -->
<section class="content-header">
   <h1>Perfil <small>informações sobre seu perfil</small></h1>
</section>

<!-- Main content -->
<section class="content">

   <?php include "alertas.php"; ?>

   <div class="box box-info">
      <div class="box-header with-border"></div><!-- /.box-header -->
      <div class="box-body">

         <?php include "avisos.php"; ?>

         <form id="formCad" class="form-horizontal" enctype="multipart/form-data" method="post" action="<?=ROOT?>" novalidate onsubmit="Shell.disableEditingWarning(); return true;">
            <input type="hidden" id="acao" name="acao" value="<?=$sigla?>">
            <input type="hidden" id="mode" name="mode" value="<?=$mode?>">
            <input type="hidden" id="cod_empresa" name="cod_empresa" value="<?=$row->cod_empresa?>" />
            <input type="hidden" id="atual_foto" name="atual_foto" value="<?=$row->foto?>" />

            <div class="row">
               <div class="col-sm-9 col-sm-offset-1">
                  <div class="nav-tabs-custom">
                     <ul id="tabs" class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Geral</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                           <div class="form-group">
                              <label for="login" class="col-sm-2 control-label">Login</label>
                              <div class="col-sm-3">
                                 <input type="text" class="form-control" id="login" name="login" maxlength="20" size="10" value="<?=$row->login?>" disabled />
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="nome_usuario" class="col-sm-2 control-label"><?="nome"?></label>
                              <div class="col-sm-8">
                                 <input type="text" class="form-control" id="nome_usuario" name="nome_usuario" maxlength="50" size="40" value="<?=$row->nome_usuario?>" />
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="senha" class="col-sm-2 control-label">Atual Senha</label>
                              <div class="col-sm-8">
                                 <input type="password" class="form-control" id="atual_senha" name="atual_senha" maxlength="50" size="20" value="" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="senha" class="col-sm-2 control-label">Nova Senha</label>
                              <div class="col-sm-8">
                                 <input type="password" class="form-control" id="nova_senha" name="nova_senha" maxlength="50" size="20" value="" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
                              </div>
                           </div>
                        </div><!-- /.tab-pane -->
                     </div><!-- /.tab-content -->
                  </div><!-- /.nav-tabs-custom -->
               </div><!-- /.col-sm-9 -->
            </div><!-- /.row -->
            <br>

            <div class="row">
               <div class="col-sm-9 col-sm-offset-1">
                  <div class="nav-tabs-custom">
                     <ul id="tabs" class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Upload Foto</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                           <div class="box-widget widget-user-2">
                              <div class="widget-user-header">
                                 <div class="widget-user-image">
                                    <img class="img-circle" src="<?=$service->getURLFoto($param, $row->foto)?>" alt="User Avatar">
                                 </div>
                                 <div style="margin:20px 5px 5px 100px;">
                                    <input type="file" name="file"/>
                                 </div>
                              </div>
                              <div class="box-body">
                              </div>
                           </div><!-- /.widget-user -->
                        </div><!-- /.tab-pane -->
                     </div><!-- /.tab-content -->
                  </div><!-- /.nav-tabs-custom -->
               </div><!-- /.col-sm-9 -->
            </div><!-- /.row -->
            <br>

         </form>
      </div><!-- /.box-body -->
      <div class="box-footer">
         <button type="button" class="btn btn-success pull-left submit"><i class="fa fa-check"></i>&nbsp;Salvar</button>
      </div><!-- /.box-footer -->
   </div><!-- /.box -->
</section><!-- /.content -->

<script>
   var atualSenha = $('#atual_senha'),
       novaSenha = $('#nova_senha'),
       idioma = $('#idioma');

   $(document).ready(function() {
      Shell.editingWarning();
      ValidForm.setForm('#formCad', '.submit', {
         formCad: {
            onsubmit: function(event) {

               var erroValida = false;

               if((atualSenha.val() != '' && novaSenha.val() == '') ||
                  (atualSenha.val() == '' && novaSenha.val() != '')) {
                  ValidForm.addError("Para alterar a senha atual no sistema, favor digitar ambos campos: Nova Senha e Atual Senha.", event.target);
                  erroValida = true;
               }

               if(atualSenha.val() != '' && novaSenha.val() != '') {
                  if(atualSenha.val() == novaSenha.val()) {
                     ValidForm.addError("Nova Senha tem que ser diferente da Atual.", event.target);
                     erroValida = true;
                  }

                  if(novaSenha.val().length <= 8) {
                     ValidForm.addError("Nova Senha muito pequena, favor informar mais de 8 caracteres.", event.target);
                     erroValida = true;
                  }
               }

               if(idioma.val() == '') {
                  ValidForm.addError("Informe o idioma do usuário.", event.target);
                  erroValida = true;
               }

               if(erroValida) {
                  ValidForm.showError();
                  return true;
               }

               return false;
            }
         }
      });
   });
</script>
