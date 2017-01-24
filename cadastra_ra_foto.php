<?php
// pega a configuracao
$prgTit = "Cadastro de RA";
require_once("inc/config.inc");

$qry = new consulta($con);
$qry->executa("SELECT imagem_rg FROM tb_demillus_revend WHERE id_revend = '".$_GET['idrevend']."' LIMIT 1");
header('Content-type: image/jpeg');
echo pg_unescape_bytea($qry->data['imagem_rg']); 
?>