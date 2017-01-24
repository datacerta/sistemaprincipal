
<?
//Ultima alteração - Ravnus - Fabio - 28/06/2007
//inclui biblioteca de controles

//1176736
include("classes/diversos.inc.php");

require_once("classes/barcode.inc.php");

//testa sessão
if (VerSessao()==false){
	header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}

$data_dia = date('Y-m-d');
$sql = new consulta($con);
$sql1 = new consulta($con);
$sql2 = new consulta($con);
$sql3 = new consulta($con);
$sql4 = new consulta($con);
$sql5 = new consulta($con);
$sql6 = new consulta($con);
$sql7 = new consulta($con);
$sql8 = new consulta($con);
$sql9 = new consulta($con);
$sql10 = new consulta($con);

$sql->executa("select * from tbtipolista where codigotipolista=$cblista");
$sql3->executa("select * from tblogin where codlogin =".$_SESSION['IDUSER']);
$sql4->executa("select * from tbbase where codbase=".$_SESSION['IDBASE']);



//new barCodeGenrator($lista,1,'barcode/barcode_'.$lista.'.gif', 190, 70, true);
 
//echo "teste";
//die;



if($enviar=="enviar" || $inf == 2){

	//imprime lista
	/*     echo "select * from tbtipolista where codigotipolista=$cblista";
	echo "select * from tblista where codigotipolista=$cblista and numlista=$lista";
	echo "select idinterno, numlista from tbmovimento where numlista=$lista group by idinterno, numlista order by idinterno";
	echo "select * from tblogin where codlogin =".$_SESSION['IDUSER'];
	echo "select * from tbbase where codbase=".$_SESSION['IDBASE'];
	*/
	$sql->executa("select * from tbtipolista where codigotipolista=$cblista"); //TIPO DE LISTA
	$sql1->executa("select * from tblista where numlista=$lista"); //LISTA  (*) Daniel tirou a parte "codigotipolista=$cblista and "
	$sql2->executa("select tbentrega.idinterno, numlista, tbentrega.codcliente from
                  tbentrega,tbenderecoentrega  
                  where 
                  tbentrega.idinterno = tbenderecoentrega.idinterno
                  and
                  numlista=$lista order by cast(tbenderecoentrega.cepentrega as integer) "); //ENTREGA

        //(*) -------------------------Modificada por Daniel --------------------------
        if ($cblista == 10)
		{
           $sql2->executa("select idinterno, numlista, codcliente from tbentrega 
                           where listafatura=$lista order by idexterno"); //ENTREGA
        }
        else
        {
			$order_idgrau = "";
			
			if($cblista == 19)
			{
				$order_idgrau = "tbentrega.idgrau,";
			}
          	$sql2->executa("select tbentrega.idinterno, numlista, codcliente from
                  tbentrega,tbenderecoentrega  
                  where 
                  tbentrega.idinterno = tbenderecoentrega.idinterno
                  and
                  numlista=$lista order by ".$order_idgrau."
                  cast(tbenderecoentrega.cepentrega as integer),enderecoentrega "); //ENTREGA
			//ENTREGA
        }
        //--------------------------------------------------------------------------------

	$qtd_encomendas = $sql2->nrw;

	$sql3->executa("select sum(quantidadevolumes) as sum_qtd_vol from tbentrega where numlista='$lista'"); //somatorio da quantidade de volumes
	$sum_qtd_vol = $sql3->data["sum_qtd_vol"];

	$sql3->executa("select sum(valorentrega) as sum_valor from tbentrega where numlista='$lista' and codcliente <> 6670"); //somatorio da quantidade de volumes
	$sum_valor = $sql3->data["sum_valor"];



	$sql3->executa("select * from tblogin where codlogin =".$_SESSION['IDUSER']); //LOGIN
	$sql4->executa("select * from tbbase where codbase=".$_SESSION['IDBASE']); //BASE
	//$sql8->executa("select distinct(idinterno) from tbmovimento where numlista=$lista  and (idtipomovimento=119 or idtipomovimento=121) order by idinterno"); //MOVIMENTO


	if($sql1->data["codcourier"]<>"")
	$sql5->executa("select * from tbcourier where codigocourier=".$sql1->data["codcourier"]); //COURIER

	else if($sql1->data["codbase"]<>"")
	$sql5->executa("select * from tbbase where codbase=".$sql1->data["codbase"]); //BASE
	$nn_base          = $sql5->data["nomebase"];
    $nn_base_endereco = strtoupper($sql5->data["enderecobase"]);
    $nn_base_bairro = strtoupper($sql5->data["bairrobase"]);
    $nn_base_cidade = strtoupper($sql5->data["cidadebase"]);
    $nn_base_uf = strtoupper($sql5->data["ufbase"]);
    $nn_base_cep = $sql5->data["cepbase"];
    echo "
	  <font size=1>

      <tr>";
    //echo "<td width='25%' align='center'><strong>E-SISCO</strong></td>";
	echo "<td width='10%' align='center'>&nbsp;</td>";



	if($cblista==1){
	echo "<table width='20%' border='0'>";
		echo "<td colspan='7' nowrap><div align='LEFT'><strong>LISTA DE ENTREGAS</strong></div></td>";
	}else if($cblista==2){
	echo "<table width0=800 align = left border='0'>";
		echo "<td colspan='7' nowrap><div align='left'><strong>LISTA DE SACA PARA BASE : $nn_base</strong></div></td>";
	}else if($cblista==5){
		echo "<td colspan='7' nowrap><div align='center'><strong>LISTA DE DEVOLUÇÃO A MATRIZ</strong></div></td>";
	}else if($cblista==8){
		echo "<td colspan='7' nowrap><div align='center'><strong>LISTA DE C.O.D.</strong></div></td>";
	}else if($cblista==3){
		echo "<td colspan='7' nowrap><div align='center'><strong>LISTA DE DEVOLUÇÃO DE AR's</strong></div></td>";
	}else if($cblista==4){
		echo "<td colspan='7' nowrap width='50%' bgcolor='#eeeeee' align='center' style='line-height: 200%'><strong>LISTA DE DEVOLUÇÃO PARA AGENCIA</strong></td>";
	}else if($cblista==6){
		echo "<td colspan='7' nowrap width='50%' bgcolor='#eeeeee' align='center' style='line-height: 200%'><strong>LISTA DE DEVOLUÇÃO AO CLIENTE</strong></td>";
	}else if($cblista==9){
		echo "<td colspan='7' nowrap width='50%' bgcolor='#eeeeee' align='center' style='line-height: 200%'><strong>LISTA DE SINISTROS</strong></td>";
	}else if($cblista==10){
		echo "<td colspan='7' nowrap width='50%' bgcolor='#eeeeee' align='center' style='line-height: 200%'><strong>LISTA DE FATURAMENTO</strong></td>";
	}else if($cblista==13){
		echo "<td colspan='7' nowrap width='50%' bgcolor='#eeeeee' align='center' style='line-height: 200%'><strong>LISTA DE FATURAMENTO</strong></td>";
	}else if($cblista==24){
		echo "<td colspan='7' nowrap width='50%' bgcolor='#eeeeee' align='center' style='line-height: 200%'><strong>REDESPACHO CORREIOS</strong></td>";
	
  
  	
	
  
  	
	}else if($cblista==14){
		echo "<td colspan='7' nowrap width='50%' bgcolor='#eeeeee' align='center' style='line-height: 200%'><strong>LISTA DE STAND BY</strong></td>";
	}else if($cblista==15){
                echo "<td colspan='7' nowrap width='50%' bgcolor='#eeeeee' align='center' style='line-height: 200%'><strong>LISTA DE RDV - RECEB. VENCIDO</strong></td>";
	}else if($cblista==16){
                echo "<td colspan='7' nowrap width='50%' bgcolor='#eeeeee' align='center' style='line-height: 200%'><strong>LISTA DE DEVOLUÇÃO SABANCO</strong></td>";
	}else if($cblista==17){
                echo "<td colspan='7' nowrap width='50%' bgcolor='#eeeeee' align='center' style='line-height: 200%'><strong>LISTA DE DEVOLUÇÃO AGENCIA SABANCO</strong></td>";
	}else if($cblista==18){
                echo "<td colspan='7'  nowrap width='50%' bgcolor='#eeeeee' align='center' <font size=20 color='#FF0000'><strong>ROMANEIO DE CARGA</strong></font></td>";
 }else if($cblista==31){
                echo "<td colspan='7' nowrap width='50%' bgcolor='#eeeeee' align='center' style='line-height: 200%'><strong>DEVOLUÇÃO DOS CORREIOS</strong></td>";


	}else if($cblista==19){
                echo "<td colspan='7' nowrap width='50%' bgcolor='#eeeeee' align='center' style='line-height: 200%'><strong>LISTA DE ARS AO CLIENTE</strong></td>";

	}else if($cblista==20){
                
                ?>
                
                <input type="button" name="imprimir" class="botao" onclick='javascript:window.open("ar_bb.php?lista=<?=$lista;?>","Imprimir","width=640, height=480, scrollbars=yes, menubar=yes, location=yes, status=yes, toolbar=yes, resizable=yes");' value='Imprime AR´s '>
                
                <?

                die;

	}else{
		echo "<td colspan='7'><div align='center'>TIPO DE LISTA INVÁLIDO</div></td>
     		    <input type='button' value='Voltar' onclick='javascript:history.back()'>";
		exit;
	}
	echo "<td width='25%' align='center'><strong></strong></td>
      </tr>
      	  <tr>
		    <td align='left' nowrap>Usu&aacute;rio/Base: ".$sql3->data["nomelogin"] . " / " . $sql4->data["nomebase"] ."</td>
			<TR>
		    <td colspan='7' align='left' nowrap>".mostra_data($data_dia)
     ."</td>
		  
		  <tr>
		    <td colspan='3'> ";
        
        echo '<img src="barcode/barcode_'.$lista.'.gif" />';
        
        
        
	if (isset($cblista)&&($cblista!=0)||($cblista!="")){
		if($sql5->nrw>0)
		$para = $sql5->data["nomecourier"];
	
		echo "<font>".$para."</font></td>";
	}else{
		echo "<font color=#990000>Tipo de Lista não identificado.</font>";
	}

	
	if(!$sql1->nrw){

		echo "<br><table align='center'>
				<tr>
	  				<td  align='center'><br><font size=3 color='#FF0000'><strong>Lista $lista não encontrada.</strong></font><br></td>
    		</tr></table><br>";

	}else{



		if ($cblista<>6 and $cblista<>18  )    {
			if ($cblista<>8){echo "
  	  </table>
	  <table width='800' border='0'>
	  <tr>
    	  <td width='20' align='left'><br></td>
	      <td width='200' align='left'>&nbsp;</td>

	  </tr>
	  <tr>
	      <td width='20' align='left'>ID</td>";
		  if($sql2->data["codcliente"] == "6841")
			echo "<td width='20' align='left'>NF</td>";
		  
	      echo "<td width='50' align='left'>DESTINATARIO</td>
        <td width='300' align='left'>ENDEREÇO</td>
        <td width='300' align='left'>OBSERVACAO</td>
        <td width='20' align='left'>BAIRRO</td>
        <td width='70' align='left'>CIDADE</td>
        <td width='10' align='left'>CEP</td>
        <td width='10' align='left'>ORDEM</td>
	      </tr>";

			$total = 0;
			$totalTT = 0;

			echo "<hr></hr>";
      for ($i=0;$i<$sql2->nrw;$i++){
						
        $sql2->navega($i);
				
        $sql6->executa("select
			           	tbentrega.idexterno, tbentrega.idinterno, 
                        tbentrega.valorentrega, tbentrega.codcliente,
                        tbentrega.obsentrega,
						tbentrega.numnotafiscal,						
                        tbenderecoentrega.idinterno, 
                        tbenderecoentrega.enderecoentrega,
                        tbenderecoentrega.referenciaentrega,
			            tbenderecoentrega.bairroentrega, 
                        tbenderecoentrega.cidadeentrega,
                        tbenderecoentrega.cepentrega,
                        tbenderecoentrega.estadoentrega, 
                        tbenderecoentrega.nomeentrega,
                        tbenderecoentrega.enderecoentrega,
					    tbenderecoentrega.foneenderecoentrega from tbentrega
					   	inner join tbenderecoentrega on 
                        tbentrega.idinterno = tbenderecoentrega.idinterno
					    where tbentrega.idinterno=".$sql2->data["idinterno"]);
			
				echo "<tr>";
				//Encomendas
				$cliente      = $sql6->data["codcliente"];
				$nfiscal      = $sql6->data["numnotafiscal"];
        $destinatario = substr($sql6->data["nomeentrega"],0,25);
        $bairro       = substr($sql6->data["bairroentrega"],0,15);
        $cidade       = substr($sql6->data["cidadeentrega"],0,10);
        $endereco     = trim($sql6->data["enderecoentrega"]);
        $obs = $sql6->data["referenciaentrega"].' '.$sql6->data["obsentrega"];
        $cep       = substr($sql6->data["cepentrega"],0,8);
        
        
        echo "<td><font size =2>
				     ".$sql6->data["idexterno"]."
             </font></td>";
		
		if($cliente == "6841"){		 
		echo "<td  align='left'><font size =2>
				 ".str_pad($nfiscal, 9, '0', STR_PAD_LEFT)."
				 </font></td>";
        }
		
		echo "<td  align='left'><font size =2>
				 ".$destinatario."
				 </font></td>";
		
      
      	echo "<td  align='left'><font size =2>
						 ".$endereco."
						 </font></td>";
           
      	echo "<td  align='left'><font size =2>
						 ".$obs."
						 </font></td>";
           
        echo "<td  align='left'><font size =2>
						 ".$bairro."
						 </font></td>";
            
        echo "<td  align='left'><font size =2>
						".$cidade."
						</font></td>";
            
        echo "<td  align='left'><font size =2>
						".$cep."
						</font></td>";
            
        echo "<td  align='left'><font size =2>
						    _________
						</font></td>";
        	    
       
			   if ($cliente==6670)
             {
             $total=0;
             $totalTT=0;
             }
        
			}	//fecha o for
		
      } //fecha o if ($cblista<>8)
	 if($cblista == 2) //Lista de C.O.D
			echo "<table width='100%' border='0'>
			  <tr>
		      <td width='20%' align='left'>Endreço da Unidade</td>
		      <td width='10%' align='left'>Bairro</td>
		      <td width='10%' align='left'>Cidade</td>
          <td width='5%' align='left'>CEP</td>
          <td width='5%' align='left'>UF</td>
		      </tr>";
			//fecha o for
			echo"</tr></table>";
			
      echo "<table width='100%' border='0'>
			  <tr>
		      <td width='20%' align='left'>$nn_base_endereco</td>
		      <td width='10%' align='left'>$nn_base_bairro</td>
		      <td width='10%' align='left'>$nn_base_cidade</td>
          <td width='5%' align='left'>$nn_base_cep</td>
          <td width='5%' align='left'>$nn_base_uf</td>
		      </tr>";
			//fecha o for
			echo"</tr></table>";
			
      
       
		} //Fecha if ($cblista<>4)
    
    
else if(($cblista == 6)){ //Verifica se a lista é de devolução de agencia.
		//echo $cblista;
	    $sql_novo1__ =    "select
	   	tbentrega.idexterno, tbenderecoentrega.nomeentrega, tbenderecoentrega.bairroentrega,tbenderecoentrega.cidadeentrega, tbmotivo.motivo, tbentrega.dataentrega, numconta
	   	from tbentrega,tbenderecoentrega,tbmotivo
		where tbentrega.idinterno = tbenderecoentrega.idinterno
	    and
		tbentrega.idmotivo = tbmotivo.idmotivo
		and
		tbentrega.numlista='".$lista."' order by motivo" ;
		
		$sqlConsulta = "select codcliente from tbentrega where numlista = '$lista' limit 1";
		$sql10->executa($sqlConsulta);
		
		
         
    $sql_novo ="select distinct idexterno ,nomeentrega, bairroentrega, cidadeentrega,e.dataentrega, e.numnotafiscal, e.codcliente, e.numconta,
                (select   m.motivo from tbmovimento tm inner join tbmotivo m on tm.idmotivo = m.idmotivo  where idinterno = e.idinterno and tm.idtipomovimento = 150 order by idmovimento desc limit 1) as motivo  
                from ((((tbentrega e   join  tblista l on e.numlista = l.numlista ) 
                join tbbase b on b.codbase = e.codbase ) join tbenderecoentrega t on e.idinterno = t.idinterno) 
                left outer join tbcourier c on l.codcourier = c.codigocourier) 
                where e.numlista  = '".$lista."'";
                
              /*  
                $sql_novo =" select 
                              distinct idexterno ,nomeentrega, bairroentrega, cidadeentrega,e.dataentrega, e.numnotafiscal, e.codcliente, e.numconta, 
                              (select m.motivo from tbmovimento tm inner join tbmotivo m on tm.idmotivo = m.idmotivo where idinterno = e.idinterno and tm.idtipomovimento = 150 order by idmovimento desc limit 1) as motivo 
                              from (((((tbentrega e 
                              inner join tbmovimento m on m.idinterno = e.idinterno)
                              join tblista l on m.numlista = l.numlista ) 
                              join tbbase b on b.codbase = e.codbase ) 
                              join tbenderecoentrega t on e.idinterno = t.idinterno) 
                              left outer join tbcourier c on l.codcourier = c.codigocourier) 
                              where m.numlista = '".$lista."'";      */
                
		if($sql10->data['codcliente'] == '6841')
		{
             $sql_novo .= "  order by e.numnotafiscal ";
		} else {
             $sql_novo .= "  order by motivo ";
		}
    
    
  //  echo $sql_novo; //die;
		$sql7->executa($sql_novo);
    $qtd_encomendas=0;
		echo "<table width='600' border='1'>
			  <tr>
		      <td width='20' align='center'>Encomenda</td>
			  <td width='80' align='center'>N.F/Cliente</td>
		      <td width='300' align='center'>Destinatario</td>
		      <td width='300' align='center'>Bairro</td>
			  <td width='300' align='center'>Cidade</td>
		      <td width='200' align='center'>Motivo</td>
		      <td width='50' align='center'>Data</td>
		      </tr>";
          
   //       if($sql7->nrw > 0){ echo "Eu";}
          
			  for ($i=0;$i<$sql7->nrw;$i++){
				$sql7->navega($i);
        //echo "Eu";
			  	echo "<tr>";

				echo "<td><font size =1><left>
				        ".$sql7->data["idexterno"]."
                        </center></font></td>";

				if($sql7->data['codcliente'] == '6841'){
					echo "<td><font size =1><center>
							".$sql7->data["numnotafiscal"]."
							</center></font></td>";
				} else {
						echo "<td><font size =1><center>
							".$sql7->data["numconta"]."
							</center></font></td>";
				}

				echo "<td><font size =1><left>
				        ".$sql7->data["nomeentrega"]."
                        </center></font></td>";
                        
                        
        echo "<td><font size =1><left>
				        ".$sql7->data["bairroentrega"]."
                        </center></font></td>";
						
			  echo "<td><font size =1><left>
				        ".$sql7->data["cidadeentrega"]."
                        </center></font></td>";			
                

				echo "<td><font size =1><left>
				        ".$sql7->data["motivo"]."
                        </center></font></td>";

				echo "<td><font size =1><left>
				        ".mostra_data($sql7->data["dataentrega"])."
                        </center></font></td>";

           
				echo "</tr>";
        
        
        

        $qtd_encomendas++;

			  }
        
			 echo "<tr><td width='100%' align='left' colspan='6'>TOTAL DE ENCOMENDAS  : $qtd_encomendas</td></tr>";
       echo"</tr></table>";




		}// Fecha if ($cblista==4)




else if(($cblista == 18)){ //Verifica se a lista é de devolução de agencia.
	
	    $sql_novo =    "select
	   	tbentrega.idexterno, tbenderecoentrega.nomeentrega, 
       tbentrega.datapromessa,numnotafiscal, 
       numconta,tbenderecoentrega.estadoentrega, 
       tbenderecoentrega.cidadeentrega, 
       tbentrega.valorentrega
	   	from tbentrega,tbenderecoentrega
		where tbentrega.idinterno = tbenderecoentrega.idinterno
	    and
		tbentrega.numlista='".$lista."' order by nomeentrega" ;
		$sql7->executa($sql_novo);
     $valor_da_lista = 0;
		echo "<table width='600' border='1'>
			  <tr>
		      <td width='20' align='center'>Encomenda</td>
          <td width='20' align='center'>Nota Fiscal</td>
          <td width='80' align='center'>Estado</td>
          <td width='200' align='center'>Cidade</td>
		      <td width='300' align='center'>Destinatario</td>
		      <td width='200' align='center'>Valor</td>
		      <td width='50' align='center'>Data</td>
		      </tr>";
			  
			  $total=0;
			  $totalTT=0;
			  
			  for ($i=0;$i<$sql7->nrw;$i++){
				$sql7->navega($i);
			  
        $valor_da_nota = $sql7->data["valorentrega"];
        
        $valor_da_nota = 'R$ '.number_format($valor_da_nota, 2, ',', '.');
        
               
        
         echo "<tr bgcolor=#".(($i%2)?"eeeeee":"ffffff").">";
				echo "<td><font size =1><left>
				        ".$sql7->data["idexterno"]."
                        </center></font></td>";

				echo "<td><font size =1><left>
				        ".$sql7->data["numnotafiscal"]."
                        </center></font></td>";

        
        
        echo "<td><font size =1><center>
				        ".$sql7->data["estadoentrega"]."
                        </center></font></td>";

                echo "<td><font size =1><center>
				        ".$sql7->data["cidadeentrega"]."
                        </center></font></td>";


				echo "<td><font size =1><left>
				        ".$sql7->data["nomeentrega"]."
                        </center></font></td>";

				echo "<td><font size =1><left>
				        ".$valor_da_nota."
                        </center></font></td>";

				echo "<td><font size =1><left>
				        ".mostra_data($sql7->data["datapromessa"])."
                        </center></font></td>";

					
				echo "</tr>";

        $valor_da_lista = $valor_da_lista + $sql7->data["valorentrega"];


			  }
			 echo"</tr></table>";




		}// Fecha if ($cblista==4)



     $valor_da_lista = 'R$ '.number_format($valor_da_lista, 2, ',', '.');
     
     



//////////////////////////////////////////////fim /////////////////////////////////////////////////////////////////
		if($cblista <> 6 and $cblista <> 13){
			echo"<table width='100%' border='0'>
	    
	    <tr>
      <td align='LEFT'>Volumes : $sum_qtd_vol</td>
      <td> 
			
		  
     <tr>
      <td align='LEFT'>Valor :  $valor_da_lista </td>
      <td> ";
		
    	echo "</td><br>";
		
		
			echo "<br>";
      echo "<br>";
      echo "<br>";
      echo"</table>";
      
      echo"<table width='100%' border='0'>";
      echo "<tr></tr>";
      
      echo "<tr></tr>";
      echo "<tr></tr>";
      echo "<tr>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - </tr>";
      echo "<tr></tr>";
      echo "<tr></tr>";
      echo "<tr></tr>";
      echo "<tr></tr>";
      echo "<tr></tr>";
      echo "<tr></tr>";
      echo "<tr></tr>";
      echo "<tr></tr>";
      echo "<tr></tr>";
      
                          
      
      echo"</td>
      </tr>
	  <tr>
    	  <td width='25%' align='center'><br></td>
	      <td width='25%' align='center'>&nbsp;</td>
          <td width='25%' align='center'>&nbsp;</td>
	      <td width='25%' align='center'>&nbsp;</td>
	  </tr>
      <tr>
      <td width='100%' align='left'><strong>AFIRMO TER RECEBIDO A CARGA ACIMA DESCRITA</strong></td>
	    </tr>

      </tr>
      <tr>
      <tr><td width='25%' align='left'>NOME: ____________________________</td></tr>
      <tr><td width='25%' align='left'>ASS : ____________________________</td></tr>
      <tr><td width='25%' align='left'>DATA: ____________________________</td></tr>
      <tr><td width='25%' align='left'>NOME: ____________________________</td></tr>
      <td width='25%'>
      <tr><td width='25%' align='left'>BASE   : $nn_base</td></tr>
      <tr><td width='25%' align='left'>LISTA  : $lista</td></tr>
      <tr><td width='25%' align='left'>VOLUMES: $sum_qtd_vol</td></tr>
      <tr><td width='25%' align='left'>NOTAS  : $qtd_encomendas</td></tr>
      
      ";
			
			

		}//Fecha else if($cblista <> 4)
		//Botão de voltar e imprimir

		if(($cblista == 6) && !$qtd_encomendas){//&&($sql8->data["idtipomovimento"]<>119)){//Mensagem de erro, caso não encontre a lista de devolução.
		echo"
	<tr>
	  	<td colspan='10'><div align='center'><font size=4><strong>Lista de devolução não encontrada.</strong></font></div>
    	<div align='center'></div>
	    <div align='center'></div></td>
    </tr>";
		}

		

		



		echo"
			<tr>
  				<td colspan='10'>&nbsp;</td>
        	</tr>";


		echo"<tr><td><br><br></td></tr>
			<tr>
  				<td colspan='10'><div align='center'><font size=2>Copyright(c) Fast Service - 2000/2004</font></div>
    			<div align='center'></div>
    			<div align='center'></div></td>
         	</tr>";

	}//else do if de verificacao principal - existencia da lista

	if($cblista == 4){

	echo "
	<tr><td></td></tr>
	<tr><td colspan='10'><center>
		 	  <font size='2'>
		 	  <A href='$arquivo' target=__TOP border=0 vlink=\"#cccccc\" alink=\"#cccccc\" link=\"#cccccc\"><input type='button' value='Download'></a>
		 	  <input type='button' value='Voltar' onclick=\"javascript:document.location.href='print_lista.php?cblista=$cblista&op=$op&inf=$inf'\">
		 	  </font>
		 	</center></td></tr>";
	} else if($cblista == 1){
		echo "<tr><td colspan='10'><center>
		 	  <font size='2'>
		 	  <input type='button' value='Imprimir' onclick='self.print()'>
			  <input type='button' value='Imprimir na Térmica' onclick=\"javascript:window.open('print_lista_pdf.php?cblista=$cblista&lista=$lista&inf=2&enviar=$enviar')\">
		 	  <input type='button' value='Voltar' onclick=\"javascript:document.location.href='print_lista.php?cblista=$cblista&op=$op&inf=$inf'\">
		 	  </font>
		 	</center></td></tr>";
	} else {
		echo "<tr><td colspan='10'><center>
		 	  <font size='2'>
		 	  <input type='button' value='Imprimir' onclick='self.print()'>
		 	  <input type='button' value='Voltar' onclick=\"javascript:document.location.href='print_lista.php?cblista=$cblista&op=$op&inf=$inf'\">
		 	  </font>
		 	</center></td></tr>";
	}


	exit;

}//Fecha if($_POST['enviar'] || $inf == 2)
  ?>
<html>
<link href="tablecloth/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />

<head>
<title>..:: Liberação de Listas ::..</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<link href="tahoma.css" rel="stylesheet" type="text/css">



<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body onload="javascript:document.libera_lista.lista.focus();">


<div align="center">
  <form name="libera_lista" action="<?=$PHP_SELF;?>" method="POST">
  <?
  //Monta  href do form em tempo de execução
  if(isset($cblista))
  echo "<input type='hidden' name='cblista' value='$cblista'>";

  if(isset($nlista))
  echo "<input type='hidden' name='nlista' value='$abrlst'>";

  if(isset($op))
  echo "<input type='hidden' name='op' value='$op'>";

  if(isset($inf))
  echo "<input type='hidden' name='inf' value='$inf'>";

  ?>
    <table width="622" align=left>
      <tr bgcolor="#eeeeee">
        <td  colspan=6> <div align="center"><font size="6"><b><font size="3"><strong>..::
            </strong></font></b><strong><font color="#990000" size="3">Impress&atilde;o
            de Listas </font></strong></font> <strong><font size="3"><b>::..</b></font></strong><font size="3"><b></b></font></div></td>
      </tr>
      <tr bgcolor="#FFFFFF">
        <td width="140" height="23"> <div align="right">Imprimindo: </div></td>
        <td height="23" colspan="5">
          <?
  if (isset($cblista)&&($cblista!=0)||($cblista!="")){
  	echo "<font color=#990000>".$sql->data[1]."</font>";
  }else{
  	echo "<font color=#990000>Tipo de Lista não identificado.</font>";
  }
		?>
        </td>
      </tr>
      <tr>
        <td> <div align="right">Lista:</div></td>
        <td colspan="5"> <input name="lista" type="text" value="<?$lista?>"> </td>
      </tr>
      <tr bgcolor="#eeeeee">
        <td height="5" colspan="6"></td>
      </tr>
      <tr>
        <td height="17" colspan="4"> <center>
            <?
		if(isset($msg)){
			echo $msg."<font color='#990000'> Lista: $lista </font>" ;
		}

		?>
          </center></td>
        <td width="277" height="17" colspan="2"><div align="right">
            <input type="hidden" name="enviar" value="enviar" id="enviar">
            <input name="enviar" type="submit" id="enviar" value="enviar">
          </div></td>
      </tr>
      <tr>
        <td height="17" colspan="6"><div align="center"><A href="selecao_lista.php"><<
            Anterior</A></div>
          <div align="center"></div></td>
      </tr>
    </table>

</form>

</div>
<? $con->desconecta(); ?>
</body>
</html>
