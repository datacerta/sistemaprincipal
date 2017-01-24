<?php
/**
 * Funcao
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */

/**
 * Funcao combo de Transportadora
 */
function comboTransportadora($code) {
   // pega variaveis global
   global $con;
   
   // pega o ID passado
   $idtransportadora = (empty($code)) ? 0 : (int)$code;

   // executa
   $dba = new consulta($con);
   $dba->executa("SELECT idtransportadora, nometransportadora FROM tbtransportadora ORDER BY nometransportadora ASC");

   // percorre o resultado da query
   for ($ii = 0; $ii < $dba->nrw; $ii++) {
       	// navega
        $dba->navega($ii);
   
        // pega os campos
		$id   = $dba->data["idtransportadora"];
		$nome = $dba->data["nometransportadora"];
   
        // seleciona
	    $sele = ($id == $idtransportadora) ? "selected='selected'" : "";
		
		// mostra a opcao
		echo "<option value='{$id}' {$sele}>{$nome}</option>\n";
   }
}

/**
 * Funcao combo de Base
 */
function comboBase($code, $id_transportadora = 0) {
   // pega variaveis global
   global $con;
   
   // pega o ID passado
   $id_transportadora = (empty($id_transportadora)) ? 0 : (int)$id_transportadora;

   // seta a condicao
   $_condi = (!empty($id_transportadora)) ? "WHERE (idtransportadora = '{$id_transportadora}')" : "";
   
   // executa
   $dba = new consulta($con);
   $dba->executa("SELECT codbase, nomebase FROM tbbase {$_condi} ORDER BY nomebase ASC");

   // percorre o resultado da query
   for ($ii = 0; $ii < $dba->nrw; $ii++) {
       	// navega
        $dba->navega($ii);
   
        // pega os campos
		$id   = $dba->data["codbase"];
		$nome = $dba->data["nomebase"];
   
        // seleciona
	    $sele = ($id == $code) ? "selected='selected'" : "";
		
		// mostra a opcao
		echo "<option value='{$id}' {$sele}>{$nome}</option>\n";
   }
}