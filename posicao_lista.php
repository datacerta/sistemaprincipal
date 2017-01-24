<?php
/**
 * Cadastro de Usuarios
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel
$prgTit = "Posi&ccedil;&atilde;o da Lista";

// pega a configuracao
require_once("inc/config.inc");

// cria a consulta
$qry  = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);

// pega o header
require_once("inc/header.inc");
?>

<!-- CSS Local -->
<link href="<?=HOST?>/css/tabela_3.css" rel="stylesheet" type="text/css" />

<div style="width: 900px; margin: 0 auto;">


    <table class="tabela">
      <tr bgcolor="#eeeeee">
        <td style="text-align: center;"><font size="6"><b><font size="4">..:: </font></b><font color="#fff" size="3"><b>Posi&ccedil;&atilde;o da Lista</b></font></font><font size="4"><b> ::..</b></font></td>
      </tr>
    </table>
    <form action=<?=$PHP_SELF;?> method=POST>
<?
            if ($msg){
                    echo "<TR>
                                            <TD colspan=2><font color=#ff0000><B>$msg</font></td>
                              </tr>";
            }

            ?>
	<br><br>		
    <table width=800 class = 'tabela'>
      <tr bgcolor="#FFFFFF">
        <td >Entre com o n&uacute;mero da lista:</td>
        <td ><input type=text value="<?=$num_lista;?>"  name=num_lista></td>
        <td ><input type=submit value="Procurar"></td>
      </tr>
    </form>
    <?

            if ($num_lista){
                    $sql = "SELECT COUNT(idinterno) as totencomendas, tbentrega.idtipomovimento, nometipomovimento
                            FROM tbentrega,tbtipomovimento WHERE
                            tbentrega.idtipomovimento = tbtipomovimento.idtipomovimento and
                            numlista = '$num_lista' group by nometipomovimento, tbentrega.idtipomovimento";
                            $qry->executa($sql);

                    $totencomendas = 0;
                    
                    
                    $qry->data["totencomendas"];

                    
                    for($z=0;$z<$qry->nrw;$z++){
				       $qry->navega($z);
					   $movimento = $qry->data["nometipomovimento"];  
					   $tot = $qry->data["totencomendas"];
					   $idtipomovimentoq = $qry->data["idtipomovimento"];  
					 
									
					   
				       echo "<tr ><td>".$movimento."</td>";
					   
					  
					  
					   //pendentes
                                        echo " 
                                        
                                       
                                        <td align='center'> <a href=\"$PHP_SELF?numlista=$num_lista&idtipomov=$idtipomovimentoq\">".$tot."</td>";
									
					   
					   
 				}
                    
                    
                    
                    

                   

                   

                       
                    
                                       

                                
                            
                   

            }

    ?>

    </table>
<form name='lll' action="<?=$selfLink?>" method="post">
<input type='hidden' name='opt' value='P'>


<?php
        $num_lista = (($num_lista)?trim($num_lista):"");
        $idtipomov =  $_GET['idtipomov'];
        $num_listab =  $_GET['numlista'];

                if(isset($idtipomov)){
                   
                   

                         $qry->nrw = 0;
                        $sql = "SELECT tbentrega.st, tbentrega.datacoletadobase, tbentrega.idinterno,tbentrega.idexterno,
                                tbentrega.dataoperacao,tbentrega.datapromessa,tbenderecoentrega.cidadeentrega,tbenderecoentrega.cepentrega,
                                tbenderecoentrega.nomeentrega,tbentrega.codcliente,tbentrega.codigoproduto,tbtipomovimento.nometipomovimento,tbentrega.codbase,
                                tbentrega.idtipomovimento FROM tbentrega, tbenderecoentrega, tbtipomovimento WHERE  
                                tbentrega.idinterno=tbenderecoentrega.idinterno AND tbentrega.idtipomovimento=tbtipomovimento.idtipomovimento 
                                
                                and numlista = $num_listab and tbentrega.idtipomovimento = '".$idtipomov."'";

                        $qry->executa($sql);

                        echo "<table class=tabela width='800' style='border:0; margin-top:25px'  align='left'>";
                        if ($qry->nrw){
                                echo "<tr><td colspan=9 align=center><b><font color='#FF0000'>ENCOMENDAS ".strtoupper($ver_detalhes)."</font></b></td></tr>";

                                if($numlotecliente > 0)
                                echo "<tr><td colspan=9><b>Lote Externo: <font color='#990000'>".$numlotecliente."</font>&nbsp;&nbsp;&nbsp;&nbsp;Total: <font color='#990000'>".$qry->nrw."</font>/<font color='#990000'>$tot_lote</font></b></td></tr>";
                                elseif($dataemissao)
                                echo "<tr><td colspan=9><b>Data $label_data <font color='#990000'>".mostra_data($dataemissao)."</font>&nbsp;&nbsp;&nbsp;&nbsp;Total: <font color='#990000'>".$qry->nrw."</font>/<font color='#990000'>$tot_lote</font></b></td></tr>";

                                echo "<tr>
                                 <td>ID Interno</td>
                                 <td>ID Externo</td>
                                 <td>Destinat&aacute;rio</td>
                                 <td>Coleta Base</td>
                                 <td>Base</td>
                                 <td>Cidade</td>
                                 <td>Status</td>
                  
                               </tr>";

                                for($i=0;$i<$qry->nrw;$i++){
                                        $qry->navega($i);


                                        $nn_base = '';
                                                $sql2 = "SELECT nomebase from tbbase where codbase = '".$qry->data["codbase"]."' ";
                                                $qry2->executa($sql2);
                                                $nn_base = $qry2->data["nomebase"];

                                        $array_codcliente[$i] = $qry->data["codcliente"];
                                        $array_codigoproduto[$i] = $qry->data["codigoproduto"];

                                

                                        echo "<tr ".(($j%2)?"bgcolor=#eeeeee":"").">
                                 <td><a href='auditoria_dm.php?opt=S&data_de=$data_de&data_ate=$data_ate&id_transportadora=$id_transportadora&id_base=$id_base&id_cliente=$id_cliente&id_produto=$id_produto&idinterno=".$qry->data["idinterno"]."&lote_de=$lote_de&lote_ate=$lote_ate&tipo_lote=$tipo_lote&tipo_data=$tipo_data&so_barrabairro=$so_barrabairro&audita_todos_movs=$audita_todos_movs'>".$qry->data["idinterno"]."</a></td>
                                 <td>".$qry->data["idexterno"]."</td>
                                 <td>".strtoupper(substr($qry->data["nomeentrega"],0,15))."</td>
                                 <td>".mostra_data($qry->data["datacoletadobase"])."</td>
                                 <td>".$nn_base."</td>
                                 <td>".strtoupper($qry->data["cidadeentrega"])."</td>";
                                             echo "<td>".strtoupper($qry->data["nometipomovimento"])."</td>";
                                 
                               
                                 

                                 echo "
                               </tr>";
                                        $j++;
                                }
                        }
                        echo "</table>";
                }

        
?>
</form>    
</div>

<?php
// pega o Footer
require_once("inc/footer.inc");