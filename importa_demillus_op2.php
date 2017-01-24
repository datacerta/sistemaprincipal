<?
session_start();

//inclui biblioteca de controles
require_once("classes/diversos.inc.php");
//testa sessão
if (VerSessao()==false){
        header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}
end;




  function  importador_tipo_001($linha){
            $posicoes[] = 2;    // 0 = tipo de registro
            $posicoes[] = 50;    // 0 = tipo de registro
			$posicoes[] = 10;    // 1 = lixo
			$posicoes[] = 10;    // 2 = Nota Fiscal
			
   
	        $posicao_inicial = 0;

            for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }
            $retorno_001["tipo"]                       = $campo[0];
            $retorno_001["idexterno"]                  = $campo[2];
			$retorno_001["nomeentrega"]                = $campo[1];
			$retorno_001["setor"]                      = $campo[3];
			
 


            return $retorno_001;
  }

  

  

 
  
  
 
 
  
?>
<HTML>
<HEAD>
</HEAD>
<BODY>
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
      $qry = new consulta($con);

        if ($ok){
             $file = file($arquivo);
				
			$retorno_001 = importador_tipo_001($file[0]);
			$cliente = 6670;
			$produto = 16;
			$tpr     = 1;
							
				
			$sql = "SELECT numloteimporta
								FROM tbconfigproduto WHERE 
								idtransportadora= '".$tpr."'
								AND
								codigoproduto= '".$produto."' 
								and codcliente = '".$cliente."'";
								
								
								$qry->executa($sql);
							    
								
								
								
							     
								 $lotenovo = $loteimporta+1;  
							
								 $sql = "UPDATE tbconfigproduto SET numloteimporta='".$lotenovo."' 
						              WHERE idtransportadora= '".$tpr."' AND 
							          codigoproduto= '".$produto."' AND codcliente='".$cliente."'";
									  $qry->executa($sql);
									  
									 


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

                                
                                $dtemissao = date("d/m/Y", mktime(0,0,0,$mes,$dia,$ano));
                                
                                
                                
                          break;

                          case("02"):
                 
			      
				  				$p++;	
				  
                                $retorno_001 = importador_tipo_001($file[$i]);
								
								

 							    $data = date("dmy");




                                $loteimporta = trim($retorno_001["setor"]).$data;
                               

                                $sql = "SELECT prazocapital,prazointerior,prefixo,sufixo,codigodebarras
								FROM tbconfigproduto WHERE
								idtransportadora= '".$tpr."'
								AND
								codigoproduto= '".$produto."'
								and codcliente = '".$cliente."'";
								$qry->executa($sql);

			                    $prazocapital  = $qry->data["prazocapital"];
			                    $prazointerior = $qry->data["prazointerior"];
								$barras       =  $qry->data["codigodebarras"];
								$bbb  = $qry->data["codigodebarras"];


								$sql = "SELECT * FROM tb_cep_rdc WHERE cepde <= '".$retorno_001["cepentrega"]."'  and
                                        cepate >= '".$retorno_001["cepentrega"]."'";
                                $qry->executa($sql);

                                $codigoregiao  = (($qry->data["codigodaregiao"])?$qry->data["codigodaregiao"]:"0");




							     $idExterno      = 'DX'.trim($retorno_001["idexterno"]);
								 $idrevendedora  = trim($retorno_001["nota"]);
								 $nota           = 1;
                                 $volumes        = 1;
                                 $valor          = 1;
								 $obs            = 'TREZER CANHOTO DA NFF ASSINADO';
                                 $peso           = 1;
                                 $setor          = $retorno_001["setor"];
                               






								$sql = "SELECT * FROM tbentrega WHERE idexterno = '$idExterno'";
								$qry->executa($sql);

							

                                if($retorno_001["total"] != '' && $retorno_001["total"] > 0 && isset($retorno_001["total"]))
								{
                                   $total = $retorno_001["total"];
                                }

								

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
												
												  codbase,
												  numconta,
												  numnotafiscal,
                                                  primeiroenvelope,

												  codigodaregiao
                                               )
                                       VALUES
                                               (
                                                  '$tpr',
												  '1',
												  '$data',
												  '".date("d/m/Y")."',
												  '".date("d/m/Y")."',
                                				  '$volumes',
												  '$cliente',
												  '".str_replace(",",".",$peso/100)."',
												  '$idExterno',
												  '$setor',
												  '$loteimporta',
                                                  '$produto',
												  '".str_replace(",",".",$valor/100)."',
												  '$obs',
                                                  '300',
												  

												  '1',

												  '$idrevendedora',
												  '$nota',
                                                  '$setor',
												  '$codigoregiao'
                                               )";
											   
											//  echo $sql;
											// echo "<br>";
											   
											  $qry->executa($sql);
											   
								
								

								 
							
								

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
										 'na',
										 'na',
										 'na',
                                         'na',
										 'na',
										 'na',
                                         'na',
										 'na',
										 'na',
										 '$obs'
                                         )";
                                        $qry->executa($sql);
                                        break;
                                        
										 }
										 
                          }
										 
							  
							   
							   //
             }

             echo "<BR><BR><CENTER><FONT COLOR=#aa0000><B></B> Arquivo Importado com sucesso total : " .$p. "<BR>Lote gerado: ".$loteimporta."<BR></font></CENTER>";

        }
      ?>
  </form>
  <? $con->desconecta(); ?>
</BODY>
</html>