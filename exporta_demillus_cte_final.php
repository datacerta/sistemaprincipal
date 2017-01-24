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
$qry9 = new consulta($con);
$qry_setor = new consulta($con);


$objdata = new consulta($con);


               
   
	
    
         
	    $sql= "SELECT idinterno,dataemissao,numnotafiscal,idexterno,idtipomovimento,idmotivo,st,dataentrega,numconta,valorentrega,
		       quantidadevolumes,dataemissao,numlotecliente,primeiroenvelope,tblista.cte,tarifa FROM tbentrega,tblista 
			   WHERE
			   tbentrega.num_manifesto = tblista.numlista
			   and
			   tbentrega.codcliente=6670 
		       and dataemissao >= '2014-05-01'
               and primeiroenvelope = ".$setor
			   ;
             

			 
               //VERIFICAR O NULL DA DATA DE GERAÇÃO, FOI COLOCADO PARA NAO REGERAR REGISTROS 
                
                                           
               $qry1->executa($sql);
                    
               //echo $sql;
               //dIE;
 	
	$tot_cont = $qry1->nrw;
	if ($tot_cont > 0) {
	
						
			$dt=date("mdY");
			$cnpj  = '04274499000186';
			$ie    = '54422541';
			$serie = 'SE';
			$n_doc = '123456789';
			$cfop  = '5352';
			
			
			
			
			$file = "demillus".$setor.date("YmdH").".REM";
			
	     		
			
			$fd=fopen("arquivos/".$file, "w");//abre/gera o arquivo
			$cont = 1;
			//Este for (abaixo) configura o arquivo a ser exportado.
			
		for ($i=0;$i<$qry1->nrw;$i++){
			$qry1->navega($i);
			fwrite($fd,"01"."|".
			         $cnpj."|".
					 $ie."|".
					 "57"."|".
					 $serie."|".
					 $n_doc."|".
                     $qry1->data["cte"]."|".
					 str_replace("/","",mostra_data_ddmmaa($qry1->data["dataemissao"],4))."|".
					 str_replace("/","",mostra_data_ddmmaa($qry1->data["dataemissao"],4))."|".
					 "0"."|".
					 $qry1->data["valorentrega"]."|".
					 $qry1->data["tarifa"]."|".
					 
					 
					 
					 
					 
						
						"\r\n");
   
                                         

				
				
					
			
				
				$seq++;
                
                
				
				//tipo de registro 2
				//fwrite($fd,"02".$TipoBAixa.espaco_branco(1). //"|". //fixo
				//$qry1->data["numnotafiscal"].
                //$dat.
                //espaco_branco($ttt).
                //abs($qry1->data["numconta"]).
                //trim($mot). 
                //espaco_branco($obb).'.'.
                
				//"\r\n");
			
                     
            
            
            
            
            
            
            
				//$qry6->executa("UPDATE tbentrega SET datageraarquivo='".date("Y-m-d")."' WHERE idinterno=".$qry1->data["idinterno"]);
				
				
				
			}
		}
		$con->desconecta();
		$tota_01 = ($cont -1);
		$tota_99 = ($cont +1);
	
		fclose($fd);//Fecha o arquivo
		
		//faz o download do arquivo

        
	download_arquivo_texto("$file","arquivos/","");//funcao da diversos para fazer download do arquivo
		
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


        
        
        
function espaco_branco($tam)//sem_traço é uma função que retira os traços da data.
{
    for($i=0;$i<$tam;$i++){
        $esp=$esp." ";
    }
    return($esp);
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



?>
