<?php
/**
 * Index
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega funcao
require_once("funcao.inc");

// pega o QueryString
$queryString = $_SERVER["QUERY_STRING"];

// verifica e tira varios codigos nao aceitos
naoAceitos($queryString);

// seta os erros
error_reporting(E_ALL|E_NOTICE);

// seta o TimeZone
date_default_timezone_set("America/Sao_Paulo");

// monta o header de acharset
header("Content-Type: text/html; charset=UTF-8", true);

// define o HOST
define("HOST", "http://".$_SERVER["HTTP_HOST"]);

// verifica a acao
$lastpag = (isset($_REQUEST["lastpag"])) ? $_REQUEST["lastpag"] : ((isset($lastpag)) ? $lastpag : 0);
$idmenu  = (isset($_REQUEST["idmenu" ])) ? $_REQUEST["idmenu" ] : ((isset($idmenu )) ? $idmenu  : 0);
$act     = (isset($_REQUEST["act"    ])) ? $_REQUEST["act"    ] : "";
$act     = trim(strip_tags(str_replace("'","",$act)));

// variavel randomica (token)
$rnd = rand(0, 999999999999999999);

// verifica a variavel de programa
if (!isset($prg   )) { $prg    = ""; }
if (!isset($prgTit)) { $prgTit = ""; }
if (!isset($prgPri)) { $prgPri = ""; }
if (!isset($local )) { $local  =  0; }

// seta onde esta
if      ($local == 0) { $sdir = ".";                 }
else if ($local == 1) { $sdir = "..";                }
else if ($local == 2) { $sdir = "../..";             }
else if ($local == 3) { $sdir = "../../..";          }
else if ($local == 4) { $sdir = "../../../..";       }
else if ($local == 5) { $sdir = "../../../../..";    }
else if ($local == 6) { $sdir = "../../../../../.."; }

// pega a configuracao principal
require_once($sdir."/classes/diversos.inc.php");
require_once($sdir."/inc/class/class.util.php");



// verifica o ID menu
if ($idmenu == 1) {
	$_SESSION["DT_BUSCA1"] = "";
    $_SESSION["DT_BUSCA2"] = "";
    $_SESSION["DT_BUSCA3"] = "";
	$_SESSION["DT_BUSCA4"] = "";
	$BUSCA1                = "";
	$BUSCA2                = "";
	$BUSCA3                = "";
	$BUSCA4                = "";
}
else {
    // seta os campos de busca em SESSION
    $BUSCA1 = (isset($_SESSION["DT_BUSCA1"])) ? $_SESSION["DT_BUSCA1"] : "";
    $BUSCA2 = (isset($_SESSION["DT_BUSCA2"])) ? $_SESSION["DT_BUSCA2"] : "";
    $BUSCA3 = (isset($_SESSION["DT_BUSCA3"])) ? $_SESSION["DT_BUSCA3"] : "";
	$BUSCA4 = (isset($_SESSION["DT_BUSCA4"])) ? $_SESSION["DT_BUSCA4"] : "";
}

// verifica o PARENT e REDIRECIONA
$PARENT      = (isset($_SESSION["PARENT"     ])) ? $_SESSION["PARENT"     ] : false;
$REDIRECIONA = (isset($_SESSION["REDIRECIONA"])) ? $_SESSION["REDIRECIONA"] : false;

// pega o ID do usuario logado
$idUser = (isset($_SESSION["IDUSER"])) ? $_SESSION["IDUSER"] : "";