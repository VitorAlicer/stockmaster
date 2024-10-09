<?php
// Inicializa a variável 'titulo' com o valor "editar"
$titulo = "editar";
// Define a variável 'disabled' com o valor "disabled", usada para desativar campos no formulário
$disabled = "disabled";
// Chama o método getEmpresa no objeto $service passando o $codigo, que retorna os dados da empresa
$row = $service->getEmpresa($codigo);
?>

<!-- Cabeçalho de conteúdo (Page header) -->
<section class="content-header">
   <!-- Exibe o título da página concatenado com o nome do menu do programa e sua descrição -->
   <h1><?=$titulo?> <?=$programa->nome_menu?> <small><?=$programa->desc_cad?></small></h1>
</section>

<!-- Seção principal do conteúdo -->
<section class="content">

<!-- Início de um box com estilo de informações (box-info) -->
<div class="box box-info">
   <!-- Início do formulário com id "formCad", estilo "form-horizontal", método "post" e ação para a raiz do site (ROOT) -->
   <form id="formCad" class="form-horizontal" method="post" action="<?=ROOT?>" novalidate>
      <!-- Campo oculto para armazenar a ação (sigla) -->
      <input type="hidden" id="acao" name="acao" value="<?=$sigla?>">
      <!-- Campo oculto para armazenar o modo do formulário (edit, view, etc.) -->
      <input type="hidden" id="mode" name="mode" value="<?=$mode?>">
      <!-- Campo oculto que indica o tipo de pessoa (jurídica ou física, neste caso 2 indica jurídica) -->
      <input type="hidden" name="tipo_pessoa" value="2">

      <!-- Cabeçalho da caixa com um botão de voltar -->
      <div class="box-header with-border">
         <!-- Exibe um botão de voltar que retorna à página anterior ao ser clicado -->
         <h3 class="box-title">
            <button type="button" class="btn btn-primary pull-left" onclick="event.preventDefault();history.back();">
               <i class="fa fa-chevron-left"></i>&nbsp;Voltar
            </button>
         </h3>
      </div><!-- /.box-header -->

      <!-- Corpo da caixa -->
      <div class="box-body">

         <!-- Inclui o arquivo PHP "avisos.php" que contém mensagens de aviso ou erro -->
         <?php include "avisos.php"; ?>

         <!-- Início de um conjunto de abas customizadas (tabs) -->
         <div class="nav-tabs-custom">
            <!-- Lista de abas -->
            <ul id="tabs" class="nav nav-tabs">
               <!-- Aba ativa chamada "Info" -->
               <li class="active"><a href="#tab_1" data-toggle="tab">Info</a></li>
               <!-- Segunda aba chamada "Endereço" -->
               <li><a href="#tab_2" data-toggle="tab">Endereço</a></li>
            </ul>

            <!-- Conteúdo das abas -->
            <div class="tab-content">

               <!-- Conteúdo da aba ativa "Info" -->
               <div class="tab-pane active" id="tab_1">
                  <!-- Grupo de formulário para o campo "Código" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "Código" -->
                     <label for="cod_pessoa" class="col-sm-2 control-label">Código</label>
                     <!-- Campo de texto desabilitado para exibir o código da empresa -->
                     <div class="col-sm-2">
                        <input type="text" class="form-control" id="cod_pessoa" name="cod_pessoa" maxlength="10" size="10" value="<?=$row->cod_empresa?>" disabled/>
                     </div>
                     <!-- Checkbox que indica se a empresa é uma matriz -->
                     <div class="col-sm-3">
                        <div class="checkbox">
                           <label>
                              <input type="checkbox" id="ind_matriz" name="ind_matriz" value="1" <?=iif($row->ind_matriz, "checked", "")?>/> Matriz
                           </label>
                        </div>
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "Razão Social" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "Razão Social" -->
                     <label for="nome_pessoa" class="col-sm-2 control-label">Razão Social</label>
                     <!-- Campo de texto para a razão social da empresa, desabilitado -->
                     <div class="col-sm-10">
                        <input type="text" class="form-control" id="nome_pessoa" name="nome_pessoa" maxlength="50" size="40" value="<?=$row->nome_empresa?>" <?=$disabled?>/>
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "Nome Fantasia" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "Nome Fantasia" -->
                     <label for="nome_fantasia" class="col-sm-2 control-label">Nome Fantasia</label>
                     <!-- Campo de texto para o nome fantasia da empresa -->
                     <div class="col-sm-10">
                        <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" maxlength="50" size="40" value="<?=$row->nome_fantasia?>" />
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "CNPJ" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "CNPJ" -->
                     <label for="id_federal" class="col-sm-2 control-label">CNPJ</label>
                     <!-- Campo de texto desabilitado para o CNPJ da empresa -->
                     <div class="col-sm-3 box-cnpj">
                        <input type="text" class="form-control cnpj" id="id_federal" name="id_federal" maxlength="20" size="20" value="<?=$row->id_federal?>" <?=$disabled?>/>
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "Inscrição Estadual" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "Inscrição Estadual" -->
                     <label for="ie" class="col-sm-2 control-label">Ins. Estadual</label>
                     <!-- Campo de texto para a inscrição estadual da empresa -->
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="ie" name="ie" maxlength="20" size="20" value="<?=$row->ie?>" />
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "Inscrição Municipal" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "Inscrição Municipal" -->
                     <label for="im" class="col-sm-2 control-label">Ins. Municipal</label>
                     <!-- Campo de texto para a inscrição municipal da empresa -->
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="im" name="im" maxlength="20" size="20" value="<?=$row->im?>" />
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "Site" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "Site" -->
                     <label for="site" class="col-sm-2 control-label">Site</label>
                     <!-- Campo de texto para o site da empresa -->
                     <div class="col-sm-10">
                        <input type="text" class="form-control" id="site" name="site" maxlength="150" size="40" value="<?=$row->site?>"/>
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "E-mail" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "E-mail" -->
                     <label for="email" class="col-sm-2 control-label">E-mail</label>
                     <!-- Campo de texto para o e-mail da empresa -->
                     <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email" maxlength="150" size="40" value="<?=$row->email?>"/>
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "Telefone" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "Fone" -->
                     <label for="fone" class="col-sm-2 control-label">Fone</label>
                     <!-- Campo de texto para o telefone da empresa -->
                     <div class="col-sm-3">
                        <input type="text" class="form-control fone" id="fone" name="fone" maxlength="15" size="15" value="<?=$row->fone?>"/>
                     </div>
                  </div>
               </div><!-- /.tab-pane -->

               <!-- Conteúdo da aba "Endereço" -->
               <div class="tab-pane" id="tab_2">
                  <!-- Grupo de formulário para o campo "CEP" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "CEP" -->
                     <label for="cep" class="col-sm-2 control-label">CEP</label>
                     <!-- Campo de texto para o CEP da empresa -->
                     <div class="col-sm-3 box-cep">
                        <input type="text" class="form-control cep" id="cep" name="cep" maxlength="10" size="10" value="<?=$row->cep?>"/>
                        <!-- Link para buscar o CEP caso o usuário não se lembre -->
                        <span><a href="http://www.buscacep.correios.com.br/sistemas/buscacep/buscaCep.cfm" target="_blank">Não se lembra do CEP ?</a></span>
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "Endereço" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "Endereço" -->
                     <label for="endereco" class="col-sm-2 control-label">Endereço</label>
                     <!-- Campo de texto para o endereço da empresa -->
                     <div class="col-sm-10">
                        <input type="text" class="form-control" id="endereco" name="endereco" maxlength="150" size="50" value="<?=$row->endereco?>"/>
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "Número" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "Número" -->
                     <label for="numero" class="col-sm-2 control-label">Número</label>
                     <!-- Campo de texto para o número do endereço -->
                     <div class="col-sm-3">
                        <input type="text" class="form-control numero" id="numero" name="numero" maxlength="11" size="5" value="<?=$row->numero?>"/>
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "Complemento" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "Complemento" -->
                     <label for="complemento" class="col-sm-2 control-label">Complemento</label>
                     <!-- Campo de texto para o complemento do endereço -->
                     <div class="col-sm-4">
                        <input type="text" class="form-control" id="complemento" name="complemento" maxlength="20" size="10" value="<?=$row->complemento?>"/>
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "Bairro" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "Bairro" -->
                     <label for="bairro" class="col-sm-2 control-label">Bairro</label>
                     <!-- Campo de texto para o bairro -->
                     <div class="col-sm-8">
                        <input type="text" class="form-control" id="bairro" name="bairro" maxlength="30" size="30" value="<?=$row->bairro?>"/>
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "Cidade" -->
                  <div class="form-group">
                     <!-- Rótulo do campo "Cidade" -->
                     <label for="cidade" class="col-sm-2 control-label">Cidade</label>
                     <!-- Campo de texto para a cidade -->
                     <div class="col-sm-8">
                        <input type="text" class="form-control" id="cidade" name="cidade" maxlength="30" size="30" value="<?=$row->cidade?>"/>
                     </div>
                  </div>

                  <!-- Grupo de formulário para o campo "UF" (estado) -->
                  <div class="form-group">
                     <!-- Rótulo do campo "UF" -->
                     <label for="uf" class="col-sm-2 control-label">UF</label>
                     <!-- Campo de seleção para o estado (UF) incluindo o arquivo que contém as opções de estados -->
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

      <!-- Rodapé da caixa -->
      <div class="box-footer">
      </div><!-- /.box-footer -->
   </form>
</div><!-- /.box -->
</section><!-- /.content -->



