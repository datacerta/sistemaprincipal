<?
set_time_limit(0);
ini_set("memory_limit","99M");
ini_set("max_execution_time","99999");

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
$qryTentativa = new consulta($con);


$objdata = new consulta($con);


	
	

	$qry1->executa("SELECT idinterno,dataemissao,numnotafiscal,idexterno,idtipomovimento,idmotivo,st,dataentrega,datacoletado,
					quantidadevolumes,pago,dataoperacao,numlotecliente FROM tbentrega 
					WHERE codcliente=7  
                 	and st in('E','D')
					Limit 100
			        ");
	
	
	
	
				$file = "teste.xml";//Armazena o nome do arquivo
				
				
				$manipulador_arq=fopen("arquivos/".$file, "w+");//abre/gera o arquivo

	
	$tot_cont = $qry1->nrw;
	if ($tot_cont > 0) {
	
				echo "teste";
				echo "<br>";		
			$id = $qry1->data["idexterno"];
				
				$xml = "\n\n<contato>\n";
				$xml .= "<nome>$id</idinterno>\n";
				
				$xml .= "\n</contato>";

                fwrite($manipulador_arq,$xml); 

					
				}
				
			
			
				//$qry6->executa("UPDATE tbentrega SET pago = 33, 
				//datageraarquivo='".date("Y-m-d")."' WHERE idinterno=".$qry1->data["idinterno"]);
						

	
		
		
		
		//fclose($fd);//Fecha o arquivo
		
		//faz o download do arquivo
		download_arquivo_texto("$file","arquivos/","");//funcao da diversos para fazer download do arquivo
		
	
	


function zeros(&$cas)//zeros é uma função que retorna zeros a esquerda do número
{
	
	if ($cas<10)
	return("0".$cas);
	else
	return($cas);
}


function movimento(&$mov,&$mot,$retornar="")//Faz a conversão do código de entrega (da fast com o do banco).
{
	if ($mov==105||$mov==106){
		$mot="10";
		$mov="00";
	}
	if ($mov==132||$mov==133){
		$mot="11";
		$mov="00";
	}
	//122
	if ($mov==150){
		$mov="12";
		
		$qryfunc  = new consulta($con);
		$qryfunc->executa("SELECT idmotivoabn FROM tbmotivo WHERE idmotivo=".intval($mot));
		$mot = $qryfunc->data["idmotivoabn"];
		/*
		switch($mot){
		case 14:
		$mot=01;
		break;
		case 17:
		$mot=02;
		break;
		case 5:
		$mot=02;
		break;
		case 11:
		$mot=02;
		break;
		case 7:
		$mot=02;
		break;
		case 4:
		$mot=02;
		break;
		case 3:
		$mot=02;
		break;
		case 18:
		$mot=03;
		break;
		case 8:
		$mot=04;
		break;
		case 19:
		$mot=05;
		break;
		case 16:
		$mot=06;
		break;
		case 1:
		$mot=07;
		break;
		case 12:
		$mot=07;
		break;
		case 13:
		$mot=07;
		break;
		case 22:
		$mot=07;
		break;
		case 20:
		$mot=07;
		break;
		case 15:
		$mot=08;
		break;
		case 10:
		$mot=9;
		break;
		}*/
	}
	
	if($retornar=="mot")
	return $mot;
	elseif($retornar=="mov")
	return $mov;
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
	
	if ($cas<10)
	return("000000".$cas);
	else
	if ($cas<100)
	return("00000".$cas);
	else
	if ($cas<1000)
	return("0000".$cas);
	else
	if ($cas<10000)
	return("000".$cas);
	
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

function casa_dec_5(&$cas)//casa_dec é uma função que retorna zeros a esquerda do número
//auto-sequencial (formatação exigida pelo arquivo de exportação).
{
	
	if ($cas<10)
	return("0000".$cas);
	else
	if ($cas<100)
	return("000".$cas);
	else
	if ($cas<3)
	return("00".$cas);
	else
	if ($cas<10000)
	return("0".$cas);
	
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
