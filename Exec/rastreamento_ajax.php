<?php
/**
 * Geracao Manifesto Demillus - EXEC
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
 // seta variavel local
$local = 1;
$prg   = "exec";

// pega a configuracao
require_once("../inc/config.inc");

// inicia a consulta
$qry = new consulta($con);
if(empty($_SESSION['USER'])){
    $status = 3;
}else{
    if(isset($_POST['obs'])){
        $obs = str_replace(array("\r", "\n"), '', $_POST['obs']);
        $sql = "UPDATE tb_demillus_revend SET obs = '".$obs."' WHERE id_revend = ".$_POST['idrevend'];
        // executa a gravacao
        $qry->executa($sql);
        $status = 1;
    }elseif(isset($_POST['codgeo'])){
        $geoex = explode(",", $_POST['codgeo']);
        if(count($geoex) == 2){
            if(is_numeric($geoex[0]) and is_numeric($geoex[1])){
                $sql = "UPDATE tb_demillus_revend SET usuario = '".$_SESSION['USER']."', latitude = '".$geoex[0]."', longitude = '".$geoex[1]."', checado = 'TRUE', atualiza_data = '".date("Y-m-d H:i:s")."' WHERE id_revend = ".$_POST['idrevend'];
                // executa a gravacao
                $qry->executa($sql);
                $status = 1;
            }else{
                $status = 2;
            }
        }else{
        	$status = 2;
        }
    }else{
    	$status = 2;
    }
}

$retorno = array('status' => $status);
echo json_encode($retorno);

