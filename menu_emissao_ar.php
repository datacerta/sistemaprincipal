<?
//inclui biblioteca de controles
require_once("inc/config.inc");
//testa sessão
if (VerSessao()==false){
	header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}

$qry = new consulta($con);
$selfLink = HOST.$PHP_SELF."?token={$rnd}";
// pega o header
require_once("inc/header.inc");
?>
<html>
<head>
<link href="estilo.css" rel="stylesheet" type="text/css">
<link href="tahoma.css" rel="stylesheet" type="text/css">
<title>..:: Bem Vindo ao eFast - Emissão de AR ::..</title>
</head>
<body>

 <center>
  <table cellspacing=01 cellpadding=0 width=100% align=center>
   <form name="form_emissao_ar" action="print_ar_edn_pdf.php" method="POST" target="__top">
         <input type="hidden" name="opt" value="P">
      <tr align=center bgcolor=#eeeeee>
         <td><b>Transportadora:</b></td>
         <?
if ($idtransportadora > 0){
	echo "<td><b>Base:</b></td>";
	echo "<td><b>Cliente:</b></td>";
}
if ($codcliente > 0)
echo "<td><b>Produto</b></td>";

         ?>
         <td><b>Lote:</b></td>
         <td>&nbsp;</td>
      </tr>

      <tr  align=center bgcolor=#eeeeee>
         <td>
			<select name="idtransportadora" onchange="javascript:document.form_emissao_ar.target='';document.form_emissao_ar.action='<?=$PHP_SELF?>';document.form_emissao_ar.submit();"><?=combo("SELECT tb.idtransportadora,tb.nometransportadora FROM Tbdireitotransportadora as dir, Tbtransportadora as tb  WHERE dir.CodLogin = '".$_SESSION['IDUSER']."' and tb.idtransportadora = dir.idtransportadora ORDER BY tb.nometransportadora",$idtransportadora);?></select>
         </td>
         
            <?
         if ($idtransportadora > 0){
         	
         	if($_SESSION['IDCLIENTE'] && $_SESSION['IDCLIENTE'] > 0)
         	$sql_combo_cliente = "SELECT codcliente,nomecliente FROM tbcliente WHERE idtransportadora = '$idtransportadora' and codcliente = '". $_SESSION['IDCLIENTE']."' ORDER BY nomecliente";
         	else
         	$sql_combo_cliente = "SELECT codcliente,nomecliente FROM tbcliente WHERE idtransportadora = '$idtransportadora' ORDER BY nomecliente";
         	
         	
         	echo "<td><select name='codbase'>";?><?=combo("SELECT tb.codbase,tb.nomebase FROM tbdireitoauditoria as dir, tbbase as tb  WHERE dir.codbase = '".$_SESSION['IDBASE']."' and tb.idtransportadora=$idtransportadora and tb.codbase = dir.codbasedireito ORDER BY tb.nomebase",$codbase)?><?="</select></td>";
         	echo "<td><select name='codcliente' onchange=\"javascript:document.form_emissao_ar.target='';document.form_emissao_ar.action='$PHP_SELF';document.form_emissao_ar.submit();\">";?><?=combo($sql_combo_cliente,$codcliente);?><?="</select></td>";
         	
         }
         if ($codcliente > 0){
         	echo "<td><select name='codigoproduto'>";?><?=combo("SELECT tbproduto.codigoproduto,tbproduto.nomeproduto FROM tbproduto,tbclienteproduto WHERE tbclienteproduto.codigoproduto=tbproduto.codigoproduto and tbclienteproduto.idtransportadora = '$idtransportadora' and tbclienteproduto.codcliente = '$codcliente' ORDER BY tbproduto.nomeproduto",$codigoproduto)?><?="</select></td>";
         }
             ?>
         <td>
			<input type=text size=8 name="lote" value='<?=$lote;?>'>
         </td>
         <td>
             <input type=submit value="Procurar">
         </td>
         </form>
       </tr>
    <table>

</center>
</body>
</html>