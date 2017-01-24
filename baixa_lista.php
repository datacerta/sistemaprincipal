<?php
/**
 * Baixa de Entregas
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel
$prgTit = "Baixa de Entregas";

// pega a configuracao
require_once("inc/config.inc");

// variavel
$questiona_promessa = 0;

//definição de objetos
$qry   = new consulta($con);
$qry2  = new consulta($con);
$qry22 = new consulta($con);

 if ($_SESSION['IDBASE']!=1 or $_SESSION['CODLOGIN'] ==736  or $_SESSION['CODLOGIN'] == 1881 or $_SESSION['CODLOGIN'] == 1892  or $_SESSION['CODLOGIN'] == 1900)
{
//Echo "UTILIZAR SISTEMA NOVO NA WEB";
//DIE;
}

//Echo "UTILIZAR SISTEMA NOVO NA WEB , www.fastcourier.com.br";
// DIE;


 $codbars = trim($codbars);
 $codbars = str_replace("%O", "_",$codbars);
 $codbars_ = $codbars;


if (strpos($cbmotivo,"|")>0){
   	$cbmotivo2 =  $cbmotivo ;
	  $cbmotivo_bruno = explode("|",$cbmotivo);
	  $cbmotivo = $cbmotivo_bruno[1];
    }

if($_POST['opt']=="PE"){
//pesquisar ENCOMENDA
        $qry->nrw = 0;
        $sql = "SELECT idtransportadora,dataemissao,codcliente,datavencimento,codcliente,codigoproduto FROM tbentrega WHERE idexterno='$codbars'";
        $qry->executa($sql);
      	if(!$qry->nrw){
            $msg = "<font color='#990000'>Encomenda $codbars não localizada.</font>";
			$codbars='';
            $erro = 1;
          }
	else{
	  	  $sql = "SELECT baixafinal 
		 	  FROM tbconfigproduto 
			  WHERE 
		    idtransportadora='".$qry->data["idtransportadora"]."' AND 
			  codcliente='".$qry->data["codcliente"]."' AND codigoproduto='".$qry->data["codigoproduto"]."'";
        $qry->executa($sql);
        $baixafinal = $qry->data["baixafinal"];
               $codbars = str_replace("%O", "_",$codbars);
        $sql = "select * from tbentrega where idexterno ='".$codbars."'";
        $qry->executa($sql);
        $cliente = $qry->data["codcliente"];
        
     if($baixafinal!=1) $_POST['opt'] = "BE"; //jah baixa direto
          }
   }



switch ($_POST['opt']){


case "PL":
        //pesquisar lista
        $baixafinal = 0;
        $sql = "SELECT count(idinterno) as qtd_montados FROM tbentrega WHERE numlista='$num_lista'";
        $qry->executa($sql);
        $qtd_montados = $qry->data["qtd_montados"];
        if(!$qry->data["qtd_montados"]){
                $msg = "<font color='#990000'>Lista não localizada ou não expedida</font>";
                $erro = 1;
        }

        if(!$msg){
                $sql = "SELECT count(idinterno) as qtd_baixados FROM tbentrega WHERE idtipomovimento<>104 AND numlista='$num_lista'";
                $qry->executa($sql);
                $qtd_baixados = $qry->data["qtd_baixados"];

                $qtd_saldo = $qtd_montados - $qtd_baixados;
        }
break;

case "BE":
//baixar encomenda

        if(($radio==150 || $radio==1) && $cbmotivo<=0){
                $msg = "<font color='#990000'>ATENÇÃO! Informe o motivo para este procediemnto.</font>";
        }else{
                //INSERTS E UPDATES
                //definição de objetos
                $qry1  = new consulta($con);
                $qry2  = new consulta($con);
                $qry3  = new consulta($con);
                $qry4  = new consulta($con);
                $qry5  = new consulta($con);
                $qry6  = new consulta($con);
                $qry20 = new consulta($con);
			        	$qry21 = new consulta($con);
                $qryBT = new consulta($con);
                $qryU = new consulta($con);
                $qryC = new consulta($con);
			        	$qry_ocorre = new consulta($con);
			        	

				

                //PROTEÇÃO --> Somente a Base q detem o Doc Físico pode LANÇAR A BAIXA
                $codbars = trim($codbars);
                $codbars = str_replace("%O", "_",$codbars);
				$dt_baixa = substr($databaixa,6,4)."-".substr($databaixa,3,2)."-".substr($databaixa,0,2);

                $qry4->executa("select * from tbentrega where idexterno ='".$codbars."'");
                if(!$qry4->nrw)
                {
				$msg = "<font size = 10 color='#990000'>$codbars não localizada...</font>";
				$codbars='';	
				} elseif(strtotime($dt_baixa) < strtotime($qry4->data["datacoletado"])) {
					$msg = "<font size ='8' color='#990000'>Data não pode ser MENOR COLETA.</font>";
					$codbars='';
					$erro = 1;
                }else{

                        $id = $qry4->data["idinterno"];
                        $movimentoatual = $qry4->data["idtipomovimento"];
                        $transp = $qry4->data["idtransportadora"];
                        $numlista_tbentrega = $qry4->data["numlista"];
                        $cliente=$qry4->data["codcliente"];

                        if($controla_lista=='t' and $num_lista!=$numlista_tbentrega){
                                $msg = "<font color='#990000'>Esta encomenda ($codbars lista $numlista_tbentrega ) 
								                não pertence a essa lista ($num_lista).</font>";
                                $erro = 1;
                        }else{

                                //dados para mensuração
                                $transportadora=$qry4->data["idtransportadora"];
                                $cliente=$qry4->data["codcliente"];
                                $produto=$qry4->data["codigoproduto"];
                                $lote   =$qry4->data["numlotecliente"];
                                $base   =$_SESSION['IDBASE'];



                                if($base == $_SESSION['IDBASE']){

                                   $baixafinal = 0; //inicializando o tipo de baixa
                                   switch($radio){
                                   //TELEMARKETING
                                   case 1:
                                       $sql = "SELECT nomeentrega,enderecoentrega, idinterno
								               FROM tbenderecoentrega
               	                               WHERE 
                                               idinterno = '$id'  ";
                 $qry20->executa($sql);
                 $endereco  = $qry20->data["enderecoentrega"];
                 $nome  = $qry20->data["nomeentrega"];
           
							  
                 
                 $erro2=0;
                 if ($qry20->nrw){
                     $sql= "SELECT * FROM tbmotivo WHERE idmotivo = '$cbmotivo' and verificahistorico = 't'";
                     $qry20->executa($sql);
                    //echo "motivo:".$sql;
                 if ($qry20->nrw){
                    $sql = "select st as status ,tbentrega.dataentrega, ";
                    $sql = $sql ." tbentrega.idinterno, tbentrega.idexterno ";
                    $sql = $sql. " from tbentrega,tbenderecoentrega "; 
                    $sql = $sql ." where"; 
                    $sql = $sql ." tbentrega.idinterno = tbenderecoentrega.idinterno";
                    $sql = $sql ." and ";
                    $sql = $sql ." tbentrega.st='E'";
                    $sql = $sql ." and";
                    $sql = $sql ." tbenderecoentrega.nomeentrega = '$nome'";
                    $sql = $sql ." and ";
                    $sql = $sql ." tbenderecoentrega.enderecoentrega = '$endereco'";
                    $sql = $sql ." limit 3 ";
                    //echo $sql;
                    $qry20->executa($sql);
                    $erro2=0;
                    if ($qry20->nrw){
                        $msg="<font color='#FF0000'>Esta encomenda já foi
						  	   entregue no mesmo endereço em outra data</font>";
                         $erro = 1;
                         $erro2=1;
                  }
                     }    
                     }                               
                    
                    if($erro2==0)
                    {
                    //identifica o produto
                    $qry2->executa("select codigoproduto,codcliente,
						            idtransportadora,idinterno from tbentrega where idinterno=$id and 
						            idtransportadora=".$_SESSION['IDTRANSP']);
                                    //identifica a qtd de tentativas nas configuarções do produto
                                    $qry3->executa("select qttentativa from tbconfigproduto where
						            idtransportadora=".$_SESSION['IDTRANSP']." 
						            and codigoproduto=".$qry2->data['codigoproduto']." and
						            codcliente=".$qry2->data['codcliente']);
                                                        switch($qry3->data["qttentativa"]){
                                                                case 1://1 tentativa deve ocorrer
                                                                $qry1->executa("select idtipomovimento, 
																               idinterno from tbmovimento where idinterno=$id and idtipomovimento=135");
                                                                if($qry1->nrw>0){//há movimento de 1 tentativa - DEVOLVE
                                                                $tipomovimento=150;
                                                                $msg="Encomenda devolvida por motivo de estouro das tentativas.";
                                                                }else{//NÃO houve tentativa - TELEMARKETING
                                                                $tipomovimento=135;
                                                                $msg="Encomenda em Telemarketing para 1.ª Tentativa.";
                                                                
                                                                }
                                                                break;
								
                                                                case 2://2 tentativas devem ocorrer
                                                                $qry1->executa("select idtipomovimento, 
																                idinterno from tbmovimento where idinterno=$id and idtipomovimento=135");
                                                                if($qry1->nrw>0){//há movimento de 1 tentativa - procure a segunda
                                                                $qry1->executa("select idtipomovimento, idinterno 
																                from tbmovimento where idinterno=$id and idtipomovimento=136");
                                                                if($qry1->nrw>0){//há movimento de 2 tentativa - DEVOLVA
                                                                $tipomovimento=150;
                                                                $msg="Encomenda devolvida por motivo de estouro das tentativas.";
                                                                }else{//NÃO houve 2ª tentativa - TELEMARKETING
                                                                $tipomovimento=136;
                                                                $msg="Encomenda em Telemarketing para 2.ª Tentativa.";
                                                                }
                                                                }else{//NÃO houve 1ª tentativa - TELEMARKETING
                                                                $tipomovimento=135;
                                                                $msg="Encomenda em Telemarketing para 1.ª Tentativa.";
                                                                }
                                                                break;
								
                                                                case 3://3 tentativas devem ocorrer
									
                                                               	$qry1->executa("select idtipomovimento, idinterno
												                                from tbmovimento where idinterno=$id and idtipomovimento=135");
                                                               	if($qry1->nrw>0){//há movimento de 1 tentativa - procure a segunda
                                                                		$qry1->executa("select idtipomovimento, idinterno 
													                                    from tbmovimento where idinterno=$id and idtipomovimento=136");
		                                                        if($qry1->nrw>0){//há movimento de 2 tentativa - DEVOLVA
                                                                			$qry1->executa("select idtipomovimento, idinterno 
														                                    from tbmovimento where idinterno=$id and idtipomovimento=137");
                                                             	if($qry1->nrw>0){//há movimento de 3 tentativa - DEVOLVA
                                                               				$tipomovimento=150;
                                                               				$msg="Encomenda devolvida por motivo de estouro das tentativas.";
                                                                			}
											                    else{//NÃO houve 3ª tentativa
                                                                				$tipomovimento=137;
                                                                				$msg="Encomenda em Telemarketing para 3.ª Tentativa.";
                                                                			}
                                                                		}
										                        else{//NÃO houve 2ª tentativa
                                                                			$tipomovimento=136;
                                                                			$msg="Encomenda em Telemarketing para 2.ª Tentativa.";
                                                                		}
                                                                	}
									                            else{//NÃO houve 1ª tentativa
                                                                		$tipomovimento=135;
                                                                		$msg="Encomenda em Telemarketing para 1.ª Tentativa.";
                                                                	}
                                                                break;
									
                                                                default: //não há tentativas - DEVOLVE
                                                                $tipomovimento=137;
                                                                //$msg="Encomenda devolvida por motivo de estouro das tentativas.";
                                                                break;
                                                        }

                                                        $motivo = $cbmotivo;
                                                       }
                                                        break;

                                                        //AUSENTE
                                                        case 5:
                                                        //identifica o produto
														$qry2->executa("select codigoproduto,codcliente,idtransportadora, idinterno 
														from tbentrega 
														where idinterno=$id");
														
                                                        //identifica a qtd de tentativas nas configuarções do produto
                                                        $qry3->executa("select qttentativa 
														from tbconfigproduto 
														where idtransportadora=".$_SESSION['IDTRANSP']." and 
														codigoproduto=".$qry2->data['codigoproduto']." and codcliente=".$qry2->data['codcliente']);
														$sql = "INSERT INTO tb_detalha_motivo (idinterno, data, detalhe)
														VALUES ( '$id' , '$databaixa' , '$obs_recuperar_tlmk' )";
														$qry20->executa($sql);

                                                        switch($qry3->data["qttentativa"]){

                                                                case 1://1 tentativa deve ocorrer
                                                                $qry1->nrw = 0;
                                                                $qry1->executa("select idtipomovimento, idinterno 
																                                from tbmovimento where idinterno=$id and idtipomovimento=500");
                                                                if($qry1->nrw>0){//há movimento de 1 tentativa - NAO FAZ NADA
                                                                $erro = 1;
                                                                $msg="<font color='#FF0000'>Encomenda excedeu o número máximo de tentativas.</font>";
                                                                }else{//NÃO houve tentativa - AUSENTE
                                                                $tipomovimento=500;
                                                                $msg="Encomenda Ausente 1.ª Tentativa.";
                                                                }
                                                                break;

                                                                case 2://2 tentativas devem ocorrer
                                                                $qry1->nrw = 0;
                                                                $qry1->executa("select idtipomovimento, 
																                                idinterno from tbmovimento where idinterno=$id and idtipomovimento=500");
                                                                if($qry1->nrw>0){//há movimento de 1 tentativa - procure a segunda
                                                                $qry1->nrw = 0;
                                                                $qry1->executa("select idtipomovimento, idinterno 
															                                  from tbmovimento where idinterno=$id and idtipomovimento=501");
                                                                if($qry1->nrw>0){//há movimento de 2 tentativa - NAO FAZ NADA
                                                                $erro = 1;
                                                                $msg="<font color='#FF0000'>Encomenda excedeu o número máximo de tentativas.</font>";
                                                                }else{//NÃO houve 2ª tentativa - AUSENTE
                                                                $tipomovimento=501;
                                                                $msg="Encomenda Ausente 2.ª Tentativa.";
                                                                }
                                                                }else{//NÃO houve 1ª tentativa - AUSENTE
                                                                $tipomovimento=500;
                                                                $msg="Encomenda Ausente 1.ª Tentativa.";
                                                                }
                                                                break;

                                                                case 3://3 tentativas devem ocorrer
                                                                $qry1->nrw = 0;
                                                                $qry1->executa("select idtipomovimento, idinterno 
																                                from tbmovimento where idinterno=$id and idtipomovimento=500");
                                                                if($qry1->nrw>0){//há movimento de 1 tentativa - procure a segunda
                                                                $qry1->nrw = 0;
                                                                $qry1->executa("select idtipomovimento, idinterno
																                                from tbmovimento where idinterno=$id and idtipomovimento=501");
                                                                if($qry1->nrw>0){//há movimento de 2 tentativa
                                                                $qry1->nrw = 0;
                                                                $qry1->executa("select idtipomovimento, idinterno 
																                                from tbmovimento where idinterno=$id and idtipomovimento=502");
                                                                if($qry1->nrw>0){//há movimento de 3 tentativa - NAO FAZ NADA
                                                                $erro=1;
                                                                $msg="<font color='#FF0000'>Encomenda excedeu o número máximo de tentativas.</font>";
                                                                }else{//NÃO houve 3ª tentativa - AUSENTE
                                                                $tipomovimento=502;
                                                                $msg="Encomenda Ausente 3.ª Tentativa.";
                                                                }
                                                                }else{//NÃO houve 2ª tentativa - AUSENTE
                                                                $tipomovimento=501;
                                                                $msg="Encomenda Ausente 2.ª Tentativa.";
                                                                }
                                                                }else{//NÃO houve 1ª tentativa - AUSENTE
                                                                $tipomovimento=500;
                                                                $msg="Encomenda Ausente 1.ª Tentativa.";
                                                                }
                                                                break;

                                                                default://não há tentativas - DEVOLVE
                                                                $erro=1;
                                                                $msg="<font color='#FF0000'>Encomenda excedeu o número máximo de tentativas.</font>";
                                                                break;
                                                        }

                                                        break;

                                                        //ENTREGUE
                                                        case 105:
                                                        //identifica o movimento
                                                        $codbars = str_replace("%O", "_",$codbars);
                                                        $tipomovimento=105;
                                                        $motivo = 0;
                                                        $msg="Encomenda Entregue.";
                                                        $status_encomenda = "E";
                                                        
                                                        //Data da Baixa
														                            $dt_baixa = substr($databaixa,6,4)."-".substr($databaixa,3,2)."-".substr($databaixa,0,2);
                                                        
                                                        $sqlC = "select datacoletado, dataemissao from tbentrega where idinterno = ".$id."";
                                                        $qryC->executa($sqlC);
                                                        
                                                        if(strtotime($dt_baixa) < strtotime($qry->data["datacoletado"]))
                                                        {
                                                          $msg = "<font size ='8' color='#990000'>Data    
                            														   não pode ser MENOR COLETA.</font>";
                            														   $codbars='';
                                         											    $erro = 1;
                                                        } else if(strtotime($dt_baixa) < strtotime($qry->data["dataemissao"]))
                                                        {
                                                          $msg = "<font size ='8' color='#990000'>Data    
                            														   não pode ser MENOR EMISSÂO.</font>";
                            														   $codbars='';
                                         								} else {
                                                        
                                                          $sqlBT = "select count(*) as conta from tbmovimento where idtipomovimento in(135,136,137,500,501,502) and idinterno = '".$id."'";
                                                          $qryBT->executa($sqlBT);
                                                          
                                                          if($qryBT->data["conta"] > 0)
                                                          {
                                                            //$atualizaDtPromessa = ", datapromessa = '$dt_baixa' ";
                                                            $sqlU = "Update tbentrega set datapromessa = '$dt_baixa' where idinterno = $id";
                                                            $qryU->executa($sqlU);
                                                          }
                                                          
  														                            $sql20 = "Update tbentrega set  pcg = '69', pago='69', 
                                                                    datageraarquivo = null where idexterno = '$codbars'";
                                                          $qry20->executa($sql20);
                                                          
                                                          $sql = "UPDATE tbocorrencia SET codocorrenciastatus='2' WHERE idinterno='$id'";
  		                                                    $qry_ocorre->executa($sql);
  		
                                                          if ($cliente == 6703) {
  								  	                                         $sql="update tbentrega set pago='06' WHERE idinterno='$id'";
  									                                           $qry20->executa($sql);
  									  
  									                                         }
                                                          
                                                        } 
                  
      
                                                        
                             $codbars = str_replace("%O", "_",$codbars);                           
														 $sql = "SELECT idtransportadora,dataemissao,datapromessa,datacoletado,
														 codcliente,datavencimento,codcliente,
													     codigoproduto, idinterno, codcliente FROM tbentrega WHERE idexterno='$codbars'";
												         $qry22->executa($sql);
	         										     
                                                         $dt_promessa = '';
														 														
														$dt_emissao = $qry22->data["dataemissao"];
                                                        $dt_promessa = $qry22->data["datapromessa"];
														$dt_coletado = $qry22->data["datacoletado"];
														$clienteCod = $qry22->data["codcliente"];
														
														$sql_movimento = "select dataoperacao from tbmovimento where idinterno = ".$qry22->data["idinterno"]." order by dataoperacao desc, horaoperacao desc limit 1";
												         $qry2->executa($sql_movimento);
														 
														 $dt_ultimo_movimento = $qry2->data["dataoperacao"];
														
														//Data da Baixa
														$dt_baixa = substr($databaixa,6,4)."-".substr($databaixa,3,2)."-".substr($databaixa,0,2);
														
														//Verifica se a data que esta baixando é menor que a emissão
														if($dt_baixa < $dt_emissao){
														   $msg = "<font size = 8 color='#990000'>Data    
														   não pode ser MENOR QUE EMISSÃO.</font>";
														   $codbars='';
             											    $erro = 1;
			 										    }
				                   
														//Verifica se a data que esta baixando é menor que a Coleta
														if($dt_baixa < $dt_coletado){
														   $msg = "<font size = 8 color='#990000'>Data    
														   não pode ser MENOR COLETA.</font>";
														   $codbars='';
             											    $erro = 1;
			 										    }
														
														if($dt_baixa < $dt_ultimo_movimento and ($clienteCod == '6841' or $clienteCod == '7'))
														{
															$msg = "<font size = 8 color='#990000'>Data    
														   não pode ser MENOR que a data do último movimento.</font>";
														   $codbars='';
             											   $erro = 1;
														}
														
														
														$questiona_promessa = 0;
														//Verifica se a data que esta baixando é menor que a Coleta
														if(strtotime($dt_baixa) > strtotime($dt_promessa)){
															$questiona_promessa = 1;
															
															
															
														?>
														    
															
															
															<script type="text/javascript">
																var questiona_promessa = 1;
																return validacao_formulario();
															
															</script>
														  <? 
														   $questiona_promessa=1;
														   $msg = "<font size = 8 color='#990000'>BAIXA MAIOR QUE LIMITE </font>";   
														   $codbars='';
             											  //  $erro = 1;
														   
			 										    }
				                   
								   

								


                                                      
                     $sql5 = "SELECT baixafinal FROM tbconfigproduto WHERE 
 											idtransportadora='$transportadora' AND codcliente='$cliente' AND codigoproduto='$produto'";
                      //echo $sql5;
                      //die;
                      
                      
                      $qry5->executa($sql5);
                      $baixafinal = $qry5->data["baixafinal"];
                       $codbars = str_replace("%O", "_",$codbars);
                      $sql = "select * from tbentrega where idexterno ='".$codbars."'";
                      $qry->executa($sql);
                      $cliente = $qry->data["codcliente"];
        
                      
                      
                      
                      if($baixafinal==1 and $cliente<>392)
                      {
							$codbars = $codbars_;
                           $nomerecebedor = strtoupper(trim($nomerecebedor));
                           $docrecebedor = grava_num(strtoupper(trim($docrecebedor)));
                            if(!$nomerecebedor)
                            {
						         $msg="<font color='#FF0000'>Por favor preencha o nome do recebedor. Usuario ".$_SESSION["IDUSER"]."</font>";
                                 $erro = 1;
                            }else{
                                 //$nomerecebedor = addslashes($nomerecebedor);
								 $nomerecebedor = $nomerecebedor;
								// echo $nomerecebedor;
								// die;
							}
                            
							if(!$docrecebedor)
                            {
                                $msg="<font color='#FF0000'>Por favor preencha o documento do recebedor. $codbars</font>";
                                $erro = 1;
                            }
                                                                
                          }


                     

                                                       break;
                                                        //NÃO VISITADO
                                                        case 181:
                                                        //identifica o movimento
                                                        $tipomovimento=181;
                                                        $motivo = 0;
                                                        $msg="Entrega Não realizada, Não visitado.";
                                                        break;
                                                        //ROUBO
                                                        case 132:
                                                        //identifica o movimento
                                                        $tipomovimento=132;
                                                        $status_encomenda = "E";
							$motivo = 0;
                                                        $msg="Entrega roubada.";
                                                        break;
                                                        //EXTRAVIADAS
                                                        
                                                        
                                                        case 133:
                                                        //identifica o movimento
                                                        $tipomovimento=133;
							$status_encomenda = "E";
                                                        $motivo = 0;
                                                        $msg="Entrega extraviada.";
                                                        break;

                                                        case 714:
                                                        //identifica o movimento
                                                        $tipomovimento=714;
							$status_encomenda = "D";
                                                        $motivo = 42;
                                                        $msg="Preparado para Reentrega";
                                                        break;



            //DEVOLVIDOS
            case 150:
                 $tipomovimento=150;
                 $motivo = $cbmotivo;
                 $msg="Entrega devolvida.";
                 $status_encomenda = "D";
                
                 
                 
                 
				 //VERIFICA SE A ENCOMENDA JA FOI ENTREGUE ALGUM VEZ NO ENDEREÇO ATUAL
				 // ISSO SERVE PARA EVITAR BAIXAR POR ENDEREÇO INSUFICIENTE INDEVIDA.
				 $sql = "SELECT nomeentrega,enderecoentrega, idinterno
			    		 FROM tbenderecoentrega
               	         WHERE 
                         idinterno = '$id'  ";
						 $qry20->executa($sql);
						 $endereco  = $qry20->data["enderecoentrega"];
						 $nome  = $qry20->data["nomeentrega"];
				   
							  
                 
                 
                 if ($qry20->nrw){
                     $sql= "SELECT * FROM tbmotivo WHERE idmotivo = '$motivo' and verificahistorico = 't'";
                     $qry20->executa($sql);
                  
                 if ($qry20->nrw){
                    $sql = "select st as status ,tbentrega.dataentrega, ";
                    $sql = $sql ." tbentrega.idinterno, tbentrega.idexterno ";
                    $sql = $sql. " from tbentrega,tbenderecoentrega "; 
                    $sql = $sql ." where"; 
                    $sql = $sql ." tbentrega.idinterno = tbenderecoentrega.idinterno";
                    $sql = $sql ." and ";
                    $sql = $sql ." tbentrega.st='E'";
                    $sql = $sql ." and";
                    $sql = $sql ." tbenderecoentrega.nomeentrega = '$nome'";
                    $sql = $sql ." and ";
                    $sql = $sql ." tbenderecoentrega.enderecoentrega = '$endereco'";
                    $sql = $sql ." limit 3 ";
                    //echo $sql;
                    $qry20->executa($sql);
                    $erro2=0;
                    if ($qry20->nrw){
                        $msg="<font color='#FF0000'>Esta encomenda já foi
						   	   entregue no mesmo endereço em outra data</font>";
                         $erro = 1;
                         $erro2=1;
                  }
                   $sql = "INSERT INTO tb_detalha_motivo (idinterno, data, detalhe)
								   VALUES ( '$id' , '$databaixa' , '$obs_recuperar_tlmk' )";
                   $qry2->executa($sql);
                  }
                                                        }
                                                        break;
                                                        //RECUPERADA
                                                        case 180:
                                                        //identifica o movimento
                                                        $tipomovimento=180;
                                                        $motivo = 17;
                                                        $msg="Entrega Recuperada.";
                                                        break;
                                                        //BAIXA ERRADA
                                                        case 182:
                                                        //identifica o movimento
                                                        $tipomovimento=182;
                                                        $motivo = 17;
                                                        $msg="Entrega Recuperada de uma Baixa Errada.";
                                                        break;
                                                        //CORREIOS
                                                        case 400:
                                                        //identifica o movimento
                                                        $codbars = str_replace("%O", "_",$codbars);
                                                        $tipomovimento=400;
                                                        $motivo = 0;
                                                        $msg="Entrega despachada via Correios.";
                                                        $status_encomenda = "E";
                                                        $sql20 = "Update tbentrega set pago = 69,datageraarquivo = null where idexterno = '$codbars'";
                                                        $qry20->executa($sql20);
                                                        
                                                        break;

                                                }//RADIO - END



                                        if(!$erro){
                                                //identifica o courier
                                                $qry2->executa("SELECT codcourier FROM tblista WHERE numlista='$numlista_tbentrega'");
                                                $courier = $qry2->data["codcourier"];

                                                //Codigo do morivo
                                                if(!$cbmotivo || $cbmotivo < 0){
                                                        $motivo = 0;
                                                }else{
                                                        $motivo = $cbmotivo;
                                                }



                                                $err[0]=inseremovimento($id,$movimentoatual,$tipomovimento,
												                        $numlista_tbentrega,$base,$courier,$motivo,trim($obs_recuperar_tlmk),"nao_faz_update_tbentrega");
                                                if($err[0]<>0){
                                                        $msg=$msg." <font color='#FF0000'>(Movimento não permitido.)".$err[0]."/".$movimentoatual."/".$tipomovimento."</font>";
                                                        $no++;
                                                        $erro = 1;
                                                }else{

                                                        $sql5 = "SELECT codtipoestoque FROM tbtipomovimento WHERE idtipomovimento=".$tipomovimento;
                                                        $qry5->executa($sql5);
                                                        $codtipoestoque = $qry5->data["codtipoestoque"];

                                                        //Begin
                                                        $sql="UPDATE tbentrega SET ";
                                                        if(trim($numlista_tbentrega)!="" and intval($numlista_tbentrega) > -1)$sql.= " numlista='".intval($numlista_tbentrega)."',";
                                                        if(intval($base) > 0 and $cliente <> 6670) $sql.= " codbase='".intval($base)."',";
                                                        if($codtipoestoque > 0)$sql.= " codtipoestoque='$codtipoestoque',";
                                                        if($tipomovimento==150 || $tipomovimento==105 || $tipomovimento==400 ) $sql.= " st='".$status_encomenda."',"; //gravando status
                                                        //se for SIMPLES e (Disponivel para Devolução ou Telemarketing 1ª tentativa ou Telemarketing 2ª tentativa ou Telemarketing 3ª tentativa)
                                                        if($tipomovimento==150 || $tipomovimento==135 || $tipomovimento==136 || $tipomovimento==137){
                                                                $motivo = ((!$motivo)?"0":"$motivo");
                                                                $sql.= " idmotivo='".$motivo."',"; //MOTIVO BAIXA
                                                                $status_encomenda = "T";
                                                        }
                                                        //dados do recebedor
                                                        if($baixafinal==1 and $tipomovimento==105){
                                                                $sql.= " nomerecebedor='".$nomerecebedor."',";
                                                                $sql.= " docrecebedor='".$docrecebedor."',";
                                                                $sql.= " primeiroenvelope=88888888,";
                                                                
                                                                   
                                                                
                                                        }                                                        
                                                       
                                                         if($tipomovimento!=150 || $tipomovimento!=135 || $tipomovimento!=136 || $tipomovimento!=137){
                                                        $sql.= " dataentrega='".substr($databaixa,6,4)."-".substr($databaixa,3,2)."-".substr($databaixa,0,2)."',";
                                                        }
                                                        $sql.= " dataoperacao"."="."'".date('Y-m-d')."'".", ";
                                                        $sql.= " idtipomovimento"."=".$tipomovimento;
                                                        $sql.= " WHERE idinterno=".$id;
                                                        $qry5->executa($sql);
                                                        if(!$qry5->res)
                                                            die("Ocorreu durante a atualização da encomenda");
                                                        else{
                                                                $acaboudebaixar = 1;
                                                                $baixafinal = 0;
                                                                $nomerecebedor = "";
                                                                $docrecebedor = "";
                                                        }

                                                       


                                                        
                                                        $ok++;
                                                        $msg=$msg." (Baixa Ok!)";

                                                        if($controla_lista=='t' and $num_lista){
                                                                $qtd_baixados = $qtd_baixados+1;
                                                                $qtd_saldo = $qtd_montados - $qtd_baixados;
                                                        }



                                                 
                                                }
                                        }

                                }else{
                                        //Se essa encomenda não está para essa Base
                                        $msg.="Esta encomenda não está nesta base.";
                                        $erro = 1;
                                }
                        }
                }
        }

break;

}

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>

<!-- CSS Local -->
<link href="<?=HOST?>/css/table_2.css" rel="stylesheet" type="text/css" />
<link href="<?=HOST?>/css/tip.css"     rel="stylesheet" type="text/css" />

<!-- Script local -->
<script type="text/javascript">
function verifica_radio( arg ){

	document.baixa_lista.radio_bkp.value =  arg;
	document.getElementById('linha_aviso').style.display='none';
	document.baixa_lista.obs_label.type='hidden';
	document.baixa_lista.obs_recuperar_tlmk.style.width='0px';
	document.baixa_lista.obs_recuperar_tlmk.style.border='0px';
	
	

        for (i=0; i<document.baixa_lista.radio.length; i++){

                if(document.baixa_lista.radio[i].checked && document.baixa_lista.radio[i].value=='105'){
                //entregue
                        document.baixa_lista.cbmotivo.disabled=true;
                        document.baixa_lista.obs_label.type='hidden';
                        document.baixa_lista.obs_recuperar_tlmk.style.width='0px';
                        document.baixa_lista.obs_recuperar_tlmk.style.border='0px';
                        document.baixa_lista.codbars.focus();


                        document.baixa_lista.enviar.type='hidden';
                        document.baixa_lista.codbars.size='60';
                        document.baixa_lista.opt.value='PL';
                        document.baixa_lista.procurar_baixa_final.type='submit';

                        if(document.baixa_lista.baixafinal.value=='1'){
                                document.baixa_lista.nomerecebedor_label.type='text';
                                document.baixa_lista.docrecebedor_label.type='text';
                                document.baixa_lista.recebedorobrigatorio_label.type='text';
                                document.baixa_lista.nomerecebedor.type='text';
                                document.baixa_lista.docrecebedor.type='text';
                        }

                }
                if(document.baixa_lista.radio[i].checked && document.baixa_lista.radio[i].value=='5'){
                //ausente
                        document.baixa_lista.cbmotivo.disabled=true;
                        document.baixa_lista.obs_label.type='text';
			            document.baixa_lista.obs_label.value= 'INFOME AS CARACTERISTICAS DO LOCAL';
			
                        document.baixa_lista.obs_recuperar_tlmk.style.width='465px';
                        document.baixa_lista.obs_recuperar_tlmk.style.border='1px solid #cccccc';
			            document.getElementById('linha_aviso').style.display='block';
			

                        document.baixa_lista.procurar_baixa_final.type='hidden';
                        document.baixa_lista.codbars.size='73';
                        document.baixa_lista.opt.value='BE';
                        document.baixa_lista.enviar.type='submit';

                        document.baixa_lista.codbars.focus();

                        document.baixa_lista.nomerecebedor_label.type='hidden';
                        document.baixa_lista.docrecebedor_label.type='hidden';
                        document.baixa_lista.recebedorobrigatorio_label.type='hidden';
                        document.baixa_lista.nomerecebedor.type='hidden';
                        document.baixa_lista.docrecebedor.type='hidden';
                        document.baixa_lista.baixafinal.value='0';

                }
		else if(document.baixa_lista.radio[i].checked && document.baixa_lista.radio[i].value=='150'){
                //devolucao
                        document.baixa_lista.cbmotivo.disabled=false;
                        document.baixa_lista.obs_label.type='hidden';
                        document.baixa_lista.obs_recuperar_tlmk.style.width='0px';
                        document.baixa_lista.obs_recuperar_tlmk.style.border='0px';

                        document.baixa_lista.procurar_baixa_final.type='hidden';
                        document.baixa_lista.codbars.size='73';
                        document.baixa_lista.opt.value='BE';
                        document.baixa_lista.enviar.type='submit';

                        document.baixa_lista.cbmotivo.focus();

                        document.baixa_lista.nomerecebedor_label.type='hidden';
                        document.baixa_lista.docrecebedor_label.type='hidden';
                        document.baixa_lista.recebedorobrigatorio_label.type='hidden';
                        document.baixa_lista.nomerecebedor.type='hidden';
                        document.baixa_lista.docrecebedor.type='hidden';
                        document.baixa_lista.baixafinal.value='0';

                }else if(document.baixa_lista.radio[i].checked && document.baixa_lista.radio[i].value=='1'){
                //telemarkentig
                        document.baixa_lista.cbmotivo.disabled=false;
                        document.baixa_lista.obs_label.type='text';
                        document.baixa_lista.obs_recuperar_tlmk.style.width='465px';
                        document.baixa_lista.obs_recuperar_tlmk.style.border='1px solid #cccccc';
			document.baixa_lista.obs_label.value='Observações';
			

                        document.baixa_lista.procurar_baixa_final.type='hidden';
                        document.baixa_lista.codbars.size='73';
                        document.baixa_lista.opt.value='BE';
                        document.baixa_lista.enviar.type='submit';

                        document.baixa_lista.codbars.focus();

                        document.baixa_lista.nomerecebedor_label.type='hidden';
                        document.baixa_lista.docrecebedor_label.type='hidden';
                        document.baixa_lista.recebedorobrigatorio_label.type='hidden';
                        document.baixa_lista.nomerecebedor.type='hidden';
                        document.baixa_lista.docrecebedor.type='hidden';
                        document.baixa_lista.baixafinal.value='0';

                }else if(document.baixa_lista.radio[i].checked && document.baixa_lista.radio[i].value=='180'){
                //recuperar
                        document.baixa_lista.cbmotivo.disabled=true;
                        document.baixa_lista.obs_label.type='text';
                        document.baixa_lista.obs_recuperar_tlmk.style.width='465px';
                        document.baixa_lista.obs_recuperar_tlmk.style.border='1px solid #cccccc';

                        document.baixa_lista.procurar_baixa_final.type='hidden';
                        document.baixa_lista.codbars.size='73';
                        document.baixa_lista.opt.value='BE';
                        document.baixa_lista.enviar.type='submit';

                        document.baixa_lista.codbars.focus();

                        document.baixa_lista.nomerecebedor_label.type='hidden';
                        document.baixa_lista.docrecebedor_label.type='hidden';
                        document.baixa_lista.recebedorobrigatorio_label.type='hidden';
                        document.baixa_lista.nomerecebedor.type='hidden';
                        document.baixa_lista.docrecebedor.type='hidden';
                        document.baixa_lista.baixafinal.value='0';

                }else if(document.baixa_lista.radio[i].checked){
                //restante...
                        document.baixa_lista.cbmotivo.disabled=true;
                        document.baixa_lista.obs_label.type='hidden';
                        document.baixa_lista.obs_recuperar_tlmk.style.width='0px';
                        document.baixa_lista.obs_recuperar_tlmk.style.border='0px';

                        document.baixa_lista.procurar_baixa_final.type='hidden';
                        document.baixa_lista.codbars.size='73';
                        document.baixa_lista.opt.value='BE';
                        document.baixa_lista.enviar.type='submit';

                        document.baixa_lista.codbars.focus();

                        document.baixa_lista.nomerecebedor_label.type='hidden';
                        document.baixa_lista.docrecebedor_label.type='hidden';
                        document.baixa_lista.recebedorobrigatorio_label.type='hidden';
                        document.baixa_lista.nomerecebedor.type='hidden';
                        document.baixa_lista.docrecebedor.type='hidden';
                        document.baixa_lista.baixafinal.value='0';

                }

        }

}

function validacao_formulario(){

	var questiona_promessa = <?php echo $questiona_promessa; ?>;
	
		marcado=0;
        for (i=0; i<document.baixa_lista.radio.length; i++){
                if(document.baixa_lista.radio[i].checked)
                        marcado=1;

                if(document.baixa_lista.radio[i].checked && document.baixa_lista.radio[i].value=='180' && document.baixa_lista.obs_recuperar_tlmk.value.length < 19){
                        alert('Por favor, preencha o campo de observação com pelo menos 20 caracteres');
                        document.baixa_lista.obs_recuperar_tlmk.focus();
                        return false;
                }
                <? if(($_SESSION["IDUSER"] != 212)&&($_SESSION["IDUSER"] != 551)){?>
				
				if(document.baixa_lista.radio[i].checked && document.baixa_lista.radio[i].value=='105' && document.baixa_lista.nomerecebedor.type=='text' && document.baixa_lista.nomerecebedor.value.length < 3 && document.baixa_lista.opt.value=='BE'){
						alert('Por favor, preencha o Nome do Recebedor');
						document.baixa_lista.nomerecebedor.focus();
						return false;
				}


				if(document.baixa_lista.radio[i].checked && document.baixa_lista.radio[i].value=='105' && document.baixa_lista.docrecebedor.type=='text' && document.baixa_lista.docrecebedor.value.length < 3 && document.baixa_lista.opt.value=='BE'){
						alert('Por favor, preencha o Documento do Recebedor');
						document.baixa_lista.docrecebedor.focus();
						return false;
				}

                <?}?>
        }

        if (marcado==0){
                alert('Por favor, selecione um status para a baixa');
                return false;
        }

        if(document.baixa_lista.cbmotivo.disabled==false && document.baixa_lista.cbmotivo.value=='-1'){
                alert('Por favor, selecione um motivo');
                document.baixa_lista.cbmotivo.focus();
                return false;
        }

        if(document.baixa_lista.databaixa.value==''){
                alert('Por favor, preencha o campo data da baixa');
                document.baixa_lista.databaixa.focus();
                return false;
        }

        if(document.baixa_lista.codbars.value==''){
                alert('Por favor, preencha o campo código de barras');
                document.baixa_lista.codbars.focus();
                return false;
        }
		
		if(questiona_promessa==1){
                //alert('Por favor, ....');
				var q = confirm("A data informada é maior que a data limite, confirma a operação ?");
				
				if (q == true) {
					var questiona_promessa = 0;
					return true;
				} else {
					document.baixa_lista.codbars.focus();
					return false;
				}
        }
		
		
        if(document.baixa_lista.controla_lista.checked && document.baixa_lista.num_lista.value==''){
                alert('Por favor, preencha o número da lista');
                document.baixa_lista.num_lista.focus();
                return false;
        }

        return true;
}

function check_controla_lista(obj_check){
        if(obj_check.checked){
                document.baixa_lista.num_lista.disabled=false;
                document.baixa_lista.controla_lista_ok.disabled=false;
                document.baixa_lista.codbars.disabled=true;
                document.baixa_lista.enviar.disabled=true;
                document.baixa_lista.num_lista.focus();
        }else{
                document.baixa_lista.num_lista.disabled=true;
                document.baixa_lista.controla_lista_ok.disabled=true;
                document.baixa_lista.codbars.disabled=false;
                document.baixa_lista.enviar.disabled=false;
        }
}

/**
 * Funcao de inicializacao
 */
function init() {
	// posiciona
	document.baixa_lista.codbars.focus();

    <?=(($radio==105 and ($_POST['opt']=="PL" or $erro==1 or $acaboudebaixar==1))?"document.baixa_lista.enviar.type='hidden';document.baixa_lista.codbars.size='60';document.baixa_lista.opt.value='PL';document.baixa_lista.procurar_baixa_final.type='submit';":"")?>	
}
</script>

<div style="width: 900px; margin: 0 auto;">
  <form name="baixa_lista" action="<?=$selfLink?>" method="post" onsubmit='return validacao_formulario()'>

      <input type='hidden' name='opt' value='BE'>
      <input type='hidden' name='baixafinal' value='<?=$baixafinal;?>'>
      <input type='hidden' name='qtd_montados' value='<?=$qtd_montados;?>'>
      <input type='hidden' name='qtd_baixados' value='<?=$qtd_baixados;?>'>
      <input type='hidden' name='qtd_saldo' value='<?=$qtd_saldo;?>'>
	  

    <table class = "tabela" style="border: none;">
      <tr bgcolor="#eeeeee"> 
        <td  colspan=10><b> 
          <div align="center"><font size="6"><font size="4">..:: </font><font color="#fff" size="3">Baixa 
            de Entregas</font></font><font size="4"> ::..</font></div>
          </b></td>
      </tr>
      
      <tr> 
        <td colspan="10" bgcolor="#eeeeee"> <center>
          </center>
          <center>
           
           &nbsp;&nbsp;&nbsp;Permitir baixa por usu&aacute;rio de base diferente?&nbsp; <input type='checkbox' name='controla_base' value='t' <?=($controla_base=='t')?"checked":"";?> onclick='javascritp:check_controla_base(this)'>
           
          </center></td>
      </tr>
      <?php
                        echo "<tr>
                              <td>";
                        echo "<input type='radio' name='radio' value='105' ".(($radio==105)?"checked":"")." onClick='javascript:verifica_radio( this.value )'>Entregue";
                        echo "</td>
                              <td nowrap>";
                        echo "<input type='radio' name='radio' value='150' ".(($radio==150)?"checked":"")." onClick='javascript:verifica_radio( this.value )'>Devolu&ccedil;&atilde;o";
                        echo "</td>
                              <td nowrap>";
                        echo "<input type='radio' name='radio' value='5' ".(($radio==5)?"checked":"")." onClick='javascript:verifica_radio( this.value )'>Ausente";
                        echo "</td>
                              <td nowrap>";
                        echo "<input type='radio' name='radio' value='1' ".(($radio==1)?"checked":"")." onClick='javascript:verifica_radio( this.value )'>Telemarketing";
                        echo "</td>
                              <td nowrap>";
                        echo "<input type='radio' name='radio' value='181' ".(($radio==181)?"checked":"")." onClick='javascript:verifica_radio( this.value )'>N&atilde;o Visitado";
                        echo "</td>
                                                <td nowrap>";
                        //echo "                <input type='radio' name='radio' value='180' ".(($radio==180)?"checked":"")." onClick='javascript:verifica_radio()'>Recuperar";
                        
                        echo "<input type='radio' name='radio' value='182' ".(($radio==182)?"checked":"")." onClick='javascript:verifica_radio( this.value )'>Baixa Errada";
                        echo "</td>
                        <td nowrap>";

                        echo "<input type='radio' name='radio' value='714' ".(($radio==714)?"checked":"")." onClick='javascript:verifica_radio( this.value )'>Reentrega";
                        echo "</td>
                        <td  colspan = 5 nowrap>";



                        echo " <input type='radio' name='radio' value='400' ".(($radio==400)?"checked":"")." onClick='javascript:verifica_radio( this.value )'>Correios";
                        echo "        </td>
                              </tr>";
					  ?>
					  <input type=hidden name=radio_bkp>
					  <SCRIPT>
					  	function atualiza_motivo( arg ){
							tipo = arg.substring(0,1);
							
							document.baixa_lista.obs_label.type='hidden';
							document.baixa_lista.obs_label.value='INFORME AS CARACTERISTICAS DO LOCAL';
							document.baixa_lista.obs_recuperar_tlmk.style.width='0px';
							document.baixa_lista.obs_recuperar_tlmk.style.border='0px';
							document.getElementById('linha_aviso').style.display='none';
							
							if ( document.baixa_lista.radio_bkp.value == 150 && tipo == 1 ){
								
								document.baixa_lista.obs_label.type='text';
								document.baixa_lista.obs_recuperar_tlmk.style.width='465px';
								document.baixa_lista.obs_recuperar_tlmk.style.border='1px solid #cccccc';
								document.getElementById('linha_aviso').style.display='block';
							}
							
							return true;
						}
					  </SCRIPT>
					  <?php
					  echo "
                                          <tr>
                                                <td align='right'>Motivo:</td>
                                                <td colspan='2'>
                                                        <select onchange='atualiza_motivo(this.value)' name='cbmotivo' ".(($radio==1 or $radio==150)?" ":"disabled").">";
                        combo("SELECT case when (detalha_ocorrencia) then 1 else 0 end ||'|'||idmotivo,motivo FROM tbmotivo ORDER BY motivo",$cbmotivo2);
                        echo "                </select>
                                        </td>
                                                  <td colspan='7'>
                                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data da Baixa:&nbsp;&nbsp;
                                                <input name='databaixa' type='text' id='databaixa' value=".(($databaixa)? "$databaixa": date("d/m/Y"))." size='10' maxlength='10'> <font color='FF0000'><= <b>Aten&ccedil;&atilde;o:</b> Informe a data REAL </font>
                                                </td>
                                          </tr>";
                                          $codbars = str_replace("%O", "_",$codbars);
?>
      <tr>
        <td height="24" align="right" nowrap> C&oacute;digo de Barras: </td>
        <td height="24" colspan="8"><input name="codbars" type="text" id="codbars5" size="73" maxlength="50" value="<?=(($radio==105 and !$acaboudebaixar)?"$codbars":"");?>" <?=(($radio==105)?"onchange=\"javascript:if(document.baixa_lista.baixafinal.value=='1'){document.baixa_lista.opt.value='PE';document.baixa_lista.enviar.type='hidden';document.baixa_lista.codbars.size='60';document.baixa_lista.baixafinal.value=='0';document.baixa_lista.nomerecebedor_label.type='hidden';document.baixa_lista.docrecebedor_label.type='hidden';document.baixa_lista.recebedorobrigatorio_label.type='hidden';document.baixa_lista.nomerecebedor.type='hidden';document.baixa_lista.docrecebedor.type='hidden';document.baixa_lista.procurar_baixa_final.type='submit';}\"":"");?> >
          &nbsp; <input type="hidden" onClick="javascript:document.baixa_lista.opt.value='PE';document.baixa_lista.submit();" name="procurar_baixa_final" value="Procurar" size="10">
          &nbsp;&nbsp;&nbsp;Controla Lista?&nbsp; <input type='checkbox' name='controla_lista' value='t' <?=($controla_lista=='t')?"checked":"";?> onclick='javascritp:check_controla_lista(this)'>
          &nbsp;&nbsp; <input type='text' name='num_lista' value='<?=$num_lista;?>' size='7' <?=(!$num_lista or $controla_lista!='t')?"disabled":""?> >
          &nbsp;&nbsp; <input type='button' onClick="javascript:document.baixa_lista.opt.value='PL';document.baixa_lista.baixafinal.value=='0';document.baixa_lista.submit();" name='controla_lista_ok' value='Ok' <?=(!$num_lista or $controla_lista!='t')?"disabled":""?> ></td>
          
           <td>
           <input type="button" name="ar" class="botao" onclick='javascript:window.open("exibe_pdf.php?opt=I&idexterno=<?=$codbars;?>");' value='Ver AR '>
             </td>
          
          
          
      </tr>    
      <?php





       
        echo "<tr>
              <td height='24' align='right' valign='top' nowrap><input type='".(($radio==105 and $baixafinal==1 and $cliente<>392)?"text":"hidden")."' class='noborder_input' name='nomerecebedor_label' Value='Nome do Recebedor:' size='19' readOnly></td>
              <td height='24' colspan=9 valign='top'>
              <input type='".(($radio==105 and $baixafinal==1and  $cliente<>392)?"text":"hidden")."' name='nomerecebedor' value='$nomerecebedor' size='31' maxlength='30'>
              <input type='".(($radio==105 and $baixafinal==1 and $cliente<>392)?"text":"hidden")."' class='noborder_input' name='docrecebedor_label' Value='&nbsp;Documento:&nbsp;' size='13' readOnly>
              <input name='docrecebedor' type='".(($radio==105 and $baixafinal==1 and $cliente<>392)?"text":"hidden")."' value='$docrecebedor' size='21' maxlength='18'>
              </td>
              </tr>
		      <tr>
		      <td colspan=10>";
			
			
			
			
if($erro2==1)
{
 echo "<table border=1 align=center cellpadding=-1 cellspacing=0>";
 echo "<tr><td colspan=3>ENCOMENDA ENTREGUES ANTERIORMENTE</td></tr>";
 echo "<tr>
       <th>id interno</th>
       <th>id externo</th>
       <th>data entrega</th>
       </tr>";
 for($i=0;$i<$qry20->nrw;$i++){
     $qry20->navega($i);
     echo "<tr bgcolor=#".(($i%2)?"eeeeee":"ffffff").">
     <td><a href='auditoria_n.php?opt=S&idinterno=".$qry20->data["idinterno"]."&idexterno=".$qry20->data["idexterno"]." '>".$qry20->data["idinterno"]."</a></td>
     <td>".$qry20->data["idexterno"]."</td>
     <td>".mostra_data($qry20->data["dataentrega"])."</td>
     </tr>";
  }
 echo "</table>";                         
}
			
								
								
	echo "<table wodth=100% border=0 >";
        echo "<tr id='linha_aviso' style='display:none'>
                      <td align=center colspan=3 > 
				<font color=#aa0000>
				Prezados usuários: <BR><BR>
				A partir desta data faz-se necessario informar as caracteristicas do local visitado em casos de ausente, 
				Desconhecido, e outros motivos que o sistema solicitar, Exemplo : Cor do imóvel ao lado, cor da casa do 
				muro etc... Essa medida visa provar ao nosso cliente que estivemos no local para realizar a entrega.
				<BR>
				Agradecemos a todos pela atenção e apoio
				</font> 
			</td>
                </tr>
	</table>";
	
	echo "</td>
	</tr>
	
		<td align = left colspan=10>";
	
	echo "<table wodth=100% border=0 >";
        echo "<tr>
			<td height='24' align='right' valign='top' nowrap><input type='".(($radio==180)?"text":"hidden")."' class='noborder' name='obs_label' Value='Observações:' size='35' readOnly></td>
			<td height='24' colspan=8 valign='top'>
				<textarea ".(($radio==180)?"":"class='hidden'")." name='obs_recuperar_tlmk' cols='55' rows='3'>$obs_recuperar_tlmk</textarea>
			</td>
                </tr>";
	echo "</table>";
								
	echo "</td>
	</tr>
	";

?>
      <tr>
        <td height="17" colspan="10" align="center"><input type='<?=(
        ($radio==105 and $baixafinal==1 and $cliente<>392)?
        "text":"hidden");?>' style='font-size: 12px; font-family: Arial,
         Helvetica; color: #FF0000; text-align: right; border: 0px solid;' 
         name='recebedorobrigatorio_label' Value='Para esse produto é obrigatório infomar os dados do recebedor.' size='70' readOnly></td>
      </tr>
      
      <tr> 
        <td height="17" colspan="10"><div align="center"><?echo $msg;?></div></td>
      </tr>
      <?php if($num_lista and $qtd_montados){ ?>
      <tr> 
        <td colspan="8" align="center"> <table border=1 cellspacing=2 cellpadding=2>
            <tr bgcolor='dddddd'> 
              <td align='center'><b>Qtd Original</b></td>
              <td align='center'><b>Montados</b></td>
              <td align='center'><b>Baixados</b></td>
              <td align='center'><b>Saldo</b></td>
            </tr>
            <tr> 
              <td width='100' align='center'><font color='FF0000' size=5 > 
                <?=$qtd_orginal;?>
                </font></td>
              <td width='100' align='center'><font color='FF0000' size=5 > 
                <?=$qtd_montados;?>
                </font></td>
              <td width='100' align='center'><font color='0000FF' size=5 > 
                <?=$qtd_baixados;?>
                </font></td>
              <td width='100' align='center'><a href='<?=(($qtd_saldo>0)?"$PHP_SELF?detalhe_saldo=1&num_lista=$num_lista&controla_lista=$controla_lista&qtd_montados=$qtd_montados&qtd_baixados=$qtd_baixados&qtd_saldo=$qtd_saldo":"");?>'><font color='FF0000' size=5 > 
                <?=$qtd_saldo;?>
                </a></font></td>
            </tr>
          </table></td>
      </tr>
      <?php } ?>
      <tr> 
        <td height="17" colspan="10"><div align="right">
          <input type="hidden" name="enviar2" value="enviar">
            <input name="enviar" type="submit" id="enviar" value="Enviar">
          </div></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="800" align="left">
      <tr> 
        <td height="17" colspan="7" bgcolor="#eeeeee" >
<div align="left"></div></td>
      </tr>
    </table>
<?



if($detalhe_saldo and $num_lista and $qtd_saldo > 0){
        echo "<table border=0 align=center cellpadding=2 cellspacing=2>";
        echo "<tr><td>&nbsp;</td></tr>";

        $sql = "SELECT tbentrega.idinterno , tbentrega.idexterno , tbenderecoentrega.nomeentrega  , 
		        tbenderecoentrega.enderecoentrega FROM tbentrega, tbenderecoentrega WHERE 
				tbenderecoentrega.idinterno = tbentrega.idinterno  AND tbentrega.numlista='$num_lista' AND tbentrega.idtipomovimento='104' ";
        $qry->executa($sql);

        if ($qry->nrw){

                echo "<tr>
                                 <td>Id Interno</td>
                                 <td>Id Externo</td>
                                 <td>Nome</td>
                                 <td>Endereço</td>
                               </tr>";

                For($i=0;$i<$qry->nrw;$i++){
                        $qry->navega($i);

                        echo "<tr bgcolor=#".(($i%2)?"eeeeee":"ffffff").">
                                 <td><a href='auditoria.php?idinterno=".$qry->data["idinterno"]."&opt=S'>".$qry->data["idinterno"]."</a></td>
                                 <td>".$qry->data["idexterno"]."</td>
                                 <td>".$qry->data["nomeentrega"]."</td>
                                 <td>".$qry->data["enderecoentrega"]."</td>
                               </tr>";
                }
                echo "<tr>
                               <td colspan=4 align=center>".$qry->nrw." registros listados</td>
                          </tr>";
        }
        else
        echo "<tr>
                                 <td colspan=2 align=center><font color=#ff0000>Nenhuma encomenda encontrada</font></td>
                               </tr>";
        echo "</table>";
}


   echo "<br>";
   echo "<br>";
  
?>
</form>
</div>

<?php
// pega o Footer
require_once("inc/footer.inc");