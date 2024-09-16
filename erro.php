<?php
require_once("init.php");
?>

<div class="container-fluid" style="background-color:#ecf0f5;">
   <div class="error-page">
      <h2 class="headline text-red">500</h2>
      <div class="error-content">
         <h3><i class="fa fa-warning text-red"></i> Oops! Aconteceu algum problema.</h3>
         <p>Verifique com seu administrador o seguinte erro:</p>
         <p class="message">Programa: <b><?=$acao?></b></p>
         <code><?=var_export($e->getMessage())?></code>
      </div>
   </div>
</div>
