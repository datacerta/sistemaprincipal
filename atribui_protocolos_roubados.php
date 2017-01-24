<?
$prgTit = "Atribui protocolos roubados";
require_once("inc/config.inc");

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);

if($_POST['enviar'] == 'Gerar'){
  $campanha = $_POST['campanha'];
  $ano = $_POST['ano'];
  $setor = (int)$_POST['setor'];
  $numloteinterno = $campanha."/".$ano;
  $sql="SELECT e.numnotafiscal, e.numerosedex, b.nomebase,e.numlotecliente FROM tbentrega e JOIN tbbase b ON e.codbase = b.codbase WHERE e.numloteinterno = '$numloteinterno' AND e.idtipomovimento = '787' and e.primeiroenvelope = $setor order by numnotafiscal ASC";
  $qry2->executa($sql); 
    echo "<h2>Protocolos Roubados - Campanha: $campanha/$ano Setor: ".$qry2->data['numlotecliente']." </h2>"; 
    echo "<table cellpadding='10' cellspacing='0' border='1' style='width:1000px'>";
    echo "<tbody>";
    for($i = 0; $i < $qry2->nrw; $i++) {
        // navega
        $qry2->navega($i);
        if($i%10 == 0 and $i != 0){
          echo "</tr>";
        }
        if($i%10 == 0){
          echo "<tr>";
        }
        echo "<td>".$qry2->data['numnotafiscal']."</td>";


    }
    echo "</tbody>"; 
    echo "</table>";
}else{
  require_once("inc/header.inc");
  ?>

  <body marginheight="0" marginwidth="0">
      <table class="tabela" style="width:800px; margin:0 auto">
        <tr bgcolor="#eeeeee">
          <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Atribuir Protocolos roubados </b></font></font><font size="4"><b> ::..</b></font></td>
        </tr>
      </table>
  <form action=<?=$PHP_SELF;?> method=POST>

  	<br><br>		
      <table class="tabela" style="width:800px; margin:0 auto">
        <tr bgcolor="#FFFFFF">
          <td >Codigo de Barras:</td>
          <td ><input type=text name="codigobarras" id="codigobarras"></td>
          <td >Mensagem:</td>
          <td ><input type=text name=mensagem  value="<?php echo $mensagem; ?>" maxlength=40 size="50"></td>
          <td ><input type=submit name="enviar" value="Gravar"></td>
        </tr>
      <?
          if($_POST['enviar'] == 'Gravar'){
             if(!empty($mensagem) and !empty($codigobarras)){


             $sql="update tbentrega set numerosedex='$mensagem',idtipomovimento = '787' where idexterno = '$codigobarras'";
             $qry->executa($sql);
             if($qry->res){
                $msg = 'Salvo com sucesso!';
             }else{
                $msg = 'Erro ao gravar';
             }
             }else{
              $msg = 'Campo mensagem ou codigo de barras vazio';
             }
                 
          }

                        
             
      ?>
          <?
              if ($msg){
                      echo "<TR>
                            <TD colspan=2><font color=#ff0000><B>$msg</font></td>
                                </tr>";
              }

              ?>
      </table> <br/><br/>
    </form>
    <form action=<?=$PHP_SELF;?> method=POST>
      <table class="tabela" style="width:800px; margin:0 auto">
        <tr bgcolor="#eeeeee">
          <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Gerar Relatorio </b></font></font><font size="4"><b> ::..</b></font></td>
        </tr>
      </table>
      

      <br><br>    
      <table class="tabela" style="width:800px; margin:0 auto">
        <tr bgcolor="#FFFFFF">
          <td >Campanha:</td>
          <td ><input type=text name="campanha"></td>
          <td >Ano:</td>
          <td ><input type=text name="ano"></td>
          <td >Setor:</td>
          <td ><input type=text name="setor"></td>
          <td ><input type=submit name="enviar" value="Gerar"></td>
        </tr>
      </table>
    </form>

  <script type="text/javascript">
    document.getElementById("codigobarras").focus();
  </script>    

  <?php
  // pega o Footer
  require_once("inc/footer.inc");
}