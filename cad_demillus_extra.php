<?
// pega a configuracao
require_once("inc/config.inc");

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry6 = new consulta($con);

$msg = "";

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
if(isset($valor))
  $valor = str_replace(',','.',$valor);
switch($opt2){
	
	
	case("IG"):
    $campFormat = $campanha."/".$ano;
	
              $diaemissao = substr($data,0,2);
              $mesemissao = substr($data,3,2);
              $anoemissao = substr($data,6,4);
			  $data  = $anoemissao.'/'. $mesemissao.'/'.$diaemissao;
	
			$sql = "INSERT INTO tb_demillus_extra 
             ( codbase,
                campanha,
                valor,
                historico,
                data)";
                            
			$sql .= " VALUES(
                '$codbase',
                '$campFormat',
                '$valor',
                '$historico',
                '".date('Y-m-d')."'   )";
			
			$qry->executa($sql);
			
				
				$msg = "Operação realizada com sucesso";
				
		

	$opt = "I";
	break;
	
	
	case("AG"):
  $campFormat = $campanha."/".$ano;
	$sql = "SELECT * FROM tb_demillus_extra 
          WHERE  id = '$id' ";
	$qry->executa($sql);
	
   $diaemissao = substr($data,0,2);
              $mesemissao = substr($data,3,2);
              $anoemissao = substr($data,6,4);
			  $data  = $anoemissao.'/'. $mesemissao.'/'.$diaemissao;
	
	if ($qry->nrw ){
	  	$sql = "UPDATE tb_demillus_extra SET
      
       codbase = '$codbase',
       campanha         = '$campFormat',
       valor        = '$valor',
       historico    = '$historico',
       usuario = '".$_SESSION['USER']."',
       data = '".date('Y-m-d')."'
       WHERE id = '$id'";
    	 $qry->executa($sql);

	}
	break;
	
	
	$opt = "";
	break;
}

?>
<html>
<head>


<title>..:: Cadastro de Extras ::..</title>
</head>
<body>
 <div align="center">
  <table class="tabela" align="center" style="width: 800px" cellspacing=1 cellpadding=0 align=left border=0>

    
    
    
<link href="estilo.css" rel="stylesheet" type="text/css">
<link href="tablecloth/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="tablecloth/tablecloth.js"></script>

    
  
    <?
switch($opt){
	case("I"):
  $codbaseGet = 1;
  if(isset($_GET['codbase']))
    $codbaseGet = $_GET['codbase'];
  $campanhaGet = '';
  if(isset($_GET['campanha']))
    $campanhaGet = $_GET['campanha'];
  $anoGet = '';
  if(isset($_GET['ano']))
    $anoGet = $_GET['ano'];

	echo "<form action='$PHP_SELF' METHOD=POST>
        <input type=hidden name=opt2 value='IG'>
         
        <tr>
        <td>Selecione a Base</td><td width='5' 'align=center'>:</td>";
        ?> <td align="left">
			    <select name='codbase' style='width:175px;>
			    <?php combo("SELECT tb.codbase,tb.nomebase FROM tbdireitoauditoria as dir, tbbase as tb  WHERE dir.codbase = '".$_SESSION['IDBASE']."' and tb.idtransportadora='".$_SESSION['IDTRANSP']."' and tb.ativa = 'S'  and tb.codbase = dir.codbasedireito ORDER BY tb.nomebase",$codbaseGet) ;?>
          </select></td>
          
          <?
        
         echo "</tr>
        <tr>
        <td>Campanha</td><td width='5' 'align=center'>:</td>
        <td><input type=text name=campanha value=".$campanhaGet."></td>
        </tr>

        <tr>
        <td>Ano</td><td width='5' 'align=center'>:</td>
        <td><input type=text name=ano value=".$anoGet."></td>
        </tr>
        
        <tr>
        <td>Valor</td><td width='5' 'align=center'>:</td>
        <td><input type=text name=valor value='".mostra_data($qry->data["valor"])."'></td>
        </tr>
        
        <tr>
        <td>Histórico</td><td width='50' 'align=center'>:</td>
        <td><input type=text size = 100 name=historico value='".$qry->data["historico"]."'></td>
        </tr>
        
        <tr>
        <td height='40' align='center' colspan='3'><input type='submit' value='Incluir'>&nbsp;&nbsp;<input type='button'
			  onclick=\"javascript:document.location.href='$PHP_SELF'\" value='Cancelar'></td>
        </tr>
        </form>
        ";
	break;
	
	
	case("A"):
	$sql = "SELECT * FROM tb_demillus_extra 
          where id = '$id' order by data";
	
	
	$qry->executa($sql);
  
	if(!empty($qry->data["campanha"])){
    $campanhaAlt =  explode("/",$qry->data["campanha"]);
  }

  if(empty($codbaseGet))
    $codbaseGet = $qry->data['codbase'];


	echo "<form action='$PHP_SELF' METHOD=POST>
        <input type=hidden name=opt2 value='AG'>
        <input type=hidden name=id value='$id'>
        
        
        <td>Selecione a Base</td><td width='5' 'align=center'>:</td>";
        ?> <td align="left">
			    <select name='codbase' style='width:175px;>
			    <? combo("SELECT tb.codbase,tb.nomebase FROM tbdireitoauditoria as dir, tbbase as tb  WHERE dir.codbase = '".$_SESSION['IDBASE']."' and tb.idtransportadora='".$_SESSION['IDTRANSP']."' and tb.ativa = 'S'  and tb.codbase = dir.codbasedireito ORDER BY tb.nomebase",$codbaseGet);?>
          </select></td>
          
          <?
        
         echo "</tr>

        <tr>
        <td>Campanha</td><td width='5' 'align=center'>:</td>
        <td><input type=text name=campanha value='".$campanhaAlt[0]."'></td>
        </tr>

        <tr>
        <td>Ano</td><td width='5' 'align=center'>:</td>
        <td><input type=text name=ano value='".$campanhaAlt[1]."'></td>
        </tr>
   
        <tr>
        <td>Valor</td><td width='5' 'align=center'>:</td>
        <td><input type=text name=valor value='".$qry->data["valor"]."'></td>
        </tr>
        
        <tr>
        <td>Histórico</td><td width='50' 'align=center'>:</td>
        <td><input type=text size = 100 name=historico value='".$qry->data["historico"]."'></td>
        </tr>
        
       
        
        <td height='40' align='center' colspan='3'><input type='submit' 
			  value='Alterar'>&nbsp;&nbsp;<input type='button' onclick=\"javascript:document.location.href='$PHP_SELF'\" value='Cancelar'></td>
        </tr>
        </form>

                          ";
	
	
	
	
	break;
	
	
	default:
	
 
 ?>
    <tr>
      <td align="center" colspan="9">
        <form action='<?=$PHP_SELF;?>' method=POST>

          <select name='codbase' style='width:175px;'>
            <?php combo("SELECT tb.codbase,tb.nomebase FROM tbdireitoauditoria as dir, tbbase as tb  WHERE dir.codbase = '".$_SESSION['IDBASE']."' and tb.idtransportadora='".$_SESSION['IDTRANSP']."' and tb.ativa = 'S'  and tb.codbase = dir.codbasedireito ORDER BY tb.nomebase",$codbaseGet);?>
          </select>
          <input type=submit name="enviar" value="Pesquisar"><br><br>
        </form>
        <form action='<?=$PHP_SELF;?>' method=POST>
              <input type=hidden name=opt value="I">
              <input type=submit name="enviar" value="Incluir ">
        </form>
        </td>
    </tr>
</table><br/>
<table class="tabela" align="center" style="width: 800px" cellspacing=1 cellpadding=0 align=left border=0>
<?php
$numreg = 10;
 if (!isset($pg)) 
 {              
   $pg = 0; 
 }    
 $inicial = @$_GET['pg'] * $numreg;
        
 $sql = "SELECT count(id) as total from tb_demillus_extra ";
 $qry->executa($sql);
 $sql_conta = $qry->data["total"];
 
if(isset($_POST['enviar'])){
   $sql = "SELECT * from tb_demillus_extra where visivel isnull AND codbase = '".$_POST['codbase']."'
          order by data DESC";
        }else{
$sql = "SELECT * from tb_demillus_extra where visivel isnull
          order by data DESC LIMIT 200";
        }
 
	        $qry->executa($sql);
	
	if ($qry->nrw){
  
	  
	  
           

	  
  	echo "\n<form action='$PHP_SELF' name='operacao' method='POST'>
          <input type=hidden name=id value=''>
          <input type=hidden name=opt value=''>
   	  	  <input type=hidden name=opt2 value=''>
    		  </form>\n";
		
		echo "<tr bgcolor='#cccccc'>
                      <th><b>id</b></th>
                    <th><b>Base</b></th>  
              		  <th><b>Data</b></th>
              		  <th><b>Valor</b></th>
                    <th><b>Voucher</b></th>
              		  <th><b>Histórico</b></th>
                      <th nowrap><b>Opções</b></th>
                    </tr>";
		
    
   
    for($i=0; $i<$qry->nrw; $i++){
			$qry->navega($i);
			
      
      $base = $qry->data["codbase"];
    $sql2="SELECT 
           nomebase FROM tbbase 
           where codbase = $base";
           $qry6->executa($sql2);
           
      
      
			echo "\n
              		<tr ".(($i%2==0)?"":"bgcolor='#eeeeee'").">
                      <td>".$qry->data["id"]."</td>           
                      <td>".$qry6->data["nomebase"]."</td>
                      <td>".mostra_data($qry->data["data"])."</td>
                      <td>".$qry->data["valor"]."</td>
                      <td>".$qry->data["voucher"]."</td>
                      <td>".$qry->data["historico"]."</td>";
                      
			
			echo   "<td><a href=# onclick=\"javascript:document.operacao.id.value='".$qry->data["id"]."';
			         document.operacao.opt.value='A';document.operacao.submit();\">Alterar  </a>&nbsp;&nbsp;
              			 

                    </tr>";
			
		}
		
	}
	else
	$msg="<font color='#FF0000'>Nenhum registro localizado</font>";
	
}

    ?>
    

    
  </table>
      
  
</div>
</body>
</html>