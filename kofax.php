<?

// header('Content-type: text/plain; charset=iso-8859-1\r\n');

session_start();
set_time_limit(0);
ini_set("memory_limit","99M");
ini_set("max_execution_time","99999");

//inclui biblioteca de controles
require_once("classes/diversos.inc.php");   
//testa sessão
if (VerSessao()==false){
    //    header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
        
        
        
      
}
end;


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
      $qry2 = new consulta($con);
      
    
    
if ($ok){
      
       $entrega =date("Y-m-d");
       
      
      $destino="arquivo.csv";
       copy($arquivo,"arquivos/".$destino);
       $vArquivo = "arquivos/arquivo.csv";
       $vDados = fopen($vArquivo, "r");
       $vConteudo = fread($vDados, filesize($vArquivo));
       fclose($vDados);
       $vLinhas = explode("\n", $vConteudo);    
       $atualizados=0;
 
 
       

       
        for ($i = 0; $i < count($vLinhas); $i++)
            {
                $vValores = explode(",", $vLinhas[$i]);
                
                
                
                $seq            = $vValores[0];
                $campo          = $vValores[1];
                $campo          = $vValores[2];
				$campo          = $vValores[3];
				$campo          = $vValores[4];
				$campo          = $vValores[5];
				$campo          = $vValores[6];
				$campo          = $vValores[7];
				$campo           = $vValores[8];
				$idExterno      = $vValores[9];
				$lote           = $vValores[10];
				$campo          = $vValores[11];
				$campo          = $vValores[12];
				$campo          = $vValores[13];
				$campo          = $vValores[14];
				$campo          = $vValores[15];
				$campo          = $vValores[16];
				$campo          = $vValores[17];
				$campo          = $vValores[18];
				$campo          = $vValores[19];
				$campo          = $vValores[20];
				$campo          = $vValores[21];
				
                if($idExterno){
                 
                
                $idExterno = str_replace('"','',$idExterno);
				$idExterno = substr($idExterno,0,12);
				$lote = ereg_replace('[\,"",Z:]', '', $lote);
				
				
                $idExterno = trim($idExterno);
				
			   
              
                
                $sql = "Select * from tbentrega 
                        where idexterno = '".$idExterno."'";
                $qry->executa($sql);
               // echo $sql;
			//	echo "<br>";
                
                if ($qry->nrw ) {
               
                        
                      $dataentrega = $qry->data["dataentrega"]; 
                      $mov='222';
                      $st='E';
                      $dataentrega = $qry->data["dataentrega"];
                    
                  
                    
                  
                  
                  
                 
                 //se depois de tudo isso a data for vazia assume a data do formulario
                 if (!$dataentrega)
                      $dataentrega = $entrega;
                    
                       if($idExterno)
                       {
                    
                         $sql2 = "update tbentrega set 
                                 
                                 dataentrega = '$dataentrega', 
                                 st = 'E', 
                                 idtipomovimento = $mov,  
                                 
                                 numlotedigital = '$lote'  
                                 where idexterno = '".$idExterno."'";
                                 $qry2->executa($sql2);
								 $atualizados++;
                       
                            
                       //insere movimento
                       //inseremovimento($id,$idtipomovimento_atual,'222',0,$_SESSION["IDBASE"],1, 0);
                       
                       
                        
                        }
                        
                        
                        
                         
                         
                     
                                            
                   }    
				   else
				   {
					echo "REGISTRO : ".$idExterno."  NÃO LOCALIZADO...";
					ECHO "<br>";		
						
					}
				   
               }
			   
			}
  
             Echo "Foram alteradas ".$atualizados. "  Encomendas, confira com o total de imagens, no caso de diferença carregar novamente.";
                        
                        
             





}

 
				
			


      ?>
  </form>
  <? $con->desconecta(); ?>
</BODY>
</html>
