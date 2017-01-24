<?php
/**
 * Cadastro Campanha Demillus - EXEC
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

// pega os campos passados pelo formulario
$code         = (isset($_REQUEST["code"        ])) ? $_REQUEST["code"        ] :  0;
$txt_setor    = (isset($_REQUEST["txt_setor"   ])) ? $_REQUEST["txt_setor"   ] : "";
$txt_campanha = (isset($_REQUEST["txt_campanha"])) ? $_REQUEST["txt_campanha"] : "";
$txt_entrega  = (isset($_REQUEST["txt_entrega" ])) ? $_REQUEST["txt_entrega" ] : "";
$txt_coleta   = (isset($_REQUEST["txt_coleta"  ])) ? $_REQUEST["txt_coleta"  ] : "";
$txt_cidade   = (isset($_REQUEST["txt_cidade"  ])) ? $_REQUEST["txt_cidade"  ] : "";
$txt_uf       = (isset($_REQUEST["txt_uf"      ])) ? $_REQUEST["txt_uf"      ] : "";
$msg          = "";

// corrige as datas
$data_entrega = (!empty($txt_entrega)) ? Util::transformaData($txt_entrega, "-") : "";
$data_coleta  = (!empty($txt_coleta )) ? Util::transformaData($txt_coleta , "-") : "";

// cria o modulo de consulta
$dba = new consulta($con);

// verifica a acao
if ($act == "incluir") {
	// monta a query de INSERT
	$lsql = "INSERT INTO tb_demillus_campanha (num_campanha     ,
	                                           data_coleta      ,
	                                           data_entrega     ,
											   nome_cidade      ,
											   num_setor        ,
											   uf               )
	                                   VALUES ('{$txt_campanha}',
									           '{$data_coleta}' ,
											   '{$data_entrega}',
											   '{$txt_cidade}'  ,
											   '{$txt_setor}'   ,
											   '{$txt_uf}'      )";
	// executa a query
	$dba->executa($lsql);
	
	// monta a mensagem
	$msg = "Campanha inclu&iacute;da com sucesso!";
}
else if ($act == "editar") {
	// monta a query de UPDATE
	$lsql = "UPDATE tb_demillus_campanha
	            SET num_campanha = '{$txt_campanha}',
	                data_coleta  = '{$data_coleta}' ,
	                data_entrega = '{$data_entrega}',
					nome_cidade  = '{$txt_cidade}'  ,
					num_setor    = '{$txt_setor}'   ,
					uf           = '{$txt_uf}'
			 WHERE (id           =  {$code})";

	// executa a query
	$dba->executa($lsql);
	
	// monta a mensagem
	$msg = "Campanha alterada com sucesso!";
}
else if ($act == "excluir") {
	// monta a query de DELETE
	$lsql = "DELETE FROM tb_demillus_campanha WHERE (id = {$code})";
	
	// executa a query
	$dba->executa($lsql);
	
	// monta a mensagem
	$msg = "Campanha exclu&iacute;da com sucesso!";
}

// exexuta o comando
echo "<script type='text/javascript'>window.parent.mensagem('{$msg}');</script>";

// fianliza o script
exit();