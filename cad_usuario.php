<?php

$lista_prog = $service->getProgramaList('');

if($_POST) {

   $usuario = (object)[
      'cod_empresa'     => $_POST["cod_empresa"],
      'cod_usuario'     => $_POST["cod_usuario"],
      'login'           => $_POST["login"],
      'nome_usuario'    => $_POST["nome_usuario"],
      'cod_perfil'      => $_POST["cod_perfil"][0],
      'senha'           => $_POST["senha"],
      'lista_programa'  => $_POST['cod_programa'],
      'foto'            => '',
   ];

   switch($mode) {
      case "new":
         $retorno = $service->newUsuario($usuario);
         $retorno = $service->setProgramaListByUsuario($usuario->cod_empresa, $usuario->login, $usuario->lista_programa);
         $msg_sistema = "Usuário <b>".$usuario->nome_usuario."</b> cadastrado com sucesso";
      break;
      case "edit":
         $retorno = $service->updateUsuario($usuario);
         $retorno = $service->setProgramaListByUsuario($usuario->cod_empresa, $usuario->login, $usuario->lista_programa);
         $msg_sistema = "Usuário <b>".$usuario->nome_usuario."</b> alterado com sucesso";
      break;
      case "delete":
         $retorno = $service->deleteUsuario($usuario->cod_empresa, $usuario->login);
         $retorno = $service->deleteProgramaListByUsuario($usuario->cod_empresa, $usuario->login);
         $msg_sistema = "Usuário <b>".$usuario->nome_usuario."</b> excluido com sucesso";
      break;
   }

   $_SESSION["alerta_sucesso"] = $msg_sistema;
   header("Location:index.php?mode=list&acao=".$sigla);
   exit;
}

if($mode == "edit") {
   $titulo = "editar";
   $disabled = "disabled";
   $ar_codigo = explode("|", $codigo); // captura o codigo concatenado por |
   $cod_empresa = $ar_codigo[0];
   $login = $ar_codigo[1];
   // busca o registro
   $row = $service->getUsuarioByLogin($login);
   $row->lista_progr = $service->getProgramaListByUsuario($row->cod_empresa, $row->login);
} else {
   $titulo = "novo";
   $disabled = "";
   $row = (object)[
      'cod_empresa'  => $empresa->cod_empresa,
      'cod_usuario'  => '',
      'login'        => '',
      'nome_usuario' => '',
      'senha'        => '',
      'cod_perfil'   => '0',
      'lista_progr'  => array(),
   ];
}

?>

<!-- Content Header (Page header) -->
<section class="content-header">
   <h1><?=$titulo?> <?=$programa->nome_menu?> <small><?=$programa->desc_cad?></small></h1>
</section>

<!-- Main content -->
<section class="content">
   <div class="box box-info">
      <form id="formCad" class="form-horizontal" method="post" action="<?=ROOT?>" autocomplete="off" novalidate>
         <input type="hidden" id="acao" name="acao" value="<?=$sigla?>">
         <input type="hidden" id="mode" name="mode" value="<?=$mode?>">
         <input type="hidden" id="cod_empresa" name="cod_empresa" value="<?=$row->cod_empresa?>">
         <input type="hidden" name="cod_usuario" value="<?=$row->cod_usuario?>">

         <div class="box-header with-border">
            <h3 class="box-title"><button type="button" class="btn btn-primary pull-left" onclick="event.preventDefault();history.back();"><i class="fa fa-chevron-left"></i>&nbsp;Voltar</button></h3>
         </div><!-- /.box-header -->
         <div class="box-body">

            <?php include "avisos.php"; ?>

            <!-- INFO -->
            <div class="row">
               <div class="col-sm-12">
                  <div class="cad-filho nav-tabs-custom cad-orange">
                     <ul id="tabs1" class="nav nav-tabs">
                        <li class="tab active"><a href="#tab_1" data-toggle="tab">Info</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">

                           <div class="form-group">
                              <label for="login" class="col-sm-2 control-label">Login</label>
                              <div class="col-sm-3">
                                 <input type="text" class="form-control" id="login" name="login" maxlength="20" size="10" value="<?=$row->login?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" <?=$disabled?> />
                              </div>
                              <div class="col-sm-4">
                                 <span id="msg_login" class="text-red"></span>
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="nome_usuario" class="col-sm-2 control-label">Nome</label>
                              <div class="col-sm-10">
                                 <input type="text" class="form-control" id="nome_usuario" name="nome_usuario" maxlength="50" size="40" value="<?=$row->nome_usuario?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="senha" class="col-sm-2 control-label"><?=iif($mode == "edit", "Nova ", "")?>Senha</label>
                              <div class="col-sm-3">
                                 <input type="password" class="form-control" id="senha" name="senha" maxlength="50" size="20" value="<?=$row->senha?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"/>
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="cod_perfil" class="col-sm-2 control-label">Perfil</label>
                              <div class="col-sm-10 radioset">
                                 <?php
                                 if(PERFIL == -1) {
                                    echo '<div class="radio-inline"><label><input type="radio" name="cod_perfil[]" value="-1" '.iif($row->cod_perfil == -1, "checked", "").' />&nbsp;Root</label></div>';
                                 }
                                 if(PERFIL <= 1) {
                                    echo '<div class="radio-inline"><label><input type="radio" name="cod_perfil[]" value="1" '.iif($row->cod_perfil == 1, "checked", "").' />&nbsp;Administrador</label></div>';
                                 }
                                 ?>
                                 <div class="radio-inline"><label><input type="radio" name="cod_perfil[]" value="2" <?=iif($row->cod_perfil == 2, "checked", "")?> />&nbsp;Financeiro</label></div>
                                 <div class="radio-inline"><label><input type="radio" name="cod_perfil[]" value="3" <?=iif($row->cod_perfil == 3, "checked", "")?> />&nbsp;Atendente</label></div>
                                 <div class="radio-inline"><label><input type="radio" name="cod_perfil[]" value="4" <?=iif($row->cod_perfil == 4, "checked", "")?> />&nbsp;Administrativo</label></div>
               
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-2 control-label">Permissão</label>
                              <?php
                              if($row->cod_perfil == -1) {
                                 echo '<p>Acesso sem restrição</p>';
                              } else {
                                 $tipo_prog = '';
                                 foreach($lista_prog as $programa) {
                                    if($tipo_prog != $programa->ind_tipo) {
                                       if($tipo_prog != '') echo '</div>';
                                       $tipo_prog = $programa->ind_tipo;
                                       echo '<div class="col-md-2" style="width:initial;">'.
                                          '<u><b>'.$service->getDescricaoTipoPrograma($programa->ind_tipo).'</b></u>';
                                    }
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
            <button type="button" class="btn btn-success pull-left submit" onclick="validForm()"><i class="fa fa-check"></i>&nbsp;Salvar</button>
            <?php
            if(PERFIL <= 1 /*Administrador*/ && $mode == "edit") {
               echo '<button type="button" class="btn btn-danger pull-right" onclick="NUrl.deleteCad(\''.$programa->nome_programa.'\',\''.$row->login.'\')"><i class="fa fa-trash"></i>&nbsp;Excluir</button>';
            }
            ?>
         </div><!-- /.box-footer -->
      </form>
   </div><!-- /.box -->
</section><!-- /.content -->

<script>
   var form = document.getElementById('formCad');

   function validForm() {
      var modo = '<?=$mode?>',
          campo = null,
          erro = false;

      event.preventDefault();
      Shell.clearAvisos();

      campo = document.getElementById('login');
      if(modo == 'new' && campo.value == '') {
         erro = true;
         Shell.addAvisoErro("Informe o Login para o acesso.");
         campo.focus();
      }

      campo = document.getElementById('nome_usuario');
      if(campo.value == '') {
         erro = true;
         Shell.addAvisoErro("Informe o Nome do Usuário.");
         campo.focus();
      }

      campo = document.getElementById('senha');
      if(modo == 'new' && campo.value == '') {
         erro = true;
         Shell.addAvisoErro("Informe uma Senha de acesso.");
         campo.focus();
      }

      if(erro) {
         Shell.showAvisoErro();
         return false;
      }

      Shell.disableEditingWarning();
      form.submit();
      return true;
   }

   $(document).ready(function() {
      Shell.editingWarning();
   });
</script>
