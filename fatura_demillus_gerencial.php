<?
session_start();
//inclui biblioteca de controles
require_once("inc/config.inc");
require_once("classes/diversos.inc.php");

// seta o parent e redireciona
$_SESSION["PARENT"     ] = false;
$_SESSION["REDIRECIONA"] = false;

//testa sessão
if (VerSessao()==false){
        header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry_preco = new consulta($con);


$google_api = "ABQIAAAAho5jFfUGXHNyRjGXbcWXChSBj9dyf3cQ2L7RnBtfd9ot5emaJxSXjDLEdGfNTakQt4_LT2Uduev7AA";

$sql = "SELECT nivelusuario FROM tblogin WHERE codlogin=".$_SESSION["IDUSER"];
$qry->executa($sql);
$nivelusuario = $qry->data["nivelusuario"];

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

//<link href="tablecloth/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />
//<script type="text/javascript" src="tablecloth/tablecloth.js"></script>


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href="estilo.css" rel="stylesheet" type="text/css">
<link href="tahoma.css" rel="stylesheet" type="text/css">


<link href="tablecloth/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="tablecloth/tablecloth.js"></script>

<title>..:: Mensuração ::..</title>
</head>
<link href="estilo.css" rel="stylesheet" type="text/css">
<link href="tahoma.css" rel="stylesheet" type="text/css">

<body>
   
<?
require_once("inc/header.inc");
/* $sql = "SELECT nometransportadora FROM tbtransportadora WHERE idtransportadora=".$_SESSION["IDTRANSP"];
$qry->executa($sql);
$nometransportadora = $qry->data["nometransportadora"]; */
        ?>
        <form name='form_pesquisa' action="<?=$PHP_SELF;?>" method='POST'>
        <input type='hidden' name='opt' value='P'>
        <input type='hidden' name='ver_detalhes' value=''>
        <input type='hidden' name='numlotecliente' value=''>
        <input type='hidden' name='dataemissao' value=''>
        <input type='hidden' name='tot_lote' value=''>
        <table class="tabela" style="width: 800px; margin:0 auto" >
            <tr>
                
                <th align='left'><b>Cliente</b></th>
                <th align='left'><b>Produto</b></th>
                <th align='left'><b>Data De</b></th>
                <th align='left'><b>Data Até</b></th>
                <th colspan="2" align='left'><b>Tipo Data</b></th>
                <th align='left'>&nbsp;</th>
            </tr>
            <tr >
            <!--<td align="center"><font color="#"><?=$nometransportadora;?></td>-->

            <td align="left">
			    <select name='codcliente' style='width:175px;' onChange=javascript:document.form_pesquisa.codigoproduto.disabled=true;document.form_pesquisa.opt.value='';
			    document.form_pesquisa.tot_lote.value='';document.form_pesquisa.numlotecliente.value='';document.form_pesquisa.submit();">
			    <? combo("SELECT codcliente,nomecliente FROM tbcliente WHERE status = 5 and
		    	idtransportadora='".$_SESSION["IDTRANSP"]."' ".(($_SESSION['IDCLIENTE'] > 0) ? " AND
		    	codcliente='". $_SESSION['IDCLIENTE']."'" : "")." ORDER BY nomecliente",$codcliente,(($_SESSION['IDCLIENTE'])?"":"T")) ;?></select></td>
            <td align="left"><select name='codigoproduto' style='width:140px;'>
				<? combo("SELECT tbclienteproduto.codigoproduto,tbproduto.nomeproduto FROM tbclienteproduto,tbproduto WHERE 
				tbclienteproduto.codigoproduto=tbproduto.codigoproduto AND 
				tbclienteproduto.codcliente='".intval($codcliente)."' AND 
				tbclienteproduto.idtransportadora='".$_SESSION["IDTRANSP"]."' ORDER BY tbproduto.nomeproduto",$codigoproduto,"T") ;?></select></td>
            <td><input type=text size=12 name=data_de value='<?=((!$data_de and $_POST["codcliente"]=="")?date("01/m/Y"):"$data_de");?>' maxlength="10"></td>
            <td><input type=text size=12 name=data_ate value='<?=((!$data_ate and $_POST["codcliente"]=="")?ult_dia_mes(date("m"),date("Y")).date("/m/Y"):"$data_ate");?>' maxlength="10"></td>
             <td colspan=2 nowrap>Emis.<input type=radio name='tipo_data'<?=(($tipo_data != "datapromessa" && $tipo_data != "datacoletado")?"checked":"");?>  value='dataemissao'>
                 
                 Col. <input type=radio name='tipo_data'  <?=(($tipo_data == "datacoletado")?"checked":"");?> value='datacoletado'></td>
             <td align="center"> <input type="submit"  name="submete" value="OK"></td>
                        
				
		  </table>			
</form>
		        <?
               echo "<table>"; 
                Switch($opt){

                        case "P":
                        //procurar

                        $where = "tbentrega.idtransportadora=".$_SESSION["IDTRANSP"];

                    
     

                        if($codcliente > 0)
                        $where.= " AND tbentrega.codcliente='$codcliente'";

                        if($codigoproduto > 0)
                        $where.= " AND tbentrega.codigoproduto='$codigoproduto'";

                        if($data_de and grava_data($data_de)!="NULL")
                        $where.= " AND tbentrega.$tipo_data >= '".grava_data($data_de)."'";

                        if($data_ate and grava_data($data_ate)!="NULL")
                        $where.= " AND tbentrega.$tipo_data <= '".grava_data($data_ate)."'";


                        $qry->nrw = 0;

                      
                        $sql = "SELECT COUNT(tbentrega.idinterno) as tot_lote, SUM(tbentrega.quantidadevolumes) as volumes,
                        to_char(tbentrega.dataemissao, 'MM/YYYY') as dataemissao, tb_preco_demillus.grupo FROM tbentrega, tb_preco_demillus 
                        WHERE CAST(tbentrega.numlotecliente as int) = tb_preco_demillus.setor AND $where GROUP BY tb_preco_demillus.grupo, to_char(tbentrega.dataemissao , 'MM/YYYY')
                        ORDER BY tb_preco_demillus.grupo,dataemissao";
                        $qry->executa($sql);
                        //echo $sql;
                        //die;
                        //coloque a var abaixo para 1 devido ao problema de digitaçào entre Vix e Rio
                         $porbase==1;
                        
                        if($qry->nrw){
                                echo "<br>";
                                echo "<br>";
                                echo "<br>";
                                echo "<br>";
                                echo "<table class='tabela' style='width: 800px; margin:0 auto'>";
                                echo "<tr bgcolor=#cccccc>";          // cor cinza + forte
                                echo "        <th align='center'><b>DATA</b></th>";
                                echo "        <th align='center'><b>GRUPO</b></th>";
                                echo "        <th align='center'><b>NOTAS</b></th>";
                                echo "        <th align='center'><b>CAIXAS</b></th>";
                                echo "        <th align='center'><b>TARIFA</b></th>";
                                echo "        <th align='center'><b>TOTAL </b></th>";
                                echo "        <th align='center' width='40'><b>ICMS</b></th>";
                                echo "        <th align='center'><b>VALOR CTE </b></th>";
                      
                                
                       
								
							
                                
                                echo "</tr>";

                                $Total_Lote = 0;
                                $Total_Entregue = 0;
                                $Total_Devolvido = 0;
                                $Total_Pendente = 0;
                                $Total_Valor = 0;
                                $Total_Peso = 0;
                                $Total_Adv = 0;
                                $Total_Gris = 0;
                                $Total_Tarifa =0;
								$Total_comiss =0;
                                $volumesqtdTotal =0;
                                        
                                for($i=0;$i<$qry->nrw;$i++){
                                        $qry->navega($i);
                                        $dataemissao = $qry->data["dataemissao"];
                                        $tot_lote = $qry->data["tot_lote"];
                                        $numlotecliente = $qry->data["grupo"];
                                        $volumesqtd = $qry->data["volumes"];
                                        $volumesqtdTotal += $qry->data["volumes"];
                                        $volumesqtdTotall +=  $qry->data["volumes"];
                                        $where_lote_atual = "  and numlotecliente ='$numlotecliente'";

                                     
                                        if($grupoanterior != $numlotecliente AND !empty($grupoanterior)){
                                            echo "<tr bgcolor=#dddddd>";          // cor cinza +- forte
                                             echo "        <td align='right'><b>".$grupos." GRUPOS</b></td>";
                                            echo "        <td align='right'><b>Totais</b></td>";
                                            
                                           
                                            echo "        <td align='right'><b>".$Total_Lotel."</b></td>";
                                            echo "        <td align='right'><b>".$volumesqtdTotall."</b></td>";
                                            
                                           
                                            echo "        <td align='right'>NA</td>";
                                            //echo "        <td align='center'><b>".number_format($Total_Tarifa,2)."</b></td>";
                                            
                                            echo "        <td align='right'><b>".number_format($Total_Tarifal+$Total_Grisl+$Total_Advl,2)."</b></td>";
                                            
                                        
                                            echo "        <td align='right'><b>".number_format($total_tibutosl,2)."</b></td>";
                                            echo "        <td align='right'><b>".number_format($total_ctel,2)."</b></td>";
                                    
                                            echo "</tr>";
                                            $volumesqtdTotall = 0;
                                            $Total_Lotel = 0;
                                            $Total_Entreguel = 0;
                                            $Total_Devolvidol = 0; 
                                            $Total_Pendentel = 0; 
                                            $Total_Valorl = 0;
                                            $Total_Pesol = 0;
                                            $Total_Advl = 0;
                                            $Total_Grisl = 0;
                                            $Total_Tarifal = 0;
                                            $res_finall = 0;
                                            $tbasel = 0; 
                                            $total_tibutosl = 0;
                                            $total_crol = 0;
                                            $total_transfl = 0;
                                            $total_ctel = 0;
                                            $grupos = 0;
                                        }

                                        echo "<tr ".(($i%2==0)?"":"bgcolor=#eeeeee").">";    // cor cinza + fraco
                                        echo "<td align='center'>".($numlotecliente)."</td>";
                                        echo "<td align='center'>".$dataemissao."</td>";
                                        
                                        echo "<td align='center'>".$tot_lote."</td>";
                                        echo "<td align='center'>".$volumesqtd."</td>";
                                       
            

                                        $vl_entrega = 0;
                                        $sql2 = "SELECT sum(tbentrega.valorentrega) as vl_entrega FROM 
                                        tbentrega WHERE $where  $where_lote_atual";
                                        $qry2->executa($sql2);
                                        $vl_entrega = $qry2->data["vl_entrega"];                                                                                       
                                       
                                        $adv = 0;
                                        $gris = 0;
                                      
									  
									  
                                        
                                        //Pegando a Tarifa 
                                        $lote_int = abs($where_lote_atual);
                                        $sql = "SELECT * from tb_preco_demillus where grupo = '$numlotecliente'";  
                                        $qry_preco->executa($sql);
                                       // echo $sql;
                                        $valor_tarifa = $qry_preco->data["preco"];
                                        $repasse      = $qry_preco->data["repasse"] *$tot_lote;
                                        
										
                                        //echo $qry_preco->data["valor"];
                                        //die;
                                       
                                        
                                        $tarifa = ($tot_lote * $valor_tarifa) ;                                                                                      
                                      
                                        
                                        $tributos  = (($tarifa / 0.88) - $tarifa);
										$cro=$tot_entrega*1.13;
										$transf=$tot_entrega*1.05;
										$comiss = $tarifa * 0.02;
                                        $valor_cte = $tarifa + $tributos;
                                        
                                        
                                        
                                        
                                      
                                        $tot_pendente = $tot_lote - ($tot_entrega + $tot_devolvido);
                                        $ie = "Conta";
                                        $tot_ie = 0;

                                      
                                    
                                        echo "        <td align='right'>".number_format($valor_tarifa,2)."</td>";
                                        echo "        <td align='right'>".number_format($tarifa,2)."</td>";
                                        echo "        <td align='right'>".number_format($tributos,2)."</td>";
                                        echo "        <td align='right'>".number_format($valor_cte,2)."</td>";
                                     
										
								                
                  
                                        //Somando os Totais
                                        $Total_Lote = $Total_Lote + $tot_lote;
                                        $Total_Entregue = $Total_Entregue + $tot_entrega;
                                        $Total_Devolvido = $Total_Devolvido + $tot_devolvido; 
                                        $Total_Pendente = $Total_Pendente + $tot_pendente; 
                                        $Total_Valor = $Total_Valor + $vl_entrega;
                                        $Total_Peso = $Total_Peso + $peso_entrega;
                                        $Total_Adv = $Total_Adv + $adv;
                                        $Total_Gris = $Total_Gris + $gris;
                                        $Total_Tarifa = $Total_Tarifa + $tarifa;
                                        $res_final = $res_final + $res;
                                        $tbase = $tbase + $repasse; 
                                        $total_tibutos = $total_tibutos + $tributos;
										$total_cro = $total_cro + $cro;
										$total_transf = $total_transf + $transf;
										$total_cte = $total_cte + $valor_cte;

                                        $Total_Lotel += $tot_lote;
                                        $Total_Entreguel += $tot_entrega;
                                        $Total_Devolvidol += $tot_devolvido; 
                                        $Total_Pendentel += $tot_pendente; 
                                        $Total_Valorl += $vl_entrega;
                                        $Total_Pesol += $peso_entrega;
                                        $Total_Advl += $adv;
                                        $Total_Grisl += $gris;
                                        $Total_Tarifal += $tarifa;
                                        $res_finall += $res;
                                        $tbasel += $repasse; 
                                        $total_tibutosl += $tributos;
                                        $total_crol += $cro;
                                        $total_transfl += $transf;
                                        $total_ctel += $valor_cte;
                                        $grupos++;

                                        if($tot_entrega > 0){
                                                $qry2->nrw=0;
                                               

                                        }

                                        echo "</tr>";

                                        $grupoanterior = $qry->data["grupo"];

                                        if(($i+1) == $qry->nrw){
                                            echo "<tr bgcolor=#dddddd>";          // cor cinza +- forte
                                            echo "        <td align='right'><b>".$grupos." GRUPOS</b></td>";
                                            echo "        <td align='right'><b>Totais</b></td>";
                                            
                                            
                                            echo "        <td align='right'><b>".$Total_Lotel."</b></td>";
                                            echo "        <td align='right'><b>".$volumesqtdTotall."</b></td>";
                                            
                                           
                                            echo "        <td align='right'>NA</td>";
                                            //echo "        <td align='center'><b>".number_format($Total_Tarifa,2)."</b></td>";
                                            
                                            echo "        <td align='right'><b>".number_format($Total_Tarifal+$Total_Grisl+$Total_Advl,2)."</b></td>";
                                            
                                        
                                            echo "        <td align='right'><b>".number_format($total_tibutosl,2)."</b></td>";
                                            echo "        <td align='right'><b>".number_format($total_ctel,2)."</b></td>";
                                    
                                            echo "</tr>";
                                            $volumesqtdTotall = 0;
                                            $Total_Lotel = 0;
                                            $Total_Entreguel = 0;
                                            $Total_Devolvidol = 0; 
                                            $Total_Pendentel = 0; 
                                            $Total_Valorl = 0;
                                            $Total_Pesol = 0;
                                            $Total_Advl = 0;
                                            $Total_Grisl = 0;
                                            $Total_Tarifal = 0;
                                            $res_finall = 0;
                                            $tbasel = 0; 
                                            $total_tibutosl = 0;
                                            $total_crol = 0;
                                            $total_transfl = 0;
                                            $total_ctel = 0;
                                            $grupos = 0;
                                        }
                                }

                                //totalizador
                                echo "<tr bgcolor=#cccccc>";          // cor cinza +- forte
                                echo "        <td align='right'><b>".$i." GRUPOS</b></td>";
                                echo "        <td align='right'><b>TOTAIS GERAIS</b></td>";
                                
                                
                                echo "        <td align='right'><b>".$Total_Lote."</b></td>";
                                echo "        <td align='right'><b>".$volumesqtdTotal."</b></td>";
                                
                               
                                echo "        <td align='right'>NA</td>";
                                //echo "        <td align='center'><b>".number_format($Total_Tarifa,2)."</b></td>";
                                
                                echo "        <td align='right'><b>".number_format($Total_Tarifa+$Total_Gris+$Total_Adv,2)."</b></td>";
                                
                            
								echo "        <td align='right'><b>".number_format($total_tibutos,2)."</b></td>";
								echo "        <td align='right'><b>".number_format($total_cte,2)."</b></td>";
						
                                echo "</tr>";
                             
                               
                                
                                

						
                                
                                
                                
								echo "</table>";
                        }

                        break;

                        case "D":
                        
                        break;
                }            
				
?>
        
<?php $con->desconecta(); ?>
</body>
</html>