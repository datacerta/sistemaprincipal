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

if(isset($_POST['enviar'])){
  $arquivo = $_FILES['arquivo']['tmp_name'];
  $fop = fopen($arquivo, 'r');
  while($linha = fgets($fop)){

  $qry->executa("UPDATE tbentrega SET idtipomovimento = '786' WHERE numnotafiscal = '".trim($linha)."'");
 
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