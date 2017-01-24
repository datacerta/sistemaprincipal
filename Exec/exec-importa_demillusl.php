<?php
/**
 * Carga Demillus - EXEC
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
































/**
 * Funcao de importacao
 *
 * @param $linha
 */
function importador_tipo_003($linha) {
            $posicoes[] = 2;    // 0 = tipo de registro
            $posicoes[] = 56;   // 1 = lixo
            $posicoes[] = 8;    // 2 = Data Emissao
            $posicoes[] = 1297; // 2 = Lixo
            
            $posicao_inicial = 0;

            for($i=0;$i<count($posicoes);$i++) {
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_003["dataemissao"]   = $campo[2];

            return $retorno_003;
}

/**
 * Funcao de importacao
 *
 * @param $linha
 */
function importador_tipo_001($linha) {
            $posicoes[] = 2;    // 0 = tipo de registro
			$posicoes[] = 4;    // 1 = lixo
			$posicoes[] = 7;    // 2 = Nota Fiscal
			$posicoes[] = 19;   // 3 = Lixo
			$posicoes[] = 197;  // 4 = Lixo
			$posicoes[] = 12;   // 5 = cpf
			$posicoes[] = 51;   // 6 = destinatario
			$posicoes[] = 7;    // 7 = conta
			$posicoes[] = 24;   // 8 = lixo
			$posicoes[] = 41;   // 9 = Endereco
			$posicoes[] = 31;   // 10 = bairro
			$posicoes[] = 31;   // 11 = cidade
			$posicoes[] = 2;    // 12 = estado
			$posicoes[] = 1;    // 13 = lixo
            $posicoes[] = 8;    // 14 = cep
            $posicoes[] = 5;    // 15 = Volumews
            $posicoes[] = 6;    // 16 = peso
            $posicoes[] = 43;   // 17 = lixo
            $posicoes[] = 9;    // 18 = valor
            $posicoes[] = 152;  // 19 = obs
            $posicoes[] = 703;  // 20 = lixo
            $posicoes[] = 4;    // 21 = Setor
            $posicoes[] = 4;    // 22 = Rota
            
   
	    $posicao_inicial = 0;

      for($i=0;$i<count($posicoes);$i++){
                 $campo[$i] = substr($linha,$posicao_inicial,$posicoes[$i]);
                 $posicao_inicial += $posicoes[$i];
            }

            $retorno_001["idexterno"]                  = $campo[2];
			$retorno_001["nomeentrega"]                = $campo[6];
			$retorno_001["enderecoentrega"]            = $campo[9];
			$retorno_001["bairroentrega"]              = $campo[10];
			$retorno_001["cidadeentrega"]              = $campo[11];
			$retorno_001["estadoentrega"]              = $campo[12];
			$retorno_001["cepentrega"]                 = $campo[14];
		    $retorno_001["nota"]                       = $campo[2];
            $retorno_001["volumes"]                    = $campo[15];
            $retorno_001["valor"]                      = $campo[18];
            $retorno_001["obs"]                        = $campo[19];
            $retorno_001["idrevendedora"]              = $campo[7];
            $retorno_001["cpf"]                        = $campo[5];
            $retorno_001["peso"]                       = $campo[16];
            $retorno_001["setor"]                      = $campo[21];
            $retorno_001["rota"]                       = $campo[22];

	// retorna
	return $retorno_001;
}