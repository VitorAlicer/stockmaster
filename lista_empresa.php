<?php

// Inicializa a variável $search como uma string vazia.
$search = '';

// Verifica se existe um parâmetro 'search' na requisição. Se existir e não estiver vazio, atribui seu valor à variável $search.
if (!empty($_REQUEST["search"])) $search = $_REQUEST["search"];

// Chama o método getEmpresaList do objeto $service, passando o valor da variável $search como parâmetro, e armazena o resultado na variável $lista.
$lista = $service->getEmpresaList($search);

?>

<!-- Content Header -->
<section class="content-header">
   <!-- Exibe o nome do programa e a descrição da lista utilizando a variável $programa -->
   <h1><?=$programa->nome_menu?> <small><?=$programa->desc_list?></small></h1>
</section>

<!-- Main Content -->
<section class="content">

   <!-- Inclui o arquivo 'alertas.php' que provavelmente contém código para exibir mensagens de alerta ou notificação. -->
   <?php include "alertas.php"; ?>

   <div class="box">

      <div class="box-header">
         <h3 class="box-title">
         </h3>
         <div class="box-tools">
            <!-- Formulário para filtrar a lista de empresas. -->
            <form id="formFiltra" method="get" action="<?=ROOT?>">
               <!-- Campos ocultos que definem a ação e o modo do formulário. -->
               <input type="hidden" name="acao" value="<?=$sigla?>"/>
               <input type="hidden" name="mode" value="list"/>
               <div class="input-group input-group-sm search">
                  <!-- Campo de entrada para pesquisa, preenchido com o valor da variável $search. -->
                  <input type="text" id="search" name="search" class="form-control pull-right" placeholder="Procurar..." value="<?=$search?>">
                  <div class="input-group-btn">
                     <!-- Botão para enviar o formulário de pesquisa. -->
                     <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
               </div>
            </form>
         </div>
      </div><!-- /.box-header -->

      <div class="box-body table-responsive no-padding">
         <table class="table table-hover sort">
            <tbody>
            <!-- Cabeçalho da tabela com os títulos das colunas. -->
            <tr class="head-list">
               <th>codigo</th>
               <th>razão social</th>
               <th>cnpj</th>
               <th>&nbsp;</th>
               <th>&nbsp;</th>
            </tr>
            <?php
            // Itera sobre a lista de empresas e cria uma linha na tabela para cada empresa.
            foreach($lista as $row) {
            ?>
               <tr class="hand" onclick="NUrl.viewCad('<?=$sigla?>','<?=$row->cod_empresa?>')">
                  <!-- Exibe o código da empresa, com formatação para a cor azul. -->
                  <td width="1%" class="text-right"><span class="text-light-blue"><?=$row->cod_empresa?></span></td>
                  <!-- Exibe o nome da empresa, também formatado. -->
                  <td width="*"><span class="text-light-blue"><?=$row->nome_empresa?></span></td>
                  <!-- Exibe o CNPJ da empresa. -->
                  <td width="20%"><span class="cnpj text-light-blue"><?=$row->id_federal?></span></td>
                  <!-- Condicional para verificar se a empresa é matriz, exibindo um rótulo se verdadeiro. -->
                  <td width="1%"><?=iif($row->ind_matriz, '<span class="label pull-right bg-blue">MATRIZ</span>', '&nbsp;')?></td>
                  <!-- Ícone para indicar que a linha é clicável e que leva a um detalhe da empresa. -->
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
