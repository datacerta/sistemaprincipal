<?php
/**
 * Fatura Demillus Base - Include
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variaveis
$local = 2;

// pega a configuracao
require_once("../../inc/config.inc");

// verifica se nao eh header
if (!$PARENT) {
	// seta o redireciona
	$_SESSION["REDIRECIONA"] = true;

	// carrega os campos
	require_once("redireciona.php");
	
	// seta o link da action
	$redi = HOST."/fatura_demillus_base.php?idmenu=1&token={$rnd}";
	// link de redirecionamento
	echo "<form action='{$redi}' name='fRedi' method='post'>\n";
	echo "<input type='hidden' name='data_de'        value='{$data_de}' />\n";
    echo "<input type='hidden' name='data_ate'       value='{$data_ate}' />\n";
    echo "<input type='hidden' name='tipo_data'      value='{$tipo_data}' />\n";
    echo "<input type='hidden' name='lote_de'        value='{$lote_de}' />\n";
    echo "<input type='hidden' name='lote_ate'       value='{$lote_ate}' />\n";
    echo "<input type='hidden' name='tipo_lote'      value='{$tipo_lote}' />\n";
    echo "<input type='hidden' name='codbase'        value='{$codbase}' />\n";
    echo "<input type='hidden' name='codcliente'     value='{$codcliente}' />\n";
    echo "<input type='hidden' name='codigoproduto'  value='{$codigoproduto}' />\n";
    echo "<input type='hidden' name='opt'            value='{$opt}' />\n";
    echo "<input type='hidden' name='ver_detalhes'   value='{$ver_detalhes}' />\n";
    echo "<input type='hidden' name='numlotecliente' value='{$numlotecliente}' />\n";
    echo "<input type='hidden' name='dataemissao'    value='{$dataemissao}' />\n";
    echo "<input type='hidden' name='tot_lote'       value='{$tot_lote}' />\n";
	echo "</form>\n";
	
    // redireciona
	echo "<script type='text/javascript'>document.fRedi.submit();</script>\n";

	// finaliza o script
	exit();
}

// consulta
$qry       = new consulta($con);
$qry2      = new consulta($con);
$qry3      = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry_cli   = new consulta($con);
$qry_preco = new consulta($con);

if(isset($campanha))
  $campanha = (int)$campanha;
// seta o link atual
$selfLink = HOST."/includes/fatura-demillus-base/lista.php?idmenu={$idmenu}&token={$rnd}";
?>

<!-- CSS Local -->
<link href="<?=HOST?>/css/table_2.css" rel="stylesheet" type="text/css" />
<link href="<?=HOST?>/css/tip.css"     rel="stylesheet" type="text/css" />
<style>
.tabela td{
  font-size:12px;
}
</style>
<div style="width: 900px; margin: 0 auto;">

<?php
/* $sql = "SELECT nometransportadora FROM tbtransportadora WHERE idtransportadora=".$_SESSION["IDTRANSP"];
$qry->executa($sql);
$nometransportadora = $qry->data["nometransportadora"]; */
?>
              <form name='form_pesquisa' action="<?=$selfLink?>" method="post">
              <input type='hidden' name='opt' value='P'>
              <input type='hidden' name='ver_detalhes' value=''>
              <input type='hidden' name='numlotecliente' value=''>
              <input type='hidden' name='dataemissao' value=''>
              <input type='hidden' name='tot_lote' value=''>

              <table class="tabela" style="width: 100%; border: none;">
                     <tr bgcolor=#eeeeee>
                     <!--<td align='center'><b>Transportadora</b></td>-->
                     <td align='left'><b>Base</b></td>
                     <td align='left'><b>Campanha</b></td>
                     <td align='left'><b>Ano</b></td>
                     <td>Resumo</td>
                     <td></td>
                     </tr>
                     <tr valign="middle" bgcolor=#eeeeee>
                     <td align="center"><select name='codbase' style='width:300px;'><?php combo("SELECT tb.codbase,tb.nomebase FROM tbdireitoauditoria as dir, tbbase as tb  WHERE dir.codbase = '".$_SESSION['IDBASE']."' and tb.ativa = 'S' and tb.idtransportadora='".$_SESSION['IDTRANSP']."' and tb.codbase = dir.codbasedireito ORDER BY tb.nomebase",$codbase,"T") ;?></select></td>
                     <td><input type=text size=15 name='campanha' maxlength="2" value="<?php echo $campanha;?>"></td>
                     <td><input type=text size=15 name='ano' maxlength="4" value="<?php echo $ano;?>"></td>
                     <td><input type=checkbox name='resumo' value="1"/><label>SIM</label></td>
                     <td align="center"> <input type="submit"  name="submete" value="OK"></td>
                     </tr>
                     </table>
                     </form>
					 <br />
                <?php
                Switch($opt){

                        case "P":
        /*****************************************************************
        CHECA SE O RESUMO NÃO FOI SELECIONADO
        ******************************************************************/
        if($_POST['resumo'] != 1){          
                        $tipo_data='dataemissao';
                        $where = "tbentrega.idtransportadora=1";
                        $where = "tbentrega.idtransportadora=1";
                        $where .= " AND tbentrega.codbase = '$codbase'";
                        $where.= " AND (tbentrega.codcliente=6670 OR tbentrega.codcliente=6671)";
						
						$where_2 = $where;
						
					    if($codigoproduto > 0){
                            $where.= " AND tbentrega.codigoproduto='$codigoproduto'";
							$where_2 .= $where;
						}
						
                        if($campanha!="NULL" && $ano != 'NULL')
                            $where.= " AND tbentrega.numloteinterno = '".$campanha."/".$ano."'";

                        if($lote_de > 0){
                           $where.= " AND tbentrega.$tipo_lote >= '".$lote_de."'";
						   $where_2 .= " AND tbentrega.$tipo_lote >= '".$lote_de."'";
						  }

                        if($lote_ate > 0){
                           $where.= " AND tbentrega.$tipo_lote <= '".$lote_ate."'";
						   $where_2 .= " AND tbentrega.$tipo_lote <= '".$lote_ate."'";
						}
                    
                        $audita=$where;

                        $qry->nrw = 0;

                        
                        $sql = "SELECT COUNT(tbentrega.idinterno) as tot_lote, 
                                tbentrega.numlotecliente,tbentrega.dataemissao 
                                FROM tbentrega WHERE $where GROUP BY tbentrega.numlotecliente,
                                tbentrega.dataemissao ORDER BY tbentrega.$tipo_data,
                                tbentrega.numlotecliente";
                                $qry->executa($sql);
                        //echo $sql;
                       //die;
                       //echo "<br>";

                        if(isset($_POST['submete'])){
                                echo "<table class='tabela' style='width: 100%;'>";
                                echo "<tr bgcolor=#eeeeee>";
                                echo "<td>Valor</td>";
                                echo "<td>Voucher</td>";
                                echo "<td>Historico</td>";
                                echo "<td colspan='2'>Gravar</td>";
                                echo "</tr>";                    
                                echo "<tr bgcolor=#eeeeee>";
                                echo "<td><input type='text' name='valorcomb' class='valorcomb' /></td>";
                                echo "<td><input type='text' name='voucher' class='voucher' /></td>";
                                echo "<td><input type='text' name='historico' class='historico' /></td>";
                                echo "<td><button class='btn-geracomb'>Gera Combustivel</button></td>";
                                echo "<td><button class='btn-geracombb'>Gerar Extras</button></td>";
                                echo "</tr>";
                                echo "</table><br><br>";
                        }

                                              
                        if($qry->nrw){

                                echo "<table class='tabela' style='width: 100%;'>";
                                echo "<tr bgcolor=#cccccc>";          // cor cinza + forte
                                echo "        <td align='center'><b>Data</b></td>";
                                echo "        <td align='center'><b>Setor</b></td>";
                                echo "        <td align='center'><b>Lista</b></td>";
                                echo "        <td align='center'><b>Notas</b></td>";
                                echo "        <td align='center'><b>Mobile</b></td>";
                                echo "        <td align='center'><b>Lib. Pagamento</b></td>";
                                echo "        <td align='center'><b>%</b></td>";
                                echo "        <td align='center'><b>Dev/Foto</b></td>";
                                echo "        <td align='center'><b>Pend.</b></td>";
								echo "        <td align='center'><b>N&atilde;o Dig.</b></td>";
                                echo "        <td align='center'><b>Tarifa.</b></td>";
                                echo "        <td align='center'><b>Total</b></td>";
                               
                                echo "</tr>";

                                for($i=0;$i<$qry->nrw;$i++){
                                        $qry->navega($i);


										//DADOS BANCARIOS DA BASE
										$sql2 = "SELECT * FROM tbbase where codbase = $codbase";
                                        $qry2->executa($sql2);
                                        $banco = $qry2->data["banco"];
                                        $agencia = $qry2->data["agencia"];
                                        $conta = $qry2->data["conta"];
                                        $cnpj = $qry2->data["cnpj"];     
                                        $cpf = $qry2->data["cpf"];
                                        $favorecido = $qry2->data["favorecido"];
                                        $dataemissao = $qry->data["$tipo_data"];
                                        $tot_lote = $qry->data["tot_lote"];
                                        $setor = $qry->data["numlotecliente"];
                                        $numlistaq = "";
                                        $totalfoto = 0;
                                        $mobile = 0;
                                        
                                        $valor_tarifa_total = 0;
                                        $sql = "SELECT count(DISTINCT idexterno) as totalfoto FROM tbentrega,tb_easy_courier WHERE $where AND (tbentrega.st='D' or tbentrega.st='A' or tbentrega.st='T' or tbentrega.idtipomovimento = 221 or tbentrega.idtipomovimento = 811 or tbentrega.idtipomovimento = 783) AND numlotecliente = '".$setor."' AND dataemissao = '$dataemissao' AND tb_easy_courier.nr_encomenda = tbentrega.idexterno";
                                        $qry4->executa($sql);
                                        $totalfoto = $qry4->data['totalfoto'];  

                                        $sql = "SELECT count(DISTINCT idexterno) as totalnextel FROM tbentrega WHERE $where AND data_baixa_nextel IS NOT NULL AND numlotecliente = '".$setor."' AND dataemissao = '$dataemissao'";
                                        $qry4->executa($sql);
                                        $mobile = $qry4->data['totalnextel']; 
                                        

                                       $sql = "SELECT numlista 
                                              FROM tbentrega WHERE $where AND numlotecliente = '".$setor."' GROUP BY tbentrega.numlista";
                                              $qry4->executa($sql);  
                                        for($k=0;$k<$qry4->nrw;$k++){
                                          $qry4->navega($k);
                                          $numlistaq .= $qry4->data["numlista"]." ";
                                        }
                                        
                                       
                                        $where_lote_atual = " AND tbentrega.$tipo_data='$dataemissao'";


                                        echo "<tr ".(($i%2==0)?"":"bgcolor=#eeeeee").">";    // cor cinza + fraco

                                        //echo "<tr>";
                                       
                                        echo "        <td align='center'>".mostra_data($dataemissao)."</td>";
                                        
                                        echo "        <td align='center'>".$setor."</td>";
                                        echo "        <td align='center'>".$numlistaq."</td>";
                                        echo "        <td align='center'>".$tot_lote."</td>";

                                        echo "        <td align='center'>".$mobile."</td>";

                                        $tot_entrega = 0;
                                        $sql2 = "SELECT COUNT(tbentrega.idinterno) 
                                                 as tot_entrega FROM tbentrega WHERE $where_2 
                                                 AND tbentrega.st='E' and  (idtipomovimento in (222,781) or idmotivo = 9) and numlotecliente = '$setor' 
												 $where_lote_atual";

                                        $qry2->executa($sql2);
                                      
                                        $tot_entrega = $qry2->data["tot_entrega"];

                                        $tot_devolvido = 0;
                                        $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot_devolvido FROM tbentrega 
                                               WHERE $where_2 AND (tbentrega.st='D' or tbentrega.st='A' or tbentrega.st='T' or tbentrega.idtipomovimento = 221 or tbentrega.idtipomovimento = 811 or tbentrega.idtipomovimento = 783) 
											   and numlotecliente = '$setor'  $where_lote_atual";
                                         //echo sql2;
										 //echo "<br>"; 
                                        $qry2->executa($sql2);
                                        $tot_devolvido = $qry2->data["tot_devolvido"];
      

										$tot_pendente=0;
	                                    $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot_pendente FROM tbentrega 
                                               WHERE $where_2 AND (tbentrega.st isnull ) 
											   and numlotecliente = '$setor'  	$where_lote_atual";
										//ECHO  $sql2;
                                        
										$qry2->executa($sql2);
                                        $tot_pendente = $qry2->data["tot_pendente"];
										
										
										$tot_nao_digi=0;
	                                    $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot_nao_digi FROM tbentrega 
                                               WHERE $where_2 AND (tbentrega.st='E' and 
											   tbentrega.idtipomovimento <> 222 and tbentrega.idtipomovimento <> 221 and tbentrega.idmotivo <> 9) 
											   and numlotecliente = '$setor'  	$where_lote_atual";
										//ECHO  $sql2;
                                        
										$qry2->executa($sql2);
                                        $tot_nao_digi = $qry2->data["tot_nao_digi"];
	  
	  
	  
                                       
                                        $ie = "Conta";
                                        $tot_ie = 0;
                                        
                                         //Pegando a Tarifa 
                                        $lote_int = abs($where_lote_atual);
                                        $sql = "SELECT * from tb_preco_demillus where setor = '$setor'";  
                                        $qry_preco->executa($sql);
                                       // echo $sql;
                                        $valor_tarifa = $qry_preco->data["repasse"];
                                        $tot_entrega = $tot_entrega+$totalfoto;
                                        $valor_tarifa_total =   $valor_tarifa * $tot_entrega;
                                        $total_a_pagar = $total_a_pagar + $valor_tarifa_total;
                                        
                                        

                                        echo "<td align='center'>".$tot_entrega."</td>";
                                        echo "<td align='center'>".number_format(round(($tot_entrega/$tot_lote)*100,2),2,",",".")."</td>";
                                       
									   echo "<td align='center'><a href=\"$selfLink&&campanha=$campanha&ano=$ano&tipo_data=
									         $tipo_data&lote_de=$lote_de&lote_ate=$lote_ate&tipo_lote=$tipo_lote&codbase=
											 $codbase&codcliente=$codcliente&codigoproduto=
											 $codigoproduto&opt=D&ver_detalhes=devolvidos&numlotecliente=
											 $numlotecliente&dataemissao=$dataemissao&tot_lote=
											 $tot_lote\" onclick=\"javascript:document.form_pesquisa.opt.value='D';
											 document.form_pesquisa.ver_detalhes.value='devolvidos';
											 document.form_pesquisa.numlotecliente.value=
											 '$numlotecliente';document.form_pesquisa.dataemissao.value=
											 '$dataemissao';document.form_pesquisa.tot_lote.value='$tot_lote';
											 document.form_pesquisa.submit();\">".$tot_devolvido." / ".$totalfoto."</a></td>";

										                    
                                        //pendentes
                                        echo " <td align='center'> <a href=\"{$selfLink}&campanha=$campanha&ano=$ano&tipo_data=$tipo_data&lote_de=
                                        $lote_de&lote_ate=$lote_ate&tipo_lote=$tipo_lote&codbase=$codbase&codcliente=$codcliente&codigoproduto=
                                        $codigoproduto&opt=D&ver_detalhes=pendentes&numlotecliente=$numlotecliente&dataemissao=
                                        $dataemissao&tot_lote=$tot_lote\" onclick=\"javascript:document.form_pesquisa.opt.value='D';
                                        document.form_pesquisa.ver_detalhes.value='devolvidos';
                                        document.form_pesquisa.numlotecliente.value='$numlotecliente';
                                        document.form_pesquisa.dataemissao.value='$dataemissao';
                                        document.form_pesquisa.tot_lote.value='$tot_lote';
										document.form_pesquisa.submit();\">".$tot_pendente."</td>";
										
										
										  //não digitados
								echo " <td align='center'> <a href=\"{$selfLink}&campanha=$campanha&ano=$ano&tipo_data=$tipo_data&lote_de=
                                        $lote_de&lote_ate=$lote_ate&tipo_lote=$tipo_lote&codbase=$codbase&codcliente=$codcliente&codigoproduto=
                                        $codigoproduto&opt=D&ver_detalhes=nao_dititalizado&numlotecliente=$numlotecliente&dataemissao=
                                        $dataemissao&tot_lote=$tot_lote\" onclick=\"javascript:document.form_pesquisa.opt.value='D';
                                        document.form_pesquisa.ver_detalhes.value='nao_dititalizado';
                                        document.form_pesquisa.numlotecliente.value='$numlotecliente';
                                        document.form_pesquisa.dataemissao.value='$dataemissao';
                                        document.form_pesquisa.tot_lote.value='$tot_lote';
										document.form_pesquisa.submit();\">".$tot_nao_digi."</td>";
                                       
                                        echo "<td align='center'>".number_format(round(($valor_tarifa),2),2,",",".")."</td>";
                                        echo "<td align='center'>".number_format(round(($valor_tarifa_total),2),2,",",".")."</td>";
                                       
                                        
                                        $total_tot_lote = $total_tot_lote + $tot_lote;
                                        $total_tot_entrega =  $total_tot_entrega + $tot_entrega;
                                        $total_tot_devolvido = $total_tot_devolvido + $tot_devolvido;
                                        $total_tot_pendente = $total_tot_pendente + $tot_pendente;
										$total_nao_digita = $total_nao_digita + $tot_nao_digi;
                                        $total_tot_ie = $total_tot_ie + $tot_ie;
                                        $total_mobile = $total_mobile +$mobile;
                                        

                                        echo "</tr>";
                                }
                                
                                echo "<tr>";
                                

                                //totalizador
                                echo "<tr bgcolor=#dddddd>";          // cor cinza +- forte
                                echo "<td align='center'><b>".$porbase."<b>Totais</b></td>";
                                echo "<td align='center'><b></b></td>";
                                echo "<td></td>";
                                echo "<td align='center'><b>".$total_tot_lote."</b></td>";
                                echo "<td align='center'><b>".$total_mobile."</b></td>";                          
                                echo "<td align='center'><b>".$total_tot_entrega."</b></td>";
                                echo "<td align='center'><b>".number_format(round(($total_tot_entrega/$total_tot_lote)*100,2),2,",",".")."</b></td>";
                                echo "<td align='center'><b>".$total_tot_devolvido."</b></td>";
                                echo "<td align='center'><b>".$total_tot_pendente."</b></td>";
								 echo "<td align='center'><b>".$total_nao_digita."</b></td>";
                                echo "<td align='center'><b>".number_format(round(($total_tot_pendente/$total_tot_lote)*100,2),2,",",".")."</b></td>";
                                echo "<td align='center'>".number_format(round(($total_a_pagar),2),2,",",".")."</td>";
                                echo "</tr>";
                                echo "<tr>";
                                $sql = "SELECT COUNT(tbentrega.idinterno) as tot, tbentrega.idtipomovimento  
                                FROM tbentrega WHERE $audita GROUP BY tbentrega.idtipomovimento ";
                                $qry->executa($sql);
                                // echo $audita;
                                
                                
                               
                                
                                if ($qry->nrw){
                                   echo "<tr>
                                   <th>Quantidade</th>
                                   <th  colspan=8 >Status</th>
                                   </tr>";

                                   For($i=0;$i<$qry->nrw;$i++){
                                      $qry->navega($i);
                                      $sql2 = "SELECT nometipomovimento FROM tbtipomovimento WHERE idtipomovimento = '".$qry->data["idtipomovimento"]."'";
                                      $qry2->executa($sql2);
                                      echo "<tr bgcolor=#".(($i%2)?"eeeeee":"ffffff").">
                                      <td>".$qry->data["tot"]."</td>
                                      <td colspan=11>".$qry2->data["nometipomovimento"]."</td>
                                      </tr>";      //S2
                                   }

							                    
                                 
                                 
                                 }           


                                 
                                echo "<tr class='adddescontos'>";
                                $sql2 = "SELECT * FROM 
                                         tb_demillus_extra 
                                         where 
                                         codbase = $codbase
                                         AND (campanha = '".$campanha."/".$ano."'
                                         OR campanha = '".str_pad($campanha, 2, "0", STR_PAD_LEFT)."/".$ano."')";
                                          
                              
                                //die;
                                
                                $qry2->executa($sql2);
                                echo "<td align='left'><b>DATA</b></td>";
                                echo "<td align='left'><b>VALOR</b></td>";
                                echo "<td align='left'><b>VOUCHER</b></td>";
                                echo "<td align='left' ><b>USUARIO</b></td>";
                                echo "<td align='left' colspan=9 ><b>HISTORICO</b></td>";
                                echo "<tr>";
                                for($i=0;$i<$qry2->nrw;$i++){
                                        $qry2->navega($i);
                                         $data_e = $qry2->data["data"];
                                         $valor_e = $qry2->data["valor"];
                                         $voucher_e = $qry2->data["voucher"];
                                         $hist_e = $qry2->data["historico"];
                                         $usuario_e = $qry2->data["usuario"];
                                         
                                         echo "<td align='left'><b>".mostra_data($data_e)."</b></td>";
                                         echo "<td align='left'><b>".number_format($valor_e,2)."</b></td>";
                                         if(!empty($voucher_e)){
                                          echo "<td align='left'><b>".$voucher_e."</b></td>";
                                            echo "<td align='left' ><b>".$usuario_e."</b></td>";
                                            echo "<td align='left' colspan=7 ><b>".$hist_e."</b></td>";
                                            echo "<td><a class='' target='_blank' href='".HOST."/fatura_demillus_base-pdf.php?valor=".number_format(str_replace('-','',$valor_e),2, ',', '')."&voucher=".$voucher_e."'>Imprimir</a></td>";
                                         }else{
                                            echo "<td align='left' ></td>";
                                            echo "<td align='left' ><b>".$usuario_e."</b></td>";
                                            echo "<td align='left' colspan=9><b>".$hist_e."</b></td>";
                                         }                                       
                                         echo "<tr>";
                                         $tvalor_e = $tvalor_e + $valor_e;
                                        }
                                
                                       echo "<tr>";
                                       echo "<td align='left'><b>TOTAL DE EXTRAS</b></td>";
                                      
                                       echo "<td align='left' colspan=11><b class='totalex'>".number_format($tvalor_e,2, ',', '.')."</b></td>";
                                       echo "<tr>";
                                       echo "<td align='left'><b>TOTAL LIQUIDO</b></td>";
                                      
                                       echo "<td align='left' colspan=11><b class='totalliq' style='font-size:16px'>".number_format($tvalor_e + $total_a_pagar,2, ',', '.')."</b></td>";
                                
                                
                                
                                
                                echo "<tr>";
                                echo "<tr>";
                                echo "<th align='left' colspan=10><b></b></th>";
                                echo "<tr>";
                                echo "<tr>";
                                
                                
                                
                                echo "<tr>";
                                echo "<th align='left' colspan=11><b>DADOS BANCARIOS</b></th>";
                                echo "<tr>";
                                echo "<td align='left'><b>BANCO</b></td>";
                                echo "<td align='left' colspan=11><b>".$banco."</b></td>";
                                
                                echo "<tr>";
                                echo "<td align='left'><b>AGENCIA</b></td>";
                                echo "<td align='left' colspan=11><b>".$agencia."</b></td>";
                                
                                echo "<tr>";
                                echo "<td align='left'><b>CONTA</b></td>";
                                echo "<td align='left' colspan=11><b>".$conta."</b></td>";
                                
                                echo "<tr>";
                                echo "<td align='left'><b>CNPJ</b></td>";
                                echo "<td align='left' colspan=11><b>".$cnpj."</b></td>";
                                
                                echo "<tr>";
                                echo "<td align='left'><b>CPF</b></td>";
                                echo "<td align='left' colspan=11><b>".$cpf."</b></td>";
                                
                                echo "<tr>";
                                echo "<td align='left'><b>FAVORECIDO</b></td>";
                                echo "<td align='left' colspan=11><b>".$favorecido."</b></td>";
                                echo "</tr>";
                                echo "<tr><tdcolspan='11'></td></tr>";
                                echo "</table>";
								echo "<br />";




                                 //Agrupa por clientes

                                 $sql = "SELECT tbentrega.codcliente
								         FROM tbentrega
										 WHERE {$audita}
										 GROUP BY tbentrega.codcliente";
                                $qry->executa($sql);
                                // echo $sql;
                                
                               
                               
                                
                                 





                        }else{
								echo "<table class='tabela' style='width: 100%;'>";
                                echo "<tr class='adddescontos'>";
                                $sql2 = "SELECT * FROM 
                                         tb_demillus_extra 
                                         where 
                                         codbase = $codbase
                                         AND (campanha = '".$campanha."/".$ano."'
                                         OR campanha = '".str_pad($campanha, 2, "0", STR_PAD_LEFT)."/".$ano."')";
                                          
                              
                                //die;
                                
                                $qry2->executa($sql2);
                                echo "<td align='left'><b>DATA</b></td>";
                                echo "<td align='left'><b>VALOR</b></td>";
                                echo "<td align='left'><b>VOUCHER</b></td>";
                                echo "<td align='left' ><b>HISTORICO</b></td>";
                                echo "<td align='left' colspan=9 ><b>USUARIO</b></td>";
                                echo "<tr>";
                                for($i=0;$i<$qry2->nrw;$i++){
                                        $qry2->navega($i);
                                         $data_e = $qry2->data["data"];
                                         $valor_e = $qry2->data["valor"];
                                         $voucher_e = $qry2->data["voucher"];
                                         $hist_e = $qry2->data["historico"];
                                         $usuario_e = $qry2->data["usuario"];
                                         
                                         echo "<td align='left'><b>".mostra_data($data_e)."</b></td>";
                                         echo "<td align='left'><b>".number_format($valor_e,2)."</b></td>";
                                         if(!empty($usuario_e)){
                                          echo "<td align='left'><b>".$voucher_e."</b></td>";
                                            echo "<td align='left' ><b>".$hist_e."</b></td>";
                                            echo "<td align='left' colspan=7 ><b>".$usuario_e."</b></td>";
                                            echo "<td><a class='' target='_blank' href='".HOST."/fatura_demillus_base-pdf.php?valor=".number_format(str_replace('-','',$valor_e),2, ',', '')."&voucher=".$voucher_e."'>Imprimir</a></td>";
                                         }else{
                                            echo "<td align='left' colspan=10><b>".$hist_e."</b></td>";
                                         }                                       
                                         echo "<tr>";
                                         $tvalor_e = $tvalor_e + $valor_e;
                                        }
                                
                                       echo "<tr>";
                                       echo "<td align='left'><b>TOTAL DE EXTRAS</b></td>";
                                      
                                       echo "<td align='left' colspan=11><b class='totalex'>".number_format($tvalor_e,2, ',', '.')."</b></td>";
                                       echo "<tr>";
                                       echo "<td align='left'><b>TOTAL LIQUIDO</b></td>";
                                      
                                       echo "<td align='left' colspan=11><b class='totalliq' style='font-size:16px'>".number_format($tvalor_e + $total_a_pagar,2, ',', '.')."</b></td>";
									echo "</table>";
						}
                    /**********************************************************
                    CHECA SE O RESUMO FOI SELECIONADO
                    ***********************************************************/
                    }else{
                        $where = "tbentrega.idtransportadora=1";
                        $where.= " AND tbentrega.codcliente=6670";
                        $arrBases = array();
                        
                        if($codigoproduto > 0){
                                        $where.= " AND tbentrega.codigoproduto='$codigoproduto'";
                        }
                        
                        if($campanha!="NULL" && $ano != 'NULL')
                            $where.= " AND tbentrega.numloteinterno = '".$campanha."/".$ano."'";

                        $qry->nrw = 0;
                        $sql3 = "SELECT * FROM tbbase WHERE ativa = 'S' ORDER BY nomebase";
                        $qry3->executa($sql3);
                        $where2 = $where;

                        for($j=0;$j<$qry3->nrw;$j++){
                          $qry3->navega($j);
                          $where2 = $where;
                          $where2.=" and codbase = '".$qry3->data['codbase']."'";
                          $sql = "SELECT COUNT(tbentrega.idinterno) as tot_lote, 
                                  tbentrega.numlotecliente,tbentrega.dataemissao
                                  FROM tbentrega WHERE $where2 GROUP BY tbentrega.numlotecliente,
                                  tbentrega.dataemissao ORDER BY tbentrega.dataemissao,
                                  tbentrega.numlotecliente";
                          $qry->executa($sql);
                          if($qry->nrw){

                              
                              for($i=0;$i<$qry->nrw;$i++){
                                      $qry->navega($i);

                                      $tot_lote = $qry->data["tot_lote"];
                                      
                                      $where_lote_atual = " AND tbentrega.dataemissao='".$qry->data["dataemissao"]."'";
                                      $setor = $qry->data["numlotecliente"];

                                      $totalfoto = 0;
                                        
                                      $valor_tarifa_total = 0;
                                      $sql = "SELECT count(DISTINCT idexterno) as totalfoto FROM tbentrega,tb_easy_courier WHERE $where2 AND (tbentrega.st='D' or tbentrega.st='A' or tbentrega.st='T' or tbentrega.idtipomovimento = 221 or tbentrega.idtipomovimento = 783 or tbentrega.idtipomovimento = 811) AND numlotecliente = '".$setor."' AND tb_easy_courier.nr_encomenda = tbentrega.idexterno";
                                      $qry4->executa($sql);
                                      $totalfoto = $qry4->data['totalfoto']; 

                                      $tot_entrega = 0;
                                      $sql2 = "SELECT COUNT(tbentrega.idinterno) 
                                               as tot_entrega FROM tbentrega WHERE $where2
                                               AND tbentrega.st='E' and  (idtipomovimento in (222,781) or idmotivo = 9) and numlotecliente = '$setor' 
                                       $where_lote_atual";

                                      $qry2->executa($sql2);
                                        
                                      $tot_entrega = $qry2->data["tot_entrega"];

                                      $tot_devolvido = 0;
                                      $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot_devolvido FROM tbentrega 
                                             WHERE $where2 AND (tbentrega.st='D' or tbentrega.st='A' or tbentrega.st='T' or tbentrega.idtipomovimento = 221 or tbentrega.idtipomovimento = 811 or tbentrega.idtipomovimento = 783) 
                                            and numlotecliente = '$setor'  $where_lote_atual";

                                      $qry2->executa($sql2);
                                      $tot_devolvido = $qry2->data["tot_devolvido"];
        

                                      $tot_pendente=0;
                                      $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot_pendente FROM tbentrega 
                                               WHERE $where2 AND (tbentrega.st isnull ) 
                                              and numlotecliente = '$setor'    $where_lote_atual";
          
                                          
                                      $qry2->executa($sql2);
                                      $tot_pendente = $qry2->data["tot_pendente"];
                      
                      
                                      $tot_nao_digi=0;
                                      $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot_nao_digi FROM tbentrega 
                                               WHERE $where2 AND (tbentrega.st='E' and 
                                               tbentrega.idtipomovimento <> 222 and tbentrega.idtipomovimento <> 221  and tbentrega.idmotivo <> 9) 
                                               and numlotecliente = '$setor'    $where_lote_atual";
                                      $qry2->executa($sql2);
                                      $tot_nao_digi = $qry2->data["tot_nao_digi"];      
                                       
                                      $ie = "Conta";
                                      $tot_ie = 0;
                                      
                                       //Pegando a Tarifa 
                                      $lote_int = abs($where_lote_atual);
                                      $sql = "SELECT * from tb_preco_demillus where setor = '$setor'";  
                                      $qry_preco->executa($sql);
                                     // echo $sql;
                                      $valor_tarifa = $qry_preco->data["repasse"];
                                      $valor_tarifa_total =   $valor_tarifa * ($tot_entrega+$totalfoto);

                                      $arrBases[$qry3->data['nomebase']]['total_a_pagar'] += $valor_tarifa_total;
                                      $arrBases[$qry3->data['nomebase']]['total_tot_lote'] += $tot_lote;
                                      $arrBases[$qry3->data['nomebase']]['total_tot_entrega'] += $tot_entrega;
                                      $arrBases[$qry3->data['nomebase']]['total_tot_devolvido'] += $tot_devolvido;
                                      $arrBases[$qry3->data['nomebase']]['total_tot_pendente'] += $tot_pendente;
                                      $arrBases[$qry3->data['nomebase']]['total_nao_digita'] += $tot_nao_digi;                                  
                              }

                              $sql2 = "SELECT SUM(valor) as totalvalor FROM 
                                       tb_demillus_extra 
                                       where
                                       voucher = '' 
                                       AND codbase = ".$qry3->data['codbase']."
                                       AND (campanha = '".$campanha."/".$ano."'
                                       OR campanha = '".str_pad($campanha, 2, "0", STR_PAD_LEFT)."/".$ano."')"; 
                                   
                              $qry2->executa($sql2); 

                              $arrBases[$qry3->data['nomebase']]['total_extras'] = $qry2->data['totalvalor'];   

                              $sql2 = "SELECT SUM(valor) as totalvalor FROM 
                                       tb_demillus_extra 
                                       where
                                       voucher != '' 
                                       AND codbase = ".$qry3->data['codbase']."
                                       AND (campanha = '".$campanha."/".$ano."'
                                       OR campanha = '".str_pad($campanha, 2, "0", STR_PAD_LEFT)."/".$ano."')"; 
                                   
                              $qry2->executa($sql2); 

                              $arrBases[$qry3->data['nomebase']]['comb'] = $qry2->data['totalvalor'];   
                          }
                        }
                        echo "<table class='tabela' style='width: 100%;'>";
                        echo "<tr bgcolor=#cccccc>";          // cor cinza + forte
                        echo "        <td align='center'><b>Base</b></td>";
                        echo "        <td align='center'><b>Notas</b></td>";
                        echo "        <td align='center'><b>Lib. Pagamento</b></td>";
                        echo "        <td align='center'><b>Dev</b></td>";
                        echo "        <td align='center'><b>Pend.</b></td>";
                        echo "        <td align='center'><b>N&atilde;o Dig.</b></td>";
                        echo "        <td align='center'><b>Total</b></td>";
                        echo "        <td align='center'><b>Extras</b></td>";
                        echo "        <td align='center'><b>Combusivel</b></td>";
                        echo "        <td align='center'><b>Total Liq.</b></td>";
                        echo "</tr>";
                        $tot1 = 0;
                        $tot2 = 0;
                        $tot3 = 0;
                        $tot4 = 0;
                        $tot5 = 0;
                        $tot6 = 0;
                        $tot7 = 0;
                        $tot8 = 0;

                        foreach($arrBases as $base => $val){
                          echo "<tr>";
                          echo "<td>".$base."</td>";
                          echo "<td>".$val['total_tot_lote']."</td>";
                          echo "<td>".$val['total_tot_entrega']."</td>";
                          echo "<td>".$val['total_tot_devolvido']."</td>";
                          echo "<td>".$val['total_tot_pendente']."</td>";
                          echo "<td>".$val['total_nao_digita']."</td>";
                          echo "<td>".number_format(round(($val['total_a_pagar']),2),2,",",".")."</td>";
                          echo "<td>".number_format(round(($val['total_extras']),2),2,",",".")."</td>";
                          echo "<td>".number_format(round(($val['comb']),2),2,",",".")."</td>";
                          echo "<td>".number_format(round(($val['comb']+$val['total_extras']+$val['total_a_pagar']),2),2,",",".")."</td>";
                          echo "</tr>"; 
                          $tot1 += $val['total_tot_lote'];
                          $tot2 += $val['total_tot_entrega'];
                          $tot3 += $val['total_tot_devolvido'];
                          $tot4 += $val['total_tot_pendente'];
                          $tot5 += $val['total_nao_digita'];
                          $tot6 += $val['total_a_pagar'];
                          $tot7 += $val['total_extras'];
                          $tot8 += $val['comb'];
                          $tot9 += ($val['comb']+$val['total_extras']+$val['total_a_pagar']);
                        }
                        echo "<tr>";
                          echo "<td><strong>TOTAL</strong></td>";
                          echo "<td>".$tot1."</td>";
                          echo "<td>".$tot2."</td>";
                          echo "<td>".$tot3."</td>";
                          echo "<td>".$tot4."</td>";
                          echo "<td>".$tot5."</td>";
                          echo "<td>".number_format(round($tot6,2),2,",",".")."</td>";
                          echo "<td>".number_format(round($tot7,2),2,",",".")."</td>";
                          echo "<td>".number_format(round($tot8,2),2,",",".")."</td>";
                          echo "<td>".number_format(round($tot9,2),2,",",".")."</td>";
                          echo "</tr>"; 
                        echo "</table>";          

                    }//FIM ELSE CHECA RESUMO
                        break;

                        case "D":
                        //detalhe

                        $where = " AND tbentrega.idtransportadora=".$_SESSION["IDTRANSP"];

                        if ($codbase > 0){
                                $where .= " AND tbentrega.codbase = '$codbase'";
                                $porbase=1;
                        }
                       

                        session_unregister('sessao_string_retornar');
                        $sessao_string_retornar = "?";

                        if($codcliente > 0)
                        $where2.= " AND tbentrega.codcliente in($codcliente)";
                        $sessao_string_retornar .= "codcliente=$codcliente&";

                       if($campanha!="NULL" && $ano != 'NULL'){
                          $where.= " AND tbentrega.numloteinterno = '".$campanha."/".$ano."'";
                          $sessao_string_retornar .= "campanha=$campanha&ano=$ano&";                         
                       }
                     
/*
                        if($data_de and grava_data($data_de)!="NULL")
                        $where.= " AND tbentrega.$tipo_data >= '".grava_data($data_de)."'";
                        $sessao_string_retornar .= "data_de=$data_de&tipo_data=$tipo_data&";

                        if($data_ate and grava_data($data_ate)!="NULL")
                        $where.= " AND tbentrega.$tipo_data <= '".grava_data($data_ate)."'";
                        $sessao_string_retornar .= "data_ate=$data_ate&";
*/
                      

                        if($setor > 0)
                        $where.= " AND tbentrega.numlotecliente='$setor'";
                        $sessao_string_retornar .= "numlotecliente=$setor&";

                        if($dataemissao)
                        $where.= " AND tbentrega.$tipo_data='$dataemissao'";
                        $sessao_string_retornar .= "$tipo_data=$dataemissao&";

                        if($ver_detalhes=="pendentes")
                        $where.= " AND (tbentrega.st isnull OR tbentrega.st='' OR tbentrega.st='A') 
					               ";
                        
                        elseif($ver_detalhes=="sinistradas")
                        $where.= " AND tbentrega.idtipomovimento='132'";
                        
                        
                        elseif($ver_detalhes=="nao_coletadas")
                        $where.= " AND tbentrega.idtipomovimento='100'";
                        
                        
                        elseif($ver_detalhes=="devolvidos")
                        $where.= " AND (tbentrega.st='D' or tbentrega.st='A' or tbentrega.st='T' or tbentrega.idtipomovimento = 221 or tbentrega.idtipomovimento = 811 or tbentrega.idtipomovimento = 783)  ";
						
						elseif($ver_detalhes=="nao_dititalizado")
                        $where.= " AND (tbentrega.st='E' and tbentrega.idtipomovimento <> 222)  ";

                        elseif($ver_detalhes=="telemarketing")
                        $where.= " AND tbentrega.st='T' ";
                        

                        $sessao_string_retornar .= "ver_detalhes=$ver_detalhes&";
                        $sessao_string_retornar .= "opt=$opt&codbase=$codbase&";
						

                        $sessao_string_retornar = substr($sessao_string_retornar,0,strlen($sessao_string_retornar)-1);
                        session_register('sessao_string_retornar');

                        $qry->nrw = 0;
                        $sql = "SELECT tbentrega.st, tbentrega.datacoletadobase, tbentrega.idinterno,tbentrega.idexterno,
                                tbentrega.dataoperacao,tbentrega.datapromessa,tbenderecoentrega.cidadeentrega,tbenderecoentrega.cepentrega,
                                tbenderecoentrega.nomeentrega,tbentrega.codcliente,tbentrega.codigoproduto,tbtipomovimento.nometipomovimento,tbentrega.codbase,
                                tbentrega.idtipomovimento FROM tbentrega, tbenderecoentrega, tbtipomovimento WHERE  
                                tbentrega.idinterno=tbenderecoentrega.idinterno AND tbentrega.idtipomovimento=tbtipomovimento.idtipomovimento 
								
                                $where2 $where ORDER BY tbentrega.codcliente,tbentrega.codigoproduto,tbentrega.dataoperacao,tbenderecoentrega.cidadeentrega,
                                tbenderecoentrega.nomeentrega,tbentrega.idtipomovimento";
                        // echo $sql;

                        $qry->executa($sql);

                        echo "<table class='tabela' style='width: 100%;'>";
                        if ($qry->nrw){
                                echo "<tr><td colspan=9 align=center><b><font color='#FFF'>ENCOMENDAS ".strtoupper($ver_detalhes)."</font></b></td></tr>";

                                if($numlotecliente > 0)
                                echo "<tr><td colspan=9><b>Lote Externo: <font color='#990000'>{$numlotecliente}</font>&nbsp;&nbsp;&nbsp;&nbsp;Total: <font color='#990000'>".$qry->nrw."</font>/<font color='#990000'>$tot_lote</font></b></td></tr>";
                                elseif($dataemissao)
                                echo "<tr><td colspan=9><b>Data {$label_data} <font color='#990000'>".mostra_data($dataemissao)."</font>&nbsp;&nbsp;&nbsp;&nbsp;Total: <font color='#990000'>".$qry->nrw."</font>/<font color='#990000'>$tot_lote</font></b></td></tr>";

                                echo "<tr>
                                 <td>ID Interno</td>
                                 <td>ID Externo</td>
                                 <td>Destinat&aacute;rio</td>
                                 <td>Coleta Base</td>
                                 <td>Base</td>
                                 <td>Cidade</td>
								                 <td>Status</td>
                                 <td>Motivo</td>
                                 <td>Foto</td>
                               </tr>";

                                for($i=0;$i<$qry->nrw;$i++){
                                        $qry->navega($i);


                                        $nn_base = '';
                                                $sql2 = "SELECT nomebase from tbbase where codbase = '".$qry->data["codbase"]."' ";
                                                $qry2->executa($sql2);
                                                $nn_base = $qry2->data["nomebase"];

                                        $array_codcliente[$i] = $qry->data["codcliente"];
                                        $array_codigoproduto[$i] = $qry->data["codigoproduto"];

                                        if($array_codcliente[$i]!=$array_codcliente[$i-1]){

                                                $qry2->data["tot_cliente"] = "";
                                                $sql2 = "SELECT count(tbentrega.idinterno) as tot_cliente FROM tbentrega WHERE tbentrega.codcliente= '".$qry->data["codcliente"]."' $where";
                                                $qry2->executa($sql2);
                                                $tot_cliente = $qry2->data["tot_cliente"];

                                                $qry2->data["nomecliente"] = "";
                                                $sql2 = "SELECT nomecliente FROM tbcliente WHERE codcliente= '".$qry->data["codcliente"]."'";
                                                $qry2->executa($sql2);
                                                echo "<tr bgcolor='#cccccc'>";
												echo "  <td colspan='5'><b>Cliente:</b>&nbsp;&nbsp;<font color='#990000'>".$qry2->data["nomecliente"]."</font></td>";
												echo "  <td colspan='4'><b>Total:</b>&nbsp;&nbsp;<font color='#990000'>$tot_cliente/".$qry->nrw."</font></td>";
												echo "</tr>";

                                        }

                                        if($array_codigoproduto[$i]!=$array_codigoproduto[$i-1]){

                                                $qry2->data["tot_produto"] = "";
                                                $sql2 = "SELECT count(tbentrega.idinterno) as tot_produto FROM tbentrega WHERE tbentrega.codcliente= '".$qry->data["codcliente"]."' AND codigoproduto= '".$qry->data["codigoproduto"]."' $where";
                                                $qry2->executa($sql2);
                                                $tot_produto = $qry2->data["tot_produto"];

                                                $qry2->data["nomeproduto"] = "";
                                                $sql2 = "SELECT nomeproduto FROM tbproduto WHERE codigoproduto= '".$qry->data["codigoproduto"]."'";
                                                $qry2->executa($sql2);
                                                echo "<tr bgcolor='#dddddd'>";
												echo "  <td colspan='5'><b>Produto:</b>&nbsp;&nbsp;<font color='#990000'>".$qry2->data["nomeproduto"]."</font></td>";
												echo "  <td colspan='4'><b>Total:</b>&nbsp;&nbsp;<font color='#990000'>$tot_produto/$tot_cliente </font></td>";
												echo "</tr>";
                                                $j=0;
                                        }

										// parametros
										$id_interno = $qry->data["idinterno"];
										$lote_de    = trim($lote_de);

										// monta o redirecionamento para o Auditoria
										$reLink  = HOST."/auditoria_dm.php";
										$reLink .= "?idmenu=0";
										$reLink .= "&opt=S";
										$reLink .= "&data_de=".trim($data_de);
										$reLink .= "&data_ate=".trim($data_ate);
										$reLink .= "&id_transportadora=".trim($id_transportadora);
										$reLink .= "&id_base=".trim($id_base);
										$reLink .= "&id_cliente=".trim($id_cliente);
										$reLink .= "&id_produto=".trim($id_produto);
										$reLink .= "&idinterno=".trim($id_interno);
										$reLink .= "&lote_de=".$lote_de;
										$reLink .= "&lote_ate=".trim($lote_ate);
										$reLink .= "&tipo_lote=".trim($tipo_lote);
										$reLink .= "&tipo_data=".trim($tipo_data);
										$reLink .= "&so_barrabairro=".trim($so_barrabairro);
										$reLink .= "&audita_todos_movs=".trim($audita_todos_movs);
										$reLink .= "&token={$rnd}";
										
                                        echo "<tr ".(($j%2)?"bgcolor=#eeeeee":"").">
                                 <td><a href='javascript:void()' onclick='window.parent.location.href = \"{$reLink}\"'>".$qry->data["idinterno"]."</a></td>
                                 <td>".$qry->data["idexterno"]."</td>
                                 <td>".strtoupper(substr($qry->data["nomeentrega"],0,15))."</td>
                                 <td>".mostra_data($qry->data["datacoletadobase"])."</td>
                                 <td>".$nn_base."</td>
                                 <td>".strtoupper($qry->data["cidadeentrega"])."</td>";
						                   	 echo "<td>".strtoupper($qry->data["nometipomovimento"])."</td>";
                                 
                                 if ($qry->data["st"]=="D" or $qry->data["st"]=="T"){
                                     $sql= "SELECT idmotivo FROM tbentrega WHERE idinterno = '{$id_interno}' and idtipomovimento = '".$qry->data["idtipomovimento"]."' LIMIT 1";
                                     $qry2->Executa($sql);

                                     if ($qry2->data["idmotivo"]){
                                         $sql = "SELECT * FROM tbmotivo WHERE idmotivo = '".$qry2->data["idmotivo"]."'";
                                         $qry2->Executa($sql);

                                         echo "<td>".$qry2->data["motivo"]."</td>";
                                     }
                                     else
                                         echo "<td>Sem motivo</td>";
                                 }
                                 else
                                   echo "<td>N&atilde;o Aplic&aacute;vel</td>";
                                 
                                $qry5->executa("select * from tb_easy_courier where 
                                        nr_encomenda = '".$qry->data["idexterno"]."'  ");
                                if ($qry5->nrw)
                                  echo "<td>Sim</td>";
                                else
                                   echo "<td>N&atilde;o</td>"; 

                                 echo "
                               </tr>";
                                        $j++;
                                }
                        }
                        else {
							echo "<tr><td colspan=9 align=center><font color=#ff0000>Nenhuma encomenda encontrada</font></td></tr>";
						}
						echo "<tr><td colspan='9'>&nbsp;</td></tr>";
                        echo "<tr><td colspan='9' align=center><a href='javascript:document.form_pesquisa.submit();'>Voltar</a></td></tr>";
                        echo "</table>";
                        break;
                }
?>
        
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
( function( $ ) {
  $(function() {
    $('.btn-geracomb').on('click',function(){
      if($('.valorcomb').val() == ''){
        alert('Entre com um valor');
        return false;
      }
      if($('.voucher').val() == ''){
        alert('Entre com um voucher');
        return false;
      }
      if(confirm('Quer adicionar um desconto de combustivel?')){
        if($('.valorcomb').val() > 100){
          alert('O valor deve ser menor que 100');
          return false;
        }
        $.ajax({
          method: "POST",
          url: "<?php echo HOST."/Exec/fatura_demillus_base_ajax.php"; ?>",
          dataType:"json",
          data: { 
            codbase: <?php echo $codbase; ?>,
            campanha: <?php echo $campanha; ?>,
            ano: <?php echo $ano; ?>,
            valor: $('.valorcomb').val(),
            voucher: $('.voucher').val(),
            historico: $('.historico').val()
          }
        })
        .done(function( obj ) {
        if(obj.status == 1){
            $('.adddescontos').after('<tr><td style="background-color:white" >'+obj.dataq+'</td><td style="background-color:white">'+obj.valor+'</td><td style="background-color:white">'+obj.voucher+'</td><td colspan="8" style="background-color:white">'+obj.historico+'</td><td><a target="_blank" href="<?php echo HOST."/fatura_demillus_base-pdf.php?valor="; ?>'+Math.abs(obj.valor)+'&voucher='+obj.voucher+'">Imprimir</a></td></tr>');
            $('.totalex').text(parseFloat($('.totalex').text())+parseFloat(obj.valor));
            $('.totalliq').text(parseFloat($('.totalliq').text())+parseFloat(obj.valor));
        }else{
          alert('O numero de voucher ja existe');
          return false;
        }

        });      
      }
    });

    $('.btn-geracombb').on('click',function(){
      if($('.valorcomb').val() == ''){
        alert('Entre com um valor');
        return false;
      }

      if(confirm('Quer adicionar um valor extra?')){
        $.ajax({
          method: "POST",
          url: "<?php echo HOST."/Exec/fatura_demillus_base_ajax.php"; ?>",
          dataType:"json",
          data: { 
            codbase: <?php echo $codbase; ?>,
            campanha: <?php echo $campanha; ?>,
            ano: <?php echo $ano; ?>,
            valor: $('.valorcomb').val(),
            historico: $('.historico').val(),
            extras: 1
          }
        })
        .done(function( obj ) {
        if(obj.status == 1){
            $('.adddescontos').after('<tr><td style="background-color:white" >'+obj.dataq+'</td><td style="background-color:white">'+obj.valor+'</td><td colspan="10" style="background-color:white">'+obj.historico+'</td></tr>');
            $('.totalex').text(parseFloat($('.totalex').text())+parseFloat(obj.valor));
            $('.totalliq').text(parseFloat($('.totalliq').text())+parseFloat(obj.valor));
        }else{
          alert('O numero de voucher ja existe');
          return false;
        }

        });      
      }
    });

  });
} )( jQuery );  
</script>