<?php

// Obtém uma lista de programas disponíveis chamando um método do objeto $service.
$lista_prog = $service->getProgramaList('');

// Verifica se o formulário foi enviado via POST.
if($_POST) {

   // Cria um objeto $usuario com os dados do formulário.
   $usuario = (object)[
      'cod_empresa'     => $_POST["cod_empresa"],     // Código da empresa.
      'cod_usuario'     => $_POST["cod_usuario"],     // Código do usuário.
      'login'           => $_POST["login"],           // Login do usuário.
      'nome_usuario'    => $_POST["nome_usuario"],    // Nome do usuário.
      'cod_perfil'      => $_POST["cod_perfil"][0],   // Código do perfil (primeiro elemento).
      'senha'           => $_POST["senha"],           // Senha do usuário.
      'lista_programa'  => $_POST['cod_programa'],    // Lista de programas selecionados.
      'foto'            => '',                          // Foto do usuário (não está definida no formulário).
   ];

   // Inicia um bloco de seleção para executar diferentes ações com base no modo.
   switch($mode) {
      case "new": // Se o modo for "novo"
         $retorno = $service->newUsuario($usuario); // Cria um novo usuário.
         $retorno = $service->setProgramaListByUsuario($usuario->cod_empresa, $usuario->login, $usuario->lista_programa); // Define a lista de programas para o usuário.
         $msg_sistema = "Usuário <b>".$usuario->nome_usuario."</b> cadastrado com sucesso"; // Mensagem de sucesso.
      break;
      case "edit": // Se o modo for "editar"
         $retorno = $service->updateUsuario($usuario); // Atualiza o usuário existente.
         $retorno = $service->setProgramaListByUsuario($usuario->cod_empresa, $usuario->login, $usuario->lista_programa); // Atualiza a lista de programas do usuário.
         $msg_sistema = "Usuário <b>".$usuario->nome_usuario."</b> alterado com sucesso"; // Mensagem de sucesso.
      break;
      case "delete": // Se o modo for "excluir"
         $retorno = $service->deleteUsuario($usuario->cod_empresa, $usuario->login); // Exclui o usuário.
         $retorno = $service->deleteProgramaListByUsuario($usuario->cod_empresa, $usuario->login); // Exclui a lista de programas do usuário.
         $msg_sistema = "Usuário <b>".$usuario->nome_usuario."</b> excluido com sucesso"; // Mensagem de sucesso.
      break;
   }

   // Armazena a mensagem de sucesso na sessão e redireciona para a lista de usuários.
   $_SESSION["alerta_sucesso"] = $msg_sistema;
   header("Location:index.php?mode=list&acao=".$sigla); // Redireciona para a página de listagem.
   exit; // Termina a execução do script.
}

// Verifica se o modo é "editar".
if($mode == "edit") {
   $titulo = "editar"; // Define o título como "editar".
   $disabled = "disabled"; // Desabilita os campos de entrada.
   $ar_codigo = explode("|", $codigo); // Captura o código concatenado por "|".
   $cod_empresa = $ar_codigo[0]; // Captura o código da empresa.
   $login = $ar_codigo[1]; // Captura o login do usuário.
   // Busca o registro do usuário com base no login.
   $row = $service->getUsuarioByLogin($login);
   // Obtém a lista de programas associados ao usuário.
   $row->lista_progr = $service->getProgramaListByUsuario($row->cod_empresa, $row->login);
} else {
   $titulo = "novo"; // Define o título como "novo".
   $disabled = ""; // Permite que os campos de entrada sejam editáveis.
   // Cria um objeto $row com valores padrão para um novo usuário.
   $row = (object)[
      'cod_empresa'  => $empresa->cod_empresa, // Define o código da empresa.
      'cod_usuario'  => '',                      // Inicializa o código do usuário como vazio.
      'login'        => '',                      // Inicializa o login como vazio.
      'nome_usuario' => '',                      // Inicializa o nome do usuário como vazio.
      'senha'        => '',                      // Inicializa a senha como vazia.
      'cod_perfil'   => '0',                    // Define o perfil padrão como 0.
      'lista_progr'  => array(),                // Inicializa a lista de programas como um array vazio.
   ];
}

?>

<!-- Header da seção de conteúdo -->
<section class="content-header">
   <!-- Título da página, com o modo e descrição do programa -->
   <h1><?=$titulo?> <?=$programa->nome_menu?> <small><?=$programa->desc_cad?></small></h1>
</section>

<!-- Conteúdo principal -->
<section class="content">
   <div class="box box-info">
      <!-- Formulário de cadastro -->
      <form id="formCad" class="form-horizontal" method="post" action="<?=ROOT?>" autocomplete="off" novalidate>
         <input type="hidden" id="acao" name="acao" value="<?=$sigla?>"> <!-- Campo oculto para ação -->
         <input type="hidden" id="mode" name="mode" value="<?=$mode?>"> <!-- Campo oculto para modo -->
         <input type="hidden" id="cod_empresa" name="cod_empresa" value="<?=$row->cod_empresa?>"> <!-- Campo oculto para código da empresa -->
         <input type="hidden" name="cod_usuario" value="<?=$row->cod_usuario?>"> <!-- Campo oculto para código do usuário -->

         <div class="box-header with-border">
            <!-- Botão para voltar à página anterior -->
            <h3 class="box-title"><button type="button" class="btn btn-primary pull-left" onclick="event.preventDefault();history.back();"><i class="fa fa-chevron-left"></i>&nbsp;Voltar</button></h3>
         </div><!-- /.box-header -->
         <div class="box-body">

            <?php include "avisos.php"; ?> <!-- Inclui o arquivo de avisos -->

            <!-- INFO -->
            <div class="row">
               <div class="col-sm-12">
                  <div class="cad-filho nav-tabs-custom cad-orange">
                     <!-- Abas do formulário -->
                     <ul id="tabs1" class="nav nav-tabs">
                        <li class="tab active"><a href="#tab_1" data-toggle="tab">Info</a></li> <!-- Aba de informações -->
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">

                           <!-- Campo para o Login -->
                           <div class="form-group">
                              <label for="login" class="col-sm-2 control-label">Login</label>
                              <div class="col-sm-3">
                                 <input type="text" class="form-control" id="login" name="login" maxlength="20" size="10" value="<?=$row->login?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" <?=$disabled?> /> <!-- Campo de entrada para o login -->
                              </div>
                              <div class="col-sm-4">
                                 <span id="msg_login" class="text-red"></span> <!-- Mensagem de erro do login -->
                              </div>
                           </div>

                           <!-- Campo para o Nome do Usuário -->
                           <div class="form-group">
                              <label for="nome_usuario" class="col-sm-2 control-label">Nome</label>
                              <div class="col-sm-10">
                                 <input type="text" class="form-control" id="nome_usuario" name="nome_usuario" maxlength="50" size="40" value="<?=$row->nome_usuario?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" /> <!-- Campo de entrada para o nome do usuário -->
                              </div>
                           </div>

                           <!-- Campo para a Senha -->
                           <div class="form-group">
                              <label for="senha" class="col-sm-2 control-label"><?=iif($mode == "edit", "Nova ", "")?>Senha</label>
                              <div class="col-sm-3">
                                 <input type="password" class="form-control" id="senha" name="senha" maxlength="50" size="20" value="<?=$row->senha?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"/> <!-- Campo de entrada para a senha -->
                              </div>
                           </div>

                           <!-- Campo para o Perfil -->
                           <div class="form-group">
                              <label for="cod_perfil" class="col-sm-2 control-label">Perfil</label>
                              <div class="col-sm-10 radioset">
                                 <?php
                                 // Exibe opções de perfil de acordo com o nível do usuário
                                 if(PERFIL == -1) {
                                    echo '<div class="radio-inline"><label><input type="radio" name="cod_perfil[]" value="-1" '.iif($row->cod_perfil == -1, "checked", "").' />&nbsp;Root</label></div>'; // Perfil Root
                                 }
                                 if(PERFIL <= 1) {
                                    echo '<div class="radio-inline"><label><input type="radio" name="cod_perfil[]" value="1" '.iif($row->cod_perfil == 1, "checked", "").' />&nbsp;Administrador</label></div>'; // Perfil Administrador
                                 }
                                 ?>
                                 <div class="radio-inline"><label><input type="radio" name="cod_perfil[]" value="2" <?=iif($row->cod_perfil == 2, "checked", "")?> />&nbsp;Financeiro</label></div> <!-- Perfil Financeiro -->
                                 <div class="radio-inline"><label><input type="radio" name="cod_perfil[]" value="3" <?=iif($row->cod_perfil == 3, "checked", "")?> />&nbsp;Atendente</label></div> <!-- Perfil Atendente -->
                                 <div class="radio-inline"><label><input type="radio" name="cod_perfil[]" value="4" <?=iif($row->cod_perfil == 4, "checked", "")?> />&nbsp;Administrativo</label></div> <!-- Perfil Administrativo -->
                              </div>
                           </div>

                           <!-- Seção de Permissões -->
                           <div class="form-group">
                              <label class="col-sm-2 control-label">Permissão</label>
                              <?php
                              // Verifica o perfil e exibe as permissões de acordo com o tipo de programa.
                              if($row->cod_perfil == -1) {
                                 echo '<p>Acesso sem restrição</p>'; // Acesso sem restrição para Root.
                              } else {
                                 $tipo_prog = ''; // Variável para armazenar o tipo de programa.
                                 foreach($lista_prog as $programa) { // Loop através da lista de programas.
                                    if($tipo_prog != $programa->ind_tipo) { // Verifica se o tipo de programa mudou.
                                       if($tipo_prog != '') echo '</div>'; // Fecha a div anterior, se necessário.
                                       $tipo_prog = $programa->ind_tipo; // Atualiza o tipo de programa.
                                       // Exibe a descrição do tipo de programa.
                                       echo '<div class="col-md-2" style="width:initial;">'.
                                          '<u><b>'.$service->getDescricaoTipoPrograma($programa->ind_tipo).'</b></u>';
                                    }
                                    // Adiciona um checkbox para cada programa.
                                    echo '<div class="checkbox"><label><input type="checkbox" name="cod_programa[]" value="'.$programa->cod_programa.'" '.iif(in_array($programa->cod_programa, $row->lista_progr), 'checked', '').'>'.$programa->nome_menu.'</label></div>';
                                 }
                              }
                              ?>
                           </div>
                        </div><!-- /.tab-pane -->
                     </div><!-- /.tab-content -->
                  </div><!-- /.nav-tabs-custom -->
               </div><!-- /.col-sm-9 -->
            </div><!-- /.row -->
            <br>

         </div><!-- /.box-body -->
         <div class="box-footer">
            <!-- Botão para salvar as alterações -->
            <button type="button" class="btn btn-success pull-left submit" onclick="validForm()"><i class="fa fa-check"></i>&nbsp;Salvar</button>
            <?php
            // Exibe o botão de exclusão se o usuário tiver permissões administrativas e estiver no modo de edição.
            if(PERFIL <= 1 /*Administrador*/ && $mode == "edit") {
               echo '<button type="button" class="btn btn-danger pull-right" onclick="NUrl.deleteCad(\''.$programa->nome_programa.'\',\''.$row->login.'\')"><i class="fa fa-trash"></i>&nbsp;Excluir</button>';
            }
            ?>
         </div><!-- /.box-footer -->
      </form>
   </div><!-- /.box -->
</section><!-- /.content -->

<script>
   // Captura o formulário pelo ID.
   var form = document.getElementById('formCad');

   // Função para validar o formulário antes de enviar.
   function validForm() {
      var modo = '<?=$mode?>', // Captura o modo (novo ou editar).
          campo = null,
          erro = false; // Inicializa a variável de erro.

      event.preventDefault(); // Previne o comportamento padrão do formulário.
      Shell.clearAvisos(); // Limpa mensagens de aviso anteriores.

      // Valida o campo de login.
      campo = document.getElementById('login');
      if(modo == 'new' && campo.value == '') { // Se for novo e o campo estiver vazio.
         erro = true; // Define erro como verdadeiro.
         Shell.addAvisoErro("Informe o Login para o acesso."); // Adiciona aviso de erro.
         campo.focus(); // Foca no campo de login.
      }

      // Valida o campo de nome do usuário.
      campo = document.getElementById('nome_usuario');
      if(campo.value == '') { // Se o campo estiver vazio.
         erro = true; // Define erro como verdadeiro.
         Shell.addAvisoErro("Informe o Nome do Usuário."); // Adiciona aviso de erro.
         campo.focus(); // Foca no campo de nome do usuário.
      }

      // Valida o campo de senha.
      campo = document.getElementById('senha');
      if(modo == 'new' && campo.value == '') { // Se for novo e o campo estiver vazio.
         erro = true; // Define erro como verdadeiro.
         Shell.addAvisoErro("Informe uma Senha de acesso."); // Adiciona aviso de erro.
         campo.focus(); // Foca no campo de senha.
      }

      // Se houver erro, mostra os avisos e retorna falso.
      if(erro) {
         Shell.showAvisoErro();
         return false;
      }

      Shell.disableEditingWarning(); // Desabilita o aviso de edição.
      form.submit(); // Envia o formulário.
      return true; // Retorna verdadeiro para indicar que a validação foi bem-sucedida.
   }

   $(document).ready(function() {
      Shell.editingWarning(); // Inicializa o aviso de edição quando o documento estiver pronto.
   });
</script>
