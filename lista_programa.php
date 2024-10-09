<?php

// Inicializa a variável $search como uma string vazia.
$search = '';
// Verifica se o parâmetro "search" da requisição não está vazio.
// Se não estiver, atribui o valor desse parâmetro à variável $search.
if (!empty($_REQUEST["search"])) $search = $_REQUEST["search"];
// Chama o método getProgramaList do objeto $service, passando a variável $search como argumento,
// e armazena o resultado na variável $lista, que conterá a lista de programas filtrados.
$lista = $service->getProgramaList($search);

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
            <!-- Botão para criar um novo programa -->
            <button type="button" class="btn btn-success" onclick="NUrl.newCad('<?=$sigla?>')"><i class="fa fa-plus-circle"></i>&nbsp;<?="novo"?></label>
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
         <!-- Tabela com a lista de programas -->
         <table class="table table-hover sort">
            <tbody>
            <!-- Cabeçalho da tabela -->
            <tr class="head-list">
            <th>nome</th> <!-- Coluna para nome do programa -->
            <th class="text-center">tipo</th> <!-- Coluna para tipo do programa -->
            <th>sigla</th> <!-- Coluna para sigla do programa -->
            <th class="text-center">ordem</th> <!-- Coluna para ordem do programa -->
            <th>&nbsp;</th> <!-- Coluna vazia -->
            </tr>
            <?php
            // Loop através da lista de resultados e cria uma linha na tabela para cada item.
            foreach($lista as $row) {
               // Verifica se o programa está ativo; se não estiver, define a cor como cinza.
               if(!$row->ind_ativo)
                  $cor = '#D8D8D8';
               else {
                  // Define a cor de fundo com base no tipo do programa.
                  switch($row->ind_tipo) {
                     case 1:  $cor = '#A9E2F3'; break; // Tipo 1
                     case 2:  $cor = '#F2F5A9'; break; // Tipo 2
                     case 3:  $cor = '#D0A9F5'; break; // Tipo 3
                     case 4:  $cor = '#F5A9BC'; break; // Tipo 4
                     case 8:  $cor = '#BCF5A9'; break; // Tipo 8
                     default: $cor = '#FFFFFF'; // Padrão
                  }
               }
            ?>
               <!-- Linha da tabela com cor de fundo e ação de clique -->
               <tr style="background-color:<?=$cor?>;" class="hand" onclick="NUrl.viewCad('<?=$sigla?>','<?=$row->cod_programa?>')">
                  <!-- Célula para o nome do programa -->
                  <td width="*"><span class="text-light-blue"><?=$row->nome_programa?></span></td>
                  <!-- Célula para o tipo do programa -->
                  <td width="5%"><span class="label pull-right bg-blue"><?=$service->getDescricaoTipoPrograma($row->ind_tipo)?></span></td>
                  <!-- Célula para a sigla do programa -->
                  <td width="5%"><code><?=$row->sigla?></code></td>
                  <!-- Célula para a ordem do programa -->
                  <td width="1%"><span class="pull-right badge bg-blue"><?=$row->ordem?></span></td>
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

   </div>

</section><!-- /.content -->
