<?php
/*
Programa criado/modificado por      : Bruno Rodrigues Siqueira / Daniel de Oliveira Vianna
Email                    			: rodrigues@simonsen.br / danielvianna@email.com
Telefone                            : (21) 3291-0243
Data de criação/modificado          : 24 de Julho de 2004 / 22 de Dezembro de 2004
*/
// seta variavel
$prgTit = "Cadastro de Bases";

// pega a configuracao
require_once("inc/config.inc");

// monta a consulta
$qry       = new consulta($con);
$qry2      = new consulta($con);
$qry3      = new consulta($con);
$qry4      = new consulta($con);
$qry5      = new consulta($con);
$qry6      = new consulta($con);
$qry_total = new consulta($con);
$msg       = "";

switch($opt2){
	case("LG"):
	$sql = "SELECT * FROM tbdireitolista WHERE codbase='$codbase' and codigotipolista='$codigotipolista'";
	$qry->executa($sql);
	if (!$qry->nrw){
		$sql = "INSERT INTO tbdireitolista ( codbase, codigotipolista )";
		$sql .= " VALUES( $codbase,$codigotipolista)";
		
		$qry->executa($sql);
		
		if ($qry->res)
		$msg = "Lista permitada com sucesso";
	}else
	$msg = "<font color='#FF0000'>Lista já permitada para essa base</font>";
	
	$opt = "L";
	break;
	
	case("LE"):
	$sql = "DELETE FROM tbdireitolista WHERE codbase='$codbase' and codigotipolista='$codigotipolista'";
	$qry->executa($sql);
	
	if ($qry->res)
	$msg = "Lista excluída com sucesso";
	else
	$msg = "<font color='#FF0000'>Lista não existente nesta base</font>";
	
	$opt = "L";
	break;
	
	case("PE"):
	$sql = "DELETE FROM tbbasearray WHERE depbase = '$codbase' and codbase = '$codbase_pai'";
	$qry->executa($sql);
	
	if ($qry->res)
	$msg = "Base submetida excluída com sucesso";
	else
	$msg = "<font color='#FF0000'>Base submetida não existente nesta base</font>";
	
	
	$opt = "P";
	$codbase = $codbase_pai;
	break;
	
	
	case("PG"):
	$sql = "SELECT * FROM tbbasearray WHERE depbase = '$codbase' and codbase = '$codbase_pai'";
	$qry->executa($sql);
	if (!$qry->nrw){
		$sql = "INSERT INTO tbbasearray ( depbase, codbase   )";
		$sql .= " VALUES( $codbase,$codbase_pai)";
		
		$qry->executa($sql);
		
		if ($qry->res)
		$msg = "Base submetida com sucesso";
	}else
	$msg = "<font color='#FF0000'>Base já submetida nesta base</font>";
	
	$opt = "P";
	$codbase = $codbase_pai;
	break;
	
	case("IG"):
	$id_transportadora = $_SESSION['IDTRANSP'];
	$id_base = $_SESSION['IDBASE'];
	
	if ($id_transportadora != "-1" && $id_transportadora && $id_base != "-1" && $id_base){
		$sql = "SELECT * FROM tbBase WHERE nomebase = '$nomebase'";
		$qry->executa($sql);
		if (!$qry->nrw){
			$sql = "INSERT INTO tbBase ( 
                                  banco,
                                  nextel,
                                  placa_veiculo, 
                                  agencia,
                                  conta,
                                  cpf,
                                  cnpj,
                                  favorecido,
								  sigla,
								  monitor,
                                  idtransportadora, 
                                  nomebase, 
                                  nomeresponsavelbase,
                                  emailbase, 
                                  fonebase, 
                                  enderecobase, 
                                  bairrobase, 
                                  cidadebase, 
                                  ufbase,
                                  cepbase  )";
		                            	$sql .= " VALUES('$banco',
                                  '$nextel',
                                  '$placa_veiculo',
                                  '$agencia',
                                  '$conta',
                                  '$cpf',
                                  '$cnpj',
                                  '$favorecido',
								  '$sigla',
								  '$monitor',
								  
                                  '".$_SESSION['IDTRANSP']."',
                                  '$nomebase', '$nomeresponsavelbase', 
                                  '$emailbase',
                                  ".(($fonebase)?"'$fonebase'":"NULL").", 
                                  '$enderecobase', 
                                  '$bairrobase', 
                                  '$cidadebase', 
                                  '$ufbase',
                                  ".(($cepbase)?"'$cepbase'":"NULL").")";
			                            $qry->executa($sql);
      
      //
      $tipo_base = substr($nomebase,0,2);
       //echo $tipo_base;
       //die;
       
      
      
			
			if ($qry->res){
				$qry->executa("select last_value from tbbase_codbase_seq");
				$novo_codbase_gerado = $qry->data["last_value"];
				//incluindo a Fast como depbase
				$sql = "INSERT INTO tbbasearray (depbase,codbase) VALUES ('1','$novo_codbase_gerado')";
				$qry->executa($sql);
				
				$sql = "INSERT INTO tbbasearray (depbase,codbase) VALUES ('$novo_codbase_gerado','1')";
				$qry->executa($sql);
				
				$sql = "INSERT INTO tbdireitoauditoria (codbase,codbasedireito) VALUES ('$novo_codbase_gerado','$novo_codbase_gerado')";
				$qry->executa($sql);
				
				
        $sql = "INSERT INTO tbdireitoauditoria (codbase,codbasedireito) VALUES ('1','$novo_codbase_gerado')";
				$qry->executa($sql);
				
				 $sql = "INSERT INTO tbdireitotelemarketing (codbase,codbasedireito) VALUES ('1','$novo_codbase_gerado')";
				$qry->executa($sql);
				
        
        $msg = "Base Incluída com sucesso";
				
			}else
			$msg = "<font color='#FF0000'>Ocorreu um erro durante a inclusão da base</font>";
		}
	}
	else
	$opt = "I";
	break;
	
	
	case("AG"):
	$sql = "SELECT * FROM tbBase WHERE  nomebase = '$nomebase'";
	$qry->executa($sql);
	
	if (!$qry->nrw || $qry->data["0"]==$codbase){
		$sql = "UPDATE tbBase SET
            nomeresponsavelbase = '".$nomeresponsavelbase."', emailbase = '".$emailbase."',
                                fonebase = ".(($fonebase)?"'$fonebase'":"NULL").", enderecobase = '".$enderecobase."',
                                bairrobase = '".$bairrobase."', cidadebase = '".$cidadebase."',
                                ufbase = '".$ufbase."', cepbase = ".(($cepbase)?"'$cepbase'":"NULL").",
                                nomebase = '$nomebase',
                                banco = '$banco',
                                agencia = '$agencia',
                                nextel = '$nextel',
                                placa_veiculo = '$placa_veiculo',
                                conta = '$conta',
                                cpf = '$cpf',
                                cnpj = '$cnpj',
                                favorecido = '$favorecido',
								sigla = '$sigla',
								monitor = '$monitor',
                ativa = '$ativa'
                                
                                WHERE codbase = '$codbase'";
		
		$qry->executa($sql);
    
    $tipo_base = substr($nomebase,0,2);
       //echo $tipo_base;
       //die;
       
       if($tipo_base = 'DM')
       {
        $sql = "UPDATE tbBase SET transfere = 'D' WHERE codbase = '$codbase'";
        $qry->executa($sql);
       
       }
       
    
	}
	break;
	
	case("E"):
	$sql = "DELETE FROM tbbase WHERE codbase='$codbase'";//deletando a base
	$qry->executa($sql);
	
	if ($qry->res){
		$sql = "DELETE FROM tbbasearray WHERE codbase='$codbase' OR depbase='$codbase'";//deletando as bases submetidas
		$qry->executa($sql);
		
		$sql = "DELETE FROM tbdireitoauditoria WHERE codbase='$codbase' OR codbasedireito='$codbase'";//deletando os direitos da base
		$qry->executa($sql);
		
		$sql = "DELETE FROM tbdireitolista WHERE codbase='$codbase'";//deletando as listas permitidas
		$qry->executa($sql);
		
		$msg = "Base excluída com sucesso";
	}else
	$msg = "<font color='#FF0000'>Ocorreu um erro durante a exclusão da base</font>";
	
	$opt = "";
	break;
}

// seta o link atual
$alLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>
 <center>
  <table cellspacing=3 cellpadding=0 align=left border=0>

    <tr>
    	<td align="center" colspan="9">
      <form action='<?=$alLink?>' method="post">
            <input type="hidden" name=opt value="I" />
            <input type="submit" class="submit" value="Incluir nova Base" />
      </form>
        </td>
    </tr>
    
    <tr>
		<td><br></td>
	</tr>
    <?php
    switch($opt){
	case("I"):
	echo "<form action='{$alLink}' method='post'>
                           <input type=hidden name=opt2 value='IG'>
                           <input type=hidden name=idtransportadora value='".$_SESSION['IDTRANSP']."'>

                               <tr>
                                  <td>Nome Base</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text  maxlength=20 align=left size=20 name=nomebase value='".$qry->data["nomebase"]."'></td>
                               </tr>
                               <tr>
                                  <td>Nome respons&aacute;vel</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text maxlength=20 align=left size=20 name=nomeresponsavelbase value='".$qry->data["nomeresponsavelbase"]."'></td>
                               </tr>
                               <tr>
                                  <td>Email</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text maxlength=20 align=left size=20 name=emailbase value='".$qry->data["emailbase"]."'></td>
                               </tr>
                              
                              
                               <tr>
                                  <td>Telefone</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text maxlength=20 align=left size=20 name=fonebase value='".$qry->data["fonebase"]."'></td>
                               </tr>
                               
                                <tr>
                                  <td>Nextel</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text maxlength=12 align=left size=12 name=nextel value='".$qry->data["nextel"]."'></td>
                               </tr>
                               
                              
                               <tr>
                                  <td>Numero Placa</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text maxlength=7 align=left size=7 name=placa_veiculo value='".$qry->data["placa_veiculo"]."'></td>
                               </tr>
                               
                              
                               
                              
                               <tr>
                                  <td>Endere&ccedil;o</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text maxlength=50 align=left size=50 name=enderecobase value='".$qry->data["enderecobase"]."'></td>
                               </tr>
                               <tr>
                                  <td>Bairro</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text maxlength=30 align=left size=30 name=bairrobase value='".$qry->data["bairrobase"]."'></td>
                               </tr>
                               <tr>
                                  <td>Cidade</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text maxlength=30 align=left size=30 name=cidadebase value='".$qry->data["cidadebase"]."'></td>
                               </tr>
                               <tr>
                                  <td>Estado</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text maxlength=2 align=left size=2  name=ufbase value='".$qry->data["ufbase"]."'></td>
                               </tr>
                               
                               <tr>
                                  <td>CEP</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=cepbase value='".$qry->data["cepbase"]."'></td>
                               </tr>
                               
                             
                               <tr>
                                  <td>BANCO</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=banco value='".$qry->data["banco"]."'></td>
                               </tr>
                             
                               
                               <tr>
                                  <td>AGENCIA</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=agencia value='".$qry->data["agencia"]."'></td>
                               </tr>
                             
                               <tr>
                                  <td>CONTA</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=conta value='".$qry->data["conta"]."'></td>
                               </tr>
                             
                               <tr>
                                  <td>CPF</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=cpf value='".$qry->data["cpf"]."'></td>
                               </tr>
                             
                               <tr>
                                  <td>cnpj</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=cnpj value='".$qry->data["cnpj"]."'></td>
                               </tr>
                             
                               <tr>
                                  <td>FAVORECIDO</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=favorecido value='".$qry->data["favorecido"]."'></td>
                               </tr>
                             
                             <tr>
                                  <td>SIGLA</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=sigla value='".$qry->data["sigla"]."'></td>
                               </tr>
                             
                             <tr>
                                  <td>APARECE NO MONITOR (S OU N)</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=monitor value='".$qry->data["monitor"]."'></td>
                               </tr>
                             
                               
                               
                               
                               <tr>
	<td height='40' align='center' colspan='3'><input type='submit' class='submit' value='Incluir'>&nbsp;&nbsp;<input type='button' class='cancel' onclick=\"javascript:document.location.href='{$alLink}'\" value='Cancelar'></td>
                               </tr>
                           </form>

                          ";
	break;
	
	
	case("A"):
	$sql = "SELECT * FROM tbBase where codbase = '$codbase'";
	$qry->executa($sql);
	
	echo "<form action='{$alLink}' method='post'>
                           <input type=hidden name=opt2 value='AG'>
                           <input type=hidden name=codbase value='$codbase'>
                           <input type=hidden name=idtransportadora value='".$_SESSION['IDTRANSP']."'>

                               <tr>
                                  <td>Nome Base</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=nomebase value='".$qry->data["nomebase"]."'></td>
                               </tr>
                               <tr>
                                  <td>Nome respons&aacute;vel</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=nomeresponsavelbase value='".$qry->data["nomeresponsavelbase"]."'></td>
                               </tr>
                               <tr>
                                  <td>Email</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=emailbase value='".$qry->data["emailbase"]."'></td>
                               </tr>
                               
                               
                               <tr>
                                  <td>Telefone</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=fonebase value='".$qry->data["fonebase"]."'></td>
                               </tr>
                               
                               
                               <tr>
                                  <td>Nextel</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text maxlength=12 align=left size=12 name=nextel value='".$qry->data["nextel"]."'></td>
                               </tr>
                               
                              <tr>
                                  <td>Placa Ve&iacute;culo</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text maxlength=7 align=left size=7 name=placa_veiculo value='".$qry->data["placa_veiculo"]."'></td>
                               </tr>
                                
                               
                               
                               
                               <tr>
                                  <td>Endere&ccedil;o</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=enderecobase value='".$qry->data["enderecobase"]."'></td>
                               </tr>
                               <tr>
                                  <td>Bairro</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=bairrobase value='".$qry->data["bairrobase"]."'></td>
                               </tr>
                               <tr>
                                  <td>Cidade</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=cidadebase value='".$qry->data["cidadebase"]."'></td>
                               </tr>
                               <tr>
                                  <td>Estado</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text maxlength=2 name=ufbase value='".$qry->data["ufbase"]."'></td>
                               </tr>
                               <tr>
                                  <td>CEP</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=cepbase value='".$qry->data["cepbase"]."'></td>
                               </tr>
                               
                               
                               
                                <tr>
                                  <td>BANCO</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=banco value='".$qry->data["banco"]."'></td>
                               </tr>
                             
                               
                               <tr>
                                  <td>AGENCIA</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=agencia value='".$qry->data["agencia"]."'></td>
                               </tr>
                             
                               <tr>
                                  <td>CONTA</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=conta value='".$qry->data["conta"]."'></td>
                               </tr>
                             
                               <tr>
                                  <td>CPF</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=cpf value='".$qry->data["cpf"]."'></td>
                               </tr>
                             
                               <tr>
                                  <td>cnpj</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=cnpj value='".$qry->data["cnpj"]."'></td>
                               </tr>
                             
                               <tr>
                                  <td>FAVORECIDO</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=favorecido value='".$qry->data["favorecido"]."'></td>
                               </tr>
                             
                               <tr>
                                  <td>SIGLA</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=sigla value='".$qry->data["sigla"]."'></td>
                               </tr>
                             
                               <tr>
                                  <td>MONITORA ? (S OU N)</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=monitor value='".$qry->data["monitor"]."'></td>
                               </tr>
                               <tr>
                                  <td>ATIVA ? (S OU N)</td><td width='5' 'align=center'>:</td>
                                  <td><input type=text name=ativa value='".$qry->data["ativa"]."'></td>
                               </tr>                             
                               
                               <tr>
                                  <td height='40' align='center' colspan='3'><input type='submit' class='submit' value='Alterar'>&nbsp;&nbsp;<input type='button' class='cancel' onclick=\"javascript:document.location.href='{$alLink}'\" value='Cancelar'></td>
                               </tr>
                           </form>

                          ";
	break;
	
	
	case("P"):
	$sql = "SELECT * FROM tbBase WHERE codbase = '$codbase'";;
	$qry->executa($sql);
	
	echo "<tr bgcolor='#cccccc'>
                           <td colspan=2><b>Base:</b> ".$qry->data["nomebase"]."</td>
                         </tr>";
	
	$sql = "SELECT tbbasearray.depbase,tbbase.nomebase FROM tbbasearray, tbbase WHERE tbbasearray.codbase='$codbase' AND tbbase.codbase=tbbasearray.depbase ORDER BY tbbase.nomebase";
	$qry->executa($sql);
	
	if ($qry->nrw){
		echo "<form action='{$alLink}' method='POST' name='exclusaoP'>";
		echo "<input type='hidden' name='opt2' value='PE'>";
		echo "<input type='hidden' name='codbase' value=''>";
		echo "<input type='hidden' name='codbase_pai' value='$codbase'>";
		echo "</form>";
		
		for($i=0;$i<$qry->nrw;$i++){
			$qry->navega($i);
			
			echo "<tr ".(($i%2==0)?"":"bgcolor='#eeeeee'").">
                                   <td>".$qry->data["nomebase"]."</td><td><a href=# onclick=\"javascript:if(confirm('Deseja remover a base submetida ".$qry->data["nomebase"]." desta base?')){ document.exclusaoP.codbase.value='".$qry->data["depbase"]."';document.exclusaoP.submit(); }\">Remover</td>
                                 </tr>";
			
		}
	}
	else
	echo "<tr>
                       	       <td colspan=2><font color='#FF0000'>Nenhuma base submetida</font></td>
                             </tr>";
	
	
	echo "<tr>
                          <td colspan=2><br></td>
                        </tr>";
	echo "<tr>
                          <form action='{$alLink}' METHOD='POST' name='gravaP'>
                              <input type=hidden name=codbase_pai value='$codbase'>
                              <input type=hidden name=opt2 value='PG'>
                             <td>
                                <SELECT name=codbase>";
	$sql = "SELECT codbase,nomebase FROM tbbase ORDER BY nomebase ASC";
	$qry->executa($sql);
	for($i=0;$i<$qry->nrw;$i++){
		$qry->navega($i);
		
		$qry2->nrw=0;
		$sql2 = "SELECT depbase FROM tbbasearray WHERE codbase='$codbase' AND depbase='".$qry->data["codbase"]."'";
		$qry2->executa($sql2);
		
		if(!$qry2->nrw)
		echo "<option value='".$qry->data["codbase"]."'>".$qry->data["nomebase"]."</option>";
	}
	echo "
                                </select>
                             </td>
                             <td>&nbsp;<input type=submit value='Adicionar esta permiss&atilde;o'></td>
                         </form>
                        </tr>";
	
	echo "<tr>
                          <td colspan='2'><br></td>
                        </tr>";
	
	echo "<tr>
                          <td colspan='2' align='center'><a href='{$alLink}'>Voltar</a></td>
                        </tr>";
	
	break;
	
	case("L"):
	$sql = "SELECT nomebase FROM tbBase WHERE codbase = '$codbase'";;
	$qry->executa($sql);
	
	echo "<tr bgcolor='#cccccc'>
                           <td colspan=2><b>Base:</b> ".$qry->data["nomebase"]."</td>
                         </tr>";
	
	$sql = "SELECT tbdireitolista.codigotipolista,tbtipolista.nometipolista FROM tbdireitolista, tbtipolista WHERE tbdireitolista.codbase = '$codbase' AND tbdireitolista.codigotipolista=tbtipolista.codigotipolista ORDER BY tbtipolista.nometipolista ASC";
	$qry->executa($sql);
	
	if ($qry->nrw){
		echo "<form action='{$alLink}' method='POST' name='exclusaoL'>";
		echo "<input type='hidden' name='opt2' value='LE'>";
		echo "<input type='hidden' name='codigotipolista' value=''>";
		echo "<input type='hidden' name='codbase' value='$codbase'>";
		echo "</form>";
		for($i=0;$i<$qry->nrw;$i++){
			$qry->navega($i);
			
			echo "<tr ".(($i%2==0)?"":"bgcolor='#eeeeee'").">
                                   <td >".$qry->data["nometipolista"]."</td><td><a href=# onclick=\"javascript:if(confirm('Deseja remover o tipo de lista ".$qry->data["nometipolista"]." desta base?')){ document.exclusaoL.codigotipolista.value='".$qry->data["codigotipolista"]."';document.exclusaoL.submit(); }\">Remover</td>
                                 </tr>";
			
		}
	}
	else
	echo "<tr>
                       	       <td colspan=2><font color='#FF0000'>Nenhuma lista permitida</font></td>
                             </tr>";
	
	
	echo "<tr>
                          <td colspan=2><br></td>
                        </tr>";
	echo "<tr>
                          <form action='{$alLink}' METHOD='POST' name='gravaL'>
                              <input type=hidden name='codbase' value='$codbase'>
                              <input type=hidden name='opt2' value='LG'>
                             <td>
                                <SELECT name='codigotipolista'>";
	$sql = "SELECT codigotipolista,nometipolista FROM tbtipolista ORDER BY nometipolista ASC";
	$qry->executa($sql);
	for($i=0;$i<$qry->nrw;$i++){
		$qry->navega($i);
		
		echo "<option value='".$qry->data["codigotipolista"]."'>".$qry->data["nometipolista"]."</option>";
	}
	echo "
                                </select>
                             </td>
                             <td>&nbsp;<input type=submit value='Adicionar esta lista'></td>
                         </form>
                        </tr>";
	
	echo "<tr>
                          <td colspan='2'><br></td>
                        </tr>";
	
	echo "<tr>
                          <td colspan='2' align='center'><a href='{$alLink}'>Voltar</a></td>
                        </tr>";
	
	break;
	
	
	default:
	$sql1 = "SELECT tbbase.codbase,tbbase.nomebase,tbbase.cidadebase,tbbase.nextel,tbbase.fonebase,tbbase.sigla,tbbase.posicao_cesta,
        tbtransportadora.nometransportadora FROM tbbase, tbtransportadora where tbbase.idtransportadora = '".$_SESSION['IDTRANSP']."' and tbbase.idtransportadora=tbtransportadora.idtransportadora order by nomebase";
	$sql = "SELECT tbbase.codbase,tbbase.ativa,tbbase.nomebase,tbbase.nextel,tbbase.fonebase,tbBase.sigla,tbbase.posicao_cesta,
          tbbase.cidadebase,tbtransportadora.nometransportadora 
          FROM tbbase, tbtransportadora 
          where tbbase.idtransportadora = '".$_SESSION['IDTRANSP']."' 
          and tbbase.idtransportadora=tbtransportadora.idtransportadora  order by nomebase";
	$qry->executa($sql);
	
	if ($qry->nrw){
		echo "\n<form action='{$alLink}' name='operacao' method='post'>
                       <input type=hidden name=codbase value=''>
                       <input type=hidden name=opt value=''>
            		   <input type=hidden name=opt2 value=''>
              		   </form>\n";
		
		echo "<tr bgcolor='#cccccc'>
                      <td><b>Base</b></td>
                      <td nowrap><b>Nextel</b></td>
                      <td nowrap><b>Telefone</b></td>
              		  <td><b>Cidade</b></td>
                      <td><b>Sigla</b></td> 
					  <td><b>Posi&ccedil;&atilde;o</b></td> 
                      <td colspan='3'><b>Op&ccedil;&otilde;es</b></td> 
                    
                      
                    </tr>";
		for($i=0; $i<$qry->nrw; $i++){
			$qry->navega($i);
      
         $bb = $qry->data["codbase"];
      	 //$sql = "SELECT count(idinterno) as total_base from tbentrega as total_base  where codbase = $bb";
	       //$qry_total->executa($sql);
			
			echo "\n
              		<tr ".(($i%2==0)?"":"bgcolor='#eeeeee'").">
                      <td>".$qry->data["nomebase"]."</td>
                      <td>".$qry->data["nextel"]."</td>
                      <td>".$qry->data["fonebase"]."</td>
                      <td>".$qry->data["cidadebase"]."</td>
					  <td>".$qry->data["sigla"]."</td>
					  <td>".$qry->data["posicao_cesta"]."</td>
                      <td>".$qry_total->data["total_base"]."</td>";
			
			echo   "<td colspan='2'>
			            <a href=# onclick=\"javascript:document.operacao.codbase.value='".$qry->data["codbase"]."';document.operacao.opt.value='A';document.operacao.submit();\">Alterar</a>&nbsp;&nbsp; 
              			  &nbsp;&nbsp;<a href=# onclick=\"javascript:document.operacao.codbase.value='".$qry->data["codbase"]."';document.operacao.opt.value='P';document.operacao.submit();\">Bases Submetidas</a>&nbsp;&nbsp;
              			  &nbsp;&nbsp;<a href=# onclick=\"javascript:document.operacao.codbase.value='".$qry->data["codbase"]."';document.operacao.opt.value='L';document.operacao.submit();\">Listas Permitidas</a>&nbsp;&nbsp;
              			  
                    </tr>";
                    
			
		}
		echo "<tr><td><br></td></tr>";
	}
	else
	$msg="<font color='#FF0000'>Nenhuma base cadastrada</font>";
	
}

    ?>
  </table>
  <?php
    if ($msg)
    echo "<br>$msg<br>";
  ?>
</center>

<?php
// pega o Footer
require_once("inc/footer.inc");