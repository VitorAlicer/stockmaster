<?php
require_once("init.php");
require_once("inc/functions.php");
require_once("lib/Service.php");


$sessao = new stdClass();
$usuario = new stdClass();
$empresa = new stdClass();

try{
   $acao = '';
   $mode = '';
   $codigo = '';
   $ext = '';

   session_start();
   $sessao = $_SESSION["sessao"];
   if(!isset($sessao)){
      header("location:login.php");
      exit;
   }

   if ($_REQUEST) {
      $acao = $_REQUEST["acao"]; 
      if(!empty($_REQUEST["mode"])) $mode = $_REQUEST["mode"]; 
      if(!empty($_REQUEST["cod"])) $codigo = $_REQUEST["cod"]; 
      if(!empty($_REQUEST["ext"])) $ext = $_REQUEST["ext"]; 
   }

   $service = new Service();
   $usuario = $sessao->usuario;
   $empresa = $sessao->empresa;
   $param = $service->getParam($empresa->cod_empresa == "0" ? null : $empresa->cod_empresa);
   $usuario->nome_perfil = $service->getNomePerfil($usuario->cod_perfil);

 
   define("ROOT"   , $param->raiz);
   define("UPLOAD" , $param->upload);
   define("USUARIO", $usuario->nome_usuario);
   define("PERFIL" , $usuario->cod_perfil);

   if(PERFIL == -1) 
        $usuario_menu = $service->getProgramaList('');
   else $usuario_menu = $service->getMenuByUsuario($usuario->cod_empresa, $usuario->login);

   
   $usuario->perm_menu = $service->getProgramaListByUsuario($usuario->cod_empresa, $usuario->login);
   

   
   $usuario->url_foto = $service->getURLFoto($param, $usuario->foto);

  
   if(DOMAIN != "" && DOMAIN != "localhost" && filter_var(DOMAIN, FILTER_VALIDATE_IP) !== false) {
      $logo = DOMAIN."logo.png";
      $logo_mini = DOMAIN."logo-mini.png";
   } else {
      $logo = "logo.png";
      $logo_mini = "logo-mini.png";
   }

   function getQty($sigla) {
      global $service, $empresa;
      switch($sigla) {
         case 'empresa':         return $service->getEmpresaQty();
         case 'item':            return $service->getItemQty();
         case 'param':           return $service->getParamQty();
         case 'programa':        return $service->getProgramaQty();
         case 'usuario':         return $service->getUsuarioQty($empresa->cod_empresa);
      }
   }

?>
<!DOCTYPE html>
<html>
<head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta charset="utf-8">
   <title><?=$empresa->nome_empresa?> - <?=SISVER?></title>
   <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <link rel="stylesheet" href="assets/adminLTE/plugins/jQueryUI/jquery-ui.min.css">
   <link rel="stylesheet" href="assets/adminLTE/bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="assets/ionicons/css/ionicons.min.css">
   <link rel="stylesheet" href="assets/adminLTE/plugins/timepicker/jquery.timepicker.min.css">
   <link rel="stylesheet" href="assets/adminLTE/plugins/datepicker/datepicker3.css">
   <link rel="stylesheet" href="assets/adminLTE/plugins/daterangepicker/daterangepicker.css">
   <link rel="stylesheet" href="assets/adminLTE/plugins/select2/select2.min.css">
   <link rel="stylesheet" href="assets/adminLTE/plugins/datatables/dataTables.bootstrap.css">
   <link rel="stylesheet" href="assets/adminLTE/plugins/colorpicker/css/bootstrap-colorpicker.min.css">
   <link rel="stylesheet" href="assets/adminLTE/dist/css/AdminLTE.min.css">
   <link rel="stylesheet" href="assets/adminLTE/dist/css/skins/skin-blue.min.css">
   <link rel="stylesheet" href="assets/adminLTE/plugins/chosen/chosen.min.css">
   <link rel="stylesheet" href="assets/dropzone/min/dropzone.min.css">
   <link rel="stylesheet" href="assets/sm.css">
   <link rel="icon" type="image/png" href="img/favicon.png">
   <script src="assets/adminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
   <script src="assets/adminLTE/plugins/jQueryUI/jquery-ui.min.js"></script>
   <script src="assets/adminLTE/bootstrap/js/bootstrap.min.js"></script>
   <script src="assets/adminLTE/dist/js/app.min.js"></script>
   <script src="assets/adminLTE/plugins/validate/jquery.validate.js"></script>
   <script src="assets/adminLTE/plugins/validate/additional-methods.js"></script>
   <script src="assets/adminLTE/plugins/validate/localization/messages_pt_BR.js"></script>
   <script src="assets/adminLTE/plugins/input-mask/inputmask.min.js"></script>
   <script src="assets/adminLTE/plugins/input-mask/inputmask.date.extensions.min.js"></script>
   <script src="assets/adminLTE/plugins/input-mask/inputmask.numeric.extensions.min.js"></script>
   <script src="assets/adminLTE/plugins/input-mask/inputmask.extensions.min.js"></script>
   <script src="assets/adminLTE/plugins/input-mask/jquery.inputmask.min.js"></script>
   <script src="assets/adminLTE/plugins/colorpicker/js/bootstrap-colorpicker.min.js"></script>
   <script src="assets/adminLTE/plugins/timepicker/jquery.timepicker.min.js"></script>
   <script src="assets/adminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
   <script src="assets/adminLTE/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js"></script>
   <script src="assets/adminLTE/plugins/daterangepicker/moment.min.js"></script>
   <script src="assets/adminLTE/plugins/daterangepicker/daterangepicker.js"></script>
   <script src="assets/adminLTE/plugins/select2/select2.full.min.js"></script>
   <script src="assets/adminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
   <script src="assets/adminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
   <script src="assets/adminLTE/plugins/chosen/chosen.jquery.min.js"></script>
   <script src="assets/dropzone/min/dropzone.min.js"></script>
   <script src="assets/accounting.min.js"></script>
   <script src="assets/sm.js<?='?ts='.$sessao->ts?>"></script>
   <!--[if lt IE 9]>
   <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
   <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
   <![endif]-->
   <script type="text/javascript">
      Shell.urlRoot += "<?=ROOT?>";
   </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
   <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">

         <!-- Logo -->
         <a href="index.php" class="logo">
            <span class="logo-mini"><img src="img/<?=$logo_mini?>"></span>
            <span class="logo-lg"><img src="img/<?=$logo?>"></span>
         </a>

         <!-- Header Navbar -->
         <nav class="navbar navbar-static-top" role="navigation">

            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
               <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-left">
               <ul class="nav navbar-nav">
                  <li><a><?=$empresa->nome_empresa?></a></li>
               </ul>
            </div>

            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
               <ul class="nav navbar-nav">

                  <!-- User Account Menu -->
                  <li class="dropdown user user-menu">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?=$usuario->url_foto?>" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?=$usuario->nome_usuario?></span>&nbsp;
                        <!-- idioma -->
                        <img class="flag-idioma" src="/img/br.svg" title="Português" width="18"/>
                     </a>
                     <ul class="dropdown-menu">
                        <li class="user-header">
                           <img src="<?=$usuario->url_foto?>" class="img-circle" alt="User Image">
                           <p>
                              <span><?=$usuario->nome_usuario?></span>
                              <small><i><?=$usuario->nome_perfil?></i></small>
                           </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                           <div class="pull-left"><a href="?acao=perfil" class="btn btn-default btn-flat">Perfil</a></div>
                           <div class="pull-right"><a href="logout.php" class="btn btn-default btn-flat">Sair</a></div>
                        </li>
                     </ul>
                  </li>
               </ul>
            </div>
         </nav>
      </header>

      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
         <section class="sidebar">
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
               <li><a href="?acao=home"><i class="fa fa-dashboard text-mediumorchid"></i> <span>Dashboard</span></a></li>
               <?php
               $tipo_prog = '';
               foreach($usuario_menu as $progr) {

                  if($progr->ind_ativo == '0') continue;

                  // break by ind_tipo
                  if($tipo_prog != $progr->ind_tipo) {
                     $tipo_prog = $progr->ind_tipo;
                     echo '<li class="header">'.mb_strtoupper($service->getDescricaoTipoPrograma($progr->ind_tipo)).'</li>';
                  }

                  $mode_prefix = "list";
                  if($progr->ind_tipo == "9") $mode_prefix = "api";

                  echo '<li>'.
                       ' <a href="?mode='.$mode_prefix.'&acao='.$progr->sigla.'">'.
                       '  <i class="fa '.$progr->icone_menu.'"></i> '.
                       '  <span>'.$progr->nome_menu;
                  if($progr->ind_qtd) {
                     echo '<span class="pull-right-container"><span class="label pull-right bg-blue">'.getQty($progr->sigla).'</span></span>';
                  }
                  echo '  </span>'.
                       ' </a>'.
                       '</li>';
               }
               ?>
            </ul><!-- /.sidebar-menu -->
         </section><!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
         <div class="row" style="margin:0">
         <?php

         // monta string do caminho do programa
         if($acao == '') $acao = 'home';
         $sigla = $acao;

         switch($mode) {
            case "filter":
            case "list":
               $path = "lista_".$acao.".php";
            break;
            case "new":
            case "edit":
            case "delete":
            case "cancel":
               $path = "cad_".$acao.".php";
            break;
            case "run":
               $path = $acao.".php";
            break;
            default: $path = $acao.".php";
         }
         $path = "./".$path;

         
         $programa = '';
         if(file_exists($path)) {
            $programa = $service->getProgramaBySigla($sigla);
            include $path;
         } else {
            include "erro_programa.php";
         }

         ?>
         </div>
      </div><!-- /.content-wrapper -->

      <!-- Main Footer -->
      <footer class="main-footer no-print">
         <div class="row">
            <div class="col-sm-8 text-left">
               <strong>Copyright &copy; 2019 <a href="<?=$empresa->site?>" target="_blank"><?=$empresa->nome_empresa?></a></strong><br>
               <small>Todos Direitos Reservados.</small>
            </div>
         </div>
      </footer>

      <div class="control-sidebar-bg"></div>
   </div><!-- ./wrapper -->

   <script>
      $(function () {
         if(localStorage) {
            // save sidebar state
            if(localStorage.expandedMenu == 0) {
               $("body").addClass('sidebar-collapse');
            }
            $('body').bind('expanded.pushMenu', function() {
               localStorage.expandedMenu = 1;
            });
            $('body').bind('collapsed.pushMenu', function() {
               localStorage.expandedMenu = 0;
            });
            if(localStorage.controlMenu == undefined) localStorage.controlMenu = 0;
            $('.control-sidebar-tabs li').click(function() {
               localStorage.controlMenu = $('.control-sidebar-tabs li').index(this);
            });
         }
      });

      $(document).ready(function() {

         TableSort.init();

         Shell.setInputMask();
         Shell.setNumericInputMask();
         Shell.setPercentInputMask();
         Shell.setNumeroInputMask();
         Shell.setCepInputMask();
         Shell.setCnpjInputMask();
         Shell.setCpfInputMask();
         Shell.setFoneInputMask();

         Shell.setYearPicker();
         Shell.setMonthPicker();
         Shell.setDatePicker();
         Shell.setDateRangePicker();
         Shell.setTimePicker();
         Shell.setColorPicker();

         $('#toggle-fullscreen').click(function() { Shell.fullScreen(); });

         <?php
         if(!empty($_SESSION["alerta_erro"])) {
            echo "Shell.showAlert('danger', '".str_replace("'", '"', $_SESSION["alerta_erro"])."')";
            $_SESSION["alerta_erro"] = "";
         }
         if(!empty($_SESSION["alerta_sucesso"])) {
            echo "Shell.showAlert('success', '".str_replace("'", '"', $_SESSION["alerta_sucesso"])."')";
            $_SESSION["alerta_sucesso"] = "";
         }
         if(!empty($_SESSION["alerta_info"])) {
            echo "Shell.showAlert('info', '".str_replace("'", '"', $_SESSION["alerta_info"])."')";
            $_SESSION["alerta_info"] = "";
         }
         if(!empty($_SESSION["alerta_atencao"])) {
            echo "Shell.showAlert('warning', '".str_replace("'", '"', $_SESSION["alerta_atencao"])."')";
            $_SESSION["alerta_atencao"] = "";
         }
         ?>
      });
   </script>
<?php
   if(isset($service)) free($service);
} catch(Exception $e) {
   @include "erro.php";
}
?>
</body>
</html>
