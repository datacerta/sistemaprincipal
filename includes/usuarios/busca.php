<?php
/**
 * Cadastro de Usuarios - Busca
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega a configuracao de busca
require_once("../../inc/busca.inc");

// pega os campos passados pelo formulario
$txt_usuario        = (isset($_POST["txt_usuario"       ])) ? $_POST["txt_usuario"       ] : "";
$txt_login          = (isset($_POST["txt_login"         ])) ? $_POST["txt_login"         ] : "";
$txt_transportadora = (isset($_POST["txt_transportadora"])) ? $_POST["txt_transportadora"] : "";

// seta a busca
$_SESSION["DT_BUSCA1"] = strtolower($txt_usuario);
$_SESSION["DT_BUSCA2"] = strtolower($txt_login);
$_SESSION["DT_BUSCA3"] = $txt_transportadora;

// seta a URL de redirecionamento
$url = HOST."/usuarios_r.php?idmenu=0&token={$rnd}";

// redireciona
header("Location: {$url}");