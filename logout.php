<?php

session_start(); // Inicia a sessão ou retoma a sessão existente

session_unset(); // Remove todas as variáveis da sessão, limpando os dados armazenados

session_destroy(); // Destroi a sessão, removendo todos os dados da sessão do servidor

header("location:login.php"); // Redireciona o usuário para a página de login
exit; // Encerra o script para garantir que nenhum código adicional seja executado
