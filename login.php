<?php
require_once("init.php");
require_once("inc/functions.php");
require_once("lib/Service.php");

try{
   $service = new Service();
   $msg_erro = '';

   // POST
   if($_POST) {

      $cod_empresa = $_POST["cod_empresa"];
      $login       = $_POST["login"];
      $senha       = $_POST["senha"];

      define("USUARIO", $login);

      try {

         if($login == "" || $senha == "") throw new Exception("Informe um usuário e senha corretamente");

         $usuario = $service->login($login, $senha);
         if(is_object($usuario)) {

            $usuario->nome_perfil = "";
   

            if($cod_empresa == "0") {
               $empresa = new stdClass();
               $empresa->cod_empresa = $cod_empresa;
               $empresa->nome_empresa = "MULTIEMPRESA";
            } else {
               if($usuario->multiempresa == false  &&
                  $usuario->cod_empresa != $cod_empresa) throw new Exception("Usuário não pertence a esta empresa");
               $empresa = $service->getEmpresa($cod_empresa);
            }

            session_cache_limiter("private");
            session_cache_expire(20);
            session_start();

            $sessao = (object) [
               'id'      => session_id(),
               'empresa' => $empresa,
               'usuario' => $usuario,
               'ts'      => time(),
            ];

            $_SESSION["sessao"] = $sessao;
            header("location:index.php");
            exit;

         } else throw new Exception("Usuário e/ou senha inválidos");

      } catch(Exception $e) {
         $msg_erro = $e->getMessage();
      }
   } // POST

   $lista_empresa = $service->getEmpresaList(null);

?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title><?=$empresa->nome_empresa?> - <?=SISVER?></title>
   <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <link rel="stylesheet" href="assets/adminLTE/bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="assets/ionicons/css/ionicons.min.css">
   <link rel="stylesheet" href="assets/adminLTE/dist/css/AdminLTE.min.css">
   <link rel="stylesheet" href="assets/adminLTE/dist/css/skins/skin-blue.min.css">
   <link rel="stylesheet" href="assets/sm.css">
   <link rel="icon" href="img/favicon.ico">
   <!--[if lt IE 9]>
   <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
   <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
   <![endif]-->
</head>
<body class="hold-transition skin-blue" style="background-color:#ecf0f5;">
   <div class="wrapper">
      <header class="main-header">
         <nav class="navbar navbar-static-top" style="margin-left:0">
            <div class="logo">
               <img src="img/logo.png" style="vertical-align:initial;">
            </div>
         </nav>
      </header>
      <div class="container-fluid" style="background-color:#ecf0f5;">
         <div class="row-fluid" style="padding-top:50px;">
            <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
               <?php
               if($msg_erro != "") echo '<div class="alert alert-danger" role="alert">'.$msg_erro.'</div>'
               ?>
               <div class="box box-primary">
                  <div class="box-header with-border">
                     <h3 class="box-title">LOGIN</h3>
                  </div>
                  <form id="form-login" method="post" accept-charset="utf-8">
                     <div class="box-body">
                        <div class="form-group">
                           <label for="login">Usuário</label>
                           <input type="text" class="form-control" id="login" name="login" value="" autocomplete="off" autocapitalize="off" placeholder="Informe seu usuário..." required autofocus>
                        </div>
                        <div class="form-group">
                           <label for="senha">Senha</label>
                           <input type="password" class="form-control" id="senha" name="senha" placeholder="Informe sua senha..." required>
                        </div>
                        <div class="form-group">
                           <label for="cod_empresa">Empresa</label>
                           <select class="form-control" id="cod_empresa" name="cod_empresa">
                              <?php
                              foreach($lista_empresa as $row){
                                 echo '<option value="'.$row->cod_empresa.'">'.$row->nome_empresa.'</option>';
                              }
                              ?>
                           </select>
                        </div>
                     </div>
                     <div class="box-footer">
                        <button class="btn btn-primary" type="submit">Entrar</button>
                     </div>
                  </form>
               </div><!-- /.box -->
            </div><!-- /.col-md-6 -->
         </div><!-- /.row-fluid -->
      </div><!-- /.container-fluid -->
   </div><!-- ./wrapper -->
   <script src="assets/adminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
   <script src="assets/adminLTE/bootstrap/js/bootstrap.min.js"></script>
   <script src="assets/sm.js"></script>
   <script>
      $(document).ready(function() {

      });
   </script>
</body>
</html>
<?php
   if(isset($service)) free($service);
} catch(Exception $e) {
   @include "erro.php";
}