<?php
/*


*/
require_once("init.php");
require_once("inc/functions.php");
require_once("lib/Service.php");

$sessao = null;

try{

   session_start();
   $sessao = $_SESSION["sessao"];
   if(!$sessao){
      header("location:login.php");
      exit;
   }


   $usuario = $sessao->usuario;
   $empresa = $sessao->empresa;

   $service = new Service();
   $param = $service->getParam($empresa->cod_empresa);

   define("ROOT"   , $param->raiz);
   define("USUARIO", $usuario->nome_usuario);
   define("PERFIL" , $usuario->cod_perfil);

   if(!empty($_REQUEST["acao"])) $acao = $_REQUEST["acao"];
   if(!empty($_REQUEST["metodo"])) $metodo = $_REQUEST["metodo"];
   if(!empty($_REQUEST["mode"])) $mode = $_REQUEST["mode"];

   switch($acao) {

      case "validateUsuario":
         switch($metodo) {
            case 'login':
               if($mode == "edit") {
                  echo 'true';
               } else {
                  $login = $_REQUEST["login"];
                  $retorno = $service->getUsuarioByLogin($login);
                  if (!empty($retorno))
                       echo 'false';
                  else echo 'true';
               }
            break;
         }
      break;

    
      case "validateItem":
         switch($metodo) {
            case 'nome':
               $nome_item = $_REQUEST["nome_item"];
               $retorno = $service->getItemByName($nome_item);
               if($mode == "new") {
                  if(is_object($retorno))
                       echo 'false';
                  else echo 'true';
               } else if($mode == "edit") {
                  $cod_item = $_REQUEST["cod_item"];
                  if(!is_object($retorno))
                       echo 'true';
                  else if($retorno->cod_item == $cod_item)
                       echo 'true';
                  else echo 'false';
               }
            break;
         }
      break;


      case "validatePerfil":
         switch($metodo) {
            case 'nome':
               $retorno = $service->getPerfilByName($_REQUEST["nome_perfil"]);
               if($mode == "new") {
                  if(is_object($retorno))
                       echo 'false';
                  else echo 'true';
               } else if($mode == "edit") {
                  $cod_perfil = $_REQUEST["cod_perfil"];
                  if(!is_object($retorno))
                       echo 'true';
                  else if($retorno->cod_perfil == $cod_perfil)
                       echo 'true';
                  else echo 'false';
               }
            break;
         }
      break;

   }
   free($service);
} catch(Exception $e) {
   saveLog($e);
}
