<?php
/**
 * Tela de Nova Revendedora
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel
$prgTit = "Cadastro de Nova Revendedora";

// pega a configuracao
require_once("inc/config.inc");

// seta o link atual
$reLink = HOST.$PHP_SELF."?token={$rnd}";

// icone de imprimir
$imgPrint = "<img src='".HOST."/inc/img/ic_print.png' alt='' title='Imprimir' />";

// pega o header
require_once("inc/header.inc");
?>
<!-- CSS Local -->
<link rel="stylesheet" href="<?=HOST?>/css/nova-revendedora.css" />

<!-- Script local -->
<script type="text/javascript" src="<?=HOST?>/js/nova-revendedora.js?token=<?=$rnd?>"></script>

<!-- Div principal -->
<div class="nova_revendedora">
    <!-- Lista -->

	

    <div class="lista">
        <?php require_once("nova-revendedora/lista.inc"); ?>
    </div>

	<!-- Mapa -->
	<iframe class="mapa" name="dadosLink" id="dadosLink" src=""></iframe>

	<!-- mostra o titulo -->
    <div class="titulo" id="dadosTitle">
		 <!-- imprimir -->
		 <a href="javascript:void()" onclick="imprimir()" class="print"><?=$imgPrint?></a>
	</div>	
</div>

<?php
// pega o Footer
require_once("inc/footer.inc");