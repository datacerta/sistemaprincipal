<?php
/**
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel


// pega a configuracao
require_once("classes/diversos.inc.php");
// seta o link atual

$qry  = new consulta($con);
$qryend  = new consulta($con);
$qryup = new consulta($con);

$qry->executa("SELECT MAX(e.idinterno) as idinterno, e.numconta
	FROM tbentrega e
	LEFT JOIN tb_demillus_revend dr ON CAST(e.numconta as integer) = dr.id_revend
	WHERE dr.id_revend is null and e.numconta != ' '
	GROUP BY e.numconta");
for ($i=0;$i<$qry->nrw;$i++){
	$qry->navega($i);
	$qryend->executa("SELECT *
		FROM tbenderecoentrega
		WHERE idinterno = ".$qry->data['idinterno']."
		ORDER BY idinterno DESC LIMIT 1");
	$qryup->executa("SELECT *
		FROM tb_demillus_revend
		WHERE id_revend = ".$qry->data['numconta']);
	if($qryup->nrw == 0){
		$qryup->executa("INSERT INTO tb_demillus_revend
			(id_revend,nome_revend,cep,uf,endereco,bairro,cidade)
			VALUES (".$qry->data['numconta'].",'".$qryend->data['nomeentrega']."','".$qryend->data['cepentrega']."','".$qryend->data['estadoentrega']."','".$qryend->data['enderecoentrega']."','".$qryend->data['bairroentrega']."','".$qryend->data['cidadeentrega']."')");
	}
	echo $qry->data['numconta'];
	echo "<br><hr><br>";
}
?>
