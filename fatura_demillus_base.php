<?php
/**
 * Fatura Demillus Base
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel
$prgTit = "Faturamento Demillus";
$prgPri = "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void()' onclick='imprimir()'>Imprimir</a>";

// pega a configuracao
require_once("inc/config.inc");

// seta o link atual
$include = HOST."/includes/fatura-demillus-base/lista.php?idmenu=1&token={$rnd}";

// verifica o redioreciona
if ($REDIRECIONA) {
	// carrega os campos
	require_once("includes/fatura-demillus-base/redireciona.php");

	// acrescenta no link
	$include .= "&data_de={$data_de}";
    $include .= "&data_ate={$data_ate}";
    $include .= "&tipo_data={$tipo_data}";
    $include .= "&lote_de={$lote_de}";
    $include .= "&lote_ate={$lote_ate}";
    $include .= "&tipo_lote={$tipo_lote}";
    $include .= "&codbase={$codbase}";
    $include .= "&codcliente={$codcliente}";
    $include .= "&codigoproduto={$codigoproduto}";
    $include .= "&opt={$opt}";
    $include .= "&ver_detalhes={$ver_detalhes}";
    $include .= "&numlotecliente={$numlotecliente}";
    $include .= "&dataemissao={$dataemissao}";
    $include .= "&tot_lote={$tot_lote}";
}

// seta o parent e redireciona
$_SESSION["PARENT"     ] = true;
$_SESSION["REDIRECIONA"] = false;

// pega o header
require_once("inc/header.inc");
?>
<!-- JS Local -->
<script type="text/javascript">
  /**
   * Funcao de inicializacao
   */
  function init() { resizeWin(); }

  /**
   * Funcao que recalculo
   */
  function resizeWin() {
	// seta o tamanho do BODY
    var bodyHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

	// mostra os iFrames
	document.getElementById("telaL").style.height = (bodyHeight - 220) + "px";
  }
  
  /**
   * Funcao de impressao
   */
  function imprimir() { window.frames["telaL"].print(); }
</script>

<!-- Tela -->
<iframe src="<?=$include?>" name="telaL" id="telaL" style="border: none; width: 100%;"></iframe>

<?php
// pega o Footer
require_once("inc/footer.inc");