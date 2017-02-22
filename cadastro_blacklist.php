<?php
// pega a configuracao
$prgTit = "Cadastro Blacklist";
require_once("inc/config.inc");
require_once("inc/header.inc");

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$numlista = $_POST['lista'];

if(isset($_POST['cadastrar'])){
	$qry->executa("UPDATE tbentrega SET idtipomovimento = '891' WHERE numconta = '".trim($ra)."'");
	echo "RA: $ra incluida na BL";
}

?>
<table style="width:1000px; margin:0 auto">
<tr bgcolor="#eeeeee">
  <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Cadastro Blacklist</b></font></font><font size="4"><b> ::..</b></font></td>
</tr>
</table>
<table class="tabela"  BORDER=0 style="margin:0 auto; width:1000px">
<form action="#"  method="post">
  <tr>
  	<td>RA: </td>
	<td><input type=text name="ra" value="<?php echo $ra;?>" ></td>
  </tr>
  <tr>
  	 <td colspan="4"><input type=submit name="pesquisar" value="Pesquisar"></td>
  </tr>
</table><br>
<?php
//SÓ MOSTRA DEPOIS DE PESQUISAR
if(isset($_POST['pesquisar'])){
?>
<!-- -->
<table class="tabela" style="width:1000px; margin:0 auto">
<tr bgcolor=#eeeeee>
	<td><b>RA</b></td>
	<td><b>Nome</b></td>
	<td></td>
</tr>
<?php
	$qry->executa("SELECT * FROM tb_demillus_revend WHERE id_revend = '$ra'");
	for($i=0;$i<$qry->nrw;$i++){
	    $qry->navega($i);
	?>
		<tr style="padding: 20px 0px;" <?php if($i%2 == 1): echo "bgcolor=#eeeeee"; endif; ?>>
			<td><?php echo $qry->data['id_revend']; ?></td>
			<td><?php echo $qry->data['nome_revend']; ?></td>
			<td>
				<form action="#"  method="post">
					<input type=submit name="cadastrar" value="Incluir na BL" />
					<input type="hidden" name="ra" value="<?php echo $ra; ?>" />
				</form>
			</td>
		</tr>
	<?php
	}
}
?>
</table>
<script>
( function( $ ) {
	$(function() {
		$('.dataemi').datepicker({  dateFormat: 'dd/mm/yy',   dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
		    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
		    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
		    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		    nextText: 'Próximo',
		    prevText: 'Anterior' });
		$('.dataemi').mask("99/99/9999");
	});
} )( jQuery );	
</script>
<?php
// pega o Footer
require_once("inc/footer.inc");
