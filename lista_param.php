<?php

// Inicializa a variável $search como uma string vazia.
$search = '';
// Verifica se o parâmetro "search" da requisição não está vazio.
// Se não estiver, atribui o valor desse parâmetro à variável $search.
if (!empty($_REQUEST["search"])) $search = $_REQUEST["search"];
// Chama o método getParamList do objeto $service, passando a variável $search como argumento,
// e armazena o resultado na variável $lista, que conterá a lista de parâmetros filtrados.
$lista = $service->getParamList($search);

?>

<!-- Header do Conteúdo -->
<section class="content-header">
   <!-- Exibe o nome do menu e a descrição da lista. -->
   <h1><?=$programa->nome_menu?> <small><?=$programa->desc_list?></small></h1>
</section>

<!-- Conteúdo Principal -->
<section class="content">

   <!-- Inclui o arquivo "alertas.php" para mostrar possíveis alertas. -->
   <?php include "alertas.php"; ?>

   <div class="box">

      <!-- Cabeçalho da caixa -->
      <div class="box-header">
         <h3 class="box-title">
         </h3>
         <div class="box-tools">
            <!-- Formulário de pesquisa -->
            <form id="formFiltra" method="get" action="<?=ROOT?>">
               <!-- Campos ocultos para enviar a ação e o modo -->
               <input type="hidden" name="acao" value="<?=$sigla?>"/>
               <input type="hidden" name="mode" value="list"/>
               <div class="input-group input-group-sm search">
                  <!-- Campo de texto para entrada de pesquisa -->
                  <input type="text" id="search" name="search" class="form-control pull-right" placeholder="Procurar..." value="<?=$search?>">
                  <div class="input-group-btn">
                     <!-- Botão de envio para pesquisa -->
                     <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
               </div>
            </form>
         </div>
      </div><!-- /.box-header -->

      <!-- Corpo da caixa com a tabela -->
      <div class="box-body table-responsive no-padding">
         <!-- Tabela com a lista de parâmetros -->
         <table class="table table-hover sort">
            <tbody>
            <!-- Cabeçalho da tabela -->
            <tr class="head-list">
            <th>cod</th> <!-- Coluna para código -->
            <th>razão social</th> <!-- Coluna para razão social -->
            <th>&nbsp;</th> <!-- Coluna vazia -->
            <th>&nbsp;</th> <!-- Coluna vazia -->
            </tr>
            <?php
            // Loop através da lista de resultados e cria uma linha na tabela para cada item.
            foreach($lista as $row) {
            ?>
               <tr class="hand" onclick="NUrl.viewCad('<?=$sigla?>','<?=$row->cod_empresa?>')">
                  <!-- Célula para o código da empresa -->
                  <td width="8" class="text-right"><span class="text-light-blue"><?=$row->cod_empresa?></span></td>
                  <!-- Célula para o nome da empresa -->
                  <td width="*"><span class="text-light-blue"><?=$row->nome_empresa?></span></td>
                  <!-- Célula para mostrar se é uma matriz -->
                  <td width="1%"><?=iif($row->ind_matriz, '<span class="label pull-right bg-blue">MATRIZ</span>', '&nbsp;')?></td>
                  <!-- Célula com ícone de seta -->
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
