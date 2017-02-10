<?
set_time_limit(0);
ini_set("memory_limit","99M");
ini_set("max_execution_time","99999");

//inclui biblioteca de controles
include("classes/diversos.inc.php");

//testa sessão
/*if (VerSessao()==false){
	header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}*/
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

		$demissaode_ = explode("/",$dataemissaode);
		$demissaoate_ = explode("/",$dataemissaoate);
		
		$demissaode = $demissaode_[2]."-".$demissaode_[1]."-".$demissaode_[0];
		if(!empty($dataemissaoate)){
			$demissaoate = $demissaoate_[2]."-".$demissaoate_[1]."-".$demissaoate_[0];
		}
    
        
        // retirei  or st ='D' para forçar somente os entregues , retornar assim que a DM resolver o problema de conpatibilidade.
         
	    $sql= "SELECT idinterno,dataemissao,numnotafiscal,idexterno,idtipomovimento,idmotivo,st,dataentrega,numconta,
		       quantidadevolumes,pago,dataoperacao,numlotecliente,primeiroenvelope,numerosedex,datareentrega FROM tbentrega 
			   WHERE codcliente=6670 
		       and (st = 'E'  or st = 'S' or st = 'D' or st = 'N') 
			   and dataemissao >= '$demissaode'";
			if(!empty($dataemissaoate)){
				$sql .= "and dataemissao <= '$demissaoate'";
			}
            $sql .= " 
               and primeiroenvelope = ".$setor
               
               ;

               //VERIFICAR O NULL DA DATA DE GERAÇÃO, FOI COLOCADO PARA NAO REGERAR REGISTROS 
                
                                           
               $qry1->executa($sql);
                    
               //echo $sql;
               //DIE;
 	
	$tot_cont = $qry1->nrw;
	if ($tot_cont > 0) {
	
						
			$dt=date("Ymd");
			
			$file = "demillus".$setor.date("YmdH").".REM";
			
			
			
			$fd=fopen("arquivos/".$file, "w");//abre/gera o arquivo
			$cont = 1;
			//Este for (abaixo) configura o arquivo a ser exportado.
			fwrite($fd,"01"."19232334000119".str_pad($qry1->data["primeiroenvelope"],4,'0',STR_PAD_LEFT)."\r\n");
   
                                         
			for ($i=0;$i<$qry1->nrw;$i++){
				$qry1->navega($i);
				
				$qry5->executa("select idexterno,idinterno,dataentrega,idmotivo,st,obsmotivo from tbentrega
				where idinterno=".$qry1->data["idinterno"]);
				
				$dat =  trim(str_replace("-","",$qry1->data["dataemissao"]));
				$datare = trim(str_replace("-","",$qry1->data["datareentrega"]));
                $mot= $qry5->data['obsmotivo'];
				
				$cont =($i+1);
				$cont = ($cont+1);
				if ($qry1->data["st"]== 'D' or $qry1->data["st"]== 'N')
				{
					
					$qry8->executa("select id_motivo_dm from tbmotivo
					where idmotivo=".$qry5->data["idmotivo"]);
					$id_mot = $qry8->data["id_motivo_dm"];
					
                    //echo $sql8;
                    //die;
                    
                    
                    $qry9->executa("select detalhe from tb_detalha_motivo
                    where idinterno=".$qry5->data["idinterno"]);
                    $detalhe = $qry9->data["detalhe"];
                    
                    $mot=$mot.''.$detalhe;
                    
				}
				
				if ($qry1->data["st"]== 'E')
					{
					$TipoBAixa = 'E';
					$mot='';
					}
						
				if ($qry1->data["st"]== 'D')
					$TipoBAixa = 'D';
	
				if ($qry1->data["st"]== 'N')
					$TipoBAixa = 'D';

				if ($qry1->data["st"]== 'R')
					$TipoBAixa = 'S';
					
				if($qry1->data["st"] == 'S')
					$mot=$qry1->data['numerosedex'];
				
				if($qry1->data["st"] == 'S')
					$TipoBAixa = 'S';

				$seq++;
                //Tamanho do registro revendedora
                $ttt = (8-strlen(abs($qry1->data["numconta"])));
                
                //$obb = (49-strlen(trim($mot)));
                $nff = (6-strlen(trim($qry1->data["numnotafiscal"])));
				$mot_pad = str_pad(utf8_decode(trim($mot)),50, " ", STR_PAD_RIGHT);

				//fwrite é uma função do php que cria o arquivo
				fwrite($fd,"02".$TipoBAixa. //"|". //fixo
				str_pad($qry1->data["numnotafiscal"],9, "0", STR_PAD_LEFT).
                $dat.
               // espaco_branco($ttt).
                str_pad(trim($qry1->data["numconta"]),8, "0", STR_PAD_LEFT).
                substr($mot_pad,0,50).
                str_pad($id_mot,2, " ", STR_PAD_LEFT).
                str_pad($datare,8, " ", STR_PAD_RIGHT).
                //$obb.'.'.
				"\r\n");
			
                     
            
            
            
            
            
            
            
				$qry6->executa("UPDATE tbentrega SET datageraarquivo='".date("Y-m-d")."' WHERE idinterno=".$qry1->data["idinterno"]);
				
				
				
			}
		}
		$con->desconecta();
		$tota_01 = ($cont -1);
		$tota_99 = ($cont +1);
	
		fclose($fd);//Fecha o arquivo
		
		//faz o download do arquivo

        
	download_arquivo_texto("$file","arquivos/","");//funcao da diversos para fazer download do arquivo
		
 function casa_dec($cas)//casa_dec é uma função que retorna zeros a esquerda do número
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
