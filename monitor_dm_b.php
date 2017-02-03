

<?
$opt = 'P';
					
echo "<meta HTTP-EQUIV='refresh' CONTENT='60;URL=monitor_dm.php'>";
 

//inclui biblioteca de controles
require_once("classes/diversos.inc.php");

//testa sessão
//if (VerSessao()==false){
	//header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
//}

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry_baixa = new consulta($con);
$qry_n_baixa = new consulta($con);
$qry_ra = new consulta($con);
$qry_lista = new consulta($con);






$sql = "select current_time as hora";
        $qry->executa($sql);
        $hora_atual =  substr($qry->data["hora"],0,8); 

?>
<html>



<head>
<link href="css/tabela_3.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet" type="text/css">
<script> 
function some() {
if(document.getElementById("div").className == "aparece")
{document.getElementById("div").className ="some";}
else
{
document.getElementById("div").className ="aparece";
}
}

</script>



<script>

setInterval("some()", 500);

</script>





<title>..:: DATA CERTA ::..</title>
</head>

<a href="monitor_digitalizacao.php">trocar</a>
	<table width=100% class = 'psdgraphics-com-table'>


<td align=center><p><font size=6px>MONITORAMENTO DEMILLUS TESTE</font></td> 
<td align=center bgcolor="#FF6666">ENCAMINHADO PARA ROTA </td> 
<td align=center bgcolor="#FFCC66">EM TRANSITO</td> 
<td align=center bgcolor="#87CEEB">ROTA FINALIZADA</td> 
<td align=center bgcolor="#000000">OCORRENCIA NA ROTA</td> 



</table>




			<form name='form_pesquisa' action="<?=$PHP_SELF;?>" method='POST'>
			
       		<input type='hidden' name='opt' value='P'>
       		<input type='hidden' name='ver_detalhes' value=''>
       		<input type='hidden' name='numlotecliente' value=''>
       		<input type='hidden' name='dataemissao' value=''>
       		<input type='hidden' name='tot_base' value=''>
			

       
			
			
		<?
   
    
    
		Switch($opt){
			
			case "P":
			//procurar
			//die;
		
			$qry->nrw = 0;
			$sql = "select num_setor,data_entrega
                    from tb_demillus_campanha where 
                    tb_demillus_campanha.data_entrega =  current_date order by num_setor";
                    $qry->executa($sql);
					
					echo $sql."<br>";

			if($qry->nrw){
				echo "<table  class = 'psdgraphics-com-table'>";
				echo "<tr bgcolor=#cccccc>";   //cor cinza + forte
				echo "	<td width = 5% align='center'><font color = 000000><b>SETOR</b></font></td>";
				echo "	<td width=40% align='center'><font color = 000000><b>ENTREGADOR</b></font></td>";
				echo "	<td width = 5%  align='center' colspan='2'><font color = 000000><b>COLETADOS</b></font></td>";
				echo "	<td width = 5% align='center' colspan='2'><font color = 000000><b>ENTREGUES</b></font></td>";
				echo "	<td width = 5% align='center' colspan='2'><font color = 000000><b>DEVOLVIDOS</b></font></td>";
				echo "	<td width = 5% align='center' colspan='2'><font color = 000000><b>DEPOSITO</b></font></td>";
				echo "	<td width = 5% align='center' colspan='2'><font color = 000000><b>PENDENTES</b></font></td>";
				echo "	<td width = 5% align='center' colspan='2'><font color = 000000><b>RESTANTE</b></font></td>";
				echo "	<td width = 7% align='center' ><font color = 000000><b>ÚLTIMA BAIXA</b></font></td>";
				echo "	<td width = 7% align='center' ><font color = 000000><b>ROMANEIO</b></font></td>";
		
				echo "</tr>";
				
				
				
								
				for($i=0;$i<$qry->nrw;$i++){
					$qry->navega($i);
					
					$setor = $qry->data["num_setor"];
					$data_entrega = $qry->data["data_entrega"];
					$emissao = data_subtrai_dia(mostra_data($data_entrega),13);   // alterar para 7-- 23/02/2015
					$emissao = substr($emissao,6,4). '-' . substr($emissao,3,2) .'-'.  substr($emissao,0,2);   
					$where_base_atual = " AND tbentrega.primeiroenvelope='$setor'";
					//$emissao = '2015-09-01';
					
					$sql2 = "select count(idinterno) as total,tbbase.nomebase,tbentrega.codbase,numlista  
					        from tbentrega,tbbase where 
					        tbentrega.codbase = tbbase.codbase and codcliente = 6670 and 
                            primeiroenvelope = $setor and dataemissao > '$emissao' 
							and tbbase.monitor = 'S'
							group by nomebase,primeiroenvelope,tbentrega.codbase,numlista";
					        $qry2->executa($sql2);

					var_dump($sql2);
					//echo $sql2;
					//echo "<br>";
					


					//ultima baixa realizada
					$ultima_base_baixa='';
					$sql_baixa = 	"select tbentrega.codbase ,data_baixa_nextel
									from tbentrega,tbbase where 
									tbentrega.codbase = tbbase.codbase and codcliente = 6670 and 
									dataemissao > (current_date - 6 )
									and tbbase.monitor = 'S'
									and data_baixa_nextel is not null
									order by data_baixa_nextel desc limit 1";
									$qry_baixa->executa($sql_baixa);
									$ultima_base_baixa = $qry_baixa->data["codbase"];
									$ultima_hora_baixa = $qry_baixa->data["data_baixa_nextel"];
									
									  
					
									
									
									
					
					for($c=0;$c<$qry2->nrw;$c++){
						
						$qry2->navega($c);
						$coletado = $qry2->data["total"];
						$base = $qry2->data["nomebase"];
						$codbase = $qry2->data["codbase"];
						$lista = $qry2->data["numlista"];
						//VERICANDO A QUANDIDADE ENTREGUES
						$sql3 = "select count(idinterno) as total_entregue from tbentrega where 
								codcliente = 6670 and 
								primeiroenvelope = $setor and dataemissao > '$emissao'  
								and codbase = $codbase 
								and st = 'E' group by codbase,primeiroenvelope";
								$qry3->executa($sql3);
								if($qry3->nrw)
								  $entregues = $qry3->data["total_entregue"];
								else 
 								  $entregues = 0;
 								  
 							//VERICANDO A QUANDIDADE NO DEPOSITO
						$sql3 = "select count(idinterno) as total_deposito from tbentrega where 
								codcliente = 6670 and 
								primeiroenvelope = $setor and dataemissao > '$emissao'  
								and codbase = $codbase 
								and st = 'N' group by codbase,primeiroenvelope";
								$qry3->executa($sql3);
								if($qry3->nrw)
								  $deposito = $qry3->data["total_deposito"];
								else 
 								  $deposito = 0;
 								  		  
 								  
 								  
 								  
								  
							    $sql3 = "select count(idinterno) as total_devolvido from tbentrega where 
								codcliente = 6670 and 
								primeiroenvelope = $setor and dataemissao > '$emissao'  
								and codbase = $codbase 
								and (tbentrega.idmotivo <>9 and (tbentrega.idmotivo >=1 and idmotivo <> 12) ) group by codbase,primeiroenvelope";
                //echo $sql3;
								$qry3->executa($sql3);
								if($qry3->nrw)
								  $devolvido = $qry3->data["total_devolvido"];
								else 
 								  $devolvido = 0;		  
								  
								   	$ultima_hora='';
								    $sql_baixa = 	"select data_baixa_nextel 
									from tbentrega where 
									codcliente = 6670 and 
									dataemissao > (current_date - 6 )
									and tbentrega.codbase = $codbase
									and data_baixa_nextel is not null
									order by data_baixa_nextel desc limit 1";
									$qry_baixa->executa($sql_baixa);
									if($qry_baixa->nrw)
										$ultima_hora = $qry_baixa->data["data_baixa_nextel"];
										
									
								  //verifica se existe status na lista
								if(!$lista) $lista =1;
								$status_lista = 'N';
								$status_text = '';
								$sql_lista = "select tb_status_lista.status,id_status 
								              from tblista,tb_status_lista where
											  tblista.status = tb_status_lista.id_status 
											  and numlista = $lista";
											  //echo $sql_lista;
											  //echo "<br>";
											  $qry_lista->executa($sql_lista);
								if($qry_lista->nrw ){
								$status_lista = $qry_lista->data["id_status"];
								$status_text  = ' -'.$qry_lista->data["status"];
								}			  
								  
						
						
						   $pendentes = $coletado - ($entregues + $devolvido  + $deposito);
						   
						   if($pendentes == 0 and $devolvido >0 and $deposito == 0)
						       $dep=1;
						   else
						       $dep=0;    
						   
						
						
						
						
						
						$pencentual = (  $pendentes/ $coletado ) * 100;
						if(	$pendentes==0)
						    $ultima_hora='';
						
						//$cor = '0c2a62';
						$pisca_100 = '';
						if($pencentual==100)
						   {
						   $cor = 'FF6666';
						   $cor_fonte = 'ffffff';
						   $pisca_100 = 'S';
						   }
						elseif($pendentes<=0)
						   {
						   $cor = '87CEEB';
						   $cor_fonte = 'ffffff';
						   }
						elseif($pendentes != 0)
						   {
						   $cor = 'FFCC66';
						   $cor_fonte = '000000';
						   }
						
						if($status_lista >1)
						   {
						   $cor = '000000';
						   $cor_fonte = 'ffffff';
						   }  
						   
						   if($dep ==1)
						   {
						   $cor = 'FF1FF';
						   $cor_fonte = 'ffffff';
						   }  
						   
						//<div  class="aparece" id="div">Pisca Pisca</div>
						
						echo "	<td bgcolor = $cor><font color = $cor_fonte><b>".$setor."</font></b></td>";
						if($codbase == $ultima_base_baixa)
							
							echo "	<td width = 40% bgcolor = $cor><font color = $cor_fonte><b> 
							        <div  class='aparece' id='div'>".strtoupper(remove_acentos($qry2->data["nomebase"]))." </div></font></b></td>";
							
						else
							echo "	<td width = 5% bgcolor = $cor><font color = $cor_fonte><b>".strtoupper(remove_acentos($qry2->data["nomebase"])).$status_text."</font></b></td>";	
						
						echo "	<td  width = 5% bgcolor = $cor colspan = 2 align='center'><font color = $cor_fonte><b>".$coletado."</font></b></td>";
						echo "	<td width = 5% bgcolor = $cor colspan = 2 align='center'><font color = $cor_fonte><b>".$entregues."</font></b></td>";
						echo "	<td width = 5% bgcolor = $cor colspan = 2 align='center'><font color = $cor_fonte><b>".$devolvido."</font></b></td>";
						echo "	<td width = 5% bgcolor = $cor colspan = 2 align='center'><font color = $cor_fonte><b>".$deposito."</font></b></td>";
						
						echo "	<td width = 5% bgcolor = $cor colspan = 2 align='center'><font color = $cor_fonte><b><blink>".$pendentes."</blink></font></b></td>";
						if(pisca_100=='S')
							echo "	<td width = 5% bgcolor = $cor colspan = 2  align='center'><font color = $cor_fonte><b> <div  class='aparece' id='div'>".number_format($pencentual,2,',','.').'%'."</div></b></font></td>";
                        else							
						    echo "	<td width = 5% bgcolor = $cor colspan = 2  align='center'><font color = $cor_fonte><b>".number_format($pencentual,2,',','.').'%'."</b></font></td>";
					   
					   $tempo_sem_baixa = difDeHoras(mostra_data($ultima_hora,3),$hora_atual);
					   if(	$pendentes==0)
						    $tempo_sem_baixa='';
					   echo "	<td width = 7% bgcolor = 0099CC   align='center'>
					   <font color = 'ffffff'><b>".mostra_data($ultima_hora,3)."</font></b></td>";
					   echo "	<td width = 7% bgcolor = 99CCFF   colspan = 2 align='center'>
					   <font color = 'ffffff'><b>".$lista."</font></b></td>";

                        


						echo "</tr>";
						
						$somacoletado  = $somacoletado + $coletado;
						$somaentregues  = $somaentregues + $entregues;
						$somadevolvidos = $somadevolvidos + $devolvido;
						$somapendentes  = $somapendentes + $pendentes;
						$somadeposito   = $somadeposito + $deposito;
						
					
					}
					
					
					
					
          
          
                //echo "<td colspan=7>--</td>"; 
				//echo "</tr>";	

									
					
				}
				
				
				
				//totalizador
				$pencentualpendente = ($somapendentes / $somacoletado) * 100;
				echo "<tr bgcolor=#000099 >";          // cor cinza +- forte
				echo "	<td><b>.</b></td>";
				echo "	<td><b>TOTAL</b></td>";
				echo "	<td colspan = 2 align='center'><b>".$somacoletado."</b></td>";
				echo "	<td colspan = 2 align='center'><b>".$somaentregues."</b></td>";
				echo "	<td colspan = 2 align='center'><b>".$somadevolvidos."</b></td>";
				echo "	<td colspan = 2 align='center'><b>".$somadeposto."</b></td>";
				echo "	<td colspan = 2 align='center'><b>".$somapendentes."</b></td>";
				echo "	<td colspan = 2 align='center'><b>".number_format($pencentualpendente,2,',','.').'%'."</b></td>";
				echo "	<td colspan = 2 align='center'><b>"."</b></td>";
				echo "</tr>";
				//echo "<tr>";
				echo "<td colspan=14 align =center>ULTIMAS FOTOS REALIZADAS POR NÃO CONFORMIDADE</td>";
				//echo "</tr>";
				echo "</table>";
				
				
				
				//Última não conformidade realizda
						$qry_n_baixa->nrw=0;
						$sql_foto = 	"select tbmovimento.idmovimento ,tbmovimento.foto,tbmovimento.dataoperacao,tbmovimento.horaoperacao,tbmovimento.idinterno 
										from tbmovimento where
										tbmovimento.dataoperacao = current_date
										and tbmovimento.foto <> ''
										and (idmotivo <>9 and idmotivo >=1)
										order by tbmovimento.dataoperacao,tbmovimento.horaoperacao desc limit 6
     	 								";
									    $qry_n_baixa->executa($sql_foto);
										
													
				
				
				echo "<table  class = 'psdgraphics-com-table'>";
				for($z=0;$z<$qry_n_baixa->nrw;$z++){
				$qry_n_baixa->navega($z);
					   $idmovimento = $qry_n_baixa->data["idmovimento"];  
					   $datamov = $qry_n_baixa->data["horaoperacao"];
					   $id = $qry_n_baixa->data["idinterno"];
					   //pegando a RA
					   $sql_ra = 	"select nomeentrega from tbenderecoentrega where idinterno = $id";
									 $qry_ra->executa($sql_ra);
									 $ra = substr($qry_ra->data["nomeentrega"],0,20);
										
					   
				       
					   echo "<td width = 16%>";
					   echo "<iframe src='ausente_dm.php?idmovimento=$idmovimento' 
				        width = 100% height = 100% marginwidth=0
						marginheight=0 scrolling=no frameborder=0 align = center
	                   
					   <tr></tr>
					   
					   </iframe>";
					  echo $datamov.' RA: '.$ra;
					   echo "</td>";
					   
					   
 				}
   				
				echo "</table>";
				
				
				
			}

			break;
			
			
		}
?>
	
</form>
</body>
</html>
