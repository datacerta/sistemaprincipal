<?php
session_start();  //para bloquear a tela
/*
Programa criado/modificado por      : Bruno Rodrigues Siqueira / Daniel de Oliveira Vianna
Email                    			: rodrigues@simonsen.br / danielvianna@email.com
Telefone                            : (21) 3291-0243
Data de criação/modificado          : 24 de Julho de 2004 / 14 de Janeiro de 2005
*/

require_once("classes/classebd.inc.php");
require_once("classes/diversos.inc.php");

//testa sessão
if (VerSessao()==false){
	header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}

$qry = new consulta($con);
$qry1 = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry6 = new consulta($con);

$msg="";

if ($_SESSION["IDUSER"] == 6 || $_SESSION["IDUSER"] == 2030 || $_SESSION["IDUSER"] == 2036)            //apenas o login do Ricardo pode acessar esta página.
{

switch($opt2){
	case("PE"):
	$sql = "DELETE FROM tbmenu WHERE codlogin = '$codlogin' and idphp = '$idphp'";
	$qry->executa($sql);
	
	if ($qry->res)
	$msg = "Permissão excluída com sucesso";
	else
	$msg = "<font color='#FF0000'>Permissão não existente para este usuário</font>";
	
	
	$opt = "P";
	break;
	
	case("PG"):
	$sql = "SELECT * FROM tbmenu WHERE codlogin = '$codlogin' and idphp = '$idphp'";
	$qry->executa($sql);
	
	if (!$qry->nrw){
		$sql = "INSERT INTO tbmenu (codlogin, idphp) VALUES('$codlogin','$idphp')";
		$qry->executa($sql);
		
		if ($qry->res)
		$msg = "Permissão adicionada com sucesso";
	}else
	$msg = "<font color='#FF0000'>Permissão já adicionada para este usuário</font>";

	$opt = "P";
	break;
	
	
	case("IG"):
	
	$nomelogin = trim($nomelogin);
	$emailusuario = trim(strtolower($emailusuario));
	$nomeusuario = mostra_nome($nomeusuario);
	$senha = trim($senha);
	$dicasenha = trim($dicasenha);
	$clientes = trim($clientes);
	$ativo = trim($ativo);
	
	
	if(!($idtransportadora > 0) or !($codbase > 0)){
		$msg = "<font color='#FF0000'>Por favor selecione uma tranpostadora e uma base para o usuário</font>";
		$opt = "I";
		break;
	}
	
	if(!$nomelogin){
		$msg = "<font color='#FF0000'>Por favor preencha o login para o usuário</font>";
		$opt = "I";
		break;
	}
	
	if(!$nomeusuario){
		$msg = "<font color='#FF0000'>Por favor preencha o nome para o usuário</font>";
		$opt = "I";
		break;
	}
	
	if(!$senha){
		$msg = "<font color='#FF0000'>Por favor preencha a senha para o usuário</font>";
		$opt = "I";
		break;
	}
	
	if($emailusuario){
		$qry->nrw = 0;
		$sql = "SELECT codlogin FROM tblogin WHERE LOWER(emailusuario) ='$emailusuario'";
		$qry->executa($sql);
		
		if($qry->nrw){
			$msg = "<font color='#FF0000'>E-mail já cadastrado para outro usuário</font>";
			$opt = "I";
			break;
		}
	}
	
	$qry1->nrw = 0;
	$sql1 = "SELECT codlogin FROM tblogin WHERE LOWER(nomelogin) = '".strtolower($nomelogin)."'";
	$qry1->executa($sql1);
	
	if($qry1->nrw){
		$msg = "<font color='#FF0000'>Login já cadastrado para outro usuário</font>";
		$opt = "I";
		break;
	}
	
	if (!$qry->nrw and !$qry1->nrw){
    if (!$ativo)
       $ativo = 'S'; //para o fastnet, validamos se o usuário está ativo ou não. No e-sisco não temos essa validação então "não importa". Isso vai forçar o S, caso não seja posto nada.
    
		$qry->res = "";
		$sql = "INSERT INTO tblogin (idtransportadora, codbase, nomelogin, emailusuario, nomeusuario, senha, dicasenha, nivelusuario, codcliente,ativo,liberaestoque)";
		$sql.= " VALUES('$idtransportadora', '$codbase', '$nomelogin', '$emailusuario', '$nomeusuario', '".md5($senha)."', 
		'".addslashes($dicasenha)."', '$nivelusuario','$codcliente' ,'$ativo','$liberaestoque')";
		$qry->executa($sql);
		echo $sql;
		if ($qry->res){
			$sql = "SELECT last_value FROM tblogin_codlogin_seq";
			$qry->executa($sql);
			$codlogin_last = $qry->data["last_value"];
			
			$sql = "INSERT INTO tbdireitotransportadora (idtransportadora, codlogin)";
			$sql.= " VALUES('$idtransportadora','$codlogin_last')";
			$qry->executa($sql);
			
			
			//$menus_basicos
			$ids_php = array(1,2,3,4,5,31,45,52,225);
			
			for ($i=0;$i<count($ids_php);$i++){
				
				$sql = "INSERT INTO tbmenu (codlogin, idphp)";
				$sql.= " VALUES('$codlogin_last','".$ids_php[$i]."')";
				$qry->executa($sql);
			}
			
			$msg = "Usuário Incluído com sucesso";
		}
	}
	
	break;
	
	
	case("AG"):
	$nomelogin = trim($nomelogin);
	$emailusuario = trim(strtolower($emailusuario));
	$nomeusuario = mostra_nome($nomeusuario);
	$senha = trim($senha);
	$dicasenha = trim($dicasenha);
	$clientes = trim($clientes);
	$ativo = trim($ativo);
	
	if(!($idtransportadora > 0) or !($codbase > 0)){
		$msg = "<font color='#FF0000'>Por favor selecione uma tranpostadora e uma base para o usuário</font>";
		$opt = "A";
		break;
	}
	
	if(!$nomelogin){
		$msg = "<font color='#FF0000'>Por favor preencha o login para o usuário</font>";
		$opt = "A";
		break;
	}
	
	if(!$nomeusuario){
		$msg = "<font color='#FF0000'>Por favor preencha o nome para o usuário</font>";
		$opt = "A";
		break;
	}
	
	if($emailusuario){
		$qry->nrw = 0;
		$sql = "SELECT codlogin FROM tblogin WHERE LOWER(emailusuario) ='$emailusuario' and codlogin<>'$codlogin'";
		$qry->executa($sql);
		
		if($qry->nrw){
			$msg = "<font color='#FF0000'>E-mail já cadastrado para outro usuário</font>";
			$opt = "A";
			break;
		}
	}
	
	$qry1->nrw = 0;
	$sql1 = "SELECT codlogin FROM tblogin WHERE LOWER(nomelogin) = '".strtolower($nomelogin)."' and codlogin<>'$codlogin'";
	$qry1->executa($sql1);
	
	if($qry1->nrw){
		$msg = "<font color='#FF0000'>Login já cadastrado para outro usuário</font>";
		$opt = "A";
		break;
	}
	
	if (!$qry->nrw and !$qry1->nrw){
		
		$sql = "UPDATE tblogin SET idtransportadora='$idtransportadora', codbase='$codbase', ativo='$ativo',
		nomelogin = '$nomelogin', emailusuario = '$emailusuario', nomeusuario = '$nomeusuario', codcliente = '$codcliente',
		nivelusuario='$nivelusuario', liberaestoque='$liberaestoque', dicasenha='".addslashes($dicasenha)."'";
		
		if($altera_senha=="t") $sql.= ", senha = '".md5($senha)."' ";
		$sql.= " WHERE codlogin = '$codlogin'";
		$qry->executa($sql);
		
		if(!$qry->res){
			$msg = "<font color='#FF0000'>Ocorreu um erro durante a alteração do usuário</font>";
			$opt = "A";
			break;
		}else
		$msg = "Usuário Alterado com sucesso";
	}
	break;
	
	case("E"):
	$qry->res = "";
	$sql = "DELETE FROM tblogin WHERE codlogin='$codlogin'";//deletando o usuário
	$qry->executa($sql);
	
	if ($qry->res){	
		$sql = "DELETE FROM tbdireitotransportadora WHERE codlogin='$codlogin'";//deletando as transportadors de direito do usuario
		$qry->executa($sql);
		
		$sql = "DELETE FROM tbmenu WHERE codlogin='$codlogin'";//deletando as permissoes
		$qry->executa($sql);
		
		$msg = "Usuário excluído com sucesso";
	}else
	$msg = "<font color='#FF0000'>Ocorreu um erro durante a exclusão do usuário</font>";
	
	$opt = "";
	break;	
	
}

?>
<html>
<head>
<link href="estilo.css" rel="stylesheet" type="text/css">
<link href="tahoma.css" rel="stylesheet" type="text/css">
<title>..:: Bem Vindo ao eFast - cadastro de usuários ::..</title>
</head>
<body>
 <center>
  <table cellspacing=3 cellpadding=0 align=center border=0>
    <?

switch($opt){
	
	case("A"):
	$sql = "SELECT * FROM tblogin where codlogin = '$codlogin'";
	$qry->executa($sql);
	
	$input_hidden = "<input type='hidden' name='codlogin' value='$codlogin'>";
	
	if(!$idtransportadora) $idtransportadora = $qry->data["idtransportadora"];
	if(!$codbase) $codbase = $qry->data["codbase"];
	if(!$nomeusuario) $nomeusuario = $qry->data["nomeusuario"];
	if(!$nomelogin) $nomelogin = $qry->data["nomelogin"];
	if(!$senha) $senha = $qry->data["senha"];
	if(!$emailusuario) $emailusuario = $qry->data["emailusuario"];
	if(!$dicasenha) $dicasenha = $qry->data["dicasenha"];
	if(!$nivelusuario) $nivelusuario = $qry->data["nivelusuario"];
	if(!$liberaestoque) $liberaestoque = $qry->data["liberaestoque"];
	if(!$codcliente) $codcliente = $qry->data["codcliente"];
	if(!$ativo) $ativo = $qry->data["ativo"];
	
	if($altera_senha!="t")
	$disabled_senha = "disabled";
	
	
	case("I"):
	
	if(!$idtransportadora and $id_transportadora)
	$idtransportadora = $id_transportadora;
	
	if(!$codbase and $id_base)
	$codbase = $id_base;
	
	echo "<form action='$PHP_SELF' method='POST' name='inclui_altera'>
                   		   $input_hidden
                           <input type='hidden' name='opt2' value='".(($opt=="A")?"AG":"IG")."'>
                   
      						   <tr>
								  <td colspan=3 align='center'>&nbsp;</td>
                    		   </tr>
                   
      						   <tr bgcolor='#cccccc'>
								  <td colspan=3 align='center'><font color='#990000'><b>.::".(($opt=="A")?"Altera&ccedil;&atilde;o":"Inclus&atilde;o")." de Usu&aacute;rio::.</b></font></td>
                    		   </tr>
                   
      						   <tr>
								  <td colspan=3 align='center'>&nbsp;</td>
                    		   </tr>
                   
                               <tr>
                                  <td>Transportadora</td><td width='5' align='center'>:</td>
                                  <td><select name='idtransportadora' onchange=\"javascript:document.inclui_altera.codbase.disabled='true';document.inclui_altera.opt2.value='$opt';document.inclui_altera.opt2.name='opt';document.inclui_altera.submit();\">'";
	combo("SELECT idtransportadora,nometransportadora FROM tbtransportadora ORDER BY nometransportadora",$idtransportadora);
	echo "			  </select>
                   				  </td>
                               </tr>
                   
                               <tr>
                                  <td>Base</td><td width='5' align='center'>:</td>
                                  <td><select name='codbase'>'";
	combo("SELECT codbase,nomebase FROM tbbase WHERE idtransportadora = '".intval($idtransportadora)."' order by nomebase",$codbase );
	echo "			  </select>
                   				  </td>
                               </tr>

                               <tr>
                                  <td>Nome</td><td width='5' align='center'>:</td>
                                  <td><input type='text' name='nomeusuario' value='".$nomeusuario."' maxlength='30'></td>
                               </tr>
                   
                               <tr>
                                  <td>Login</td><td width='5' align='center'>:</td>
                                  <td><input type='text' name='nomelogin' value='".$nomelogin."' maxlength='30'></td>
                               </tr>
                   
                               <tr>
                                  <td>Email</td><td width='5' align='center'>:</td>
                                  <td><input type='text' name='emailusuario' value='".$emailusuario."' maxlength='30'></td>
                               </tr>
                   
                               <tr>
                                  <td>Senha</td><td width='5' align='center'>:</td>
                                  <td><input type='password' name='senha' value='".$senha."' maxlength='10' $disabled_senha >
                   					  ".(($opt=="A") ? "<input type='checkbox' name='altera_senha' ".(($altera_senha=="t")?"checked":"")." value='t' onclick=\"javascript:if(this.checked){ document.inclui_altera.senha.disabled=false; document.inclui_altera.senha.value=''; document.inclui_altera.senha.focus(); }else{ document.inclui_altera.senha.disabled=true; document.inclui_altera.senha.value='$senha'; }\">":"")."
                   				  </td>
                               </tr>
                   
                               <tr>
                                  <td>Dica de Senha</td><td width='5' align='center'>:</td>
                                  <td><input type='text' name='dicasenha' value='".$dicasenha."' maxlength='50'></td>
                               </tr>
                   
                               <tr>
                                  <td>N&iacute;vel do Usu&aacute;rio</td><td width='5' 'align=center'>:</td>
                                  <td><select name='nivelusuario'><option value='1' ".(($nivelusuario==1)?"selected":"")." >1</option><option value='2' ".(($nivelusuario==2)?"selected":"").">2</option><option value='3' ".(($nivelusuario==3)?"selected":"").">3</option></select></td>
                               </tr>
                   
                               <tr>
                                  <td>Libera Estoque</td><td width='5' 'align=center'>:</td>
                                  <td><input type='radio' name='liberaestoque'  value='t' ".(($liberaestoque=="t")?"checked":"")." > Sim&nbsp;&nbsp;&nbsp;<input type='radio' name='liberaestoque'  value='f' ".(($liberaestoque=="f" or !$liberaestoque)?"checked":"")." > Não</td>
                               </tr>
							   
							   <tr>
                                  <td>Cliente Permitido</td><td width='5' align='center'>:</td>
                                  <td><input type='text' name='codcliente' value='".$codcliente."' maxlength='300'></td>
                               </tr>
							  
							   <tr>
                                  <td>Usuário Ativo : (S ou N)</td><td width='1' align='center'>:</td>
                                  <td><input type='text' name='ativo' value='".$ativo."' maxlength='1'></td>
                               </tr>
							  
							   
	
                               <tr>
                                  <td height='40' align='center' colspan='3'><input type='submit' value='Gravar'>&nbsp;&nbsp;<input type='button' onclick=\"javascript:document.location.href='$PHP_SELF'\" value='Cancelar'></td>
                               </tr>
                           </form>";
	break;
	
	
	case("P"):
	$sql = "SELECT nomelogin FROM tblogin WHERE codlogin = '$codlogin'";;
	$qry->executa($sql);
	
    echo "<tr>
						   <td colspan=2>&nbsp;</td>
                    	 </tr>";

	echo "<tr bgcolor='#cccccc'>
                           <td colspan=2><b>Usuário:</b> ".$qry->data["nomelogin"]."</td>
                         </tr>";
	
	$sql = "SELECT tbmenu.idphp,tbphp.descricao FROM tbmenu,tbphp WHERE tbmenu.codlogin = '$codlogin' AND tbmenu.idphp=tbphp.idphp ORDER BY tbphp.descricao";
	$qry->executa($sql);
	
	if ($qry->nrw){
		echo "<form action='$PHP_SELF' method='POST' name='exclusaoP'>";
		echo "<input type='hidden' name='opt2' value='PE'>";
		echo "<input type='hidden' name='idphp' value=''>";
		echo "<input type='hidden' name='codlogin' value='$codlogin'>";
		echo "</form>";

		for($i=0;$i<$qry->nrw;$i++){
			$qry->navega($i);
					
			echo "<tr ".(($i%2==0)?"":"bgcolor='#eeeeee'").">
                                   <td>".$qry->data["descricao"]."</td><td><a href=# onclick=\"javascript:if(confirm('Deseja remover a permissão ".$qry->data["descricao"]." deste usuário?')){ document.exclusaoP.idphp.value='".$qry->data["idphp"]."';document.exclusaoP.submit(); }\">Remover</td>
                                 </tr>";			
		
		}
	}
	else
	echo "<tr>
                                   <td colspan=2><font color='#FF0000'>Nenhuma permissão setada para este usuario</font></td>
                                </tr>";
	
	echo "<tr>
                          <td colspan=2><br></td>
                        </tr>";
	echo "<tr>
                          <form action='$PHP_SELF' METHOD='POST' name='gravaP'>
                              <input type='hidden' name='codlogin' value='$codlogin'>
                              <input type='hidden' name='opt2' value='PG'>
                             <td>
                                <SELECT name='idphp'>";
	
	$sql = "SELECT idphp,descricao FROM tbphp ORDER BY descricao ASC";
	$qry->executa($sql);
	for($i=0;$i<$qry->nrw;$i++){
		$qry->navega($i);
		
		$qry2->nrw = 0;
		$sql2 = "SELECT idphp FROM tbmenu WHERE codlogin='$codlogin' AND idphp='".$qry->data["idphp"]."'";
		$qry2->executa($sql2);
		
		if(!$qry2->nrw)
		echo "<option value='".$qry->data["idphp"]."'>".$qry->data["descricao"]."</option>";
		
	}
	
	echo "
                                </select>
                             </td>
                             <td><input type=submit value='Adicionar esta permissão'></td>
                         </form>
                        </tr>";
	
	echo "<tr>
                          <td colspan='2'><br></td>
                        </tr>";
	
	echo "<tr>
                          <td colspan='2' align='center'><a href='$PHP_SELF'>Voltar</a></td>
                        </tr>";
	
	break;
	
	
	
	default:
            ?>
    <form name="form_selecao" action="<?=$PHP_SELF;?>" method="POST">
    <input type=hidden name=opt value="I">
      <tr>
         <td colspan="2" align="center">Transportadora&nbsp;:&nbsp;
             <select name="id_transportadora" onChange="javascript:document.form_selecao.id_base.disabled=true;document.form_selecao.opt.value='';document.form_selecao.submit();">
             <?
            echo "<option value='T' ".(($id_transportadora=="T")?"selected":"").">TODOS</selected>";
            combo("SELECT idtransportadora,nometransportadora FROM tbtransportadora ORDER BY nometransportadora",$id_transportadora);
             ?>
             </select>
         </td>
             <?
             if ($id_transportadora > 0 and $id_transportadora!="T"){
             ?>
         <td>
             Base&nbsp;:&nbsp;
             <select name="id_base" onchange="javascript:document.form_selecao.opt.value='';document.form_selecao.submit();">
             <?
             echo "<option value='T' ".(($id_base=="T")?"selected":"").">TODOS</selected>";
             combo("SELECT codbase,nomebase FROM tbbase WHERE idtransportadora = '$id_transportadora' order by nomebase",$id_base);
             }else
             echo "<input type=hidden name=id_base value=''>";//soh pra naum dar erro no javascript
             ?>
             <td>
             <?
             if ($id_base > 0 and $id_base!="T" and $id_transportadora > 0 and $id_transportadora!="T"){
             	echo "&nbsp;<input type=submit value='Incluir Usuário'>";
             }
             ?>
             </td>
      </tr>
      </form>
            
            <?
             $where = (($id_transportadora!="T" and $id_transportadora > 0)?" AND tblogin.idtransportadora = '$id_transportadora'":"");
             $where.= (($id_base!="T" and $id_base > 0)?" AND tblogin.codbase = '$id_base'":"");
             
             $sql = "SELECT tblogin.codlogin,tblogin.nomeusuario,tblogin.nomelogin,tbbase.nomebase FROM tblogin,tbbase WHERE tblogin.codbase=tbbase.codbase $where ORDER BY tblogin.nomeusuario";
             $qry->executa($sql);
             
             if ($qry->nrw){
             	echo "\n<form action='$PHP_SELF' name='operacao' method='POST'>
                      <input type=hidden name='codlogin' value=''>
                      <input type=hidden name='opt' value=''>
            		  <input type=hidden name='opt2' value=''>
              		</form>\n";
             	
             	echo "<tr bgcolor='#cccccc'>
                      <td><b>Nome</td>
                      <td><b>Login</td>
                      <td><b>Base</td>
					  <td nowrap><b>Opções</b></td>
                    </tr>";
             	
             	for($i=0; $i<$qry->nrw; $i++){
             		$qry->navega($i);
             		
             		
             		echo "\n
              		<tr ".(($i%2==0)?"":"bgcolor='#eeeeee'").">
                      <td>".$qry->data["nomeusuario"]."</td>
                      <td>".$qry->data["nomelogin"]."</td>
                      <td>".$qry->data["nomebase"]."</td>";
             		
             		echo   "<td><a href=# onclick=\"javascript:document.operacao.codlogin.value='".$qry->data["codlogin"]."';document.operacao.opt.value='A';document.operacao.submit();\">Alterar</a>&nbsp;&nbsp;
              			  &nbsp;&nbsp;<a href=# onclick=\"javascript:document.operacao.codlogin.value='".$qry->data["codlogin"]."';document.operacao.opt.value='P';document.operacao.submit();\">Permiss&otilde;es</a>&nbsp;&nbsp;
              			  &nbsp;&nbsp;<a href=# onclick=\"javascript:if(confirm('Deseja remover o usuário \'".addslashes($qry->data["nomeusuario"])."\' e suas permissões?')){ document.operacao.codlogin.value='".$qry->data["codlogin"]."';document.operacao.opt.value='';document.operacao.opt2.value='E';document.operacao.submit(); }\" >Excluir</a></td>
                    </tr>";
             		
             	}
             	echo "<tr><td><br></td></tr>";
             }
             else
             $msg="<font color='#FF0000'>Nenhum usu&aacute;rio cadastrado</font>";
             
}
}
else
{
     echo "Tela permitida apenas para usuários master";
}
if ($msg)
echo "<br>$msg";

    ?>
  </table>
</center>
<? $con->desconecta(); ?>
</body>
</html>