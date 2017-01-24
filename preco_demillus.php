<?php
require_once("inc/config.inc");

$qry   = new consulta($con);
$qry2   = new consulta($con);
$qry3   = new consulta($con);

$selfLink = HOST.$PHP_SELF;
require_once("inc/header.inc");
?>
<link href="<?=HOST?>/css/table_2.css" rel="stylesheet" type="text/css" />
<link href="<?=HOST?>/css/tip.css"     rel="stylesheet" type="text/css" />
<?php
/*
GRAVAR NOVO PREÇO
*/
if($_POST['novo_gravar'] == 1){
	$setorNovo = $_POST['setor'];
	$precoNovo = $_POST['preco'];
	$cidadeNovo = $_POST['cidade'];
	$estadoNovo = $_POST['estado'];
	$repasseNovo = $_POST['repasse'];

	$sql = "INSERT INTO tb_preco_demillus(setor, preco, cidade, codigoproduto, estado, repasse)
		VALUES ('".$setorNovo."','".$precoNovo."','".$cidadeNovo."','543','".$estadoNovo."','".$repasseNovo."')";
	$qry2->executa($sql);

	if ($qry2->res){
	?>
		<META http-equiv="refresh" content="0;URL=<?php echo HOST."/preco_demillus.php?msg=Cadastrado com sucesso!"; ?>">
	<?php
	}else{
	?>
		<META http-equiv="refresh" content="0;URL=<?php echo HOST."/preco_demillus.php?msg=".pg_last_error(); ?>">
	<?php
	}
	?>

<?php
/*
ALTERAR NOVO PREÇO
*/
}if($_POST['alterar_gravar'] == 1){

	$setorNovo = $_POST['setor'];
	$precoNovo = $_POST['preco'];
	$cidadeNovo = $_POST['cidade'];
	$estadoNovo = $_POST['estado'];
	$repasseNovo = $_POST['repasse'];
	$id = $_POST['id'];

	if(!empty($id)){
		$sql = "UPDATE tb_preco_demillus 
		SET setor = '".$setorNovo."', preco = '".$precoNovo."', cidade = '".$cidadeNovo."',  estado = '".$estadoNovo."', repasse = '".$repasseNovo."'
		WHERE id = ".$id;
		$qry3->executa($sql);
	}else{
	?>
		<META http-equiv="refresh" content="0;URL=<?php echo HOST."/preco_demillus.php?msg=Variavel id vazio!"; ?>">
	<?php
	}

	if ($qry3->res){
	?>
		<META http-equiv="refresh" content="0;URL=<?php echo HOST."/preco_demillus.php?msg=Alterado com sucesso!"; ?>">
	<?php
	}else{
	?>
		<META http-equiv="refresh" content="0;URL=<?php echo HOST."/preco_demillus.php?msg=".pg_last_error(); ?>">
	<?php
	}
	?>
<?php
/*
PESQUISAR PREÇO
*/
}elseif($_POST['enviar'] == 'Pesquisar'){
	$sql = "SELECT pd.id, pd.setor, pd.preco, pd.repasse, pd.cidade, pd.estado, p.nomeproduto 
	FROM tb_preco_demillus pd  
	INNER JOIN tbproduto p ON pd.codigoproduto = p.codigoproduto ";
	$sql .= " WHERE 1 = 1 ";
	if(!empty($_POST['preco'])){
		$sql .= " AND preco = '".$_POST['preco']."'";
	}
	if(!empty($_POST['setor'])){
		$sql .= " AND setor = '".$_POST['setor']."'";
	}
	$sql .= " ORDER BY pd.setor";

	$qry->executa($sql);
	if ($qry->nrw){
		?>
		<table class="tabela" style="width: 800px; margin:0 auto">
		<tr>
			<th><div align="center"><font size="2">Produto</font> </div></th>
			<th><div align="center"><font size="2">Setor</font> </div></th>
			<th><div align="center"><font size="2">Preço</font> </div></th>
			<th><div align="center"><font size="2">Repasse</font> </div></th>
			<th><div align="center"><font size="2">Cidade</font> </div></th>
			<th><div align="center"><font size="2">Estado</font> </div></th>
			<th><div align="center"><font size="2">Ações</font> </div></th>
		</tr>
		<?php
		for($i=0;$i<$qry->nrw;$i++){
			$qry->navega($i);
		?>
			<tr>
				<td><?php echo $qry->data["nomeproduto"]; ?></td>
				<td><?php echo $qry->data["setor"]; ?></td>
				<td><?php echo $qry->data["preco"]; ?></td>
				<td><?php echo $qry->data["repasse"]; ?></td>
				<td><?php echo $qry->data["cidade"]; ?></td>
				<td><?php echo $qry->data["estado"]; ?></td>
				<td><a href="<?php echo $selfLink."?alterar=1&id=".$qry->data["id"]; ?>"><img src="<?=HOST?>/imagens/alterar_icon.gif"></a></td>
			</tr>
		<?php
		}//FIM FOR
		?>
		</table>
	<?php
	}else{
	?>
		<table class="tabela" style="width: 800px; margin:0 auto">
			<tr>
				<th colspan="4"><div align="center"><font size="2">DADOS NÃO ENCONTRADOS</font> </div></th>
			</tr>
		</table>
	<?php	
	}
	?>
	<div style="width:800px; margin:30px auto; text-align:left"><a href="<?php echo HOST."/preco_demillus.php"; ?>">VOLTAR</a></div>
<?php
/*
CRIAR NOVO
*/
}elseif($_POST['enviar'] == 'Novo'){
?>
	<form name="form_novo" action="<?=$selfLink?>" method="post">
		<input type="hidden" name="novo_gravar" value="1" /> 
		<table class="tabela" style="width: 800px; margin:0 auto">
			<tr>
				<th colspan="2"><div align="center"><font size="2">CADASTRO DE PREÇO</font> </div></th>
			</tr>
			<tr>
				<td>SETOR</td>
				<td><input type="text" name="setor" /></td>
			</tr>
			<tr>
				<td>PREÇO</td>
				<td><input type="text" name="preco"  /></td>
			</tr>
			<tr>
				<td>REPASSE</td>
				<td><input type="text" name="repasse"  /></td>
			</tr>
			<tr>
				<td>CIDADE</td>
				<td><input type="text" name="cidade"  /></td>
			</tr>
			<tr>
				<td>ESTADO</td>
				<td><input type="text" name="estado" /></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="enviar" value="Gravar"></td>
			</tr>
		</table>
	</form>
	<div style="width:800px; margin:30px auto; text-align:left"><a href="<?php echo HOST."/preco_demillus.php"; ?>">VOLTAR</a></div>
<?php 
/*
ALTERAR PRECO
*/
}elseif(isset($_GET['alterar'])){
	$sql = "SELECT pd.id, pd.setor, pd.preco, pd.repasse, pd.cidade, pd.estado
	FROM tb_preco_demillus pd  
	WHERE pd.id = ".$_GET['id'];

	$qry->executa($sql);
	if ($qry->nrw){
	?>
		<form name="form_altera" action="<?=$selfLink?>" method="post">
			<input type="hidden" name="alterar_gravar" value="1" /> 
			<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" /> 	
			<table class="tabela" style="width: 800px; margin:0 auto">
				<tr>
					<th colspan="2"><div align="center"><font size="2">PARAMETROS PARA PESQUISA</font> </div></th>
				</tr>
				<tr>
					<td>SETOR</td>
					<td><input type="text" name="setor" value="<?php echo $qry->data["setor"]; ?>" /></td>
				</tr>
				<tr>
					<td>PREÇO</td>
					<td><input type="text" name="preco" value="<?php echo $qry->data["preco"]; ?>" /></td>
				</tr>
				<tr>
					<td>REPASSE</td>
					<td><input type="text" name="repasse" value="<?php echo $qry->data["repasse"]; ?>" /></td>
				</tr>
				<tr>
					<td>CIDADE</td>
					<td><input type="text" name="cidade" value="<?php echo $qry->data["cidade"]; ?>" /></td>
				</tr>
				<tr>
					<td>ESTADO</td>
					<td><input type="text" name="estado" value="<?php echo $qry->data["estado"]; ?>" /></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="enviar" value="Alterar"></td>
				</tr>
			</table>
		</form>
	<?php
	}else{
	?>
		<table class="tabela" style="width: 800px; margin:0 auto">
			<tr>
				<th colspan="4"><div align="center"><font size="2">DADOS NÃO ENCONTRADOS</font> </div></th>
			</tr>
		</table>	
	<?php	
	}
	?>
	<div style="width:800px; margin:30px auto; text-align:left"><a href="<?php echo HOST."/preco_demillus.php"; ?>">VOLTAR</a></div>
<?php
/*
CAMPOS DE PESQUISA
*/
}else{
?>
	<div style="width:800px; margin:0 auto; text-align:center; margin-bottom:25px">
	<?php
		if(!empty($_GET['msg']))
			echo $_GET['msg'];
	?>
	</div>
	<form name="form_busca" action="<?=$selfLink?>" method="post">
		<table class="tabela" style="width: 800px; margin:0 auto">
			<tr>
				<th colspan="4"><div align="center"><font size="2">PARAMETROS PARA PESQUISA</font> </div></th>
			</tr>
			<tr>
				<td>SETOR</td>
				<td><input type="text" name="setor" /></td>
				<td>PREÇO</td>
				<td><input type="text" name="preco" /></td>
			</tr>
			<tr>
				<td colspan="4"><input type="submit" name="enviar" value="Pesquisar" style="margin-right:20px"><input name="enviar" type="submit" value="Novo"></td>
			</tr>
		</table>
	</form>
<?php
}
// pega o Footer
require_once("inc/footer.inc");