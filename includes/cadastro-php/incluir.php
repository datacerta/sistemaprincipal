<?php
/**
 * Cadastro de Programas PHP - INCLUIR
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
 // seta variavel local
$local = 2;

// pega a configuracao
require_once("../../inc/config.inc");

// seta o Exec
$_Exec = HOST."/Exec/cadastro-php.php?token={$rnd}";

// pega o header
require_once("../../inc/header-interno.inc");
?>

<!-- JS Local -->
<script type="text/javascript" src="<?=HOST?>/js/cadastro-php/incluir.js?token=<?=$rnd?>"></script>

<!-- Formulario -->
<div id="interno">
    <h1>Incluir Novo Programa PHP</h1>
	 
	<form action="<?=$_Exec?>" name="fCad" method="post" target="gravar" onsubmit="return validar(this)">
	<input type="hidden" name="act"     value="incluir" />
	<input type="hidden" name="lastpag" value="<?=$lastpag?>" />
	<table>
	<tr><td><strong>Programa PHP:</strong></td></tr>
	<tr>
	   <td><input type="text" class="campo" name="txt_programa"  size="50" style="width: 100%;" /></td>
	</tr>
	<tr>
	   <td><strong>Descri&ccedil;&atilde;o:</strong></td>
	</tr>
	<tr>
	   <td><input type="text" class="campo" name="txt_descricao" size="50" style="width: 100%;" /></td>
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