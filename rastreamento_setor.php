<?php
include_once('classes/googlemap_setor.php');
 
?>
<html>
<body>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<div id="map" style="width:100%; height:100%;" >
<?php
require_once("classes/diversos.inc.php");
$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry_motivo = new consulta($con);

$sql = "SELECT num_campanha FROM tb_demillus_campanha WHERE data_coleta >= '".date('Y-m-d')."' LIMIT 1";
$qry3->executa($sql);
$campanha_atual = $qry3->data['num_campanha'];

if(!empty($_GET['setores']) or !empty($_GET['listas'])){
	if(!empty($_GET['setores'])){
		$sql = "SELECT latitude, longitude
			  FROM tb_demillus_revend
			  WHERE id_setor IN(".$_GET['setores'].") and latitude <> '0' and latitude <> '' and longitude <> '0' AND latitude <> '' LIMIT 1";
	}else{
		$sql = "SELECT *
			FROM tb_demillus_revend dr
			JOIN tbentrega e ON CAST(e.numconta as integer) = dr.id_revend
			WHERE e.numlista IN(".$_GET['listas'].")  AND e.numconta != '' and dr.latitude <> '0' and dr.latitude <> '' and dr.longitude <> '0' AND dr.latitude <> ''
			LIMIT 1";
	}
}else{
	$sql = "SELECT latitude, longitude
		  FROM tb_demillus_revend
		  WHERE id_setor = '".$setor."' and latitude <> '0' and latitude <> '' and longitude <> '0' AND latitude <> '' LIMIT 1";
}

$qry->executa($sql);

$la = $qry->data["latitude"];
$lo = $qry->data["longitude"];

$map=new GOOGLE_API_3();
$map->center_lat=$la; // set latitude for center location
$map->center_lng=$lo; // set langitude for center location
$map->zoom=15;

if(!empty($_GET['setores']) or !empty($_GET['listas'])){

	if(!empty($_GET['setores'])){
		$sql = "SELECT *
			FROM tb_demillus_revend
			WHERE id_setor IN(".$_GET['setores'].")
			ORDER BY id_setor";
		$sqlb = "SELECT *
			FROM tb_demillus_revend dr
			WHERE id_setor IN(".$_GET['setores'].") AND atualiza_data IS NOT NULL
			ORDER BY dr.atualiza_data DESC LIMIT 1 ";
	}else{
		$sql = "SELECT *
			FROM tb_demillus_revend dr
			JOIN tbentrega e ON CAST(e.numconta as integer) = dr.id_revend
			WHERE e.numlista IN(".$_GET['listas'].")
			ORDER BY e.numlista";
		$sqlb = "SELECT *
			FROM tb_demillus_revend dr
			JOIN tbentrega e ON CAST(e.numconta as integer) = dr.id_revend
			WHERE e.numlista IN(".$_GET['listas'].") AND atualiza_data IS NOT NULL
			ORDER BY dr.atualiza_data DESC LIMIT 1 ";
	}

}else{
	$sql = "SELECT *
		  FROM tb_demillus_revend
		  WHERE id_setor = '".$setor."'";
	$sqlb = "SELECT *
		  FROM tb_demillus_revend
		  WHERE atualiza_data IS NOT NULL AND id_setor = '".$setor."' 
		  ORDER BY atualiza_data DESC LIMIT 1";
}

$qry->executa($sql);
$qry2->executa($sqlb);
$ultimadata = $qry2->data["atualiza_data"];
$imgcont = 0;
$destaque = false;
$icon = 'img/1spin.png';
for($i=0;$i<$qry->nrw;$i++)
{
	 $qry->navega($i);
	 $la = $qry->data["latitude"];
	 $lo = $qry->data["longitude"];
	 if($la<>$tla and $lo <>$tlo and !empty($la) and !empty($lo))
	 {
	 	if($qry->data['checado'] == 'f' and $_GET['camp'] == 'true'){
	 		$icon = 'img/6spin.png';
	 	}else{
		 	if($_GET['camp'] == 'true'){
		 		$qry4->executa("SELECT numloteinterno FROM tbentrega WHERE numconta = '".$qry->data['id_revend']."' ORDER BY idinterno DESC LIMIT 1");
		 		$camp_ano = explode("/",$qry4->data['numloteinterno']);
		 		$campanha = $camp_ano[0];
		 		$spin_cor = $campanha_atual-$campanha;

		 		if($spin_cor == 0){
		 			$icon = 'img/1spin.png';
		 		}elseif($spin_cor == 1){
		 			$icon = 'img/5spin.png';
		 		}elseif($spin_cor == 2){
		 			$icon = 'img/4spin.png';
		 		}else{
		 			$icon = 'img/7spin.png';
		 		}
		 	}else{
			 	if(!empty($_GET['setores'])){
				 	if($qry->data['id_setor'] != $id_setor){
				 		$imgcont++;
				 		if($imgcont == 7){
				 			$imgcont = 1;
				 		}
				 		$icon = 'img/'.$imgcont.'spin.png';

				 	}
				 }else{
				 	if($qry->data['numlista'] != $numlista){
				 		$imgcont++;
				 		if($imgcont == 7){
				 			$imgcont = 1;
				 		}
				 		$icon = 'img/'.$imgcont.'spin.png';

				 	}
				}

		 	}
	 	}

		$isclickable='true';
		$title = $qry->data['nome_revend'];
		if(strlen($qry->data['cep']) < 8){
			$formatcep = str_pad($qry->data['cep'], 8, "0", STR_PAD_LEFT);
			$cep = substr($formatcep,0,5)."-".substr($formatcep,5,3);
		}else{
			$cep = substr($qry->data['cep'],0,5)."-".substr($qry->data['cep'],5,3);
		}

			$info= "<img width=150 height=50 src='inc/img/logo-datacerta.png'><br>"
			."<b>SETOR PROPOSTO: </b><input style='width:30px' type='text' name='geo' placeholder='SETOR' value='".$qry->data['id_setor_correcao']."'/><button class='gravar'>GRAVAR</button><input name='idrevend' type='hidden' class='idrevend' value='".$qry->data['id_revend']."'/> <br>"
			."<b>COD. RA:</b> ".$qry->data['id_revend']."<br>"
			."<b>NOME RA:</b> ".utf8_decode(trim($qry->data['nome_revend']))."<br>"
			."<b>SETOR:</b> ".$qry->data['id_setor']."<br>";

		$map->addMarker($la,$lo,$isclickable,$title,$info,$icon);

		$tla = $la;
		$tlo = $lo;
		if(!empty($_GET['setores'])){
			$id_setor = $qry->data['id_setor'];
		}else{
			$numlista = $qry->data['numlista'];
		}
	}
}

echo $map->showmap();
?>
</div>
</body>
</html>
