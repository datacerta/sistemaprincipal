<?
session_start();
//inclui biblioteca de controles
require_once("classes/diversos.inc.php");

//testa sessão
if (VerSessao()==false){
        header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry_preco = new consulta($con);


//<link href="tablecloth/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />
//<script type="text/javascript" src="tablecloth/tablecloth.js"></script>


?>
<html>
<head>
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
        <table width="800" align="left" >
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

                      
                        $sql = "SELECT COUNT(tbentrega.idinterno) as tot_lote,
                        tbentrega.numlotecliente,tbentrega.dataemissao FROM tbentrega 
                        WHERE $where GROUP BY tbentrega.numlotecliente,tbentrega.dataemissao 
                        ORDER BY tbentrega.dataemissao,tbentrega.numlotecliente";
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
                                echo "<table width='800' align='left' border='0'>";
                                echo "<tr bgcolor=#cccccc>";          // cor cinza + forte
                                echo "        <th align='center'><b>Emissão</b></th>";
                                echo "        <th align='center'><b>Setor</b></th>";
                                echo "        <th align='center'><b>Col</b></th>";
                                echo "        <th align='center'><b>Entr</b></th>";
                                echo "        <th align='center'><b>Dev</b></th>";
                                echo "        <th align='center'><b>Pend.</b></th>";
                                echo "        <th align='center' width='40'><b>Digitalizados</b></th>";
                                
                                
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
                                        
                                for($i=0;$i<$qry->nrw;$i++){
                                        $qry->navega($i);
                                        $dataemissao = $qry->data["dataemissao"];
                                        $tot_lote = $qry->data["tot_lote"];
                                        $numlotecliente = $qry->data["numlotecliente"];
                                        $where_lote_atual = "AND tbentrega.dataemissao='$dataemissao' 
                                                             and numlotecliente ='$numlotecliente'";
                                     
                                        echo "<tr ".(($i%2==0)?"":"bgcolor=#eeeeee").">";    // cor cinza + fraco
                                        echo "<td align='center'>".mostra_data($dataemissao)."</td>";
                                        echo "<td align='center'>".($numlotecliente)."</td>";
                                        echo "<td align='center'>".$tot_lote."</td>";


                                        $tot_entrega = 0;
                                        $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot_entrega, 
                                                 numlotecliente FROM 
                                                 tbentrega WHERE $where AND tbentrega.st='E' 
                                        $where_lote_atual group by dataemissao,numlotecliente";
                                        $qry2->executa($sql2);
                                        $tot_entrega = $qry2->data["tot_entrega"];
                                        if(!$tot_entrega)
                                           $tot_entrega = 0;
                                       // echo $sql2;
                                       
                                       
                                        $tot_devolvido = 0;
                                        $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot_devolvido FROM 
                                        tbentrega WHERE $where AND tbentrega.st='D' $where_lote_atual";
                                        $qry2->executa($sql2);
                                        $tot_devolvido = $qry2->data["tot_devolvido"];

                                        $vl_entrega = 0;
                                        $sql2 = "SELECT sum(tbentrega.valorentrega) as vl_entrega FROM 
                                        tbentrega WHERE $where  $where_lote_atual";
                                        $qry2->executa($sql2);
                                        $vl_entrega = $qry2->data["vl_entrega"];                                                                                       
                                        $adv = ($vl_entrega*0.3/100);
                                        $gris = ($vl_entrega*0.1/100);
                                      
                                        $peso_entrega = 0;
                                        $sql2 = "SELECT sum(tbentrega.pesoentrega) as peso_entrega FROM 
                                        tbentrega WHERE $where  $where_lote_atual";
                                        $qry2->executa($sql2);
                                        $peso_entrega = $qry2->data["peso_entrega"]; 
                                        //Pegando a Tarifa 
                                        $lote_int = abs($where_lote_atual);
                                        $sql = "SELECT * from tb_preco_demillus where setor = '$numlotecliente'";  
                                        $qry_preco->executa($sql);
                                       // echo $sql;
                                        $valor_tarifa = $qry_preco->data["preco"];
                                        $repasse      = $qry_preco->data["repasse"] *$tot_lote;
                                        
                                        //echo $qry_preco->data["valor"];
                                        //die;
                                       
                                        
                                        $tarifa = ($tot_lote * $valor_tarifa) ;                                                                                      
                                      
                                        $res = $tarifa - $repasse;
                                      
                                        $primeira_nota = '';
                                        $sql2 = "SELECT numnotafiscal as primeiranota FROM 
                                        tbentrega WHERE $where  $where_lote_atual order by numnotafiscal asc limit 1";
                                        $qry2->executa($sql2);
                                        $primeira_nota = $qry2->data["primeiranota"];
 
                                        
                                        $ultima_nota = '';
                                        $sql2 = "SELECT numnotafiscal as ultima_nota FROM 
                                        tbentrega WHERE $where  $where_lote_atual order by numnotafiscal desc limit 1";
                                        $qry2->executa($sql2);
                                        $ultima_nota = $qry2->data["ultima_nota"];
 
                                        
                                        
                                      
                                        $tot_pendente = $tot_lote - ($tot_entrega + $tot_devolvido);
                                        $ie = "Conta";
                                        $tot_ie = 0;

                                        $digi=0;
                                        $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot_digi FROM tbentrega 
                                               WHERE $where  $where_lote_atual AND (tbentrega.idtipomovimento = 222 or tbentrega.idtipomovimento = 221) 
                                               limit 1";
                                        $qry2->executa($sql2);
                                        $digi = $qry2->data["tot_digi"];

                                        echo "        <td align='center'>".$tot_entrega."</td>";
                                      
                                        echo "        <td align='center'><a href=\"javascript:document.form_pesquisa.opt.value='D';document.form_pesquisa.ver_detalhes.value='devolvidos';document.form_pesquisa.numlotecliente.value='$numlotecliente';document.form_pesquisa.dataemissao.value='$dataemissao';document.form_pesquisa.tot_lote.value='$tot_lote';document.form_pesquisa.submit();\">".$tot_devolvido."</a></td>";
                                        
                                        echo "        <td align='center'><a href=\"javascript:document.form_pesquisa.opt.value='D';document.form_pesquisa.ver_detalhes.value='pendentes';document.form_pesquisa.numlotecliente.value='$numlotecliente';document.form_pesquisa.dataemissao.value='$dataemissao';document.form_pesquisa.tot_lote.value='$tot_lote';document.form_pesquisa.submit();\">".$tot_pendente."</a></td>";
                                        echo "        <td align='center'>".$digi."</td>";
                                       
                                                    
                  
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
                                        $Total_Digi = $Total_Digi + $digi;
                                        $res_final = $res_final + $res;
                                        $tbase = $tbase + $repasse; 
                                       

                                        if($tot_entrega > 0){
                                                $qry2->nrw=0;
                                               

                                        }

                                        echo "</tr>";
                                }

                                //totalizador
                                echo "<tr bgcolor=#dddddd>";          // cor cinza +- forte
                                echo "        <td align='center'><b>".Totais."</b></td>";
                                echo "        <td align='center'><b></b></td>";
                                echo "        <td align='center'><b>".$Total_Lote."</b></td>";
                                echo "        <td align='center'><b>".$Total_Entregue."</b></td>";
                                echo "        <td align='center'><b>".$Total_Devolvido."</b></td>";
                                echo "        <td align='center'><b>".$Total_Pendente."</b></td>";
                                echo "        <td align='center'><b>".$Total_Digi."</b></td>";
                               
                                echo "</tr>";
                             
                               
                                
                                

									
                                if($total_tot_devolvido > 0){
										echo "<br>";
										echo "<br>";
										echo "<br>";
										echo "<table align='left'>";
                                        global $where;
                                        echo "<tr>";
									 	echo "<td align='center'>";
										
										echo "<br><br><br><center><img src=\"graf_mensuracao_mot_dev.php?where=$where\"></center>";
										echo "</tr>";
										echo "</td>";
										
										
                                }
                                
                                
                                
                                
								echo "</table>";
                        }

                        break;

                        case "D":
                        //detalhe

                        $where = " AND tbentrega.idtransportadora=".$_SESSION["IDTRANSP"];

                        if ($codbase > 0){
                                $where .= " AND tbentrega.codbase = '$codbase'";
                                $porbase=1;
                        }else{
                                //todas: todas bases que a pessoa tem direito
                                $qry->nrw = 0;
                                $sql = "SELECT codbasedireito FROM tbdireitoauditoria WHERE codbase=".$_SESSION["IDBASE"]." and codbasedireito<>".$_SESSION["IDBASE"];
                                $qry->executa($sql);
                                $where.= " AND (tbentrega.codbase = '".$_SESSION["IDBASE"]."'";

                                for ($j=0;$j<$qry->nrw;$j++){
                                        $qry->navega($j);
                                        $where.= " or tbentrega.codbase = '".$qry->data["codbasedireito"]."'";
                                }

                                $where.= ")";
            }

                        session_unregister('sessao_string_retornar');
                        $sessao_string_retornar = "?";

                        if($codcliente > 0)
                        $where2.= " AND tbentrega.codcliente='$codcliente'";
                        $sessao_string_retornar .= "codcliente=$codcliente&";

                        if($codigoproduto > 0)
                        $where2.= " AND tbentrega.codigoproduto='$codigoproduto'";
                        $sessao_string_retornar .= "codigoproduto=$codigoproduto&";

                        if($data_de and grava_data($data_de)!="NULL")
                        $where.= " AND tbentrega.$tipo_data >= '".grava_data($data_de)."'";
                        $sessao_string_retornar .= "data_de=$data_de&tipo_data=$tipo_data&";

                        if($data_ate and grava_data($data_ate)!="NULL")
                        $where.= " AND tbentrega.$tipo_data <= '".grava_data($data_ate)."'";
                        $sessao_string_retornar .= "data_ate=$data_ate&";

                        if($lote_de > 0)
                        $where.= " AND tbentrega.$tipo_lote >= '".$lote_de."'";
                        $sessao_string_retornar .= "lote_de=$lote_de&";

                        if($lote_ate > 0)
                        $where.= " AND tbentrega.$tipo_lote <= '".$lote_ate."'";
                        $sessao_string_retornar .= "lote_ate=$lote_ate&tipo_lote=$tipo_lote&tot_lote=$tot_lote&";

                        if($numlotecliente > 0)
                        $where.= " AND tbentrega.numlotecliente='$numlotecliente'";
                        $sessao_string_retornar .= "numlotecliente=$numlotecliente&";

                        if($dataemissao)
                        $where.= " AND tbentrega.dataemissao='$dataemissao'";
                        $sessao_string_retornar .= "dataemissao=$dataemissao&";

                        if($ver_detalhes=="pendentes")
                        $where.= " AND (tbentrega.st isnull OR tbentrega.st='' OR (tbentrega.st<>'E' AND tbentrega.st<>'D'))";
                        elseif($ver_detalhes=="sinistradas")
                        $where.= " AND tbentrega.idtipomovimento='132'";
                        elseif($ver_detalhes=="devolvidos")
                        $where.= " AND tbentrega.st='D' ";

                        $sessao_string_retornar .= "ver_detalhes=$ver_detalhes&";
                        $sessao_string_retornar .= "opt=$opt&codbase=$codbase&";

                        $sessao_string_retornar = substr($sessao_string_retornar,0,strlen($sessao_string_retornar)-1);
                        session_register('sessao_string_retornar');

                        $qry->nrw = 0;
                        $sql = "SELECT tbentrega.st,tbentrega.idinterno,tbentrega.idexterno,tbentrega.dataoperacao,tbentrega.datapromessa,tbenderecoentrega.cidadeentrega,					tbenderecoentrega.cepentrega,tbenderecoentrega.nomeentrega,tbentrega.codcliente,tbentrega.codigoproduto,
						tbtipomovimento.nometipomovimento,tbentrega.idtipomovimento FROM tbentrega WHERE tbentrega.idinterno=tbenderecoentrega.idinterno AND tbentrega.idtipomovimento=tbtipomovimento.idtipomovimento $where2 $where ORDER BY tbentrega.codcliente,tbentrega.codigoproduto,tbentrega.dataoperacao,tbenderecoentrega.cidadeentrega,tbenderecoentrega.nomeentrega,tbentrega.idtipomovimento";

                        $qry->executa($sql);

                        echo "<table width='800' align='left'>";
                        if ($qry->nrw){
                                echo "<tr><td colspan=9 align=center><b><font color='#FF0000'>ENCOMENDAS ".strtoupper($ver_detalhes)."</font></b></td></tr>";

                                if($numlotecliente > 0)
                                echo "<tr><td colspan=9><b>Lote Externo: <font color='#990000'>".$numlotecliente."</font>&nbsp;&nbsp;&nbsp;&nbsp;Total: <font color='#990000'>".$qry->nrw."</font>/<font color='#990000'>$tot_lote</font></b></td></tr>";
                                elseif($dataemissao)
                                echo "<tr><td colspan=9><b>Data de Emissão: <font color='#990000'>".mostra_data($dataemissao)."</font>&nbsp;&nbsp;&nbsp;&nbsp;Total: <font color='#990000'>".$qry->nrw."</font>/<font color='#990000'>$tot_lote</font></b></td></tr>";

                                echo "<tr>
                                 <td>ID Interno</td>
                                 <td>ID Externo</td>
                                 <td>Destinatário</td>
                                 <td>Dt Operação</td>
                                 <td>Dt Promessa</td>
                                 <td>Atraso</td>
                                 <td>Cidade</td>
								 <td>CEP</td>
                                 <td>Status</td>
                               </tr>";

                                for($i=0;$i<$qry->nrw;$i++){
                                        $qry->navega($i);

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
                                                echo "<tr bgcolor=#cccccc ><td colspan=5><b>Cliente:</b> <font color='#990000'>".$qry2->data["nomecliente"]."</font></td><td colspan=5><b>Total:</b> <font color='#990000'>$tot_cliente/".$qry->nrw."</font></td></tr>";

                                        }

                                        if($array_codigoproduto[$i]!=$array_codigoproduto[$i-1]){

                                                $qry2->data["tot_produto"] = "";
                                                $sql2 = "SELECT count(tbentrega.idinterno) as tot_produto FROM tbentrega WHERE tbentrega.codcliente= '".$qry->data["codcliente"]."' AND codigoproduto= '".$qry->data["codigoproduto"]."' $where";
                                                $qry2->executa($sql2);
                                                $tot_produto = $qry2->data["tot_produto"];

                                                $qry2->data["nomeproduto"] = "";
                                                $sql2 = "SELECT nomeproduto FROM tbproduto WHERE codigoproduto= '".$qry->data["codigoproduto"]."'";
                                                $qry2->executa($sql2);
                                                echo "<tr bgcolor=#dddddd ><td colspan=5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Produto:</b> <font color='#990000'>".$qry2->data["nomeproduto"]."</font></td><td colspan=5><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:</b> <font color='#990000'>$tot_produto/$tot_cliente </font></td></tr>";
                                                $j=0;
                                        }

                                        echo "<tr ".(($j%2)?"bgcolor=#eeeeee":"").">
                                 <td><a href='auditoria.php?opt=S&data_de=$data_de&data_ate=$data_ate&id_transportadora=$id_transportadora&id_base=$id_base&id_cliente=$id_cliente&id_produto=$id_produto&idinterno=".$qry->data["idinterno"]."&lote_de=$lote_de&lote_ate=$lote_ate&tipo_lote=$tipo_lote&tipo_data=$tipo_data&so_barrabairro=$so_barrabairro&audita_todos_movs=$audita_todos_movs'>".$qry->data["idinterno"]."</a></td>
                                 <td>".$qry->data["idexterno"]."</td>
                                 <td>".strtoupper(substr($qry->data["nomeentrega"],0,25))."</td>
                                 <td>".mostra_data($qry->data["dataoperacao"])."</td>
                                 <td>".mostra_data($qry->data["datapromessa"])."</td>
                                 <td>".intval(date_dif(mostra_data($qry->data["datapromessa"]),date("d/m/Y")))."</td>
                                 <td>".strtoupper($qry->data["cidadeentrega"])."</td>
								                  <td>".strtoupper($qry->data["cepentrega"])."</td>";

                                 if ($qry->data["st"]=="D"){
                                     $sql= "SELECT idmotivo FROM tbentrega WHERE idinterno = '".$qry->data["idinterno"]."' and idtipomovimento = '".$qry->data["idtipomovimento"]."' LIMIT 1";
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
                                     echo "<td>".strtoupper($qry->data["nometipomovimento"])."</td>";

                                 echo "
                               </tr>";
                                        $j++;
                                }
                        }
                        else
                        echo "<tr><td colspan=9 align=center><font color=#ff0000>Nenhuma encomenda encontrada</font></td></tr>";
                        echo "<tr><td>&nbsp;</td></tr>";
                        echo "<tr><td colspan=9 align=center><a href='javascript:document.form_pesquisa.submit();'>Voltar</a></td></tr>";
                        echo "</table>";
                        break;
                }
				
?>
        
<? $con->desconecta(); ?>
</body>
</html>