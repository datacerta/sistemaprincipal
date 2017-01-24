<?
session_start();
//phpinfo();
//CLIENTE REDECARD = 392
//PRODUTO = 47

//inclui biblioteca de controles
require_once("classes/diversos.inc.php");


//testa sessão
//if (VerSessao()==false){
//        header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
//}


  
  
   function  importador_tipo_000($linha){
            $posicoes[] = 3;    // 0  Tipo de Registro ->> fixo 000
            $posicoes[] = 35;   // 1  Identificação do Remetente
            $posicoes[] = 35;   // 2  Identificação do destinatario
            $posicoes[] = 6;    // 3  Data de Uso EDI
            $posicoes[] = 4;    // 4  Hora Uso EDI
            $posicoes[] = 12;   // 5  Identificação do Inercambio (numlotecliente)
            $posicoes[] = 145;  // 6  Filler
            
           
            $posicao_inicial = 0;
            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_000["numlotecliente"] = $campo[5]; 
			

            return $retorno_000;
  }
  
  
  
   function  importador_tipo_310($linha){
            $posicoes[] = 3;    // 0  Tipo de Registro ->> fixo 310
            $posicoes[] = 14;   // 1  Lote 
            $posicoes[] = 223;  // 2  Filler
            $posicao_inicial = 0;
            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }
            $retorno_000["numloteinterno"] = $campo[1]; 
	          return $retorno_000;
  }
  
  
  
  
  function  importador_tipo_311($linha){
            //DADOS DA EMBARCADORA
            $posicoes[] = 3;    // 0  Tipo de Registro ->> fixo 311
            $posicoes[] = 14;   // 1  cnpj 
            $posicoes[] = 15;   // 2  Inscrição estaduao
            $posicoes[] = 40;   // 3  Lograqdouro
            $posicoes[] = 35;   // 4  Cidade  
            $posicoes[] = 9;    // 5  CEP
            $posicoes[] = 9;    // 6  Estado
            $posicoes[] = 8;    // 7  Data Emissao DDMMAAAA
            $posicoes[] = 40;   // 8  Embarcadora
            $posicoes[] = 67;   // 9  Filler
            $posicao_inicial = 0;
            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_000["dataemissao"] = $campo[7]; 
			

            return $retorno_000;
  }
  
  
  
  
  
  

  function  importador_tipo_312($linha){
            //DADOS DO DESTINATÁRIO DA ENREGA FINAL
            $posicoes[] =  3;   // 0 -  Tipo de Registro = Fixo 312
            $posicoes[] = 35;   // 1 -  Razão social 
            $posicoes[] = 14;   // 2 -  Cnpj
            $posicoes[] = 15;   // 3 -  IE
            $posicoes[] = 65;   // 4 -  Endereço
            $posicoes[] = 19;   // 5 -  Bairro
            $posicoes[] = 30;   // 6 -  Cidade
            $posicoes[] = 8;    // 7 -  CEP
            $posicoes[] = 9;    // 8 -  Código do Município
            $posicoes[] = 2;    // 9 -  Estado
            $posicoes[] = 4;    // 10 -  Área de coleta ex. Portão ou doca
            $posicoes[] = 35;   // 11 -  Telefones
            $posicoes[] = 1;   //  12 -  Tipo identificação 1=PJ 2=PF
           
            $posicao_inicial = 0;
            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_001["nomeentrega"] = $campo[1];
            $retorno_001["enderecoentrega"] = $campo[4];
            $retorno_001["bairroentrega"] = $campo[5];
            $retorno_001["cidadeentrega"] = $campo[6];
            $retorno_001["estadoentrega"] = $campo[9];
			      $retorno_001["cepentrega"] = $campo[7];
            $retorno_001["foneenderecoentrega"] = $campo[11];
            
		    return $retorno_001;
  }

  function  importador_tipo_313($linha){
            //DADOS DA NOTA FISCAL
            $posicoes[] = 3; 	  //Tipo de Regsitro
            $posicoes[] = 15; 	//Numero do Romaneio (usar como idexterno)
            $posicoes[] = 7; 	  //Código da Rota
            $posicoes[] = 1; 	  //Meio de Transporte - NA
            $posicoes[] = 1; 	  //Tipo do Transporte - Na
            $posicoes[] = 1; 	  //Tipo da Carga
            $posicoes[] = 1; 	  //Condição do Frete - C=CIF   F=FOB
            $posicoes[] = 3; 	  //Séria da Nota Fiscal
            $posicoes[] = 8; 	  //Número da Nota Fiscal
            $posicoes[] = 8; 	  //Data da Emissão
            $posicoes[] = 15; 	//Natureza da Mercadoria
            $posicoes[] = 15; 	//Especie de acondicionamento
            $posicoes[] = 7; 	  //Quantidade volumes
            $posicoes[] = 15;   //Valor da Nota
            $posicoes[] = 7; 	  //Peso Total
            $posicoes[] = 5; 	  //Peso Cubado
            
            
            
             
            
          
          
          
          
           $posicao_inicial = 0;
            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_002["idkit"] = $campo[1];
            $retorno_002["nomekit"] = $campo[2];
            $retorno_002["quantidadekit"] = $campo[3];


            return $retorno_002;
  }


  function  importador_tipo_003($linha){
            $posicoes[] = 2; 	  //Tipo de Regsitro
            $posicoes[] = 10;     //Código do Motivo
            $posicoes[] = 80;     //Descrição do Motivo
            $posicoes[] = 147;    //Descrição da Reclamação
            $posicoes[] = 10;     //Código da Entrega Original
			      $posicoes[] = 2;      //Prazo
            $posicoes[] = 7;      //Sequencia interna do registro

            $posicao_inicial = 0;
            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_003["idkit"] = $campo[1];
            $retorno_003["nomekit"] = $campo[2];
            $retorno_003["quantidadekit"] = $campo[3];


            return $retorno_002;
  }


  
  function  importador_tipo_099($linha){
            $posicoes[] = 2; 	  //Tipo de Regsitro
            $posicoes[] = 7;     //Código do Kit
            $posicoes[] = 7;     //Descrição do Kit
            $posicoes[] = 231;    //Filler
            $posicoes[] = 7;      //Sequencia interna do registro

            $posicao_inicial = 0;
            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_099["tiporegistro"] = $campo[0];
            $retorno_099["total001"]     = $campo[1];
            $retorno_099["total002"]     = $campo[2];


            return $retorno_099;
  }

  
  
  
  function  importador_tipo_004($linha){
            $posicoes[] = 2; 	  //Tipo de Regsitro
            $posicoes[] = 10;     //Código do Kit
            $posicoes[] = 80;     //Descrição do Kit
            $posicoes[] = 63;    //Filler
            $posicoes[] = 103;      //Sequencia interna do registro

            $posicao_inicial = 0;
            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_004["tiporegistro"] = $campo[0];
            $retorno_004["rec_01"]     = $campo[1];
            $retorno_004["rec_02"]     = $campo[2];


            return $retorno_004;
  }
  
 
 
  
?>
<HTML>
<HEAD>
</HEAD>
<BODY>
  <form enctype="multipart/form-data" action="<?=$PHP_SELF;?>" METHOD=POST>
   <input type=hidden name=ok value=1>
      REDECARD DIARIO
      <TABLE BORDER=0>
        <tr>
            <td><input type=file name="arquivo"></td>
            <td><input type=submit value="Enviar Dados"></td>
        </tr>
        
      </table>

      <?
      $qry = new consulta($con);

        if ($ok){
             $file = file($arquivo);

             $codcliente = 392; 
			 $codigo_produto = 26;
			 $total001 = 0;
			 $total002 = 0;

             for($i=0;$i<count($file);$i++){
			      

                  $tipo_linha = substr($file[$i],0,2);
				

                  switch($tipo_linha){
                          case("00"):
                             $retorno_000 = importador_tipo_000($file[$i]);
								             $numlotecliente = abs($retorno_000["lote"]);
                          break;

                       
                          case("01"):
                             $retorno_001 = importador_tipo_001($file[$i]);
								$p++;
								$total001++;
								$lkit='';
								
								$dtemissao = $retorno_001["dataemissao"];
                $dia = substr($dtemissao,6,2);
                $mes = substr($dtemissao,4,2);
                $ano = substr($dtemissao,0,4);
								$dtemissao =  $dia.'/'.$mes.'/'.$ano;
								//$dia = $dia+7;
								$datapromessa = $dia.'/'.$mes.'/'.$ano;
								
								
								$dtvencimento = $retorno_001["datavencimento"];
                                $dia_v = substr($dtvencimento,6,2);
                                $mes_v = substr($dtvencimento,4,2);
                                $ano_v = substr($dtvencimento,0,4);
								$dtvencimento = $dia_v.'/'.$mes_v.'/'.$ano_v;
								
								
								$obs1='';	 
								$obs2='';
								$obs='';
						  	
                $sql = "SELECT * FROM tb_rdc_cep_novo 
                        WHERE cep_de <= '".$retorno_001["cepentrega"]."'  and
                cep_ate >= '".$retorno_001["cepentrega"]."'";
                //echo $sql;
                //die;
                $qry->executa($sql);
                if($qry->nrw)
                   {
                   $via =  $qry->data["via"];
                   $obs =  $qry->data["via"];
                   }
                else   
                   {$via='CEP NÃO LOCALIZADO';
                     $obs='CEP NÃO LOCALIZADO';
                    }
                $codigoregiao = 2;
								
								
								$idtipoentrega=1;
								$codigo_produto = '26';
								if ($retorno_001["idtipoentrega"] == 'E')
									{
									$obs1 = 'ATENÇÃO ENTREGA EMERGENCIAL D+0';
								    $codigo_produto = '49';
                    $idtipoentrega=2;
									}
								if ($retorno_001["pcg"] == 'S')
									{
									$obs2 = 'DESPACHAR VIA CORREIOS';
									$codigo_produto = '50';
                  $idtipoentrega=9;
									}
								if ($retorno_001["numconta"] == 'RE')
									{
									$obs1 = 'AR DE RECLAMAÇÃO, ENTRAGAR PARA AUDITORIA';
									$obs2 = $retorno_004["rec_01"].$retorno_004["rec_02"];
									$codigo_produto = '50';
									}
							
							if ($retorno_001["numconta"] == 'PR')
									{
									$obs1 = 'PRIORIDADE, ESTABELECIMENTO S/ MATERIAL';
									$codigo_produto = '56';
									}
							
							
							
							    if ($codigoregiao == 9)
									{
									$obs2 = 'DESPACHAR VIA CORREIOS';
									$codigo_produto = '55';
									}
							
							
								
								
								
								$obs3 = '.'.$obs1.$obs2.$obs;
								
								
								
								$idExterno = "RE".abs($retorno_001["idexterno"]).$retorno_001["numconta"];
								$rdc = abs($retorno_001["idexterno"]);

                                
                                
								
								$sql = "SELECT * FROM tbentrega WHERE idexterno = '$idExterno'";
								$qry->executa($sql);
								
								if (!$qry->nrw){
                    $sql = "INSERT INTO tbentrega
                    (
                    idtransportadora,  
   								  idtipoentrega,
		  						  dataemissao,
                    datapromessa,
			  					  datavencimento,
									  quantidadevolumes,
									  codcliente,
                    pesoentrega, 
									  idexterno, 
									  numlotecliente,
									  numloteinterno,
                    codigoproduto,
									  valorentrega,
									  obsentrega,
                    idtipomovimento,
									  datacoletado, 
									  codbase,
									  numnotafiscal,
									  numerosedex,
									  pcg,
                    numconta,
                    numagencia,
                    codigodaregiao
                    )
                    VALUES
                    (
                    '1',
									  '$idtipoentrega',
									  '$dtemissao',
									  '$datapromessa',
									  '$dtvencimento',
									  '1',
									  '392',
									  '0',
									  '$idExterno',
									  '$numlotecliente',
									  '$numlotecliente',
                    '$codigo_produto',
									  '1',
									  '$obs3',
                    '300',
									  '".date("d/m/Y")."',
									  '1',
									  '".abs($retorno_001["numnotafiscal"])."',
									  '".abs($retorno_001["numerosedex"])."',
                    '100',
                    '$via',
                    '$rdc',
									  '$codigoregiao'
                    )";
											   
								}
								else{
								$sql = "DELETE FROM tbenderecoentrega WHERE idinterno = '".$qry->data["idinterno"]."'";
								$qry->executa($sql);
							    $sql = "UPDATE tbentrega SET
                          idtransportadora = 1,
												  idtipoentrega = 1 ,
												  dataemissao = '$dtemissao',
                          datapromessa = '$datapromessa',
												  quantidadevolumes = '1' ,
												  codcliente = '$codcliente',
                          pesoentrega = '1', 
												  numlotecliente = '$numlotecliente',
												  numloteinterno =  '$numlotecliente',
                          codigoproduto = '$codigo_produto', 
												  valorentrega = '1' 
												  WHERE idexterno = '$IdExterno'";
								}

								 $qry->executa($sql);
							
							   $sql = "SELECT idinterno FROM tbentrega WHERE idexterno = '$idExterno'";
                 $qry->executa($sql);

                 $idinterno = $qry->data["idinterno"];
                 $oob = $qry->data["obsentrega"];
                 $sql =  "INSERT INTO tbenderecoentrega
                         (
                         idinterno, 
								    		 nomeentrega,
									    	 enderecoentrega,
										     bairroentrega,
										     cidadeentrega,
										     cepentrega,
                         estadoentrega,
										     foneenderecoentrega,
										     obsentrega
                          )
                         VALUES
                         (
                         '$idinterno',
										     '".str_replace("'","",$retorno_001["nomeentrega"])."',
										     '".str_replace("'","",substr($retorno_001["enderecoentrega"],0,69))."',
                         '".str_replace("'","",$retorno_001["bairroentrega"])."',
										     '".str_replace("'","",$retorno_001["cidadeentrega"])."',
										     '".str_replace("'","",$retorno_001["cepentrega"])."',
                         '".str_replace("'","",$retorno_001["estadoentrega"])."',
										     '".str_replace("'","",$retorno_001["foneenderecoentrega"])."',
										     '$obs3'
                         )";
                         $qry->executa($sql);
                          break;
						  
						     case("02"):
							           $peso=0; 
                         $retorno_002= importador_tipo_002($file[$i]);
								         $p++;
								         $total002++;
								         
                         
								         
								         
                         $sql =  "INSERT INTO tb_rdc_kit
                                 (
                                 idinterno, 
            										 id_kit,
						            				 qt_kit
										             )
                                 VALUES
                                 (
                                 '$idinterno',
										             '".$retorno_002["idkit"]."',
										             '".$retorno_002["quantidadekit"]."'
                                 )";
                         				 $qry->executa($sql);
			  					               
                                 $cpl='';
                                 $sql = "SELECT complementoenderecoentrega FROM 
                                        tbenderecoentrega WHERE idinterno = '$idinterno'";
                     	           $qry->executa($sql);
									               $cpl=$qry->data["complementoenderecoentrega"];
									               
									               
                                 
                                 $sql = "SELECT peso_kit,crd FROM tb_rdc_kit_modelo WHERE id_kit = '".$retorno_002["idkit"]."'";
                     	           $qry->executa($sql);
									               
                                 $lkit=$lkit." K=". abs($qry->data["crd"]). " Q=". abs($retorno_002["quantidadekit"]);
                                 $ttt = abs($retorno_002["quantidadekit"]);
                                 
                                 
                                 $peso = $qry->data["peso_kit"];
										             if(!$peso)
                                    $peso=1;
                               
                               
                                 $sql = "update tbentrega set 
                                         pesoentrega = pesoentrega +  '$peso', numagencia = '$rdc' 
                                         where idinterno = '$idinterno'";
										             $qry->executa($sql);
										           
                               
                                $dt1 = $via.' - '.$lkit;
                                $dt2 = $cpl.' - '.$lkit;
                               
                               $dt1 = $dt1.'  >>   AFIRMO TER RECEBIDO '.$ttt. ' CAIXAS    CONTENDO 12 BOBINAS CADA';
                                 
                                 $sql = "update tbentrega set  
                                          obsentrega = '$dt1'
                                          where idinterno = '$idinterno'";
										                      $qry->executa($sql);
                                          
                                 $sql = "update tbenderecoentrega set  
                                          complementoenderecoentrega = '$dt2',obsentrega = '$dt1'
                                          where idinterno = '$idinterno'";
										                      $qry->executa($sql);         
                                          
										             //echo $sql;
										             
                                 
                                   
										             
										
					          break;
						  	
							
							
							      case("99"):
							                 $peso=0; 
                             	 $retorno_099= importador_tipo_099($file[$i]);
								               echo "<BR><BR><CENTER><FONT COLOR=#aa0000> Total de Encomendas Importadas  <B>$total001</B></font></CENTER>";
								               echo "<BR><CENTER><FONT COLOR=#0000FF>Total de Encomendas no Arquivo  <B>".abs($retorno_099["total001"])."</B> </font></CENTER>";
		 						               echo "<BR><BR><CENTER><FONT COLOR=#aa0000>Total de KIT'S Importadas  <B>$total002</B> </font></CENTER>";
								               echo "<BR><BR><CENTER><FONT COLOR=#0000FF>Total de KIT'S no Arquivo  <B>".abs($retorno_099["total002"])."</B> </font></CENTER>";
									                                	
									             echo "<BR><BR><CENTER><FONT COLOR=#0000FF>LOTE GERADO PARA IMPRESSÃO  <B>".$numlotecliente."</B> </font></CENTER>";
									                                	
					          break;
						  				
						  
                  }
             }

             echo "<BR><BR><CENTER><FONT COLOR=#aa0000><B></B> Favor verificar os totais os mesmos devem bater.</font></CENTER>";
        }
      ?>
  </form>
  <? $con->desconecta(); ?>
</BODY>
</html>
