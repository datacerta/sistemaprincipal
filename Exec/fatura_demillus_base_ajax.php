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
$lqry = new consulta($con);
$lqry2 = new consulta($con);
$lqry3 = new consulta($con);

if(isset($_POST['codbase'])){
    $codbase = $_POST['codbase'];
    $campanha = $_POST['campanha'];
    $ano = $_POST['ano'];
    $valor = $_POST['valor'];
    $valor = str_replace(",", ".",  $_POST['valor']);
    $voucher = $_POST['voucher'];
	$campFormat = $campanha."/".$ano;
    $historico = $_POST['historico'];
    if(!isset($_POST['extras'])){
        $valor = str_replace("-","",$valor);
        $valor = str_replace(",",".",$valor);
        $lqry3->executa("SELECT * FROM tb_demillus_extra WHERE voucher = '$voucher'");  
        if(!$lqry3->nrw){
            $sql = "INSERT INTO tb_demillus_extra 
             (codbase,
                campanha,
                valor,
                historico,
                data,
                usuario,
                voucher) VALUES(
                '$codbase',
                '$campFormat',
                '-$valor',
                '$historico',
                '".date('Y-m-d')."',
                '".$_SESSION['USER']."',
                '$voucher') ";
            $lqry->executa($sql);
            $lqry2->executa("SELECT * FROM tb_demillus_extra ORDER BY id DESC LIMIT 1");  
            $status = 1;
        }else{
            $status = 0;
        }
    }else{
        $valor = str_replace(",",".",$valor);
        $sql = "INSERT INTO tb_demillus_extra 
         (codbase,
            campanha,
            valor,
            historico,
            data,
            usuario,
            voucher) VALUES(
            '$codbase',
            '$campFormat',
            '$valor',
            '$historico',
            '".date('Y-m-d')."',
            '".$_SESSION['USER']."',
            '') ";
        $lqry->executa($sql);
        $lqry2->executa("SELECT * FROM tb_demillus_extra ORDER BY id DESC LIMIT 1");
        $status = 1;
    }
}


$retorno = array('status' => $status, 'historico' => $lqry2->data['historico'], 'valor' => $lqry2->data['valor'],'voucher' => $lqry2->data['voucher'], 'dataq' => mostra_data($lqry2->data['data']), 'id' => $lqry2->data['id'] );
echo json_encode($retorno);

