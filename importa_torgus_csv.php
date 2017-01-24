<?
session_start();


//inclui biblioteca de controles
require_once("classes/diversos.inc.php");



?>
<HTML>
<HEAD>
</HEAD>
<BODY>
  <form enctype="multipart/form-data" action="<?=$PHP_SELF;?>" METHOD=POST>
   <input type=hidden name=ok value=1>
     
      <TABLE BORDER=0>
        <tr>

        </tr>
        
          SELECIONE O ARQUIVO : 
        
        <tr>
            <td><input type=file name="arquivo"></td>
            <td><input type=submit value="Enviar Dados"></td>
        </tr>
        
      </table>

      <?
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
      
      $qry = new consulta($con);
	  $qry6 = new consulta($con);
      $qry_rota = new consulta($con);
      
         
			$codcliente = 6855;	
            $codigo_produto = 16; 
            //$produto = 756;   

        if ($ok){
      
             $destino="arquivo.csv";
             copy($arquivo,"arquivos/".$destino);
             $vArquivo = "arquivos/arquivo.csv";
             $vDados = fopen($vArquivo, "r");
             $vConteudo = fread($vDados, filesize($vArquivo));
             fclose($vDados);
             $vLinhas = explode("\n", $vConteudo);



           		 
            for ($i = 0; $i < count($vLinhas); $i++)
            {
                if($i == 0)
                  continue;

                $vValores = explode(";", $vLinhas[$i]);
                
                
                $ctrc  = $vValores[0];
                $dataemissao  = trim($vValores[1]); 
		            $manifesto      = trim($vValores[2]); 
                $motorista       = trim($vValores[3]);
                $cidade           = trim($vValores[4]);
                $valor   = str_replace(',', '.', trim($vValores[5])) ;
                $numnota        = trim($vValores[6]);
                $nome        = trim($vValores[7]);
                $endereco        = trim($vValores[8]);
                $cep = trim($vValores[9]);
                $idExterno = $numnota;

                $dataemissao = explode("/", $dataemissao);
                $dtemissao =  $dataemissao[2].'-'.$dataemissao[1].'-'.$dataemissao[0];
                 $dtpromessa = date('Y-m-d', strtotime( "$dtemissao +5 days" ) );
                 $loteinter = $dataemissao[2].$dataemissao[1].$dataemissao[0];


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
                          '1',
                          '$codcliente',
                          '1',
                          '$numnota',
                          '',
                          '$loteinter',
                            '$codigo_produto',
                              '$valor',
                              '',
                            '300',
                          '".date("Y-m-d")."',
                          '1',
                          '$idExterno',
                          '',
                            '100',
                            '0',
                            '',
                            '',
                            '999',
                            ''
                            )";
                                                       
                        }else{
                            $sql = "DELETE FROM tbenderecoentrega WHERE idinterno = '".$qry->data["idinterno"]."'";
                            $qry->executa($sql);
                            $sql = "UPDATE tbentrega SET
                                      idtransportadora = 1,
                                      idtipoentrega = 1 ,
                                      dataemissao = '$dtemissao',
                                      datapromessa = '$dtpromessa',
                                      quantidadevolumes = '1',
                                      codcliente = '$codcliente',
                                      pesoentrega = '1',
                                      codigoproduto = '$codigo_produto',
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
                         '".str_replace("'","",utf8_decode($nome))."',
                         '".str_replace("'","",substr(utf8_decode($endereco),0,70))."',
                         '',
                         '".str_replace("'","",utf8_decode($cidade))."',
                         '$cep',
                         '',
                         '',
                         ''
                         )";
                         $qry->executa($sql);
			   }	 
                   
                   

             echo "<BR><BR><CENTER><FONT COLOR=#aa0000><B></B> Arquivo Importado com sucesso total : " .$i. " </font></CENTER>";
        }
      ?>
  </form>
  <? $con->desconecta(); ?>
</BODY>
</html>
