<?php
/**
 * Cadastro Campanha Demillus - EDITAR
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
 // seta variavel local
$local = 2;

// pega a configuracao
require_once("../../inc/config.inc");

// pega o codigo
$code = (isset($_REQUEST["code"])) ? $_REQUEST["code"] : 0;

// inicia as variaveis
$txt_setor    = "";
$txt_campanha = "";
$txt_entrega  = "";
$txt_coleta   = "";
$txt_cidade   = "";
$txt_uf       = "";

// cria o modulo de consulta
$dba = new consulta($con);

// monta a query
$lsql = "SELECT num_campanha,
				to_char(data_coleta , 'DD/MM/YYYY') AS dt_coleta ,
				to_char(data_entrega, 'DD/MM/YYYY') AS dt_entrega,
				nome_cidade,
				num_setor  ,
				uf
		 FROM tb_demillus_campanha
		 WHERE (id = {$code})";


// executa a query
$dba->executa($lsql);

// verifica o resultado da query
if ($dba->nrw > 0) {
	// pega os campos
	$txt_setor    = $dba->data["num_setor"];
    $txt_campanha = $dba->data["num_campanha"];
    $txt_entrega  = $dba->data["dt_entrega"];
    $txt_coleta   = $dba->data["dt_coleta"];
    $txt_cidade   = $dba->data["nome_cidade"];
    $txt_uf       = $dba->data["uf"];
}

// seta o Exec
$_Exec = HOST."/Exec/cadastro-campanha-demillus.php?token={$rnd}";

// pega o header
require_once("../../inc/header-interno.inc");
?>

<!-- JS Local -->
<script type="text/javascript" src="<?=HOST?>/js/cadastro-campanha-demillus/editar.js?token=<?=$rnd?>"></script>

<!-- Formulario -->
<div id="interno">
    <h1>Editar Campanha</h1>
	 
	<form action="<?=$_Exec?>" name="fCad" method="post" target="gravar" onsubmit="return validar(this)">
	<input type="hidden" name="act"     value="editar" />
	<input type="hidden" name="code"    value="<?=$code?>" />
	<input type="hidden" name="lastpag" value="<?=$lastpag?>" />
	<table>
	<tr>
	   <td><strong>Setor:</strong></td>
	   <td><strong>N<sup>o</sup> da Campanha:</strong></td>
	   <td><strong>Data da Entrega:</strong></td>
	   <td><strong>Data da Coleta:</strong></td>
	</tr>
	<tr>
	   <td><input type="text" class="campo" name="txt_setor" size="8" value="<?=$txt_setor?>" /></td>
	   <td><select class="campo"  name="txt_campanha">
	         <option value="">Selecione</option>
			 <?php
			   for($ii = 1; $ii < 100; $ii++) {
				   // seleciona
				   $sele = ($txt_campanha == $ii) ? "selected='selected'" : "";

				   // mostra a opcao
				   echo "<option value='{$ii}' {$sele}>{$ii}</option>\n";
			   }
			 ?>
	       </select>
	   </td>
	   <td><input type="text" class="campo" name="txt_entrega" size="12" value="<?=$txt_entrega?>" /></td>
	   <td><input type="text" class="campo" name="txt_coleta"  size="12" value="<?=$txt_coleta?>" /></td>
	</tr>
	<tr>
	   <td colspan="3"><strong>Cidade:</strong></td>
	   <td><strong>UF:</strong></td>
	</tr>
	<tr>
	   <td colspan="3"><input type="text" class="campo" name="txt_cidade" size="80" value="<?=$txt_cidade?>" style="width: 100%;" /></td>
	   <td><select class="campo"  name="txt_uf">
	         <option value="">Selecione</option>
			 <option value="SP" <?php if ($txt_uf == "SP") {?>selected='selected'<?php }?>>SP</option>
			 <option value="RJ" <?php if ($txt_uf == "RJ") {?>selected='selected'<?php }?>>RJ</option>
			 <option value="PR" <?php if ($txt_uf == "PR") {?>selected='selected'<?php }?>>PR</option>
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