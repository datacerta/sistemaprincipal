<?php
/**
 * Busca
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// iniciar SESSION
session_start();

// define o HOST
define("HOST", "http://".$_SERVER["HTTP_HOST"]);

// variavel randomica (token)
$rnd = rand(0, 999999999999999999);