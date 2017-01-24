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

function formataMes($mes){
  switch ($mes) {
    case 'jan':
      return '01';
      break;
    case 'fev':
      return '02';
      break;
    case 'mar':
      return '03';
      break;
    case 'abr':
      return '04';
      break;
    case 'mai':
      return '05';
      break;
    case 'jun':
      return '06';
      break;
    case 'jul':
      return '07';
      break;
    case 'ago':
      return '08';
      break;
    case 'set':
      return '09';
      break;
    case 'out':
      return '10';
      break;
    case 'nov':
      return '11';
      break;
    case 'dez':
      return '12';
      break;
    default:
      return '0';
      break;
  }
}

$qry = new consulta($con);
$qry2 = new consulta($con);

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
$i = 0;
if(isset($_POST['enviar'])){
    $arquivo = $_FILES['arquivo']['tmp_name'];
    $fop = fopen($arquivo, 'r');
    while($linha = fgets($fop)){
      $linhaex = explode(";", $linha);

      $camp = trim($linhaex[0]);
      $setor = trim($linhaex[1]);
      $cidade = utf8_encode(trim($linhaex[2]));

      $coletaex = explode("/", trim($linhaex[3]));
      $coleta = $coletaex[2]."-".$coletaex[1]."-".$coletaex[0];

      $entregaex = explode("/", trim($linhaex[4]));
      $entrega = $entregaex[2]."-".$entregaex[1]."-".$entregaex[0];

      $sql =  "SELECT COUNT(*) as quant FROM tb_demillus_campanha WHERE data_coleta = '$coleta' AND num_setor = '$setor'";
      var_dump($sql);
      $qry2->executa($sql);
      if(!$qry2->data['quant']){
        echo "INSERIDO SETOR: $setor CIDADE: $cidade COLETA: $coleta <br>";
        $sql =  "INSERT INTO tb_demillus_campanha (num_campanha, data_coleta,data_entrega,nome_cidade,num_setor,uf)
                 VALUES
                 ('$camp','$coleta','$entrega','$cidade','$setor','')";

        $qry->executa($sql);
      }
      $i++;
    }

  echo "Importação Finalizada $i Setores";
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