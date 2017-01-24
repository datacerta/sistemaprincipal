/**
 * Classe LightBox
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015
 */
// seta variavel global
var jxServer = window.location.protocol+"//"+window.location.host;

/**
 * Cria a classe
 */
function jLightBox() {
	// propriedades do objeto
	this.xWidth   = 300;
	this.xHeight  = 100;
	this.objeto   = null;
	this.xFundo   = null;
	this.xIframe  = null;
	this.icone    = jxServer+"/inc/lightbox/ic_close.png";

	// cria a janela
	this.create();
}

/**
 * Metodo que cria a janela
 */
jLightBox.prototype.create = function() {
	// criacao da janela (DOM - Document Object Model)
	var xjanela                = document.createElement("div");
	    xjanela.className      = "xlightbox";
	    xjanela.style.position = "fixed";
	    xjanela.style.display  = "none";

	//***********************************************************
	// icone de fechar no topo
	//***********************************************************
	  // pega a imagem (icone)
	  var ximagem           = document.createElement("img");
	      ximagem.className = "xclose";
	      ximagem.setAttribute("src", this.icone);

	  // coloca a imagem na janela
	  xjanela.appendChild(ximagem);
	//***********************************************************
	
	//***********************************************************
	// iframe interno
	//***********************************************************
	var xiframe              = document.createElement("iframe");
		xiframe.className    = "xiframe";
		xiframe.src          = "";
		xiframe.frameBorder  = "0";
		xiframe.style.width  = this.xWidth  + "px";
		xiframe.style.height = this.xHeight + "px";

    // adiciona o iframe na janela
	xjanela.appendChild(xiframe);
	
	// acrescenta no Body
	document.body.appendChild(xjanela);

	// seta os objetos
	this.objeto  = xjanela;
	this.xIframe = xiframe;

	// pega o objeto local
	var xobjeto = this;

	// seta acao no botao fechar
	ximagem.onclick = function() { xobjeto.close(); };
};

/**
 * Metodos Setter's de tamanho e largura
 * 
 * @param width
 */
jLightBox.prototype.setWidth  = function(width)  { this.xWidth  = width;  };
jLightBox.prototype.setHeight = function(height) { this.xHeight = height; };

/**
 * Metodo que posiciona a janela
 */
jLightBox.prototype.setPosition = function() {
    // seta o tamanho do BODY
    var bodyWidth  = window.innerWidth  || document.documentElement.clientWidth  || document.body.clientWidth;
    var bodyHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

	// calcula as coordenadas
	var top  = (Math.ceil((bodyHeight - this.xHeight) / 2));
	var left = (Math.ceil((bodyWidth  - this.xWidth ) / 2));
	
	// redimensiona a janela
	this.objeto.style.top    = top  + "px";
	this.objeto.style.left   = left + "px";
	this.objeto.style.width  = this.xWidth  + "px";
	this.objeto.style.height = this.xHeight + "px";
	
	// acerta o tamanho do iframe
	this.xIframe.style.width  = this.xWidth  + "px";
	this.xIframe.style.height = this.xHeight + "px";
};

/**
 * Metodo que fecha a janela
 */
jLightBox.prototype.show  = function() { this.fundo(1); this.objeto.style.display = "block"; };
jLightBox.prototype.close = function() { this.fundo(0); this.objeto.style.display = "none";  };

/**
 * Metodo que seta a URL no iframe
 * 
 * @param url
 */
jLightBox.prototype.setURL = function(url) { this.xIframe.src = ""; this.xIframe.src = url; }; 

/**
 * Metodo que mostra/inibe o fundo
 * 
 * @param act
 */
jLightBox.prototype.fundo = function(act) {
	  // verifica se foi criado ou nao
	  if (!this.xFundo) {
	     // cria o DOM
	     this.xFundo           = document.createElement("div");
	     this.xFundo.className = "xlightboxFundo";

	     // coloca na body
	     document.body.appendChild(this.xFundo);
	  }

	  // executa a acao
	  this.xFundo.style.display = (act == 0) ? "none" : "block";
};