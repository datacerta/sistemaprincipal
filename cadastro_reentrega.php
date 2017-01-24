<?php
// pega a configuracao
$prgTit = "Data de Reentrega";
require_once("inc/config.inc");
require_once("inc/header.inc");

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$numlista = $_POST['lista'];

if(isset($_POST['cadastrar'])){
	$datareentrega = $_POST['datareentrega'];
	$motivo = substr($_POST['motivo'],0,20);
	$coment = $_POST['comentarios'];
	$obsmotivo = $motivo." - ".$coment;


	if(empty($datareentrega)){
		echo "<div style='margin:20px auto; width:800px; text-align:center; color:red; font-weight:bold'>Preencha a data de Reentrega</div>";
	}else{
		$dataformat =  explode("/", $datareentrega);
		$datareentrega = $dataformat[2]."-".$dataformat[1]."-".$dataformat[0];
		$qry3->executa("UPDATE tbentrega SET st = 'N', datareentrega = '$datareentrega', idmotivo = '45', obsmotivo = '$obsmotivo' WHERE numlista = '$numlista'");
		echo "<div style='margin:20px auto; width:800px; text-align:center; font-weight:bold'>Cadastrado com Sucesso!</div>";
	}
	
}

?>
<table style="width:1000px; margin:0 auto">
<tr bgcolor="#eeeeee">
  <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Data de Reentrega</b></font></font><font size="4"><b> ::..</b></font></td>
</tr>
</table>
<table class="tabela"  BORDER=0 style="margin:0 auto; width:1000px">
<form action="#"  method="post">
  <tr>
  	<td>Lista: </td>
	<td><input type=text name="lista" value="<?php echo $numlista;?>" ></td>
  </tr>
  <tr>
  	 <td colspan="4"><input type=submit name="pesquisar" value="Pesquisar"></td>
  </tr>
</table><br>
<?php
//SÓ MOSTRA DEPOIS DE PESQUISAR
if(isset($_POST['pesquisar'])){
?>
<table class="tabela"  BORDER=0 style="margin:0 auto; width:1000px">
  <tr>
  	<td>Data Reentrega: </td>
	<td><input type=text name="datareentrega" class="dataemi"></td>
  	<td>Motivo: </td>
	<td>
	<select name="motivo">
	<?php
	$qry2->executa("SELECT * FROM tbmotivo ORDER BY motivo");
	for($i=0;$i<$qry2->nrw;$i++){
		$qry2->navega($i);
	?>
		<option><?php echo $qry2->data['motivo'];?></option>
	<?php
	}
	?>
	</select>
	</td>
  	<td>Comentarios</td>
	<td>
	<textarea name="comentarios"></textarea>
	</td>
  </tr>
  <tr>
  	 <td colspan="4"><input type=submit name="cadastrar" value="Cadastrar"></td>
  </tr>
</form>
</table>
<br>
<!-- -->
<table class="tabela" style="width:1000px; margin:0 auto">
<tr bgcolor=#eeeeee>
	<td><b>Nota Fiscal</b></td>
	<td><b>Id Externo</b></td>
	<td><b>Setor</b></td>
</tr>
<?php
	$qry->executa("SELECT * FROM tbentrega WHERE numlista = '$numlista'");
	for($i=0;$i<$qry->nrw;$i++){
	    $qry->navega($i);
	?>
		<tr style="padding: 20px 0px;" <?php if($i%2 == 1): echo "bgcolor=#eeeeee"; endif; ?>>
			<td><?php echo $qry->data['numnotafiscal']; ?></td>
			<td><?php echo $qry->data['idexterno']; ?></td>
			<td><?php echo $qry->data['primeiroenvelope']; ?></td>
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
