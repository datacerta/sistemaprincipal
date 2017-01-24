/**
 * Funcoes de AJAX
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015
 */
//seta variaveis global para o AJAX
var READY_STATE_UNINITIALISED = 0;
var READY_STATE_LOADING       = 1;
var READY_STATE_LOADED        = 2;
var READY_STATE_INTERACTIVE   = 3;
var READY_STATE_COMPLETE      = 4;

//--------------------------------------------------------------
// Funcao que chama o AJAX
//--------------------------------------------------------------
// Parametro recebido:
// url ........: URL a ser executada
// funcRet ....: Funcao de retorno
// HttpMethod .: method da pesquisa (GET|POST)  [opcional]
//--------------------------------------------------------------
function ajax_request(url, funcRet, HttpMethod) {
	// verifica o parametro do HttpMethod (default)
    if (!HttpMethod) { HttpMethod = "GET"; }

    // inicia variavel do objeto XMLHttpRequest
    var xRequest = null;

    // Procura por um objeto nativo
    if (window.XMLHttpRequest) { xRequest = new XMLHttpRequest();                   }  // Mozilla/Safari
    else                       { xRequest = new ActiveXObject("Microsoft.XMLHTTP"); }  // Internet Explorer

    // method
    xRequest.open(HttpMethod, url, true);
    
    // verifica se eh POST
    if (HttpMethod == "POST") {
        // seta o Header
    	xRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    }

    // ReadyStateChange (onReadyStateChange)
    xRequest.onreadystatechange = function() {
    	// apenas quando o estado for "completado"
        if (xRequest.readyState == READY_STATE_COMPLETE) {
        	// pega o retorno
            var resposta = xRequest.responseText;

            // apenas se o servidor retornar "OK"
            if (xRequest.status == 200) { if (resposta != "") { if (funcRet) { funcRet(resposta); }}}

            // apaga o objeto do Request
            delete xRequest;
        }
        else {}
    };

    // Send
    xRequest.send(null);
}