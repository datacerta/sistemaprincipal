/**
 * Funcoes em JavaScript - Header
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// variavel global
var host    = "";
var lastpag = 0;

/**
 * Funcao de inicializacao
 */
function init() {}

/**
 * Funcao que centraliza um BOX
 */
function centerBOX(box_name) {
    // seta o tamanho do BODY
	var bodyWidth  = window.innerWidth  || document.documentElement.clientWidth  || document.body.clientWidth;
    var bodyHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    
    // pega o tamanho e largura do BOX
    var bWidth  = dg(box_name).offsetWidth;
    var bHeight = dg(box_name).offsetHeight;
    
    // calcula a posicao TOP & LEFT
    var top  = (Math.ceil((bodyHeight - bHeight) / 2));
    var left = (Math.ceil((bodyWidth  - bWidth ) / 2));
    
    // posiciona o BOX no centro
    dg(box_name).style.top  = top  + "px";
    dg(box_name).style.left = left + "px";
}

/**
 * Funcao de clique no main
 */
function headerClick() {
	// verifica se o menu esta executando
	if (inibeMenu) { inibeMenu(); }
}

/**
 * Funcao que inibe/exibe o Preloader
 */
function hidePreLoader() { dg("preloader").style.display = "none";  }
function showPreLoader() { dg("preloader").style.display = "block"; }

// carrega o onload
window.onload = function() { hidePreLoader(); if (init) { init(); }};
  
// atualiza tela em quanto é feito o redimensionamento da tela
window.onresize = function() { if (resizeWin) { resizeWin(); }};