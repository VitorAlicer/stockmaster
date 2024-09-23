<?php
// Inclui o arquivo de inicialização do sistema, que pode conter configurações e definições necessárias.
require_once("init.php");
?>

<!-- Início de um contêiner fluido com uma cor de fundo clara -->
<div class="container-fluid" style="background-color:#ecf0f5;">
   <!-- Seção de página de erro -->
   <div class="error-page">
      <!-- Cabeçalho com o código de erro 500, estilizado com texto vermelho -->
      <h2 class="headline text-red">500</h2>
      <!-- Conteúdo da seção de erro -->
      <div class="error-content">
         <!-- Título da mensagem de erro, com um ícone de aviso -->
         <h3>
            <!-- Ícone de aviso (fa-warning) estilizado em vermelho -->
            <i class="fa fa-warning text-red"></i> Oops! Aconteceu algum problema.
         </h3>
         <!-- Mensagem para o usuário sobre o erro -->
         <p>Verifique com seu administrador o seguinte erro:</p>
         <!-- Exibe a ação que estava sendo realizada quando o erro ocorreu -->
         <p class="message">Programa: <b><?=$acao?></b></p>
         <!-- Exibe a mensagem do erro capturado (variável $e) formatada como código -->
         <code><?=var_export($e->getMessage())?></code>
      </div>
   </div>
</div>
<!-- Fim do contêiner -->
