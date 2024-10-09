<?php
// Indica se há um erro (inicializado como falso)
$erro = false;

// Obtém os parâmetros de configuração a partir do código passado
$row = $service->getParam($codigo);

// Define o título da página como "editar" e desativa os campos de entrada
$titulo = "editar";
$disabled = "disabled";

// Divide a string de dias úteis armazenada no banco de dados em um array
$ar_dias_uteis = explode(',', $row->dias_uteis);
?>

<!-- Cabeçalho da página de conteúdo -->
<section class="content-header">
   <!-- Mostra o título e a descrição do menu -->
   <h1><?=$titulo?> <?=$programa->nome_menu?> <small><?=$programa->desc_list?></small></h1>
</section>

<!-- Conteúdo principal -->
<section class="content">

   <div class="box box-info">
      <!-- Formulário para edição de parâmetros -->
      <form id="formCad" class="form-horizontal" enctype="multipart/form-data" method="post" action="<?=ROOT?>" novalidate>

         <!-- Botão Voltar -->
         <div class="box-header with-border">
            <h3 class="box-title">
               <button type="button" class="btn btn-primary pull-left" onclick="event.preventDefault();history.back();">
                  <i class="fa fa-chevron-left"></i>&nbsp;Voltar
               </button>
            </h3>
         </div>

         <!-- Corpo da caixa de conteúdo -->
         <div class="box-body">

            <?php include "avisos.php"; // Inclui avisos de validação ou erros ?>

            <!-- Seção Geral -->
            <div class="row">
               <div class="col-sm-12">
                  <div class="cad-filho nav-tabs-custom cad-blue">
                     <ul id="tabs1" class="nav nav-tabs">
                        <!-- Aba Geral -->
                        <li class="tab active"><a href="#tab_1" data-toggle="tab">Geral</a></li>
                     </ul>

                     <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                           <!-- Campo Empresa -->
                           <div class="form-group">
                              <label for="cod_empresa" class="col-sm-2 control-label">Empresa</label>
                              <div class="col-sm-6">
                                 <!-- Exibe o nome da empresa e um campo hidden para o código -->
                                 <input type="text" class="form-control" id="nome_empresa" name="nome_empresa" size="40" value="<?=$row->nome_empresa?>" <?=$disabled?>/>
                                 <input type="hidden" id="cod_empresa" name="cod_empresa" value="<?=$row->cod_empresa?>">
                              </div>
                           </div>

                           <!-- Campos de Horário de Expediente -->
                           <div class="form-group">
                              <label for="expediente" class="col-sm-2 control-label">Expediente</label>
                              <div class="col-sm-3">
                                 <!-- Campo de hora inicial do expediente -->
                                 <div class="input-group">
                                    <input type="text" class="form-control input-sm timepicker" id="expediente_ini" name="expediente_ini" maxlength="10" size="10" value="<?=$row->expediente_ini?>" />
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-info btn-flat btn-sm" onclick="$('#expediente_ini').focus();">
                                          <i class="fa fa-clock-o"></i>
                                       </button>
                                    </span>
                                 </div>
                              </div>

                              <!-- Campo de hora final do expediente -->
                              <div class="col-sm-3">
                                 <div class="input-group">
                                    <input type="text" class="form-control input-sm timepicker" id="expediente_fin" name="expediente_fin" maxlength="10" size="10" value="<?=$row->expediente_fin?>" />
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-info btn-flat btn-sm" onclick="$('#expediente_fin').focus();">
                                          <i class="fa fa-clock-o"></i>
                                       </button>
                                    </span>
                                 </div>
                              </div>
                           </div>

                           <!-- Campos de Intervalo -->
                           <div class="form-group">
                              <label for="intervalo" class="col-sm-2 control-label">Intervalo</label>
                              <div class="col-sm-3">
                                 <!-- Campo de início do intervalo -->
                                 <div class="input-group">
                                    <input type="text" class="form-control input-sm timepicker" id="intervalo_ini" name="intervalo_ini" maxlength="10" size="10" value="<?=$row->intervalo_ini?>" />
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-info btn-flat btn-sm" onclick="$('#intervalo_ini').focus();">
                                          <i class="fa fa-clock-o"></i>
                                       </button>
                                    </span>
                                 </div>
                              </div>

                              <!-- Campo de fim do intervalo -->
                              <div class="col-sm-3">
                                 <div class="input-group">
                                    <input type="text" class="form-control input-sm timepicker" id="intervalo_fin" name="intervalo_fin" maxlength="10" size="10" value="<?=$row->intervalo_fin?>" />
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-info btn-flat btn-sm" onclick="$('#intervalo_fin').focus();">
                                          <i class="fa fa-clock-o"></i>
                                       </button>
                                    </span>
                                 </div>
                              </div>
                           </div>

                           <!-- Checkbox para selecionar os dias úteis -->
                           <div class="form-group">
                              <label for="dias_uteis" class="col-sm-2 control-label">Dias Úteis</label>
                              <div class="col-sm-3">
                                 <!-- Dias da semana com checkbox para marcar os dias úteis -->
                                 <div class="checkbox"><label><input type="checkbox" name="dias_uteis[]" value="0" <?=iif(in_array('0', $ar_dias_uteis), 'checked', '')?>>&nbsp;DOM</label></div>
                                 <div class="checkbox"><label><input type="checkbox" name="dias_uteis[]" value="1" <?=iif(in_array('1', $ar_dias_uteis), 'checked', '')?>>&nbsp;SEG</label></div>
                                 <div class="checkbox"><label><input type="checkbox" name="dias_uteis[]" value="2" <?=iif(in_array('2', $ar_dias_uteis), 'checked', '')?>>&nbsp;TER</label></div>
                                 <div class="checkbox"><label><input type="checkbox" name="dias_uteis[]" value="3" <?=iif(in_array('3', $ar_dias_uteis), 'checked', '')?>>&nbsp;QUA</label></div>
                                 <div class="checkbox"><label><input type="checkbox" name="dias_uteis[]" value="4" <?=iif(in_array('4', $ar_dias_uteis), 'checked', '')?>>&nbsp;QUI</label></div>
                                 <div class="checkbox"><label><input type="checkbox" name="dias_uteis[]" value="5" <?=iif(in_array('5', $ar_dias_uteis), 'checked', '')?>>&nbsp;SEX</label></div>
                                 <div class="checkbox"><label><input type="checkbox" name="dias_uteis[]" value="6" <?=iif(in_array('6', $ar_dias_uteis), 'checked', '')?>>&nbsp;SAB</label></div>
                              </div>
                           </div>
                        </div><!-- /.tab-pane -->
                     </div><!-- /.tab-content -->
                  </div><!-- /.nav-tabs-custom -->
               </div><!-- /.col-sm-12 -->
            </div><!-- /.row -->
         </div><!-- /.box-body -->

         <!-- Botões de ação -->
         <div class="box-footer">
         </div><!-- /.box-footer -->

         <!-- Inputs escondidos para acao e modo -->
         <input type="hidden" id="acao" name="acao" value="<?=$sigla?>">
         <input type="hidden" id="mode" name="mode" value="<?=$mode?>">

      </form>
   </div><!-- /.box -->

   <!-- Mostra a data de implantação do sistema -->
   <h6 class="pull-right">Implantado em <?=invertDate($row->dt_impl_sis, "")?></h6>

</section><!-- /.content -->
