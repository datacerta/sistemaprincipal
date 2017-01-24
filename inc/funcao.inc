<?php
/**
 * Funcao
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */

/**
 * Funcao que executa um DIE
 */
function errado() {
	die("<h1 style='color: #000; font: bold 20pt Verdana; text-align: center;'>404 - P&aacute;gina n&atilde;o encontrada!</h1>");
    exit();
}

/**
 * Funcao que verifica a url
 * 
 * @param <text> $param
 */
function naoAceitos($param) {
    // verifica
	if       (strpos($param, "SCRIPT") > -1) { errado(); }
    else  if (strpos($param, "script") > -1) { errado(); }
}