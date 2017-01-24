<?
$prgTit = "Setores Pagos";
require_once("inc/config.inc");

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);

if($_POST['enviar'] == 'Gravar'){
  $campanha = (int)$_POST['campanha'];
  $ano = $_POST['ano'];
  $setor = (int)$_POST['setor'];
  $numloteinterno = $campanha."/".$ano;
  if(!empty($ano)){
    $sql="UPDATE tbentrega SET fatpago = '1' WHERE numloteinterno = '$numloteinterno' AND primeiroenvelope = $setor";
    $qry->executa($sql);
    echo "<script>alert('Atualizado com sucesso!')</script>";
  }else{
    echo "<script>alert('Campo ano vazio')</script>";
  }
}
  require_once("inc/header.inc");
  ?>

  <body marginheight="0" marginwidth="0">
  <table class="tabela" style="width:800px; margin:0 auto">
    <tr bgcolor="#eeeeee">
      <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Setores Pagos</b></font></font><font size="4"><b> ::..</b></font></td>
    </tr>
  </table>
  <form action=<?=$PHP_SELF;?> method=POST id="formpago" >
  	<br><br>		
      <table class="tabela" style="width:800px; margin:0 auto">
        <tr bgcolor="#FFFFFF">
          <td>Campanha:</td>
          <td><input type=text name="campanha" id="campanha" value="<?php echo $campanha?>"></td>
          <td>Ano:</td>
          <td><input type=text name="ano" id="ano" value="<?php echo $ano?>"></td>
          <td>Setor:</td>
          <td><input type=text name="setor" id="setor"></td>
          <td><input type=submit name="enviar" value="Gravar"></td>
        </tr>
      </table> 
    
    <br><hr><br>

      <table class="tabela" style="width:800px; margin:0 auto">
        <tr bgcolor="#FFFFFF">
        <?php
        $sql="SELECT COUNT(idinterno) as qtd FROM tbentrega WHERE fatpago = '1' AND dataemissao > '2016-01-01'";
        $qry3->executa($sql);
        $totalpago = $qry3->data['qtd'];

        $sql="SELECT COUNT(idinterno) as qtd FROM tbentrega WHERE fatpago is null AND dataemissao > '2016-01-01'";
        $qry3->executa($sql);
        $totalaberto = $qry3->data['qtd'];
        ?>
          <td><b>ULTIMO ATUALIZADO:</b></td>
          <td><?php echo "CAMPANHA/ANO: ".$numloteinterno." SETOR: ".$setor; ?></td>
          <td><b>TOTAL DE ENCOMENDAS:</b></td>
          <td><?php echo $totalpago+$totalaberto; ?></td>
          <td><b>TOTAL PAGO:</b></td>
          <td><?php echo $totalpago; ?></td>
          <td><b>TOTAL EM ABERTO:</b></td>
          <td><?php echo $totalaberto; ?></td>
        </tr>
      </table> <br>
      <table style="width:800px; margin:0 auto">
        <tr>
         <td><input type="submit" name="mostrar" value="Mostrar Tabela"/></td>
        </tr>
      </table>
  </form>
  <?php
  if(isset($_POST['mostrar'])){
  ?>
 <table class="tabela" style="width:800px; margin:0 auto">
    <tr bgcolor="#eeeeee">
      <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Listagem de Setores Pagos</b></font></font><font size="4"><b> ::..</b></font></td>
    </tr>
  </table>
    <table class="tabela" style="width:800px; margin:0 auto">
     <tr bgcolor=#eeeeee>
     <td><b>Setor</b></td>
     <td><b>Campanha/Ano</b></td>
     <td><b>Data</b></td>
     <td><b>Pago</b></td>
     </tr>
     <?php
      $sql="SELECT primeiroenvelope,numloteinterno,count(primeiroenvelope) as qtd, TO_CHAR(MAX(dataemissao),'DD/MM/YYYY') as datamax, TO_CHAR(MIN(dataemissao),'DD/MM/YYYY')  as datamin FROM tbentrega WHERE fatpago = '1' GROUP BY numloteinterno, primeiroenvelope ORDER BY numloteinterno DESC, primeiroenvelope ASC";
      $qry2->executa($sql);
      for ($i=0;$i<$qry2->nrw;$i++){
        $qry2->navega($i);
     ?>
     <tr <?php ?><?php if($qry2->data['primeiroenvelope'] == $setor AND $qry2->data['numloteinterno'] == $numloteinterno){ echo "style='background-color:#eeeeee'"; } ?>>
     <td align='left'><?php echo $qry2->data['primeiroenvelope']?></td>
     <td align='left'><?php echo $qry2->data['numloteinterno']?></td>
     <td align='left'><?php echo $qry2->data['datamin']." - ".$qry2->data['datamax']; ?></td>
     <td align='left'><?php echo  $qry2->data['qtd']?></td>
     </tr>
  <?php
      }
  ?>
    </table>
 <table class="tabela" style="width:800px; margin:0 auto">
    <tr bgcolor="#eeeeee">
      <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Listagem de Setores Não Pagos</b></font></font><font size="4"><b> ::..</b></font></td>
    </tr>
  </table>
    <table class="tabela" style="width:800px; margin:0 auto">
     <tr bgcolor=#eeeeee>
     <td><b>Setor</b></td>
     <td><b>Campanha/Ano</b></td>
     <td><b>Data</b></td>
     <td><b>Não Pago</b></td>
     </tr>
     <?php
      $sql="SELECT primeiroenvelope,numloteinterno,count(primeiroenvelope) as qtd , TO_CHAR(MAX(dataemissao),'DD/MM/YYYY') as datamax, TO_CHAR(MIN(dataemissao),'DD/MM/YYYY')  as datamin FROM tbentrega WHERE fatpago is null AND dataemissao > '2016-01-01' GROUP BY numloteinterno, primeiroenvelope  ORDER BY primeiroenvelope DESC";
      $qry2->executa($sql);
      for ($i=0;$i<$qry2->nrw;$i++){
        $qry2->navega($i);
     ?>
     <tr <?php ?><?php if($qry2->data['primeiroenvelope'] == $setor AND $qry2->data['numloteinterno'] == $numloteinterno){ echo "style='background-color:#eeeeee'"; } ?>>
     <td align='left'><?php echo $qry2->data['primeiroenvelope']?></td>
     <td align='left'><?php echo $qry2->data['numloteinterno']?></td>
     <td align='left'><?php echo $qry2->data['datamin']." - ".$qry2->data['datamax']; ?></td>
     <td align='left'><?php echo  $qry2->data['qtd']?></td>
     </tr>
  <?php
      }
  ?>
    </table>

  <?php
  }
  // pega o Footer
  require_once("inc/footer.inc");
