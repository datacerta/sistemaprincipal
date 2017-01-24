<?
set_time_limit(0);
ini_set("memory_limit","99M");
ini_set("max_execution_time","99999");

require_once("inc/config.inc");



$qry = new consulta($con);
$qry1 = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qryexp = new consulta($con);
$qryrec = new consulta($con);

$dataemissao_barra = explode("/", $_POST['dataemi']);
$codcliente = $_POST['cliente'];
$qry3->executa("SELECT * FROM tbcliente WHERE codcliente = '$codcliente'");

$dataemissao_q = $dataemissao_barra[2]."-".$dataemissao_barra[1]."-".$dataemissao_barra[0];
if(!empty($dataemissao_q)){
	$data_traco = explode('-',$dataemissao_q);
	$dataemissao_format = $data_traco[2].$data_traco[1].$data_traco[0];
}else{
	$dataemissao_format = '';
}

$l_ = "RET_400_".date("Ymd")."_.TXT";
$l_000_remetente = $qry3->data['razaosocial'];
$l_000_destinatario = 'DATA CERTA TRANSPORTE E LOGISTICA LTDA - EPP';
$l_000_data = date("dmyy");	
$l_000_hora = date("hi");
$l_000_id_intercambio = "NOT".date("dmhi0");
$l_000_filler = espaco_branco(145);	
	    
$qry->executa(
	"SELECT e.idexterno,e.idinterno,e.cpf, e.numnotafiscal, e.dataemissao, e.datapromessa, e.valorentrega, e.pesoentrega, e.chave_nfe, e.primeiroenvelope,
		ee.nomeentrega, ee.enderecoentrega, ee.bairroentrega, ee.cidadeentrega, ee.cepentrega, ee.estadoentrega, e.quantidadevolumes,e.codigoproduto
	   FROM tbentrega e
	   JOIN tbenderecoentrega ee ON e.idinterno = ee.idinterno
	   AND codcliente=$codcliente
	   and dataemissao = '$dataemissao_q'");


if ($qry->nrw > 0) {	
				
	$dt=date("Ymd");	
	$file = "cte".date("dm").".txt";		
	$fd=fopen("arquivos/".$file, "w");
	$cont = 1;
	$seq++;
				
	fwrite($fd,"000".
	formatacao(strlen($l_000_remetente),35,$l_000_remetente).
	formatacao(strlen($l_000_destinatario),35,$l_000_destinatario).
	formatacao(strlen($l_000_data),6,$l_000_data).
	formatacao(strlen($l_000_data),4,$l_000_hora).
	formatacao(strlen($l_000_id_intercambio),12,$l_000_id_intercambio).
	$l_000_filler.
	"\r\n");
	
	$l_310_id_intercambio = "NOTFI".date("dmhi0");
	$l_310_filler = espaco_branco(223);	
	fwrite($fd,"310".
	formatacao(strlen($l_310_id_intercambio),14,$l_310_id_intercambio).
	$l_310_filler.
	"\r\n");

	
	$l_311_cnpj = $qry3->data['cnpj'];
	$l_311_ie = $qry3->data['ie'];
	$l_311_endereco = $qry3->data['endereco'];
	$l_311_cidade = $qry3->data['cidade'];
	$l_311_cep = $qry3->data['cep'];
	$l_311_uf = $qry3->data['uf'];
	$l_311_datadeembarque = $dataemissao_format;
	$l_311_razaosocial = $qry3->data['razaosocial'];	
	$l_311_filer = espaco_branco(67);

	fwrite($fd,"311".
	formatacao(strlen($l_311_cnpj),14,$l_311_cnpj).
	formatacao(strlen($l_311_ie),15,$l_311_ie).
	formatacao(strlen($l_311_endereco),40,$l_311_endereco).
	formatacao(strlen($l_311_cidade),35,$l_311_cidade).
	formatacao(strlen($l_311_cep),9,$l_311_cep).
	formatacao(strlen($l_311_uf),9,$l_311_uf).
	formatacao(strlen($l_311_datadeembarque),8,$l_311_datadeembarque).
	formatacao(strlen($l_311_razaosocial),40,$l_311_razaosocial).
	$l_311_filer.
	"\r\n");		
	for ($i=0;$i<$qry->nrw;$i++){
		$qry->navega($i);

		$l_312_razaosocial = $qry->data['nomeentrega'];
		$l_312_cnpjcpf = str_pad($qry->data['cpf'],14,"0",STR_PAD_LEFT);
		$l_312_ie = 'ISENTO';
		$l_312_endereco = $qry->data['enderecoentrega'];
		$l_312_bairro = $qry->data['bairroentrega'];
		$l_312_cidade = $qry->data['cidadeentrega'];
		$l_312_cep = $qry->data['cepentrega'];
		$l_312_codmun = '999';
		$l_312_uf = $qry->data['estadoentrega'];
		$l_312_areafrete = '000';
		$l_312_numcom = '9999999999';
		$l_312_id = '2';
		$l_312_filer = espaco_branco(6);

		fwrite($fd,"312".
		formatacao(strlen($l_312_razaosocial),40,$l_312_razaosocial).
		formatacao(strlen($l_312_cnpjcpf),14,$l_312_cnpjcpf).
		formatacao(strlen($l_312_ie),15,$l_312_ie).
		formatacao(strlen($l_312_endereco),40,$l_312_endereco).
		formatacao(strlen($l_312_bairro),20,$l_312_bairro).
		formatacao(strlen($l_312_cidade),35,$l_312_cidade).
		formatacao(strlen($l_312_cep),9,$l_312_cep).
		formatacao(strlen($l_312_codmun),9,$l_312_codmun).
		formatacao(strlen($l_312_uf),9,$l_312_uf).
		formatacao(strlen($l_312_areafrete),4,$l_312_areafrete).
		formatacao(strlen($l_312_numcom),35,$l_312_numcom).
		formatacao(strlen($l_312_id),1,$l_312_id).
		$l_312_filer.
		"\r\n");

		if(!empty($qry->data['datapromessa'])){
			$data_traco = explode('-',$qry->data['datapromessa']);
			$datapromessa = $data_traco[2].$data_traco[1].$data_traco[0];
		}else{
			$datapromessa = '';
		}

		$qry4->executa("SELECT * FROM tbproduto WHERE codigoproduto = '".$qry->data['codigoproduto']."'");
		if($codcliente == '6670' OR $codcliente == '6671'){
			$qry1->executa(
			    "SELECT COUNT(idinterno) as totalcaixas
			    FROM tb_demillus_volumes dv
			    WHERE idinterno=".$qry->data['idinterno'].
			    " GROUP BY idinterno");
			$qry2->executa(
			    "SELECT preco, cte_remetente, cte_destinatario, cte_expedidor, cte_recebedor,cfop, aliquota_icms
			    FROM tb_preco_demillus 
			    WHERE setor=".$qry->data['primeiroenvelope']);

			$cfop = $qry2->data['cfop'];
			$cte_expedidor = $qry2->data['cte_expedidor'];
			$cte_recebedor = $qry2->data['cte_recebedor'];

			$qryexp->executa("SELECT * FROM tb_cte_politica WHERE codcliente = '$codcliente' AND tipo = '$cte_expedidor'");
			$qryrec->executa("SELECT * FROM tb_cte_politica WHERE codcliente = '$codcliente' AND tipo = '$cte_recebedor'");

			$aliquota_base_icms = 0.88;
			$aliquota_icms      = $qry2->data['aliquota_icms'];
			$icms_calc = round_half_down((($qry2->data['preco'] / $aliquota_base_icms) * $aliquota_icms) / 100, 2);
	        $icms_calc = number_format($icms_calc,2,".",""); 

			$l_313_numromaneio = $qry->data['primeiroenvelope'];
			$l_313_codrota = $qry->data['primeiroenvelope'];
			$totalcaixas = $qry1->data['totalcaixas'];
			$l_313_valfretepv = number_format(($qry2->data['preco']+$icms_calc),2,".","");
			$l_313_valtotfrete = number_format(($qry2->data['preco']+$icms_calc),2,".","");
			$l_313_valicms = $icms_calc;

		}else{
			$cfop = $qry4->data['cfop'];
			$l_313_numromaneio = '';
			$l_313_codrota = '';
			$totalcaixas = $qry->data['quantidadevolumes'];
			$l_313_valfretepv = $qry4->data['valfretepv'];
			$l_313_valtotfrete = $qry4->data['valtotfrete'];
			$l_313_valicms = $qry4->data['valicms'];

			$qryexp->executa("SELECT * FROM tb_cte_politica WHERE codcliente = '$codcliente' AND tipo = '3'");
			$qryrec->executa("SELECT * FROM tb_cte_politica WHERE codcliente = '$codcliente' AND tipo = '4'");
		}

		$l_313_meiotransporte = $qry4->data['meiotransporte'];
		$l_313_tipotransporte = $qry4->data['tipotransporte'];
		$l_313_tipocarga = $qry4->data['tipocarga'];
		$l_313_condfrete = $qry4->data['condfrete'];
		$l_313_serie = $qry4->data['serie'];
		$l_313_num = $qry->data['numnotafiscal'];
		$l_313_datadeemissao = $dataemissao_format;
		$l_313_natureza = $qry4->data['natureza'];
		$l_313_especieacond = $qry4->data['especieacond'];
		$l_313_volume = $totalcaixas;
		$l_313_valmercadoria = $qry->data['valorentrega'];
		$l_313_pesototal = $qry->data['pesoentrega'];
		$l_313_pesodens = $qry->data['pesoentrega'];
		$l_313_tipoicms = $qry4->data['incidicms'];
		$l_313_seguroefetuado = $qry4->data['seguroefetuado'];
		$l_313_valseguro = $qry4->data['valseguro'];
		$l_313_valcobrado = $qry4->data['valcobrado'];
		$l_313_placacaminhao = $qry4->data['nplaca'];
		$l_313_plancarga = $qry4->data['planocarga'];
		$l_313_valadvalorem = $qry4->data['valadvalorem'];
		$l_313_valtottax = $qry4->data['valtottax'];
		$l_313_acaododoc = $qry4->data['acaodoc'];
		$l_313_valicmsret = $qry4->data['valicmsret'];
		$l_313_indicacaobon = $qry4->data['indbon'];
		$l_313_dschave = $qry->data['chave_nfe'];
		$l_313_filer = espaco_branco(2);

		fwrite($fd,"313".
		formatacao(strlen($l_313_numromaneio),15,$l_313_numromaneio).
		formatacao(strlen($l_313_codrota),7,$l_313_codrota).
		formatacao(strlen($l_313_meiotransporte),1,$l_313_meiotransporte).
		formatacao(strlen($l_313_tipotransporte),1,$l_313_tipotransporte).
		formatacao(strlen($l_313_tipocarga),1,$l_313_tipocarga).
		formatacao(strlen($l_313_condfrete),1,$l_313_condfrete).
		formatacao(strlen($l_313_serie),3,$l_313_serie).
		formatacao(strlen($l_313_num),8,$l_313_num).
		formatacao(strlen($l_313_datadeemissao),8,$l_313_datadeemissao).
		formatacao(strlen($l_313_natureza),15,$l_313_natureza).
		formatacao(strlen($l_313_especieacond ),15,$l_313_especieacond).
		formatacao(strlen($l_313_volume),7,$l_313_volume).
		formatacao(strlen($l_313_valmercadoria),15,$l_313_valmercadoria).
		formatacao(strlen($l_313_pesototal),7,$l_313_pesototal).
		formatacao(strlen($l_313_pesodens ),5,$l_313_pesodens).
		formatacao(strlen($l_313_tipoicms),1,$l_313_tipoicms).
		formatacao(strlen($l_313_seguroefetuado),1,$l_313_seguroefetuado).
		formatacao(strlen($l_313_valseguro),15,$l_313_valseguro).
		formatacao(strlen($l_313_valcobrado),15,$l_313_valcobrado).
		formatacao(strlen($l_313_placacaminhao),7,$l_313_placacaminhao).
		formatacao(strlen($l_313_plancarga),1,$l_313_plancarga).
		formatacao(strlen($l_313_valfretepv),15,$l_313_valfretepv).
		formatacao(strlen($l_313_valadvalorem),15,$l_313_valadvalorem).
		formatacao(strlen($l_313_valtottax),15,$l_313_valtottax).
		formatacao(strlen($l_313_valtotfrete),15,$l_313_valtotfrete).
		formatacao(strlen($l_313_acaododoc),1,$l_313_acaododoc).
		formatacao(strlen($l_313_valicms),12,$l_313_valicms).
		formatacao(strlen($l_313_valicmsret),12,$l_313_valicmsret).
		formatacao(strlen($l_313_indicacaobon),1,$l_313_indicacaobon).
		formatacao(strlen(trim($l_313_dschave)),44,trim($l_313_dschave)).
		$l_313_filer.
		"\r\n");

		$l_333_codoperacao = $cfop;
		$l_333_tipoperiodo = '1';
		$l_333_dataini = $dataemissao_format;	
		$l_333_horaini = '1200';
		$l_333_datafin = $datapromessa;
		$l_333_horafin = '1200';
		$l_333_iddesembarque = 'RESIDENCIA';
		$l_333_calcfrete = 'N';
		$l_333_idtabelapreco = '';
		$l_333_filera = espaco_branco(155);
		$l_333_tipoveic = 'BR01';
		$l_333_filerb = espaco_branco(32);

		fwrite($fd,"333".
		formatacao(strlen($l_333_codoperacao),4,$l_333_codoperacao).
		formatacao(strlen($l_333_tipoperiodo),1,$l_333_tipoperiodo).
		formatacao(strlen($l_333_dataini),8,$l_333_dataini).
		formatacao(strlen($l_333_horaini),4,$l_333_horaini).
		formatacao(strlen($l_333_datafin),8,$l_333_datafin).
		formatacao(strlen($l_333_horafin),4,$l_333_horafin).				
		formatacao(strlen($l_333_iddesembarque),15,$l_333_iddesembarque).
		formatacao(strlen($l_333_calcfrete),1,$l_333_calcfrete).
		$l_333_filera.
		formatacao(strlen($l_333_tipoveic),5,$l_333_tipoveic).
		$l_333_filerb.
		"\r\n");	

		$l_314_qtdvola = $totalcaixas;
		$l_314_espaconda = $qry4->data['especieacond'];
		$l_314_mercnotaa = '0000000000';
		$l_314_qtdvolb = $totalcaixas;
		$l_314_espacondb = $qry4->data['especieacond'];
		$l_314_mercnotab = '0000000000';
		$l_314_qtdvolc = $totalcaixas;
		$l_314_espacondc = $qry4->data['especieacond'];
		$l_314_mercnotac = '0000000000';
		$l_314_qtdvold = $totalcaixas;
		$l_314_espacondd = $qry4->data['especieacond'];
		$l_314_mercnotad = '0000000000';
		$l_314_filer = espaco_branco(29);

		fwrite($fd,"314".
		formatacao(strlen($l_314_qtdvola),7,$l_314_qtdvola).
		formatacao(strlen($l_314_espaconda),15,$l_314_espaconda).
		formatacao(strlen($l_314_mercnotaa),30,$l_314_mercnotaa).
		formatacao(strlen($l_314_qtdvolb),7,$l_314_qtdvolb).
		formatacao(strlen($l_314_espacondb),15,$l_314_espacondb).
		formatacao(strlen($l_314_mercnotab),30,$l_314_mercnotab).
		formatacao(strlen($l_314_qtdvolc),7,$l_314_qtdvolc).
		formatacao(strlen($l_314_espacondc),15,$l_314_espacondc).
		formatacao(strlen($l_314_mercnotac),30,$l_314_mercnotac).
		formatacao(strlen($l_314_qtdvold),7,$l_314_qtdvold).
		formatacao(strlen($l_314_espacondd),15,$l_314_espacondd).
		formatacao(strlen($l_314_mercnotad),30,$l_314_mercnotad).
		$l_314_filer.
		"\r\n");	

		$l_315_razaosocial = $qryrec->data['nomecliente'];
		$l_315_cnpj = $qryrec->data['cnpj'];
		$l_315_ie = $qryrec->data['ie'];
		$l_315_endereco = $qryrec->data['endereco'];
		$l_315_bairro = $qryrec->data['bairro'];
		$l_315_cidade = $qryrec->data['cidade'];
		$l_315_cep = $qryrec->data['cep'];
		$l_315_codmun = '999';
		$l_315_uf = $qryrec->data['uf'];
		$l_315_tel = $qryrec->data['fonecontato'];
		$l_315_filer = espaco_branco(11);

		fwrite($fd,"315".
		formatacao(strlen($l_315_razaosocial),40,$l_315_razaosocial).
		formatacao(strlen($l_315_cnpj),14,$l_315_cnpj).
		formatacao(strlen($l_315_ie),15,$l_315_ie).
		formatacao(strlen($l_315_endereco),40,$l_315_endereco).
		formatacao(strlen($l_315_bairro),20,$l_315_bairro).
		formatacao(strlen($l_315_cidade),35,$l_315_cidade).
		formatacao(strlen($l_315_cep),9,$l_315_cep).
		formatacao(strlen($l_315_codmun),9,$l_315_codmun).
		formatacao(strlen($l_315_uf),9,$l_315_uf).
		formatacao(strlen($l_315_tel),35,$l_315_tel).
		$l_315_filer.
		"\r\n");

		$l_316_razaosocial = $qryexp->data['nomecliente'];
		$l_316_cnpj = $qryexp->data['cnpj'];
		$l_316_ie = $qryexp->data['ie'];
		$l_316_endereco = $qryexp->data['endereco'];
		$l_316_bairro = $qryexp->data['bairro'];
		$l_316_cidade = $qryexp->data['cidade'];
		$l_316_cep = $qryexp->data['cep'];
		$l_316_codmun = '999';
		$l_316_uf = $qryexp->data['uf'];
		$l_316_areafrete = '0000';
		$l_316_tel = $qryexp->data['fonecontato'];
		$l_316_filer = espaco_branco(7);

		fwrite($fd,"316".
		formatacao(strlen($l_316_razaosocial),40,$l_316_razaosocial).
		formatacao(strlen($l_316_cnpj),14,$l_316_cnpj).
		formatacao(strlen($l_316_ie),15,$l_316_ie).
		formatacao(strlen($l_316_endereco),40,$l_316_endereco).
		formatacao(strlen($l_316_bairro),20,$l_316_bairro).
		formatacao(strlen($l_316_cidade),35,$l_316_cidade).
		formatacao(strlen($l_316_cep),9,$l_316_cep).
		formatacao(strlen($l_316_codmun),9,$l_316_codmun).
		formatacao(strlen($l_316_uf),9,$l_316_uf).
		formatacao(strlen($l_316_areafrete),4,$l_316_areafrete).
		formatacao(strlen($l_316_tel),35,$l_316_tel).
		$l_316_filer.
		"\r\n");	

		$l_317_razaosocial = trim($qry3->data['razaosocial']);
		$l_317_cnpj = $qry3->data['cnpj'];
		$l_317_ie = $qry3->data['ie'];
		$l_317_endereco = $qry3->data['endereco'];
		$l_317_bairro = $qry3->data['bairro'];
		$l_317_cidade = $qry3->data['cidade'];
		$l_317_cep = $qry3->data['cep'];
		$l_317_codmun = $qry3->data['codmun'];
		$l_317_subpais = $qry3->data['uf'];
		$l_317_numcom = $qry3->data['fonecontato1'];
		$l_317_filer = espaco_branco(11);
	
		

		fwrite($fd,"317".
		formatacao(strlen($l_317_razaosocial),40,$l_317_razaosocial).
		formatacao(strlen($l_317_cnpj),14,$l_317_cnpj).
		formatacao(strlen($l_317_ie),15,$l_317_ie).
		formatacao(strlen($l_317_endereco),40,$l_317_endereco).
		formatacao(strlen($l_317_bairro),20,$l_317_bairro).
		formatacao(strlen($l_317_cidade),35,$l_317_cidade).
		formatacao(strlen($l_317_cep),9,$l_317_cep).
		formatacao(strlen($l_317_codmun),9,$l_317_codmun).
		formatacao(strlen($l_317_subpais),9,$l_317_subpais).
		formatacao(strlen($l_317_numcom),35,$l_317_numcom).
		$l_317_filer.
		"\r\n");	
		
		$l_318_totalnotas += $qry->data['valorentrega'];
		$l_318_totalpeso += $qry->data['pesoentrega'];
		$l_318_totalden += $qry->data['pesoentrega'];
		$l_318_qtdvolumes += $totalcaixas;
		$l_318_valcobrado += ($qry->data['valorentrega']+$icms_calc);
		$l_318_valseguro = '';
	}
	
	$l_318_filer = espaco_branco(147);

	fwrite($fd,"318".
	formatacao(strlen($l_318_totalnotas),15,$l_318_totalnotas).
	formatacao(strlen($l_318_totalpeso),15,$l_318_totalpeso).
	formatacao(strlen($l_318_totalden),15,$l_318_totalden).
	formatacao(strlen($l_318_qtdvolumes),15,$l_318_qtdvolumes).
	formatacao(strlen($l_318_valcobrado),15,$l_318_valcobrado).
	formatacao(strlen($l_318_valseguro),15,$l_318_valseguro).
	$l_318_filer.
	"\r\n");	

}

$con->desconecta();	
fclose($fd);//Fecha o arquivo

//faz o download do arquivo
download_arquivo_texto("$file","arquivos/","");//funcao da diversos para fazer download do arquivo

		
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






function formatacao($quant,$tam,$conteudo)//formatacao é uma função que coloca espaços em branco
//para formatar o campo para exportação.
{
	$conteudo = utf8_decode($conteudo);
	if ($quant>$tam){
		$valor = substr($conteudo,0,$tam);
	}else{
		$valor = str_pad($conteudo, $tam, " ", STR_PAD_RIGHT);
	}
	return $valor;
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
