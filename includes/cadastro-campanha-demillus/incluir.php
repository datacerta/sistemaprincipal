<?php
/**
 * Cadastro Campanha Demillus - INCLUIR
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
$_Exec = HOST."/Exec/cadastro-campanha-demillus.php?token={$rnd}";

// pega o header
require_once("../../inc/header-interno.inc");
?>

<!-- JS Local -->
<script type="text/javascript" src="<?=HOST?>/js/cadastro-campanha-demillus/incluir.js?token=<?=$rnd?>"></script>

<!-- Formulario -->
<div id="interno">
    <h1>Incluir Nova Campanha</h1>
	 
	<form action="<?=$_Exec?>" name="fCad" method="post" target="gravar" onsubmit="return validar(this)">
	<input type="hidden" name="act"     value="incluir" />
	<input type="hidden" name="lastpag" value="<?=$lastpag?>" />
	<table>
	<tr>
	   <td><strong>Setor:</strong></td>
	   <td><strong>N<sup>o</sup> da Campanha:</strong></td>
	   <td><strong>Data da Entrega:</strong></td>
	   <td><strong>Data da Coleta:</strong></td>
	</tr>
	<tr>
	   <td><input type="text" class="campo" name="txt_setor" size="8" /></td>
	   <td><select class="campo"  name="txt_campanha">
	         <option value="">Selecione</option>
			 <?php
			   for($ii = 1; $ii < 100; $ii++) {
				   echo "<option value='{$ii}'>{$ii}</option>\n";
			   }
			 ?>
	       </select>
	   </td>
	   <td><input type="text" class="campo" name="txt_entrega" size="12" /></td>
	   <td><input type="text" class="campo" name="txt_coleta"  size="12" /></td>
	</tr>
	<tr>
	   <td colspan="3"><strong>Cidade:</strong></td>
	   <td><strong>UF:</strong></td>
	</tr>
	<tr>
	   <td colspan="3"><input type="text" class="campo" name="txt_cidade" size="80" style="width: 100%;" /></td>
	   <td><select class="campo"  name="txt_uf">
	         <option value="">Selecione</option>
			 <option value="SP">SP</option>
			 <option value="RJ">RJ</option>
			 <option value="PR">PR</option>
	       </select>
	   </td>
	</tr>
	<tr>
	   <td colspan="4">
	       <button type="submit" class="submit">Gravar</button>
	   </td>
	</tr>
	</table>
	</form> 

</div>

<?php
// pega o footer
require_once("../../inc/footer-interno.inc");