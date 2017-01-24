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
        
      <?  
      echo "VERIFIQUE SE O ARQUIVO ESTA NA SEGUINTE ORDEM";
      Echo "<br>";
      echo "Destinatario";
      echo "<br>";
			echo "VIP";
      echo "<br>";
			echo "kit 1";
      echo "<br>";
			echo "Kit 2";
      echo "<br>";
			echo "kit 3";
      echo "<br>";
			echo "kit 4";
      echo "<br>";
      echo "endereco";
      echo "<br>";
      echo "numero";
      echo "<br>";
      echo "cep";
      echo "<br>";
      echo "Complemento"; 
      echo "<br>";
      echo "cidade";
      echo "<br>";
      echo "bairro";
      echo "<br>";
      echo "estado";	
      echo "<br>";		
      echo "telefone";
      echo "<br>";
      echo "SALVE EM CSV";          
      ?>   
        
        </tr>
        
          SELECIONE O ARQUIVO : 
        
        <tr>
            <td><input type=file name="arquivo"></td>
            <td><input type=submit value="Enviar Dados"></td>
        </tr>
        
      </table>

      <?
      $qry = new consulta($con);
	  $qry6 = new consulta($con);
      $qry_rota = new consulta($con);
      
         
						$cliente = 322;	
            $produto = 57; 
            //$produto = 756;   

        if ($ok){
      
             $destino="arquivo.csv";
             copy($arquivo,"arquivos/".$destino);
             $vArquivo = "arquivos/arquivo.csv";
             $vDados = fopen($vArquivo, "r");
             $vConteudo = fread($vDados, filesize($vArquivo));
             fclose($vDados);
             $vLinhas = explode("\n", $vConteudo);


	         $sql = "SELECT prazocapital,prazointerior,numloteimporta,prefixo,
             sufixo,codigodebarras FROM tbconfigproduto 
             WHERE idtransportadora=1  
             AND  codcliente=322  and codigoproduto=$produto";
			 $qry->executa($sql);				                                																					                             
			 $loteimporta   = $qry->data["numloteimporta"];
			 $prazocapital  = $qry->data["prazocapital"];
			 $prazointerior = $qry->data["prazointerior"];
			 $pre = $qry->data["prefixo"];
			 $su = $qry->data["sufixo"];
			 $b = $qry->data["codigodebarras"];
							   
             

	  
					
             $lote   = $qry->data["numloteimporta"];
 			 $lotenovo = $lote+1;  
			 $sql = "UPDATE tbconfigproduto SET numloteimporta='".$lotenovo."' 
			 WHERE idtransportadora= 1 AND 
			 codigoproduto= $produto AND codcliente=$cliente.";
			 $qry->executa($sql);



            // echo $sql;
            //die;


           		 
            for ($i = 0; $i < count($vLinhas); $i++)
            {
                $vValores = explode(";", $vLinhas[$i]);
                
                
				$kit ='';
                $destinatario  = $vValores[0];
                $vip           = trim($vValores[1]);
                $kit_1         = trim($vValores[2]); //Consulta
                $kit_2         = trim($vValores[3]); //Sadt
                $kit_3         = trim($vValores[4]); //Outras Despesas
                $kit_4         = trim($vValores[5]); //Internação
				$kit_5         = trim($vValores[6]); //Opme
				$kit_6         = trim($vValores[7]); //comprovante Presencial
				$kit_7         = trim($vValores[8]); //Programação de Internação
				$kit_8         = trim($vValores[9]); //Solicitação de Recurso de Glosa
		        $endereco      = trim($vValores[10]); 
                $numero        = trim($vValores[11]);
                $cep           = trim($vValores[12]);
                $complemento   = trim($vValores[13]);
                $cidade        = trim($vValores[14]);
                $bairro        = trim($vValores[15]);
                $estado        = trim($vValores[16]);
                $fone          = trim($vValores[17]);

                $cep = str_replace("-","",$cep);   
                  
			//	 echo $bairro;
		//		 echo "<br>";		
				  
                
				$kit = $kit.'[CONS>'.$kit_1;
				$kit = $kit.'*******[SADT>'.$kit_2;
				$kit = $kit.'********[DESP>'.$kit_3;
				$kit = $kit.'********[INTER>'.$kit_4;
				$kit = $kit.'************[OPME>'.$kit_5;
				$kit = $kit.'*****[C.PRES>'.$kit_6;
				$kit = $kit.'*****[P.INT>'.$kit_7;
				$kit = $kit.'*****[GLOSA>'.$kit_8;
			  				  
                  //$kit = $kit.$ren.chr(13); 
                
                  $codigoregiao  = 2;
								
                
              
                     $cod_base_entrega=1;
                     
								
								$b = $b+1;
								$sql = "UPDATE tbconfigproduto SET codigodebarras='".$b."' WHERE idtransportadora=1 AND codigoproduto=$produto AND codcliente=322";
								//echo $sql;
							//	echo "<br>";
								
								$qry6->executa($sql);
								
								 
								$c=$pre.$b.$su;
								
								
								 $sql = "SELECT prazocapital,prazointerior
								   FROM tbconfigproduto WHERE
								   codcliente = '$cliente'
								   and					 
								   codigoproduto= '$produto'";
								   $qry->executa($sql);
								   
								   
						   
								   $prazocapital  = $qry->data["prazocapital"];
								   $prazointerior = $qry->data["prazointerior"];
								   if(!$prazocapital)
									  $prazocapital=2;
								   if(!$prazointerior)	  
									   $prazointerior=5;
								   if(!$cep)	   
									   $cep = 20921002;
									   
                                    
                                    
									
								   
								   $hoje = date("Y/m/d");
								   if($cep <=23021002)
									  $prazo = data_soma_dia($hoje,$prazocapital);
								   else	  
									  $prazo = data_soma_dia($hoje,$prazointerior);
									  
								 	
								  
 								   $soma_final_semana = 0;
									$dia_da_semana = date('N', strtotime($prazo));
									if($dia_da_semana==7)
									  $soma_final_semana =2;
									if($dia_da_semana==1)
									  $soma_final_semana =1;  
									  
									if($dia_da_semana>0)
									{
									if($cep <=23021002)
									  $prazo = data_soma_dia($hoje,$prazocapital+$soma_final_semana);
								   else	  
									  $prazo = data_soma_dia($hoje,$prazointerior+$soma_final_semana);
									}
														
									
											
									$prazo = grava_data($prazo);
									  
									  
									  
									  
								   if(!$prazo)
									  {
									  echo "OCORREU UM ERRO NA INCLUSÃO DO PRAZO DE ENTREGA, A OPERAÇÃO FOI ABORTADA.";
									  die;
									  
									  
									  }
								
								
								
								$sql = "SELECT * FROM tbentrega where idexterno = '$c'";
						 $qry->executa($sql);	
								if(!$qry->nrw)
								{
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
								 codigodaregiao,
                                 numerosedex,
                                 obshotfast,
                                 codsinistro
                                 )
                                 VALUES
                                (
                                1,
								1,
								'".date("Y/m/d")."',
								'$prazo',
								'$prazo',
								1,
								322,
								0,
								'$c',
								'$lote',
								'$lote',
                                '$produto',
					      		1,
								'$kit',
                                '778',
							    1,
								'".$numconta."',
							    '".$lote."',
							    $codigoregiao,
                                '$numerosedex',
                                '$obshotfast',
                                '$cod_base_entrega'
                                )";        
				      		   $qry->executa($sql);
							   $sql = "SELECT idinterno FROM tbentrega WHERE idexterno = '$c'";
							   $qry->executa($sql);                            //--------------------------------------Teste KADU
							   $idinterno = $qry->data["idinterno"];
                
                 //errada strtoupper(remove_acentos(substr($bairro),0,38)) 
                 // certa strtoupper(remove_acentos(str_replace("'","",substr(trim($bairro),0,38))))
                 
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
						       obsentrega
                               )
                               VALUES
                               (
                               '$idinterno',
							   '".strtoupper(remove_acentos(str_replace("'","",substr(trim($destinatario),0,50))))."',
							   '".strtoupper(remove_acentos(str_replace("'","",substr(trim($endereco),0,69))))."',
							   '".trim($numero).'-'.strtoupper(remove_acentos(trim($complemento)))."',
                               '".strtoupper(remove_acentos(str_replace("'","",substr(trim($bairro),0,38))))."',
							   '".strtoupper(remove_acentos(substr(trim($cidade),0,29)))."',
							   '".str_replace("-","",substr(trim($cep),0,8))."',
                               '".substr(trim($estado),0,2)."',
                               '".substr(trim($fone),0,30)."',
							   '$kit'
                               )";
                               $qry->executa($sql); 
							   
							  // echo "<br>";
							   
							   if ($vip==VIP||$vip=='VIPF')
								 {
								  $sql = "update tbentrega set 
                                          idtipomovimento = 601 where idinterno = $idinterno";
									  	  $qry->executa($sql);
											 

											 inseremovimento($idinterno,300,601,0,0,0,"","","");

											 $sql = "Select idmovimento from tbmovimento where idinterno = $idinterno and idtipomovimento = 601";
											 $qry->executa($sql);
											 $idmovimento =  $qry->data["idmovimento"];

											 $sql = "insert into tbobsbloqueio (idinterno,idmovimento,obs) values ($idinterno,$idmovimento,'ENTREGA COM PRIORIDADE')";
											 $qry->executa($sql);
											 //echo $sql;


										 }
									}	 		
									}	 
                   
                   	 $lotenovo = $lote+1;  
					   $sql = "UPDATE tbconfigproduto SET numloteimporta='".$lotenovo."' 
						         WHERE idtransportadora= 1 AND 
							       codigoproduto= $produto AND codcliente=$cliente.";
							       $qry->executa($sql);      
             

             echo "<BR><BR><CENTER><FONT COLOR=#aa0000><B></B> Arquivo Importado com sucesso total : " .$i. "  lote :   "   .$lote.  "</font></CENTER>";
        }
      ?>
  </form>
  <? $con->desconecta(); ?>
</BODY>
</html>
