<?php
/**
 * Login de Acesso
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega a configuracao
require_once("inc/config.inc");

// pega os campos nos formularios
$cbbasede   = (isset($_POST["cbbasede"  ])) ? $_POST["cbbasede"  ] : "";
$cbbasepara = (isset($_POST["cbbasepara"])) ? $_POST["cbbasepara"] : "";
$codbars    = (isset($_POST["codbars"   ])) ? $_POST["codbars"   ] : "";

// pega o ID do transporte
$idTrasnp = $_SESSION["IDTRANSP"];

// consulta
$qry  = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry6 = new consulta($con);
$qry7 = new consulta($con);

// seta o link atual
$alLink = HOST.$PHP_SELF."?token={$rnd}";

//echo 'UTILIZAR NOVA VERSÃO EM WWW.FASTCOURIER.COM.BR';
//DIE;

// pega o header
require_once("inc/header.inc");
?>
<!-- Script local -->
<script type="text/javascript" src="<?=HOST?>/js/mudanca-base-por-lista.js?token=<?=$rnd?>"></script>

<!-- Style local -->
<!--<link href="tablecloth/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />-->
<style>
  .tabela { width: auto; border: none; border-spacing: 5px; }
  .tabela thead { padding: 3px; }
  .tabela tbody { padding: 3px; }
</style>

<center>

<div class="box" id="box">

  <?php
    switch($act){
           case("B"):
            if ($cbbasede && $cbbasepara && $cbbasede != $cbbasepara){

             $sql = "SELECT * FROM tbentrega WHERE (numlista = '{$codbars}')" ;
             $qry->executa($sql);
             if ($qry->nrw){
                 $sql = "UPDATE tbentrega SET codbase = '{$cbbasepara}' WHERE  numlista = '{$codbars}'";
                 $qry->executa($sql);
				 
				 $sql = "UPDATE tblista SET codbase = '{$cbbasepara}' WHERE numlista = '{$codbars}'";
                 $qry->executa($sql);
				 
				 
                 echo "Alteração realizada com sucesso";
             }
             else
                 echo "Item não encontrado nessa base";
          }
          else
              echo "Favor preencher as bases corretamente";


           default:
  ?>

<form name="form" action="<?=$alLink?>" method="post" onsubmit="return validar(this)">  
<input type="hidden" name="act"              value="B" />
<input type="hidden" name="idtransportadora" value="<?=$idTrasnp?>" />

<table class="tabela">
<thead> 
<tr>
    <td>Base De</td>
    <td>
        <select name="cbbasede">
            <?php combo("select codbase, nomebase FROM tbbase where (idtransportadora = '{$idTrasnp}') and ativa = 'S'  ORDER BY nomebase");?>
        </select>
    </td>
    <td>Base Para</td>
    <td>
        <select name="cbbasepara">
            <?php combo("select codbase, nomebase FROM tbbase where (idtransportadora = '{$idTrasnp}') and ativa = 'S'  ORDER BY nomebase");?>
        </select>
    </td>
</tr>
</thead>
<tbody>
<tr><td colspan="2"></td></tr>
<tr>
    <td>N&uacute;mero da Lista</td>
    <td><input type="text" name="codbars" align="left" size="10" value="" /></td>
    <td><input type="submit" class="submit" value="Mudar" /></td>
</tr>
</tbody>
</table>

</form>

<?php } ?>

</div>

</center>


<?php
// pega o Footer
require_once("inc/footer.inc");