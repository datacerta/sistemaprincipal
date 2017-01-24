/**
 * Funcoes globais em JavaScript
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel local
var swidth       = parseInt(window.screen.width );
var sheight      = parseInt(window.screen.height);
var loadingState = 0;
var site         = "";
var xChec        = true;

/**
 * Funcao que gera um numero randomico
 */
function sData(){var zeit=new Date();var ms=(zeit.getHours() * 24 * 60 * 1000) + (zeit.getMinutes() * 60 * 1000) + (zeit.getSeconds() * 1000) + zeit.getMilliseconds();return ms;}

//**************************************************************
// formata uma mascara
//**************************************************************
// exemplo de uso:
//
// onKeyPress="return setMASCARA(this, '999.999.999-99',event)" (CPF)
// onKeyPress="return setMASCARA(this, '###-9999',event)"       (Placa)
//
// tipos de mascara:
// 9 = (numerico)
// # = (string)
//**************************************************************
// teclas:
// 0  = (TAB)
// 8  = (BACKSPACE)
// 13 = (ENTER)
//**************************************************************
function setMASCARA(este, mask, evento) {
  // seta variaveis
  var key;

  // verifica
  if     (window.event) { key = window.event.keyCode; }
  else if(evento)       { key = evento.which; }
  else                  { return false; }

  // tecla ENTER pressionada
  if (key == 13 || key == 8 || key == 0) { return true; }

  // pega o codigo da tecla
  var keychar = String.fromCharCode(key);

  // pega os totais
  var i = este.value.length;
  var m = mask.length - 1;

  // verifica o total de mascara com o total do campo
  if (i > m) { return false; }
  else
  {
    // pega a mascara
    var texto = mask.substring(i);
    var saida = texto.substring(0,1);

    // verifica
    if      (saida == "9") { if (("0123456789").indexOf(keychar) > -1) { return true; }}
    else if (saida == "#")
    {
        if      ((("abcdefghijklmnopqrstuvwxyz ").indexOf(keychar) > -1)) { return true; }
        else if ((("ABCDEFGHIJKLMNOPQRSTUVWXYZ ").indexOf(keychar) > -1)) { return true; }
    }
    else
    {
      // coloca a separacao no campo texto
      este.value += saida;

      // pega o proximo da mascara
      var texto1 = mask.substring(i+1);
      var saida1 = texto1.substring(0,1);

      // realiza uma nova validacao
      if      (saida1 == "9") { if (("0123456789").indexOf(keychar) > -1) { return true; }}
      else if (saida1 == "#")
      {
        if      ((("abcdefghijklmnopqrstuvwxyz ").indexOf(keychar) > -1)) { return true; }
        else if ((("ABCDEFGHIJKLMNOPQRSTUVWXYZ ").indexOf(keychar) > -1)) { return true; }
      }
    }

    // retorna
    return false;
  }
}

//**************************************************************
// Funcao que verifica se eh data
//**************************************************************
String.prototype.isDate = function() {
	var bissexto = 0;
    var data     = this;
    var tam      = data.length;
    if (tam == 10) {
        var dia = data.substr(0,2);
        var mes = data.substr(3,2);
        var ano = data.substr(6,4);
        if ((ano > 1900)||(ano < 2100)) {
           switch (mes) {
             case '01':
             case '03':
             case '05':
             case '07':
             case '08':
             case '10':
             case '12': if  (dia <= 31) { return true; } break;
             case '04':
             case '06':
             case '09':
             case '11': if  (dia <= 30) { return true; } break;
             case '02':
                 /* Validando ano Bissexto / fevereiro / dia */
                 if ((ano % 4 == 0) || (ano % 100 == 0) || (ano % 400 == 0))  { bissexto = 1; }
                 if ((bissexto == 1) && (dia <= 29))                          { return true;  }
                 if ((bissexto != 1) && (dia <= 28))                          { return true;  }
                 break;
           }
        }
    }
    return false;
};

//**************************************************************
//Funcao para validar o CNPJ
//**************************************************************
function isCNPJ(pcnpj) {
	// seta va riaveis
	var cnpj = pcnpj.replace(/[^\d]+/g,'');

    if (cnpj == '') return false;
  
    if (cnpj.length != 14) { return false; }

    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" || 
        cnpj == "11111111111111" || 
        cnpj == "22222222222222" || 
        cnpj == "33333333333333" || 
        cnpj == "44444444444444" || 
        cnpj == "55555555555555" || 
        cnpj == "66666666666666" || 
        cnpj == "77777777777777" || 
        cnpj == "88888888888888" || 
        cnpj == "99999999999999") { return false; }
      
    // Valida DVs
    var tamanho = cnpj.length - 2;
    var numeros = cnpj.substring(0,tamanho);
    var digitos = cnpj.substring(tamanho);
    var soma    = 0;
    var pos     = tamanho - 7;
    for (var i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) { pos = 9; }
    }
    var resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0)) { return false; }
      
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma    = 0;
    pos     = tamanho - 7;
    for (var i = tamanho; i >= 1; i--) {
         soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) { pos = 9; }
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1)) { return false; }

    // validado OK
    return true;
}

/**
 * Funcao de validacao de CPF
 * 
 * @param cpf
 * @returns {Boolean}
 */
function isCPF(cpf) {  
    cpf = cpf.replace(/[^\d]+/g,'');    
    if (cpf == '') { return false; } 
    // Elimina CPFs invalidos conhecidos    
    if (cpf.length != 11 || 
        cpf == "00000000000" || 
        cpf == "11111111111" || 
        cpf == "22222222222" || 
        cpf == "33333333333" || 
        cpf == "44444444444" || 
        cpf == "55555555555" || 
        cpf == "66666666666" || 
        cpf == "77777777777" || 
        cpf == "88888888888" || 
        cpf == "99999999999") { return false; }

    // Valida 1o digito 
    add = 0;    
    for (var i = 0; i < 9; i ++) { add += parseInt(cpf.charAt(i)) * (10 - i); }  
    rev = 11 - (add % 11);  
    if (rev == 10 || rev == 11) { rev = 0; }
    if (rev != parseInt(cpf.charAt(9))) { return false; }
    
    // Valida 2o digito 
    add = 0;    
    for (var i = 0; i < 10; i ++) { add += parseInt(cpf.charAt(i)) * (11 - i); }  
    rev = 11 - (add % 11);  
    if (rev == 10 || rev == 11) { rev = 0; }
    if (rev != parseInt(cpf.charAt(10))) { return false; }
    return true;   
}

//********************************************************
// Funcao que valida um e-mail
//********************************************************
function isEmail(email) {
	if (/^[a-zA-Z0-9][a-zA-Z0-9\._-]+@([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2}/.exec(email)) { return true; }
    else { return false; }
}

//********************************************************
//funcao que retorna um OBJETO
//********************************************************
function dg(id) { return document.getElementById(id); }

/**
 * Funcao que carrega uma URL em um iFrame
 */
function loadFrame(url,objeto) { dg(objeto).src = url; }

//**************************************************************
// Funcao que coloca uma cor no fundo de uma linha na tabela
//**************************************************************
// Parametros da funcao:
// tr .: linha da tabela (TAG tr)
// cor : cor que vai ser aplicada (em hexa)
//**************************************************************
function fLightTr(tr,cor) {
	var td     = tr.getElementsByTagName("td");
    var qtd_td = td.length;

    for(var i=0; i < qtd_td; i++) {
    	td[i].style.backgroundColor = cor;
        if(td[i].innerText) { td[i].title = td[i].innerText; }
    }
}

//**********************************************************
// Funcao que carrega as imagens
//**********************************************************
function MM_preloadImages() {
	var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
	var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

//**********************************************************
// Funcao que seleciona/des-seleciona os CheckBox de um
// formulario
//**********************************************************
function fcheck(campo) {
	// pega os elementos do formulario
    var f = document.flista;

    // verifica o campo
    if (!campo) { campo = "code"; }
    
    // percorre o formulario
    for (var i = 0; i < f.length; i++) {
    	// percorre os elementos
        if (f.elements[i].type == "checkbox") {
        	// pega o campo local
        	var lcampo = (f.elements[i].name).substring(0, 4);

        	if (campo == lcampo) { f.elements[i].checked = xChec; }
        }
    }

    // seta o checked
    xChec = !xChec;
}

/**
 * Carrega o mouse
 */
//document.oncontextmenu = new Function("alert('Ação não permitida!'); return false;");