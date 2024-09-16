<?php

setlocale(LC_ALL, "pt_BR", "pt_BR.utf-8", "pt_BR.utf-8", "portuguese");
date_default_timezone_set("America/Sao_Paulo");
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
mb_internal_encoding("UTF-8");

if (substr_count($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip"))
     ob_start("ob_gzhandler");
else ob_start();

define("DOMAIN", explode(".", str_replace("www.", "", $_SERVER["HTTP_HOST"]))[0]);
define("SISVER", "STOCKMASTER 1.0");
