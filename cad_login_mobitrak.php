<?php
/**
 * Login de Acesso
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel
$prgTit = "Cadastro Login Mobitrak";

// pega a configuracao
require_once("inc/config.inc");

// pega os parametros
$id     = (isset($_REQUEST["id"    ])) ? $_REQUEST["id"    ] : "";
$login  = (isset($_REQUEST["login" ])) ? $_REQUEST["login" ] : "";
$nome   = (isset($_REQUEST["nome"  ])) ? $_REQUEST["nome"  ] : "";
$cidade = (isset($_REQUEST["cidade"])) ? $_REQUEST["cidade"] : "";
$uf     = (isset($_REQUEST["uf"    ])) ? $_REQUEST["uf"    ] : "";
$projeto     = (isset($_REQUEST["projeto"    ])) ? $_REQUEST["projeto"    ] : "";
$msg    = "";

// seta as consultas
$qry  = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry6 = new consulta($con);

switch($opt2) {
	case("IG"):
	if (!empty($login)) {
		$sql = "SELECT * FROM tb_easy_courier_login
		        WHERE login  = '{$login}'";
		        $qry->executa($sql);
		if (!$qry->nrw) {
			$sql  = "INSERT INTO tb_easy_courier_login (login, nome, cidade, uf,projeto)
			                                     VALUES('{$login}', '{$nome}', '{$cidade}', '{$uf}','{$projeto}')";
			$qry->executa($sql);
			$msg = "Login incluido com sucesso";
		}
		else {
			$msg = "Login já existe";
		}
	}
	else { $opt = "I"; }
	break;
	
	case("AG"):
	$sql = "SELECT * FROM tb_easy_courier_login WHERE  (id_login = '{$id}') ORDER BY nome ASC";
	$qry->executa($sql);
	
	if ($qry->nrw) {
        // atualizacao
		$sql = "UPDATE tb_easy_courier_login
	               SET login    = '{$login}' ,
                       nome     = '{$nome}'  ,
                       cidade   = '{$cidade}',
	                   uf       = '{$uf}',
	                   projeto  = '{$projeto}'
                WHERE (id_login = '{$id}')";
		$qry->executa($sql);
	}
	$opt = "";
	break;
}

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>

<!-- CSS local -->
<link href="<?=HOST?>/estilo.css"                rel="stylesheet" type="text/css" />
<link href="<?=HOST?>/tablecloth/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />

<!-- Script local -->
<script type="text/javascript" src="<?=HOST?>/tablecloth/tablecloth.js?token=<?=$rnd?>"></script>
	<?php if(!empty($msg)):?>
		<?php echo "<div style='background-color:#f7f7f9; text-align:center;font-weight:bold; padding:10px; border:1px solid #e1e1e8'>".$msg."</div>" ;?>
	<?php endif ?>
<table>
  
<?php
switch($opt) {
	case("I"):
	echo "<form action='{$selfLink}' method='post'>
        <input type='hidden' name='opt2' value='IG'>
		
		<tr>
        <td>Login (cpf)</td><td width='15' align='center'>:</td>
        <td><input type='text' name='login' value='".$qry->data["login"]."' /></td>
        </tr>
        
         <tr>
        <td>Nome do Usuário</td><td width='30' align='center'>:</td>
        <td><input type='text' name='nome' value='".$qry->data["nome"]."' /></td>
        </tr>
        
        <tr>
        <td>Cidade</td><td width='5' align='center'>:</td>
        <td><input type='text' name='cidade' maxlength='40' value='".$qry->data["cidade"]."' /></td>
        </tr>
        
        <tr>
        <td>Estado</td><td width='5' align='center'>:</td>
        <td><input type='text' name='uf' maxlength='2' size='2' value='".$qry->data["uf"]."' /></td>
        </tr> 
        
        <tr>
        <td>PROJETO (INSIRA 1 PARA ENTREGADORES OU 2 PARA NOVA RA)</td><td width='1' align='center'>:</td>
        <td><input type='text' name='projeto' maxlength='2' size='1' value='".$qry->data["projeto"]."' /></td>
        </tr> 
        
        
        <tr>
        <td height='40' align='center' colspan='3'>
		    <input type='submit' class='submit' value='Incluir' />&nbsp;&nbsp;
			<input type='button' class='cancel' onclick='window.location.href=\"{$selfLink}\"' value='Cancelar' /></td>
        </tr>
        </form>
        ";
	break;
	
	
	case("A"):
	$sql = "SELECT * FROM tb_easy_courier_login where id_login = '$id'";
	
	
	$qry->executa($sql);
	
	echo "<form action='{$selfLink}' METHOD=POST>
        <input type=hidden name=opt2 value='AG' />
        <input type=hidden name=id value='$id' />
	   
        <tr>
        <td>Login (CPF.)</td><td width='15' align='center'>:</td>
        <td><input type=text name=login value='".$qry->data["login"]."'></td>
        </tr>
        
        <tr>
        <td>Nome do Usuário</td><td width='30' align='center'>:</td>
        <td><input type=text name=nome value='".$qry->data["nome"]."'></td>
        </tr>
       
        <tr>
        <td>Cidade</td><td width='5' align='center'>:</td>
        <td><input type=text name=cidade value='".$qry->data["cidade"]."'></td>
        </tr>
       
        <tr>
        <td>Estado</td><td width='2' align='center'>:</td>
        <td><input type=text name=uf maxlength =2  size=2 value='".$qry->data["uf"]."'></td>
        </tr>

<tr>
        <td> PROJETO (INSIRA 1 PARA ENTREGADORES OU 2 PARA NOVA RA)</td><td width='1' align='center'>:</td>
        <td><input type=text name=projeto maxlength =1  size=1 value='".$qry->data["projeto"]."'></td>
        </tr>        
        
        
       
        
        <td height='40' align='center' colspan='3'>
		    <input type='submit' class='submit' value='Alterar' />&nbsp;&nbsp;
			<input type='button' class='cancel' onclick=\"window.location.href='{$selfLink}'\" value='Cancelar' /></td>
        </tr>
        </form>
                          ";
	
	break;
	
	default:
	
 case("X"):
 


 
	
	
      $sql = "SELECT * from tb_easy_courier_login order by  nome";
  
//echo $sql;

	$qry->executa($sql);
	
	if ($qry->nrw){
  
	  $quantreg = $sql_conta;
	  
	  
	  
  	echo "\n<form action='{$selfLink}' name='operacao' method='post'>
          <input type=hidden name=id value=''>
          <input type=hidden name=opt value=''>
   	  	  <input type=hidden name=opt2 value=''>
    		  </form>\n";
?>
   
      <form action="<?=$selfLink?>" method="post">
            <input type=hidden name="opt" value="I" />
            <button type="submit" class="submit">INCLUIR NOVO USUÁRIO</button>
      </form>
        
<?php
		echo "<tr bgcolor='#cccccc'>
                      <th><b>id</b></th>
                      <th><b>Login (CPF)</b></th>
                      <th><b>Nome</b></th>  
              		  <th><b>Cidade</b></th>
              		  <th><b>UF</b></th>
              		  <th><b>Projeto</b></th>
					  <th><b>Operacao</b></th>
                      </tr>";
		
    
   
    for($i=0; $i<$qry->nrw; $i++){
			$qry->navega($i);
			
			echo "\n
              		<tr ".(($i%2==0)?"":"bgcolor='#eeeeee'").">
                      <td>".$qry->data["id_login"]."</td>           
                      <td>".$qry->data["login"]."</td>
                      <td>".$qry->data["nome"]."</td>
                      <td>".$qry->data["cidade"]."</td>
                      <td>".$qry->data["uf"]."</td>
                       <td>".$qry->data["projeto"]."</td>
                      
                      ";
                      
                      
			
			echo   "<td><a href=# onclick=\"javascript:document.operacao.id.value='".$qry->data["id_login"]."';
			         document.operacao.opt.value='A';document.operacao.submit();\">Alterar</a>&nbsp;&nbsp;
              			 

                    </tr>";
			
		}
		
	}
	else
	$msg="<font color='#FF0000'>Nenhuma Lotação cadastrada</font>";
  break;	
}
    
	
	// seta o numero de colunas
	$coluna = (($opt == "I") || ($opt == "A")) ? "3" : "6";
	
	
	?>
 

  </table>
      


<?php
// pega o Footer
require_once("inc/footer.inc");