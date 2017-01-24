<?php
/**
 * Cadastro Campanh Demillus
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel
$prgTit = "Cadastro Campanha Demillus";

// pega a configuracao
require_once("inc/config.inc");
require_once("inc/class/class.pagina.php");

// seta links
$_Exec  = HOST."/Exec/cadastro-campanha-demillus.php?token={$rnd}";
$_Lis   = HOST."/cadastro-campanha-demillus.php";
$_Busca = HOST."/includes/cadastro-campanha-demillus/busca.php?token={$rnd}";
$_condi = "";

// seta a condicao de busca
if (!empty($BUSCA1)) { $_condi .= (empty($_condi)) ? "WHERE (num_campanha = '{$BUSCA1}')" : " AND (num_campanha = '{$BUSCA1}')"; }
if (!empty($BUSCA2)) { $_condi .= (empty($_condi)) ? "WHERE (num_setor    = '{$BUSCA2}')" : " AND (num_setor    = '{$BUSCA2}')"; }

// cria o modulo de consulta
$lqry = new consulta($con);

// monta a query
$lsql = "SELECT id          ,
                num_campanha,
				to_char(data_coleta , 'DD/MM/YYYY') AS dt_coleta ,
				to_char(data_entrega, 'DD/MM/YYYY') AS dt_entrega,
				nome_cidade,
				num_setor  ,
				uf
				FROM tb_demillus_campanha
				{$_condi}
				ORDER BY num_campanha DESC,
				         data_coleta  DESC,
						 data_entrega ASC ,
						 num_setor    ASC";

// monta a paginacao
$paginacao = new PAGINA();
$paginacao->setSQL($lsql);
$paginacao->setPhpSELF($_Lis);
$paginacao->setNum(20);

// executa a query
$lqry->executa($lsql." ".$paginacao->limite());

// numero de linhas
$num_lin = $lqry->nrw;

// pega o header
require_once("inc/header.inc");
?>

<!-- JS Local -->
<script type="text/javascript" src="<?=HOST?>/js/cadastro-campanha-demillus/lista.js?token=<?=$rnd?>"></script>

<!-- monta a tabela de lista -->
<div class="lista">
    <form action="<?=$_Busca?>" name="fBusca" method="post" onsubmit="return validar(this)">
	<table style="width: auto;">
	<tr>
	    <td style="padding: 8px 20px 8px 0px;"><strong>Campanha:</strong></td>
		<td style="padding: 8px 20px 8px 8px;"><strong>Setor:</strong></td>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
	    <td style="padding: 8px 20px 8px 0px;"><input type="text" name="txt_campanha" class="campo" size="10" value="<?=$BUSCA1?>" /></td>
		<td style="padding: 8px 20px 8px 8px;"><input type="text" name="txt_setor"    class="campo" size="10" value="<?=$BUSCA2?>" /></td>
		<td style="padding: 8px 10px 8px 8px;"><button type="submit" class="submit">Buscar</button></td>
		<td style="padding: 8px 20px 8px 8px;"><button type="button" class="cancel" onclick="incluir()">Incluir</button></td>
	</tr>
    </table>
    </form>

	<br />
	
	<table>
    <tr>
        <th width="8%">Campanha</th>
		<th width="10%">Coleta</th>
		<th width="10%">Entrega</th>
		<th>Cidade</th>
		<th width="6%">UF</th>
		<th width="6%">Setor</th>
		<th width="3%" colspan="2">&nbsp;</th>
    </tr>
	<?php
		// percorre o resultado da query
		for ($ii = 0; $ii < $num_lin; $ii++) {
			// seta a classe de zebra
            $xclasse = (($ii % 2) == 0) ? "" : "class='zebra'";

			// navega
            $lqry->navega($ii);

			// pega o ID
			$id = $lqry->data["id"];

			// seta os Links
			$edLink = "<a href='javascript:void()' onclick='editar({$id})'>{$iEditar}</a>";
			$exLink = "<a href='javascript:void()' onclick='excluir({$id})'>{$iExcluir}</a>";
	?>
	<tr onmouseover="fLightTr(this,'#CCE1F0')" onmouseout="fLightTr(this,this.bgColor)" <?=$xclasse?>>
		<td align="center"><?=$lqry->data["num_campanha"]?></td>
		<td align="center"><?=$lqry->data["dt_coleta"]?></td>
		<td align="center"><?=$lqry->data["dt_entrega"]?></td>
		<td><?=$lqry->data["nome_cidade"]?></td>
		<td align="center"><?=$lqry->data["uf"]?></td>
		<td align="center"><?=$lqry->data["num_setor"]?></td>
		<td align="center"><?=$edLink?></td>
		<td align="center"><?=$exLink?></td>
	</tr>	  
	<?php } ?>
	</table>
	
	<!-- paginacao -->
	<?php echo $paginacao->menu();?>
	
</div>

<?php
// pega o Footer
require_once("inc/footer.inc");