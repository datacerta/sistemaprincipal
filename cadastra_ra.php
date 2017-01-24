<?php
// pega a configuracao
$prgTit = "Cadastro de RA";
require_once("inc/config.inc");
require_once("inc/header.inc");

$qry = new consulta($con);
$qry2 = new consulta($con);
$qrySet = new consulta($con);

if(isset($_POST['atualizar'])){
	$arquivo = $_FILES['arquivo']['tmp_name'];
	$data = file_get_contents($arquivo);
	$rg = pg_escape_bytea($data);
	$idrevend = $_POST['idrevend'];
	if(!empty($arquivo) AND !empty($idrevend)){
		$qry->executa("UPDATE tb_demillus_revend  SET imagem_rg = '{$rg}' WHERE id_revend = '".$idrevend."'");
		echo "<div style='width:800px; margin:0 auto; text-align:center; font-weight:bold; padding:25px; color:red'>ID: $idrevend atualizado!</div>";
	}else{
		echo "<div style='width:800px; margin:0 auto; text-align:center; font-weight:bold; padding:25px; color:red'>Campo de imagem vazio.</div>";
	}

}

if(isset($_POST['pesquisar'])){
	$idrevend = $_POST['idrevend'];
	$nome = $_POST['nome'];
	$setor = $_POST['setor'];
	$cep = $_POST['cep'];
	$endereco = $_POST['endereco'];
	$cidade = $_POST['cidade'];
	$uf = $_POST['uf'];
	$bairro = $_POST['bairro'];

	$where = '1 = 1';
	if(!empty($idrevend))
		$where .= " AND id_revend = '$idrevend' ";
	if(!empty($nome))
		$where .= " AND nome_revend LIKE '%$nome%' ";
	if(!empty($setor))
		$where .= " AND Id_setor = '$setor' ";
	if(!empty($cep))
		$where .= " AND cep LIKE '%$cep%' ";
	if(!empty($endereco))
		$where .= " AND endereco LIKE '%$endereco%' ";
	if(!empty($cidade))
		$where .= " AND cidade LIKE '%$cidade%' ";
	if(!empty($uf))
		$where .= " AND uf = '%$uf%' ";
	if(!empty($bairro))
		$where .= " AND bairro LIKE '%$bairro%' ";

	$qry->executa("SELECT * FROM tb_demillus_revend WHERE $where LIMIT 500");
}

?>
<table style="width:1000px; margin:0 auto">
<tr bgcolor="#eeeeee">
  <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>PESQUISA DE RA</b></font></font><font size="4"><b> ::..</b></font></td>
</tr>
</table>
<table class="tabela"  BORDER=0 style="margin:0 auto; width:1000px">
<form action="#"  method="post">
  <tr>
  	<td>Id Revendendora: </td>
	<td><input type=text name="idrevend" ></td>
	<td>Nome: </td>
	<td><input type=text name="nome" ></td>
  </tr>
  <tr>
  	<td>Setor: </td>
	<td>
		<select name="setor">
			<option value=''>Selecione</option>
			<?php
			$qrySet->executa("SELECT id_setor FROM tb_demillus_revend GROUP BY id_setor ORDER BY id_setor ASC");
			for ($i=0;$i<$qrySet->nrw;$i++){
			    $qrySet->navega($i);
			?>
				<option><?php echo $qrySet->data['id_setor']; ?></option>
			<?php
			}
			?>
		</select>
	</td>
	<td>Cep: </td>
	<td><input type=text name="cep" ></td>
  </tr>
  <tr>
    <td>Endereço: </td>
    <td colspan="3"><input style="width: 100%" type=text name="endereco" ></td>
  </tr>
  <tr>
  	<td>UF: </td>
	<td><input type=text name="uf" ></td>
	<td>Cidade: </td>
	<td><input type=text name="cidade" ></td>
  </tr>
  <tr>
  	<td>Bairro: </td>
	<td><input type=text name="bairro" ></td>
  </tr>
  <tr>
  	 <td colspan="4"><input type=submit name="pesquisar" value="Pesquisar RA"></td>
  </tr>
</form>
</table>
<br><br><br>
<table class="tabela" style="width:1000px; margin:0 auto">
<tr bgcolor=#eeeeee>
	<td><b>Id Revendendora</b></td>
	<td><b>Nome</b></td>
	<td><b>Setor</b></td>
	<td><b>Cidade</b></td>
	<td><b>Bairro</b></td>
	<td><b>Gravar RG</b></td>
	<td><b>Ver RG</b></td>
</tr>
<?php
for($i=0;$i<$qry->nrw;$i++){
    $qry->navega($i);
?>
	<tr style="padding: 20px 0px;" <?php if($i%2 == 1): echo "bgcolor=#eeeeee"; endif; ?>>
		<td><?php echo $qry->data['id_revend']; ?></td>
		<td><?php echo $qry->data['nome_revend']; ?></td>
		<td><?php echo $qry->data['id_setor']; ?></td>
		<td><?php echo $qry->data['cidade']; ?></td>
		<td><?php echo $qry->data['bairro']; ?></td>
		<td style="padding: 10px 0px">
			<form enctype="multipart/form-data" action="#"  method="post">
				<input type="hidden" name="idrevend" value="<?php echo $qry->data['id_revend']; ?>">
				<input style="margin-bottom: 10px" type=file name="arquivo">
				<input type=submit name="atualizar" value="Cadastrar RG">
			</form>
		</td>
		<td><a target="_blank" href="<?php echo "cadastra_ra_foto.php?idrevend=".$qry->data['id_revend']; ?>">Ver Foto</a></td>
	</tr>
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
