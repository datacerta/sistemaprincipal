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


$ativo_ = "";

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";
$_Exec  = HOST."/rastreamento_entregas.php";
// pega o header
require_once("inc/header.inc");
?>
<style>
	#tblResultado_filter{
		display: none;
	}
	.campos input[type="text"]{
		width: 100px;
		margin-left: 5px;
	}
	.campos label{
	}

	.camp{
		margin-right: 5px !important;
		margin-left: 10px !important;
	}
</style>
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
                     "search": 'Filtro:',
                     "infoFiltered": "(_MAX_ total de registros)"

                 }
                 
             });
			 
			 

	
			 
		 });
		 
	 
	
	
		
	
	
	function verMapa(setor){
        url = 'rastreamento_entregas.php?setor='+setor;
		
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
	dg("conteudoMapa").style.height = (bodyHeight ) + "px"
}
	

 </script>

<div style="margin:0 auto; width:890px">
<div style="background-color:#F0F0F0; padding:25px; border: 1px solid #C8C8C8 ;">
	<div style="float:left" class="campos">
		<div style="margin-bottom: 5px">
			<label>Setor:</label><input type="text" name="setor" class="setores"/>
			<label>Lista:</label><input type="text" name="lista" class="listas"/>
		</div>
		<div style="margin-bottom: 5px">
			<label>RA:</label><input type="text" name="ra" class="ra"/>
			<label>ENDERECO:</label><input type="text" name="endereco" class="endereco"/>
			<input type="checkbox" name="camp" class="camp"/><label>Campanhas</label>
		</div>
		<div>
			<button class="pesquisar">Pesquisar</button>
		</div>
	</div>
	<div style="float:right; margin-left: 10px">
		<div style="font-weight: bold">
			Campanhas:
		</div>
		<div>
			<img src="img/1spin.png" />
			<img src="img/5spin.png" />
			<img src="img/4spin.png" />
			<img src="img/7spin.png" />
		</div>
	</div>
	<div style="float:right; margin-left: 10px">
		<div style="font-weight: bold">
			&Uacute;ltimo:
		</div>
		<div>
			<img src="img/3spin.png" />
		</div>
	</div>
	<div style="float:right; margin-left: 10px">
		<div style="font-weight: bold">
			Checar:
		</div>
		<div>
			<img src="img/6spin.png" />
		</div>
	</div>
	<div style="clear:both"></div>
</div>
 <table id="tblResultado" class="table compact" style="font-size:12px;" width="100%" cellspacing=1 cellpadding=0 border=0>
	<thead>
    <?

    $sql = "SELECT COUNT(nome_revend) as quant, id_setor
	  FROM tb_demillus_revend
	  GROUP BY id_setor
	  ORDER BY id_setor;";
	$qry->executa($sql);

	if ($qry->nrw){
  

	  

	  
  	echo "\n<form action='$PHP_SELF' name='operacao' id='operacao' method='POST'>
          <input type=hidden name=id value=''>
          <input type=hidden name=opt value=''>
   	  	  <input type=hidden name=opt2 value=''>";
		
		
    		echo "</form>\n";
		
		echo "<tr bgcolor=#303231 style='color:#fff' align = left>
                     
                      <th style='color:#fff'><b>SETOR</b></th>
                      <th style='color:#fff'><b>QTD</b></th>  
					  <th style='color:#fff'><b>QTD TOTAL</b></th>  
			  </tr>
			  </thead>
			  <tbody>";
		
   
   
    for($i=0; $i<$qry->nrw; $i++){
			$qry->navega($i);
		    $sql = "SELECT COUNT(nome_revend) as quant, id_setor
			  FROM tb_demillus_revend
			  WHERE latitude != '0' AND id_setor = ".$qry->data["id_setor"]
			  ."GROUP BY id_setor
			  ORDER BY id_setor;";
			$qry2->executa($sql);
			   $id_setor = $qry->data["id_setor"];

			echo "<tr ".(($i%2==0)?"":"bgcolor='#eeeeee'").">";
			?>
					  <td><a href="#" onClick="verMapa('<?php echo $id_setor; ?>', <?php echo $i; ?>)"><?php echo $id_setor; ?></a></td>
			<?php
			echo "<td>".$qry2->data["quant"]."</td>";
			echo "<td>".$qry->data["quant"]."</td>";
			?>	   

<?php				  
					 
                      
			
			
			
		}
		
	}
	else
	$msg="<font color='#FF0000'>Nenhuma Lotação cadastrada</font>";
?>
     
</tbody>
</table>
</div>

<!-- iFrame do MAPA -->
<iframe name="conteudoMapa"
        id="conteudoMapa"
		src=""></iframe>

<script>
( function( $ ) {
	$(function() {
		$('.pesquisar').on('click',function(){
			url = 'rastreamento_entregas.php?setores='+$('.setores').val()+'&listas='+$('.listas').val()+'&ra='+$('.ra').val()+'&camp='+$('.camp').prop( "checked" )+'&endereco='+$('.endereco').val();
			window.open(url,'conteudoMapa');
		});
	});
} )( jQuery );
</script>

<?php
// pega o Footer
require_once("inc/footer.inc");