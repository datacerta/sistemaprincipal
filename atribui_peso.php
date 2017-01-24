<?
//inclui biblioteca de controles
require_once("classes/diversos.inc.php");

//testa sessão
if (VerSessao()==false){
        header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);

?>
<html>
<head>
<link href="estilo.css" rel="stylesheet" type="text/css">
<link href="tahoma.css" rel="stylesheet" type="text/css">
<title>..:: Atribuir Sedex ::..</title>
</head>
<link href="estilo.css" rel="stylesheet" type="text/css">
<link href="tahoma.css" rel="stylesheet" type="text/css">

<body marginheight="0" marginwidth="0">
<?
        
?>
    <table width="800" align="left" border="0">
      <tr bgcolor="#eeeeee">
        <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Atribuir Sedex </b></font></font><font size="4"><b> ::..</b></font></td>
      </tr>
    </table>
    <form action=<?=$PHP_SELF;?> method=POST>

	<br><br>		
    <table align="left" border="0">
      <tr bgcolor="#FFFFFF">
        <td >Código de Barras:</td>
        <td ><input type=text name="codigobarras" id="codigobarras"></td>
        <td >Mensagem:</td>
        <td ><input type=text name=mensagem  value="<?php echo $mensagem; ?>" maxlength=40 size="50"></td>
        <td ><input type=submit name="enviar" value="Gravar"></td>
      </tr>
    </form>
    <?
        if(isset($_POST['enviar'])){
           if(!empty($mensagem) and !empty($codigobarras)){


           $sql="update tbentrega set numerosedex='$mensagem', st = 'S',idtipomovimento = '890' where idexterno = '$codigobarras'";
           $qry->executa($sql);
           if($qry->res){
              $msg = 'Salvo com sucesso!';
           }else{
              $msg = 'Erro ao gravar';
           }
           }else{
            $msg = 'Campo mensagem ou codigo de barras vazio';
           }
               
        }

                      
           
    ?>
        <?
            if ($msg){
                    echo "<TR>
                          <TD colspan=2><font color=#ff0000><B>$msg</font></td>
                              </tr>";
            }

            ?>
    </table>
<script type="text/javascript">
  document.getElementById("codigobarras").focus();
</script>    

</body>
</html>