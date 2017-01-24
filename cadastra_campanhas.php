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
$qry3 = new consulta($con);

if(isset($_POST['enviar'])){
  $campanha = $_POST['campanha'];

  $dataformata = explode("/", $_POST['datacoleta']);
  $datacoleta = $dataformata['2']."-".$dataformata['1']."-".$dataformata['0'];
  $dataformatb = explode("/", $_POST['dataentrega']);
  $dataentrega = $dataformatb['2']."-".$dataformatb['1']."-".$dataformatb['0'];

  $cidade = $_POST['cidade'];
  $setor = (int)$_POST['setor'];
  $uf = $_POST['uf'];

  $qry->executa("SELECT * FROM tb_demillus_campanha WHERE num_setor = '$setor' AND data_coleta = '$datacoleta' AND data_entrega = '$dataentrega'");
  if($qry->nrw){
    echo "<div style='width:700px; margin:0 auto; color:red; font-weight:bold; text-align:center; margin-bottom:25px'>Setor $setor já cadastrado</div>";
  }else{
    if(!empty($cidade) AND !empty($setor)){
      $qry2->executa("INSERT INTO tb_demillus_campanha(num_campanha,data_coleta,data_entrega,nome_cidade,num_setor,uf) VALUES('$campanha','$datacoleta','$dataentrega','$cidade','$setor','$uf')");
    }else{
      echo "<div style='width:700px; margin:0 auto; color:red; font-weight:bold; text-align:center; margin-bottom:25px'>Campos Vazios</div>";
    }
  }
}
?>
<div class="box" style="margin: 0 auto;">
<form enctype="multipart/form-data" action="<?=$selfLink?>"  method="post">  
  <table BORDER=0>
  <tr>
    <td>Campanha</td>
    <td>
      <select name="campanha">
        <option <?php if($campanha == 1) echo "selected"; ?>>1</option>
        <option <?php if($campanha == 2) echo "selected"; ?>>2</option>
        <option <?php if($campanha == 3) echo "selected"; ?>>3</option>
        <option <?php if($campanha == 4) echo "selected"; ?>>4</option>
        <option <?php if($campanha == 5) echo "selected"; ?>>5</option>
        <option <?php if($campanha == 6) echo "selected"; ?>>6</option>
        <option <?php if($campanha == 7) echo "selected"; ?>>7</option>
        <option <?php if($campanha == 8) echo "selected"; ?>>8</option>
        <option <?php if($campanha == 9) echo "selected"; ?>>9</option>
        <option <?php if($campanha == 10) echo "selected"; ?>>10</option>
        <option <?php if($campanha == 11) echo "selected"; ?>>11</option>
        <option <?php if($campanha == 12) echo "selected"; ?>>12</option>
        <option <?php if($campanha == 13) echo "selected"; ?>>13</option>
        <option <?php if($campanha == 14) echo "selected"; ?>>14</option>
        <option <?php if($campanha == 15) echo "selected"; ?>>15</option>
        <option <?php if($campanha == 16) echo "selected"; ?>>16</option>
        <option <?php if($campanha == 17) echo "selected"; ?>>17</option>
        <option <?php if($campanha == 18) echo "selected"; ?>>18</option>
        <option <?php if($campanha == 19) echo "selected"; ?>>19</option>
      </select>
    </td>
    <td>Data de Coleta:</td>
    <td><input type=text name="datacoleta" class="data" value="<?php if(isset($_POST['datacoleta'])) echo $_POST['datacoleta']; ?>"></td>
    <td>Data de Entrega:</td>
    <td><input type=text name="dataentrega" class="data" value="<?php if(isset($_POST['dataentrega'])) echo $_POST['dataentrega']; ?>"></td>
  </tr>
    <tr>
    <td>Cidade</td>
    <td><input type=text name="cidade" value="<?php if(isset($_POST['cidade'])) echo $_POST['cidade']; ?>"></td>
    <td>Setor:</td>
    <td><input type=text name="setor" value="<?php if(isset($_POST['setor'])) echo $_POST['setor']; ?>"></td>
    <td>UF:</td>
    <td><input type=text name="uf" value="<?php if(isset($_POST['uf'])) echo $_POST['uf']; ?>"></td>
  </tr>
  <tr>
    <td><input type=submit name="enviar" value="Cadastrar"></td>
  </tr>
</table>
</form>
<br><hr><br>

<?php 
if(isset($_POST['datacoleta'])){
?>
  <h3>Setores cadastrados na data: <?php echo $_POST['datacoleta']; ?></h3>
  <?php
  $qry3->executa("SELECT * FROM tb_demillus_campanha WHERE data_coleta = '$datacoleta'");
  ?>
  <table BORDER=0>
    <th>Campanha</th>
    <th>Data de Coleta</th>
    <th>Data de Entrega</th>
    <th>Cidade</th>
    <th>Setor</th>
    <th>UF</th>
  
  <?php
  for ($i=0;$i<$qry3->nrw;$i++){
    $qry3->navega($i);
  ?>
  <tr>
    <td><?php echo $qry3->data['num_campanha']; ?></td>
    <td><?php echo $qry3->data['data_coleta']; ?></td>
    <td><?php echo $qry3->data['data_entrega']; ?></td>
    <td><?php echo $qry3->data['nome_cidade']; ?></td>
    <td><?php echo $qry3->data['num_setor']; ?></td>
    <td><?php echo $qry3->data['uf']; ?></td>
  </tr>
  <?php
  }
  ?>
  </table>
<?php
}
?>
</div>
<script>
( function( $ ) {
  $(function() {
    $('.data').datepicker({  dateFormat: 'dd/mm/yy',   dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        nextText: 'Próximo',
        prevText: 'Anterior' });
    $('.data').mask("99/99/9999");

  });
} )( jQuery );  
</script>
<?php
// pega o Footer
require_once("inc/footer.inc");