<?php
require_once("bo/ItemBO.php"); // Inclui a classe de negócio ItemBO

// Verifica se há dados enviados via POST
if($_POST) {
   try {
      // Coleta os dados do formulário
      $cod_item  = $_POST["cod_item"];
      $nome_item = $_POST["nome_item"];
      $tipo      = $_POST["tipo"];

      // Cria uma instância da classe ItemBO
      $item = new ItemBO();

      // Verifica se o modo é "delete" para remover o item
      if($mode == "delete") {
         $item->remove($cod_item, $nome_item);

      // Verifica se o modo é "new" ou "edit" para criar ou editar o item
      } elseif($mode == "new" || $mode == "edit") {

         // Define os dados do item com base no formulário
         $item->setItem($_POST);

         // Se o tipo for '2' (item com variação), configura variáveis adicionais
         if($tipo == '2') $item->setVars();

         // Executa a ação de acordo com o modo (novo ou editar)
         switch($mode) {
            case "new": $item->create(); break; // Cria novo item
            case "edit": $item->update(); break; // Atualiza item existente
         }
      }

      // Verifica se houve erro ao processar o item
      if($item->isError()) {
         throw new Exception($item->getMessage()); // Lança uma exceção com a mensagem de erro
      }

      // Define uma mensagem de sucesso na sessão e redireciona
      $_SESSION["alerta_sucesso"] = $item->getMessage();
      header("Location:index.php?mode=list&acao=".$sigla);
      exit;

   } catch(Exception $e) {
      // Define uma mensagem de erro na sessão em caso de exceção
      $_SESSION["alerta_erro"] = $e->getMessage();
   }
}

// Configura o formulário para edição ou criação de novo item
if($mode == "edit") {
   $titulo = "editar";
   $disabled = "disabled";
   $row = $service->getItem($codigo); // Busca o item a ser editado
   $lista_var = $service->getVariacaoList($codigo); // Busca as variações do item
   $cont_var = count($lista_var); // Conta o número de variações
} else {
   $titulo = "novo";
   $disabled = "";
   $lista_var = null;
   // Configura um objeto vazio para novo item
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

// Obtém a lista de unidades de medida
$lista_un = $service->getItemUnidadeList();
?>

<!-- Cabeçalho da página de conteúdo -->
<section class="content-header">
   <h1><?=$titulo?> <?=$programa->nome_menu?> <small><?=$programa->desc_cad?></small></h1>
</section>

<!-- Conteúdo principal -->
<section class="content">

   <?php include "alertas.php"; // Inclui alertas de erro ou sucesso ?>

   <div class="box box-info">
      <!-- Formulário para cadastro/edição de item -->
      <form id="formCad" class="form-horizontal" method="post" action="<?=ROOT?>" novalidate>
         <!-- Inputs escondidos para acao, modo e quantidade de variações -->
         <input type="hidden" id="acao" name="acao" value="<?=$sigla?>">
         <input type="hidden" id="mode" name="mode" value="<?=$mode?>">
         <input type="hidden" id="tipo_ant" name="tipo_ant" value="<?=$row->tipo?>">
         <input type="hidden" id="qtd_var" name="qtd_var" value="<?=$cont_var?>">

         <!-- Botão Voltar -->
         <div class="box-header with-border">
            <h3 class="box-title"><button type="button" class="btn btn-primary pull-left" onclick="event.preventDefault();history.back();"><i class="fa fa-chevron-left"></i>&nbsp;Voltar</button></h3>
         </div>
         <!-- Corpo da caixa de conteúdo -->
         <div class="box-body">

            <?php include "avisos.php"; // Inclui avisos de validação ?>

            <!-- Campo para o código do item -->
            <div class="form-group">
               <label for="cod_item" class="col-sm-2 control-label">Código</label>
               <div class="col-sm-2">
                  <input type="text" class="form-control" id="cod_item" name="cod_item" maxlength="20" size="10" value="<?=$row->cod_item?>" disabled/>
               </div>
            </div>

            <!-- Campo para o nome do item -->
            <div class="form-group">
               <label for="nome_item" class="col-sm-2 control-label">Nome</label>
               <div class="col-sm-6">
                  <input type="text" class="form-control" id="nome_item" name="nome_item" maxlength="100" size="40" value="<?=$row->nome_item?>"/>
               </div>
            </div>

            <!-- Campo para seleção do tipo de item -->
            <div class="form-group">
               <label for="tipo" class="col-sm-2 control-label">Tipo</label>
               <div class="col-sm-10 radioset">
                  <!-- Tipos: Normal, Com Variação, Com Medida -->
                  <div class="radio"><label><input type="radio" name="tipo" onclick="setTipoItem('1')" value="1" <?=iif($row->tipo == 1, "checked", "")?> />&nbsp;Normal</label></div>
                  <div class="radio"><label><input type="radio" name="tipo" onclick="setTipoItem('2')" value="2" <?=iif($row->tipo == 2, "checked", "")?> />&nbsp;Com Variação</label></div>
                  <div class="radio"><label><input type="radio" name="tipo" onclick="setTipoItem('3')" value="3" <?=iif($row->tipo == 3, "checked", "")?> />&nbsp;Com Medida</label></div>
               </div>
            </div>

            <!-- Campo para a medida do item -->
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
                     // Popula a lista de unidades com as opções disponíveis
                     foreach($lista_un as $key => $value) {
                        echo '<option value="'.$value.'" '.iif($row->un == $value, 'selected', '').'>'.$value.'</option>';
                     }
                     ?>
                  </select>
               </div>
            </div>

            <!-- Campo para o valor de custo -->
            <div class="form-group campoValor">
               <label for="val_custo" class="col-sm-2 control-label">Valor Custo</label>
               <div class="col-sm-2">
                  <input type="text" class="form-control numeric" id="val_custo" name="val_custo" maxlength="50" size="40" value="<?=invertDec($row->val_custo, '')?>"/>
               </div>
            </div>

            <!-- Campo para o valor de venda -->
            <div class="form-group campoValor">
               <label for="val_ref" class="col-sm-2 control-label">Valor Venda</label>
               <div class="col-sm-2">
                  <input type="text" class="form-control numeric" id="val_ref" name="val_ref" maxlength="50" size="40" value="<?=invertDec($row->val_ref, '')?>"/>
               </div>
            </div>

            <!-- Checkbox para controle de estoque -->
            <div class="form-group">
               <label class="col-sm-2 control-label">&nbsp;</label>
               <div class="col-sm-3">
                  <div class="checkbox">
                     <label><input type="checkbox" id="controla_estoque" name="controla_estoque" value="1" <?=iif($row->controla_estoque, "checked", "")?>/> Controla estoque</label>
                  </div>
               </div>
            </div>

            <br><br>

            <!-- Botões de ação -->
            <div class="form-group">
               <label class="col-sm-2 control-label">&nbsp;</label>
               <div class="col-sm-10">
                  <button type="submit" id="btnSubmit" class="btn btn-primary">&nbsp;&nbsp;Salvar&nbsp;&nbsp;</button>
               </div>
            </div>
         </div>
      </form>
   </div>
</section>

<script>
// Função para configurar o tipo de item selecionado (Normal, Com Variação, Com Medida)
function setTipoItem(tipo) {
   $(".campoMedida").hide(); // Esconde o campo de medida
   if(tipo == "3") $(".campoMedida").show(); // Mostra o campo de medida se o tipo for 3
}
</script>
