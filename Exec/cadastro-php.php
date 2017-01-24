<?php
/**
 * Programas PHP - EXEC
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

// pega os campos passados pelo formulario
$code          = (isset($_REQUEST["code"         ])) ? $_REQUEST["code"         ] :  0;
$txt_programa  = (isset($_REQUEST["txt_programa" ])) ? $_REQUEST["txt_programa" ] : "";
$txt_descricao = (isset($_REQUEST["txt_descricao"])) ? $_REQUEST["txt_descricao"] : "";

// cria o modulo de consulta
$dba = new consulta($con);

// verifica a acao
if ($act == "incluir") {
	// monta a query de INSERT
	$lsql = "INSERT INTO tbphp (nomelink          ,
	                            descricao         )
	                    VALUES ('{$txt_programa}' ,
								'{$txt_descricao}')";
	// executa a query
	$dba->executa($lsql);
	
	// monta a mensagem
	$msg = "Programa PHP incluido com sucesso!";
}
else if ($act == "editar") {
	// monta a query de UPDATE
	$lsql = "UPDATE tbphp
	            SET nomelink  = '{$txt_programa}',
	                descricao = '{$txt_descricao}'
			 WHERE (idphp     =  {$code})";

	// executa a query
	$dba->executa($lsql);
	
	// monta a mensagem
	$msg = "Programa PHP alterado com sucesso!";
}
else if ($act == "excluir") {
	// monta a query de DELETE
	$lsql = "DELETE FROM tbphp WHERE (idphp = {$code})";
	
	// executa a query
	$dba->executa($lsql);
	
	// monta a mensagem
	$msg = "Programa PHP excluido com sucesso!";
}

// exexuta o comando
echo "<script type='text/javascript'>window.parent.mensagem('{$msg}');</script>";

// fianliza o script
exit();