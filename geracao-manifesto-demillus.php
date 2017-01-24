<?php
/**
 * Geracao Manifesto Demillus
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel
$prgTit = "Gera&ccedil;&atilde;o de Manifesto Demillus";

// pega a configuracao
require_once("inc/config.inc");

// pega os campos
$codCliente = (isset($_REQUEST["codCliente"])) ? $_REQUEST["codCliente"] : 6670;
$codProduto = (isset($_REQUEST["codProduto"])) ? $_REQUEST["codProduto"] :  543;  // DEMILLUS SP
$data       = (isset($_REQUEST["dataEmis"  ])) ? $_REQUEST["dataEmis"  ] : date("d/m/Y");

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>

<!-- Calendadio - CSS -->
<link rel="stylesheet" type="text/css" href="<?=HOST?>/datetime/calendar-blue.css"  media="all"  title="blue" />
<link rel="stylesheet" type="text/css" href="<?=HOST?>/js/jquery-ui/jquery-ui.min.css"  media="all"   />
<!-- Calendario - JS -->
<script type="text/javascript" src="<?=HOST?>/datetime/calendar.js?token=<?=$rnd?>"></script>
<script type="text/javascript" src="<?=HOST?>/datetime/lang/calendar-br.js?token=<?=$rnd?>"></script>
<script type="text/javascript" src="<?=HOST?>/datetime/calendar-setup.js?token=<?=$rnd?>"></script>

<!-- CSS Local -->
<link rel="stylesheet" type="text/css" href="<?=HOST?>/css/geracao-manifesto-demillus.css" />

<!-- JS Local -->
<script type="text/javascript" src="<?=HOST?>/js/geracao-manifesto-demillus/lista.js?token=<?=$rnd?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="<?=HOST?>/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=HOST?>/js/jquery.mask.min.js"></script>
<!-- Div principal -->
<div class="geracao_manifesto_demillus">
    <form action="<?=$selfLink?>" name="fCons" method="post" onsubmit="return validarCon(this)"> 
	<input type="hidden" name="codCliente" value="<?=$codCliente?>" />
	<table class="tabela">
	<tr>
	   <td>Produto</td>
	   <td><select name="codProduto" class="campo">
	         <option value="0">Selecione</option>
			 <option value="543" <?php if ($codProduto == 543) {?>selected="selected"<?php }?>>DEMILLUS SP</option>
	       </select>
	   </td>
	   <td style="width: 30px;">&nbsp;</td>
	   <td>Data Emiss&atilde;o</td>
	   <td><input type="text" class="campo" name="dataEmis" id="dataEmis" size="20" value="<?=$data?>" />
	       <img src="<?=HOST?>/datetime/img.gif" border="0" id="data_emissao" style="cursor: pointer;" title="Selecione a Data de Emiss&atilde;o" />
	   </td>
	   <td style="width: 30px;">&nbsp;</td>
	   <td><button type="submit" class="botao">Pesquisar</button>
	   </td>
    </tr>
    </table>
    </form>

	<br />
	
    <!-- Lista -->
	<?php require_once("includes/geracao-manifesto-demillus/lista.php"); ?>
</div>

<?php
// pega o Footer
require_once("inc/footer.inc");