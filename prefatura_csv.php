<?php
$con = pg_connect ("dbname=esisco1 user=esisco1 password=iset7617 host=127.0.0.1");

if($_GET['tipo'] == 'validacao'){
	$sql = "SELECT e.serie_cte,e.dataemissao, e.numnotafiscal,e.pesoentrega,e.valorentrega,e.valor_icms,e.tarifa,ee.nomeentrega,ee.enderecoentrega, ee.cepentrega,ee.bairroentrega,ee.cidadeentrega,ee.estadoentrega FROM tbentrega e, tbenderecoentrega ee WHERE e.idinterno = ee.idinterno AND emissao_cte BETWEEN '".$_GET['datade']."' AND '".$_GET['dataate']."' AND codcliente = '".$_GET['cliente']."' ORDER BY serie_cte";
	$result = pg_query($con, $sql);
}else{
	$sql = "SELECT e.serie_cte,e.dataemissao, e.numnotafiscal,e.pesoentrega,e.valorentrega,e.valor_icms,e.tarifa,ee.nomeentrega,ee.enderecoentrega, ee.cepentrega,ee.bairroentrega,ee.cidadeentrega,ee.estadoentrega FROM tbentrega e, tbenderecoentrega ee WHERE e.idinterno = ee.idinterno AND dataemissao BETWEEN '".$_GET['datade']."' AND '".$_GET['dataate']."' AND codcliente = '".$_GET['cliente']."' ORDER BY serie_cte";
	$result = pg_query($con, $sql);
}

header( 'Content-type: application/csv' );   
header( 'Content-Disposition: attachment; filename=file.csv' );   
header( 'Content-Transfer-Encoding: binary' );
header( 'Pragma: no-cache');

$output = fopen("php://output", "w");
$data = pg_fetch_all($result);

fputcsv($output, array('SERIE','DATA','NOTA','PESO','VALOR','ICMS','TARIFA','NOME','ENDERECO','CEP','BAIRRO','CIDADE','ESTADO'),';','"');
foreach ($data as $row) {
    fputcsv($output, $row,';','"'); 
}
fclose($output);


