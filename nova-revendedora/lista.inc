<?php
/**
 * Tela de Nova Revendedora - Lista
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega o icone da camera
$imgFoto = "<img src='".HOST."/inc/img/ic_camera.png' title='Exibir Foto'    alt='' />";
$imgRefr = "<img src='".HOST."/inc/img/ic_reload.png' title='Atualizar Tela' alt='' />";
 
// cria o modulo de consulta
$lqry = new consulta($con);
$pqry = new consulta($con);
$pqrr = new consulta($con);

// monta a query de lista
$lsql = "SELECT id          ,
                nr_encomenda,
                datacliente,
                tb_easy_courier_login.nome as promotora
				
         FROM   tb_easy_courier,tb_easy_courier_login where tb_easy_courier.login = tb_easy_courier_login.login
		 and (id_ocorrencia = 33)
		 ORDER BY datacliente DESC";
// executa a consulta
$lqry->executa($lsql);
?>

<!-- Atuaiza a tela -->
<div style="clear: both;" align="right">
     <a href="<?=$reLink?>"><?=$imgRefr?></a>
</div>

<!-- monta a tabela de lista -->
<table class="tabela">
<tr>
  <th>CPF</th>
  <th>NOME DA PROMOTORA</th>
  <th>Data</th>
  <th>Setor</th>
  <th>Lista</th>
  <th>Status</th>
</tr>
<?php
$arrValores = array();

// percorre o resultado da query
for($i = 0; $i < $lqry->nrw; $i++) {
 $status='';  
 
$cpf = $lqry->data["nr_encomenda"];

   
	$lsql = "SELECT idexterno,numlista,numlotecliente,
                dataemissao,st
                
         FROM   tbentrega
		 WHERE (cpf = $cpf)";
		 
// executa a consulta
$pqry->executa($lsql);

$st = $pqry->data["st"];

if ($pqry->nrw)
	$status = "<span style='color:red; font-weight:bold'> PEDIDO JA EXISTE :  ".$pqry->data["idexterno"]."  ".$st." </span>";
else
	$status = "AGUARDANDO...";

	 
if ( $pqry->nrw and $st == "E") 
		$status="<span style='color:green; font-weight:bold'>FINALIZADO(E)</span>";
elseif($pqry->nrw and $st == "D")  
	$status="<span style='color:green; font-weight:bold'>FINALIZADO(D)</span>";
	
	
	

	
	// seta a classe de zebra
    $xclasse = (($i % 2) == 0) ? "" : "class='zebra'";

    // navega
    $lqry->navega($i);
	
	//if ( !$pqry->nrw  or ($pqry->nrw and $st == '' )) 
	//{

    // seta o link
    
    
    $atlink = "<a href='javascript:void()' onclick='exibeMapa({$lqry->data["id"]},\"{$lqry->data["nr_encomenda"]}\")'>{$lqry->data["nr_encomenda"]}</a>";
    $arrValores[] = array(
    	'link' => $atlink,
    	'promotora' => $lqry->data["promotora"],
    	'datacliente' => $lqry->data["datacliente"],
    	'numlotecliente' => $pqry->data["numlotecliente"],
    	'numlista' => $pqry->data["numlista"],
    	'status' => $status
    );
	?>
<?php } ?>
	<?php
	foreach($arrValores as $val){ 

	?>
		<!-- mostra o resultado -->
	    <tr onmouseover="fLightTr(this,'#CCE1F0')" onmouseout="fLightTr(this,this.bgColor)" <?=$xclasse?>>
	      <td><?=$val['link']?></td>
	      <td align="center"><?=$val['promotora']?></td>
	      <td align="center"><?=mostra_data($val['datacliente'], 1)?></td>
	      <td align="left"><?=$val['numlotecliente']?></td>
	      <td align="left"><?=$val['numlista']?></td>
		  <td align="left"><?=$val['status']?></td>
	    </tr>
    <?php
    }
    ?>

</table>
