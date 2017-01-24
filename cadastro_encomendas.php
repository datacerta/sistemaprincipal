<?php
/**
 * Cadastro de Encomendas
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel
$prgTit = "Cadastro de Encomendas";

// pega a configuracao
require_once("inc/config.inc");

// consulta
$qry  = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry6 = new consulta($con);
$qry7 = new consulta($con);

switch($opt){
        case("B"):

        if(!($idtransportadora > 0)){
                echo "<script>alert('Por favor selecione o tipo de servico');</script>";
                break;
        }

        if(!($codcliente > 0)){
                echo "<script>alert('Por favor selecione o cliente');</script>";
                break;
        }

        if(!($codigoproduto > 0)){
                echo "<script>alert('Por favor selecione o produto');</script>";
                break;
        }

        if(!($idtipoentrega > 0)){
                echo "<script>alert('Por favor selecione o tipo de servico');</script>";
                break;
        }
		
		//echo $codcliente;

        $sql = "SELECT numloteimporta,predigitacao FROM tbconfigproduto WHERE
                idtransportadora = '$idtransportadora' and
                codcliente = '$codcliente' and
                codigoproduto = '$codigoproduto'";
             //   echo $sql;
                $qry->executa($sql);
                $predigitacao = $qry->data["predigitacao"];
                $numloteimporta_tbconfigproduto = $codcliente.$codigoproduto.$qry->data["numloteimporta"];
       
       
       
        if($predigitacao=="t"){
                $qry->nrw = 0;
                $sql = "SELECT quantidade,numloteinterno,completo FROM tbpredigitacao WHERE
                idtransportadora = '$idtransportadora' and
                codcliente = '$codcliente' and
                codigoproduto = '$codigoproduto' and completo='f' ORDER BY numloteinterno DESC LIMIT 1";  // echo $sql;
                $qry->executa($sql);

                if($qry->nrw)
                $loteimporta = $qry->data["numloteinterno"];
                elseif(!$qry->nrw){

                        if (!$loteimporta){
                                $loteimporta = $numloteimporta_tbconfigproduto;
                                $sql = "UPDATE tbconfigproduto SET numloteimporta = numloteimporta+1 WHERE
                                idtransportadora = '$idtransportadora' and
                                codcliente = '$codcliente' and
                                codigoproduto = '$codigoproduto'";
                                $qry->executa($sql);

                        }

                        $qry->nrw=0;
                        $sql = "SELECT quantidade,numloteinterno,completo FROM tbpredigitacao WHERE
                                    idtransportadora = '$idtransportadora' and
                                    codcliente = '$codcliente' and
                                    codigoproduto = '$codigoproduto' and numloteinterno='$loteimporta' ORDER BY numloteinterno DESC LIMIT 1";
                                    $qry->executa($sql);

                        if($qtd_predigitacao > 0){
                                if(!$qry->nrw){
                                        $sql = "INSERT INTO tbpredigitacao (idtransportadora,codcliente,codigoproduto,numloteinterno,quantidade,data,codlogin,completo)
                                        VALUES ('$idtransportadora','$codcliente','$codigoproduto','$loteimporta','$qtd_predigitacao','".date("m/d/Y H:i:s")."','".$_SESSION["IDUSER"]."','f')";
                                        $qry->executa($sql);
                                        //$mostra_predigitacao = "/$qtd_predigitacao";
                                }
                        }else{
                                echo "<script>alert('Por favor preencha a quantidade para a pré-digitação');</script>";
                                break;
                        }
                }

        }else{
                if (!$loteimporta){
                        $loteimporta = $numloteimporta_tbconfigproduto;
                        $sql = "UPDATE tbconfigproduto SET numloteimporta = numloteimporta+1 WHERE
                                idtransportadora = '$idtransportadora' and
                                codcliente = '$codcliente' and
                                codigoproduto = '$codigoproduto'";
                                $qry->executa($sql);
                }
        }
        break;

        case("P"):
        if(!$coddestinatario_entrega and !$id_destinatario_entrega_combo){
                echo "<script>alert('Por favor preenha o código ou selecione o destinatário da coleta');</script>";
                break;
        }

        $sql = "SELECT * FROM tbdestinatario WHERE
        idtransportadora = '$idtransportadora' and
        codcliente = '$codcliente' and
        codproduto = '$codigoproduto' and";
	
        if($id_destinatario_entrega_combo)
            $sql.= "iddestinatario = $id_destinatario_entrega_combo";
        else
            $sql.= " coddestinatario = '$coddestinatario_entrega'";

      // echo $sql;
        $qry->executa($sql);

        if($qry->nrw>0){
                $empresa_entrega = $qry->data["nomedestino"];
                $endereco_entrega = $qry->data["endereco"];
                $bairro_entrega = $qry->data["bairro"];
                $cidade_entrega = $qry->data["cidade"];
                $uf_entrega = $qry->data["uf"];
                $contato_entrega = $qry->data["contato"];
                $telefone_entrega = $qry->data["telefone"];
                $cep_entrega = $qry->data["cep"];
                $codigodaregiao_entrega = $qry->data["codigodaregiao"];
                $coddestinatario_entrega = $qry->data["coddestinatario"];
                $id_destinatario_entrega_combo = $qry->data["iddestinatario"];
        }else{
                $empresa_entrega = "";
                $endereco_entrega = "";
                $bairro_entrega = "";
                $cidade_entrega = "";
                $uf_entrega = "";
                $contato_entrega = "";
                $telefone_entrega = "";
                $cep_entrega = "";
                $codigodaregiao_entrega = "";
        }


        break;

        case("G"):



        if(!$coddestinatario_entrega and $ativa_dest=='t'){
                echo "<script>alert('Por favor preenha o código do destinatário');</script>";
                break;
        }

        if($envelope > $ultimo and $somente_nome!="t"){
                echo "<script>alert('O campo último precisa ser maior que o campo envelope');</script>";
                break;
        }

        if(!$empresa_entrega){
                echo "<script>alert('Por favor preenha o nome do destinatário');</script>";
                break;
        }

        if(!($codforma > -1)){
                echo "<script>alert('Por favor preenha a forma de pagamento');</script>";
                break;
        }

        if(!verifica_data($data_entrega)){
                echo "<script>alert('Por favor preenha a data de entrega com uma data válida');</script>";
                break;
        }

        $qtdvolumes = intval(trim($qtdvolumes));
        if(!$qtdvolumes or !(is_int($qtdvolumes))){
                echo "<script>alert('Por favor entre com uma quantidade de volume válida');</script>";
                break;
        }

        $sql = "SELECT geracodigobarras,digitacodbarras,prefixo,sufixo,codigodebarras,
		        prazocapital,prazointerior,dva FROM tbconfigproduto WHERE
        idtransportadora = '$idtransportadora' and
        codcliente = '$codcliente' and
        codigoproduto = '$codigoproduto'";
        //echo "$sql<br>";
        $qry->executa($sql);

        if(!$qry->nrw){
                echo "<script>alert('Configuração do produto não encontrada. Favor entrar em contato com o administrador');</script>";
                break;
        }elseif($qry->data["digitacodbarras"]<>'f'){
                $input_idexterno =  trim($input_idexterno);
                
                
                if(!$input_idexterno){
                        echo "<script>alert('Por favor preenha o código de barras');</script>";
                        break;
                }else{
                        $idexterno = $input_idexterno;
                      
                        
                        
                }
        }else{
                $idexterno = strtoupper($qry->data["prefixo"].$qry->data["codigodebarras"].$qry->data["sufixo"]);
                $qry2->res = "";
                $sql2 = "UPDATE tbconfigproduto SET codigodebarras=(codigodebarras+1) WHERE
                         idtransportadora = '$idtransportadora' and
                         codcliente = '$codcliente' and
                         codigoproduto = '$codigoproduto'";
                $qry2->executa($sql2);
                if(!$qry2->res){
                        echo "Ocorreu durante a atualização do codigo de barras";
                        exit;
                }
        }

        
       //   if ($codcliente = 408)
       //        $idexterno = abs(substr($idexterno,19,8));
       //   else      
       //       $idexterno = trim($idexterno);

        //verificacao de idexterno existente
        $qry2->res = "";
        $qry2->nrw = 0;
        $sql2 = "SELECT idinterno FROM tbentrega WHERE idexterno = '$idexterno'";
        $qry2->executa($sql2);
        if($qry2->nrw){
                echo "<script>alert('Código de barras já existente. Por favor insira outro');</script>";
                break;
        }


        $dva = $qry->data["dva"];//verificador de devolucao em agencia
        $dataoperacao = date("Y/m/d");
        $dataemissao = date("Y/m/d");
        $datacoletado = date("Y/m/d");
        $codbase = $_SESSION['IDBASE'];
        
        $coleta_sodexo = 0;
        if ($codcliente == 408) 
            $coleta_sodexo = 98;

        if($codigodaregiao_entrega==2 or $codigodaregiao_entrega==3)
        $datapromessa = data_soma_dia(date("Y/m/d"),$qry->data["prazocapital"]);
        else
        $datapromessa = data_soma_dia(date("Y/m/d"),$qry->data["prazointerior"]);


        if(!$codigodaregiao_entrega)
        $codigodaregiao_entrega = 2;

        if($dva=="t"){
                $qry->nrw=0;
                $sql = "SELECT idinterno FROM tbentrega WHERE numlista = '$input_idexterno' LIMIT 1";
                $qry->executa($sql);
                if($qry->nrw){
                        $sql2 = "SELECT codigoagencia,nomeagencia FROM tbbancodados WHERE codigoagencia = '$coddestinatario_entrega' AND idinterno=".$qry->data["idinterno"];
                        $qry2->executa($sql2);
                        if(!$qry2->nrw){
                                echo "<script>alert('A lista $input_idexterno não pertence a agência ".$coddestinatario_entrega.".\\n A operação foi cancelada.');</script>";
                                break;
                        }
                }else{
                        echo "<script>alert('Nenhuma encomenda encontrada na lista $input_idexterno.');</script>";
                        break;
                }
        }


        //INICIO - Cadastra destinario do coleta se ele nao estive na base
        if($ativa_dest=='t' and ($id_destinatario_entrega_combo or $coddestinatario_entrega)){
                $sql = "SELECT iddestinatario FROM tbdestinatario WHERE 
                                    

			idtransportadora = '$idtransportadora' AND
                                       codcliente = '$codcliente' AND
                                       codproduto = '$codigoproduto' AND";

                        if($id_destinatario_entrega_combo)
                        $sql.= "                                   iddestinatario = $id_destinatario_entrega_combo";
                        else
                        $sql.= "                                   coddestinatario = '$coddestinatario_entrega'";

                $qry->executa($sql);


                //echo "$sql<br>";
                 //$endereco_entrega =  remove_acentos($endereco_entrega);
                 //$empresa_entrega =  remove_acentos($empresa_entrega);
                if(!$qry->nrw>0){
                        $sql = "INSERT INTO tbdestinatario
                                       (idtransportadora,codproduto,codcliente,nomedestino,endereco,
                                       bairro,cidade,uf,contato,telefone,
                                       cep,coddestinatario,codigodaregiao)
                                       VALUES
                                       ('$idtransportadora','$codigoproduto','$codcliente','$empresa_entrega','$endereco_entrega',
                                       '$bairro_entrega','$cidade_entrega','$uf_entrega','$contato_entrega','$telefone_entrega',
                                       '$cep_entrega','$coddestinatario_entrega','$codigodaregiao_entrega')";
                        $qry->executa($sql);
                        //echo "$sql<br>";

                        if(!$qry->res){
                                echo "101 - Ocorreu durante a inclusão do destinatario da coleta";
                                exit;
                        }
                }else{
                       

					   $sql = "UPDATE tbdestinatario SET
                                       nomedestino='$empresa_entrega',
                                       endereco='$endereco_entrega',
                                       bairro='$bairro_entrega',
                                       cidade='$cidade_entrega',
                                       uf='$uf_entrega',
                                       contato='$contato_entrega',
                                       telefone='$telefone_entrega',
                                       cep='$cep_entrega',
                                       coddestinatario='$coddestinatario_entrega',
                                       codigodaregiao='$codigodaregiao_entrega'
                                       WHERE
                                       idtransportadora = '$idtransportadora' AND
                                       codcliente = '$codcliente' AND
                                       codproduto = '$codigoproduto' AND";

                        if($id_destinatario_entrega_combo)
                        $sql.= "                                   iddestinatario = $id_destinatario_entrega_combo";
                        else
                        $sql.= "                                   coddestinatario = '$coddestinatario_entrega'";

                        $qry->executa($sql);

                        if(!$qry->res){
                                echo "101 - Ocorreu durante a alteração do destinatario da coleta";
                                exit;
                        }

                }
        }
        //FIM - Cadastra destinario do coleta se ele nao estive na base

        $obs = trim(remove_acentos($obs));
        $valor_tr = str_replace(",",".",$valor_tr);
        $valor_tt = str_replace(",",".",$valor_tt);
        $valor_total = $valor_tr + $valor_tt;

        $total_envelopes = ($ultimo - $envelope) + 1;
		
		
        $sql = "SELECT sequencialoteinterno FROM tbentrega WHERE idtransportadora='$idtransportadora' 
		AND codigoproduto='$codigoproduto' AND codcliente='$codcliente' 
		AND numloteinterno='$loteimporta' ORDER BY sequencialoteinterno DESC LIMIT 1";
        
        $qry->executa($sql);

        $numsequencia = $qry->data["sequencialoteinterno"] + 1;
        
        $qry->res="";
		
        //coddestinatario_entrega
        $obs = remove_acentos($obs);
		
		$sql = "INSERT INTO tbentrega
               (idtransportadora,pcg,codigoproduto,codcliente,valorentrega,
               primeiroenvelope,ultimoenvelope , numconta,numerosedex,
               idexterno,idtipoentrega,dataemissao,datacoletado,codbase,
               dataoperacao,idtipomovimento,datapromessa,numloteinterno,numlotecliente,quantidadevolumes,sequencialoteinterno,obsentrega)
               VALUES
               ('$idtransportadora','$coleta_sodexo','$codigoproduto','$codcliente','$valor_tr','$envelope','$ultimo','$coddestinatario_entrega','$codcliente',
               '$idexterno','$idtipoentrega','$dataemissao','$datacoletado','$codbase',
               '$dataoperacao','300','$dataoperacao','$loteimporta','$loteimporta','$qtdvolumes','$numsequencia','$obs')";
        //echo "$sql<br>";
        $qry->executa($sql);
	

        if(!$qry->res){
                echo "102 - Ocorreu durante a inclusão da entrega";
                exit;
        }else{


                //$qry->executa("select last_value from tbentrega_idinterno_seq");
                $qry->executa("select idinterno FROM tbentrega WHERE idexterno = '$idexterno'");
                if ($qry->data["idinterno"])
                        $idinterno = $qry->data["idinterno"];
                else
                        echo "ERRO IDINTERNO NÃO ENCONTRADO";
                /*
                $sql = "UPDATE tbconfigproduto SET codigodebarras=(codigodebarras+1) WHERE
                                      idtransportadora = '$idtransportadora' and
                                      codcliente = '$codcliente' and
                                      codigoproduto = '$codigoproduto'";
                //echo "$sql<br>";
                $qry->executa($sql);

                */


                        if($obs){
                                $obs=remove_acentos($obs);
								$sql = "INSERT INTO tbreferenciaendereco
                                       (idinterno,nomereferenciaendereco)
                                       VALUES
                                       ('$idinterno','$obs')";
                                //echo "$sql<br>";
                                $qry->executa($sql);

                                if(!$qry->res){
                                        echo "103 - Ocorreu durante a inclusão da referencia de endereço";
                                        exit;
                                }
                        }
                        $obs=remove_acentos($obs);
						$empresa_entrega=remove_acentos($empresa_entrega);
						$bairro_entrega=remove_acentos($bairro_entrega);
						$cidade_entrega=remove_acentos($cidade_entrega);
						$endereco_entrega=remove_acentos($endereco_entrega);
						$contato_entrega=remove_acentos($contato_entrega);
						
						
						
						
                        $sql = "INSERT INTO tbenderecoentrega
                                       (idinterno,nomeentrega,bairroentrega,cidadeentrega,estadoentrega,cepentrega,
                                       enderecoentrega,responsavelentrega,foneenderecoentrega,obsentrega,codigodaregiao)
                                       VALUES
                                       ('$idinterno',
									   '$empresa_entrega',
									   '$bairro_entrega',
									   '$cidade_entrega',
									   '$uf_entrega',
									   '$cep_entrega',
                                       '$endereco_entrega',
									   '$contato_entrega',
									   '$telefone_entrega',
									   '$obs',
					'$codigodaregiao_entrega')";

                        $qry->executa($sql);
			

                        if(!$qry->res){
                                echo "104 - Ocorreu durante a inclusão do endereço de entrega";
                                exit;
                        }

                    

                        //inserindo movimento nas encomendas dva dependentes dessa encomenda
                        if($dva=="t"){
                                $qry->nrw=0;
                                $sql = "SELECT idinterno,numlista,idtransportadora,codbase FROM tbentrega WHERE numlista = '$input_idexterno' ORDER BY idinterno";
                                $qry->executa($sql);
                                for($i=0;$i<$qry->nrw;$i++){
                                        $qry->navega($i);

                                        $qry2->res="";
                                        $sql2 = "INSERT INTO tbmovimento
                                       (idinterno,codlogin,numlista,idtipomovimento,codbase,dataoperacao,
                                       horaoperacao,idtransportadora,codigocourier,codbasedestino)
                                       VALUES
                                       ('".$qry->data["idinterno"]."','".$_SESSION['IDUSER']."','".$qry->data["numlista"]."','510','".$qry->data["codbase"]."','".date("Y-m-d")."',
                                       '".date("H:i:s")."','".$qry->data["idtransportadora"]."','0','".$qry->data["codbase"]."')";

                                        //echo "$sql2<br>";
                                        $qry2->executa($sql2);

                                        if(!$qry2->res)
                                        die("108 - Ocorreu durante a inclusão da movimento 'AR para Entrega Agência Emitido'");
                                        else{
                                                $qry2->res="";
                                                $sql2 = "UPDATE tbentrega SET dataoperacao='".date("Y/m/d")."',idtipomovimento='510' WHERE idinterno = '".$qry->data["idinterno"]."'";
                                                $qry2->executa($sql2);
                                                if(!$qry2->res)
                                                die("203 - Ocorreu durante a atualização das encomendas de devolucao");
                                        }
                                }
                        }

                        //echo "<script>alert('Gravado com sucesso!')</script>";
                        echo "<center>Encomenda <b>$idexterno</b> gravada com sucesso!<center><br>";
                        echo "<script>document.form_codigo.input_idexterno.focus();</script>";

                        $coddestinatario_entrega = "";
                        $id_destinatario_entrega_combo = "";

                        $empresa_entrega = "";
                        if($somente_nome!="t"){
						        
                                $input_idexterno = "";
                                $endereco_entrega = "";
                                $bairro_entrega = "";
                                $cidade_entrega = "";
                                $uf_entrega = "";
                                $contato_entrega = "";
                                $telefone_entrega = "";
                                $cep_entrega = "";
                                $codigodaregiao_entrega = "";
                                $valor_tr = "";
                                $valor_tt = "";
                                $valor_total = "";
                                //$data_entrega = "";
                                $envelope = "";
                                $ultimo = "";
                                $total_envelopes = "";
                                $obs = "";
                                $somente_nome = "";
                                $qtdvolumes = "";
                                $codforma = "";
                        }
                        //$ativa_dest = "";


        }
        break;

}

if ($loteimporta){// and (!$total_encomendas or !$mostra_predigitacao)){
$sql = "SELECT COUNT(*) FROM tbentrega WHERE
                                    idtransportadora = '$idtransportadora' and
                                    codcliente = '$codcliente' and
                                    codigoproduto = '$codigoproduto' and
                                    numloteinterno = '$loteimporta'";
$qry->executa($sql);
$total_encomendas = $qry->data["count"];
/*
$qry->nrw = 0;
$sql = "SELECT predigitacao FROM tbconfigproduto WHERE
idtransportadora = '$idtransportadora' and
codcliente = '$codcliente' and
codigoproduto = '$codigoproduto'";
$qry->executa($sql);
$predigitacao = $qry->data["predigitacao"];
*/
if($predigitacao=="t"){
        $qry->nrw = 0;
        $sql = "SELECT quantidade,completo FROM tbpredigitacao WHERE
                                    idtransportadora = '$idtransportadora' and
                                    codcliente = '$codcliente' and
                                    codigoproduto = '$codigoproduto' and
                                                                        numloteinterno= '$loteimporta'";
        $qry->executa($sql);

        if($qry->nrw){
                $qtd_predigitacao = $qry->data["quantidade"];
                $mostra_predigitacao = "/$qtd_predigitacao";
                if($qtd_predigitacao > $total_encomendas)
                $readonly_pre_lote = "readOnly=true";
                else{
                        if($qry->data["completo"]!="t"){
                                $sql = "UPDATE tbpredigitacao SET completo='t' 
								       WHERE idtransportadora = '$idtransportadora' and codcliente = '$codcliente' and codigoproduto = '$codigoproduto' and numloteinterno= '$loteimporta'";
                                $qry->executa($sql);
                        }
                        echo "<font color='FF0000'>A digitação deste lote já está completa. Por favor selecione outro lote.</font><br>";
                        echo "<script>alert('A digitação deste lote já está completa.\\nPor favor selecione outro lote.');</script>";
                        $digitacao_completa=1;
                }
        }
}
}

// seta o link atual
$selfLink = HOST."/cadastro_encomendas.php?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>

<!-- CSS Local -->
<link href="<?=HOST?>/css/table_2.css" rel="stylesheet" type="text/css" />
<link href="<?=HOST?>/css/tip.css"     rel="stylesheet" type="text/css" />

<!-- JS Local -->
<script>

                function ativa_destiva_dest(){
                        if(document.form_codigo.ativa_dest.checked){
                                document.form_codigo.id_destinatario_entrega_combo.disabled = false;
                                document.form_codigo.coddestinatario_entrega.disabled = false;
                                document.form_codigo.procurar_dest.disabled = false;
                                document.form_codigo.coddestinatario_entrega.focus();
                        }else{
                                document.form_codigo.id_destinatario_entrega_combo.disabled = true;
                                document.form_codigo.coddestinatario_entrega.disabled = true;
                                document.form_codigo.procurar_dest.disabled = true;
                                document.form_codigo.empresa_entrega.focus();
                        }
                }

                function somente_nome_dest(){
                        if(document.form_codigo.somente_nome.checked){

                                document.form_codigo.endereco_entrega.readOnly = true;
                                document.form_codigo.cidade_entrega.readOnly = true;
                                document.form_codigo.cep_entrega.readOnly = true;
                                document.form_codigo.contato_entrega.readOnly = true;
                                document.form_codigo.bairro_entrega.readOnly = true;
                                document.form_codigo.uf_entrega.readOnly = true;
                                document.form_codigo.telefone_entrega.readOnly = true;
                                document.form_codigo.codigodaregiao_entrega.disabled = true;

                                document.form_codigo.valor_tr.readOnly = true;
                                document.form_codigo.valor_tt.readOnly = true;
                                document.form_codigo.valor_total.readOnly = true;
                                document.form_codigo.data_entrega.readOnly = true;
                                document.form_codigo.envelope.readOnly = true;
                                document.form_codigo.ultimo.readOnly = true;
                                document.form_codigo.total_envelopes.readOnly = true;
                                document.form_codigo.obs.readOnly = true;


                                document.form_codigo.endereco_entrega.value = 'NA';
                                document.form_codigo.cidade_entrega.value = 'NA';
                                document.form_codigo.cep_entrega.value = '99999999';
                                document.form_codigo.contato_entrega.value = 'NA';
                                document.form_codigo.bairro_entrega.value = 'NA';
                                document.form_codigo.uf_entrega.value = 'NA';
                                document.form_codigo.telefone_entrega.value = 'NA';

                                document.form_codigo.valor_tr.value = '0';
                                document.form_codigo.valor_tt.value = '0';
                                document.form_codigo.valor_total.value = '0';
                                document.form_codigo.data_entrega.value = '<?=date("d/m/Y")?>';
                                document.form_codigo.envelope.value = '0';
                                document.form_codigo.ultimo.value = '0';
                                document.form_codigo.total_envelopes.value = '1';
                                document.form_codigo.obs.value = '';

                                document.form_codigo.empresa_entrega.tabIndex = '1';
                                document.form_codigo.envia_form.tabIndex = '2';

                                document.form_codigo.empresa_entrega.focus();

                        }else{

                                document.form_codigo.endereco_entrega.readOnly = false;
                                document.form_codigo.cidade_entrega.readOnly = false;
                                document.form_codigo.cep_entrega.readOnly = false;
                                document.form_codigo.contato_entrega.readOnly = false;
                                document.form_codigo.bairro_entrega.readOnly = false;
                                document.form_codigo.uf_entrega.readOnly = false;
                                document.form_codigo.telefone_entrega.readOnly = false;
                                document.form_codigo.codigodaregiao_entrega.disabled = false;

                                document.form_codigo.valor_tr.readOnly = false;
                                document.form_codigo.valor_tt.readOnly = false;
                                document.form_codigo.valor_total.readOnly = false;
                                document.form_codigo.data_entrega.readOnly = false;
                                document.form_codigo.envelope.readOnly = false;
                                document.form_codigo.ultimo.readOnly = false;
                                document.form_codigo.total_envelopes.readOnly = false;
                                document.form_codigo.obs.readOnly = false;


                                document.form_codigo.endereco_entrega.value = '';
                                document.form_codigo.cidade_entrega.value = '';
                                document.form_codigo.cep_entrega.value = '';
                                document.form_codigo.contato_entrega.value = '';
                                document.form_codigo.bairro_entrega.value = '';
                                document.form_codigo.uf_entrega.value = '';
                                document.form_codigo.telefone_entrega.value = '';

                                document.form_codigo.valor_tr.value = '0';
                                document.form_codigo.valor_tt.value = '0';
                                document.form_codigo.valor_total.value = '0';
                                document.form_codigo.data_entrega.value = '<?=date("Y/m/d")?>';
                                document.form_codigo.envelope.value = '0';
                                document.form_codigo.ultimo.value = '0';
                                document.form_codigo.total_envelopes.value = '1';
                                document.form_codigo.obs.value = '';

                                document.form_codigo.empresa_entrega.tabIndex = '';
                                document.form_codigo.envia_form.tabIndex = '';

                                document.form_codigo.empresa_entrega.focus();
                        }
                }

        function somar_valores(){
                 val_valortr = document.form_codigo.valor_tr.value.replace(',','.');
                 val_valortt = document.form_codigo.valor_tt.value.replace(',','.');

                 val_valortr = parseFloat(val_valortr);
                 val_valortt = parseFloat(val_valortt);

                 if(val_valortt!=Math.abs(val_valortt))
                 val_valortt = 0;

                 if(val_valortr!=Math.abs(val_valortr))
                 val_valortr = 0;

                 document.form_codigo.valor_total.value = val_valortr + val_valortt;
        }

        function subtrair_envelopes(){
                 if(document.form_codigo.envelope.value && document.form_codigo.ultimo.value){
                    val_envelope = parseInt(document.form_codigo.envelope.value);
                    val_ultimo = parseInt(document.form_codigo.ultimo.value);

                    document.form_codigo.total_envelopes.value = (val_ultimo - val_envelope) + 1;

                    if(val_envelope > val_ultimo){
                        document.form_codigo.ultimo.focus();
                        alert('O campo último precisa ser maior que o campo envelope');
                    }

                 }
        }
</script>

<script type="text/javascript">
var palavra = "ATENÇÃO - AO CADASTRAR 2a ENTREGA DE TICKET INSIRA NO CAMPO C. DE BARRAS O NUMERO DO AWB SEGUIDO DE -2 EXEMPLO : 15644254-2";
var velocidade = 500;
var valor = 1;
function pisca() {
if (valor == 1) {
texto.innerHTML = palavra;
valor=0;
} else {
texto.innerHTML = "";
valor=1;
}
setTimeout("pisca();",velocidade);
}

/**
 * Funcao de inicializacao
 */
function init() {
	<?php
	    if ($loteimporta and (($predigitacao=="t" and $mostra_predigitacao and !$digitacao_completa) or $predigitacao!="t")) {
			//especialmente para a Amil
			if ($codcliente == 8) {
				echo "document.form_codigo.ativa_dest.checked                     = true;
				      document.form_codigo.id_destinatario_entrega_combo.disabled = false;
					  document.form_codigo.coddestinatario_entrega.disabled       = false;
					  document.form_codigo.procurar_dest.disabled                 = false;
					  document.form_codigo.coddestinatario_entrega.focus();";
			}
			else {
				echo "document.form_codigo.empresa_entrega.focus();";
			}
	    }
	?>
}
function resizeWin() {}
</script>

<?php
        if($loteimporta and (($predigitacao=="t" and $mostra_predigitacao and !$digitacao_completa) or $predigitacao!="t")){

                //if($codcliente==8)//especialmente para a Amil
                //echo "<body onload='javascript:document.form_codigo.ativa_dest.checked=true;document.form_codigo.id_destinatario_entrega_combo.disabled=false;document.form_codigo.coddestinatario_entrega.disabled=false;document.form_codigo.procurar_dest.disabled=false;document.form_codigo.coddestinatario_entrega.focus();'>";
                //else
                //{
				//echo "<body onload='javascript:document.form_codigo.empresa_entrega.focus();'>";
				
				//}

        }
        //echo "<body>";
		//echo "<div id='texto'></div>";
?>
 
<div class="box" style="width: 800px; margin: 0 auto;">
 
  <table cellspacing=1>
   <form name="form" action="<?=$selfLink?>" method="post">
         <input type=hidden name=opt value=B>
         <input type=hidden name="fast_dev_bco_abn_real" value="<?=$fast_dev_bco_abn_real?>">
         <input type=hidden name=idtransportadora value='<?=$_SESSION['IDTRANSP'];?>'>

      <tr align=center bgcolor=#DADADA>
         <td>Cliente</td>
         <td style="border-left: 1px solid #eee">Produto</td>
         <td style="border-left: 1px solid #eee">Tipo de Servi&ccedil;o</td>
         <?php if($predigitacao=="t"){ ?>
		       <td style="border-left: 1px solid #eee">Pr&eacute;-digita&ccedil;&atilde;o</td>
		 <?php } ?>
         <td style="border-left: 1px solid #eee">Lote</td>
         <td style="border-left: 1px solid #eee">N<sup>o</sup> Encomendas: <b><?=$total_encomendas.$mostra_predigitacao;?></b></td>
      </tr>
      <tr align=center bgcolor=#eeeeee>
         <td>
             <select name=codcliente onchange="document.location.href='<?=$PHP_SELF;?>?codcliente='+form.codcliente.value+'&fast_dev_bco_abn_real=<?=$fast_dev_bco_abn_real;?>'">
                <option value="-1">Selecione um cliente</option>
               <?php
//se for um cliente logado
if($_SESSION['IDCLIENTE'] && $_SESSION['IDCLIENTE']<>" " && $_SESSION['IDCLIENTE'] > 0) {
	$where_cliente = " and c.codcliente = '". $_SESSION['IDCLIENTE']."'";
}

if($fast_dev_bco_abn_real==1) {
	$where_cliente = " and (c.codcliente='175' or c.codcliente='15' or c.codcliente='6603' )";
}

$sql = "SELECT distinct(c.codcliente),c.nomecliente FROM tbclienteproduto clientes, tbconfigproduto config , tbcliente c
                                  WHERE
                                       config.idtransportadora = clientes.idtransportadora and
                                       config.codcliente = clientes.codcliente and
                                       config.codigoproduto = clientes.codigoproduto and
                                       config.digitamanual = 1  and c.codcliente = config.codcliente and
                                       c.idtransportadora = '".$_SESSION['IDTRANSP']."'
                                       $where_cliente
                                                                  ORDER BY c.nomecliente";
                                                                  
$qry->executa($sql);

for($i=0;$i<$qry->nrw;$i++){
        $qry->navega($i);

        echo "<option ".(($codcliente == $qry->data["codcliente"])?"selected":"")." value='".$qry->data["codcliente"]."'>".$qry->data["nomecliente"]."</option>";
}
               ?>
             </select>
         </td>
         <?php
        // echo $sql;die;                   
               if ($codcliente && $codcliente != "-1"){
                       echo "
         <td>
             <select name=codigoproduto>";

                       $sql = "SELECT * FROM tbconfigproduto cp, tbclienteproduto c, tbproduto p WHERE
                         c.codcliente = '$codcliente' and
                         p.codigoproduto = c.codigoproduto and
                         cp.codigoproduto = c.codigoproduto and
                         cp.codcliente = c.codcliente and
                         cp.idtransportadora = c.idtransportadora
                                        ORDER BY p.nomeproduto 
                         ";
                       $qry->executa($sql);

                       for($i=0;$i<$qry->nrw;$i++){
                               $qry->navega($i);

                               echo "<option ".(($codigoproduto == $qry->data["codigoproduto"])?"selected":"")."  value='".$qry->data["codigoproduto"]."'>".$qry->data["nomeproduto"]."</option>";
                       }
                       echo "
             </select>
         </td>    ";
               }
               else
               echo "<td></td>";
       ?>
         <td>
             <select name=idtipoentrega>
                <option value="">Selecione um tipo</option>
               <?php
       $sql = "SELECT * FROM tbtipoentrega WHERE bb=1";
       $qry->executa($sql);

       for($i=0;$i<$qry->nrw;$i++){
               $qry->navega($i);

               echo "<option ".(($idtipoentrega == $qry->data["idtipoentrega"])?"selected":"")." value='".$qry->data["idtipoentrega"]."'>".$qry->data["tipoentrega"]."</option>";
       }
               ?>
             </select>
         </td>

       <?php if($predigitacao=="t"){ ?>
               <td>
       Qtd: <input type="text" size="5" name="qtd_predigitacao" <?=$readonly_pre_lote;?> value="<?=$qtd_predigitacao;?>">
       </td>
       <?php } ?>
       <td>
        <input type=text size=5 name=loteimporta <?=$readonly_pre_lote;?> value='<?=$loteimporta;?>'>
        <input type=hidden name=lote value='<?=$loteimporta;?>'>
        <input type=hidden name=cliente value='<?=$codcliente;?>'>
       </td>
       <td>
         <input type='button' onclick="javascript:document.form.action='<?=$PHP_SELF;?>';document.form.target='_self';document.form.submit();" value="Carregar">
         <input type='button' onclick="javascript:document.form.action='ar_fast.php';
           document.form.target='__top';
           document.form.lote.value=document.form.loteimporta.value;
            document.form.cliente.value=document.form.codcliente.value;
           document.form.submit();" value="Imprimir">
       </td>
        </form>
      </tr>
     <?php

               if ($loteimporta and $idtipoentrega and (($predigitacao=="t" and $mostra_predigitacao and !$digitacao_completa) or $predigitacao!="t")){
       ?>
       <form action="<?=$selfLink?>" method="post" name="form_codigo">
       <input type=hidden name=opt value='G'>
       <input type=hidden name=idtransportadora value='<?=$_SESSION['IDTRANSP'];?>'>
       <input type=hidden name=codigoproduto value='<?=$codigoproduto;?>'>
       <input type=hidden name=codcliente value='<?=$codcliente;?>'>
       <input type=hidden name=loteimporta value='<?=$loteimporta;?>'>
       <input type=hidden name=opcao value='<?=$opcao;?>'>
       <input type=hidden name=idtipoentrega value='<?=$idtipoentrega;?>'>
       <input type=hidden name=predigitacao value='<?=$predigitacao;?>'>
       <input type=hidden name="fast_dev_bco_abn_real" value="<?=$fast_dev_bco_abn_real?>">

              <tr>
                  <td colspan=50>
                      <table border=0 width=100%>
       <?php
       $qry->nrw = 0;
       //verifica se vai gerar codigo de barras automatico ou manualmente
       $sql = "SELECT geracodigobarras,digitavalortr,digitavalortt,digitaenvelope,digitaultimo,digitacodforma,digitaobs,digitaempresa,digitaendereco,
       digitabairro,digitacidade,digitacep,digitauf,digitaregiao,digitacontato,digitavolumes,digitatelefone,digitacodigodestinatario,digitacodbarras,digitadataentrega
        FROM tbconfigproduto WHERE idtransportadora = '$idtransportadora' and codcliente = '$codcliente' and codigoproduto = '$codigoproduto'";
       $qry->executa($sql);
       if($qry->nrw and $qry->data["geracodigobarras"]<>1)
          $input_geracodigobarras = "<td>C. Barras:</td><td><input type='text' name='input_idexterno' value='$input_idexterno' size='40' maxlength='50'></td>";

       if(!$qtdvolumes)
       $qtdvolumes = 1;

       if($qry->data["digitacodigodestinatario"]!='f' or $qry->data["digitaempresa"]!='f' or $qry->data["digitaendereco"]!='f' or $qry->data["digitabairro"]!='f' or $qry->data["digitacidade"]!='f' or $qry->data["digitacep"]!='f' or $qry->data["digitauf"]!='f' or $qry->data["digitaregiao"]!='f'){
               echo "            <tr><td  colspan=6><hr></td></tr>";
               echo "           <tr><td><b>DESTINAT&Aacute;RIO</b></td>";
       }

       if($qry->data["digitacodigodestinatario"]!='f'){
               echo "              <td colspan=5 > C&oacute;digo <input type=text name=coddestinatario_entrega value='$coddestinatario_entrega' ".(($ativa_dest=="t")?"":"disabled")." size=10 maxlength=20 > <input type=button name='procurar_dest' ".(($ativa_dest=="t")?"":"disabled")." value='Procurar' onclick=\"javascript:document.form_codigo.opt.value='P';document.form_codigo.id_destinatario_entrega_combo.value='';document.form_codigo.submit();\">";
               //align=center
               echo "<input type='hidden' name='id_destinatario_entrega_combo' value='$id_destinatario_entrega_combo' ".(($ativa_dest=="t")?"":"disabled").">";
              
              
               echo "                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' name='ativa_dest' value='t' ".(($ativa_dest=="t")?"checked":"")." onclick='javascript:ativa_destiva_dest();'>Ativar
                                                                          &nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' name='somente_nome' value='t' ".(($somente_nome=="t")?"checked":"")." onclick='javascript:somente_nome_dest();'>Somente Nome
                           </td>";
       }else{
               echo "<input type='hidden' name='coddestinatario_entrega' value='$codigodaregiao_entrega'>";
               echo "<input type='hidden' name='id_destinatario_entrega_combo' value=''>";
               echo "<input type='hidden' name='ativa_dest' value=''>";
               echo "<input type='hidden' name='somente_nome' value=''>";
       }

       echo "           </tr>
                        <tr><td  colspan=6><hr></td></tr>
                      </table>

                      <table border=0>
                        <tr>";
       if($qry->data["digitaempresa"]!='f')
       echo "             <td>Empresa:</td><td colspan=3><input type='text' name='empresa_entrega' value='".strtoupper($empresa_entrega)."' size='50' maxlength='50'></td>";
       else
       echo "<input type='hidden' name='empresa_entrega' value='NA'>";

       if( $qry->data["digitacodbarras"]!='f')
       echo "             <td>C. Barras:</td><td colspan=3><input type='text' name='input_idexterno' value='$input_idexterno' size='40' maxlength='50'></td>";

       echo "           </tr>
                        <tr>";

       if($qry->data["digitaendereco"]!='f')
       echo "             <td>Endere&ccedil;o:</td><td colspan=3><input type='text' name='endereco_entrega' ".(($somente_nome=="t")?"readOnly":"")." value='".strtoupper($endereco_entrega)."' size='60' maxlength='70'></td>";
       else
       echo "<input type='hidden' name='endereco_entrega' value='NA'>";

       if($qry->data["digitabairro"]!='f')
       echo "             <td>Bairro:</td><td colspan=3><input type='text' name='bairro_entrega' ".(($somente_nome=="t")?"readOnly":"")." value='".strtoupper($bairro_entrega)."' size='40' maxlength='50'></td>";
       else
       echo "<input type='hidden' name='bairro_entrega' value='NA'>";

       echo "           </tr>
                        <tr>";

       if($qry->data["digitacidade"]!='f')
       echo "             <td>Cidade:</td><td><input type='text' name='cidade_entrega' ".(($somente_nome=="t")?"readOnly":"")." value='".strtoupper($cidade_entrega)."' size='40' maxlength='40'></td>";
       else
       echo "<input type='hidden' name='cidade_entrega' value='NA'>";

       if($qry->data["digitacep"]!='f')
       echo "             <td>Cep:</td><td><input type='text' name='cep_entrega' ".(($somente_nome=="t")?"readOnly":"")." value='".strtoupper($cep_entrega)."' size='8' maxlength='8'></td>";
       else
       echo "<input type='hidden' name='cep_entrega' value='99999999'>";

       if($qry->data["digitauf"]!='f')
       echo "             <td>UF:</td><td><input type='text' name='uf_entrega' ".(($somente_nome=="t")?"readOnly":"")." maxlength='2' value='".strtoupper($uf_entrega)."' size='2'></td>";
       else
       echo "<input type='hidden' name='uf_entrega' value='NA'>";

       if($qry->data["digitaregiao"]!='f')
       echo "             <td>Regi&atilde;o:</td><td><select name='codigodaregiao_entrega' ".(($somente_nome=="t")?"disabled":"")." ><option ".(($codigodaregiao_entrega==2)?"selected":"")." value=2>Urbano</option><option ".(($codigodaregiao_entrega==3)?"selected":"")." value=3>Grande Cidade</option><option ".(($codigodaregiao_entrega==4)?"selected":"")." value=4>Interior</option></select></td>";
       else
       echo "<input type='hidden' name='codigodaregiao_entrega' value='0'>";

       echo "           </tr>
                        <tr>";

       if($qry->data["digitacontato"]!='f')
       echo "             <td valign='top'>Contato:</td><td><input type='text' name='contato_entrega' ".(($somente_nome=="t")?"readOnly":"")." value='".strtoupper($contato_entrega)."' size='40' maxlength='40'></td>";
       else
       echo "<input type='hidden' name='contato_entrega' value='NA'>";

       if($qry->data["digitavolumes"]!='f')
       echo "             <td>Volumes:</td><td><input type='text' name='qtdvolumes' value='$qtdvolumes' size='4' maxlength='15'></td>";
       else
       echo "<input type='hidden' name='qtdvolumes' value='1'>";

       if($qry->data["digitatelefone"]!='f')
       echo "             <td>Telefone:</td><td colspan=3><input type='text' name='telefone_entrega' ".(($somente_nome=="t")?"readOnly":"")." value='$telefone_entrega' size='20' maxlength='30'></td>";
       else
       echo "<input type='hidden' name='telefone_entrega' value='NA'>";

       echo "           </tr>
                      </table>
                      <table border=0 width=100%>";

       if($qry->data["digitavalortr"]!='f' or $qry->data["digitavalortt"]!='f' or $qry->data["digitaenvelope"]!='f' or $qry->data["digitaultimo"]!='f' or $qry->data["digitacodforma"]!='f')
       echo "           <tr><td colspan=6 align=center><hr><b>VALORES</b><hr></td></tr>";

       echo "           <tr><td colspan=6>
                          <table border=0 align=center>
                            <tr>";

       if($qry->data["digitavalortr"]!='f')
       echo "                           <td align='right'>Valor TR:&nbsp;</td><td><input type='text' name='valor_tr' ".(($somente_nome=="t")?"readOnly":"")." value='".(($valor_tr)?$valor_tr:"0")."' size='10' onchange=\"javascript:somar_valores();\"></td>";
       else
       echo "<input type='hidden' name='valor_tr' value='0'>";

       if($qry->data["digitavalortt"]!='f')
       echo "                           <td align='right'>Valor TT:&nbsp;</td><td><input type='text' name='valor_tt' ".(($somente_nome=="t")?"readOnly":"")." value='".(($valor_tt)?$valor_tt:"0")."' size='10' onchange=\"javascript:somar_valores();\"></td>";
       else
       echo "<input type='hidden' name='valor_tt' value='0'>";

       if($qry->data["digitavalortr"]!='f' or $qry->data["digitavalortt"]!='f')
       echo "                 <td align='right'>Valor Total:&nbsp;</td><td><input type='text' name='valor_total' ".(($somente_nome=="t")?"readOnly":"")." value='".(($valor_total) ? $valor_total:"0")."' size='10' readOnly></td>";
       else
       echo "<input type='hidden' name='valor_total' value='0'>";

       if($qry->data["digitadataentrega"]!='f')
       echo "                           <td align='right'>Data Entrega:&nbsp;</td><td><input type='text' name='data_entrega' ".(($somente_nome=="t")?"readOnly":"")." value='".((!$data_entrega)? date("d/m/Y") : $data_entrega)."' size='11'></td>";
       else
       echo "<input type='hidden' name='data_entrega' value='".date("d/m/Y")."'>";

       echo "                         </tr>
                            <tr>";

       if($qry->data["digitaenvelope"]!='f')
       echo "                           <td align='right'>Envelope:&nbsp;</td><td><input type='text' name='envelope' ".(($somente_nome=="t")?"readOnly":"")." value='".(($envelope)?$envelope:"0")."' size='10' onchange=\"javascript:subtrair_envelopes();\"></td>";
       else
       echo "<input type='hidden' name='envelope' value='0'>";

       if($qry->data["digitaultimo"]!='f')
       echo "                           <td align='right'>&Uacute;ltimo:&nbsp;</td><td><input type='text' name='ultimo' ".(($somente_nome=="t")?"readOnly":"")." value='".(($ultimo)?$ultimo:"0")."' size='10' onchange=\"javascript:subtrair_envelopes();\"></td>";
       else
       echo "<input type='hidden' name='ultimo' value='0'>";

       if($qry->data["digitaenvelope"]!='f' or $qry->data["digitaultimo"]!='f')
       echo "                           <td align='right'>Total Envelopes:&nbsp;</td><td><input type='text' name='total_envelopes' ".(($somente_nome=="t")?"readOnly":"")." value='".((!$total_envelopes)?"1":$total_envelopes)."' size='10' readOnly></td>";
       else
       echo "<input type='hidden' name='total_envelopes' value='1'>";

       if($qry->data["digitacodforma"]!='f'){
               echo "                           <td align='right'>Forma de Pagto:&nbsp;</td><td><select name='codforma'>";
               combo("SELECT codforma,nomeforma FROM tbformapagto ORDER BY nomeforma",$codforma);
               echo "                           </select></td>";
       }else
       echo "<input type='hidden' name='codforma' value='0'>";

       echo "                         </tr>";
       if($qry->data["digitaobs"]!='f')
       echo "                         <tr><td valign='top' align='right'>Obs:&nbsp;</td><td colspan=7><textarea name='obs' ".(($somente_nome=="t")?"readOnly":"")." rows=3 cols=50>$obs</textarea></td></tr>";
       else
       echo "<input type='hidden' name='obs' value=''>";

       echo "             </table>

                        </td></tr>

                        <tr><td colspan=6 align=center><input type=submit value='Enviar' name='envia_form'></td></tr>
	                      </form>

                      </table>
                    </td>
                 </tr>";

               }
              
     ?>
  </table>
</div>

<?php
// pega o Footer
require_once("inc/footer.inc");