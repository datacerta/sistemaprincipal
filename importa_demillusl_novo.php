<?php
/**
 * Importa Demillus
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega a configuracao
require_once("inc/config.inc");

function  importador_tipo_003($linha){
            $posicoes[] = 2;    // 0 = tipo de registro
            $posicoes[] = 56;  // 1 = lixo
            $posicoes[] = 8;   // 2 = Data Emissao
            $posicoes[] = 1297; // 2 = Lixo
            
            $posicao_inicial = 0;

            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_003["dataemissao"]   = $campo[2];
            
 


            return $retorno_003;
  }






  function  importador_tipo_001($linha){
            $posicoes[] = 2;    // 0 = tipo de registro
			$posicoes[] = 4;    // 1 = lixo
			$posicoes[] = 7;    // 2 = Nota Fiscal
			$posicoes[] = 19;   // 3 = Lixo
			$posicoes[] = 197;  // 4 = Lixo
			$posicoes[] = 12;   // 5 = cpf
			$posicoes[] = 51;   // 6 = destinatario
			$posicoes[] = 7;    // 7 = conta
			$posicoes[] = 24;   // 8 = lixo
			$posicoes[] = 41;   // 9 = Endereco
			$posicoes[] = 31;   // 10 = bairro
			$posicoes[] = 31;   // 11 = cidade
			$posicoes[] = 2;    // 12 = estado
			$posicoes[] = 1;    // 13 = lixo
            $posicoes[] = 8;    // 14 = cep
            $posicoes[] = 5;    // 15 = Volumews
            $posicoes[] = 6;    // 16 = peso
            $posicoes[] = 43;   // 17 = lixo
            $posicoes[] = 9;    // 18 = valor
            $posicoes[] = 152;  // 19 = obs
            $posicoes[] = 703;  // 20 = lixo
            $posicoes[] = 4;    // 21 = Setor
            $posicoes[] = 4;    // 22 = Rota
            
   
	    $posicao_inicial = 0;

      for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_001["idexterno"]                  = $campo[2];
			      $retorno_001["nomeentrega"]                = $campo[6];
			      $retorno_001["enderecoentrega"]            = $campo[9];
			      $retorno_001["bairroentrega"]              = $campo[10];
			      $retorno_001["cidadeentrega"]              = $campo[11];
			      $retorno_001["estadoentrega"]              = $campo[12];
			      $retorno_001["cepentrega"]                 = $campo[14];
		          $retorno_001["nota"]                       = $campo[2];
                  $retorno_001["volumes"]                    = $campo[15];
                  $retorno_001["valor"]                      = $campo[18];
                  $retorno_001["obs"]                        = $campo[19];
                  $retorno_001["idrevendedora"]              = $campo[7];
                  $retorno_001["cpf"]                        = $campo[5];
                  $retorno_001["peso"]                       = $campo[16];
                  $retorno_001["setor"]                      = $campo[21];
                  $retorno_001["rota"]                       = $campo[22];
		          return $retorno_001;
  }

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>

<div class="box" style="margin: 0 auto;">

<form enctype="multipart/form-data" action="<?=$PHP_SELF;?>" METHOD=POST>
   <input type=hidden name=ok value=1>
      SELECIONE O ARQUIVO
      <TABLE BORDER=0>
        <tr>
            <td><input type=file name="arquivo"></td>
            <td><input type=submit value="Enviar Dados"></td>
        </tr>
        
      </table>

      <?
      $qry        = new consulta($con);
	  $qry2        = new consulta($con);
      $qry_prod   = new consulta($con);
	  $qryLista   = new consulta($con);

        if ($ok){
             $file = file($arquivo);
				
			$retorno_001 = importador_tipo_001($file[0]);
			$cliente = 6670;
			//$cliente = 254;
			$produto = 16;
			$tpr     = 1;
			$barras        = 'N';
			$vSetor = "";
			$vBase  = "";
							
				
			$sql = "SELECT numloteimporta
			    	FROM tbconfigproduto WHERE 
					idtransportadora= '".$tpr."'
					AND
					codigoproduto= '".$produto."' 
					and codcliente = '".$cliente."'";
					$qry->executa($sql);
	    		    
					
					$lotenovo = $loteimporta+1;  
	    		    $prefixo       = $qry->data["prefixo"];
					$sufixo        = $qry->data["sufixo"];
					$id            =  $qry->data["codigodebarras"];
					$bbb           = $qry->data["codigodebarras"]; 
	    			$sql = "UPDATE tbconfigproduto SET numloteimporta='".$lotenovo."' 
					WHERE idtransportadora= '".$tpr."' AND 
					codigoproduto= '".$produto."' AND codcliente='".$cliente."'";
				//	$qry->executa($sql);
	 		        $loteimporta   = $qry->data["numloteimporta"];
             
                       		 
      for($i=0;$i<count($file);$i++){
         
         
         
          
          $tipo_linha = substr($file[$i],0,2);
          switch($tipo_linha){
                case("03"):
                    $retorno_003 = importador_tipo_003($file[$i]);
                    $dataemissao = trim($retorno_003["dataemissao"]);
                    $dia = substr($dataemissao,0,2);
                    $mes = substr($dataemissao,2,2);
                    $ano = substr($dataemissao,4);
                    $dtemissao = date("Y/m/d", mktime(0,0,0,$mes,$dia,$ano));
                    break;
               case("02"):
  				  				$p++;	
	                  $retorno_001 = importador_tipo_001($file[$i]);
	  						    $data = date("mdy");
                    $loteimporta = trim($retorno_001["setor"]).$dataemissao;
                    $sql = "SELECT prazocapital,prazointerior,prefixo,sufixo,codigodebarras
								    FROM tbconfigproduto WHERE
								    idtransportadora= '".$tpr."'
								    AND
								    codigoproduto= '".$produto."'
								    and codcliente = '".$cliente."'";
								    $qry->executa($sql);
                    $prazocapital  =  $qry->data["prazocapital"];
			              $prazointerior =  $qry->data["prazointerior"];
				     				$barras        =  $qry->data["codigodebarras"];
								    $bbb           =  $qry->data["codigodebarras"];
 
								    $sql = "SELECT * FROM tb_cep_rdc WHERE cepde <= '".$retorno_001["cepentrega"]."'  and
                            cepate >= '".$retorno_001["cepentrega"]."'";
                    $qry->executa($sql);
                    $codigoregiao   = (($qry->data["codigodaregiao"])?$qry->data["codigodaregiao"]:"0");
                    $idExterno      = trim($retorno_001["idexterno"]);
					$idrevendedora  = trim($retorno_001["idrevendedora"]);
					$nota           = trim($retorno_001["nota"]);
                    $volumes        = abs($retorno_001["volumes"]);
                    $valor          = $retorno_001["valor"];
					$obs            = $retorno_001["obs"].'TREZER CANHOTO DA NFF ASSINADO';
                    $peso           = $retorno_001["peso"];
                    $setor          = $retorno_001["setor"];
					$endereco       = remove_acentos($retorno_001["enderecoentrega"]);
					$bairro         = remove_acentos($retorno_001["bairroentrega"]);
					$rota           = $retorno_001["rota"];
					$complemento    = remove_acentos($retorno_001["complementoenderecoentrega"]);
          		    $vsetor=abs($setor);
					
							      
                    $sql = "Select * from tb_preco_demillus where setor = '$vsetor'";
                    $qry_prod->executa($sql);
                    
                   
                    $idExterno = '67100'.$idExterno;
				    //$idExterno = '25477'.$idExterno;
                    
                    $sql = "SELECT * FROM tbentrega WHERE idexterno = '$idExterno'";
								    $qry->executa($sql);
                    if ($qry_prod->data["codigoproduto"]){
                   
                    //echo "<br>";
                    $produto = $qry_prod->data["codigoproduto"];
                    //$qry_prod(close);
                            }

							      if($retorno_001["total"] != '' && $retorno_001["total"] > 0 && isset($retorno_001["total"]))
								         {
								$total = $retorno_001["total"];
                         }

								
								
                
							
								   
                 // echo $idExterno;
                 //  die;

								    if (!$qry->nrw){
									
									$cbaseT = "";
										
										if($setor == '0849'){
											if($rota== '001'){
												$cbaseT = "344";
											}
											if($rota== '002'){
												$cbaseT = "190";
											}
											if($rota== '003' or $rota == '004' or $rota == '005'){
												$cbaseT = "191";
											}
										}
										
										if($setor == '0850' or $setor == '0874'){
											$cbaseT = "185";
										}
													
										if($setor == '0889'){
											if($rota== '001' or $rota== '002' or $rota== '003'){
												$cbaseT = "344";
											}
											if($rota== '004' or $rota == '005'){
												$cbaseT = "1";
											}
										}
										
										if($setor == '0873'){
											if($rota== '001'){
												$cbaseT = "191";
											}
											
											if($rota== '002' or $rota== '003'){
												$cbaseT = "190";
											}
											if($rota== '004'){
												$cbaseT = "191";
											}
											
											if($rota== '005'){
												$cbaseT = "190";
											}
										}
										
										if($setor == '0856'){
											if($rota== '001' or $rota == '002'){
												$cbaseT = "190";
											}
											if($rota== '003' or $rota== '005'){
												$cbaseT = "191";
											}
											if($rota== '004'){
												$cbaseT = "344";
											}
										}
										
										if($setor == '0654'){
											if($rota== '001'){
												$cbaseT = "382";
											}
											if($rota== '002' or $rota== '004'){
												$cbaseT = "652";
											}
											if($rota== '003' or $rota== '005'){
												$cbaseT = "344";
											}
										}
										
										if($setor == '0863'){
											if($rota== '001'){
												$cbaseT = "179";
											}
											if($rota== '002' or $rota== '003' or $rota== '004' or $rota== '005'){
												$cbaseT = "190";
											}
										}
										
										if($setor == '0655'){
											if($rota== '001' or $rota == '005'){
												$cbaseT = "131";
											}
											if($rota== '002'){
												$cbaseT = "52";
											}
											if($rota== '003' or $rota== '004'){
												$cbaseT = "191";
											}
										}
										
										if($setor == '0860'){
											if($rota== '001' or $rota == '002'){
												$cbaseT = "191";
											}
											if($rota== '003' or $rota== '004'){
												$cbaseT = "622";
											}
											if($rota == '005'){
												$cbaseT = "1";
											}
										}

										
										if($setor == '0861'){
											if($rota== '001' or $rota == '002' or $rota== '003' or $rota== '004'){
												$cbaseT = "191";
											}
											if($rota== '005'){
												$cbaseT = "190";
											}
										}
										
										if($setor == '0868'){
											if($rota== '001' or $rota == '002' or $rota== '003'){
												$cbaseT = "193";
											}
											if($rota== '004' or $rota== '005'){
												$cbaseT = "344";
											}
										}
										
									//	echo $setor." - ".$rota."<br>";
										if(empty($vSetor) and empty($vbase))
										{
											$SqlCriarLista = "INSERT INTO tblista (codigotipolista, datalista, codbase, codbaseorigem, dataexpedicao, horaexpedicao, codcliente, codigoproduto, codloginmontagem, codloginexpede) VALUES 
														  ('2', '".date('Y-m-d')."', '".$cbaseT."', '1', '".date('Y-m-d')."', '".date('H:i:s')."', '$cliente', '$produto','".$_SESSION['IDUSER']."','".$_SESSION['IDUSER']."')";
											$qry->executa($SqlCriarLista);
											
											$SqlPegaLista = "SELECT numlista FROM tblista where codcliente = '$cliente' and codigoproduto = '$produto' order by numlista desc limit 1";
											$qryLista->executa($SqlPegaLista);
											
											$nLista = $qryLista->data['numlista'];
											echo "Número da lista: ".$nLista." para setor: ".$setor." - Rota: ".$rota."<br>";
											
											$vSetor = $setor;
											$vbase = $cbaseT;
											
                                        } else if ($vSetor != $setor or $vbase != $cbaseT) { 
											$SqlCriarLista = "INSERT INTO tblista (codigotipolista, datalista, codbase, codbaseorigem, dataexpedicao, horaexpedicao, codcliente, codigoproduto, codloginmontagem, codloginexpede) VALUES 
														  ('2', '".date('Y-m-d')."', '".$cbaseT."', '1', '".date('Y-m-d')."', '".date('H:i:s')."', '$cliente', '$produto','".$_SESSION['IDUSER']."','".$_SESSION['IDUSER']."')";
											$qry->executa($SqlCriarLista);
											
											$SqlPegaLista = "SELECT numlista FROM tblista where codcliente = '$cliente' and codigoproduto = '$produto' order by numlista desc limit 1";
											$qryLista->executa($SqlPegaLista); 
											
											$nLista = $qryLista->data['numlista'];
											echo "Número da lista: ".$nLista." para setor: ".$setor." - Rota: ".$rota."<br>";
											
											$vbase = $cbaseT;
											$vSetor = $setor;
											
										}
							

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
												  codbase,
												  numconta,
												  numnotafiscal,
												  primeiroenvelope,
												  ultimoenvelope,
												  codigodaregiao,
												  numlista
													)
												  VALUES
                                                 (
												 '$tpr',
												  '1',
												  '$dtemissao',
												  '".date("Y/m/d")."',
												  '".date("Y/m/d")."',
												  '$volumes',
												  '$cliente',
												  '".str_replace(",",".",$peso/100)."',
												  '$idExterno',
												  '$setor',
												  '$loteimporta',
												  '$produto',
												  '".str_replace(",",".",$valor/100)."',
												  '$obs',
												  '109',
												  '$cbaseT',
												  '$idrevendedora',
												  '$nota',
												  '$setor',
												  '$rota',
												  '$codigoregiao',
												  '$nLista'
												  )";
											$qry->executa($sql); 
											 // echo $sql;
											 //echo "<br>";
											   
				                   $sql = "SELECT idinterno FROM tbentrega WHERE idexterno = '$idExterno'";
                           $qry->executa($sql);
                           $idinterno = $qry->data["idinterno"];
                           $sql =  "INSERT INTO tbenderecoentrega
                                   (
                                   idinterno, 
								   nomeentrega,
								   enderecoentrega,
								   complementoenderecoentrega,
								   bairroentrega,
								   cidadeentrega,
								   cepentrega,
                                   estadoentrega,
								   foneenderecoentrega,
								   responsavelentrega,
								   obsentrega
                                   )
                                   VALUES
                                   (
                                   '$idinterno',
								   '".trim($retorno_001["nomeentrega"])."',
								   '".substr(trim($endereco),0,69)."',
								   '".$complemento."',
                                   '".$bairro."',
								   '".$retorno_001["cidadeentrega"]."',
								   '".$retorno_001["cepentrega"]."',
                                   '".$retorno_001["estadoentrega"]."',
								   '".$retorno_001["telefoneentrega"]."',
								   '".$retorno_001["responsavelentrega"]."',
								   '$obs'
                                   )";
                                $qry->executa($sql);
								//INSERIR MOVIMENTO FISICO COLETADO
										$insertMovimento = "INSERT INTO tbmovimento (idinterno, codlogin, idtipomovimento, codbase, dataoperacao, horaoperacao, idtransportadora, numlista) VALUES
															($idinterno, '".$_SESSION['IDUSER']."', '109', '".$cbaseT."','".date('Y-m-d')."','".date('H:i:s')."', 1, '".$nLista."')";
										$qry2->executa($insertMovimento); 
										
                                    break;
                                        
                                        
                                   //POPULA DE MILLUS
                                        
                                   $sql = "SELECT * from tb_demillus_revend  
                                           WHERE  id_revend = '$idrevendedora'";
                                           $qry->executa($sql);
                                                 
                                         if (!$qry->nrw){
                                         
                                          $sql =  "INSERT INTO tb_demillus_revend
                                                   (
                                                   id_revend, 
                										               nome_revend,
                										               id_setor,
                                                   cep,
                                                   uf,
                                                   endereco,
                                                   bairro,
                                                   cidade
                										               )
                                                   VALUES
                                                   (
                                                   '$idrevendedora',
                										               '".substr(trim($retorno_001["nomeentrega"]),0,50)."',
                										               '$setor',
                                                   '".$retorno_001["cepentrega"]."',
                                                   '".$retorno_001["estadoentrega"]."',
                                                   '".substr(trim($retorno_001["enderecoentrega"]),0,69)."',
                                                   '".$retorno_001["bairroentrega"]."',
										                               '".$retorno_001["cidadeentrega"]."'
                										               )";
                                                $qry2->executa($sql);   
                                                   }  
                                        
                                        
                                          
										 			
										
										
                                        
										               }
										             else
										             {
                                 //Atualiza
                                $sql = "update tbentrega set codigoproduto = '$produto' WHERE idexterno = '$idExterno'";
                                $qry->executa($sql);
                                 //echo $sql;
                                 
                                 
                                 
                                 }
										             
										             
										             
                          }
										 
							  
							   
							   //
             }

             echo "<BR><BR><CENTER><FONT COLOR=#aa0000><B></B> Arquivo Importado com sucesso total : " .$p. "<BR>Lote gerado: ".$loteimporta."<BR></font></CENTER>";

        }
      ?>
  </form>

</div>

<?php
// pega o Footer
require_once("inc/footer.inc");