<?php

// Verifica se houve uma submissão do formulário via método POST.
if($_POST) {

   // Cria um novo objeto padrão para armazenar os dados enviados pelo formulário.
   $post = new stdClass();

   // Atribui os valores enviados no formulário ao objeto $post.
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

   // Verifica a ação a ser realizada (novo, editar ou excluir), dependendo do valor de $mode.
   switch($mode) {
      case "new":
         // Se o modo for 'new', chama o serviço para cadastrar um novo programa.
         $retorno = $service->newPrograma($post);
         // Cria uma mensagem de sucesso indicando que o programa foi cadastrado.
         $msg_sistema = $programa->nome_programa." ".$post->nome_programa." cadastrado com sucesso";
      break;

      case "edit":
         // Se o modo for 'edit', chama o serviço para atualizar o programa.
         $retorno = $service->updatePrograma($post);
         // Cria uma mensagem de sucesso indicando que o programa foi alterado.
         $msg_sistema = $programa->nome_programa." ".$post->nome_programa." alterado com sucesso";
      break;

      case "delete":
         // Se o modo for 'delete', chama o serviço para excluir o programa usando o código do programa.
         $retorno = $service->deletePrograma($post->cod_programa);
         // Cria uma mensagem de sucesso indicando que o programa foi excluído.
         $msg_sistema = $programa->nome_programa." ".$post->nome_programa." excluído com sucesso";
      break;
   }

   // Armazena a mensagem de sucesso na sessão para exibição posterior.
   $_SESSION["alerta_sucesso"] = $msg_sistema;
   // Redireciona o usuário para a lista de programas após a operação.
   header("Location:index.php?mode=list&acao=".$sigla);
   // Finaliza a execução do script.
   exit;
}

// Verifica se o modo é 'edit'.
if($mode == "edit") {
   // Define o título da página como "editar".
   $titulo = "editar";
   // Define o campo de código como desabilitado (não pode ser editado).
   $disabled = "disabled";
   // Obtém os dados do programa a ser editado.
   $row = $service->getPrograma($codigo);
} else {
   // Se o modo não for 'edit', define o título como "novo".
   $titulo = "novo";
   // Cria um novo objeto vazio para o formulário.
   $row = new stdClass();
   // Define o tipo do programa como padrão (valor 0).
   $row->ind_tipo = 0;
}

?>

<!-- Cabeçalho do conteúdo da página -->
<section class="content-header">
   <!-- Exibe o título da página concatenado com o nome do menu e a descrição do cadastro -->
   <h1><?=$titulo?> <?=$programa->nome_menu?> <small><?=$programa->desc_cad?></small></h1>
</section>

<!-- Conteúdo principal -->
<section class="content">
<div class="box box-info">
   <!-- Formulário para cadastro/edição de programas -->
   <form id="formCad" class="form-horizontal" method="post" action="<?=ROOT?>" novalidate>
      <!-- Campo oculto para armazenar a ação -->
      <input type="hidden" id="acao" name="acao" value="<?=$sigla?>">
      <!-- Campo oculto para armazenar o modo (new, edit ou delete) -->
      <input type="hidden" id="mode" name="mode" value="<?=$mode?>">

   <div class="box-header with-border">
      <h3 class="box-title">
         <!-- Botão para voltar à página anterior -->
         <button type="button" class="btn btn-primary pull-left" onclick="event.preventDefault();history.back();">
            <i class="fa fa-chevron-left"></i>&nbsp;Voltar
         </button>
      </h3>
   </div><!-- /.box-header -->

   <!-- Corpo da caixa com os campos do formulário -->
   <div class="box-body">

         <!-- Inclui os avisos de erro ou sucesso (mensagens de sistema) -->
         <?php include "avisos.php"; ?>

         <!-- Abas para organização dos formulários -->
         <div class="nav-tabs-custom">
            <ul id="tabs" class="nav nav-tabs">
               <li class="active"><a href="#tab_1" data-toggle="tab">Info</a></li>
            </ul>
            <div class="tab-content">

               <!-- Primeira aba de informações gerais -->
               <div class="tab-pane active" id="tab_1">
                  <!-- Campo para o código do programa -->
                  <div class="form-group">
                     <label for="cod_programa" class="col-sm-2 control-label">Código</label>
                     <div class="col-sm-2">
                        <!-- Campo desabilitado, pois o código não pode ser alterado -->
                        <input type="text" class="form-control" id="cod_programa" name="cod_programa" maxlength="20" size="10" value="<?=$row->cod_programa?>" disabled/>
                     </div>
                  </div>

                  <!-- Campos para selecionar o tipo de programa -->
                  <div class="form-group">
                     <label for="ind_tipo" class="col-sm-2 control-label"><?="tipo"?></label>
                     <div class="col-sm-10 radioset">
                        <!-- Botões de opção para o tipo do programa (Cadastro, Tarefa, etc.) -->
                        <div class="radio"><label><input type="radio" name="ind_tipo" value="1" <?=iif($row->ind_tipo == 1, "checked", "")?> />&nbsp;Cadastro</label></div>
                        <div class="radio"><label><input type="radio" name="ind_tipo" value="2" <?=iif($row->ind_tipo == 2, "checked", "")?> />&nbsp;Tarefa</label></div>
                        <div class="radio"><label><input type="radio" name="ind_tipo" value="3" <?=iif($row->ind_tipo == 3, "checked", "")?> />&nbsp;Consulta</label></div>
                        <div class="radio"><label><input type="radio" name="ind_tipo" value="4" <?=iif($row->ind_tipo == 4, "checked", "")?> />&nbsp;Relatório</label></div>
                        <div class="radio"><label><input type="radio" name="ind_tipo" value="8" <?=iif($row->ind_tipo == 8, "checked", "")?> />&nbsp;Configuração</label></div>
                        <div class="radio"><label><input type="radio" name="ind_tipo" value="9" <?=iif($row->ind_tipo == 9, "checked", "")?> />&nbsp;API</label></div>
                     </div>
                  </div>

                  <!-- Campo para definir a ordem do programa no menu -->
                  <div class="form-group">
                     <label for="ordem" class="col-sm-2 control-label">Ordem no Menu</label>
                     <div class="col-sm-2">
                        <!-- Campo numérico para definir a ordem -->
                        <input type="number" class="form-control" id="ordem" name="ordem" maxlength="3" size="10" value="<?=$row->ordem?>"/>
                     </div>
                  </div>

                  <!-- Campo para o título do programa -->
                  <div class="form-group">
                     <label for="nome_programa" class="col-sm-2 control-label">Título</label>
                     <div class="col-sm-6">
                        <!-- Campo de texto para o título do programa -->
                        <input type="text" class="form-control" id="nome_programa" name="nome_programa" maxlength="1000" size="40" value="<?=$row->nome_programa?>"/>
                     </div>
                  </div>

                  <!-- Campo para o nome no menu -->
                  <div class="form-group">
                     <label for="nome_menu" class="col-sm-2 control-label">Nome no Menu</label>
                     <div class="col-sm-6">
                        <!-- Campo de texto para o nome no menu -->
                        <input type="text" class="form-control" id="nome_menu" name="nome_menu" maxlength="40" size="40" value="<?=$row->nome_menu?>"/>
                     </div>
                  </div>

                  <!-- Campo para o ícone no menu -->
                  <div class="form-group">
                     <label for="icone_menu" class="col-sm-2 control-label">Ícone no Menu</label>
                     <div class="col-sm-6">
                        <!-- Campo de texto para o ícone do menu -->
                        <input type="text" class="form-control" id="icone_menu" name="icone_menu" maxlength="100" size="40" value="<?=$row->icone_menu?>"/>
                     </div>
                  </div>

                  <!-- Campo para indicar se mostra quantidade de registros -->
                  <div class="form-group">
                     <label class="col-sm-2 control-label">&nbsp;</label>
                     <div class="col-sm-3">
                        <!-- Checkbox para definir se a quantidade de registros será mostrada -->
                        <div class="checkbox">
                           <label><input type="checkbox" id="ind_qtd" name="ind_qtd" value="1" <?=iif($row->ind_qtd, "checked", "")?>/> Mostra quantidade de registro</label>
                        </div>
                     </div>
                  </div>

                  <!-- Campo para a sigla do programa -->
                  <div class="form-group">
                     <label for="path" class="col-sm-2 control-label">Sigla</label>
                     <div class="col-sm-6">
                        <!-- Campo de texto para a sigla -->
                        <input type="text" class="form-control" id="sigla" name="sigla" maxlength="200" size="40" value="<?=$row->sigla?>"/>
                     </div>
                  </div>

                  <!-- Campo para a descrição do programa na listagem -->
                  <div class="form-group">
                     <label for="desc_list" class="col-sm-2 control-label">Descrição na Listagem</label>
                     <div class="col-sm-6">
                        <!-- Área de texto para a descrição da listagem -->
                        <textarea class="form-control" id="desc_list" name="desc_list" rows="3"><?=$row->desc_list?></textarea>
                     </div>
                  </div>

                  <!-- Campo para a descrição no cadastro -->
                  <div class="form-group">
                     <label for="desc_cad" class="col-sm-2 control-label">Descrição no Cadastro</label>
                     <div class="col-sm-6">
                        <!-- Área de texto para a descrição no cadastro -->
                        <textarea class="form-control" id="desc_cad" name="desc_cad" rows="3"><?=$row->desc_cad?></textarea>
                     </div>
                  </div>

                  <!-- Checkbox para definir se o programa está ativo -->
                  <div class="form-group">
                     <label class="col-sm-2 control-label">&nbsp;</label>
                     <div class="col-sm-3">
                        <!-- Checkbox para marcar se o programa está ativo -->
                        <div class="checkbox">
                           <label><input type="checkbox" id="ind_ativo" name="ind_ativo" value="1" <?=iif($row->ind_ativo, "checked", "")?>/> Ativo</label>
                        </div>
                     </div>
                  </div>
               </div><!-- /.tab-pane -->

            </div><!-- /.tab-content -->
         </div><!-- /.nav-tabs-custom -->
      </div><!-- /.box-body -->

      <!-- Rodapé da caixa com os botões de ação -->
      <div class="box-footer">
         <?php
         // Verifica se o perfil do usuário permite salvar ou excluir
         if(PERFIL < 1 /*Root*/) {
            // Exibe o botão de salvar se o perfil permitir
            echo '<button type="button" class="btn btn-success pull-left submit"><i class="fa fa-check"></i>&nbsp;Salvar</button>';
         }
         //Exibe o botão de excluir se o perfil permitir e se o modo for de edição
         if(PERFIL < 1 /*Root*/ && $mode == "edit") {
            echo '<button type="button" class="btn btn-danger pull-right" onclick="NUrl.deleteCad(\''.$programa->nome_programa.'\',\''.$row->cod_programa.'\')"><i class="fa fa-trash"></i>&nbsp;Excluir</button>';
         }
         ?>
      </div><!-- /.box-footer -->
   </form>
</div><!-- /.box -->
</section><!-- /.content -->

<script>
   // Script que executa avisos de edição e validações no formulário ao carregar a página
   $(document).ready(function() {
      Shell.editingWarning();
      ValidForm.setForm('#formCad', '.submit', {});
   });
</script>
