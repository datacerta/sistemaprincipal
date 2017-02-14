<?php
// pega a configuracao
$prgTit = "Data de Reentrega";
require_once("inc/config.inc");
require_once("inc/header.inc");

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$numlista = $_POST['lista'];
if(isset($_POST['pesquisar'])){
	$datadeformat =  explode("/", $_POST['datade']);
	$datade = $datadeformat[2]."-".$datadeformat[1]."-".$datadeformat[0];
	$dataateformat =  explode("/", $_POST['dataate']);
	$dataate = $dataateformat[2]."-".$dataateformat[1]."-".$dataateformat[0];
	$qry->executa("SELECT ec.datacliente, ec.nr_encomenda,ec.id
	  FROM tb_easy_courier ec
	  JOIN tbentrega e ON ec.nr_encomenda = e.idExterno
	  JOIN tbmovimento m ON  m.idinterno = e.idinterno
	  WHERE (m.foto is not null AND m.foto != '')
	  AND ec.datacliente BETWEEN '".$datade."' AND '".$dataate."' ORDER BY ec.datacliente");
}
if(isset($_POST['Sim'])){
	$datade = $_POST['datadeb'];
	$dataate = $_POST['dataateb'];
	$qry->executa("SELECT ec.datacliente, ec.nr_encomenda,ec.id
	  FROM tb_easy_courier ec
	  JOIN tbentrega e ON ec.nr_encomenda = e.idExterno
	  JOIN tbmovimento m ON  m.idinterno = e.idinterno
	  WHERE (m.foto is not null AND m.foto != '')
	  AND ec.datacliente BETWEEN '".$datade."' AND '".$dataate."'");
	for($i=0;$i<$qry->nrw;$i++){
	    $qry->navega($i);
		$qry2->executa("UPDATE tb_easy_courier SET foto = NULL WHERE id = ".$qry->data['id']);
	}

}

?>
<table style="width:1000px; margin:0 auto">
<tr bgcolor="#eeeeee">
  <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Limpa Tabela Easy Courier</b></font></font><font size="4"><b> ::..</b></font></td>
</tr>
</table>
<form action="#"  method="post">
<table class="tabela"  BORDER=0 style="margin:0 auto; width:1000px">
  <tr>
  	<td>De: </td>
	<td><input type=text name="datade" class="dataemi" value="<?php echo $_POST['datade']?>"></td>
 	<td>Até: </td>
	<td><input type=text name="dataate" class="dataemi" value="<?php echo $_POST['dataate']?>"></td>
  </tr>
  <tr>
  	 <td colspan="4"><input type=submit name="pesquisar" value="Pesquisar"></td>
  </tr>
</form>
</table><br><br>
<?php
//SÓ MOSTRA DEPOIS DE PESQUISAR
if(isset($_POST['pesquisar'])){
?>
<form action="#"  method="post">
	<div style="width:1000px; margin:0 auto; text-align: center">
		<h1>Tem certeza?</h1>
		<input type=submit name="Sim" value="Sim" style="padding: 10px; margin-right: 10px;"><input type=submit name="Nao" value="Não" style="padding: 10px;">
		<input type="hidden" name="datadeb" value="<?php echo $datade; ?>"/><input type="hidden" name="dataateb" value="<?php echo $dataate; ?>"/>
		<br>
	</div>
</form>
<br>
<!-- -->
<table class="tabela" style="width:1000px; margin:0 auto">
<tr bgcolor=#eeeeee>
	<td><b>Data</b></td>
	<td><b>Encomenda</b></td>
</tr>
<?php
	$j = 0;
	for($i=0;$i<$qry->nrw;$i++){
	    $qry->navega($i);
	?>
		<tr style="padding: 20px 0px;" <?php if($i%2 == 1): echo "bgcolor=#eeeeee"; endif; ?>>
			<td><?php echo $qry->data['datacliente']; ?></td>
			<td><?php echo $qry->data['nr_encomenda']; ?></td>
		</tr>
	<?php
		$j++;
	}
	?>
	<script>alert("<?php echo $j." registros"; ?>");</script>
<?php
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
