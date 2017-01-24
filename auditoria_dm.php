<?php
/**
 * Auditoria DM
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega a configuracao
require_once("inc/config.inc");
require_once("classes/fpdf/fpdf.php");

// seta o parent e redireciona
$_SESSION["PARENT"     ] = false;
$_SESSION["REDIRECIONA"] = false;

// consulta
$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry6 = new consulta($con);
$qry7 = new consulta($con);
$qry8 = new consulta($con);
$qry9 = new consulta($con);
$qry10 = new consulta($con);
$qry11 = new consulta($con);
$qryDigita = new consulta($con);
$qryFatBase = new consulta($con);
$qry_ponto  = new consulta($con);
$qry_login  = new consulta($con);
$qry_n_baixa  = new consulta($con);
$qry_ra  = new consulta($con);
$qry_n_baixa = new consulta($con);
$qry_geo = new consulta($con);

$google_api = "ABQIAAAAho5jFfUGXHNyRjGXbcWXChSBj9dyf3cQ2L7RnBtfd9ot5emaJxSXjDLEdGfNTakQt4_LT2Uduev7AA";

$sql = "SELECT nivelusuario FROM tblogin WHERE codlogin=".$_SESSION["IDUSER"];
$qry->executa($sql);
$nivelusuario = $qry->data["nivelusuario"];

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>

<!-- CSS Local -->
<link href="<?=HOST?>/tablecloth/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />

<!-- JS Local -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script> 

<div style="width: 900px; margin: 0 auto;">
  
<form name="form" action="<?=$selfLink?>" method="post">
<input type="hidden" name="opt" value="B" />
         
<table class="tabela">
<tr>
    <th align=center bgcolor=#eeeeee>
        <input type="button" name="ocorrencia" class="botao" onclick='window.open("ocorrencia.php?opt=I&idinterno=<?=$idinterno;?>&popup=1","Ocorrencia","width=640, height=480, scrollbars=auto, menubar=no, location=no, status=yes, toolbar=no, resizable=yes");' value='Criar Ocorr&ecirc;ncia' />
		<?php
			if ($opt) {
                if ($opt=="S")
                    $link_botao_voltar = $HTTP_REFERER.$_SESSION["sessao_string_retornar"];
                    echo "<input type='button' class='botao' onclick=\"document.location.href='{$link_botao_voltar}'\" value='Voltar para a p&aacute;gina anterior' />";
          }

		?>
    </th>
</tr>
</table>
     
        
     <br> 

	 <table class="tabela">
                  
       <?php
             switch($opt){

                     case("S"):
                     //informacoes completa da encomenda

                     $sql = "SELECT * FROM tbentrega WHERE idinterno = '$idinterno'";
                     $qry->executa($sql);

                     $id_cliente = $qry->data["codcliente"];
					 $id_produto = $qry->data["codigoproduto"];
                     $codigodaregiao = $qry->data["codigodaregiao"];
                     $idexterno = $qry->data["idexterno"];
                     $lote = $qry->data["numlotecliente"];
                     $lotecaminhoteste =  $qry->data["numlotedigital"];
                     $idmotivo = $qry->data['idmotivo'];
                     if(empty($idmotivo))
                        $idmotivo = 0;

                     if($qry->data["numlista"]){
                        $sql = "SELECT tbtipolista.nometipolista FROM tblista, tbtipolista WHERE 
							          tblista.numlista = '".$qry->data["numlista"]."' 
							          AND tblista.codigotipolista=tbtipolista.codigotipolista";
                        $qry2->executa($sql);
                        $lista_nometipo = "(".$qry2->data["nometipolista"].")";
                       }
                       $codbase = $qry->data["codbase"];
                     if($qry->data["codbase"])
                     {
                     $sql = "SELECT nomebase FROM tbbase WHERE codbase = '".$qry->data["codbase"]."'";
                     $qry2->executa($sql);
                     $nomebase = $qry2->data["nomebase"];
                     }
                     $sql = "SELECT nomecliente FROM tbcliente WHERE codcliente = '$id_cliente'";
                     $qry2->executa($sql);
                     $nomecliente = $qry2->data["nomecliente"];

                     $sql = "SELECT nomeproduto FROM tbproduto WHERE codigoproduto = '$id_produto'";
                     $qry2->executa($sql);
                     $nomeproduto = $qry2->data["nomeproduto"];

                     $qry10->executa("SELECT * FROM tbmotivo WHERE idmotivo = $idmotivo");

                     // agencia e conta corrente

                     echo "<tr bgcolor=#eeeeee align=left>
                                 <th>Remetente:</th><th><b>".$nomecliente."</b></th>
                                 <th colspan=10><b>".$nomeproduto."</b></th>
                             </tr>";
                     echo "<tr>
                                 <td colspan=12><br></td>
                             </tr>";
                     $sql = "SELECT
                                       *
                               FROM
                                       tbentrega as entrega, tbenderecoentrega as endereco
                               WHERE
                                       entrega.idinterno = endereco.idinterno and entrega.idinterno='$idinterno'";
                $qry->executa($sql);
                $sql = "SELECT * FROM tbtipomovimento WHERE idtipomovimento = '".$qry->data["idtipomovimento"]."'";
                $qry3->executa($sql);

                $digitador="Arquivo";
               

			
					              					
                        $numloteinterno = $qry->data["numloteinterno"];
                      $link_imprimir = "print_ar_edn_pdf.php?lote=".$qry->data["numloteinterno"]."&ar=".$idinterno;
                      //echo $link_imprimir;
                      //die;
                      $de = $qry->data["nomeentrega"].'('.$qry->data["numconta"].')';
                    
                      echo "<tr bgcolor=#eeeeee>
                                <td>Destinat&aacute;rio:</td>
							    <td colspan='8'><b>".$de."</b></td>
                                <td>Emiss&atilde;o:</td>
							    <td width='20' colspan='2'><b>".mostra_data($qry->data["dataemissao"])."</b></td>
                            </tr>
                             <tr bgcolor=#eeeeee>
                                <td>Endere&ccedil;o:</td>
							    <td colspan='11'><b>".$qry->data["enderecoentrega"]."</b></td>
                             </tr>
                             <tr bgcolor=#eeeeee>
                                <td>Complemento:</td>
								<td colspan='11'><b>".$qry->data["complementoenderecoentrega"]."</b></td>
                                 
                             </tr>
                             <tr bgcolor=#eeeeee>
                                <td>Bairro:</td>
								<td colspan='5'><b>".$qry->data["bairroentrega"]."</b></td>
                                <td>Cidade</td>
								<td><b>".$qry->data["cidadeentrega"]."</b></td>
								<td>UF:</td>
								<td><b>".$qry->data["estadoentrega"]."</b></td>
								<td>CEP:</td>
								<td><b>".$qry->data["cepentrega"]."</b></td>
                             </tr>
                             <tr bgcolor=#eeeeee>
                                 <td>Valor:</td>
								 <td><b>".$qry->data["valorentrega"]."</b></td>
                                 <td>peso:</td>
								 <td><b>".$qry->data["pesoentrega"]."</b></td>
								 <td>Promessa:</td>
								 <td><b>".mostra_data($qry->data["datapromessa"])."</b></td>
								 <td>Coleta:</td>
                                 <td><b>".mostra_data($qry->data["datacoletado"])."</b></td>
								 <td>Setor:</td>
								 <td colspan='3'><b>".$qry->data["numlotecliente"]."</b></td>
                             </tr>
                             <tr bgcolor=#eeeeee>
                                 <td><b><font color=#3333FF>STATUS ATUAL:</font></b></td>";
                  if($qry3->data["idtipomovimento"] == '890'){
                    echo "<td colspan='11'><font color=#3333FF><b>".$qry3->data["status"]." / ".$qry->data["numerosedex"]." (".$qry10->data['motivo'].")</b></font></td>";
                  }else{
                    echo "<td colspan='11'><font color=#3333FF><b>".$qry3->data["status"]." (".$qry10->data['motivo'].")</b></font></td>";
                  }
                 echo "</tr>
                             <tr bgcolor=#eeeeee>
                                 <td>ID Interno:</td>
								 <td colspan='3'><b>".$idinterno."</b></td>
                                 <td>Nota Fiscal:</td>
								 <td colspan='7'><b>".$qry->data["idexterno"]."</b></td>
                             </tr>";

                     //informacoes de cheque e talao


                    

                     $sql = "SELECT * FROM tbentrega WHERE idinterno = '$idinterno'";

                     $qry11->executa($sql);

                    if ($qry11->data["st"]=='E' )
						   	   	{
								        $dte=mostra_data($qry->data["dataentrega"]);
                        $label_data = 'Entrega no Cliente :';
								    }
                    
                    elseif ($qry11->data["st"]=='D' )
						   	   	{
								        $dte=mostra_data($qry->data["dataentrega"]);
                        $label_data = 'Data do Insucesso :';
								    }
                    
                    
							           else
								    {
								         $dte='';	
                         $label_data = '..';  
								    }

                            $sql = "SELECT * FROM tb_easy_courier WHERE nr_encomenda = '$idexterno'";
                            $qry_geo->executa($sql);
                            //echo $sql;
                            
                            $geo=$qry_geo->data["latitude"];
                            $endereco_desejado = urlencode(utf8_encode("$geo"));
                            
                          echo "<tr bgcolor='#eeeeee'>
                                    <td>Entregado:</td>
						            <td colspan='11'><b>".$nomebase."</b></td>
                                </tr>
                                <tr bgcolor='#eeeeee'>
                                    <td>Obs Entrega:</td>
									<td colspan='11'><b>".$qry->data['obsentrega']."</b></td>
                                </tr>
                                <tr bgcolor='#eeeeee'>
                                   <td>GEO Localiza&ccedil;&atilde;o :</td>
								   <td colspan='11'><b>".$qry_geo->data["geo_posicao"]."</b></td>
                                </tr>";
							
							      
                     $qry2->nrw=0;
                     //ocorrencias
                     $sql = "SELECT tbocorrencia.codocorrencia,tbocorrenciatipo.tipo,tbocorrencia.datacriacao,tbocorrencia.assunto,tblogin.nomelogin FROM tbocorrencia, tbocorrenciatipo, tblogin WHERE tbocorrencia.codocorrenciatipo=tbocorrenciatipo.codocorrenciatipo AND tbocorrencia.codlogin=tblogin.codlogin AND tbocorrencia.idinterno='$idinterno' ORDER BY tbocorrencia.codocorrencia";

                     $qry2->executa($sql);
				
					 
                     if ($qry2->nrw){
                             echo "<tr bgcolor='#cccccc'>";
                             echo "    <th><b>Cod. Ocorr&ecirc;ncia</b></th>";
                             echo "    <th><b>Tipo</b></th>";
                             echo "    <th><b>Assunto</b></th>";
                             echo "    <th><b>Data</b></th>";
                             echo "    <th><b>Usu&aacute;rio</b></th>";
                             echo "    <th colspan='7'>&nbsp;</th>";
                             echo "</tr>";

                             for($i=0;$i<$qry2->nrw;$i++){
                                     $qry2->navega($i);
                                     echo "<tr bgcolor=#".(($i%2)?"eeeeee":"ffffff").">";
                                     echo "    <td>".$qry2->data["codocorrencia"]."</td>";
                                     echo "    <td>".$qry2->data["tipo"]."</td>";
                                     echo "    <td>".$qry2->data["assunto"]."</td>";
                                     echo "    <td>".mostra_data($qry2->data["datacriacao"],1)."</td>";
                                     echo "    <td>".$qry2->data["nomelogin"]."</td>";
                                     echo "    <td colspan='7' align='center'><a href='#' onclick=\"javascript:window.open('ocorrencia.php?opt=V&codocorrencia=".$qry2->data["codocorrencia"]."&popup=1','VerOcorrencia','width=640, height=480, scrollbars=yes, menubar=no, location=no, status=yes, toolbar=no, resizable=yes');\">Ver detalhes</a></td>";
                                     echo "</tr>";
                             }
                     }

                     echo "</table>";
                   
                    /********************************  CONSULTA VOLUMES  ********************************************************/

                     $sql = "SELECT * FROM tb_demillus_volumes WHERE idinterno = $idinterno";
                     $qry8->nrw=0;
                     $qry8->executa($sql);
                     if ($qry8->nrw){
                      ?>
                       <table class="tabela">
                       <tr><th>CONSULTA DE VOLUMES</th></tr>
                       </table>
                      <?php
                      echo "<table class='tabela'>";
                      echo "<tr>";
                      for($i=0;$i<$qry8->nrw;$i++){
                          $qry8->navega($i);
                          echo "<td>".$qry8->data["num_caixa"]."</td>";
                          if($qry8->data["status"] == 'P')
                            echo "<td><span style='color:red'>Em Pend&ecirc;ncia</span></td>";
                          else
                            echo "<td>Lido</td>";
                      }
                      echo "</tr>";
                      echo "</table>";
                     }

                              
                    /*****************************   TRACKING  **********************************************************/
                           
                            $sql = "SELECT
                           *
                           FROM
                           tbmovimento as mov, tbtipomovimento as tp
                           WHERE
                           mov.idinterno = '$idinterno' and
                           tp.idtipomovimento = mov.idtipomovimento
                           ORDER BY mov.idmovimento,
                           mov.dataoperacao,mov.horaoperacao";
                           $qry2->executa($sql);
                           //echo $sql;
                             
                             
                                   
                     
                     
                     
                     if ($qry2->nrw){
                             
                             ?>
                             <table class="tabela">
                             <tr><th>T R A C K I N G</th></tr>
                             </table>
                             
                             <table class="tabela">
							 <?php
                             echo "<tr bgcolor=#cccccc align=left>
                                   <th><b>Movimenta&ccedil;&atilde;o</th>
                                    <th><b>Lista</th>
                                    <th><b>OBS</th>
                                   <th><b>Data</th>
                                   <th><b>Usu&aacute;rio</th>
                                   <th><b>Origem</th>
                                   <th><b>Destino</b</th>
                                   <th><b>Geo Localiza&ccedil;&atilde;o</th>
                                   </tr>";
								 



                             for($i=0;$i<$qry2->nrw;$i++){
                                     $qry2->navega($i);

                                      $iddd  = $qry2->data["idmovimento"];
                                      
                                      $sql = "SELECT latitude,longitude from tbmovimento  WHERE idmovimento = '$iddd'";
                                      $qry_geo->executa($sql);
									  $latitude  = $qry_geo->data["latitude"];
									  $longitude = $qry_geo->data["longitude"];
                                      $geo=$qry_geo->data["latitude"].' '.$qry_geo->data["longitude"];
                                      $geo_ok = urlencode(utf8_encode("$geo"));
                                      //echo $geo_ok;
                                      //die;
                                      
                                      
                                      $bbb_base  = $qry2->data["codbase"];
                                      $bbb_login = $qry2->data["codlogin"];
                                      if(!$bbb_base) $bbb_base =1;
                                      
                                         
                                     
                                  


                                     $sql3 = "SELECT * FROM tbbase WHERE codbase = $bbb_base";
                                     $qry3->executa($sql3);
                                     
                                    

                                     if ($qry2->data["codbasedestino"]){
                                             $sql5 = "SELECT nomebase FROM tbbase WHERE codbase = '".$qry2->data["codbasedestino"]."'";
                                             $qry5->executa($sql5);
                                     }



                                       $sql5 = "SELECT * FROM tblogin WHERE codlogin = $bbb_login";
                                             $qry_login->executa($sql5);
                                     

									$sql6 = "SELECT idexterno FROM tbentrega WHERE idinterno = '$idinterno'";
									
                                     $qry6->executa($sql6);	
									 $idd2 = $qry6->data["idexterno"];
									 
									 
								      $sql6 = "SELECT idmotivo FROM tbmovimento WHERE idinterno = '$idinterno'";
									
                                     $qry6->executa($sql6);	
									 $_id_motivo = $qry6->data["idmotivo"];
									 
									 if(!$_id_motivo) $_id_motivo=999;
									 
									 $sql6 = "SELECT nome_ocorrencia FROM tb_sabo_ocorr WHERE id_ocorrencia = '$_id_motivo'";
									
                                     $qry6->executa($sql6);	
									 $_nome_motivo = $qry6->data["nome_ocorrencia"];
									 
									
                                     if($qry2->data["numlista"])
                                      {  
                                     $sql6 = "SELECT * FROM tblista WHERE numlista = '".$qry2->data["numlista"]."'";
                                     $qry6->executa($sql6);
                                      }
                                     $str = "</td><td>".$qry2->data["numlista"]."";

                                      $id_mov = $qry2->data["idtipomovimento"];
                                      $nome_mov = $qry2->data["nometipomovimento"].' '.$_nome_motivo;
                                      $idd = $qry2->data["idmovimento"];
                                      
									  
                                    
                                 
                                             $str.="</td><td>&nbsp; ".$qry2->data["idtipomovimento"];
                                             
                                       

									                           if(!$str)
                                              $str='.';
									                        
                                            if($id_mov==803 or $id_mov==160 or $id_mov==500)
                                              {
                                               //verifica se tem foto
											   $sql = "select foto from tb_easy_courier where 
												nr_encomenda = '$idd2'  ";
												//echo $sql;
                                                $qry_n_baixa->executa($sql);
												$tem_foto = $qry_n_baixa->data["foto"];
												
												if(strlen($tem_foto)>10 )
													$obs_foto = 1;
												
												
											   echo "<td><a href='mostra_imagens_ausente.php?opt=S&idd2=".$idd2."'>".$nome_mov."</a></td>";
                                               echo "<td>".$qry2->data["numlista"]."</td>";
                                             
											if($obs_foto==1)
											
										     echo "<td><a href='mostra_imagens_ausente.php?opt=S&idd2=".$idd2."'>OPA!!! TEMOS A FOTO DO LOCAL</a></td>";
									            	ELSE
											 echo "<td>".$qry2->data["numlista"]."</td>";
                                               
                                               
                                               } 
                                            else
                                               echo "<td>".$nome_mov.$str."</td>";    
                                                    
                                            
                                            echo "<td>".mostra_data($qry2->data["dataoperacao"])." ".$qry2->data["horaoperacao"]."</td>";
                                            echo "<td>".$qry_login->data["nomelogin"]."</td>";
                                            
											
												echo "<td>".$qry3->data["nomebase"]."</td>";
                        echo "<td>".$qry5->data["nomebase"]."</td>";

                                            // monta o link para o MAPA
											$mapLink = HOST."/movimentacao.php?iddd={$iddd}&token={$rnd}";

											// mostra as coordenadas
											if (!empty($latitude)) { echo "<td><a href='{$mapLink}'>{$geo_ok}</a></td>"; }
											else                   { echo "<td>{$geo_ok}</td>"; }
											
											echo "</tr>";
                             }

                                                                // TELEMARKETING
                             $sql = "SELECT * FROM tbrespostatele WHERE idinterno = '$idinterno'";
                             $qry->executa($sql);

                         


                          //Última não conformidade realizda
						$qry_n_baixa->nrw=0;
						$sql_foto = 	"select tbmovimento.idmovimento ,tbmovimento.foto,tbmovimento.dataoperacao,
						                tbmovimento.horaoperacao,tbmovimento.idinterno 
										from tbmovimento where
										tbmovimento.idinterno = $idinterno
										and tbmovimento.foto <> ''
										and (idmotivo <>9 and idmotivo >=1)
										order by tbmovimento.dataoperacao,tbmovimento.horaoperacao desc limit 6
     	 								";
									    $qry_n_baixa->executa($sql_foto);
										
										//echo $sql_foto;
				
				
				echo "<table>";
				for($z=0;$z<$qry_n_baixa->nrw;$z++){
				$qry_n_baixa->navega($z);
					   $idmovimento = $qry_n_baixa->data["idmovimento"];  
					   $datamov = $qry_n_baixa->data["horaoperacao"];
					   $datamov2 = $qry_n_baixa->data["dataoperacao"];
					   $id = $qry_n_baixa->data["idinterno"];
					   //pegando a RA
					   $sql_ra = 	"select nomeentrega from tbenderecoentrega where idinterno = $id";
									 $qry_ra->executa($sql_ra);
									 $ra = substr($qry_ra->data["nomeentrega"],0,20);
										
					   
				       
					   echo "<td width = 50%>";
					   echo "<iframe src='ausente_dm.php?idmovimento=$idmovimento' 
				        width = 100% height = 100% marginwidth=0
						marginheight=0 scrolling=no frameborder=0 align = center
	                   
					   <tr></tr>
					   
					   </iframe>";
					  echo mostra_data($datamov2).'-'.$datamov.' RA: '.$ra;
					   echo "</td>";
					   
					   
 				}                              
                       

                    if($id_cliente == '6670' OR $id_cliente == '6671'){     
                   ?>         

                     <input type="button" name="ar" class="botao" onclick='window.open("exibe_pdf.php?opt=I&idexterno=<?=$idexterno;?>");' value='Aviso de Entrega Digitalizado '>
  	
                     <input type="button" name="ar" class="botao" onclick='window.open("exibe_pdf_demillus.php?opt=I&idexterno=<?=$idexterno;?>");' value='VER AR - DATA CERTA '>
                     
                    
                     <?php
                   }else{
                    ?>
                     <input type="button" name="ar" class="botao" onclick='window.open("print_ar_edn_pdf.php?codbase=<?=$codbase?>&codcliente=<?=$id_cliente?>&lote=<?=$numloteinterno?>&ar=<?=$idinterno?>");' value='VER AR - DATA CERTA '>
                    <?php
                   }

                     }

                     
                     else
                     echo "<tr bgcolor=#cccccc>
                                     <td colspan=50 align=center><b>Nenhum movimento nesta encomenda</td>
                                 </tr>";

                     

 
					 

					 
        
				
        
     
					
                     break;

                                         //*******************************************************

                     

                   
             }
			 
			 
           ?>

       
		              
                                <?php
								
                                
								                 
								 //botao voltar
                                
             
                 
                

								              
								                 
								                 
								                 
								               
								                 
        ?>
				
				
             
  </table>
  </form>
  
 
</div>  
  
 <?php
// pega o Footer
require_once("inc/footer.inc");