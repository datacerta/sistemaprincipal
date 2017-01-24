<?php
 // seta variavel local
$local = 1;
$prg   = "exec";

// pega a configuracao
require_once("../inc/config.inc");

// inicia a consulta
$lqry = new consulta($con);
$qry = new consulta($con);

$pos = $_POST['pos'];
$geo = $_POST['geo'];
$obs = str_replace(array("\r", "\n"), '', $_POST['obs']);
if(empty($_SESSION['USER'])){
    $status = 3;
}else{
    if(isset($_POST['marcar'])){
        $lsql = "SELECT * FROM tb_demillus_revend WHERE id = $pos";
        $qry->executa($lsql);
        if($qry->data['marcar'] == 't'){
            $lsql = "UPDATE tb_demillus_revend SET usuario = '".$_SESSION['USER']."', marcar='false' WHERE id = $pos";
            $lqry->executa($lsql);
            $status = 1;
        }else{
            $lsql = "UPDATE tb_demillus_revend SET usuario = '".$_SESSION['USER']."', marcar='true' WHERE id = $pos";
            $lqry->executa($lsql);
            $status = 2;
        }
    }else{
        if(!empty($pos)){
            $geoex = explode(",", $geo);
            if(count($geoex) == 2){
                if(is_numeric($geoex[0]) and is_numeric($geoex[1])){
                    $lsql = "UPDATE tb_demillus_revend SET usuario = '".$_SESSION['USER']."', obs='".$obs."', checado = 'TRUE', latitude = '".$geoex[0]."', longitude = '".$geoex[1]."' WHERE id = $pos";

                    // executa a gravacao
                    $lqry->executa($lsql);
                    $status = 1;
                }else{
                    $status = 2;
                }
            }else{
                $lsql = "UPDATE tb_demillus_revend SET obs='".$obs."' WHERE id = $pos";
                $lqry->executa($lsql);
                $status = 4;
            }
        }else{
            $status = 2;
        }
    }
}




$retorno = array('status' => $status);
echo json_encode($retorno);

