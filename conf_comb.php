<?
$prgTit = "Conferencia de combustivel";
require_once("inc/config.inc");

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);

require_once("inc/header.inc");

if(isset($_POST['enviar'])){
  $nota_p = $_POST['nota'];
  $voucher_p = $_POST['voucher'];

  if(!empty($nota_p)){
    if(!empty($voucher_p)){
      $qry3->executa("SELECT * FROM tb_voucher_combustivel WHERE voucher = '$voucher_p'");
      if($qry3->nrw == 0){
        $qry2->executa(
          "INSERT INTO tb_voucher_combustivel(voucher,datacadastro,nota)
            VALUES ('".$voucher_p."','".date('Y-m-d H:i:s')."','".$nota_p."')"); 
      }else{
        echo "<script>alert('Voucher duplicado')</script>";
      }  
    }else{
      echo "<script>alert('Preencha o voucher')</script>";
    }  
  }else{
    echo "<script>alert('Campo de nota vazio')</script>";
  }
}
?>

  <body marginheight="0" marginwidth="0">
      <table class="tabela" style="width:800px; margin:0 auto">
        <tr bgcolor="#eeeeee">
          <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Conferencia de combustivel</b></font></font><font size="4"><b> ::..</b></font></td>
        </tr>
      </table>
<form action=<?=$PHP_SELF;?> method=POST>
	<br><br>		
    <table class="tabela" style="width:800px; margin:0 auto">
      <tr bgcolor="#FFFFFF">
        <td >Nota:</td>
        <td ><input type=text name="nota" id="nota" value="<?php echo $nota_p; ?>"></td> 
        <td >Voucher:</td>
        <td ><input type=text name="voucher" id="voucher"></td>
        <td ><input type=submit name="enviar" value="Gravar"></td>
      </tr>
   
    </table> <br/><br/>
  </form>

<form action=<?=$PHP_SELF;?> method=POST>
<table class="tabela" style="width:800px; margin:0 auto">
  <tr bgcolor="#FFFFFF">
    <td >Nota:</td>
    <td ><input type=text name="nota_pesq" id="nota_pesq" value="<?php echo $nota_pesq;?>"/> </td> 
      <td >Voucher:</td>
      <td ><input type=text name="voucher_pesq" id="voucher_pesq" value="<?php echo $voucher_pesq;?>"></td>
      <td >Campanha/Ano:</td>
      <td ><input type=text name="campanha" id="camp" value="<?php echo $campanha;?>"></td>
    <td ><input type=submit name="pesquisar" value="Pesquisar"></td>
  </tr>
</table>
</form>

<?php
if(isset($_POST['pesquisar'])){
  $nota_pesq = $_POST['nota_pesq'];
  $voucher_pesq = $_POST['voucher_pesq'];
  $campanha = $_POST['campanha'];
  $where = 'WHERE 1=1 ';
  if(!empty($nota_pesq)){
    $where .= " AND vc.nota = '".$nota_pesq."' ";
  }
  if(!empty($voucher_pesq)){
    $where .= " AND vc.voucher = '".$voucher_pesq."' ";
  }
  if(!empty($campanha)){
    $where .= " AND de.campanha LIKE '".$campanha."'";
  }
  $qry->executa(
    "SELECT vc.voucher, de.campanha,de.usuario,de.valor,b.nomebase
       FROM tb_voucher_combustivel vc
       LEFT JOIN tb_demillus_extra de ON vc.voucher = de.voucher
       LEFT JOIN tbbase b ON de.codbase = b.codbase
       $where
       ORDER BY vc.id DESC");     

  ?>
    <table class="tabela" style="width:800px; margin:0 auto">
     <tr bgcolor=#eeeeee>
     <td align='left'><b>Voucher</b></td>
     <td align='left'><b>Campanha</b></td>
     <td align='left'><b>Usuário</b></td>
     <td align='left'><b>Valor</b></td>    
     <td align='left'><b>Mei</b></td>  
     </tr>
  <?php
  $valor_total = 0;
  for ($i=0;$i<$qry->nrw;$i++){
    $qry->navega($i);
    if(empty($qry->data['campanha'])){
  ?>
    <tr style="background-color:red;">
      <td style="color:white"><?php echo $qry->data['voucher'];?></td>
      <td style="color:white"><?php echo $qry->data['campanha'];?></td>
      <td style="color:white"><?php echo $qry->data['usuario'];?></td>
      <td style="color:white"><?php echo number_format($qry->data['valor'], 2, ',', '.');?></td>
      <td style="color:white"><?php echo $qry->data['nomebase'];?></td>
    </tr>
  <?php

    }else{
      $valor_total += $qry->data['valor'];
  ?>
    <tr <?php if($i%2 == 1): echo "style='border-bottom:1px solid #A9A9A9' bgcolor='#FFFFF0'"; else: echo "bgcolor='#FFFFFF'"; endif; ?>>
      <td><?php echo $qry->data['voucher'];?></td>
      <td><?php echo $qry->data['campanha'];?></td>
      <td><?php echo $qry->data['usuario'];?></td>
      <?php if($qry->data['valor'] > 0): ?>
        <td><?php echo "<strong style='color:green'>".number_format($qry->data['valor'], 2, ',', '.')."</strong>";?></td>
      <?php else: ?>
        <td><?php echo "<strong style='color:red'>".number_format($qry->data['valor'], 2, ',', '.')."</strong>";?></td>
      <? endif; ?>
      <td><?php echo $qry->data['nomebase'];?></td>
    </tr>
  <?php

    }
  }
  ?>
  <tr bgcolor="#FFFFFF">
    <td><strong>TOTAL</strong></td>
    <td></td>
    <td></td>
    <td><?php echo number_format($valor_total, 2, ',', '.'); ?></td>
    <td></td>
  </tr>
  </table> 
<?php
}
?>

<?php
if(empty($nota_p)){ 
?>
  <script type="text/javascript">
    document.getElementById("nota").focus();
  </script>  
<?php
}else{
?>
  <script type="text/javascript">
    document.getElementById("voucher").focus();
  </script>    
<?php
}
// pega o Footer
require_once("inc/footer.inc");
