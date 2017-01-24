<?php
/**
 * Fatura Demillus Base
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel


// pega a configuracao
include("classes/diversos.inc.php");
// seta o link atual

$qry  = new consulta($con);
echo "NOTAS:<br>";
if(isset($_POST['enviar'])){
  $arquivo = $_FILES['arquivo']['tmp_name'];
  $fop = fopen($arquivo, 'r');
  while($linha = fgets($fop)){
    $numnota = trim(substr($linha, 40, 14));
    if(is_numeric($numnota)){
      $tarifa = str_replace(",",".",trim(substr($linha, 61, 6)));
      $icms = str_replace(",",".",trim(substr($linha, 77, 6)));
      $cte = trim(substr($linha, 99, 46));

      $dataformat = explode("/", trim(substr($linha, 9, 16)));
      $emissao_cte = $dataformat[2].'-'.$dataformat[1].'-'.$dataformat[0];
      $conhecimento = trim(substr($linha, 26, 10));
      echo $numnota." / $conhecimento - $cte - $emissao_cte <br>";
      $qry->executa("UPDATE tbentrega SET serie_cte = '$conhecimento',tarifa= '$tarifa', num_cte = '$cte', emissao_cte = '$emissao_cte', valor_icms = '$icms' WHERE numnotafiscal = '$numnota'");
      echo "UPDATE tbentrega SET serie_cte = '$conhecimento',tarifa= '$tarifa', num_cte = '$cte', emissao_cte = '$emissao_cte', valor_icms = '$icms' WHERE numnotafiscal = '$numnota'";
    }

  }
  fclose($fop);
}
?>
<div class="box" style="margin: 0 auto;">
<form enctype="multipart/form-data" action="#"  method="post">  
  SELECIONE O ARQUIVO
  <table BORDER=0>
  <tr>
    <td><input type=file name="arquivo"></td>
    <td><input type=submit name="enviar" value="Enviar Arquivo"></td>
  </tr>
</table>
</form>
</div>
<?php
// pega o Footer
require_once("inc/footer.inc");