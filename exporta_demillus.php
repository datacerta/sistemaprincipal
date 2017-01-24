<?php
/**
 * Exporta Demillus
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega a configuracao
require_once("inc/config.inc");

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>

<!-- CSS Locaol -->
<link rel="stylesheet" type="text/css" media="all" href="<?=HOST?>/datetime/calendar-blue.css" title="blue" />

<!-- JS Local -->
<script type="text/javascript" src="<?=HOST?>/datetime/calendar.js?token=<?=$rnd?>"></script>
<script type="text/javascript" src="<?=HOST?>/datetime/lang/calendar-br.js?token=<?=$rnd?>"></script>
<script type="text/javascript" src="<?=HOST?>/datetime/calendar-setup.js?token=<?=$rnd?>"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
function Validar(form)     
{
	if (form.dataemissaode.value=="")
	{
		window.alert("A data de emissão inicial deve ser informado");
		form.dataemissaode.focus(); 
		return false;
	}else if (form.setor.value=="")
	{
		window.alert("O setor deve ser informado");
		form.setor.focus(); 
		return false;
	}else if (form.dataemissaode.value!="" && form.dataemissaoate.value!="")
	{
		var data_1 = document.getElementById("dataemissaode").value;
		var data_2 = document.getElementById("dataemissaoate").value;
		var Compara01 = parseInt(data_1.split("/")[2].toString() + data_1.split("/")[1].toString() + data_1.split("/")[0].toString());
		var Compara02 = parseInt(data_2.split("/")[2].toString() + data_2.split("/")[1].toString() + data_2.split("/")[0].toString());

		//alert(Compara01);
		if ((Compara01 < Compara02) || (Compara01 == Compara02) ) {
		//	document.getElementById("msg").innerHTML = "OK";
		}else{
			window.alert("A data de emissão final não pode ser maior que a data inicial");       
			form.dataemissaoate.focus;      
			return false;    
		}

	}
	
}	
</script>

<div class="box" style="margin: 0 auto;">

<form name="exporta_Redecard" action="exporta_demillus_final_novo.php?op=<?$setor?>&token=<?=$rnd?>" method="post"  onsubmit="return Validar(document.exporta_Redecard)">
<input type="hidden" name="ok" value="1" />
  
<table>
    <tr>
    <td width="16%">Data de:</td>
    <td width="10%">
        <input type="text" name="dataemissaode" id="dataemissaode" value='<?=$dataemissaode;?>' />
    </td>
	<td width="5%">
		<img src='<?=HOST?>/datetime/img.gif' id='data_emissaode' style='cursor: pointer;' title='Selecione uma data' />
                                        <script>
											Calendar.setup({
													inputField     :    'dataemissaode',     // id of the input field
													ifFormat       :    'dd/mm/y',      // format of the input field
													button         :    'data_emissaode',  // trigger for the calendar (button ID)
													align          :    'Bl',           // alignment (defaults to 'Bl')
													singleClick    :    true
											});
										</script>
	</td>
	<td align="right">Data at&eacute;:</td>
    <td width="10%">
        <input type="text" name="dataemissaoate" id="dataemissaoate" value='<?=$dataemissaoate;?>' />
    </td>
	<td width="5%">
		<img src='<?=HOST?>/datetime/img.gif' id='data_emissaoate' style='cursor: pointer;' title='Selecione uma data' />
                                        <script>
											Calendar.setup({
													inputField     :    'dataemissaoate',     // id of the input field
													ifFormat       :    'dd/mm/y',      // format of the input field
													button         :    'data_emissaoate',  // trigger for the calendar (button ID)
													align          :    'Bl',           // alignment (defaults to 'Bl')
													singleClick    :    true
											});
										</script>
    </td>
	</tr>
	<tr>
    <td width="16%">N&uacute;mero do Setor</td>
    <td colspan="5">
        <input type="text" name=setor value='<?=$setor;?>' />
    </td>
    </tr>
	<tr><td colspan="6">&nbsp;</td></tr>
	<tr>
	   <td colspan="6" align="center">
	       <input type=submit class="submit" value="Gerar Arquivo para DeMillus, Ap&oacute;s gera&ccedil;&atilde;o favor enviar para Site DeMillus" />
	   </td>
	</tr>
    </table>
</form>

</div>

<?php
// pega o Footer
require_once("inc/footer.inc");