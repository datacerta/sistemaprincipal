/**
 * Funcoes em JavaScript - MENU
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
/**
 * Funcao de redirecionamento
 */
function go(select_obj) {
	// pega as variaveis
	var partes_select_obj_value = select_obj.value.split("|");
	var parLink                 = partes_select_obj_value[1];
	var caracter                = (parLink.indexOf("?") > -1) ? "&" : "?";

	// redirecionamento
	window.location.href = host + "/" + parLink + caracter + "token=" + sData();
	
	//if (partes_select_obj_value[0] != "") { dg("div_link_ajuda_ferramenta").style.visibility = "visible"; }
	//else                                  { dg("div_link_ajuda_ferramenta").style.visibility = "hidden";  }
}

/**
 * Funcao de abre
 */
function abre() {
	// seta a URL
	var xUrl = host + "/selecao_ticket.php?token=" + sData();

    // abre janela	
	window.open(xUrl,"","width=800, height=500, top=0, left=0, scrollbars=no, menubar=no, directories=no, location=no, copyhistory=no, status=no, toolbar=no, maximized=yes, resizable=no");
}

/**
 * Funcao de slice
 */
function sliceMenu() {
	// pega a altura
	var bodyHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
	
	// pega a situacao atual
	var atual = dg("menuSlice").style.display;
	
	// seta a nova situacao
	dg("menuSlice").style.height  = (bodyHeight - 90) + "px";
	dg("menuSlice").style.display = ((atual == "none") || (atual.length == 0)) ? "block" : "none";
}

/**
 * Funcao que inibe o menu
 */
function inibeMenu() { dg("menuSlice").style.display = "none"; }