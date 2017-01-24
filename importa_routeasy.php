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
require_once("inc/config.inc");
// seta o link atual
$selfLink = HOST.$PHP_SELF;
// pega o header

require_once("inc/header.inc");
$qry = new consulta($con);
$qry2 = new consulta($con);
if(isset($_POST['enviar'])){
  $arquivo = $_FILES['arquivo']['tmp_name'];
  $fop = fopen($arquivo, 'r');
  $i = 0;
  while($linha = fgets($fop)){
  $linhaex = explode(";", $linha);
  
  foreach($linhaex as $linha){
    if($anterior == 'Entrega' or $anterior == 'Servi?o'){
      $i++;
      $qry->executa("SELECT e.numlista, dr.id_revend, e.numnotafiscal, dr.nome_revend, dr.latitude, dr.longitude FROM tbentrega e  LEFT JOIN tb_demillus_revend dr ON CAST(e.numconta as integer) = dr.id_revend WHERE e.numnotafiscal = '".trim($linha)."' ");
      $numlista = $qry->data['numlista'];
      $idrevend = $qry->data['id_revend'];
      $numnotafiscal = $qry->data['numnotafiscal'];
      $nome_revend = $qry->data['nome_revend'];
      $latitude = $qry->data['latitude'];
      $longitude = $qry->data['longitude'];

      $qry2->executa("INSERT INTO tb_demillus_routeasy(numlista,idrevend, numnota,nomera,latitude,longitude,sequencia,dataupload,idexterno) VALUES($numlista,$idrevend,$numnotafiscal, '$nome_revend', '$latitude', '$longitude', $i,'".date('Y-m-d')."','67100$numnotafiscal') ");
    }
    $anterior = utf8_decode($linha);
  }
}
  

  echo "Importação Finalizada";
  fclose($fop);
}
?>
<div class="box" style="margin: 0 auto;">
<form enctype="multipart/form-data" action="<?=$selfLink?>"  method="post">  
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