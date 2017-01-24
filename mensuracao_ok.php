<?
require_once("inc/config.inc");

//testa sessão
if (VerSessao()==false){
        header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry_cli = new consulta($con);
$selfLink = HOST.$PHP_SELF."?token={$rnd}";
// pega o header
require_once("inc/header.inc");

?>
<html>
<head>
<link href="css/table_2.css" rel="stylesheet" type="text/css">
<link href="css/tip.css" rel="stylesheet" type="text/css">


<title>..:: Mensuração ::..</title>


</head>    

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

             <table class="tabela" style="width: 800px; margin:0 auto">
                     <tr bgcolor=#eeeeee>
                     <!--<td align='center'><b>Transportadora</b></td>-->
                                          <td align='center'><b>Base</b></td>
                                          <td align='center'><b>Cliente</b></td>
                                          <td align='center'><b>Produto</b></td>
                                          <td align='center'><b>Data de</b></td>
                                          <td align='center'><b>Data até</b></td>
                                          <td colspan = '4' align='center'><b>Tipo Data</b></td>
                                          <td align='center'><b></b></td>
                                         
                                          
                                          
                                  </tr>
                                  <tr valign="middle" bgcolor=#eeeeee>
                                          <!--<td align="center"><font color="#"><?=$nometransportadora;?></td>-->
                                          <td align="center"><select name='codbase' style='width:100px;'><? combo("SELECT tb.codbase,tb.nomebase FROM tbdireitoauditoria as dir, tbbase as tb  WHERE dir.codbase = '".$_SESSION['IDBASE']."' and tb.idtransportadora='".$_SESSION['IDTRANSP']."' and tb.codbase = dir.codbasedireito ORDER BY tb.nomebase",$codbase,"T") ;?></select></td>
                                          <td align="center"><select name='codcliente' style='width:100px;' onchange="javascript:document.form_pesquisa.codigoproduto.disabled=true;document.form_pesquisa.opt.value='';document.form_pesquisa.tot_lote.value='';document.form_pesquisa.numlotecliente.value='';document.form_pesquisa.submit();"><? combo("SELECT codcliente,nomecliente FROM tbcliente WHERE idtransportadora='".$_SESSION["IDTRANSP"]."' ".(($_SESSION['IDCLIENTE'] > 0) ? " AND codcliente='". $_SESSION['IDCLIENTE']."'" : "")." ORDER BY nomecliente",$codcliente,(($_SESSION['IDCLIENTE'])?"":"T")) ;?></select></td>
                                          <td align="center"><select name='codigoproduto' style='width:140px;'><? combo("SELECT tbclienteproduto.codigoproduto,tbproduto.nomeproduto FROM tbclienteproduto, tbproduto WHERE tbclienteproduto.codigoproduto=tbproduto.codigoproduto AND tbclienteproduto.codcliente='".intval($codcliente)."' AND tbclienteproduto.idtransportadora='".$_SESSION["IDTRANSP"]."' ORDER BY tbproduto.nomeproduto",$codigoproduto,"T") ;?></select></td>
                                          
                                      <td><input type=text size=8 name=data_de value='<?=((!$data_de and $_POST["codcliente"]=="")?date("01/m/Y"):"$data_de");?>' maxlength="10"></td>
            <td><input type=text size=8 name=data_ate value='<?=((!$data_ate and $_POST["codcliente"]=="")?ult_dia_mes(date("m"),date("Y")).date("/m/Y"):"$data_ate");?>' maxlength="10"></td>
             
              <td colspan=4 nowrap>
                    CLB <input type=radio name='tipo_data'  <?=(($tipo_data == "datacoletadobase")?"checked":"");?>  value='datacoletadobase'>
                    CLF <input type=radio name='tipo_data'  <?=(($tipo_data == "datacoletado")?"checked":"");?>  value='datacoletado'>
                    EM<input type=radio name='tipo_data'  <?=(($tipo_data == "dataemissao")?"checked":"");?>  value='dataemissao'></td>
                 
              
                                          
                                          
                                          <td align="center"> <input type="submit"  name="submete" value="Procurar"></td>
                                  </tr>
                        </table>
                        
                         <br><br><br><br>
                        </form>
                <?
                Switch($opt){

                        case "P":
                        
                        if(!$tipo_data)
                           {
                           echo "Selecione o tipo de data";
                           die;
                           }
                        
                        
                        if($tipo_data=='datacoletadobase')
                           $label_data='C.Base';
                        if($tipo_data=='dataemissao')
                           $label_data='Emissão';
                        if($tipo_data=='datacoletado')
                           $label_data='C.Fast';
                        
                        
                        
                        
						   $sql = "SELECT grupo FROM tbcliente WHERE codcliente = '$codcliente'";
                           $qry3->Executa($sql);
						   if (strlen($qry3->data["grupo"])>0){
						  		$codcliente = $qry3->data["grupo"];
									}
							
						//procurar
						
						
						

                        $where = "tbentrega.idtransportadora=".$_SESSION["IDTRANSP"];


                        
                        $where = "tbentrega.idtransportadora=".$_SESSION["IDTRANSP"];

                        if ($codbase > 0){
                                $porbase=1;
                                $where .= " AND tbentrega.codbase = '$codbase'";
                        }



                        if($codcliente > 0)
                        $where.= " AND tbentrega.codcliente in ($codcliente)";
						
                        if($codigoproduto > 0)
                        $where.= " AND tbentrega.codigoproduto='$codigoproduto'";

                        if($data_de and grava_data($data_de)!="NULL")
                        $where.= " AND tbentrega.$tipo_data >= '".grava_data($data_de)."'";

                        if($data_ate and grava_data($data_ate)!="NULL")
                        $where.= " AND tbentrega.$tipo_data <= '".grava_data($data_ate)."'";

                        if($lote_de > 0)
                        $where.= " AND tbentrega.$tipo_lote >= '".$lote_de."'";

                        if($lote_ate > 0)
                        $where.= " AND tbentrega.$tipo_lote <= '".$lote_ate."'";

                        $audita=$where;

                        $qry->nrw = 0;

                        if($porbase=1)
                        $sql = "SELECT COUNT(tbentrega.idinterno) as tot_lote,tbentrega.$tipo_data FROM 
                               tbentrega WHERE $where GROUP BY tbentrega.$tipo_data ORDER BY tbentrega.$tipo_data";
                        else
                        $sql = "SELECT COUNT(tbentrega.idinterno) as tot_lote,
                                tbentrega.numlotecliente,tbentrega.dataemissao 
                                FROM tbentrega WHERE $where GROUP BY tbentrega.numlotecliente,
                                tbentrega.dataemissao ORDER BY tbentrega.$tipo_data,
                                tbentrega.numlotecliente";
                        $qry->executa($sql);
                        //echo $sql;
                       // echo "<br>";

                                                //coloque a var abaixo para 1 devido ao problema de digitaçào entre Vix e Rio
                                                $porbase==1;
                        if($qry->nrw){
                                echo "<table class='tabela' style='width:800px; margin:0 auto' border='0'>";
                                echo "<tr bgcolor=#cccccc>";          // cor cinza + forte
                                if($porbase!=1) echo "        <td align='center'><b>Nº Lote</b></td>";
                                echo "        <td align='center'><b>$label_data</b></td>";
                                echo "        <td align='center'><b>Arq.</b></td>";
                                echo "        <td align='center'><b>Entr.</b></td>";
                                echo "        <td align='center'><b>%</b></td>";
                                echo "        <td align='center'><b>Dev.</b></td>";
                                echo "        <td align='center'><b>%</b></td>";
                                echo "        <td align='center'><b>Pend.</b></td>";
                                echo "        <td align='center'><b>%</b></td>";
                                echo "        <td align='center'><b>N.Col.</b></td>";
                                echo "        <td align='center'><b>TLMK.</b></td>";
                                echo "        <td align='center' width='40'><b>D1</b></td>";
                                echo "        <td align='center' width='40'><b>D2</b></td>";
                                echo "        <td align='center' width='40'><b>D3</b></td>";
                                echo "        <td align='center' width='40'><b>D4</b></td>";
                                echo "        <td align='center' width='40'><b>D5</b></td>";
                                echo "        <td align='center' width='40'><b>D6</b></td>";
                                echo "        <td align='center' width='40'><b>D7</b></td>";
                                echo "        <td align='center' width='40'><b>D>7</b></td>";
                                
                                echo "</tr>";

                                for($i=0;$i<$qry->nrw;$i++){
                                        $qry->navega($i);


                                        $dataemissao = $qry->data["$tipo_data"];
                                        $tot_lote = $qry->data["tot_lote"];
                                        if($porbase!=1){
                                                $numlotecliente = $qry->data["numlotecliente"];
                                                $where_lote_atual = " AND tbentrega.numlotecliente='$numlotecliente'";
                                        }else
                                        $where_lote_atual = " AND tbentrega.$tipo_data='$dataemissao'";



                                        echo "<tr ".(($i%2==0)?"":"bgcolor=#eeeeee").">";    // cor cinza + fraco

                                        //echo "<tr>";
                                        if($porbase!=1) echo "        <td align='center'>".$numlotecliente."</td>";
                                        echo "        <td align='center'>".mostra_data($dataemissao)."</td>";
                                        echo "        <td align='center'>".$tot_lote."</td>";


                                        $tot_entrega = 0;
                                        $sql2 = "SELECT COUNT(tbentrega.idinterno) 
                                                 as tot_entrega FROM tbentrega WHERE $where 
                                                 AND tbentrega.st='E' $where_lote_atual";
                                        $qry2->executa($sql2);
                                        //echo $sql2;
                                        //echo "<br>";
                                        $tot_entrega = $qry2->data["tot_entrega"];

                                        $tot_devolvido = 0;
                                        $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot_devolvido FROM tbentrega 
                                               WHERE $where AND tbentrega.st='D' $where_lote_atual";
                                        $qry2->executa($sql2);
                                        $tot_devolvido = $qry2->data["tot_devolvido"];

                                       
                                        $tot_tlmk = 0;
                                        $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot_tlmk FROM tbentrega 
                                                 WHERE $where AND tbentrega.st='T' $where_lote_atual";
                                        $qry2->executa($sql2);
                                        $tot_tlmk = $qry2->data["tot_tlmk"];

                                        
                                        
                                        $tot_nao_coletado = 0;
                                        $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot_nao_coletado FROM tbentrega WHERE $where AND 
                                               tbentrega.idtipomovimento='100' $where_lote_atual";
                                        $qry2->executa($sql2);
                                        $tot_nao_coletado = $qry2->data["tot_nao_coletado"];
                                        
                                        

                                        $tot_pendente = $tot_lote - ($tot_entrega + $tot_devolvido + $tot_tlmk + $tot_nao_coletado);
                                        $ie = "Conta";
                                        $tot_ie = 0;

                                        echo "        <td align='center'>".$tot_entrega."</td>";
                                        echo "        <td align='center'>".number_format(round(($tot_entrega/$tot_lote)*100,2),2,",",".")."</td>";
                                        echo "        <td align='center'><a href=\"$PHP_SELF?data_de=$data_de&data_ate=$data_ate&tipo_data=$tipo_data&lote_de=$lote_de&lote_ate=$lote_ate&tipo_lote=$tipo_lote&codbase=$codbase&codcliente=$codcliente&codigoproduto=$codigoproduto&opt=D&ver_detalhes=devolvidos&numlotecliente=$numlotecliente&dataemissao=$dataemissao&tot_lote=$tot_lote\" onclick=\"javascript:document.form_pesquisa.opt.value='D';document.form_pesquisa.ver_detalhes.value='devolvidos';document.form_pesquisa.numlotecliente.value='$numlotecliente';document.form_pesquisa.dataemissao.value='$dataemissao';document.form_pesquisa.tot_lote.value='$tot_lote';document.form_pesquisa.submit();\">".$tot_devolvido."</a></td>";
													
                                        echo "        <td align='center'>".number_format(round(($tot_devolvido/$tot_lote)*100,2),2,",",".")."</td>";
//                               
										                    
                                        //pendentes
                                        echo " <td align='center'> <a href=\"$PHP_SELF?data_de=$data_de&data_ate=$data_ate&tipo_data=$tipo_data&lote_de=
                                        $lote_de&lote_ate=$lote_ate&tipo_lote=$tipo_lote&codbase=$codbase&codcliente=$codcliente&codigoproduto=
                                        $codigoproduto&opt=D&ver_detalhes=pendentes&numlotecliente=$numlotecliente&dataemissao=
                                        $dataemissao&tot_lote=$tot_lote\" onclick=\"javascript:document.form_pesquisa.opt.value='D';
                                        document.form_pesquisa.ver_detalhes.value='devolvidos';
                                        document.form_pesquisa.numlotecliente.value='$numlotecliente';
                                        document.form_pesquisa.dataemissao.value='$dataemissao';
                                        document.form_pesquisa.tot_lote.value='$tot_lote';document.form_pesquisa.submit();\">".$tot_pendente."</td>";
                                        
                                        
                                        echo "        <td align='center'>".number_format(round(($tot_pendente/$tot_lote)*100,2),2,",",".")."</td>";
                                       
                                       
                                       //Não coletadas
                                         echo "<td align='center'><a href=\"$PHP_SELF?data_de=$data_de&data_ate=$data_ate&tipo_data=$tipo_data&lote_de=$lote_de&lote_ate=$lote_ate&tipo_lote=$tipo_lote&codbase=$codbase&codcliente=$codcliente&codigoproduto=$codigoproduto&opt=D&ver_detalhes=nao_coletadas&numlotecliente=$numlotecliente&dataemissao=$dataemissao&tot_lote=$tot_lote\" onclick=\"javascript:document.form_pesquisa.opt.value='D';document.form_pesquisa.ver_detalhes.value='nao_coletadas';document.form_pesquisa.numlotecliente.value='$numlotecliente';document.form_pesquisa.dataemissao.value='$dataemissao';document.form_pesquisa.tot_lote.value='$tot_lote';document.form_pesquisa.submit();\">".$tot_nao_coletado."</a></td>";
                                       
                                        //Telemarketing
                                        echo "<td align='center'><a href=\"$PHP_SELF?data_de=$data_de&data_ate=$data_ate&tipo_data=$tipo_data&lote_de=$lote_de&lote_ate=$lote_ate&tipo_lote=$tipo_lote&codbase=$codbase&codcliente=$codcliente&codigoproduto=$codigoproduto&opt=D&ver_detalhes=telemarketing&numlotecliente=$numlotecliente&dataemissao=$dataemissao&tot_lote=$tot_lote\" onclick=\"javascript:document.form_pesquisa.opt.value='D';document.form_pesquisa.ver_detalhes.value='telemarketing';document.form_pesquisa.numlotecliente.value='$numlotecliente';document.form_pesquisa.dataemissao.value='$dataemissao';document.form_pesquisa.tot_lote.value='$tot_lote';document.form_pesquisa.submit();\">".$tot_tlmk."</a></td>";
                                       
                                       
                                       
                                        $total_tot_lote = $total_tot_lote + $tot_lote;
                                        $total_tot_entrega =  $total_tot_entrega + $tot_entrega;
                                        $total_tot_devolvido = $total_tot_devolvido + $tot_devolvido;
                                        $total_tot_pendente = $total_tot_pendente + $tot_pendente;
                                        $total_tot_nao_coletado = $total_tot_nao_coletado + $tot_nao_coletado;
                                        $total_tot_tlmk      = $total_tot_tlmk + $tot_tlmk;
                                        $total_tot_ie = $total_tot_ie + $tot_ie;

                                        if($tot_entrega > 0){
                                                $qry2->nrw=0;
                                                                                                //voltar para 4

									                   	$sql2 = "SELECT 1 as d, count(tbentrega.idinterno) as tot_d FROM 
                                              tbentrega WHERE $where AND tbentrega.st='E' $where_lote_atual
                                              and ((tbentrega.dataentrega - (tbentrega.datacoletadobase))) 
                                              <= 1 UNION ALL SELECT 
                                              ((tbentrega.dataentrega - (tbentrega.datacoletadobase))) 
                                              as d, count(tbentrega.idinterno) as tot_d FROM 
                                              tbentrega WHERE $where AND tbentrega.st='E' $where_lote_atual 
                                              and ((tbentrega.dataentrega - (tbentrega.datacoletadobase)))
                                               > 1 GROUP BY d ORDER BY d";
                                                $qry2->executa($sql2);

                                                if($qry2->nrw){

                                                        $naoprimeira = 0;
                                                        $tot_d_menor0 = 0;
                                                        $tot_d_maiorq7 = 0;
                                                        $tot_d[0] = 0;
                                                        $tot_d[1] = 0;
                                                        $tot_d[2] = 0;
                                                        $tot_d[3] = 0;
                                                        $tot_d[4] = 0;
                                                        $tot_d[5] = 0;
                                                        $tot_d[6] = 0;
                                                        $tot_d[7] = 0;
                                                        $tot_d[8] = 0;

                                                         for($j=0;$j<$qry2->nrw;$j++){
                                                                $qry2->navega($j);

                                                                if($qry2->data["d"] <= 7)
                                                                    $tot_d[$qry2->data["d"]] = $qry2->data["tot_d"]; //echo "                <td align='center'>D".$qry2->data["d"]." ".$qry2->data["tot_d"]."</td>";
                                                                else
                                                                        $tot_d_maiorq7 = $tot_d_maiorq7 + $qry2->data["tot_d"];
                                                        }
                                                        $tot_d[8] = $tot_d_maiorq7;//echo "                <td align='center'>D > 7 ".$tot_d_maiorq7 ."</td>";

                                                       
                                                        
                                                        echo "                <td align='center'>".$tot_d[1]."</td>"; //coluna D1
                                                        echo "                <td align='center'>".$tot_d[2] ."</td>"; //coluna D2
                                                        echo "                <td align='center'>".$tot_d[3] ."</td>"; //coluna D3
                                                        echo "                <td align='center'>".$tot_d[4] ."</td>"; //coluna D4
                                                        echo "                <td align='center'>".$tot_d[5] ."</td>"; //coluna D5
                                                        echo "                <td align='center'>".$tot_d[6] ."</td>"; //coluna D6
                                                        echo "                <td align='center'>".$tot_d[7] ."</td>"; //coluna D7
                                                        echo "                <td align='center'>".$tot_d[8] ."</td>"; //coluna D>7
                                                  

                                                        $total_tot_d[0] = $total_tot_d[0] + $tot_d[0];
                                                        $total_tot_d[1] = $total_tot_d[1] + $tot_d[1];
                                                        $total_tot_d[2] = $total_tot_d[2] + $tot_d[2];
                                                        $total_tot_d[3] = $total_tot_d[3] + $tot_d[3];
                                                        $total_tot_d[4] = $total_tot_d[4] + $tot_d[4];
                                                        $total_tot_d[5] = $total_tot_d[5] + $tot_d[5];
                                                        $total_tot_d[6] = $total_tot_d[6] + $tot_d[6];
                                                        $total_tot_d[7] = $total_tot_d[7] + $tot_d[7];
                                                        $total_tot_d[8] = $total_tot_d[8] + $tot_d[8];
                                                }

                                        }

                                        echo "</tr>";
                                }
                                
                                echo "<tr>";
                                

                                //totalizador
                                echo "<tr bgcolor=#dddddd>";          // cor cinza +- forte
                                echo "        <td align='center' ".(($porbase!=1)?"colspan='2'":"") ."><b>Totais</b></td>";
                                echo "        <td align='center'><b>".$total_tot_lote."</b></td>";
                                echo "        <td align='center'><b>".$total_tot_entrega."</b></td>";
                                echo "        <td align='center'><b>".number_format(round(($total_tot_entrega/$total_tot_lote)*100,2),2,",",".")."</b></td>";
                                echo "        <td align='center'><b>".$total_tot_devolvido."</b></td>";
                                echo "        <td align='center'><b>".number_format(round(($total_tot_devolvido/$total_tot_lote)*100,2),2,",",".")."</b></td>";
                                echo "        <td align='center'><b>".$total_tot_pendente."</b></td>";
                                echo "        <td align='center'><b>".number_format(round(($total_tot_pendente/$total_tot_lote)*100,2),2,",",".")."</b></td>";
                                echo "        <td align='center'><b>".$tot_nao_coletado."</b></td>";
                                echo "        <td align='center'><b>".$total_tot_tlmk."</b></td>";
                                echo "        <td align='center'><b>".$total_tot_d[1] ."</b></td>"; //coluna D1
                                echo "        <td align='center'><b>".$total_tot_d[2] ."</b></td>"; //coluna D2
                                echo "        <td align='center'><b>".$total_tot_d[3] ."</b></td>"; //coluna D3
                                echo "        <td align='center'><b>".$total_tot_d[4] ."</b></td>"; //coluna D4
                                echo "        <td align='center'><b>".$total_tot_d[5] ."</b></td>"; //coluna D5
                              
                                
								                
								
							                 echo "<td align='center'><b>".$total_tot_d[6] ."</b></td>"; //coluna D6
                    
                                echo "<td align='center'><b>".$total_tot_d[7] ."</b></td>"; //coluna D7
                    
                                echo "<td align='center'><b>".$total_tot_d[8] ."</b></td>"; //coluna D>7
                              
                                echo "</tr>";

                                echo "<tr>";
                               
                                 
                                $sql = "SELECT COUNT(tbentrega.idinterno) as tot, tbentrega.idtipomovimento  
                                FROM tbentrega WHERE $audita GROUP BY tbentrega.idtipomovimento ";
                                $qry->executa($sql);
                                // echo $audita;
                                
                                
                               
                                
                                if ($qry->nrw){
                                   echo "<tr>
                                   <th>Quantidade</th>
                                   <th  colspan=7 >Status</th>
                                   </tr>";

                                   For($i=0;$i<$qry->nrw;$i++){
                                      $qry->navega($i);
                                      $sql2 = "SELECT nometipomovimento FROM tbtipomovimento WHERE idtipomovimento = '".$qry->data["idtipomovimento"]."'";
                                      $qry2->executa($sql2);
                                      echo "<tr bgcolor=#".(($i%2)?"eeeeee":"ffffff").">
                                      <td>".$qry->data["tot"]."</td>
                                      <td colspan=17>".$qry2->data["nometipomovimento"]."</td>
                                      </tr>";      //S2
                                   }

							                    
                                 
                                 
                                 }           



                                 //Agrupa por clientes

                                 $sql = "SELECT  tbentrega.codcliente  
                                        FROM tbentrega where
                                  $audita GROUP BY  tbentrega.codcliente  ";
                                $qry->executa($sql);
                                // echo $sql;
                                
                               
                               echo "<tr><td colspan=18><th></th></tf></tr>";
                               
                                echo "<tr>";
                                
                                if ($qry->nrw){
                                   echo "<tr>
                                   <th align=left>Cliente</th>
                                   <th  colspan=2 align=left>07 DIAS</th>
                                   <th  colspan=2  align=left>15 DIAS</th>
                                   <th colspan=2  align=left>30 DIAS</th>
                                   </tr>";

                                   For($i=0;$i<$qry->nrw;$i++){
                                      $qry->navega($i);
                                      $t_7=0;
                                      $t_15=0;
                                      $t_30=0;
                                      $cc_cli = $qry->data["codcliente"];
                                      $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot, tbentrega.codcliente
                                               FROM tbentrega where $audita  and   st = '' and codcliente = $cc_cli
                                               and (CURRENT_DATE - datacoletadobase) BETWEEN  7 and  14
                                               GROUP BY tbentrega.codcliente 
                                               order by codcliente";
                                               $qry_cli->executa($sql2);
                                               if ($qry_cli->nrw)
                                                   $t_7 = $qry_cli->data["tot"];
                                               else
                                                  $t_7=0;    
                                               
                                      $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot, tbentrega.codcliente
                                               FROM tbentrega where $audita  and   st = '' and codcliente = $cc_cli
                                               and (CURRENT_DATE - datacoletadobase)BETWEEN  15 and  29
                                               GROUP BY tbentrega.codcliente 
                                               order by codcliente";
                                               $qry_cli->executa($sql2);
                                               if ($qry_cli->nrw)
                                                   $t_15 = $qry_cli->data["tot"];
                                               else
                                                  $t_15=0;
                                               
                                               
                                               
                                      $sql2 = "SELECT COUNT(tbentrega.idinterno) as tot, tbentrega.codcliente
                                               FROM tbentrega where $audita  and   st = ''  and codcliente = $cc_cli
                                               and (CURRENT_DATE - datacoletadobase) >=30
                                               GROUP BY tbentrega.codcliente 
                                               order by codcliente";
                                               $qry_cli->executa($sql2);
                                               //echo $sql2;
                                              if ($qry_cli->nrw)
                                                   $t_30 = $qry_cli->data["tot"];
                                               else
                                                  $t_30=0;
                                               
                                   
                                   
                                   
                                        $sql2 = "SELECT nomecliente from tbcliente where codcliente = $cc_cli";
                                               $qry_cli->executa($sql2);
                                               $nome_cli = $qry_cli->data["nomecliente"];
                                      
                                      
                                      
                                      
                                      
                                               //echo $sql2;
                                               //echo "<br>";
                                      
                                      //$qry2->executa($sql2);
                                      echo "<tr bgcolor=#".(($i%2)?"eeeeee":"ffffff").">
                                      <td >".$nome_cli."</td>
                                      <td colspan=2>".$t_7."</td>
                                      <td colspan=2 >".$t_15."</td>
                                      <td colspan=2 >".$t_30."</td>
                                      </tr>";      //S2
                                   }

							                    
                                 echo "</tr>";
                                 
                                 
                                 }   





                        }

                        break;

                        case "D":
                        //detalhe

                        $where = " AND tbentrega.idtransportadora=".$_SESSION["IDTRANSP"];

                        if ($codbase > 0){
                                $where .= " AND tbentrega.codbase = '$codbase'";
                                $porbase=1;
                        }
                        /*
                        else
                        {
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
            */

                        session_unregister('sessao_string_retornar');
                        $sessao_string_retornar = "?";

                        if($codcliente > 0)
                        $where2.= " AND tbentrega.codcliente in($codcliente)";
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
                        $where.= " AND tbentrega.$tipo_data='$dataemissao'";
                        $sessao_string_retornar .= "$tipo_data=$dataemissao&";

                        if($ver_detalhes=="pendentes")
                        $where.= " AND (tbentrega.st isnull OR tbentrega.st='') and tbentrega.idtipomovimento <> 100 ";
                        
                        elseif($ver_detalhes=="sinistradas")
                        $where.= " AND tbentrega.idtipomovimento='132'";
                        
                        
                        elseif($ver_detalhes=="nao_coletadas")
                        $where.= " AND tbentrega.idtipomovimento='100'";
                        
                        
                        elseif($ver_detalhes=="devolvidos")
                        $where.= " AND tbentrega.st='D' ";

                        elseif($ver_detalhes=="telemarketing")
                        $where.= " AND tbentrega.st='T' ";
                        

                        $sessao_string_retornar .= "ver_detalhes=$ver_detalhes&";
                        $sessao_string_retornar .= "opt=$opt&codbase=$codbase&";

                        $sessao_string_retornar = substr($sessao_string_retornar,0,strlen($sessao_string_retornar)-1);
                        session_register('sessao_string_retornar');

                        $qry->nrw = 0;
                        $sql = "SELECT tbentrega.st, tbentrega.datacoletadobase, tbentrega.idinterno,tbentrega.idexterno,tbentrega.numloteinterno,
                                tbentrega.dataoperacao,tbentrega.datapromessa,tbenderecoentrega.cidadeentrega,tbenderecoentrega.cepentrega,
                                tbenderecoentrega.nomeentrega,tbentrega.codcliente,tbentrega.codigoproduto,tbtipomovimento.nometipomovimento,
                                tbentrega.idtipomovimento FROM tbentrega, tbenderecoentrega, tbtipomovimento WHERE 
                                tbentrega.idinterno=tbenderecoentrega.idinterno AND tbentrega.idtipomovimento=tbtipomovimento.idtipomovimento 
                                $where2 $where ORDER BY tbentrega.codcliente,tbentrega.codigoproduto,tbentrega.dataoperacao,tbenderecoentrega.cidadeentrega,
                                tbenderecoentrega.nomeentrega,tbentrega.idtipomovimento";
                               //echo $sql;

                        $qry->executa($sql);

                        echo "<table class=tabela width='800'  align='left'>";
                        if ($qry->nrw){
                                echo "<tr><td colspan=9 align=center><b><font color='#FF0000'>ENCOMENDAS ".strtoupper($ver_detalhes)."</font></b></td></tr>";

                                if($numlotecliente > 0)
                                echo "<tr><td colspan=9><b>Lote Externo: <font color='#990000'>".$numlotecliente."</font>&nbsp;&nbsp;&nbsp;&nbsp;Total: <font color='#990000'>".$qry->nrw."</font>/<font color='#990000'>$tot_lote</font></b></td></tr>";
                                elseif($dataemissao)
                                echo "<tr><td colspan=9><b>Data $label_data <font color='#990000'>".mostra_data($dataemissao)."</font>&nbsp;&nbsp;&nbsp;&nbsp;Total: <font color='#990000'>".$qry->nrw."</font>/<font color='#990000'>$tot_lote</font></b></td></tr>";

                                echo "<tr>
                                 <td>ID Interno</td>
                                 <td>ID Externo</td>
                                 <td>Destinatário</td>
                                 <td>Coleta Base</td>
                                 <td>Atraso</td>
                                 <td>Cidade</td>
								 <td>Status</td>
                                 <td>Motivo</td>
                                  <td>Lote</td>
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
                                 <td><a href='auditoria_novo.php?opt=S&data_de=$data_de&data_ate=$data_ate&id_transportadora=$id_transportadora&id_base=$id_base&id_cliente=$id_cliente&id_produto=$id_produto&idinterno=".$qry->data["idinterno"]."&lote_de=$lote_de&lote_ate=$lote_ate&tipo_lote=$tipo_lote&tipo_data=$tipo_data&so_barrabairro=$so_barrabairro&audita_todos_movs=$audita_todos_movs'>".$qry->data["idinterno"]."</a></td>
                                 <td>".$qry->data["idexterno"]."</td>
                                 <td>".strtoupper(substr($qry->data["nomeentrega"],0,15))."</td>
                                 <td>".mostra_data($qry->data["datacoletadobase"])."</td>
                                 <td>".intval(date_dif(mostra_data($qry->data["datapromessa"]),date("d/m/Y")))."</td>
                                 <td>".strtoupper($qry->data["cidadeentrega"])."</td>";
						                   	 echo "<td>".strtoupper($qry->data["nometipomovimento"])."</td>";
                                 
                                 if ($qry->data["st"]=="D" or $qry->data["st"]=="T"){
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
                                   echo "<td>Não Aplicável</td>";
                                   
                                   
                                 

                                 echo "
                                 
                                 <td>".$qry->data["numloteinterno"]."</td>
                                 
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
