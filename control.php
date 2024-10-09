<?php
/*
  Este é um bloco de comentário que pode ser usado para descrever a funcionalidade do arquivo
  ou outros detalhes relevantes, mas está vazio neste caso.
*/
require_once("init.php"); // Inclui o arquivo init.php, que geralmente inicializa configurações e dependências.
require_once("inc/functions.php"); // Inclui funções auxiliares que podem ser usadas neste arquivo.
require_once("lib/Service.php"); // Inclui a classe Service, que provavelmente contém métodos para interagir com dados.

$sessao = null; // Inicializa a variável $sessao como null.

try {
    session_start(); // Inicia uma nova sessão ou retoma a sessão existente.
    $sessao = $_SESSION["sessao"]; // Recupera a sessão armazenada na variável de sessão global.

    if (!$sessao) { // Verifica se a sessão não está definida.
        header("location:login.php"); // Redireciona o usuário para a página de login se não houver sessão.
        exit; // Encerra a execução do script.
    }

    $usuario = $sessao->usuario; // Armazena o usuário da sessão na variável $usuario.
    $empresa = $sessao->empresa; // Armazena a empresa da sessão na variável $empresa.

    $service = new Service(); // Cria uma nova instância da classe Service.
    $param = $service->getParam($empresa->cod_empresa); // Obtém parâmetros específicos da empresa.

    define("ROOT", $param->raiz); // Define a constante ROOT com o valor da raiz do parâmetro.
    define("USUARIO", $usuario->nome_usuario); // Define a constante USUARIO com o nome do usuário.
    define("PERFIL", $usuario->cod_perfil); // Define a constante PERFIL com o código do perfil do usuário.

    // Verifica se existem valores na requisição e os armazena em variáveis.
    if (!empty($_REQUEST["acao"])) $acao = $_REQUEST["acao"];
    if (!empty($_REQUEST["metodo"])) $metodo = $_REQUEST["metodo"];
    if (!empty($_REQUEST["mode"])) $mode = $_REQUEST["mode"];

    // Estrutura de controle que determina a ação a ser executada com base na variável $acao.
    switch ($acao) {
        case "validateUsuario": // Caso a ação seja validar usuário.
            switch ($metodo) { // Verifica o método de validação.
                case 'login': // Se o método for login.
                    if ($mode == "edit") { // Se o modo for editar.
                        echo 'true'; // Retorna 'true', indicando que a validação foi bem-sucedida.
                    } else {
                        $login = $_REQUEST["login"]; // Recupera o login da requisição.
                        $retorno = $service->getUsuarioByLogin($login); // Obtém o usuário pelo login.
                        if (!empty($retorno)) // Se o retorno não estiver vazio.
                            echo 'false'; // Retorna 'false', indicando que o usuário já existe.
                        else echo 'true'; // Caso contrário, retorna 'true'.
                    }
                break; // Fim do case 'login'.
            }
        break; // Fim do case 'validateUsuario'.

        case "validateItem": // Caso a ação seja validar item.
            switch ($metodo) { // Verifica o método de validação.
                case 'nome': // Se o método for nome.
                    $nome_item = $_REQUEST["nome_item"]; // Recupera o nome do item da requisição.
                    $retorno = $service->getItemByName($nome_item); // Obtém o item pelo nome.
                    if ($mode == "new") { // Se o modo for novo.
                        if (is_object($retorno)) // Se o retorno for um objeto (item já existe).
                            echo 'false'; // Retorna 'false'.
                        else echo 'true'; // Caso contrário, retorna 'true'.
                    } else if ($mode == "edit") { // Se o modo for editar.
                        $cod_item = $_REQUEST["cod_item"]; // Recupera o código do item da requisição.
                        if (!is_object($retorno)) // Se o retorno não for um objeto (item não existe).
                            echo 'true'; // Retorna 'true'.
                        else if ($retorno->cod_item == $cod_item) // Se o código do item retornado for igual ao código do item da requisição.
                            echo 'true'; // Retorna 'true'.
                        else echo 'false'; // Caso contrário, retorna 'false'.
                    }
                break; // Fim do case 'nome'.
            }
        break; // Fim do case 'validateItem'.

        case "validatePerfil": // Caso a ação seja validar perfil.
            switch ($metodo) { // Verifica o método de validação.
                case 'nome': // Se o método for nome.
                    $retorno = $service->getPerfilByName($_REQUEST["nome_perfil"]); // Obtém o perfil pelo nome.
                    if ($mode == "new") { // Se o modo for novo.
                        if (is_object($retorno)) // Se o retorno for um objeto (perfil já existe).
                            echo 'false'; // Retorna 'false'.
                        else echo 'true'; // Caso contrário, retorna 'true'.
                    } else if ($mode == "edit") { // Se o modo for editar.
                        $cod_perfil = $_REQUEST["cod_perfil"]; // Recupera o código do perfil da requisição.
                        if (!is_object($retorno)) // Se o retorno não for um objeto (perfil não existe).
                            echo 'true'; // Retorna 'true'.
                        else if ($retorno->cod_perfil == $cod_perfil) // Se o código do perfil retornado for igual ao código do perfil da requisição.
                            echo 'true'; // Retorna 'true'.
                        else echo 'false'; // Caso contrário, retorna 'false'.
                    }
                break; // Fim do case 'nome'.
            }
        break; // Fim do case 'validatePerfil'.

    }
    free($service); // Libera a instância do serviço após o uso.
} catch (Exception $e) { // Captura qualquer exceção que ocorra durante a execução.
    saveLog($e); // Salva o log da exceção.
}
