<?
set_time_limit(0);
ini_set("memory_limit","99M");
ini_set("max_execution_time","99999");

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

//inclui biblioteca de controles
include("classes/diversos.inc.php");

//testa sessão
if (VerSessao()==false){
	header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}
$qry = new consulta($con);
$qry1 = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry6 = new consulta($con);
$qry7 = new consulta($con);
$qry8 = new consulta($con);
$qry9 = new consulta($con);

$numero_nota_demillus = $_GET['numero_nota_demillus'];
$numero_setor = $_GET['numero_setor'];	    
$datanota_demillus = $_GET['datanota_demillus'];
$qry1->executa("SELECT * FROM tbnotademillus WHERE datanota_demillus = '".$datanota_demillus."' AND numero_setor = '".$numero_setor."'");

$tot_cont = $qry1->nrw;

if ($tot_cont > 0) {	

	$data_emissao_cte_ano = new DateTime(str_replace('/', '-',$qry1->data['dataemissao_cte']));			
	$file = "CT".$numero_nota_demillus.$data_emissao_cte_ano->format('y').".txt";		
	$fd=fopen("arquivos/".$file, "w");
	
	$l_01_cnpj = '19232334000119';
	$l_01_ie = '142997991110';
	$l_01_cmdf = '57';
	$l_01_seriedf = '0001';
	$l_01_numdf = str_pad($qry1->data['numero_nota_demillus'], 9, "0", STR_PAD_LEFT);
	$l_01_cfop = '5352';
	$l_01_chavecte = $qry1->data['cte'];
	$l_01_datadocfiscal = str_replace('/', '', $qry1->data['dataemissao_cte']);
	$l_01_dataaqserv = str_replace('/', '', $qry1->data['dataemissao_cte']);
	$l_01_tipocte = '0';
	$l_01_docfiscaltot = (str_replace(',', '.',$qry1->data['frete'])+str_replace(',', '.',$qry1->data['icms']));
	$l_01_prestservtot = (str_replace(',', '.',$qry1->data['frete'])+str_replace(',', '.',$qry1->data['icms']));
	$l_01_aliqicms = '012.00';
	$l_01_codicms = '00';
	$l_01_baseicms = (str_replace(',', '.', $qry1->data['frete'])+str_replace(',', '.', $qry1->data['icms']));
	$l_01_valicms = str_replace(',', '.', $qry1->data['icms']);
	$l_01_valtot = str_replace(',', '.', $qry1->data['valor']);
	$l_01_fatura = $numero_nota_demillus.$data_emissao_cte_ano->format('y');
	$l_01_infcompl = 'informacao complementar do doc fiscal';
	if(strlen($l_01_infcompl) > 255)
		$l_01_infcompl = substr($l_01_infcompl,0,255);
	$l_01_infcompl = '';
	
	fwrite($fd,"|01|".
	$l_01_cnpj."|".
	$l_01_ie."|".
	$l_01_cmdf."|".
	$l_01_seriedf."|".
	$l_01_numdf."|".
	$l_01_cfop."|".
	$l_01_chavecte."|".
	$l_01_datadocfiscal."|".
	$l_01_dataaqserv."|".
	$l_01_tipocte."|".
	$l_01_docfiscaltot."|".
	$l_01_prestservtot."|".
	$l_01_aliqicms."|".
	$l_01_codicms."|".
	$l_01_baseicms."|".
	$l_01_valicms."|".
	$l_01_valtot."|".
	$l_01_fatura."|".
	$l_01_infcompl."|".
	"\r\n");

	$l_02_nomecomp = 'Frete Peso';
	$l_02_valcomp = str_replace(',', '.', $qry1->data['frete']);

	fwrite($fd,"|02|".
	$l_02_nomecomp."|".
	$l_02_valcomp."|".
	"\r\n");

	$l_02_nomecomp = 'Outros';
	$l_02_valcomp = str_replace(',', '.', $qry1->data['icms']);

	fwrite($fd,"|02|".
	$l_02_nomecomp."|".
	$l_02_valcomp."|".
	"\r\n");

	$l_03_codunmed = '01';
	$l_03_tipomedida = 'PESO BRUTO';
	$l_03_quantidade = str_replace(',', '.', $qry1->data['peso']);

	fwrite($fd,"|03|".
	$l_03_codunmed."|".
	$l_03_tipomedida."|".
	$l_03_quantidade."|".
	"\r\n");

	$l_04_numnota = str_pad($qry1->data['primeira_nota'], 9, "0", STR_PAD_LEFT);
	$l_04_datanf = str_replace('/', '', $qry1->data['datanota_demillus']);;

	fwrite($fd,"|04|".
	$l_04_numnota."|".
	$l_04_datanf."|".
	"\r\n");

	$con->desconecta();	
	fclose($fd);//Fecha o arquivo
	//faz o download do arquivo
	download_arquivo_texto("$file","arquivos/","");//funcao da diversos para fazer download do arquivo
}else{
	exit();
}



		
/********************************************
************** FUNÇÔES **********************
*********************************************/

function zeros(&$cas)//zeros é uma função que retorna zeros a esquerda do número
{
	
	if ($cas<10)
	return("0".$cas);
	else
	return($cas);
}

function espaco_branco($tam)//sem_traço é uma função que retira os traços da data.
{
	for($i=0;$i<$tam;$i++){
		$esp=$esp." ";
	}
	return($esp);
}

function sem_traco(&$data)//sem_traço é uma função que retira os traços da data.
{
	for ($j=0;$j<10;$j++){
		if ($data[$j]=='-'){
			for ($l=$j;$l<9;$l++){
				$data[$l]=$data[$l+1];
				$data[$l+1]=" ";
			}
		}
	}
	
	return($data);
}

function casa_dec(&$cas)//casa_dec é uma função que retorna zeros a esquerda do número
//auto-sequencial (formatação exigida pelo arquivo de exportação).
{
	
	
  if (strlen($cas)==1)
	return("000000".$cas);
	else
  if (strlen($cas)==2)
	return("00000".$cas);
	else
	if (strlen($cas)==3)
	return("0000".$cas);
	else
	if (strlen($cas)==4)
	return("000".$cas);
	else
	if (strlen($cas)==5)
	return("00".$cas);
	
}

function casa_dec_10(&$cas)//casa_dec é uma função que retorna zeros a esquerda do número
//auto-sequencial (formatação exigida pelo arquivo de exportação).
{
	
	if ($cas<10)
	return("000000000".$cas);
	else
	if ($cas<100)
	return("00000000".$cas);
	else
	if ($cas<1000)
	return("0000000".$cas);
	else
	if ($cas<10000)
	return("000000".$cas);
	
}

function casa_dec_9(&$cas)//casa_dec é uma função que retorna zeros a esquerda do número
//auto-sequencial (formatação exigida pelo arquivo de exportação).
{
	
	if ($cas<10)
	return("00000000".$cas);
	else
	if ($cas<100)
	return("0000000".$cas);
	else
	if ($cas<1000)
	return("000000".$cas);
	else
	if ($cas<10000)
	return("00000".$cas);
	
}

function casa_dec_15(&$cas)//casa_dec é uma função que retorna zeros a esquerda do número
//auto-sequencial (formatação exigida pelo arquivo de exportação).
{
	
	if ($cas<10)
	return("00000000000000".$cas);
	else
	if ($cas<100)
	return("0000000000000".$cas);
	else
	if ($cas<1000)
	return("000000000000".$cas);
	else
	if ($cas<10000)
	return("00000000000".$cas);
	
}

function formatacao($quant,$tam,&$conteudo)//formatacao é uma função que coloca espaços em branco
//para formatar o campo para exportação.
{
	if ($quant>$tam){
		for ($j=$tam;$j<$quant;$j++){
			$conteudo[$j]=" ";
		}
		$conteudo=trim($conteudo);
		//$conteudo=trim(str_replace(" ","",$conteudo));
	}
	if ($quant<$tam){
		/*   if (is_string($conteudo)==false){//verifica se o conteúdo é string, caso contrário o converte.
		$conteudo=substr($conteudo);
		} */
		$aux=" ";
		for ($i=1;$i<($tam-$quant);$i++){
			$aux=$aux." ";
		}
		if (is_null($conteudo)==true){
			$conteudo="$aux";
		}
		else
		$conteudo="$conteudo$aux";
		
	}
	
	return($conteudo);
}

function format_ext(&$ide)//format_ext retira o pre-fixo e o sufixo do idexterno
{
	for ($j=0;$j<11;$j++){
		if (($j==0)||($j==1)||($j==9)||($j==10)){
			$ide[$j]=" ";
		}

}
}
?>
