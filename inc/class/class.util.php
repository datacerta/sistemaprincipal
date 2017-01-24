<?php
/**
 * Classe de UTIL
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
class Util {
	/**
	 * Metodo que retorna zeros a esquerda
	 * -----------------------------------------------------------------------
	 * @param unknown_type $num  // numero que contera os zeros a esquerda
	 * @param unknown_type $qtde // quantidade de retorno com zeros a esquerda e o numero
	 */
	public static function strZero($num, $qtde) {
		// seta a quantidade de zeros
		$zeros = $qtde - strlen($num);
		$final = "";
	
		// gera na variavel final o numero de zeros
		for ($i=0;$i<$zeros;$i++) {
			$final .= "0";
		}
	
		// retorna
		return $final.$num;
	}

	/**
     * Metodo que tira dados mal intencionados no Login
	 * -----------------------------------------------------------------------
	 * @param String senha
     */
    public static function limpaLogin($login) {
		// limpa
		$login = trim($login);
		$login = htmlentities($login);
		$login = trim(strip_tags(str_replace("'", "", $login)));
        $login = str_replace("--"   , "", $login);
        $login = str_replace("UNION", "", $login);
        $login = str_replace("#"    , "", $login);
        $login = str_replace("md5"  , "", $login);
        $login = str_replace("\\"   , "", $login);

		// retorna o login limpo
		return $login;
	}	

	/**
     * Metodo que tira dados mal intencionados na Senha
	 * -----------------------------------------------------------------------
	 * @param String senha
     */
    public static function limpaSenha($senha) {
		// limpa
		$senha = trim($senha);
	    $senha = htmlentities($senha);
	    $senha = trim(strip_tags(str_replace("'", "", $senha)));
        $senha = str_replace("--"   , "", $senha);
        $senha = str_replace("UNION", "", $senha);
        $senha = str_replace("#"    , "", $senha);
        $senha = str_replace("/"    , "", $senha);
        $senha = str_replace("md5"  , "", $senha);
        $senha = str_replace("\\"   , "", $senha);

	    // retorna a senha limpa
        return md5($senha);
    }

	/**
	 * Metodo que retorna o formato de uma data
	 * -----------------------------------------------------------------------
	 * @param <text> $data    (a data a ser formatada - AAAA-MM-DD)
	 * @param <text> $formato (o formato) 
	 *               [dma]      - DD/MM/AAAA
	 *               [amd]      - AAAA-MM-DD
	 *               [dmah]     - DD/MM/AAAA HH:SS
	 *               [extenso]  - data por extenso s/ hora
	 *               [extensoh] - data por extenso c/ hora
	 *               [hm]       - HHhMM
	 * -----------------------------------------------------------------------
	 * @return <text> return (a data com o formtado escolhido)
	 * -----------------------------------------------------------------------
	 */
	public static function getDataFormat($data, $formato) {
		// retorno
		$retorno = "";
		
		// verifica a data recebida
		if (!empty($data)) {
			// pega a composicao da data, hora e dia da semana
		    $dia  = substr($data,  8, 2);
		    $mes  = substr($data,  5, 2);
		    $ano  = substr($data,  0, 4);
		    $hora = substr($data, 11, 5);
		    $dw   = date("w", mktime(0, 0, 0, (int)$mes, (int)$dia, (int)$ano));
			
			// verifica o formato
			switch ($formato) {
				case "dma":
					$retorno = "{$dia}/{$mes}/{$ano}";
					break;
				case "amd":
					$retorno = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
					break;
				case "dmah":
					$retorno = "{$dia}/{$mes}/{$ano} {$hora}";
					break;
				case "extenso":
					$retorno  = self::$semana[$dw].", ";
					$retorno .= $dia." de ".self::$mesExt[(int)$mes]." de ".$ano;
					break;
				case "extensoh":
					$retorno  = self::$semana[$dw].", ";
					$retorno .= $dia." de ".self::$mesExt[(int)$mes]." de ".$ano." &agrave;s " . $hora;
					$retorno  = str_replace(":", "h", $retorno);
					break;
				case "hm":
					$retorno = str_replace(":", "h", $hora);
					break;
			}
		}
	
		// retorna a data formatada
		return $retorno;
	}
	
	/**
	 * Metodo que retorna o formato de uma data
	 * -----------------------------------------------------------------------
	 * @param <text> $data    (a data a ser formatada - DD/MM/AAAA)
	 * @param <text> $forma   (/ ou -)
	 * -----------------------------------------------------------------------
	 * @return <text> return (a data com o formtado YYYY/MM/DD ou YYYY-MM-DD)
	 * -----------------------------------------------------------------------
	 */
	public static function transformaData($data, $forma="/") {
		// retorno
		$retorno = "";
		
		// verifica a data recebida
		if (!empty($data)) {
			// explode
			$eData = explode("/", $data);
			
			// monta o retorno
			$retorno = $eData[2] . $forma . $eData[1] . $forma . $eData[0];
		}
	
		// retorna a data formatada
		return $retorno;
		
	}
}