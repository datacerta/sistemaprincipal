<?
session_start();
//phpinfo();
//CLIENTE REDECARD = 392
//PRODUTO = 47

//inclui biblioteca de controles
require_once("inc/config.inc");


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
            $posicoes[] = 40;   // 1 -  Razão social 
            $posicoes[] = 14;   // 2 -  Cnpj
            $posicoes[] = 15;   // 3 -  IE
            $posicoes[] = 40;   // 4 -  Endereço
            $posicoes[] = 20;   // 5 -  Bairro
            $posicoes[] = 35;   // 6 -  Cidade
            $posicoes[] = 9;    // 7 -  CEP
            $posicoes[] = 9;    // 8 -  Código do Município
            $posicoes[] = 9;    // 9 -  Estado
            $posicoes[] = 4;    // 10 -  Área de coleta ex. Portão ou doca
            $posicoes[] = 35;   // 11 -  Telefones
            $posicoes[] = 1;   //  12 -  Tipo identificação 1=PJ 2=PF
            $posicoes[] = 6;   //  13 -  Filler
           
            $posicao_inicial = 0;
            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_001["nomeentrega"] = $campo[1];
            $retorno_001["cpf"] = $campo[2];
            $retorno_001["enderecoentrega"] = $campo[4];
            $retorno_001["bairroentrega"] = $campo[5];
            $retorno_001["cidadeentrega"] = $campo[6];
            $retorno_001["estadoentrega"] = $campo[9];
			$retorno_001["cepentrega"] = $campo[7];
            $retorno_001["codmun"] = $campo[8];
            $retorno_001["foneenderecoentrega"] = $campo[11];
            
		    return $retorno_001;
  }

  function  importador_tipo_313($linha){
            //DADOS DA NOTA FISCAL
            $posicoes[] = 3; 	  //0- Tipo de Regsitro
            $posicoes[] = 15; 	//1 - Numero do Romaneio (usar como idexterno)
            $posicoes[] = 7; 	  //2 - Código da Rota
            $posicoes[] = 1; 	  //3 - Meio de Transporte - NA
            $posicoes[] = 1; 	  //4 - Tipo do Transporte - Na
            $posicoes[] = 1; 	  //5 - Tipo da Carga
            $posicoes[] = 1; 	  //6 - Condição do Frete - C=CIF   F=FOB
            $posicoes[] = 3; 	  //7 - Séria da Nota Fiscal
            $posicoes[] = 8; 	  //8 - Número da Nota Fiscal
            $posicoes[] = 8; 	  //9 - Data da Emissão
            $posicoes[] = 15; 	//10 - Natureza da Mercadoria
            $posicoes[] = 15; 	//11 - Especie de acondicionamento
            $posicoes[] = 7; 	  //12 - Quantidade volumes
            $posicoes[] = 15;   //13 - Valor da Nota
            $posicoes[] = 7; 	  //14 - Peso Total
            $posicoes[] = 5; 	  //15 - Peso Cubado
            $posicoes[] = 1;      //16 - ICMS
            $posicoes[] = 1;      //17 - SEGURO
            $posicoes[] = 15;      //18 - VALOR SEGURO
            $posicoes[] = 15;      //19 - VALOR COBRADO
            $posicoes[] = 7;      //20 - N PLACA
            $posicoes[] = 1;      //21 - PLANO DE CARGA
            $posicoes[] = 15;      //22 - VALOR FRETE PESO-VOLUME
            $posicoes[] = 15;      //23 - VALOR AD VALOREM
            $posicoes[] = 15;      //24 - VALOR TOTAL TAX
            $posicoes[] = 15;      //25 - VALOR TOTAL FRETE
            $posicoes[] = 1;      //26 - ACAO DOC
            $posicoes[] = 12;      //27 - VALOR ICMS
            $posicoes[] = 12;      //28 - VALOR ICMS RET
            $posicoes[] = 1;      //29 - IND BON
            $posicoes[] = 2;      //30 - FILLER
            $posicoes[] = 44;      //31 - chave


           $posicao_inicial = 0;
            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_002["id"] = $campo[1];
            $retorno_002["idexterno"] = $campo[8];
            $retorno_002["valor"] = $campo[13];
            $retorno_002["peso"] = $campo[14];
            $retorno_002["qtd"] = $campo[12]/100;
            $retorno_002["chave"] = $campo[31];

            return $retorno_002;
  }


  function  importador_tipo_318($linha){
            $posicoes[] = 3; 	  //0 -Tipo de Regsitro
            $posicoes[] = 15;     //1 - Valor Total notas
            $posicoes[] = 15;     //2 - Peso Total
            $posicoes[] = 15;    //3 - Peso total cubagem
            $posicoes[] = 15;     //4 - Qtd volumes
			$posicoes[] = 15;     //5 - Valor total cobrado
            $posicoes[] = 15;     //6 - Valor Seguro
            $posicoes[] = 147;     //7 - filler

            $posicao_inicial = 0;
            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_003["valortotal"] = $campo[1];
            $retorno_003["volumes"] = $campo[4];

            return $retorno_003;
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
      LOGHAUS
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

             $codcliente = 6848; 
			 $codigo_produto = 16;
			 $total001 = 0;
			 $total002 = 0;
             $total003 = 0;

             for($i=0;$i<count($file);$i++){
			      
                
                  $tipo_linha = substr($file[$i],0,3);


                  switch($tipo_linha){
                          case("000"):
                             $retorno_000 = importador_tipo_000($file[$i]);
							 $numlotecliente = abs($retorno_000["numlotecliente"]);
                          break;

                          case("310"):
                             $retorno_310 = importador_tipo_310($file[$i]);
                             $numloteinterno = abs($retorno_310["numloteinterno"]);
                          break;

                          case("311"):
                             $retorno_311 = importador_tipo_311($file[$i]);
                            $dtemissao = $retorno_311["dataemissao"];
                            $dia = substr($dtemissao,0,2);
                            $mes = substr($dtemissao,2,2);
                            $ano = substr($dtemissao,4,4);
                            $dtemissao =  $ano.'-'.$mes.'-'.$dia;
                             $dtpromessa = date('Y-m-d', strtotime( "$dtemissao +5 days" ) );
                             $loteinter = $ano.$mes.$dia;
                          break;

                          case("312"):
                             $retorno_312 = importador_tipo_312($file[$i]);

                            break;
						  
                          case("313"):
                                $retorno_313 = importador_tipo_313($file[$i]);
                                $idExterno = trim($retorno_313["id"]);
                                $numnota = $retorno_313["idexterno"];
                                $valor = $retorno_313['valor']/100;
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
                                  primeiroenvelope,
                                    numconta,
                                    numagencia,
                                    codigodaregiao,
                                    chave_nfe
                                    )
                                    VALUES
                                    (
                                    '1',
                                  '1',
                                  '$dtemissao',
                                  '$dtpromessa',
                                  '$dtpromessa',
                                  '".$retorno_313['qtd']."',
                                  '$codcliente',
                                  '".$retorno_313['peso']."',
                                  '$idExterno',
                                  '',
                                  '$loteinter',
                                    '$codigo_produto',
                                      '$valor',
                                      '',
                                    '300',
                                  '".date("Y-m-d")."',
                                  '1',
                                  '$numnota',
                                  '',
                                    '100',
                                    '0',
                                    '',
                                    '',
                                    '".$retorno_312['codmun']."',
                                    '".$retorno_313['chave']."'
                                    )";
                                                               
                                }else{
                                    $sql = "DELETE FROM tbenderecoentrega WHERE idinterno = '".$qry->data["idinterno"]."'";
                                    $qry->executa($sql);
                                    $sql = "UPDATE tbentrega SET
                                              idtransportadora = 1,
                                              idtipoentrega = 1 ,
                                              dataemissao = '$dtemissao',
                                              datapromessa = '$dtpromessa',
                                              quantidadevolumes = '".$retorno_313['qtd']."',
                                              codcliente = '$codcliente',
                                              pesoentrega = '".$retorno_313['peso']."',
                                              codigoproduto = '$codigo_produto',
                                              cpf = '".$retorno_312["cpf"]."',
                                                      valorentrega = '$valor'
                                                      WHERE idexterno = '$idExterno'";
                                }

                                $qry->executa($sql);
                                $sql = "SELECT idinterno FROM tbentrega WHERE idexterno = '$idExterno'";
                                $qry->executa($sql);

                                 $idinterno = $qry->data["idinterno"];
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
                                 '".str_replace("'","",utf8_decode($retorno_312["nomeentrega"]))."',
                                 '".str_replace("'","",substr(utf8_decode($retorno_312["enderecoentrega"]),0,70))."',
                                 '".str_replace("'","",utf8_decode($retorno_312["bairroentrega"]))."',
                                 '".str_replace("'","",utf8_decode($retorno_312["cidadeentrega"]))."',
                                 '".str_replace("'","",utf8_decode($retorno_312["cepentrega"]))."',
                                 '".str_replace("'","",utf8_decode($retorno_312["estadoentrega"]))."',
                                 '".str_replace("'","",utf8_decode($retorno_312["foneenderecoentrega"]))."',
                                 ''
                                 )";

                                 $qry->executa($sql);
                                 $total001++;
                                  $total002 += $retorno_313['valor'];
                                  $total003 += $retorno_313['qtd'];

                          break;
						  
					       case("318"):
                     	           $retorno_318= importador_tipo_318($file[$i]);
						               echo "<BR><BR><CENTER><FONT COLOR=#aa0000> Total de Encomendas Importadas  <B>$total001</B></font></CENTER>";
 						               echo "<BR><BR><CENTER><FONT COLOR=#aa0000>Quantidade de Volumes ".(int)$retorno_318["volumes"]." de  $total003 importado </font></CENTER>";
							                                	
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
