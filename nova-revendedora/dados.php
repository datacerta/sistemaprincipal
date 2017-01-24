<?php
/**
 * Tela de Nova Revendedora - Mapa
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel local
$local = 1;

// recebe o ID como parametro
$id = (isset($_GET["id"])) ? $_GET["id"] : 0;

// pega a configuracao
require_once("../inc/config.inc");

// monta a consulta
$qry = new consulta($con);

// monta a query
$sql = "SELECT nr_encomenda, latitude, longitude FROM tb_easy_courier WHERE (id = '{$id}')";

// executa a query
$qry->executa($sql);

// pega o CPF
$nr_encomenda = $qry->data["nr_encomenda"];
$latitude = $qry->data["latitude"];
$longitude = $qry->data["longitude"];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title></title>
  	<meta charset="UTF-8">
    <meta name="robots"              content="noindex,nofollow" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma"        content="no-cache" />
	<meta name="viewport"            content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />
	
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" />
    <style>
	    body {
			-webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
		    margin: 0px;
		    padding: 0px;
			clear: both;
	    }
        iframe { border: none; display: inline; }
		
		h2 {
			font-family: 'Roboto', sans-serif;
			font-weight: 400;
			font-size: 14px;
			color: #000;
			margin: 0px 0px 0px 10px;
		}
	</style>
<style>
@font-face {
    font-family: myFirstFont;
    src: url(../3OF9_NEW.TTF);
}

  .listacodbar{
    font-family: myFirstFont;
    font-size: 30px;
  }
</style>
	<script type="text/javascript">
	   window.onload = function() {
		   	// seta o tamanho do BODY
            var bodyHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

			// mostra os iFrames
			document.getElementById("mapaL").style.height = (bodyHeight - 18) + "px";
			document.getElementById("fotoL").style.height = (bodyHeight/1.7 - 18) + "px";
	   }
	</script>
</head>

<body>

  <!-- Mapa -->
  <iframe src="mapa.php?id=<?=$id?>&token=<?=$rnd?>" id="mapaL" style="float: left; width: 51%;"></iframe>

  <!-- Foto -->
  <iframe src="foto.php?id=<?=$id?>&token=<?=$rnd?>" id="fotoL" style="float: right; width: 49%"></iframe>
  <div style="float: right; width: 49%; height: 45%;">
  <?php

// executa a query
$qry->executa("SELECT dr.id_revend as codra, dr.nome_revend as nome,dr.endereco,dr.latitude,dr.longitude FROM tb_easy_courier ec JOIN tbentrega e ON cast(ec.nr_encomenda AS float) = e.cpf LEFT JOIN tb_demillus_revend dr ON CAST(e.numconta as integer) = dr.id_revend   WHERE (ec.id = '{$id}')");
if($qry->nrw){
?>
	<strong>Cod. RA:</strong><?=$qry->data['codra']?><br>
	<strong>Nome:</strong><?=$qry->data['nome']?><br>
	<strong>Endereço:</strong><?=$qry->data['endereco']?><br>
	<strong>GEO:</strong><?=$qry->data['latitude']?>,<?=$qry->data['longitude']?><br>
	<span class='listacodbar'>*<?=$qry->data['latitude']?>,<?=$qry->data['longitude']?>*</span> <br>
<?php
}else{
  ?>
  <strong>Nr. CPF:</strong> <?=$nr_encomenda?> <br> 
  <strong>Geo:</strong> <?=$latitude?> , <?=$longitude?> <br>
  <span class='listacodbar'>*<?=$latitude?>,<?=$longitude?>*</span> <br>
  <strong>inserir a numeração ou leia o código de barras.</strong>
 <?php
}
 ?>
  </div>

</body>
</html>