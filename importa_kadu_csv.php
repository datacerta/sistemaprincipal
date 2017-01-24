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

$qry = new consulta($con);
$qry2 = new consulta($con);
$first = false;

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
$i = 0;
if(isset($_POST['enviar'])){
  
    $arquivo = $_FILES['arquivo']['tmp_name'];
    $fop = fopen($arquivo, 'r');
    while($linha = fgets($fop)){
      if($first){
        $linhaex = explode(";", $linha);
        $sql =  "SELECT COUNT(*) as quant FROM tbentrega WHERE idexterno = '".trim($linhaex[0])."'";
        $qry2->executa($sql);
        if($qry2->data['quant']){
          $dataex = explode("/", $linhaex[2]);
          if(empty($linhaex[4]))
            $idmotivo = 0;
          else 
            $idmotivo = $linhaex[4];
          if(count($dataex) != 1){
            $dataoperacao = $dataex[2]."-".$dataex[1]."-".$dataex[0];
            $sql =  "UPDATE tbentrega SET numlista = '".trim($linhaex[1])."', dataoperacao = '$dataoperacao', idtipomovimento = '".trim($linhaex[3])."', idmotivo = '".trim($idmotivo)."', st = '".trim($linhaex[5])."', obsentrega = '".trim($linhaex[6])."' WHERE idexterno = '".trim($linhaex[0])."'";
          }else{
            $sql =  "UPDATE tbentrega SET numlista = '".trim($linhaex[1])."', idtipomovimento = '".trim($linhaex[3])."', idmotivo = '".trim($idmotivo)."', st = '".trim($linhaex[5])."', obsentrega = '".trim($linhaex[6])."' WHERE idexterno = '".trim($linhaex[0])."'";
          }

          $qry->executa($sql);
        }
        $i++;
      }else{
        $first = true;
      }
     
    }

  echo "Importação Finalizada $i Notas";
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