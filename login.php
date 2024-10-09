<?php
require_once("init.php"); // Inicializa configurações e dependências
require_once("inc/functions.php"); // Inclui funções auxiliares
require_once("lib/Service.php"); // Inclui a classe de serviço

try {
   // Instancia o serviço para interagir com os dados
   $service = new Service();
   $msg_erro = ''; // Variável para armazenar mensagens de erro

   // POST
   if ($_POST) { // Verifica se o formulário foi submetido

      // Captura os dados do formulário de login
      $cod_empresa = $_POST["cod_empresa"];
      $login       = $_POST["login"];
      $senha       = $_POST["senha"];

      // Define a constante USUARIO com o valor do login
      define("USUARIO", $login);

      try {
         // Verifica se o login e a senha foram informados
         if ($login == "" || $senha == "") throw new Exception("Informe um usuário e senha corretamente");

         // Chama o método de login do serviço
         $usuario = $service->login($login, $senha);
         if (is_object($usuario)) { // Verifica se o retorno é um objeto, indicando sucesso no login

            $usuario->nome_perfil = ""; // Inicializa a propriedade nome_perfil

            // Verifica o código da empresa
            if ($cod_empresa == "0") {
               // Configuração para multiempresa
               $empresa = new stdClass();
               $empresa->cod_empresa = $cod_empresa;
               $empresa->nome_empresa = "MULTIEMPRESA";
            } else {
               // Valida se o usuário pertence à empresa
               if ($usuario->multiempresa == false && $usuario->cod_empresa != $cod_empresa) throw new Exception("Usuário não pertence a esta empresa");
               $empresa = $service->getEmpresa($cod_empresa); // Obtém os dados da empresa
            }

            // Configuração da sessão
            session_cache_limiter("private"); // Define o limitador de cache para a sessão
            session_cache_expire(20); // Define a expiração do cache da sessão
            session_start(); // Inicia a sessão

            // Cria um objeto de sessão com dados do usuário
            $sessao = (object) [
               'id'      => session_id(),
               'empresa' => $empresa,
               'usuario' => $usuario,
               'ts'      => time(),
            ];

            $_SESSION["sessao"] = $sessao; // Armazena os dados da sessão
            header("location:index.php"); // Redireciona para a página inicial
            exit;

         } else throw new Exception("Usuário e/ou senha inválidos"); // Erro de login

      } catch (Exception $e) {
         // Captura erros e armazena a mensagem de erro
         $msg_erro = $e->getMessage();
      }
   } // POST

   // Obtém a lista de empresas para o dropdown
   $lista_empresa = $service->getEmpresaList(null);

?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8"> <!-- Define o conjunto de caracteres -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title><?=$empresa->nome_empresa?> - <?=SISVER?></title> <!-- Título da página -->
   <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <!-- Links para os estilos CSS -->
   <link rel="stylesheet" href="assets/adminLTE/bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="assets/ionicons/css/ionicons.min.css">
   <link rel="stylesheet" href="assets/adminLTE/dist/css/AdminLTE.min.css">
   <link rel="stylesheet" href="assets/adminLTE/dist/css/skins/skin-blue.min.css">
   <link rel="stylesheet" href="assets/sm.css">
   <link rel="icon" href="img/favicon.ico"> <!-- Ícone da aba do navegador -->
   <!--[if lt IE 9]>
   <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script> <!-- Compatibilidade com versões antigas do IE -->
   <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
   <![endif]-->
</head>
<body class="hold-transition skin-blue" style="background-color:#ecf0f5;">
   <div class="wrapper">
      <header class="main-header">
         <nav class="navbar navbar-static-top" style="margin-left:0">
            <div class="logo">
               <img src="img/logo.png" style="vertical-align:initial;"> <!-- Logo da aplicação -->
            </div>
         </nav>
      </header>
      <div class="container-fluid" style="background-color:#ecf0f5;">
         <div class="row-fluid" style="padding-top:50px;">
            <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
               <?php
               // Exibe mensagem de erro, se houver
               if ($msg_erro != "") echo '<div class="alert alert-danger" role="alert">' . $msg_erro . '</div>';
               ?>
               <div class="box box-primary">
                  <div class="box-header with-border">
                     <h3 class="box-title">LOGIN</h3> <!-- Título do formulário de login -->
                  </div>
                  <form id="form-login" method="post" accept-charset="utf-8"> <!-- Formulário de login -->
                     <div class="box-body">
                        <div class="form-group">
                           <label for="login">Usuário</label>
                           <!-- Campo de entrada para o login -->
                           <input type="text" class="form-control" id="login" name="login" value="" autocomplete="off" autocapitalize="off" placeholder="Informe seu usuário..." required autofocus>
                        </div>
                        <div class="form-group">
                           <label for="senha">Senha</label>
                           <!-- Campo de entrada para a senha -->
                           <input type="password" class="form-control" id="senha" name="senha" placeholder="Informe sua senha..." required>
                        </div>
                        <div class="form-group">
                           <label for="cod_empresa">Empresa</label>
                           <select class="form-control" id="cod_empresa" name="cod_empresa"> <!-- Dropdown para selecionar a empresa -->
                              <?php
                              // Gera opções do dropdown com base na lista de empresas
                              foreach ($lista_empresa as $row) {
                                 echo '<option value="' . $row->cod_empresa . '">' . $row->nome_empresa . '</option>';
                              }
                              ?>
                           </select>
                        </div>
                     </div>
                     <div class="box-footer">
                        <!-- Botão para submeter o formulário de login -->
                        <button class="btn btn-primary" type="submit">Entrar</button>
                     </div>
                  </form>
               </div><!-- /.box -->
            </div><!-- /.col-md-6 -->
         </div><!-- /.row-fluid -->
      </div><!-- /.container-fluid -->
   </div><!-- ./wrapper -->
   <!-- Scripts para a funcionalidade da página -->
   <script src="assets/adminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
   <script src="assets/adminLTE/bootstrap/js/bootstrap.min.js"></script>
   <script src="assets/sm.js"></script>
   <script>
      $(document).ready(function() {
         // Inicializações e scripts adicionais podem ser colocados aqui
      });
   </script>
</body>
</html>
<?php
   // Libera o serviço se estiver definido
   if (isset($service)) free($service);
} catch (Exception $e) {
   // Inclui uma página de erro se uma exceção for capturada
   @include "erro.php";
}
