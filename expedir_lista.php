<?
//inclui biblioteca de controles
include("classes/diversos.inc.php");

//testa sessão
if (VerSessao()==false){
	header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}

//definição de objetos
$qry = new consulta($con);

if($_POST['enviar']){
	//INSERTS E UPDATES
	//definição de objetos
	$qry1 = new consulta($con);
	$qry2 = new consulta($con);
	$qry3 = new consulta($con);
	$qry4 = new consulta($con);
	$qry5 = new consulta($con);
	
	$no=0;
	$ok=0;
	$qtd=0;
  
  if(!$lista)
     {
     echo "Lista deve ser informada";
     die;
     }
  
  
  
  
	
  if($_SESSION['IDBASE'] == 0 or $_SESSION['IDBASE']==1)
	    $qry3->executa("SELECT codigotipolista,quantidadetotal FROM tblista WHERE numlista=$lista");
  else    
	   $qry3->executa("SELECT codigotipolista,quantidadetotal FROM tblista WHERE numlista=$lista AND codbaseorigem=".$_SESSION['IDBASE']);
  
	if ($qry3->nrw > 0){	
		//$qtd_tblista = $qry3->data["quantidadetotal"];
		
		//nova maneira
		$qry->executa("SELECT count(idinterno) as qtd_nalista FROM tbentrega WHERE numlista=$lista");
		$qtd_tblista = $qry->data["qtd_nalista"];
		
		$qry->executa("SELECT idtipomovimento,idtipoexpede FROM tbtipolista WHERE codigotipolista=$cblista");
		$tipomovimentoexpede = $qry->data["idtipoexpede"];
		$tipomovimentomonta = $qry->data["idtipomovimento"];

		$qry->executa("SELECT count(idinterno) as qtd_tbentrega FROM tbentrega 
                   WHERE numlista=$lista AND (idtipomovimento=$tipomovimentomonta 
                   OR idtipomovimento=$tipomovimentoexpede or idtipomovimento = 105  or idtipomovimento = 182 )");
		$qtd_tbentrega = $qry->data["qtd_tbentrega"];
		
		if($qtd_tblista == $qtd_tbentrega){
			
			$qry->executa("BEGIN WORK");

			$qry->res="";
			$sql = "UPDATE tbmovimento SET codlogin = ".$_SESSION["IDUSER"].", idtipomovimento=$tipomovimentoexpede, codbase=".$_SESSION["IDBASE"].", dataoperacao='".date("Y-m-d")."',
			horaoperacao='".date("H:i:s")."', codigocourier=".(($courier)? $courier :"NULL") ." WHERE tbmovimento.numlista=$lista AND (tbmovimento.idtipomovimento=$tipomovimentomonta OR tbmovimento.idtipomovimento=$tipomovimentoexpede) AND tbmovimento.idinterno in (Select idinterno from tbentrega where tbentrega.numlista=$lista AND (tbentrega.idtipomovimento=$tipomovimentomonta OR tbentrega.idtipomovimento=$tipomovimentoexpede))";
			$qry->executa($sql);
			if($qry->res){
			
				$qry1->executa("SELECT codtipoestoque FROM tbtipomovimento WHERE idtipomovimento=".$tipomovimentoexpede);
				$codtipoestoque = $qry1->data["codtipoestoque"];			
			
				$qry->res="";
				$sql = "UPDATE tbentrega SET idtipomovimento=$tipomovimentoexpede, dataoperacao='".date("Y-m-d")."', 
				codigocourier=".(($courier)? $courier :"NULL") ."  WHERE numlista=$lista AND (idtipomovimento=$tipomovimentomonta OR idtipomovimento=$tipomovimentoexpede)";
				$qry->executa($sql);
				if($qry->res){
					
					$qry->res="";
					$sql = "UPDATE tblista SET  dataexpedicao='".grava_data($datalibera)."', horaexpedicao='".date('H:i:s')."', lacre='$lacre', conhecimento='$conhece',
					 veiculo='$veiculo', rastreada = 'N',  codloginexpede='".$_SESSION["IDUSER"]."', codcourier=".(($courier)? $courier :"NULL")." WHERE numlista=$lista";
					$qry->executa($sql);
					if($qry->res)
					$qry->executa("COMMIT WORK");
				}
			
         
      
      
      
			}
		
			$sql = "SELECT count(idinterno) as tot_exp FROM 
              tbentrega WHERE numlista=$lista 
              AND idtipomovimento = $tipomovimentoexpede";
			$qry->executa($sql);
			$tot_exp = $qry->data["tot_exp"];
			if($tot_exp)
			$msg = "Lista $lista Expedida com sucesso";
			else
			$msg = "Nenhuma encomenda da lista $lista foi expedida";
		
		}else
		$msg = "A lista $lista não foi expedida pois existem encomendas que não estão disponíveis para a expedição";
		
	}else{
		$msg = "A lista não pode ser expedida porque ela não pertence a sua base.";
	}
}
?>

<html>
<head>
<title>..:: Expedir Listas ::..</title>
<link href="css/table_2.css" rel="stylesheet" type="text/css">
<link href="css/tip.css" rel="stylesheet" type="text/css">


<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>
<body onload="javascript:document.libera_lista.lista.focus();">
<div align="center">
  <form name="libera_lista" action="<? 
  //PHP_SELF;
  //Monta  href do form em tempo de execução
  if($base <= 0 or !$base)
  $base = $_SESSION["IDBASE"];
  
  $str="expedir_lista.php?";
  if(isset($cblista)){
  	$str.="cblista=$cblista";
  }
  if(isset($nlista)){
  	$str.="&nlista=$abrlst";
  }
  if(isset($op)){
  	$str.="&op=$op";
  }
  if(isset($courier)){
  	$str.="&courier=$courier";
  }
  if(isset($base)){
  	$str.="&base=$base";
  }
  echo $str;
            ?>" method="post">
     <table class = tabela width=800 align=left >
      <tr bgcolor="#eeeeee"> 
        <td  colspan=6> <div align="center"> <font size="6"><b><font size="3"><strong>..:: 
            </strong></font></b><strong><font color="#990000" size="3">Expedir 
            Listas </font></strong></font> <strong><font size="3"><b>::..</b></font></strong><font size="3"><b></b></font></div></td>
      </tr>
      <tr bgcolor="#FFFFFF"> 
        <td width="159" height="23"> <div align="right">Liberando: </div></td>
        <td height="23"> 
          <?
            if (isset($cblista)&&($cblista!=0)||($cblista!="")){
            	$qry->executa("select * from tbtipolista where codigotipolista=$cblista");
            	echo "<font color=#990000>".$qry->data[1]."</font>";
            }else{
            	echo "<font color=#990000>Tipo de Lista não identificado.</font>";
            }
		?>
        </td>
        <td width="131" height="23"> 
		  <? 
		  if($courier > 0){
		  	echo"<div align='right'>Para:</div></td>";
		  	echo"<td colspan='3'>";
		  	$qry->executa("select * from tbcourier where codigocourier=$courier and codbase=".$_SESSION['IDBASE']);
		  	echo "<font color=#990000>".$qry->data[2]."</font>";
		  }else if(!isset($courier) && $cblista==1){
		  	echo "<font color=#990000>Não identificado.</font>";
		  }
		?>
        </td>
      </tr>
      <tr> 
        <td colspan="6" bgcolor="#eeeeee"> <center>
          </center>
          <center>
            <font size="2"><strong>..::</strong></font><font color='#990000'> 
            Dados para Libera&ccedil;&atilde;o </font><strong><font size="2">::..</font></strong> 
          </center></td>
      </tr>
      <tr> 
        <td> <div align="right">Lista:</div></td>
        <td width="148"> <input name="lista" type="text" size="20" maxlength="20"> 
        </td>
        <td colspan="2"> <div align="right">Total Expedidas:</div></td>
        <td colspan="2"> 
          <?
		echo "<font color=#990000>".$tot_exp."</font>";
		  ?>
        </td>
        <? /*
        <td colspan="2"> <div align="right">Quantidade de Entregas:</div></td>
        <td colspan="2"> 
          <?
		echo "<font color=#990000>".$qtd."</font>";
		  ?>
        </td>
        */ ?>
      </tr>
      <tr> 
        <td height="24"> 
          <div align="right"> Data da Libera&ccedil;&atilde;o:</div></td>
        <td> <input name="datalibera" type="text" value="<?echo date('d/m/Y');?>" size="20" maxlength="20"> 
        </td>
        <? /*
        <td colspan="2" align="left"><div align="right">Expedidas:</div></td>
        <td width="82" align="left"><? echo "<font color=#990000>".$ok."</font>";?></td>
        <td width="37" align="left">&nbsp;</td>
        */ ?>
      </tr>
      <tr> 
        <td height="25"><div align="right">Lacre:</div></td>
        <td><input name="lacre" type="text" value="0"  size="20" maxlength="20"></td>
        <? /*
        <td colspan="2" align="left"><div align="right">N&atilde;o Expedidas:</div></td>
        <td align="left"><? echo "<font color=#990000>".$no."</font>";?></td>
        <td align="left">&nbsp;</td>
        */ ?>
      </tr>
      <tr> 
        <td height="25"><div align="right">N&uacute;mero do Conhecimento:</div></td>
        <td><input name="conhece" type="text" value="0"  size="20" maxlength="20"></td>
        <td colspan="4" align="left">&nbsp;</td>
      </tr>
      <tr> 
        <td height="25"><div align="right">Ve&iacute;culo(modelo/placa):</div></td>
        <td colspan="3"><input name="veiculo" type="text" value="NA"  size="50" maxlength="50"> 
        
        
        
          <div align="right"></div></td>
        <td colspan="2"><div align="center">
        <?
		  if($cblista == 1 and $lista > 0){
		  	echo "<a href='print_lista.php?op=7&inf=2&enviar=enviar";
		  	if($cblista){
		  		echo"&cblista=$cblista";
		  	}if($lista){
		  		echo"&nlista=$lista";
		  		echo"&lista=$lista";
		  	}if($courier){
		  		echo"&courier=$courier";
		  	}if($base){
		  		echo"&base=$base";
		  	}
		  	echo"' target='__top' >Imprimir Lista</a>";
		  }
		?>
		  
		  </div></td>
      </tr>
      <tr> 
        <td height="17" colspan="4"> <center>
            <?
		if(isset($msg)){
			echo $msg."<font color='#990000'> Lista: $lista </font>" ;
		}
		
		?>
          </center></td>
        <td height="17" colspan="2"> <div align="right"> 
            <input type="hidden" name="enviar2" value="enviar">
            <input name="enviar" type="submit"  value="enviar">
          </div></td>
      </tr>
      <tr> 
        <td height="17" colspan="6"><div align="center"><A href="selecao_lista.php"><< 
            Anterior</A></div>
          <div align="center"></div></td>
      </tr>
    
      <tr>
        <td colspan="6" bgcolor="#eeeeee" height="5"></td>
      </tr>
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
