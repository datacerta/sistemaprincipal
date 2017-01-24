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

$pos = $_POST['pos'];

if(isset($_POST['limpar'])){
	$datanota_demillus = (isset($_POST["datanota_demillus"])) ? $_POST["datanota_demillus"] : "";
	$numero_setor      = (isset($_POST["numero_setor"     ])) ? $_POST["numero_setor"     ] : "";
   
	if(empty($datanota_demillus) or empty($numero_setor)){
		$status = 2;
	}else{
	    $lsql = "DELETE FROM tbnotademillus WHERE datanota_demillus = '".$datanota_demillus."' AND numero_setor = '".$numero_setor."'";
	    $lqry->executa($lsql);
		$status = 1;		
	}
}else{
	// pega os campos hidden
    $frete             = (isset($_POST["frete"            ])) ? $_POST["frete"            ] : 0.0;
    $valor             = (isset($_POST["valor"            ])) ? $_POST["valor"            ] : 0.0;
	$peso              = (isset($_POST["peso"             ])) ? $_POST["peso"             ] : 0.0;
	$icms              = (isset($_POST["icms"             ])) ? $_POST["icms"             ] : "0,00";
	$primeira_nota     = (isset($_POST["primeira_nota"    ])) ? $_POST["primeira_nota"    ] : "";
	$ultima_nota       = (isset($_POST["ultima_nota"      ])) ? $_POST["ultima_nota"      ] : "";
	$numero_setor      = (isset($_POST["numero_setor"     ])) ? $_POST["numero_setor"     ] : "";
	$numero_notas      = (isset($_POST["numero_notas"     ])) ? $_POST["numero_notas"     ] : 0;
	$tarifa            = (isset($_POST["tarifa"           ])) ? $_POST["tarifa"           ] : 0.0;
	$datanota_demillus = (isset($_POST["datanota_demillus"])) ? $_POST["datanota_demillus"] : "";

	// acerta o icms
	$icms = str_replace(".", "-", $icms);
	$icms = str_replace(",", ".", $icms);
	$icms = str_replace("-", ",", $icms);

	$id_nota_demillus = (isset($_POST["id_nota_demillus"])) ? $_POST["id_nota_demillus"] : "";
    $dataemissao_cte      = (isset($_POST["dataemissao_cte"     ])) ? $_POST["dataemissao_cte"     ] : "";
    $numero_nota_demillus = (isset($_POST["numero_nota_demillus"])) ? $_POST["numero_nota_demillus"] : "";
    $numero_cte           = (isset($_POST["numero_cte"          ])) ? $_POST["numero_cte"          ] : "";
    $numero_manifesto     = (isset($_POST["numero_manifesto"    ])) ? $_POST["numero_manifesto"    ] : "";
    $cte                  = (isset($_POST["cte"                 ])) ? $_POST["cte"                 ] : "";
    $preco_demillus       = 0.00;
	
    $sql = "SELECT * FROM tbnotademillus WHERE datanota_demillus = '".$datanota_demillus."' AND numero_setor = '".$numero_setor."'";
    $lqry3->executa($sql);

	// verifica o numero da Nota
    if ($lqry3->nrw == 0) {
		// separa a serie
        $eNumCte    = explode("/", $numero_cte);
        $numero_cte = $eNumCte[0];
        $serie      = $eNumCte[1];

        $datanota_demillus_format = (!empty($datanota_demillus)) ? Util::transformaData($datanota_demillus) : "";
        $dataemissao_cte_format = (!empty($dataemissao_cte)) ? Util::transformaData($dataemissao_cte) : "";
        /*$sql = "UPDATE tbentrega_auxiliar SET num_cte = '".$cte."', emissao_cte = '".$dataemissao_cte_format."', numnotafatura = '".$numero_nota_demillus."', serie_te = '".$numero_cte."' WHERE numlotecliente = '".$numero_setor."' AND dataemissao = '".$datanota_demillus_format."'";
        $lqry2->executa($sql);*/
       
        // monta a query
        $lsql = "INSERT INTO tbnotademillus (valor                    ,
                                             peso                     ,
                                             icms                     ,
                                             primeira_nota            ,
                                             ultima_nota              ,
                                             numero_setor             ,
                                             numero_notas             ,
                                             tarifa                   ,
									         dataemissao_cte          ,
                                             numero_nota_demillus     ,
                                             numero_cte               ,
                                             numero_manifesto         ,
                                             cte                      ,
									         serie                    ,
										     datanota_demillus        ,
										     preco_demillus           ,
                                             frete)
							         VALUES ('{$valor}'               ,
                                             '{$peso}'                ,
                                             '{$icms}'                ,
                                             '{$primeira_nota}'       ,
                                             '{$ultima_nota}'         ,
                                             '{$numero_setor}'        ,
                                             '{$numero_notas}'        ,
                                             '{$tarifa}'              ,
									         '{$dataemissao_cte}'     ,
                                             '{$numero_nota_demillus}',
                                             '{$numero_cte}'          ,
                                             '{$numero_manifesto}'    ,
                                             '{$cte}'                 ,
									         '{$serie}'               ,
										     '{$datanota_demillus}'   ,
										     '{$preco_demillus}'      ,
                                             '{$frete}')";

        // executa a gravacao
        $lqry->executa($lsql);
        $status = 1;
    }else{
    	$status = 2;
    }

}


$retorno = array('status' => $status);
echo json_encode($retorno);

