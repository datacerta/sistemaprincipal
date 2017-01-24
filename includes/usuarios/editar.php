<?php
/**
 * Cadastro de Usuario - EDITAR
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
 // seta variavel local
$local = 2;

// pega a configuracao
require_once("../../inc/config.inc");
require_once("funcao.inc");

// pega o codigo
$code = (isset($_REQUEST["code"])) ? $_REQUEST["code"] : 0;

// inicia as variaveis
$txt_programa  = "";
$txt_descricao = "";

$idtransportadora = 0;


// cria o modulo de consulta
$dba = new consulta($con);

// monta a query
$lsql = "SELECT * FROM tblogin WHERE (codlogin = {$code})";

// executa a query
$dba->executa($lsql);

// verifica o resultado da query
if ($dba->nrw > 0) {
	// pega os campos
	$txt_programa  = $dba->data["nomelink"];
    $txt_descricao = $dba->data["descricao"];
}

// seta o Exec
$_Exec = HOST."/Exec/usuarios.php?token={$rnd}";

// pega o header
require_once("../../inc/header-interno.inc");
?>

<!-- JS Local -->
<script type="text/javascript" src="<?=HOST?>/js/usuarios/editar.js?token=<?=$rnd?>"></script>

<!-- Formulario -->
<div id="interno">
    <h1>Editar Usu&aacute;rio</h1>
	 
	<form action="<?=$_Exec?>" name="fCad" method="post" target="gravar" onsubmit="return validar(this)">
	<input type="hidden" name="act"     value="editar" />
	<input type="hidden" name="code"    value="<?=$code?>" />
	<input type="hidden" name="lastpag" value="<?=$lastpag?>" />
	<table>
	<tr>
	    <td><strong>Transportadora:</strong></td>
	    <td><strong>Base:</strong></td>
	</tr>
	<tr>
	    <td><select name="txt_transportadora" class="campo">
			   <option value="0">Selecione</option>
			   <?php comboTransportadora($BUSCA3); ?>
			</select>
	    </td>
	
	
	
	
	</tr>
	
	
	
	
	
	
	
	
	
	<tr><td><strong>Programa PHP:</strong></td></tr>
	<tr>
	   <td><input type="text" class="campo" name="txt_programa"  size="50" value="<?=$txt_programa?>" style="width: 100%;" /></td>
	</tr>
	<tr>
	   <td><strong>Descri&ccedil;&atilde;o:</strong></td>
	</tr>
	<tr>
	   <td><input type="text" class="campo" name="txt_descricao" size="50" value="<?=$txt_descricao?>" style="width: 100%;" /></td>
	</tr>
	<tr>
	   <td><button type="submit" class="submit">Gravar</button></td>
	</tr>
	</table>
	</form> 

</div>

<?php
// pega o footer
require_once("../../inc/footer-interno.inc");