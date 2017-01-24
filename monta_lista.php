<?php
/**
 * Monta Lista
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega a configuracao
require_once("inc/config.inc");

//cria objeto de dados
$qry  = new consulta($con);
$qry1 = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry6 = new consulta($con);
$qry7 = new consulta($con);
$qry8 = new consulta($con);
$qry9 = new consulta($con);
$qryfunc  = new consulta($con);
$qry10 = new consulta($con);
$qry11 = new consulta($con);
$qry12 = new consulta($con);
$qry_seq = new consulta($con);
$qry_seq_up = new consulta($con); 

if ($_POST['enviar']){		
		$abrlst = $_POST["abrlst"];
		echo $abrlst;
		$codbarsAnterior = str_replace("%O", "_",$_POST["codbars"]);
        //critica do reabrir...
		
        if($op==2 or $op==18){
		if(!($abrlst > 0))
		$err[7] = 1;
		else{			
			$qry->nrw=0;
			$qry->executa("SELECT codigotipolista,codbase FROM tblista WHERE numlista='".$abrlst."'");
			if(!$qry->nrw)
			$err[7] = 1;
			else{
				$err[7] = 0;				
				$nlista = $abrlst;
				$cblista = $qry->data["codigotipolista"];//codigotipolista da lista
				$base = $qry->data["codbase"];//base da lista
				$qry->data["codigotipolista"]="";
				$qry->data["codbase"]="";
			}
		}
        }else
        $err[7] = 0;

        if ((isset($codbars) || $codbars <> " ") and $err[7]==0){
		//Detecta o ultimo movimento e dados da entrega
		//$codbars=strtoupper(trim($codbars));


    
    
    
    
     
	   
	   $codbars = trim($codbars);
     $codbars = str_replace("%O", "_",$codbars);


		$qry->nrw = 0;
		$qry->executa("Select pago,idtransportadora,codcliente,codigoproduto,
                   idinterno,codbase,idtipomovimento,numerosedex,
		       	       numlista,valorentrega, datacoletado from tbentrega where idexterno='".$codbars."'");
          
    if(($cblista==3)&&($contI == 100))
        {
    die("A lista de devolução de ar's não pode conter mais que 100 ar's");
        }


		if ($qry->nrw<=0){
			$err[1]=1; //encomenda não localizada
			$errors=$errors+1;       //incrementa Contator de Erro
		}else{
			$err[1]=0;
    	$id = $qry->data["idinterno"]; //Identificação de  idinterno
			$c_ticket = $qry->data["numerosedex"]; //Identificação de  idinterno
			$baseENT = $qry->data["codbase"];
			$idtipomovimento_atual = $qry->data["idtipomovimento"];
			$lista_atual = $qry->data["numlista"];
			$valorentrega = $qry->data["valorentrega"];
			$codcliente = $qry->data["codcliente"];
			$codigoproduto = $qry->data["codigoproduto"];
			$datacoletado = $qry->data["datacoletado"];
      if($codcliente==6670)
         $valorentrega=0;
      
			if($radio==1){
         //Encontrando o valor máximo
         if($cblista == 3)
             {
                //Se a lista for de devolução de AR's, o número máximos de AR's será de 100
                $valormaximo = 10000;
            }
         else
            {
         $qry2->executa("SELECT valormaximo
				 FROM tbconfigproduto
				 WHERE idtransportadora='".$qry->data["idtransportadora"]."' AND
				 codigoproduto='".$qry->data["codigoproduto"]."' AND
				 codcliente='".$qry->data["codcliente"]."'");
         $valormaximo = $qry2->data["valormaximo"];
            }
				//die(number_format($tot_valor_entrega+$valorentrega,2,".","")." > ".number_format($valormaximo,2,".",""));

				if($valormaximo > 0 and (number_format($tot_valor_entrega+$valorentrega,2,".","") > number_format($valormaximo,2,".",""))){
					$err[8]=1; //valor total ultrapassa o valor limite
					//$errors++;
				}//else{
				//        $err[8]=0;


				
			

          
          
          
     
				
									
				
				//Evita leitura por bases diferentes
				if($cblista != 10  AND  
           $cblista != 19  AND  
           $cblista != 21  AND  
           $cblista != 13  and
           $cblista != 9  and
           $idtipomovimento_atual <> 160 and
         
           $baseENT <> $_SESSION["IDBASE"])
            if($_SESSION["IDBASE"] != 1)
            {
				      $err[6] = 1;
            }
				else
				$err[6] = 0;

			

				
				 //vERIFICA SE A ENCOMENDA PODE SEGUIR PARA UMA BASE
				if($cblista == 2 and !empty($c_ticket)){
					$qry10->executa("select msg  from tb_cliente_ticket where cod_cliente= '$c_ticket'");
					if($qry10->nrw>0){
						$err[11] = 1; //indica erro de agencia
					}else{
						$err[11] = 0; //indica erro de agencia
					}
				}

				  $err[24]=0;
        	if($cblista==99){
          
            $sql_atende = "select tbenderecoentrega.cepentrega from tbenderecoentrega 
                           where tbenderecoentrega.idinterno = $id";
                           $qry12->executa($sql_atende);
                           if($qry12->nrw>0){   
                             $cep = $qry12->data["cepentrega"];            
          
                    			    $sql_atende = "select tbenderecoentrega.idinterno from tbenderecoentrega,tb_amil_cep_atende
                                            where  tb_amil_cep_atende.cep_de <= $cep and 
                                            tb_amil_cep_atende.cep_ate >= $cep 
                                            and tbenderecoentrega.idinterno = $id";
                                            $qry12->executa($sql_atende);
                                            
                                            if($qry12->nrw>0)
                                              {
                                              $sql_atende = "update tbentrega set idtipomovimento = 100 where idinterno = $id";
                                              $qry12->executa($sql_atende);
                                              $err[24]=1;
                                              $errors=$errors+1; 
                                              }
                        
                          }
                        }  
        
				
				
				
				
				
				
				$qry1->executa("Select idtipomovimento from tbtipolista where codigotipolista=$cblista");
				if ($qry1->nrw<=0){
					$err[2]=2; //Tipo de Lista não Localizado
				}else{ $err[2]=0; }

				//Evita duplicação
				$qry3->executa("Select idinterno from tbentrega where idinterno=$id and idtipomovimento=".$qry1->data["idtipomovimento"]);
				if ($qry3->nrw>0){
					$err[3]=4; //Movimento não permitido - TESTANDO em tbentrega
				}else{ $err[3]=0; }


        //Não permite montar lista antes de gerar arquivo da 
				$qry3->executa("Select pago,codcliente from tbentrega where idinterno=$id");
				
        if ($qry3->data["codcliente"]==6703 and $qry3->data["pago"]=='15'){
	      			$err[12]=1; //voltei o erro para zero até implentar
				}else{ $err[12]=0; 
        
        }



				if (($qry->data["codcliente"] == 200) && ($qry->data["pago"] == 2) && ($qry->data["codigoproduto"] != 4)  ){
					$err[10]=1;              // Arquivo de retorno de cliente não gerado
					$errors=$errors+1;       //incrementa Contator de Erro
				}else{
					$err[10]=0;
				}


				/*
				//Evita duplicação
				if($abrlst > 0){
				//$qry3->executa("Select idinterno,numlista from tbmovimento where idinterno=$id and numlista=$abrlst");
				$nlista = $abrlst;
				}
				*/


				//Se nenhum dos campos do vetor for ocupado por valor - executa ação!
				if ($err[1]==0 && $err[2]==0 && $err[3]==0 && $err[5]==0 && $err[6]==0  && $err[10]==0  && $err[11]==0 && $err[12]==0  && $err[24]==0 ){

					//inicio - criando numero de lista
					if(!$nlista){

						if($cblista!=2 and $cblista!=5 and $cblista!=18)
						$base = $_SESSION["IDBASE"];

						//INSERT tblista
						//$sql="";
						$sql ="insert into"." tblista ";
						$sql.= "(";
						$sql.="codigotipolista".",";      //01
						$sql.="datalista".",";            //02
						$sql.="CodBaseOrigem,";           //03
						$sql.="CodBase,";                                  //04
						$sql.="codcliente,";                          //05
						$sql.="codigoproduto,";                          //06
						$sql.="graudificuldade,";                  //07
						$sql.="codloginmontagem";                  //08

						$sql.= ")";
						$sql.= " values ";
						$sql.= "(";

						$sql.=$cblista.",";                //01
						$sql.="'".date('Y/m/d')."'".",";   //02
						$sql.=$_SESSION['IDBASE'].",";     //03
						$sql.=$base.",";                                   //04
						$sql.=$codcliente.",";                           //05
						$sql.=$codigoproduto.",";                   //06
						$sql.=intval($graudificuldade).",";//07
						$sql.=$_SESSION["IDUSER"];                   //08

						$sql.= ")";

						//Executa a query
						$qry->executa($sql);
						if(!$qry->res)
						die("Erro ao criar a lista");

						//captura ultima lista criada
						$qry->executa("select last_value from tblista_numlista_seq");
						$nlista=$qry->data["last_value"];
						$criou_lista_agora=1;
					}

					if($nlista){

						//fim - criando numero de lista
						//antigo le da tbmovimento se $qry3->executa("Select idinterno,numlista from tbmovimento where idinterno=$id and numlista=$nlista");
						$qry3->executa("Select idinterno,numlista from tbentrega where idinterno=$id and numlista=$nlista");

						if ($qry3->nrw>0){
							$err[4]=5;
							$jml=$qry3->data[1]; //já existe na lista X (jml = já montado em lista)
						}else{
							$err[4]=0;
						}

						if($err[4]==0){






							$qry3->nrw = 0;
							$qry3->data["codcliente"] = "";
							$qry3->data["codigoproduto"] = "";
							$qry3->data["codigotipolista"] = "";
							$qry3->executa("SELECT codcliente,codigoproduto,codigotipolista
					    FROM tblista
					    WHERE numlista='".$nlista."'");
						
						if($cblista == 19)
							{
								$sql_seq="select idgrau from tbentrega  
									   WHERE numlista='".$nlista."' order by 
									   idgrau desc limit 1";
									   $qry_seq->executa($sql_seq);
									   $pseq = $qry_seq->data["idgrau"];
							   // echo $sql_seq."<br>";
								//echo "Anterior Seq: ".$qry_seq->data["idgrau"]."<br>";
								   
								   
								   
									if(empty($pseq) or $pseq == 0){
									echo $pseq;
										$pseq=1;
										//echo "a";
									} else {
										$pseq = $pseq+1;
									}
									// echo "Nova Seq: ".$pseq;
									$sql_seq_up= "update tbentrega set idgrau = ".$pseq." WHERE idexterno='".$codbars."'";   
								   $qry_seq_up->executa($sql_seq_up);
							
							}
							
								      //and $qry3->data["codcliente"]==$codcliente
							if(($qry3->data["codigotipolista"]==10
			       and $qry3->data["codigoproduto"]==$codigoproduto) or $qry3->data["codigotipolista"]!=10){
								if($cblista != 13 or  $cdlista !=33)
									$err[0]=inseremovimento($id,$idtipomovimento_atual,$qry1->data["idtipomovimento"],$nlista,$base, $courier,$motivo,1);
                  $sql2="";
				  
              	if ($cblista==1 and $codcliente = 254) {
								 
                 
                 $destinatario = "rsouza@fastcourier.com.br";
                 $assunto = "Seu pedido Netshoes já está a caminho – Confira o dia e horário agendados para a sua entrega.";
                 $c1 = "Olá, ".$nome_cliente;
                 $c2 = "A entrega do seu pedido Nº XXXXXXX realizado na Netshoes está agendada para o dia: DD/MM/AAAA, entre 00h e 00h.";
                 $c3 = "É necessário que alguém esteja no local para recebê-lo. Se por algum motivo você precisar reagendar, por favor, entre em contato com a gente: (XX) XXXX-XXXX." ;
                 $c4 = "Mais algumas orientações que você precisa saber:
 
Ø                Se a transportadora não tiver sucesso na entrega de seu produto na data e período que você escolheu, dependendo do local de entrega e tipo de produto, a transportadora ainda poderá tentar realizar a entrega por até outras 2 (duas) vezes em dias e horários diferentes do agendado. Após estas 2 novas tentativas, a mercadoria será devolvida ao Centro de Distribuição Netshoes. Neste caso, você poderá solicitar uma nova tentativa, mas será cobrado um novo valor de frete.
 
Ø  A entrega pode ser recebida por parentes e porteiros, desde que você tenha autorizado previamente. É necessário assinar o comprovante de entrega e apresentar um documento.
 
Ø  Nossas transportadoras não estão autorizadas a abrir embalagens ou a realizar a entrega por meios 'alternativos', tais como: cordas, janelas e telhados. Não podemos colocar pessoas ou o seu produto em risco.
 
Ø  Recuse a entrega se a embalagem não estiver em condições adequadas ou apresentar sinais de violação.
 
Ø  Se a entrega não ocorrer no prazo combinado ou tiver algum problema, fale com a Netshoes para que possamos atendê-lo cada vez melhor.
 
 
Obrigado por comprar na Netshoes.
 
www.netshoes.com.br
 "
 
 
 ; 
// mail($destinatario, 'My Subject', $c4);                 
                  
                  
                    
									}
                
								
								

								$sql2="";
								if ($cblista==9 or cblista==3) {
									  $sql2.="update tbentrega set st = 'E' WHERE idexterno='$codbars'";
									  $qry9->executa($sql2);
									}

							  $sql2="";
              	if ($cblista==1 or $cblista==2 or $cblista==20 and $codcliente = 6703) {
								  	$sql2.="update tbentrega set pago='05' WHERE idexterno='$codbars'";
									  $qry9->executa($sql2);
									  
									}

              
                $sql2="";
                if ($cblista==21 and $codcliente = 6703) {
								  	$sql2.="update tbentrega set pago='07' WHERE idexterno='$codbars'";
									  $qry9->executa($sql2);
									  
									}
              
                  $sql2="";
              		if ($cblista==15) {
									$sql2.="update tbentrega set idmotivo = 26 WHERE idexterno='$codbars'";
									$qry9->executa($sql2);
									}
								  
                  $sql2="";
                	if ($cblista==13) {
									$sql2.="update tbentrega set listafatura = $nlista WHERE idexterno='$codbars'";
									$qry9->executa($sql2);
									}
								
                  $sql2="";
                	if ($cblista==12 and !$datacoletado) {
									$sql2.="update tbentrega set datacoletadobase = '".date('Y-m-d')."' WHERE idexterno='$codbars'";
									$qry9->executa($sql2);
									}

								  $sql2="";
                  if (!$datacoletado) {
									$sql2.="update tbentrega set datacoletado = '".date('Y-m-d')."' WHERE idexterno='$codbars'";
									$qry9->executa($sql2);
									}
									
                  
                  $sql2="";
                	if ($cblista==24) {
                  
                  
                  
									$sql2.="update tbentrega set st = 'E', dataentrega = '".date('Y-m-d')."' WHERE idexterno='$codbars'";
									$qry9->executa($sql2);
									}

									



								if ($err[0]==0){
									//incrementa Contator de Incluidos
									$contI=$contI+1;


									//incrementa soma de valor das entregas
									$tot_valor_entrega = $tot_valor_entrega + $valorentrega;

									//updateentrega($id,$qry1->data["idtipomovimento"],$nlista);

									//if($cblista==2){// or $cblista==8){

									//$sql = "";
									//$sql = "update tbentrega set numlista='$nlista', codbase = $base where idinterno = $id";
									// echo $qry;
									//$qry5->executa($sql);
									//}
								}

								//Abre janela de entrada da identificação de PGTO para COD
								$sql = "select tbentrega.idinterno,tbdetalhepagto.idinterno,tbformapagto.codforma, tbformapagto.complemento, tbformapagto.nomeforma";
								$sql .=" from tbentrega " ;
								$sql .=" inner join tbdetalhepagto on tbdetalhepagto.idinterno = tbentrega.idinterno";
								$sql .=" inner join tbformapagto on tbformapagto.codforma = tbdetalhepagto.codforma";
								$sql .=" where tbentrega.idexterno = '$codbars'";
								$qry6->executa($sql);
								//ABRE JANELA PARA LEITURA DE CMC7
								if($cblista==8 and $qry6->data["complemento"]==9999){//BEGIN

								//carrega dados da página monta tela
								if(isset($op))
								$str.="&op=$op";
								if(isset($base))
								$str.="&base=$base";
								if(isset($contI))
								$str.="&contI=$contI";
								if(isset($contE))
								$str.="&contE=$contE";
								if(isset($errors)){
									$str.="&errors=$errors";
									for($i=0;$i<4;$i++){
										$str.="&err[$i]=$err[$i]";
									}
								}
								$str.="&cblista=$cblista&nlista=$nlista";
								header("location: confere_cod_valor.php?codbars=$codbars".$str);
								}//END
							}else{
								$err[9]=1; //ALTEREI NI DIA 17/11 PARA ANA FAZER AS LISTAS DA SABANCO, QD TERMINAR VOLTAR O FLAG PARA 1 
								$errors=$errors+1;       //incrementa Contator de Erro
							}

						}
					}
				}else{
					$errors=$errors+1;       //incrementa Contator de Erro



				}

			}else if($radio==2){

				//Begin - Insere aviso de exclusão da lista código de exclusao da lista ainda NAO implementado!!!!!!!!!!!!!!!!!!!!!
				//$err[0]=inseremovimento($id,$qry->data["idtipomovimento"],'100',$nlista,$base,$courier, $motivo);
				//$err[0]=inseremovimento($id,$idtipomovimento_atual,'180',0,$base,$courier, $motivo, "Excluída da lista $lista_atual");
				$err[0]=inseremovimento($id,$idtipomovimento_atual,'170',0,$_SESSION["IDBASE"],$courier, $motivo);


        	if ($cblista==1 or $cblista==2 and $codcliente = 6703) {
								  	$sql2.="update tbentrega set pago='05' WHERE idexterno='$codbars'";
									  $qry9->executa($sql2);
							}



				//if (($qry->data["codcliente"] == 200) && ($qry->data["pago"] == 1) && ($qry->data["codigoproduto"] != 4) && ($cblista==12)   ){
				//$sql.= " update tbentrega set pago = 2 WHERE idexterno='$codbars'";
				//$qryfunc->executa($sql);
										    //echo $sql;
				//}


				if ($err[0]==0){
					$contE=$contE+1; //incrementa Contator de exclidos
					$tot_valor_entrega = $tot_valor_entrega - $valorentrega; 
				}
				//End
			}

			//}
		}
        }
        if (($err[1]!=0 or $err[2]!=0 or $err[3]!=0 or $err[4]!=0 or $err[5]!=0 or $err[6]!=0) && $criou_lista_agora==1 && $nlista > 0){
		//remove a lista se deu algum erro.
		$sql = "DELETE FROM tblista WHERE numlista='$nlista'";
		$qry->executa($sql);
        }
}

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>

<script>
function init() { inicio(document.form_monta_lista); }

function inicio(form){
	form.codbars.disabled = false;
	form.codbars.focus();
}

function Validar(form)
{
        if (form.codbars.value=="")
        {
        window.alert("O código de barras deve ser informado");
        form.codbars.focus();
        return false;
        }
    if (form.Radio[0].checked==false && form.Radio[1].checked==false)
        {
        window.alert("Uma opção deve ser escolhida");
        return false;
        }
    
}

function seleciona_graudificuldade(selecionado){
        document.form_monta_lista.codbars.disabled = false;
        document.form_monta_lista.codbars.focus();
}

</script>


<div class="box" style="width: 850px; margin: 0 auto;">
<form name="form_monta_lista" method="post" action="<?=$selfLink?>">
<?php
if($base <= 0 or !$base)
$base = $_SESSION["IDBASE"];

if(isset($op))
echo "<input type='hidden' name='op' value='$op'>";
if(isset($base))
echo "<input type='hidden' name='base' value='$base'>";
if(isset($contI))
echo "<input type='hidden' name='contI' value='$contI'>";
if(isset($contE))
echo "<input type='hidden' name='contE' value='$contE'>";
if(isset($tot_valor_entrega))
echo "<input type='hidden' name='tot_valor_entrega' value='$tot_valor_entrega'>";
if(isset($errors))
echo "<input type='hidden' name='errors' value='$errors'>";
if(isset($codagencia))
echo "<input type='hidden' name='codagencia' value='$codagencia'>";
if(isset($nomagencia))
echo "<input type='hidden' name='nomagencia' value='$nomagencia'>";
if(isset($nlista))
echo "<input type='hidden' name='nlista' value='$nlista'>";

if(isset($err)){
        for($i=0;$i<5;$i++)
        echo "<input type='hidden' name='err[$i]' value='".$err[$i]."'>";
}

echo "<input type='hidden' name='cblista' value='$cblista'>";

?>
    <table style="width:800px">
      <tr> 
        <td colspan=6 bgcolor="#DDD"> <div align="center"> <font size="6"><b><font size="3"><strong>..:: 
	    </strong></font></b><strong><font color="#990000" size="3">Montagem 
	    da Lista</font></strong></font><strong><font size="3"><b> ::..</b></font></strong><font size="3"><b></b></font></div></td>
      </tr>
      <tr> 
        <td colspan="3"> 
	  <?php
if (isset($cblista)&&($cblista!=0)||($cblista!="")){
        $qry1->executa("SELECT nometipolista,idtipomovimento,idtipoexpede FROM tbtipolista WHERE codigotipolista=$cblista");
        echo "Montando Lista do Tipo: ";
        echo "<font color=#990000>".$qry1->data["nometipolista"]."</font>";
}else{
        echo "<font color=#990000>Tipo de Lista não identificado.</font>";
}

if ($base>0)
{
	$qryBase = new consulta($con);
	$qryBase->executa("Select nomeBase from tbBase where codBase=$base");
	
	$nomebase = "Base não identificada.";
	if ($qryBase->nrw)
	{
		$nomebase = $qryBase->data["nomebase"];
	}	
	
	echo "<br>Base selecionada: <font color=#990000> $nomebase </font>";
	
}
	 ?></br>
	  </div> <div align="left"></div></td>
        <td colspan="2"><div align="center"><font color=#990000> </font> </div>
	  <div align="center"> 
	    <?php
	 if($op==2 || $op==18){

		 echo "Lista : ";
		 //echo "<select name='abrlst'>";
		 echo "<input type='text' name='abrlst' value='$abrlst'>";
		 /*if($base > 0)
		 
		 // (*) acrescentado " and codigotipolista=$cblista"
		 combo("SELECT tblista.numlista,tblista.numlista FROM tblista 
		 
		 WHERE  tblista.codbase=$base and codigotipolista=$cblista			
				 		 
		 ORDER BY tblista.numlista",$abrlst); */
		 
		 
		 
		 /*
		 
		 combo("SELECT tblista.numlista,tblista.numlista FROM tblista 
		 
		 WHERE tblista.codigotipolista=$cblista AND tblista.codbase=$base 
		 
		 AND (SELECT count(idinterno) FROM tbentrega WHERE numlista=tblista.numlista 
		 
		 and (idtipomovimento=".$qry1->data["idtipomovimento"]." 
		 
		 OR idtipomovimento=".$qry1->data["idtipoexpede"]."))= tblista.quantidadetotal 
		 
		 ORDER BY tblista.numlista",$abrlst); 
		 */
		 
		 //else
		//  combo("SELECT tblista.numlista,tblista.numlista FROM tblista WHERE tblista.codigotipolista=$cblista AND tblista.codbase=".$_SESSION["IDBASE"]." AND (SELECT count(idinterno) FROM tbentrega WHERE numlista=tblista.numlista and (idtipomovimento=".$qry1->data["idtipomovimento"]." OR idtipomovimento=".$qry1->data["idtipoexpede"]."))= tblista.quantidadetotal ORDER BY tblista.numlista",$abrlst);//combo("SELECT numlista,numlista FROM tblista WHERE codigotipolista=$cblista AND codbaseorigem=".$_SESSION["IDBASE"]." ORDER BY numlista",$abrlst);

		 echo "</select>";
		 //echo " <input type='text' name='abrlst'>";
	 }
		  ?>
	  </div></td>
        <td width="20%"> <center>
	    <?
		  if($op==1){
			  echo "N&uacute;mero da Lista:<br>
		  <font color=#990000><b>$nlista</b></br></font>";
		  }
		  ?>
	    <?
		  if($op=="" || $op==0 || !isset($op)){
			  echo "<font color=#990000>Lista não identifica.</font>";
			  $op=0;
		  }
		  ?>
	  </center></td>
      </tr>
      <?
		  if($cblista==2 || $op==18){
			  //Cria Linha na tabela para inserção de uma combo para seleção de base
			  echo "<tr bgcolor='#FFFFFF'>
		<td colspan='3'>
			    Montando Saca para a Base :";
			  $qrypos = new consulta($con);
			  $qrypos->executa("select codbase,nomebase from tbbase where codbase=$base order by nomebase");
			  echo "<font color=#990000> ".$qrypos->data[1]."</font>
		</td>
		</tr>";
		  }else{
			  if($cblista == 4){
				  if(isset($codagencia) && isset($nomagencia)){
					  //Cria Linha na tabela para inserção de uma combo para seleção de base
					  echo "<tr bgcolor='#FFFFFF'>
				<td colspan='3'>
						 Montando Lista para Agencia :
					<font color=#990000> $codagencia - $nomagencia</font>
				    </td>
				    </tr>";
				  }
			  }
		  }
	  ?>
      <? if($op==1 and !$nlista)
			
      ?>
      <tr bgcolor="#DDD"> 
        <td colspan="2" bgcolor="#DDD"> <div align="left"><font color="#990000"> 
	    <font color="#000000"><strong>..::</strong></font> Op&ccedil;&otilde;es<font color="#000000"> 
	    <strong>::..</strong> </font></font></div></td>
        <td colspan="4"> <div align="center"><font color="#000000"><strong>..::</strong> 
	    </font><font color="#990000">Informe o pr&oacute;ximo c&oacute;digo 
	    de barras<font color="#000000"> <strong>::..</strong></font></font></div></td>
      </tr>
      <tr> 
        <td width="3%"> <div align="center"> 
	    <input name="radio" type="radio" value="1" onClick="inicio(document.form_monta_lista)" <?=(($radio=="1" or !$radio)?"checked":"")?>>
	  </div>
	  <div align="center"> </div></td>
        <td width="20%">Incluir na Lista</td>
        <td colspan="4"><div align="center">
			<table>
				<tr>
					<td>
						C&oacute;digo de barras anterior
					</td>
					<td>
						C&oacute;digo de barras a ser lido
					</td>
				</tr>
				<tr>
					<td>
						<input name="codbarsAnterior" type="text" size="35" value='<?=$codbarsAnterior?>' "disabled">	
					</td>
					<td>
						<input name="codbars" type="text" size="35" <?=(($op==1 and !$nlista and !$graudificuldade)? "disabled":"")?>>
			          	<input type="hidden" name="enviar" value="enviar">
		  				<input type="submit" value="Enviar">
					</td>
				</tr>
			</table>		  
		  
	  </div></td>
      </tr>
      <tr> 
        <td> <div align="center"><font color=#990000> 
	    <input type="radio" name="radio" value="2" onClick="inicio(document.form_monta_lista)" <?=(($radio=="2")?"checked":"")?>>
	    </font></div></td>
        <td><font color=#990000>Excluir da Lista</font></td>
        <td width="23%"> <div align="right"> Inseridos :</div></td>
        <td width="100" height="100%">  
	    <?php //imprimir a  quantidade de registros VALIDOS lidos
	  //echo  $contI;         <div  align=center style='width:100%;height:100%;'><strong>     </strong></div>
	//  echo "<font face=Rockwell Extra Bold size=300pt>".$contI."</font>";
  
  echo "<span style='display:inline-block;font-size:120pt;height:67%;width:100%;'><font face='Rockwell Extra '><strong>".$contI."</strong></font></span>";
	  
	  ?>
	 </td>
        <td colspan="2"> 
	  <?php //imprimir Ultimo nome de destinatario

	  ?>
        </td>
      </tr>
      <tr> 
        <td height="21"></td>
        <td height="21"></td>
        <td><div align="right"> Valor :</div></td>
        <td><div align="center"> 
	    <?php //imprimir a soma dos valores da encomenda
	  if(isset($tot_valor_entrega))echo number_format($tot_valor_entrega,2,",",".");
	    ?>
	  </div></td>
        <td colspan="2"> 
	  <?php /* imprimir Ultimo nome de destinatario */?>
        </td>
      </tr>
      <tr> 
        <td height="21"></td>
        <td height="21"></td>
        <td><div align="right"> Exclu&iacute;dos :</div></td>
        <td> <div align="center"> 
	    <? //imprimir a  quantidade de registros VALIDOS lidos
	    echo $contE;
	    ?>
	  </div></td>
        <td colspan="2"> 
	  <?php /* imprimir Ultimo nome de destinatario */?>
        </td>
      </tr>
      <tr> 
        <td colspan="2"> <center>
	  </center></td>
        <td> <div align="right">Erros : </div></td>
        <td><center>
	    <?php //imprimir a  quantidade de registros VALIDOS lidos
		echo $errors;
		?>
	  </center></td>
        <td colspan="2"> 
        
        
        
	  <?php
		//imprimir Ultimo nome de destinatario
    
		if ($err[0]>0){
			 if ($idtipomovimento_atual == "601"){
				 $sql = "SELECT idmovimento FROM tbmovimento WHERE idtipomovimento = '$idtipomovimento_atual' and idinterno = '$id' ORDER BY idmovimento DESC LIMIT 1" ;
				 $qry->executa($sql);

				 $sql = "SELECT obs FROM tbobsbloqueio WHERE idinterno = '$id' and idmovimento = '".$qry->data["idmovimento"]."'";
				 $qry->executa($sql);

				 $obs = "<BR>".$qry->data["obs"];
			 }

			 echo "<p>".$codbars.": <font color=#990000>Movimento não permitido.$obs / $idtipomovimento_atual-".$qry1->data["idtipomovimento"]."</font></p>";
		}
		if ($err[1]>0)
		echo "<p>".$codbars.": <font color=#990000>Encomenda não localizada.</font></p>";
		if ($err[2]>0)
		echo "<p>".$codbars.": <font color=#990000>Tipo de Lista não Localizado.</font></p>";
		if ($err[3]>0)
		echo "<p>".$codbars.": <font color=#990000>Encomenda já se encontra neste tipo de movimento.</font></p>";
		if ($err[4]>0)
		echo "<p>".$codbars.": <font color=#990000>Encomenda já montada na lista $jml.</font></p>";
		if ($err[5]>0)
		echo "<p>".$codbars.": <font color=#990000>Agencia Invalida!</font></p>";
		if ($err[6]>0)
		echo "<p>".$codbars.": <font color=#990000>Encomenda não pertence a sua base.</font></p>";
		if ($err[7]>0)
		echo "<p><font color=#990000>Por favor selecione uma lista válida para a reabertura.</font></p>";
		
		if ($err[8]>0)
		echo "<p><font color=#990000>Total dos valores da entrega (R$".number_format($tot_valor_entrega+$valorentrega,2,",",".").") ultrapassa o valor máximo (R$".number_format($valormaximo,2,",",".").") da lista</font></p>";
		
		if ($err[11]>0)
		echo "<p>".$codbars.": <font color=#990000>ENCOMENDA NAO PODE SERGUIR PARA BASE, UTILIZAR RECURSOS PROPRIOS...!!!</font></p>";
	

  	if ($err[12]>0)
		echo "<p>".$codbars.": <font color=#990000>ANTES DE COLOCAR EM ROTA VOCE DEVE GERAR O ARQUIVO</font></p>";
	
  
  if ($err[24]>0)
		echo "<p>".$codbars.": <font color=#990000>ENCOMENDO NÃO PODE SER ENTREGUE VIA CORREIOS, FAVOR ENVIAR PARA BASE DA FAST</font></p>";
	
  
  



		if ($err[9]>0)
		echo "<p>".$codbars.": <font color=#990000>Encomenda não pertence ao cliente original da lista.</font></p>";
			if ($err[10]=0)
		echo "<p>".$codbars.": <font color=#990000>O Arquivo de Retorno do cliente não foi gerado, operação cancelada</font></p>";

	  ?>
        </td>
      </tr>
      
      
      <tr> 
        <td colspan="6"> <div align="right"></div>
	  <div align="right"> </div></td>
      </tr>
      <tr> 
        <td colspan="6"><hr></td>
      </tr>
      <tr> 
        <td colspan="6"><div align="center"> </div></td>
      </tr>
      <tr> 
        <td colspan="6" align="center" > <font size="4"> 
        
	  <?php  {
		if(isset($tot_valor_entrega))
		{
			echo "<a target='blank_' href=print_lista.php";
			if($cblista){
				echo"?cblista=$cblista";
			} if($nlista){
				echo"&lista=$nlista";
			} if($op){
				echo"&op=7";
			}
			echo"&inf=2";
			echo">Imprimir Lista</a> | ";
			
			echo "<a href=expedir_lista_direto.php?enviar=enviar";
			if($cblista){
			  echo"&cblista=$cblista";
			} if($nlista){
			  echo"&lista=$nlista";
			} if($op){
			  echo"&op=7";
			}
			echo"&inf=2";
			echo">Expedir Lista</a>";
		}
			
      
	  }
		?>
		
		
	  <div align="center"></div></td>
      </tr>
      <tr>
        <td colspan="6"><div align="center"><a href="selecao_lista.php"><< Anterior</a> 
	    <a href="selecao_lista.php"></a></div></td>
      </tr>
      <tr> 
        <td colspan="6"><div align="center"><font size=10><?$errors?></font></div></td>
      </tr>
    </table>
  </form>
</div>

<?php
// pega o Footer
require_once("inc/footer.inc");