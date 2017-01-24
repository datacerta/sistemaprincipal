<?php
/**
 * Tela de Nova Revendedora - Include do Mapa
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
require_once("../classes/googlemap.php");
require_once("../inc/config.inc");

// monta a consulta
$qry = new consulta($con);

// monta a query
$sql = "SELECT nr_encomenda,
               latitude    ,
			   longitude   ,
			   datacliente
        FROM   tb_easy_courier
		WHERE (id = '{$id}')";

// executa a query
$qry->executa($sql);

// pega os dados
$latitude    = $qry->data["latitude"];
$longitude   = $qry->data["longitude"];
$title       = "";
$info        = $qry->data["nr_encomenda"]."<br />".mostra_data($qry->data["datacliente"],1);
$isclickable = "true";
$icon        = HOST."/inc/img/ic_marker_demillus.png";

// gera o MAPA
$map = new GOOGLE_API_3();
$map->center_lat = $latitude;  // set latitude for center location
$map->center_lng = $longitude; // set langitude for center location
$map->zoom       = 16;

// adiciona um Marker
$map->addMarker($latitude, $longitude, $isclickable, $title, $info, $icon);

echo "<!-- MAPA -->\n";
echo "<div id='map' style='width: 100%; height: 100%;'>\n";
echo $map->showmap()."\n";
echo "</div>\n";