<?
$prgTit = "Atualiza RG";
require_once("inc/config.inc");

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);

require_once("inc/header.inc");

if(isset($_POST['enviar'])){
  $nota_p = $_POST['nota'];
  $rg_p = $_POST['rg'];

  if(!empty($nota_p)){
    if(!empty($rg_p)){
      if(is_numeric($rg_p)){
        $qry3->executa("SELECT * FROM tbentrega WHERE numnotafiscal = '$nota_p'");
        if($qry3->nrw > 0){
          $qry2->executa(
            "UPDATE tb_demillus_revend dr
              SET rg = $rg_p
              FROM tbentrega e
              WHERE e.numnotafiscal = '$nota_p' AND CAST(e.numconta as integer) = dr.id_revend");
          echo "<script>alert('Atualizado com sucesso!')</script>";
        }else{
          echo "<script>alert('Nota não existe')</script>";
        }
      }else{
        echo "<script>alert('Preencha somente os números do rg')</script>";
      }
    }else{
      echo "<script>alert('Campo de rg vazio')</script>";
    }  
  }else{
    echo "<script>alert('Campo de nota vazio')</script>";
  }
}
?>

  <body marginheight="0" marginwidth="0">
      <table class="tabela" style="width:800px; margin:0 auto">
        <tr bgcolor="#eeeeee">
          <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Atualiza RG</b></font></font><font size="4"><b> ::..</b></font></td>
        </tr>
      </table>
<form action=<?=$PHP_SELF;?> method=POST>
	<br><br>		
    <table class="tabela" style="width:800px; margin:0 auto">
      <tr bgcolor="#FFFFFF">
        <td >Nota:</td>
        <td ><input type=text name="nota" id="nota" ></td> 
        <td >RG:</td>
        <td ><input type=text name="rg" id="rg"></td>
        <td ><input type=submit name="enviar" value="Gravar"></td>
      </tr>
    </table> <br/><br/>
  </form>

<script type="text/javascript">
  document.getElementById("nota").focus();
</script>   
<?php
// pega o Footer
require_once("inc/footer.inc");
