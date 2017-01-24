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
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
ini_set('max_execution_time', 300);

function retorna_geo($endereco, $cep, $cidade){

	if(empty($endereco))
		return null;
	$key = "AIzaSyBYZ9DMPrJe2cwU66S7Y1H7j6VWH4_2o-k";

	$endereco = utf8_decode(trim($endereco));
	$data_virg = explode(',',$endereco,2);
	$numero_esp = explode(' ', $data_virg[1],2);
	$endereco = $data_virg[0];
	$numero = $numero_esp[0];

	if(empty($numero))
		$endereco_completo = $endereco;
	else
		$endereco_completo = $endereco.", ".$numero;
	if(strlen($cep) < 8){
		$formatcep = str_pad($cep, 8, "0", STR_PAD_LEFT);
		$cep = substr($formatcep,0,5)."-".substr($formatcep,5,3);
	}else{
		$cep = substr($cep,0,5)."-".substr($cep,5,3);
	}

	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode(trim($endereco_completo))."&components=country:BR|postal_code:".$cep."|locality:".urlencode(trim($cidade))."&key=$key";

	$data = json_decode(file_get_contents($url));

	if(count($data->results) == 0){
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode(trim($endereco_completo))."&components=country:BR|locality:".urlencode(trim($cidade))."&key=$key";
	}

	$data = json_decode(file_get_contents($url));

	return $data;
}

$qry  = new consulta($con);
$qryup = new consulta($con);
//MINHAKEY
//$key = "AIzaSyD4d0Xq3_hKt90-zcrFKjwTZTxVbrG3Ld0";

//KEYRICARDO
$key = "AIzaSyBYZ9DMPrJe2cwU66S7Y1H7j6VWH4_2o-k";

$qry->executa("SELECT * FROM tb_demillus_revend WHERE latitude = '' LIMIT 2500");
for ($i=0;$i<$qry->nrw;$i++){
	$qry->navega($i);

	$data = retorna_geo($qry->data['endereco'],$qry->data['cep'],$qry->data['cidade']);

	if(count($data->results) == 0 or empty($data)){
		$qryup->executa("UPDATE tb_demillus_revend SET latitude = '0', longitude = '0' WHERE id = ".$qry->data['id']);
	}else{
		$qryup->executa("UPDATE tb_demillus_revend SET latitude = '".$data->results[0]->geometry->location->lat."', longitude = '".$data->results[0]->geometry->location->lng."' WHERE id = ".$qry->data['id']);
	}
	echo "ID: ".$qry->data['id']." / STATUS: ".$data->status;
	echo "<br><hr><br>";
}
?>
