<?php
/**
 * Monta Lista Demillus
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

if($cblista!=2 && $cblista!=5 && $cblista!=18) { $base = $_SESSION["IDBASE"]; }

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
$qry13 = new consulta($con);


if ($_POST['enviar']){		
		$abrlst = $_POST["abrlst"];
		echo $abrlst;
		$codbarsAnterior = $_POST["codbars"];
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
 		$qry->nrw = 0;
		$qry->executa("Select pago,idtransportadora,codcliente,codigoproduto,
                   idinterno,codbase,idtipomovimento,numerosedex,
		       	       numlista, datacoletado from tbentrega where idexterno='".$codbars."'");
        if($op==1 and $cblista = 28){
			if ($qry->nrw>0){
				$dateEmiQ = new DateTime($qry->data['dataemissao']);
				$campanhaFormat = ((int)$campanhaInput)."/".$dateEmiQ->format('Y');
				$qry13->executa("Select pago,idtransportadora,codcliente,codigoproduto,
		                   idinterno,codbase,idtipomovimento,numerosedex,
				       	       numlista, datacoletado from tbentrega where idexterno='".$codbars."' and numlotecliente = '".str_pad($setorInput, 4, "0", STR_PAD_LEFT)."' and numloteinterno = '".$campanhaFormat."'");	
				var_dump("Select pago,idtransportadora,codcliente,codigoproduto,
		                   idinterno,codbase,idtipomovimento,numerosedex,
				       	       numlista, datacoletado from tbentrega where idexterno='".$codbars."' and numlotecliente = '".str_pad($setorInput, 4, "0", STR_PAD_LEFT)."' and numloteinterno = '".$campanhaFormat."'");			
				if($qry13->nrw <= 0){
					$err[9]=1; //encomenda nao localizada
					$errors=$errors+1;       //incrementa Contator de Erro
				}else{
					$numlistaTot = $qry13->data['numlista'];
					$qry13->executa("Select COUNT(idinterno) as total from tbentrega where numlista = '".$numlistaTot."'");	
					$notasFaltando = $qry13->data['total'];
				}			
			}      	
        }

   

		if ($qry->nrw<=0){
			$err[1]=1; //encomenda nao localizada
			$errors=$errors+1;       //incrementa Contator de Erro
		}else{
			$err[1]=0;
    	$id = $qry->data["idinterno"]; //Identificacao de  idinterno
			$c_ticket = $qry->data["numerosedex"]; //Identifica��o de  idinterno
			$baseENT = $qry->data["codbase"];
			$idtipomovimento_atual = $qry->data["idtipomovimento"];
			$lista_atual = $qry->data["numlista"];
		
			$codcliente = $qry->data["codcliente"];
			$codigoproduto = $qry->data["codigoproduto"];
			$datacoletado = $qry->data["datacoletado"];
     
      
			if($radio==1){
         //Encontrando o valor m�ximo
         if($cblista == 3)
             {
                //Se a lista for de devolu��o de AR's, o n�mero m�ximos de AR's ser� de 100
                $valormaximo = 100;
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

			
				//Evita leitura por bases diferentes
				if($cblista != 10  AND  
           $cblista != 19  AND  
           $cblista != 21  AND  
           $cblista != 27  and
           $cblista != 29  and
           $cblista != 28  and
           $cblista != 30   and
           
           $baseENT <> $_SESSION["IDBASE"])
				   $err[6] = 1;
				else
				$err[6] = 0;

        if($cblista == 28)
			      $base=$baseENT;
            
         if($cblista == 29)
			      $base=$baseENT;    
       
        if($cblista == 30)
			      $base=$baseENT;     
				
				

				
	
				//SE EXISTE FOTO PARA NOVA RA
				
				if ($idtipomovimento_atual==889)
				{
					$err[8]=1;
					$errors=$errors+1;     
					// COLOCAR A IMPRESSÃO DO MAPA
				 }

					
	
	
	
				//Detecta o ID do movimentos
				//echo "<font color=red>".$cblista."</font>";
				$qry1->executa("Select idtipomovimento from tbtipolista where codigotipolista=$cblista");
				if ($qry1->nrw<=0){
					$err[2]=2; //Tipo de Lista n�o Localizado
				}else{ $err[2]=0; }

				
				
				
				
				
				//Evita duplica��o
				$qry3->executa("Select idinterno from tbentrega where idinterno=$id and idtipomovimento=".$qry1->data["idtipomovimento"]);
				if ($qry3->nrw>0){
					$err[3]=4; //Movimento n�o permitido - TESTANDO em tbentrega
				}else{ $err[3]=0; }


        //N�o permite montar lista antes de gerar arquivo da 
				$qry3->executa("Select pago,codcliente from tbentrega where idinterno=$id");
				
        if ($qry3->data["codcliente"]==6703 and $qry3->data["pago"]=='15'){
	      			$err[12]=1; //voltei o erro para zero at� implentar
				}else{ $err[12]=0; 
        
        }



				if (($qry->data["codcliente"] == 200) && ($qry->data["pago"] == 2) && ($qry->data["codigoproduto"] != 4)  ){
					$err[10]=1;              // Arquivo de retorno de cliente n�o gerado
					$errors=$errors+1;       //incrementa Contator de Erro
				}else{
					$err[10]=0;
				}


        //VErifica se a econcomenda foi bloqueada, se sim dia
		/*
								$sql = "select * from tb_bloqueadas ";
								$sql .=" where tb_bloqueadas.idexterno = '$codbars'";
								$qry_bloqueio->executa($sql);
                if ($qry_bloqueio->nrw>0){
                echo "Encomenda :  ".$qry_bloqueio->data["idexterno"].'  >>>>'.$qry_bloqueio->data["msg"];
                die;
                }
				*/
                
        







				//Se nenhum dos campos do vetor for ocupado por valor - executa a��o!
				if ($err[1]==0 && $err[2]==0 && $err[3]==0 && $err[5]==0 && $err[6]==0  && $err[10]==0  && $err[11]==0 && $err[12]==0 ){

					//inicio - criando numero de lista
					if(!$nlista){

						if($cblista!=2 and $cblista!=5 and $cblista!=18)
						$base = $_SESSION["IDBASE"];

						if($cblista==29 )
						   $base =  $baseENT;
            if($cblista==28 )
						   $base =  $baseENT; 
            if($cblista==30 )
						   $base =  $baseENT;      
          
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
							$jml=$qry3->data[1]; //j� existe na lista X (jml = j� montado em lista)
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
								      //and $qry3->data["codcliente"]==$codcliente
							
              if(($qry3->data["codigotipolista"]==10
			       and $qry3->data["codigoproduto"]==$codigoproduto) or $qry3->data["codigotipolista"]!=10){
								if($cblista != 13)
								
									$err[0]=inseremovimento($id,$idtipomovimento_atual,
                          $qry1->data["idtipomovimento"],$nlista,$base, $courier,$motivo,1);

          
              
                $sql2="";
                if ($cblista==27) {
								  	$sql2.="update tbentrega set codbase=1,numlista=0 WHERE idexterno='$codbars'";
									  $qry9->executa($sql2);
								  
									}
                  
                  
                  $sql2="";
				 // $pseq = "";
                if ($cblista==2) {
				  	$sql2="update tbentrega set datapromessa='$data_promessa' WHERE idexterno='$codbars'";
				    $qry9->executa($sql2);
                    
								  
                $sql_seq="select sequencialoteinterno from tbentrega  
                       WHERE numlista='".$nlista."' order by sequencialoteinterno desc nulls last limit 1";
             	       $qry_seq->executa($sql_seq);
             	       $pseq = abs($qry_seq->data["sequencialoteinterno"]);
					   
					//   echo "Seq do banco: ".$pseq;
               // echo $sql_seq."<br>";
               //   echo "Anterior Seq: ".$qry_seq->data["sequencialoteinterno"]."<br>";
                   
                   
                   
                    if(!$pseq){
						$pseq=1;
					} else {
						$pseq = $pseq+1;
                    }
				// 	 echo "Nova Seq: ".$pseq;
                    $sql_seq_up= "update tbentrega set sequencialoteinterno = ".$pseq." WHERE idexterno='".$codbars."'";   
                   $qry_seq_up->executa($sql_seq_up);       
                    
                  
                  
									}
                  
                  
                    $sql2="";
                if ($cblista==29) {
								  	$sql2.="update tbentrega set st='E'  WHERE idexterno='$codbars'";
									  $qry9->executa($sql2);
								  
									}
                  
                  
                  
           
								if ($err[0]==0){
									//incrementa Contator de Incluidos
									$contI=$contI+1;


									//incrementa soma de valor das entregas
									$tot_valor_entrega = $tot_valor_entrega + $valorentrega;

									
								}

								//Abre janela de entrada da identifica��o de PGTO para COD
								$sql = "select tbentrega.idinterno,tbdetalhepagto.idinterno,tbformapagto.codforma, tbformapagto.complemento, tbformapagto.nomeforma";
								$sql .=" from tbentrega " ;
								$sql .=" inner join tbdetalhepagto on tbdetalhepagto.idinterno = tbentrega.idinterno";
								$sql .=" inner join tbformapagto on tbformapagto.codforma = tbdetalhepagto.codforma";
								$sql .=" where tbentrega.idexterno = '$codbars'";
								$qry6->executa($sql);
								//ABRE JANELA PARA LEITURA DE CMC7
								if($cblista==8 and $qry6->data["complemento"]==9999){//BEGIN

								//carrega dados da p�gina monta tela
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
				$err[0]=inseremovimento($id,$idtipomovimento_atual,'170',0,$_SESSION["IDBASE"],$courier, $motivo);


        




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
<script type="text/javascript">
function inicio(form){
    <?php if($op==1){ ?>
       form.codbars.disabled = true;

      //se for excluir
	  if (form.radio[1].checked == true)
      {
	      <? if(!$nlista) echo "document.getElementById('div_dificuldade_codbarras').style.visibility='hidden';"; ?>
		form.codbars.disabled = false;
		form.codbars.focus();
      }

      <?php if(!$nlista){ ?>
      //se for incluir
	     if (form.radio[0].checked==true)
	     {
		   document.getElementById('div_dificuldade_codbarras').style.visibility='visible';
		//verifica se jah tem algum graudificuldade da encomenda checado
		graudificuldade_checado=0;
		for (var i=0; i < form.graudificuldade.length;i++){
			if(form.graudificuldade[i].checked)
			graudificuldade_checado=1;
		}

		//se jah tiver checado entao o input da encomenda eh habilitado e foco vai lah
		//senao o input da encomenda continua desabilitada e foco vai pro grau de dificuldade
		if(graudificuldade_checado > 0){
			form.codbars.disabled = false;
			form.codbars.focus();
		}else
		form.graudificuldade[0].focus();
	  }

        <?php   }else{
			echo "form.codbars.disabled = false;";
			echo "form.codbars.focus();";
		 }

	   }else
		echo "form.codbars.focus();";

        ?>
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
    <? if($op==1 and !$nlista){ ?>
        window.alert("Um grau de dificuldade para lista deve ser escolhido");
        return false;
        <? } ?>
}

function seleciona_graudificuldade(selecionado){
        document.form_monta_lista.codbars.disabled = false;
        document.form_monta_lista.codbars.focus();
}

</script>
<script type="text/javascript" src="<?=HOST?>/tablecloth/tablecloth.js"></script>

<div class="box" style="width: 900px; margin: 0 auto;">


<form name="form_monta_lista" method="post" action="<?=$selfLink?>">
<?php
if($base <= 0 or !$base)
$base = $_SESSION["IDBASE"];

if(isset($op))
echo "<input type='hidden' name='op' value='$op'>";

if(isset($data_promessa))
echo "<input type='hidden' name='data_promessa' value='$data_promessa'>";


if(isset($base))
echo "<input type='hidden' name='base' value='$base'>";
if(isset($contI))
echo "<input type='hidden' name='contI' value='$contI'>";
if(isset($contE))
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
    <table>
	  <thead>
      <tr> 
        <th colspan="6" style="padding: 8px;">
		    <font color="#990000" size="3"><strong>Montagem da Lista</strong></font>
        </th>
      </tr>
	  </thead>
      <tr> 
        <td colspan="3"> 
	  <?php
if (isset($cblista)&&($cblista!=0)||($cblista!="")){
        $qry1->executa("SELECT nometipolista,idtipomovimento,idtipoexpede FROM tbtipolista WHERE codigotipolista=$cblista");
        echo "Montando Lista do Tipo: ";
        echo "<font color=#990000>".$qry1->data["nometipolista"]."</font>";
}else{
        echo "<font color=#990000>Tipo de Lista n&atilde;o identificado.</font>";
}

if ($base>0)
{
	$qryBase = new consulta($con);
	$qryBase->executa("Select nomeBase from tbBase where codBase=$base");
	
	$nomebase = "Base n&atilde;o identificada.";
	if ($qryBase->nrw)
	{
		$nomebase = $qryBase->data["nomebase"];
	}	
	
	echo "<br>Base selecionada: <font color=#990000> $nomebase </font>";
	
}
	 ?></br>
	  </div> <div align="left"></div></td>
	    <?php
	 if($op==2 || $op==18){
	 ?>	  
        <td colspan="2"><div align="center"><font color=#990000> </font> </div>
	  <div align="center"> 

	  <?php
		 echo "Lista : ";
		 //echo "<select name='abrlst'>";
		 echo "<input type='text' name='abrlst' value='$abrlst'>";
		
		echo "</select>";
		 //echo " <input type='text' name='abrlst'>";
		 ?>
	  </div></td>
	  	<?php
		 }elseif($op==1 and $cblista == 28){
		 	?>
		        <td>
			  <div align="center"> 
			  <?php
				 echo "Campanha : ";
				 echo "<input type='text' name='campanhaInput' value='$campanhaInput'>";				
				 ?>
			  </div></td>
		      <td>
			  <div align="center"> 
			  <?php
				 echo "Setor : ";
				 echo "<input type='text' name='setorInput' value='$setorInput'>";
				 ?>
			  </div></td>
		<?php 	
		 }
		  ?>
        <td width="20%"> <center>
	    <?php
		  if($op==1){
			  echo "N&uacute;mero da Lista:<br>
		  <font color=#990000><b>$nlista</b></br></font>";
		  }
		  ?>
	    <?php
		  if($op=="" || $op==0 || !isset($op)){
			  echo "<font color=#990000>Lista n&atilde;o identifica.</font>";
			  $op=0;
		  }
		  ?>
	  </center></td>
      </tr>
      <?php
		  if($cblista==2 || $op==18){
			  //Cria Linha na tabela para insercaoo de uma combo para selecao de base
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
					  //Cria Linha na tabela para insercao de uma combo para selecao de base
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
      
      <?php if($op==1 and !$nlista)
      $graudificuldade=1;
			echo "<tr><td colspan='2'>&nbsp;</td><td align='center' colspan='4'><div id='div_dificuldade_codbarras' style='visibility:visible'>Grau de Dificuldade : <input name='graudificuldade' onclick='javascript:seleciona_graudificuldade(this);' type='radio' ". (($graudificuldade==1)?"checked":"")." value='1'>&nbsp;F&aacute;cil <input name='graudificuldade' onclick='javascript:seleciona_graudificuldade(this);' type='radio' ".(($graudificuldade==2)?"checked":"")." value='2'>&nbsp;M&eacute;dia <input name='graudificuldade' onclick='javascript:seleciona_graudificuldade(this);' ".(($graudificuldade==3)?"checked":"")." type='radio' value='3'>&nbsp;Dif&iacute;cil</div></td></tr>";
      ?>
      <tr bgcolor="#eeeeee"> 
        <td colspan="2" bgcolor="#eeeeee"> <div align="left"><font color="#990000"> 
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
						<input name="codbarsAnterior" type="text" size="35" value='<?= $codbarsAnterior ?>' "disabled">	
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
        <td width="5%"> <div align="center"> 
	    <?php //imprimir a  quantidade de registros VALIDOS lidos
	    if($op==1 and $cblista == 28 and !empty($notasFaltando)){
	    	echo $contI." | Faltam: ".$notasFaltando;
	    }else{
	    	echo $contI;
	    }
	  ?>
	  </div></td>
        <td colspan="2"> 
	  <?php //imprimir Ultimo nome de destinatario

	  ?>
        </td>
      </tr>
      <tr> 
        <td height="21"></td>
        <td height="21"></td>
        
        <td><div align="center"> 
	    <?php //imprimir a soma dos valores da encomenda
	 
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
	    <?php //imprimir a  quantidade de registros VALIDOS lidos
	    if($op==1 and $cblista == 28 and !empty($notasFaltando)){
	    	echo $contE." | Faltam: ".$notasFaltando;
	    }else{
	    	echo $contE;
	    } 
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

			 echo "<p>".$codbars.": <font color=#990000>Movimento n&atilde;o permitido.$obs</font></p>";
		}
		if ($err[1]>0)
		echo "<p>".$codbars.": <font color=#990000>Encomenda n&atilde;o localizada.</font></p>";
		if ($err[2]>0)
		echo "<p>".$codbars.": <font color=#990000>Tipo de Lista n&atilde;o Localizado.</font></p>";
		if ($err[3]>0)
		echo "<p>".$codbars.": <font color=#990000>Encomenda j&aacute; se encontra neste tipo de movimento.</font></p>";
		if ($err[4]>0)
		echo "<p>".$codbars.": <font color=#990000>Encomenda j&aacute; montada na lista $jml.</font></p>";
		if ($err[5]>0)
		echo "<p>".$codbars.": <font color=#990000>Agencia Invalida!</font></p>";
		if ($err[6]>0)
		echo "<p>".$codbars.": <font color=#990000>Encomenda n&atilde;o pertence a sua base.</font></p>";
		if ($err[7]>0)
		echo "<p><font color=#990000>Por favor selecione uma lista v&aacute;lida para a reabertura.</font></p>";
		
		
		if ($err[8]>0){
			echo "<p><font color=#990000>ATENÇÃO, ENCOMENDA DE NOVA RA, IMPRIMA O MAPA.</font></p>";
			echo "<script>
				$(function() {
					$('#myModal').modal('show');
				});
			</script>";
		}

		if ($err[9]>0)
		echo "<p><font color=#990000>Campanha ou setor não pertencem a esse código de barras</font></p>";

		
		
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
		  echo "<a href=print_lista_demillus.php?token={$rnd}";
		  if($cblista){
			  echo"&cblista=$cblista";
		  }if($nlista){
			  echo"&lista=$nlista";
		  }if($op){
			  echo"&op=7";
		  }
		  echo"&inf=2";
		  echo">>>>Imprimir Lista<<<</a>";
	  }
		?>
		
		
	  <div align="center"></div></td>
      </tr>
      <tr>
        <td colspan="6"><div align="center"><a href="selecao_lista.php"><< Anterior</a> 
	    <a href="selecao_lista.php"></a></div></td>
      </tr>
      
      
    </table>
  </form>

</div>

<?php
$qry12->executa("SELECT id FROM tb_easy_courier WHERE (nr_encomenda = '{$codbars}')");

$id_tracking = $qry12->data['id'];
if($qry12->nrw){
?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">ATENÇÃO, ENCOMENDA DE NOVA RA, IMPRIMA O MAPA.</h4>
      </div>
      <div class="modal-footer">
        <a target="_blank" href="<?=HOST?>/nova-revendedora/dados.php?id=<?=$id_tracking?>">Imprimir></a>
      </div>
    </div>
  </div>
</div>
<?php
}
?>
<script type="text/javascript">
   inicio(document.form_monta_lista);
   document.form_monta_lista.codbars.focus();
</script>

<?php
// pega o Footer
require_once("inc/footer.inc");