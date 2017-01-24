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

// pega os campos
$linhas = (isset($_POST["num_lin"])) ? $_POST["num_lin"] : 0;
if(isset($_GET['positi'])){
	$ii = $_GET['positi'];
	$datanota_demillus = $_GET['datanota_demillus'];
	$numero_setor = $_GET['setor'];
   
    // monta a query
    $lsql = "DELETE FROM tbnotademillus WHERE datanota_demillus = '".$datanota_demillus."' AND numero_setor = '".$numero_setor."'";
    // executa a gravacao
    $lqry->executa($lsql);
	
}else{
	// percorre a quantidade de linhas
	for ($ii = 0; $ii < $linhas; $ii++) {
		// pega os campos hidden
	    $valor             = (isset($_POST["valor{$ii}"            ])) ? $_POST["valor{$ii}"            ] : 0.0;
		$peso              = (isset($_POST["peso{$ii}"             ])) ? $_POST["peso{$ii}"             ] : 0.0;
		$icms              = (isset($_POST["icms{$ii}"             ])) ? $_POST["icms{$ii}"             ] : "0,00";
		$primeira_nota     = (isset($_POST["primeira_nota{$ii}"    ])) ? $_POST["primeira_nota{$ii}"    ] : "";
		$ultima_nota       = (isset($_POST["ultima_nota{$ii}"      ])) ? $_POST["ultima_nota{$ii}"      ] : "";
		$numero_setor      = (isset($_POST["numero_setor{$ii}"     ])) ? $_POST["numero_setor{$ii}"     ] : "";
		$numero_notas      = (isset($_POST["numero_notas{$ii}"     ])) ? $_POST["numero_notas{$ii}"     ] : 0;
		$tarifa            = (isset($_POST["tarifa{$ii}"           ])) ? $_POST["tarifa{$ii}"           ] : 0.0;
		$datanota_demillus = (isset($_POST["datanota_demillus{$ii}"])) ? $_POST["datanota_demillus{$ii}"] : "";

		// acerta o icms
		$icms = str_replace(".", "-", $icms);
		$icms = str_replace(",", ".", $icms);
		$icms = str_replace("-", ",", $icms);

		// pega o id_nota_demillus
		$id_nota_demillus = (isset($_POST["id_nota_demillus{$ii}"])) ? $_POST["id_nota_demillus{$ii}"] : "";

		// verifica o id_nota_demillus
		if (empty($id_nota_demillus)) {
			// pega os campos digitados
		    $dataemissao_cte      = (isset($_POST["dataemissao_cte{$ii}"     ])) ? $_POST["dataemissao_cte{$ii}"     ] : "";
		    $numero_nota_demillus = (isset($_POST["numero_nota_demillus{$ii}"])) ? $_POST["numero_nota_demillus{$ii}"] : "";
		    $numero_cte           = (isset($_POST["numero_cte{$ii}"          ])) ? $_POST["numero_cte{$ii}"          ] : "";
		    $numero_manifesto     = (isset($_POST["numero_manifesto{$ii}"    ])) ? $_POST["numero_manifesto{$ii}"    ] : "";
		    $cte                  = (isset($_POST["cte{$ii}"                 ])) ? $_POST["cte{$ii}"                 ] : "";
		    $preco_demillus       = 0.00;
			
			// verifica o numero da Nota
		    if (!empty($numero_nota_demillus)) {
				// separa a serie
		        $eNumCte    = explode("/", $numero_cte);
		        $numero_cte = $eNumCte[0];
		        $serie      = $eNumCte[1];

		        $datanota_demillus_format = (!empty($datanota_demillus)) ? Util::transformaData($datanota_demillus) : "";
		        $dataemissao_cte_format = (!empty($dataemissao_cte)) ? Util::transformaData($dataemissao_cte) : "";
		        $sql = "UPDATE tbentrega_auxiliar SET num_cte = '".$cte."', emissao_cte = '".$dataemissao_cte_format."', numnotafatura = '".$numero_nota_demillus."', serie_te = '".$numero_cte."' WHERE numlotecliente = '".$numero_setor."' AND dataemissao = '".$datanota_demillus_format."'";
		        $lqry2->executa($sql);
		       
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
												     preco_demillus           )
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
												     '{$preco_demillus}'      )";
		        // executa a gravacao
		        $lqry->executa($lsql);
		    }
		}
	}	
}

// recarrega a tela
echo "<script type='text/javascript'>window.parent.location.reload();</script>";

// finaliza o script
//exit();