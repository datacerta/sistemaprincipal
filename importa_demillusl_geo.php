<?php
/**
 * Carga Demillus - EXEC
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel
$prgTit = "Carga Demillus";

// pega a configuracao
require_once("classes/diversos.inc.php");
define("HOST", "http://".$_SERVER["HTTP_HOST"]);

/**
 * Funcao de importacao
 *
 * @param $linha
 */
 
function retorna_geo($endereco, $cep, $cidade){

	if(empty($endereco))
		return null;
	$key = "AIzaSyBYZ9DMPrJe2cwU66S7Y1H7j6VWH4_2o-k";

	$endereco = utf8_decode(trim($endereco));
	$cidade = utf8_decode(trim($cidade));
	$data_virg = explode(',',$endereco,2);
	$numero_esp = explode(' ', $data_virg[1],2);
	$endereco = $data_virg[0];
	$numero = $numero_esp[0];

	if(empty($numero))
		$endereco_completo = $endereco;
	else
		$endereco_completo = $endereco.", ".$numero;
	if(strlen($cep) < 8){
		$formatcep = str_pad($cep, 8, "0", STR_PAD_LEFT);
		$cep = substr($formatcep,0,5)."-".substr($formatcep,5,3);
	}else{
		$cep = substr($cep,0,5)."-".substr($cep,5,3);
	}

	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode(trim($endereco_completo))."&components=country:BR|postal_code:".$cep."|locality:".urlencode(trim($cidade))."&key=$key";

	$data = json_decode(file_get_contents($url));

	if(count($data->results) == 0){
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode(trim($endereco_completo))."&components=country:BR|locality:".urlencode(trim($cidade))."&key=$key";
	}

	$data = json_decode(file_get_contents($url));

	return $data;
}
function  importador_tipo_003($linha){
            $posicoes[] = 2;    // 0 = tipo de registro
            $posicoes[] = 56;  // 1 = lixo
            $posicoes[] = 8;   // 2 = Data Emissao
            $posicoes[] = 1297; // 2 = Lixo
            
            $posicao_inicial = 0;

            for($i=0;$i<count($posicoes);$i++){
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
function  importador_tipo_001($linha){
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
            $posicoes[] = 112;  // 23 = Caixas
            $posicoes[] = 44;  // 24 = Danfe
            
            
   
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
                  $retorno_001["caixas"]                     = $campo[23];
                  $retorno_001["danfe"]                      = $campo[24];
		          return $retorno_001;
}
 
// seta o EXEC
$_Exec = HOST."/Exec/exec-importa_demillusl.php?token={$rnd}";

// seta o link atual
$selfLink = HOST.$PHP_SELF."";

// pega o header
require_once("inc/header.inc");

?>

<div class="box" style="margin: 0 auto;">


<form enctype="multipart/form-data" action="<?=$selfLink?>" onsubmit="return valida_form(this)" method="post">
   <input type=hidden name=ok value=1>
      SELECIONE O ARQUIVO
      <TABLE BORDER=0>
        <tr>
            <td><input type=file name="arquivo"></td>
            <td><span>Data de Coleta: </span><input type="text" class="dataemi" id="datadecoleta" name="var_data_coleta" style="text-align: center;" size="12" /></td>
            <td><input type=submit value="Enviar Dados"></td>
        </tr>
        
      </table>

      <?php
      $qry            = new consulta($con);
      $qry2            = new consulta($con);
      $qry_prod       = new consulta($con);
      $qry_campanha   = new consulta($con);
      $qry_revend = new consulta($con);
      $qry_revend_exe = new consulta($con);
      $lqry = new consulta($con);

        if ($ok){
             $file = file($arquivo);
			$datacoletaFormat = explode("/",$var_data_coleta);
			$var_data_coleta = $datacoletaFormat[2]."-".$datacoletaFormat[1]."-".$datacoletaFormat[0];
			$var_data_coleta_ano = $datacoletaFormat[2];
			$retorno_001 = importador_tipo_001($file[0]);
			$cliente = 6670;
			$produto = 543;
			
			//$cliente =6846;
			//$produto =16;
			$tpr     = 1;
			$barras  = 'N';
							
				
			$sql = "SELECT numloteimporta
			    	FROM tbconfigproduto WHERE 
					idtransportadora= '".$tpr."'
					AND
					codigoproduto= '".$produto."' 
					and codcliente = '".$cliente."'";
					$qry->executa($sql);
	    		    
					
					$lotenovo = $loteimporta+1;  
	    		    $prefixo       = $qry->data["prefixo"];
					$sufixo        = $qry->data["sufixo"];
					$id            =  $qry->data["codigodebarras"];
					$bbb           = $qry->data["codigodebarras"]; 
	    			$sql = "UPDATE tbconfigproduto SET numloteimporta='".$lotenovo."' 
					WHERE idtransportadora= '".$tpr."' AND 
					codigoproduto= '".$produto."' AND codcliente='".$cliente."'";
					$qry->executa($sql);
	 		        $loteimporta   = $qry->data["numloteimporta"];
             
                       		 
      for($i=0;$i<count($file);$i++){
         
         
         
           
          $tipo_linha = substr($file[$i],0,2);
          switch($tipo_linha){
                case("03"):
                    $retorno_003 = importador_tipo_003($file[$i]);
                    $dataemissao = trim($retorno_003["dataemissao"]);
                 //   echo   $dataemissao ."<br>";
                    $dia = substr($dataemissao,0,2);
                    $mes = substr($dataemissao,2,2);
                    $ano = substr($dataemissao,4);
                    $dtemissao = date("Y/m/d", mktime(0,0,0,$mes,$dia,$ano));
                  //  echo   $dtemissao ."<br>";
                    break;
               case("02"):
  				  					
	                  $retorno_001 = importador_tipo_001($file[$i]);
	  						    $data = date("mdy");
                    $loteimporta = trim($retorno_001["setor"]).$dataemissao;
                    $sql = "SELECT prazocapital,prazointerior,prefixo,sufixo,codigodebarras
								    FROM tbconfigproduto WHERE
								    idtransportadora= '".$tpr."'
								    AND
								    codigoproduto= '".$produto."'
								    and codcliente = '".$cliente."'";
								    $qry->executa($sql);
                    $prazocapital  =  $qry->data["prazocapital"];
			              $prazointerior =  $qry->data["prazointerior"];
				     				$barras        =  $qry->data["codigodebarras"];
								    $bbb           =  $qry->data["codigodebarras"];
 
								   
                    $codigoregiao   = 2;
                    $idExterno      = trim($retorno_001["idexterno"]);
					$idrevendedora  = abs($retorno_001["idrevendedora"]);
					$nota           = trim($retorno_001["nota"]);
                    $volumes        = abs($retorno_001["volumes"]);
                    $valor          = $retorno_001["valor"];
					$obs            = $retorno_001["obs"].'TRAZER CANHOTO DA NFF ASSINADO';
                    $peso           = $retorno_001["peso"];
                    $setor          = $retorno_001["setor"];
                    $caixas          = $retorno_001["caixas"];
                    $danfe          = $retorno_001["danfe"];
                  	$endereco       = remove_acentos($retorno_001["enderecoentrega"]);
					$bairro         = remove_acentos($retorno_001["bairroentrega"]);
					$rota           = $retorno_001["rota"];
					$cpf           = $retorno_001["cpf"];
					$complemento    = remove_acentos($retorno_001["complementoenderecoentrega"]);
          		    $vsetor=abs($setor);
				 	  
									

										               
									                //POPULA DE MILLUS
                                       
									
                                   $sql = "SELECT * from tb_demillus_revend  
                                           WHERE  id_revend = '$idrevendedora'";
                                           $qry_revend->executa($sql);
                                                 
                                         if (!$qry_revend->nrw){
                                         
                                         	$data = retorna_geo($retorno_001["enderecoentrega"],$retorno_001["cepentrega"],$retorno_001["cidadeentrega"]);
											if(count($data->results) == 0 or empty($data)){
												$latitude = 0;
												$longitude = 0;
											}else{
												$latitude = $data->results[0]->geometry->location->lat;
												$longitude = $data->results[0]->geometry->location->lng;
											}
											echo $idrevendedora." INSERE<br>";
                                          $sql =  "INSERT INTO tb_demillus_revend
                                                   (
                                                   id_revend, 
                										               nome_revend,
                										               id_setor,
                                                   cep,
                                                   uf,
                                                   endereco,
                                                   bairro,
                                                   cidade,
                                                   latitude,
                                                   longitude
                									)
                                                   VALUES
                                                   (
                                                   '$idrevendedora',
                								   '".substr(trim($retorno_001["nomeentrega"]),0,50)."',
                								  '$setor',
                                                   '".$retorno_001["cepentrega"]."',
                                                   '".$retorno_001["estadoentrega"]."',
                                                   '".utf8_decode(substr(trim($retorno_001["enderecoentrega"]),0,69))."',
                                                   '".utf8_decode($retorno_001["bairroentrega"])."',
										           '".utf8_decode($retorno_001["cidadeentrega"])."',
										           '".$latitude."',
										           '".$longitude."'
                										               )";
                                                $qry_revend_exe->executa($sql);   
                                                
                                                // GRAVAR A GEO LOCALIZAÇÃO
                                                
                                                //echo $sql;
                                                //echo "<br>";
                                                
                                                   }  
                                        
                                        
										               
										             else
										             {
                                 //Atualiza
                                 // PRIMEIRO VERIFICAR SE O ENDREÇO DA TB_REVEND_DEMILLUS = AO $VAR ENDEREÇO
                                 // SE FOR IGUAL = NÃO FAZ NADA
                                 // SE FOR DIFERENTE FAZ UPDATE E GEO LOCALIZAÇÃO.
										    
                                   $sql = "SELECT * from tb_demillus_revend  
                                           WHERE id_revend = '$idrevendedora' AND endereco <> '".utf8_decode(substr(trim($retorno_001["enderecoentrega"]),0,69))."'";
                                           $qry_revend->executa($sql);
                                    if ($qry_revend->nrw){
	                                 	$data = retorna_geo($retorno_001["enderecoentrega"],$retorno_001["cepentrega"],$retorno_001["cidadeentrega"]);
	                   
										if(count($data->results) == 0 or empty($data)){
											$latitude = 0;
											$longitude = 0;
										}else{
											$latitude = $data->results[0]->geometry->location->lat;
											$longitude = $data->results[0]->geometry->location->lng;
										}
										echo $idrevendedora." ATUALIZA<br>";
	                                   $sql = "UPDATE tb_demillus_revend SET bairro = '".utf8_decode($retorno_001["bairroentrega"])."',id_setor = '".$setor."', cep = '".$retorno_001["cepentrega"]."', endereco = '".utf8_decode(substr(trim($retorno_001["enderecoentrega"]),0,69))."', cidade = '".utf8_decode($retorno_001["cidadeentrega"])."', checado = 'false' WHERE  id_revend = '$idrevendedora'";
									  $qry_revend_exe->executa($sql);   
								    }

                                 //echo $sql;
                                 
                                 
                                 
                                 } 
										             
								 
							
                          }
					
             }
             if(isset($error))
             	echo $error;
             echo "<BR><BR><CENTER><FONT COLOR=#aa0000><B></B> Arquivo Importado com sucesso total : " .$p. "<BR>Lote gerado: ".$loteimporta."<BR></font></CENTER>";
        }
      ?>
  </form>
  
</div>
<script>
( function( $ ) {
	$(function() {
		$('.dataemi').datepicker({  dateFormat: 'dd/mm/yy',   dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
		    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
		    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
		    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		    nextText: 'Próximo',
		    prevText: 'Anterior' });
		$('.dataemi').mask("99/99/9999");

	});
} )( jQuery );	

function valida_form (){
	if(document.getElementById("datadecoleta").value.length < 10){
		alert('Por favor, preencha o data de coleta');
		return false;
	}
}
</script>

<?php
// pega o Footer
require_once("inc/footer.inc");