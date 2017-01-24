<?php
/**
 * Menu - Include no Sistema
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// declaração de objetos
$qry  = new consulta($con);
$qry2 = new consulta($con);
$sql  = new consulta($con);

// inicia variaveis
$qtd_msg_nao_lidas_privadas = 0;
$qtd_msg_nao_lidas          = 0;

// cria os links de atlahos
$lAtalho01 = HOST."/baixa_lista.php?idmenu=1&token={$rnd}";
$lAtalho02 = HOST."/buscar.php?idmenu=1&token={$rnd}";
$lAtalho03 = HOST."/buscar_riocard.php?idmenu=1&token={$rnd}";
$lAtalho04 = HOST."/buscar_unimed_leste.php?idmenu=1&token={$rnd}";
$lAtalho05 = HOST."/buscar_unimed.php?idmenu=1&token={$rnd}";
$lAtalho06 = HOST."/selecao_lista.php?idmenu=1&token={$rnd}";
$lAtalho07 = HOST."/altera_senha.php?idmenu=1&token={$rnd}";
$lAtalho08 = HOST."/or.php?idmenu=1&token={$rnd}";
$lAtalho09 = HOST."/logoff.php?idmenu=1&token={$rnd}";

// pega a imagem do Drawer
$imgDrawer = "<img src='".HOST."/inc/img/ic_drawer.png' alt='' title='Menu Principal' />";
?>

<!-- Script local -->
<script type="text/javascript" src="<?=HOST?>/inc/js/menu.js?token=<?=$rnd?>"></script>

<!-- Drawer -->
<a href="javascript:void()" onclick="sliceMenu()" class="drawer"><?=$imgDrawer?></a>

<!-- Menu lateral - Slace -->
<div id="menuSlice">
    <!-- Titulo -->
    <div class="title">Menu</div>

    <?php
	    // query do MENU
        $_sql = "SELECT distinct(a.idphp),
	                    b.nomelink       ,
					    b.descricao
				 FROM   tbmenu AS a,
				        tbphp  AS b
				 WHERE (a.idphp    = b.idphp)
				   AND (a.codlogin = '{$idUser}')
				 ORDER BY b.descricao ASC";

		// executa a consulta
		$qry->executa($_sql);

		if ($qry->nrw > 0) { echo "<ul>"; }
		
        for ($i = 0; $i < $qry->nrw; $i++) {
		    // navega
		    $qry->navega($i);
	
	        if      ($qry->data["idphp"] ==   2) { $tem_permissao_oplista       = 1; }
	        else if ($qry->data["idphp"] ==   3) { $tem_permissao_baixa         = 1; }
	        else if ($qry->data["idphp"] ==   5) { $tem_permissao_buscar        = 1; }
	        else if ($qry->data["idphp"] == 565) { $tem_permissao_riocard       = 1; }
	        else if ($qry->data["idphp"] == 126) { $tem_permissao_buscar_unimed = 1; }
	        else if ($qry->data["idphp"] == 562) { $tem_permissao_leste         = 1; }

            // link
	        $qry2->data["link"] = "";
	        
			// monta a query e executa
			$sql2 = "SELECT link FROM tbajuda WHERE (idphp = ".$qry->data["idphp"].")";
	        $qry2->executa($sql2);
	
	        // pega o link
			$llink  = $qry2->data["link"];
			$nlink  = HOST."/".$qry->data["nomelink"]; //."?idmenu=1&token={$rnd}";
			$nlink .= (strpos($nlink, "?") > -1) ? "&idmenu=1&token={$rnd}" : "?idmenu=1&token={$rnd}";
	
	        // mostra a opcao
	        echo "<li><a href='{$nlink}'>".$qry->data["descricao"]."</a></li>\n";
        }
 
        // seta o link especial
		$elink = HOST."/conserta_lista_tbentrega.php?token={$rnd}";
 
        // acrescentado a correcao de lista soh pra liana ou web ou daniel ou ricardo
        if (($idUser == "45") || ($idUser == "1") || ($idUser == "78") || ($idUser == "6")) {
			echo "<li><a href='{$elink}'>Corre&ccedil;&atilde;o de Lista</a></li>\n";
        }
        
		// finaliza o UL
		if ($qry->nrw > 0) { echo "</ul>"; }
	?>

</div>

<div class="bgfill1">
	<ul class="context right">
	    <?php if ($tem_permissao_baixa        ) {?><li><a href="<?=$lAtalho01?>">Baixa</a></li><?php }?>
		<?php if ($tem_permissao_buscar       ) {?><li><a href="<?=$lAtalho02?>">Pesquisa</a></li><?php }?>
		<?php if ($tem_permissao_riocard      ) {?><li><a href="<?=$lAtalho03?>">Atalho para Pesquisa</a></li><?php }?>
		<?php if ($tem_permissao_leste        ) {?><li><a href="<?=$lAtalho04?>">Atalho para Pesquisa</a></li><?php }?>
        <?php if ($tem_permissao_buscar_unimed) {?><li><a href="<?=$lAtalho05?>">Pesquisa</a></li><?php }?>
		
		<!-- Diversos -->
		<?php if($tem_permissao_oplista || $tem_permissao_buscar) {?>
		    <li><a href="<?=$lAtalho06?>">Listas</a></li>
			<li><a href="<?=$lAtalho07?>">Alterar Senha</a></li>

		    <li><a href="<?=$lAtalho08?>">E-Ticket (<?=$qtd_msg_nao_lidas_privadas?>/<?=$qtd_msg_nao_lidas?>)</a> 
		<?php }?>
		
		<!-- Logout -->
		<li><a href="<?=$lAtalho09?>">Sair</a></li>
	</ul>
</div>