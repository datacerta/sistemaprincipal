<?php
/**
 * Login de Acesso
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta o prg
$prg    = "login";
$prgTit = "Login de Acesso";

// pega a configuracao
require_once("inc/config.inc");

// verifica a acao
if ($act == "entrar" ) {
    // pega os campos
  	$login  = (isset($_POST["txlogin"])) ? $_POST["txlogin"] : "";
    $senha  = (isset($_POST["txsenha"])) ? $_POST["txsenha"] : "";
    $login  = Util::limpaLogin($login);
    $asenha = Util::limpaSenha($senha);

    // monta a conexao para a consulta
    $qry  = new consulta($con);
	$data = new consulta($con);
 
    // executa a consulta
	$data->executa("SELECT nomelogin       ,
	                       codlogin        ,
                           idtransportadora,
						   codbase         ,
						   codcliente      ,
						   coddepartamento 
                    FROM   tblogin
					WHERE (nomelogin = '{$login}' )
                      AND (senha     = '{$asenha}')");
	
	if ($data->nrw > 0) {
		if ($_SESSION['USER'])      { FechaSessao($_SESSION['USER']); }
		if ($_SESSION['IDCOURIER']) { unset($_SESSION['IDCOURIER']);  }

        $ll = $data->data["codlogin"];
        $bb = $data->data["codbase"];
	
        // faz o login e cria a sessao
		CriaSessao($data->data["nomelogin"], 
                   $data->data["codlogin"], 
                   $data->data["idtransportadora"], 
                   $data->data["codbase"],
                   $data->data["codcliente"],
                   $data->data["coddepartamento"]);

        // atualiza a tabela de login				   
        $sql = "UPDATE tblogin SET data_login = NOW() WHERE (codlogin = {$ll})";
        $qry->executa($sql);

        // redireciona
  		header("Location: index.php?token={$rnd}");
	}
	else {
		// Aviso de erro
	    header("Location: aviso.php?ider=1&token={$rnd}");
		exit();
	}
}




// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");


     

?>

<!-- Script local -->
<script type="text/javascript" src="<?=HOST?>/js/login.js?token=<?=$rnd?>"></script>

<!-- BOX Login de Acesso -->
<div class="boxLogin" id="boxLogin">
    <!-- Titulo -->
    <div class="title">Login de Acesso</div>

	<!-- formulario -->
	<form action="<?=$selfLink?>"  name="form1" method="post" onsubmit="return validar(this)">
	<input type="hidden" name="act" id="act" value="entrar" />
	
	<table>
	<tr>
	  <td style="width: 70px;"><label>Usu&aacute;rio:</label></td>
	  <td><input type="text" class="campo" name="txlogin" id="txlogin5" /></td>
	<tr>
	<tr>
	  <td style="width: 70px;"><label>Senha:</label></td>
	  <td><input type="password" class="campo" name="txsenha" id="txsenha" placeholder="Somente n&uacute;meros" /></td>
	<tr>
	<tr><td colspan="2"></td></tr>
	<tr>
	  <td colspan="2">
	      <a href="buscar_ra.php" name="ra" id="ra" form.form1.act.value="ra" class="left">Esqueci minha senha</a>
		  
		  
		  
		  
		  
	      <button type="submit" class="submit right">Entrar</button>
	  </td>
	</tr>
	
	
	</table>
	</form>
</div>

<?php
// pega o Footer
require_once("inc/footer.inc");