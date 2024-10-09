<?php

$erro = false; // Inicializa uma variável de erro como falsa
$row = null; // Inicializa a variável $row como nula

if($_POST) { // Verifica se o formulário foi enviado via POST

   session_start(); // Inicia a sessão para gerenciar as variáveis de sessão

   $usuario = new stdClass(); // Cria um objeto para armazenar informações do usuário
   $usuario->cod_empresa  = $_POST["cod_empresa"]; // Captura o código da empresa
   $usuario->login        = $_POST["login"]; // Captura o login do usuário
   $usuario->nome_usuario = $_POST["nome_usuario"]; // Captura o nome do usuário
   $usuario->senha        = $_POST["nova_senha"]; // Captura a nova senha

   // Verifica se a senha atual e nova foram informadas
   if($_POST["atual_senha"] != "" && $_POST["nova_senha"] != "") {
      $retorno = $service->login($usuario->login, $_POST["atual_senha"]); // Tenta logar com as credenciais
      if(!$retorno) { // Se o login falhar
         $_SESSION["alerta_erro"] = "Atual Senha informada não confere."; // Armazena mensagem de erro na sessão
         $erro = true; // Define a variável de erro como verdadeira
      }
   }

   if (!$erro) { // Se não houve erro
      // Verifica se um novo arquivo foi enviado
      if($_FILES['file']['name'] != "") {
         $storeFolder = fixPath(__DIR__.'/'.$param->upload.'/foto/'); // Define o diretório de armazenamento
         if(!is_dir($storeFolder)) { // Verifica se o diretório existe
            mkdir($storeFolder, 0755, true); // Cria o diretório se não existir
         }
         $targetFile = $storeFolder.$_POST["atual_foto"]; // Define o caminho do arquivo atual
         removeFile($targetFile); // Remove o arquivo antigo
         $tempFile = $_FILES['file']['tmp_name']; // Captura o caminho temporário do arquivo enviado
         $fileName = $_FILES['file']['name']; // Captura o nome do arquivo
         $fileSize = $_FILES['file']['size']; // Captura o tamanho do arquivo
         $ext = getExtensionFile($fileName); // Obtém a extensão do arquivo
         $fileName = $usuario->login.'_'.time().'.'.$ext; // Define um novo nome para o arquivo
         $targetFile = $storeFolder.'/'.$fileName; // Define o caminho final do arquivo
         move_uploaded_file($tempFile, $targetFile); // Move o arquivo para o diretório de destino
         $usuario->foto = $fileName; // Armazena o nome do arquivo no objeto usuário
      }
      $retorno = $service->updateUsuario($usuario); // Atualiza as informações do usuário no serviço
      $sessao->usuario = $service->getUsuarioByLogin($usuario->login); // Obtém as informações atualizadas do usuário
      $sessao->usuario->nome_perfil = ""; // Limpa o nome do perfil do usuário (pode ser uma medida de segurança)
      $_SESSION["alerta_sucesso"] = "Perfil alterado com sucesso"; // Armazena uma mensagem de sucesso na sessão
      header("Location:index.php?acao=perfil"); // Redireciona o usuário para a página de perfil
      exit; // Encerra a execução do script
   }

}

// Obtém os dados do usuário da sessão
$row = $sessao->usuario;

?>
<!-- Content Header (Page header) -->
<section class="content-header">
   <h1>Perfil <small>informações sobre seu perfil</small></h1>
</section>

<!-- Main content -->
<section class="content">

   <?php include "alertas.php"; ?> <!-- Inclui um arquivo de alertas -->

   <div class="box box-info">
      <div class="box-header with-border"></div><!-- /.box-header -->
      <div class="box-body">

         <?php include "avisos.php"; ?> <!-- Inclui um arquivo de avisos -->

         <form id="formCad" class="form-horizontal" enctype="multipart/form-data" method="post" action="<?=ROOT?>" novalidate onsubmit="Shell.disableEditingWarning(); return true;">
            <input type="hidden" id="acao" name="acao" value="<?=$sigla?>"> <!-- Ação do formulário -->
            <input type="hidden" id="mode" name="mode" value="<?=$mode?>"> <!-- Modo do formulário -->
            <input type="hidden" id="cod_empresa" name="cod_empresa" value="<?=$row->cod_empresa?>" /> <!-- Código da empresa -->
            <input type="hidden" id="atual_foto" name="atual_foto" value="<?=$row->foto?>" /> <!-- Foto atual -->

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
                                    <img class="img-circle" src="<?=$service->getURLFoto($param, $row->foto)?>" alt="User Avatar"> <!-- Exibe a foto do usuário -->
                                 </div>
                                 <div style="margin:20px 5px 5px 100px;">
                                    <input type="file" name="file"/> <!-- Campo para upload de nova foto -->
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
         <button type="button" class="btn btn-success pull-left submit"><i class="fa fa-check"></i>&nbsp;Salvar</button> <!-- Botão para salvar as alterações -->
      </div><!-- /.box-footer -->
   </div><!-- /.box -->
</section><!-- /.content -->

<script>
   var atualSenha = $('#atual_senha'),
       novaSenha = $('#nova_senha'),
       idioma = $('#idioma');

   $(document).ready(function() {
      Shell.editingWarning(); // Inicializa um aviso de edição
      ValidForm.setForm('#formCad', '.submit', { // Validação do formulário
         formCad: {
            onsubmit: function(event) {
               var erroValida = false; // Inicializa a variável de erro de validação

               // Verifica se os campos de senha atual e nova foram preenchidos corretamente
               if((atualSenha.val() != '' && novaSenha.val() == '') ||
                  (atualSenha.val() == '' && novaSenha.val() != '')) {
                  ValidForm.addError("Para alterar a senha atual no sistema, favor digitar ambos campos: Nova Senha e Atual Senha.", event.target);
                  erroValida = true;
               }

               if(atualSenha.val() != '' && novaSenha.val() != '') {
                  if(atualSenha.val() == novaSenha.val()) { // Verifica se a nova senha é diferente da atual
                     ValidForm.addError("Nova Senha tem que ser diferente da Atual.", event.target);
                     erroValida = true;
                  }

                  if(novaSenha.val().length <= 8) { // Verifica se a nova senha possui mais de 8 caracteres
                     ValidForm.addError("Nova Senha muito pequena, favor informar mais de 8 caracteres.", event.target);
                     erroValida = true;
                  }
               }

               if(idioma.val() == '') { // Verifica se o idioma do usuário foi informado
                  ValidForm.addError("Informe o idioma do usuário.", event.target);
                  erroValida = true;
               }

               if(erroValida) { // Se houve erro de validação
                  ValidForm.showError(); // Exibe os erros
                  return true; // Permite a continuação
               }

               return false; // Bloqueia a submissão do formulário
            }
         }
      });
   });
</script>
