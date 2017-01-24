<?php
// pega a configuracao
$prgTit = "Faturamento";
require_once("inc/config.inc");
require_once("inc/header.inc");

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry6 = new consulta($con);
$qry7 = new consulta($con);

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
      $qry->executa("UPDATE tbentrega SET serie_cte = '$conhecimento',tarifa= '$tarifa', num_cte = '$cte', emissao_cte = '$emissao_cte', valor_icms = '$icms' WHERE numnotafiscal = '$numnota'");
    }

  }
  echo "<div style='width:500px; margin:0 auto'>IMPORTADO COM SUCESSO!</div>";
  fclose($fop);
}
?>
<style>
.tabela1 { width: 100%; border: none; border-spacing: 0px; }
.tabela1 th {
  font-size: 12px;
  color: #FFF;
  background: rgba(0,56,105,1);
  text-align: center;
  border-right: 1px solid #FFF;
  padding: 5px;
}
.tabela1 td           { font-size: 12px; color: #333; padding: 8px; text-align: center; }
.tabela1 td a         { font-size: 12px; text-decoration: none; color: rgba(0  ,56 ,105,1); font-weight: 700; }
.tabela1 td a:visited {                  text-decoration: none; color: rgba(0  ,56 ,105,1); }
.tabela1 td a:hover   {                  text-decoration: none; color: rgba(255,100,64 ,1); }
.tabela1 .zebra       { background: #666; }
.tabela1 .zebra td    { color: #fff; padding: 12px; }
.tabela1 .zebra:hover{ background-color: #A9A9A9; }
.tabela1 .trhover:hover{ background-color: #FFF8DC; }
.tabela td{ padding: 10px; }

</style>



<table style="width:800px; margin:0 auto">
<tr bgcolor="#eeeeee">
  <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>FATURAMENTO</b></font></font><font size="4"><b> ::..</b></font></td>
</tr>
</table>
<table class="tabela"  BORDER=0 style="margin:0 auto; width:800px">
<form name="exporta_cte" action="exporta_cte_final.php" method="post">
    <tr>
        <td><strong>EXPORTAÇÃO:</strong> </td>
    	  <td>Data Emissão: </td>
        <td><input type=text name="dataemi" class="dataemi"></td>
        <td>Cliente:</td>
        <td>
          <select name="cliente">
            <?php 
            $qry5->executa("SELECT * FROM tbcliente");
            for ($i=0;$i<$qry5->nrw;$i++){
              $qry5->navega($i);
            ?>
              <option value="<?php echo $qry5->data['codcliente']; ?>"><?php echo $qry5->data['nomecliente']; ?></option>
            <?php
            }
            ?>
          </select>
        </td>
        <td><input type=submit value="Gerar Arquivo para BSOFT"></td>
        <td> <input type=hidden name=ok value=1></td>
    </tr>
</form>
     
<form enctype="multipart/form-data" action="#"  method="post">  
  <tr>
    <td><strong>IMPORTAÇÃO:</strong> </td>
    <td>Txt BSOFT: </td>
    <td><input type=file name="arquivo"></td>
    <td></td><td></td>
    <td><input type=submit name="enviar" value="Importar Arquivo da BSOFT"></td>
  </tr>
</form>
<form name="buscar_cte" action="#" method="post">
    <tr>
      <td><strong>BUSCAR SETORES:</strong> </td>
      <td>Data De: </td>
      <td><input type=text name="dataemide" class="dataemi" value="<?php echo $_POST['dataemide']; ?>"></td>
      <td>Data Até: </td>
      <td><input type=text name="dataemiate" class="dataemi" value="<?php echo $_POST['dataemiate']; ?>"></td>
      <td><input type=submit name="buscar" value="Buscar"></td>
    </tr>
    <tr>
      <td> </td>
      <td>Cliente: </td>
      <td>
          <select name="cdcliente">
            <?php 
            $qry5->executa("SELECT * FROM tbcliente");
            for ($i=0;$i<$qry5->nrw;$i++){
              $qry5->navega($i);
            ?>
              <option <?php if($qry5->data['codcliente'] == $_POST['cdcliente']):  echo "selected"; endif; ?> value="<?php echo $qry5->data['codcliente']; ?>"><?php echo $qry5->data['nomecliente']; ?></option>
            <?php
            }
            ?>
          </select>
      </td>
      <td>Tipo:</td>
      <td><input type=radio name="tipo" value="emissao" <?php if($_POST['tipo'] ==  'emissao'): echo "checked='checked'"; endif; ?> >Emissao <input type=radio name="tipo" value="validacao" <?php if($_POST['tipo'] ==  'validacao'): echo "checked='checked'"; endif; ?>>Validação</td><td></td>
    </tr>
</form>
</table>

<br><br><br>

<?php
if(isset($_POST['buscar'])){

$datetime = new DateTime(str_replace('/', '-',$_POST['dataemide']));
$dataemissaode = $datetime->format('Y-m-d');

$datetime = new DateTime(str_replace('/', '-',$_POST['dataemiate']));
$dataemissaoate = $datetime->format('Y-m-d');
?>
<table class="tabela" BORDER=0 style="margin:0 auto; width:800px">
    <tr>
      <td>Data Emiss&atilde;o:</td>
      <td><?php echo $_POST['dataemide']; ?> - <?php echo $_POST['dataemiate']; ?></td>
      <td>Data de Vencimento:</td>
      <td><input type="text" name="data-venc-fat" class="data-venc-fat" /></td>
      <td><a href="<?php echo "prefatura_csv.php?cliente=".$_POST['cdcliente']."&datade=$dataemissaode&dataate=$dataemissaoate&tipo=".$_POST['tipo']; ?>" >Gerar Pré Fatura</a></td>
    </tr>
</table>
<table class="tabela1">
  <tr>
    <th>Setor</th>
    <th>Notas</th>
    <th>Notas a Validar</th>
    <th>Valor Notas</th>
    <th>Peso Total</th>
    <th>Frete</th>
    <th>ICMS</th>
    <th>Frete Peso</th>
    <th>Fatura</th>
    <th>Data Emissão</th>
    <th>Data Validação</th>
    <th>Pdf</th>
    <th>Txt</th>
  </tr>

<?php

//VERIFICA O CODIGO 
if(isset($_POST['cdcliente'])){
  if($_POST['cdcliente'] == '6670' OR $_POST['cdcliente'] == '6671'){
    $codcliente = "(codcliente = '6670' OR codcliente = '6671')";
    if($_POST['tipo'] == 'validacao'){
      $sql = "SELECT primeiroenvelope as setor, SUM(pesoentrega) as pesototal, COUNT(*) as notas, SUM(valorentrega) as valor, SUM(tarifa) as frete,dataemissao, SUM(valor_icms) as icms FROM tbentrega WHERE emissao_cte BETWEEN '".$dataemissaode."' AND '".$dataemissaoate."'  AND $codcliente AND primeiroenvelope != 0  GROUP BY primeiroenvelope,dataemissao ORDER BY dataemissao ASC";
    }else{
      $sql = "SELECT primeiroenvelope as setor, SUM(pesoentrega) as pesototal, COUNT(*) as notas, SUM(valorentrega) as valor, SUM(tarifa) as frete,dataemissao, SUM(valor_icms) as icms FROM tbentrega WHERE dataemissao BETWEEN '".$dataemissaode."' AND '".$dataemissaoate."'  AND $codcliente AND primeiroenvelope != 0  GROUP BY primeiroenvelope,dataemissao ORDER BY dataemissao ASC";
    }
  }else{
    $codcliente = " codcliente = '".$_POST['cdcliente']."' ";
    if($_POST['tipo'] == 'validacao'){
      $sql = "SELECT primeiroenvelope as setor, SUM(pesoentrega) as pesototal, COUNT(*) as notas, SUM(valorentrega) as valor, SUM(tarifa) as frete,dataemissao, SUM(valor_icms) as icms FROM tbentrega WHERE emissao_cte BETWEEN '".$dataemissaode."' AND '".$dataemissaoate."'  AND $codcliente GROUP BY primeiroenvelope,dataemissao ORDER BY dataemissao ASC";
    }else{
      $sql = "SELECT primeiroenvelope as setor, SUM(pesoentrega) as pesototal, COUNT(*) as notas, SUM(valorentrega) as valor, SUM(tarifa) as frete,dataemissao, SUM(valor_icms) as icms FROM tbentrega WHERE dataemissao BETWEEN '".$dataemissaode."' AND '".$dataemissaoate."'  AND $codcliente GROUP BY primeiroenvelope,dataemissao ORDER BY dataemissao ASC";
    }
  }
  $qry->executa($sql);
}


  $totalnotas = 0;
  $totalnotasfaltando = 0;
  $totalvalor = 0;
  $totalpesototal = 0;
  $totalfrete = 0;
  $totalicms = 0;
  $totalfretepeso = 0;

  for ($i=0;$i<$qry->nrw;$i++){
    $qry->navega($i);

    $qry3->executa("SELECT serie_cte,emissao_cte,dataemissao FROM tbentrega WHERE dataemissao = '".$qry->data['dataemissao']."' AND primeiroenvelope = '".$qry->data['setor']."' AND $codcliente  ORDER BY serie_cte LIMIT 1");
    $qry4->executa("SELECT count(*) as qtd FROM tbentrega WHERE dataemissao = '".$qry->data['dataemissao']."' AND primeiroenvelope = '".$qry->data['setor']."' AND $codcliente AND num_cte is null ");

    $qry6->executa(
    "SELECT aliquota_icms
    FROM tb_preco_demillus 
    WHERE setor=".$qry->data['setor']);

    $emissao_cte = '';
    $datavalidacao = '';
    $dataemissao = '';
    $serie_cte = '';

    $datetime = new DateTime($qry3->data['emissao_cte']);
    $datavalidacao .= $datetime->format('d/m/Y')." ";
    $datetime = new DateTime($qry3->data['dataemissao']);
    $dataemissao .= $datetime->format('d/m/Y')." ";
    $serie_cte .= $qry3->data['serie_cte']." ";

    $notasfaltando = $qry4->data['qtd'];
    $icms = $qry->data['icms'];
    $setor = $qry->data['setor'];
    $notas = $qry->data['notas'];
    $valor = $qry->data['valor'];
    $pesototal = $qry->data['pesototal'];
    $frete = $qry->data['frete'];
?>
  <tr <?php if($i%2 == 1): echo "class='zebra'"; else: echo "class='trhover'"; endif; ?>>
    <td><?php echo $setor; ?></td>
    <td><?php echo $notas; ?></td>
    <td><?php echo "<strong>$notasfaltando</strong>"; ?></td>
    <td><?php echo "R$".number_format($valor, 2, ',', '.'); ?></td>
    <td><?php echo number_format($pesototal, 2, ',', '.')."KG"; ?></td>
    <td><?php echo "R$".number_format($frete, 2, ',', '.'); ?></td>
    <td><?php echo "R$".number_format($icms, 2, ',', '.'); ?></td>
    <td><?php echo "R$".number_format(($frete+$icms),2, ',', '.'); ?></td>
    <td><?php echo $serie_cte; ?></td>
    <td><?php echo $dataemissao; ?></td>
    <td><?php echo $datavalidacao; ?></td>
    <td><a target="_blank" class="gerapdf-val" href="<?php echo HOST."/geracao-demillus-pdf-bsoft.php?datanota_demillus=".$qry3->data['dataemissao']."&numero_setor=$setor&cliente=".$_POST['cdcliente']; ?>">Pdf</a></td>
    <td><a target="_blank" href="<?php echo HOST."/geracao-demillus-txt-bsoft.php?datanota_demillus=".$qry3->data['dataemissao']."&numero_setor=$setor&cliente=".$_POST['cdcliente']; ?>">Txt</a></td>
  </tr>
<?php
  $totalnotas += $notas;
  $totalnotasfaltando += $notasfaltando;
  $totalvalor += $valor;
  $totalpesototal += $pesototal ;
  $totalfrete += $frete;
  $totalicms += $icms;
  $totalfretepeso += ($frete+$icms);

  }
?>
  <tr>
    <th>TOTAL:</th>
    <th><?php echo $totalnotas; ?></th>
    <th><?php echo $totalnotasfaltando; ?></th>
    <th><?php echo "R$".number_format($totalvalor, 2, ',', '.'); ?></th>
    <th><?php echo number_format($totalpesototal, 2, ',', '.')."KG"; ?></th>
    <th><?php echo "R$".number_format($totalfrete, 2, ',', '.'); ?></th>
    <th><?php echo "R$".number_format($totalicms, 2, ',', '.'); ?></th>
    <th><?php echo "R$".number_format(($totalfretepeso),2, ',', '.'); ?></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
</table>
<?php
}
?>
<script>
( function( $ ) {
	$(function() {
		$('.dataemi').datepicker({  dateFormat: 'dd/mm/yy',   dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
		    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
		    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
		    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		    nextText: 'Próximo',
		    prevText: 'Anterior' });
		$('.dataemi').mask("99/99/9999");

    $('.data-venc-fat').datepicker({  dateFormat: 'dd/mm/yy',   dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        nextText: 'Próximo',
        prevText: 'Anterior' });
    $('.data-venc-fat').mask("99/99/9999");

    $('.gerapdf-val').on('click',function(){
      if($('.data-venc-fat').val() == ''){
        alert('Entre com a data de vencimento');
        return false;
      }else{
        $(this).prop('href',$(this).prop('href')+'&datavenc='+$('.data-venc-fat').val());
        return true;
      }
    })

	});
} )( jQuery );	
</script>
<?php
// pega o Footer
require_once("inc/footer.inc");
