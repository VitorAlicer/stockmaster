<?php

global $empresa;

$hoje = date('d/m/Y');
$dt_mes_ini = getMonthBegin($hoje);
$dt_mes_fin = getMonthEnd($hoje);

if($empresa->cod_empresa == "0")
     $cod_empresa = $_REQUEST["cod_empresa"];
else $cod_empresa = $empresa->cod_empresa;

$dash = new stdClass();

?>

<!-- Content Header (Page header) -->
<section class="content-header">
   <h1>Dashboard <small>Painel de controle</small></h1>
</section>

<!-- Main content -->
<section class="content">

   <div class="row">
      <img src="/img/dashboard.png"/>
   </div>

</section><!-- /.content -->

