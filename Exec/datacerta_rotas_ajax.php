<?php
 // seta variavel local
$local = 1;
$prg   = "exec";

// pega a configuracao
require_once("../inc/config.inc");

// inicia a consulta
$qry = new consulta($con);
$qry2 = new consulta($con);

$dados = $_POST['dados'];
$numlista = $_POST['numlista'];
foreach($dados as $dado){
	$sql = "SELECT e.numlista, dr.id_revend, dr.endereco, e.numnotafiscal, dr.nome_revend, dr.latitude, dr.longitude FROM tbentrega e  LEFT JOIN tb_demillus_revend dr ON CAST(e.numconta as integer) = dr.id_revend WHERE dr.id_revend = '$dado' AND e.numlista = '$numlista' ";
	$qry->executa($sql);
	if($qry->nrw){
		var_dump($qry->data['endereco']);
		$sql2 = "INSERT INTO tb_demillus_routeasy(numlista,idrevend, numnota,nomera,latitude,longitude,sequencia,dataupload,idexterno) VALUES($numlista,$idrevend,$numnotafiscal, '$nome_revend', '$latitude', '$longitude', $i,'".date('Y-m-d')."','67100$numnotafiscal') ";
	    //$qry2->executa($sql2);
	}
}


$retorno = array('status' => $status);
//echo json_encode($retorno);