<?php
/**
 * MobData - Telemetria
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel
$prgTit = "Mobidata - Telemetria";

// pega a configuracao
require_once("inc/config.inc");
require_once("classes/calendar.php"); 

// consulta
$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry6 = new consulta($con);
$qry_online = new consulta($con);
$qry_realizadas = new consulta($con);
$qry_devolvidas = new consulta($con);

$msg = "";



switch($opt2){
	
	
	
	
	
	
	case("AG"):
	$sql = "select count(idgeo),tb_easy_courier_login.login  as cpf  ,tb_easy_courier_login.nome from tb_easy_courier_geo,tb_easy_courier_login  
            where  tb_easy_courier_geo.login = tb_easy_courier_login.login  group by tb_easy_courier_login.login order by nome";
	$qry->executa($sql);
	

	
	if ($qry->nrw ){
	  	$desc_lotacao = strtoupper($desc_lotacao);
      $cod_lotacao = strtoupper($cod_lotacao);
      $endereco = strtoupper($endereco);
      
      $sql = "UPDATE tb_amil_lotacao SET
      nome_empresa  = '".$nomeempresa."',
      cod_empresa   = '".$codempresa."',
      cod_filial    = '".$cod_filial."',
      cod_lotacao   = '".$cod_lotacao."',
      desc_lotacao  = '".$desc_lotacao."',
      endereco      = '".$endereco."',
      bairro        = '".$bairro."',
      cidade        = '".$cidade."',
      uf            = '".$uf."',
      cep           = '".$cep."',
      complemento   = '".$complemento."',
      responsavel   = '".$responsavel."'
      
      
      
      WHERE id = '$id'";
  		$qry->executa($sql);
  	
	}
	break;
	
	
	$opt = "";
	break;
}

$ativo_ = "";

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>

<!-- CSS Local -->
<link rel="stylesheet" type="text/css" media="all" href="<?=HOST?>/datetime/calendar-blue.css" title="blue" />

<!-- JS Local -->
<script type="text/javascript" src="<?=HOST?>/datetime/calendar.js?token=<?=$rnd?>"></script>
<script type="text/javascript" src="<?=HOST?>/datetime/lang/calendar-br.js?token=<?=$rnd?>"></script>
<script type="text/javascript" src="<?=HOST?>/datetime/calendar-setup.js?token=<?=$rnd?>"></script>

<!-- JS Google -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
   

<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script type="text/javascript" src="<?=HOST?>/dist/js/bootstrap.min.js?token=<?=$rnd?>"></script>
    
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script type="text/javascript" src="<?=HOST?>/dist/js/ie10-viewport-bug-workaround.js?token=<?=$rnd?>"></script>
	<link href="<?=HOST?>/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?=HOST?>/DataTables/css/jquery.dataTables.css" rel="stylesheet" />
    <script type="text/javascript" src="<?=HOST?>/DataTables/js/jquery.dataTables.js?token=<?=$rnd?>"></script>

	
    
        <script>
		
		 $(document).ready(function () {
             $("#tblResultado").dataTable({
                 scrollY: 150,
				 scrollX: "99%",
				 "paging": false,
                 "language": {
                     "lengthMenu": "_MENU_ Registros por p&aacute;gina",
                     "zeroRecords": "Nenhum registro encontrado",
                     /*"info": "Página _PAGE_ de _PAGES_",*/
					 "info": "",
                     "infoEmpty": "Nenhum registro encontrado.",
                     "decimal": ",",
                     "thousands": ".",
                     "paginate": {
                         "first": "Primeiro",
                         "last": "&Uacute;ltimo",
                         "next": "Pr&oacute;ximo",
                         "previous": "Anterior"
                     },
                     "search": '<?php if($ativo=='s')
		{
		  echo '<button type="button" style=z-index:10000 class="btn  btn-danger btn-xs" onclick=verOnline("n")>Ver Todos</button>&nbsp;&nbsp;&nbsp;';
		  //"		  <input type='checkbox' name=ativo id=ativo value=1 checked />";
		} else {
			echo '<button type="button" class="btn  btn-success btn-xs" onclick=verOnline("s")>Ver Somente Online</button>&nbsp;&nbsp;&nbsp;'; 
			//" <input type='checkbox' name=ativo id=ativo value=1 />";
		} ?><a href="#" class="btn btn-warning btn-xs"  onclick="location.reload();">Atualizar P&aacute;gina</a>&nbsp;&nbsp;&nbsp; Filtrar:',
                     "infoFiltered": "(_MAX_ total de registros)"

                 },
                 "aoColumns": [
                     null,
                     null,
                     null,
                     null,
					 null,
					 null,
					 null,
					 null,
					 null,
                     { "bSortable": false }
                 ]
             });
			 
			 

			$("#ativo").on("click", function(){
				
				if($("#ativo").is(":checked"))
				{
					location.href='trak_courier_select.php?ativo=s&token=<?=$rnd?>';
				} else {
					location.href='trak_courier_select.php?ativo=n&token=<?=$rnd?>';
				}
				//$("#ativo").submit();
			});
			
			
			
			$("#ativo").on("click", function(){
				
				if($("#ativo").is(":checked"))
				{
					location.href='trak_courier_select.php?ativo=s&token=<?=$rnd?>';
				} else {
					location.href='trak_courier_select.php?ativo=n&token=<?=$rnd?>';
				}
				//$("#ativo").submit();
			});
	
			 
		 });
		 
	 
	function verOnline(n){
		
		location.href='trak_courier_select.php?ativo='+n+"&token=<?=$rnd?>";
	}
	
		
	
	
	function verMapa(cpf, id){
        url = 'rastreamento_courier.php?opt=S&login='+cpf+'&data='+$('#data_ate_'+id).val()+'';
		
		window.open(url,'conteudoMapa');
    }
	
	function verImagens(cpf, id){
        url = 'mostra_imagens.php?opt=S&cpf='+cpf+'&data='+$('#data_ate_'+id).val()+'';
		
		window.open(url,'conteudoMapa');
    }

	
/**
 * Funcao de inicializacao
 */
function init() { resizeWin(); }
	
/**
 * Funcao que recalculo
 */
function resizeWin() {
	// seta a altura do BODY
    var bodyHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

	// acerta o tamanho
	dg("conteudoMapa").style.height = (bodyHeight - 440) + "px"
}
	

 </script>

 
 <table id="tblResultado" class="table compact" style="font-size:12px;" width="100%" cellspacing=1 cellpadding=0 border=0>
	<thead>
    <?

switch($opt){
	case("I"):

	break;
	
	
	case("A"):
	
	
	
	
	
	break;
	
	
	default:
	
 case("X"):
 
	$where = "";
    if($ativo == 's')
	{
		$where = "and datacliente >= CURRENT_DATE";
	}
	
    $sql = "select count(nr_encomenda) as registros,tb_easy_courier_login.login as cpf ,tb_easy_courier_login.nome,cidade from tb_easy_courier,tb_easy_courier_login 
where tb_easy_courier.login = tb_easy_courier_login.login ".$where." group by tb_easy_courier_login.login order by nome";
   
//echo $sql;
//echo "<strong>VER SOMENTE ONLINE</strong> <input type='checkbox' name=ativo value=1 />";


	$qry->executa($sql);
	
	if ($qry->nrw){
  

	  

	  
  	echo "\n<form action='$PHP_SELF' name='operacao' id='operacao' method='POST'>
          <input type=hidden name=id value=''>
          <input type=hidden name=opt value=''>
   	  	  <input type=hidden name=opt2 value=''>";
		
		
    		echo "</form>\n";
		
		echo "<tr bgcolor=#303231 style='color:#fff' align = left>
                     
                      <th style='color:#fff'><b>LOGIN (CPF)</b></th>
					  <th style='color:#fff'><b>NOME</b></th>  
					  <th style='color:#fff'><b>STATUS</b></th>
					  <th style='color:#fff'><b>REALIZADAS</b></th>
					  <th style='color:#fff'><b>DEVOLVIDAS</b></th>
					  <th style='color:#fff'><b>IMAGENS</b></th>
					  <th style='color:#fff'><b>TELEMETRIA</b></th>
					  <th style='color:#fff'><b>&Uacute;LTIMO SINAL</b></th>
                      <th style='color:#fff'><b>CIDADE</b></th>
					  <th><b>DATA</b></th>
			  </tr>
			  </thead>
			  <tbody>";
		
   
   
    for($i=0; $i<$qry->nrw; $i++){
			$qry->navega($i);
			
			//Verifica se o usuário esta online
			$sql = "select datacliente  from tb_easy_courier
			where  tb_easy_courier.login = '".$qry->data["cpf"]."'  and datacliente >= CURRENT_DATE";
        	$qry_online->executa($sql);
			if ($qry_online->nrw)
			   {
			   $status = 'ON LINE';
			   $cor = '#33CC00';
			   }
			else   
			   {
			   $status = 'OFF LINE';
			   $cor = '#FF000';
			   }
			   
			//Verifica a quantidade ja entregue
			$sql = "select datacliente  from tb_easy_courier
			where  tb_easy_courier.login = '".$qry->data["cpf"]."'  and datacliente >= CURRENT_DATE and id_ocorrencia=9";
        	$qry_realizadas->executa($sql);
			if ($qry_realizadas->nrw)   
			    $realizadas =  $qry_realizadas->nrw;
			else	
			   $realizadas=0;
			   
			
			//Verifica a quantidade DEVOLVIDA'
			$sql = "select datacliente  from tb_easy_courier
			where  tb_easy_courier.login = '".$qry->data["cpf"]."'  and datacliente >= CURRENT_DATE and id_ocorrencia<>9";
        	$qry_devolvidas->executa($sql);
			if ($qry_devolvidas->nrw)   
			    $devolvidas =  $qry_devolvidas->nrw;
			else	
			   $devolvidas=0;
			   
			   
			//VERIFICA O ULTIMO SINAL   
			$sql = "select datacliente  from tb_easy_courier
			where  tb_easy_courier.login = '".$qry->data["cpf"]."'  order by datacliente desc limit 1";
        	$qry_online->executa($sql);   
			if ($qry_online->nrw)
			   $ultimo_sinal = mostra_Data($qry_online->data["datacliente"],1);
			else   
			   $ultimo_sinal = "NUNCA";

			
			   $cpf = $qry->data["cpf"];
			
			echo "\n
              		<tr ".(($i%2==0)?"":"bgcolor='#eeeeee'").">";
			?>
					  <td><a href="#" onClick="verMapa('<?php echo $cpf; ?>', <?php echo $i; ?>)"><?php echo $qry->data["cpf"]; ?></a></td>
			<?php
			echo "<td>".$qry->data["nome"]."</td>";
			echo " <td><font color=$cor><b>".$status."</b></font></td>
				   <th><b>$realizadas</b></th>
				   <th><b>$devolvidas</b></th>";
			?>	   
				   <td><a href="#" onClick="verImagens('<?php echo $cpf; ?>', <?php echo $i; ?>)">IMAGENS</a></td>
			<?php	   
    	    echo " <td>".$qry->data["registros"]."</td>
				   <th><b>$ultimo_sinal</b></th>			    
                   <td>".$qry->data["cidade"]."</td>
					  
					  
					  
					  
                      
                      ";
					 
					  ?>
					  
					  <td><input type="text" size=12 name="data_ate_<?php echo $i; ?>" id="data_ate_<?php echo $i; ?>" class="data_courier" value='<?=(($ultimo_sinal)? substr($ultimo_sinal,0,10) : date("d/m/Y"));?>'>
                                        <img src='datetime/img.gif' border=0 id='data_entrega_ate_<?php echo $i; ?>' style='cursor: pointer;' title='Selecione uma data'></td>
                                        <script>
											Calendar.setup({
													inputField     :    'data_ate_<?=$i?>',     // id of the input field
													ifFormat       :    'dd/mm/y',      // format of the input field
													button         :    'data_entrega_ate_<?=$i?>',  // trigger for the calendar (button ID)
													align          :    'Bl',           // alignment (defaults to 'Bl')
													singleClick    :    true
											});
										</script>

<?php				  
					 
                      
			
			
			
		}
		
	}
	else
	$msg="<font color='#FF0000'>Nenhuma Lotação cadastrada</font>";
  break;	
	
}
?>
     
</tbody>
</table>

<!-- iFrame do MAPA -->
<iframe name="conteudoMapa"
        id="conteudoMapa"
		src=""></iframe>

<?php
// pega o Footer
require_once("inc/footer.inc");