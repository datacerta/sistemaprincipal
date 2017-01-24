
<style>
@font-face {
    font-family: myFirstFont;
    src: url(3OF9_NEW.TTF);
}

  .listacodbar{
    font-family: myFirstFont;
    font-size: 40px;
  }
</style>
<?
//Ultima alteração - Ravnus - Fabio - 28/06/2007
//inclui biblioteca de controles
include("classes/diversos.inc.php");

//testa sessão
if (VerSessao()==false){
	header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}

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

$qry_setor = new consulta($con);



$sql->executa("select * from tbtipolista where codigotipolista=$cblista");
$sql3->executa("select * from tblogin where codlogin =".$_SESSION['IDUSER']);
$sql4->executa("select * from tbbase where codbase=".$_SESSION['IDBASE']);

$data = date("d/m/Y");


if($enviar=="enviar" || $inf == 2){


	$sql->executa("select * from tbtipolista where codigotipolista=$cblista"); //TIPO DE LISTA
	$sql1->executa("select * from tblista where numlista=$lista"); //LISTA  (*) Daniel tirou a parte "codigotipolista=$cblista and "
	//$sql2->executa("select idinterno, numlista from tbmovimento where numlista=$lista  group by idinterno,numlista order by idinterno"); //MOVIMENTO
	$sql2->executa("select idinterno, numlista from tbentrega 
                  where numlista=$lista 
                  order by idexterno"); //ENTREGA
                  
                  
              $sql="select nomebase from tbbase,tblista 
                  where 
                  tbbase.codbase = '".$sql1->data["codbase"]."'
                  and tblista.numlista=$lista ";
                  //echo $sql;
                  //die;
                  
                  $sql10->executa($sql);
                  
                  $sql="select DISTINCT  numlotecliente from tbentrega 
                  where 
                  tbentrega.numlista=$lista  ORDER BY numlotecliente";
                  $qry_setor->executa($sql);
                  $setor = '';
                  if($qry_setor->nrw != 1){
                    $fontSize = "style='font-size:20px; font-weight:bold'";
                  }else{
                    $fontSize = "style='font-weight:bold'";
                  }
                  for($j=0;$j<$qry_setor->nrw;$j++){
                            $qry_setor->navega($j);               
                            $setor .=  $qry_setor->data["numlotecliente"]." ";
                  }
                  
                  
                                  

     
          $sql2->executa("select idinterno, numlista from tbentrega where numlista=$lista order by idexterno"); //ENTREGA
        
        //--------------------------------------------------------------------------------

	$qtd_encomendas = $sql2->nrw;

	$sql3->executa("select sum(quantidadevolumes) as sum_qtd_vol 
                 from tbentrega where numlista='$lista'"); //somatorio da quantidade de volumes
	$sum_qtd_vol = $sql3->data["sum_qtd_vol"];

	



	$sql3->executa("select * from tblogin where codlogin =".$_SESSION['IDUSER']); //LOGIN
	$sql4->executa("select * from tbbase where codbase=".$_SESSION['IDBASE']); //BASE
	//$sql8->executa("select distinct(idinterno) from tbmovimento where numlista=$lista  and (idtipomovimento=119 or idtipomovimento=121) order by idinterno"); //MOVIMENTO


	if($sql1->data["codcourier"]<>"")
	$sql5->executa("select * from tbcourier where 
        codigocourier=".$sql1->data["codcourier"]); //COURIER

	else if($sql1->data["codbase"]<>"")
	$sql5->executa("select * from tbbase where codbase=".$sql1->data["codbase"]); //BASE
	echo "
	  <font size=1>

      <tr>";
    //echo "<td width='25%' align='center'><strong>E-SISCO</strong></td>";
	echo "<td width='10%' align='center'>&nbsp;</td>";


   for($i=0;$i<2;$i++){
 
	echo "<table width='100%' border='0'>";
	echo "<td colspan='7' nowrap><div align='left'><strong>LISTA DE ENTREGAS DEMILLUS</strong></div></td>";

	echo "<td width='25%' align='center'><strong></strong></td>
      </tr>
      	  <tr>
		    <td align='left' nowrap>ENTREGADOR : ".$sql10->data["nomebase"] . "</td>
			<TR>
		    <td colspan='7' align='left' nowrap> DATA EMISSÃO : ".$data."</td>
		    <TR>
			<td  align='left' nowrap>Lista N&uacute;mero: $lista</td>
		  </tr>
		  <tr>
		     ";
	

	//if de verificacao principal - existencia da lista
	if(!$sql1->nrw){

		echo "<br><table align='center'>
				<tr>
	  				<td  align='center'><br><font size=3 color='#FF0000'>
            <strong>Lista $lista não encontrada.</strong></font><br></td>
    		</tr></table><br>";

	}else{


     echo "<br>";
         echo "<br>";
          echo "<br>";
           echo "<br>";
            echo "<br>";
            
	
		echo "  
  	  </table>
	  <table width='400' border='1'>
	  <tr>
    	  <td width='600'colspan=2  align='center'> TOTAIS</td>
	     

	  </tr>
	  <tr>
	      <td width='300' align='left'> NOTAS </td>
	      <td width='300' align='right'>VOLUMES</td>
	      </tr>";
        
        
         Echo"<tr>
	      <td width='300' align='center' ><strong><font size = '40'> $qtd_encomendas</td>
	      <td width='300' align='center'><strong><font size = '40'>$sum_qtd_vol</td>
	    
        </tr>";
        
      
      
         Echo"<tr>
	       <td width='600' colspan=2  align='center'> SETOR </td>
         </TR>";
         
        echo"<td width='600' colspan=2  align='center'><font size = '16' ".$fontSize."> $setor</td>
	     
         </tr>";
        
         Echo"<tr>
	       <td width='600' colspan=2  align='center'> PROTOCOLO </td>
         </TR>";
         
        echo"<td width='300' align='left'> ASS.:</td>
	      <td width='300' align='left'>DATA:</td>
	      
        </tr>";
      
        
        
        
        
        }
	    
        
        
        
        



			
		
	
	


     }
	

		echo "<tr><td colspan='10'><center>
		 	  <font size='2'>
		 	  <input type='button' value='Imprimir' onclick='self.print()'>
		 	  <input type='button' value='Voltar' onclick=\"javascript:document.location.href='print_lista.php?cblista=$cblista&op=$op&inf=$inf'\">
		 	  </font>
		 	</center></td></tr></tbody></table>";

    
	echo "<br><br><span class='listacodbar'>*$lista*</span>";


	exit;

}//Fecha if($_POST['enviar'] || $inf == 2)

  ?>
<html>
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
    <table width="622">
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
<table width="622">
  <tr>
    <td colspan="6"><div align="center"><font size=1>Copyright(c) Fast Courier/Rio
        de janeiro - 2000/2004</font></div></td>
  </tr>
</table>
</form>

</div>
<? $con->desconecta(); ?>
</body>
</html>
