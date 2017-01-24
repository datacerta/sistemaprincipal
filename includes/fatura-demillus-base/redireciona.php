<?php
/**
 * Fatura Demillus Base - Include - Redireciona
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega os campos passados
$data_de        = (isset($_REQUEST["data_de"       ])) ? trim($_REQUEST["data_de"       ]) : "";
$data_ate       = (isset($_REQUEST["data_ate"      ])) ? trim($_REQUEST["data_ate"      ]) : "";
$tipo_data      = (isset($_REQUEST["tipo_data"     ])) ? trim($_REQUEST["tipo_data"     ]) : "";
$lote_de        = (isset($_REQUEST["lote_de"       ])) ? trim($_REQUEST["lote_de"       ]) : "";
$lote_ate       = (isset($_REQUEST["lote_ate"      ])) ? trim($_REQUEST["lote_ate"      ]) : "";
$tipo_lote      = (isset($_REQUEST["tipo_lote"     ])) ? trim($_REQUEST["tipo_lote"     ]) : "";
$codbase        = (isset($_REQUEST["codbase"       ])) ? trim($_REQUEST["codbase"       ]) : "";
$codcliente     = (isset($_REQUEST["codcliente"    ])) ? trim($_REQUEST["codcliente"    ]) : "";
$codigoproduto  = (isset($_REQUEST["codigoproduto" ])) ? trim($_REQUEST["codigoproduto" ]) : "";
$opt            = (isset($_REQUEST["opt"           ])) ? trim($_REQUEST["opt"           ]) : "";
$ver_detalhes   = (isset($_REQUEST["ver_detalhes"  ])) ? trim($_REQUEST["ver_detalhes"  ]) : "";
$numlotecliente = (isset($_REQUEST["numlotecliente"])) ? trim($_REQUEST["numlotecliente"]) : "";
$dataemissao    = (isset($_REQUEST["dataemissao"   ])) ? trim($_REQUEST["dataemissao"   ]) : "";
$tot_lote       = (isset($_REQUEST["tot_lote"      ])) ? trim($_REQUEST["tot_lote"      ]) : "";

// verifica o tot_lote
if (strpos($tot_lote, "?") > -1) {
	// explode
	$eLote = explode("?", $tot_lote);
	
	// acerta o Lote
	$tot_lote = $eLote[0];
}