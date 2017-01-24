<?php
/**
 * Geracao Manifesto Demillus - EXEC
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
 // seta variavel local
$local = 1;
$prg   = "exec";

// pega a configuracao
require_once("../inc/config.inc");

// inicia a consulta
$qry = new consulta($con);
if(isset($_POST['setor'])){
    $sql = "UPDATE tb_demillus_revend SET id_setor_correcao = ".$_POST['setor']." WHERE id_revend = ".$_POST['idrevend'];
    // executa a gravacao
    $qry->executa($sql);
    $status = 1;
}else{
	$status = 2;
}


$retorno = array('status' => $status);
echo json_encode($retorno);

