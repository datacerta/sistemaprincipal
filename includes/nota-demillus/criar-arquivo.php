<?php
/**
 * Nota Demillus - CRIAR ARQUIVO
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
 // seta variavel local
$local = 2;

// pega a configuracao
require_once("../../inc/config.inc");

// pega o numero da nota
$nr_nota = (isset($_REQUEST["nr_nota"])) ? $_REQUEST["nr_nota"] : "";
$nr_cfop = (isset($_REQUEST["nr_cfop"])) ? $_REQUEST["nr_cfop"] : "";

// pega o ano atual
$ano = date("Y");

// monta a query
$sql = "SELECT cte,
               dataemissao_cte                                       ,
               datanota_demillus                                     ,
               primeira_nota                                         ,
			   sum(valor)                            AS valMercadoria,
			   sum((numero_notas*tarifa))            AS totPreServ   ,
			   numero_nota_demillus                                  ,
			   sum(peso) AS peso                                     ,
			   serie                                                 ,
			   numero_cte                                            ,
			   ((sum((numero_notas*tarifa))/100)*19) AS valICMS      ,
               '0'                                   AS GRIS         ,
			   '0'                                   AS ADVALOREM 
		FROM   tbnotademillus
        WHERE (numero_nota_demillus = '{$nr_nota}')
        GROUP BY cte                 ,
		         dataemissao_cte     ,
				 numero_nota_demillus,
				 serie               ,
				 numero_cte          ,
				 valor               ,
				 datanota_demillus   ,
                 primeira_nota
        ORDER BY dataemissao_cte ASC";

// cria a consulta e executa a query
$dba = new consulta($con);
$dba->executa($sql);

// pega os campos
$serie    = Util::strZero($dba->data["serie"], 4);
$numDoc   = Util::strZero($dba->data["numero_nota_demillus"], 9);
$cte      = $dba->data["cte"];
$datae    = str_replace("/", "", $dba->data["dataemissao_cte"]);
$totalP   = $dba->data["totPreServ"];
$vlICMS   = $dba->data["valICMS"];
$totalM   = $dba->data["valMercadoria"];
$numCon   = $dba->data["numero_cte"];
$peso     = $dba->data["peso"];
$emissao  = str_replace("/", "", $dba->data["datanota_demillus"]);
$primeira = Util::strZero($dba->data["primeira_nota"], 9);
		
// inicia as linhas
$linha  = "|01|19232334000119|142997991110|57|0001|{$serie}|{$numDoc}|{$nr_cfop}|{$cte}|{$datae}|{$datae}|0|{$totalP}|{$totalP}|012.00|00|{$totalP}|{$vlICMS}|{$totalM}|{$numCon}||"."\r\n";
$linha .= "|02|Frete Peso|"."\r\n";
$linha .= "|02|Outros|{$vlICMS}|"."\r\n";
$linha .= "|03|01|PESO BRUTO|{$peso}|"."\r\n";
$linha .= "|04|{$primeira}|{$emissao}|";

// cria o Header
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="CT'.$numCon.'.txt"');

// mostra o conteudo para download
echo $linha;