/**
 * Funcoes em JavaScript - NOVA REVENDEDORA
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel global
var janela = null;

/**
 * Funcao de inicializacao
 */
function init() {
	// recalcula
	resizeWin();
	
	// inibe
	dg("dadosTitle").style.display = "none";
}

/**
 * Funcao que recalculo
 */
function resizeWin() {
	// seta o tamanho do BODY
    var bodyWidth  = window.innerWidth  || document.documentElement.clientWidth  || document.body.clientWidth;
    var bodyHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

	// acerta o tamenho
	dg("dadosLink").style.width  = (bodyWidth  - 530) + "px"
	dg("dadosLink").style.height = (bodyHeight - 210) + "px"
	
	// posiciona o titulo
	dg("dadosTitle").style.width = (bodyWidth - 545) + "px";
}

/**
 * Funcao que mostra o MAPA
 */
function exibeMapa(cod) {
	// seta a URL
	var xUrl = host + "/nova-revendedora/dados.php?id=" + cod + "&rnd=" + sData();
	
	// carrega no iFrame
	loadFrame(xUrl, "dadosLink");
	
	// exibe o titulo
	dg("dadosTitle").style.display = "block";
}

/**
 * Funcao de impressao
 */
function imprimir() { window.frames["dadosLink"].print(); }