<?php
// Configura o locale para o sistema em português do Brasil, com suporte a UTF-8 e o idioma "portuguese".
setlocale(LC_ALL, "pt_BR", "pt_BR.utf-8", "pt_BR.utf-8", "portuguese");

// Define o fuso horário padrão para "America/Sao_Paulo".
date_default_timezone_set("America/Sao_Paulo");

// Configura o nível de relatórios de erros para exibir todos os erros, exceto os de tipo E_NOTICE (avisos).
error_reporting(E_ALL & ~E_NOTICE);

// Define para exibir os erros diretamente no navegador (útil para desenvolvimento e depuração).
ini_set("display_errors", 1);

// Define a codificação interna de caracteres como UTF-8 para funções multibyte (mb_*).
mb_internal_encoding("UTF-8");

// Verifica se o cliente suporta codificação gzip para comprimir o conteúdo da página.
if (substr_count($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip"))
     // Inicia a saída bufferizada com suporte a gzip para reduzir o tamanho do conteúdo transferido.
     ob_start("ob_gzhandler");
else
     // Caso o navegador não suporte gzip, inicia o buffer de saída sem compressão.
     ob_start();

// Define uma constante "DOMAIN" com o domínio base, removendo "www." e capturando a parte principal do host.
define("DOMAIN", explode(".", str_replace("www.", "", $_SERVER["HTTP_HOST"]))[0]);

// Define uma constante "SISVER" com o nome e a versão do sistema, neste caso "STOCKMASTER 1.0".
define("SISVER", "STOCKMASTER 1.0");
