<?php
/**
 * Cadastro Campanha Demillus - Busca
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega a configuracao de busca
require_once("../../inc/busca.inc");

// pega os campos passados pelo formulario
$txt_campanha = (isset($_POST["txt_campanha"])) ? $_POST["txt_campanha"] : "";
$txt_setor    = (isset($_POST["txt_setor"   ])) ? $_POST["txt_setor"   ] : "";

// seta a busca
$_SESSION["DT_BUSCA1"] = $txt_campanha;
$_SESSION["DT_BUSCA2"] = $txt_setor;

// seta a URL de redirecionamento
$url = HOST."/cadastro-campanha-demillus.php?idmenu=0&token={$rnd}";

// redireciona
header("Location: {$url}");