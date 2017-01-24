/**
 * Classe Alert
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015
 */
// seta variavel global
var jxServer = window.location.protocol+"//"+window.location.host;
var xalert   = null;

/**
 * Funcao que mostra o Alert
 * 
 * @param texto
 */
function alerta(texto) {
	xalert = null;
	xalert = new jAlert(texto);
	xalert.setPosition();
	xalert.show();
}

/**
 * Cria a classe
 */
function jAlert(xtexto) {
	// propriedades do objeto
	this.xWidth  = 260;
	this.xHeight = 120;
	this.jxFundo = null;
	this.objeto  = null;
	this.xTexto  = (!xtexto) ? "Alert" : xtexto;
	this.icone   = jxServer+"/inc/alert/ic_close.png";
	
	// cria a janela
	this.create();
}

/**
 * Metodo que cria a janela
 */
jAlert.prototype.create = function() {
	// criacao da janela (DOM - Document Object Model)
	var xjanela                = document.createElement("div");
	    xjanela.className      = "alerta";
	    xjanela.style.position = "fixed";
	    xjanela.style.display  = "none";

	//***********************************************************
	// icone de fechar no topo
	//***********************************************************
      // pega a imagem (icone)
      var ximagem = document.createElement("img");
          ximagem.setAttribute("src", this.icone);
	//***********************************************************

  	//***********************************************************
    // Texto
  	//***********************************************************
      var xspan           = document.createElement("p");
	      xspan.innerHTML = this.xTexto;
    //***********************************************************
    
	// coloca na janela os componentes
	xjanela.appendChild(ximagem);
	xjanela.appendChild(xspan);
      
  	// coloca a janela no Body
  	document.body.appendChild(xjanela);

  	// seta os objetos
  	this.objeto = xjanela;

  	// pega o objeto local
  	var xobjeto = this;

  	// seta acao no botao fechar
  	ximagem.onclick = function() {xobjeto.close();};
};

/**
 * Metodo que posiciona a janela
 */
jAlert.prototype.setPosition = function() {
    // seta o tamanho do BODY
    var bodyWidth  = window.innerWidth  || document.documentElement.clientWidth  || document.body.clientWidth;
    var bodyHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

	// calcula as coordenadas
	var top  = (Math.ceil((bodyHeight - this.xHeight) / 2)) - 50;
	var left = (Math.ceil((bodyWidth  - this.xWidth ) / 2));

	// redimensiona a janela
	this.objeto.style.top    = top  + "px";
	this.objeto.style.left   = left + "px";
};

/**
 * Metodo que fecha a janela
 */
jAlert.prototype.show  = function() { this.fundo(1); this.objeto.style.display = "block"; };
jAlert.prototype.close = function() { this.fundo(0); this.objeto.style.display = "none";  };

/**
 * Metodo que mostra/inibe o fundo
 * 
 * @param act
 */
jAlert.prototype.fundo = function(act) {
	  // verifica se foi criado ou nao
	  if (!this.jxFundo) {
	     // cria o DOM
	     this.jxFundo           = document.createElement("div");
	     this.jxFundo.className = "alertaFundo";

	     // coloca na body
	     document.body.appendChild(this.jxFundo);
	  }

	  // executa a acao
	  this.jxFundo.style.display = (act == 0) ? "none" : "block";
};