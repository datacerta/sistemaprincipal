<?php
/**
 * Cadastro de Programas PHP - Busca
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega a configuracao de busca
require_once("../../inc/busca.inc");

// pega os campos passados pelo formulario
$txt_programa  = (isset($_POST["txt_programa" ])) ? $_POST["txt_programa" ] : "";
$txt_descricao = (isset($_POST["txt_descricao"])) ? $_POST["txt_descricao"] : "";

// seta a busca
$_SESSION["DT_BUSCA1"] = $txt_programa;
$_SESSION["DT_BUSCA2"] = $txt_descricao;

// seta a URL de redirecionamento
$url = HOST."/cadastro-php.php?idmenu=0&token={$rnd}";

// redireciona
header("Location: {$url}");