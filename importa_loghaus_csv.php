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
        $sql =  "SELECT COUNT(*) as quant FROM tb_demillus_revend WHERE id_revend = ".trim($linhaex[0]);
        $qry2->executa($sql);
        echo $qry2->data['quant']."<br>";
        if(!$qry2->data['quant']){
          echo "Revendedora: ".$linhaex[0]." não existe";
          $sql =  "INSERT INTO tb_demillus_revend (id_revend, nome_revend,id_setor,cep,uf,endereco,bairro,cidade,latitude,longitude)
                   VALUES
                   ('".trim($linhaex[0])."','".trim(utf8_decode($linhaex[1]))."','".trim($linhaex[2])."','".trim($linhaex[3])."','".trim(utf8_decode($linhaex[4]))."','".trim(utf8_decode($linhaex[5]))."','".trim(utf8_decode($linhaex[6]))."','".trim(utf8_decode($linhaex[7]))."','".trim($linhaex[8])."','".trim($linhaex[9])."')";
          $qry->executa($sql);
        }
        $i++;
      }else{
        $first = true;
      }
     
    }

  echo "Importação Finalizada $i RAs";
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