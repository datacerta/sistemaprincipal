<?php
$local = 1;
$prg   = "exec";
require_once("../inc/configsemvalidacao.inc");
$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$caixa = $_POST['caixa'];
$lista = $_POST['lista'];
$codigo = $_POST['codigo'];
$status = 0;
$caixaex = array();
$numcaixaRes = 'Erro';
$numnotaRes = 'Erro';
$detalhesErr = null;
$errorLog = "";
$fo = fopen("../caixalog/".date('jny').".txt",'a');

if(isset($codigo)){
	$checkCaixa = false;
	for($i = 0; $i < count($codigo); $i++){
		if(in_array($codigo[$i], $caixa)){
			$checkCaixa = true;
			$valorRem = $codigo[$i];
			$qry->executa("UPDATE tb_demillus_volumes dv SET status = 'L'
					FROM tbentrega e 
					WHERE dv.idinterno = e.idinterno AND dv.num_caixa = '".$valorRem."' AND e.numlista = '$lista' AND dv.status = 'P'");
			$errorLog .= "[".date('H:i')."][$lista][$valorRem] CAIXA LIDA COM SUCESSO \r\n";
			$codigo = array_diff($codigo, array($valorRem));
			$caixa = array_diff($caixa, array($valorRem));
			$codigo = array_values($codigo);
			$caixa = array_values($caixa);
		}
	}
	if($checkCaixa){
		for($i = 0; $i < count($codigo); $i++){

			if(!$qry->nrw){
				$qry->executa("UPDATE tb_demillus_volumes dv SET status = 'L', num_caixa = '".$codigo[$i]."'
						FROM tbentrega e 
						WHERE dv.idinterno = e.idinterno AND dv.num_caixa = '".$caixa[$i]."' AND e.numlista = '$lista' AND dv.status = 'P'");
				$errorLog .= "[".date('H:i')."][$lista] CAIXA ".$caixa[$i]." ATUALIZADA PARA ".$codigo[$i]."\r\n";
			}
		}
		$detalhesErr = 'CAIXAS ATUALIZADAS';
		$status = 1;
	}else{
		$detalhesErr = 'ERRO AO ATUALIZAR CAIXAS';
	}
}else{
	if(!empty($caixa)){
		$qry->executa("SELECT dv.idinterno 
			FROM tb_demillus_volumes dv 
			INNER JOIN tbentrega e ON  dv.idinterno = e.idinterno
			WHERE dv.num_caixa = '".$caixa."' AND e.numlista = '$lista'");
		$idinterno = $qry->data['idinterno'];
		if($qry->nrw){
			//ATUALIZA STATUS
			$qry->executa("SELECT dv.idinterno 
				FROM tb_demillus_volumes dv 
				INNER JOIN tbentrega e ON  dv.idinterno = e.idinterno
				WHERE dv.num_caixa = '".$caixa."' AND dv.status = 'L' AND e.numlista = '$lista'");
			if(!$qry->nrw){
				$qry2->executa("SELECT dv.idinterno, dv.num_caixa
					FROM tb_demillus_volumes dv 
					INNER JOIN tbentrega e ON  dv.idinterno = e.idinterno
					WHERE dv.idinterno = '".$idinterno."' AND e.numlista = '$lista'");
				if($qry2->nrw > 1){
					for ($i=0;$i<$qry2->nrw;$i++){
						$qry2->navega($i);
						$caixaex[] = $qry2->data['num_caixa'];
						$detalhesErr = 'NOTA COM MAIS DE UMA CAIXA';
					}
				}else{
				    $qry2->executa("UPDATE tb_demillus_volumes dv SET status = 'L'
					FROM tbentrega e 
					WHERE dv.idinterno = e.idinterno AND dv.num_caixa = '".$caixa."' AND e.numlista = '$lista'");

				    if($qry2->res){
				    	$status = 1;
				    }else{
				    	$errorLog .= "[".date('H:i')."][$lista][$caixa]FALHA AO ATUALIZAR STATUS ".pg_last_error()."\r\n";
				    	$detalhesErr = 'FALHA AO ATUALIZAR STATUS';
				    }
				}
			}else{
				$errorLog .= "[".date('H:i')."][$lista][$caixa]CAIXA JA FOI LIDA\r\n";
				$detalhesErr = 'CAIXA JÁ FOI LIDA';	
			}

	    }else{
	    	$errorLog .= "[".date('H:i')."][$lista][$caixa]CAIXA NAO EXISTE\r\n";
	    	$detalhesErr = 'CAIXA NÃO EXISTE';
	    }

	}

}

/*
QUANTIDADE DE CAIXAS RESTANTES
*/
$sql = "SELECT COUNT(dv.num_caixa) as numcaixa 
FROM tbentrega e
INNER JOIN tb_demillus_volumes dv
ON e.idinterno = dv.idinterno
WHERE numlista = $lista
AND dv.status = 'P'";

$qry3->executa($sql);
if($qry3->nrw){
	$numcaixaRes = $qry3->data['numcaixa'];
}

/*
QUANTIDADE DE NOTAS RESTANTES
*/
$sql = "SELECT COUNT(DISTINCT e.numnotafiscal) as numnota
FROM tbentrega e
INNER JOIN tb_demillus_volumes dv
ON e.idinterno = dv.idinterno
WHERE numlista = $lista
AND dv.status = 'P'";

$qry3->executa($sql);
if($qry3->nrw){
	$numnotaRes = $qry3->data['numnota'];
}

if($status){
	if(is_array($caixa)){
		foreach($caixa as $cx){
			$errorLog .= "[".date('H:i')."][$lista][$cx]n[$numnotaRes]c[$numcaixaRes]CAIXA LIDA COM SUCESSO\r\n";
		}
	}else{
		$errorLog .= "[".date('H:i')."][$lista][$caixa]n[$numnotaRes]c[$numcaixaRes]CAIXA LIDA COM SUCESSO\r\n";
	}
}

$retorno = array(
	'caixa' => $caixa,
	'status' => $status,
	'caixaex' => $caixaex,
	'qtdcaixa' => $numcaixaRes,
	'qtdnota' => $numnotaRes,
	'detalhesErr' => $detalhesErr
	);

fwrite($fo,$errorLog);
fclose($fo);

echo json_encode($retorno);

