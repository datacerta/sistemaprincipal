<?php
require_once("classes/diversos.inc.php");
define("HOST", "http://".$_SERVER["HTTP_HOST"]);
$selfLink = HOST.$PHP_SELF;

$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$numnota = '';
$numcaixa = '';
$numnotaRes = '';
$numcaixaRes = '';
$lista = 0;
$foco = 0;
$erroLista = 0;

if($_POST['Enviar'] == 'OK' && !empty($_POST['lista_input'])){
	
	$lista = $_POST['lista_input'];

	/*
	QUANTIDADE DE NOTA TOTAL
	*/	
	$sql = "SELECT COUNT(e.numnotafiscal) as numnota 
	FROM tbentrega e
	WHERE numlista = $lista";
	
	$qry->executa($sql);
	if($qry->data['numnota']){
		$foco = 1;
		$numnota = $qry->data['numnota'];
	}

	/*
	QUANTIDADE DE CAIXA TOTAL
	*/
	$sql = "SELECT COUNT(dv.num_caixa) as numcaixa 
	FROM tbentrega e
	INNER JOIN tb_demillus_volumes dv
	ON e.idinterno = dv.idinterno
	WHERE numlista = $lista";

	$qry->executa($sql);
	if($qry->data['numcaixa']){
		$foco = 1;
		$numcaixa = $qry->data['numcaixa'];
	}


	/*
	QUANTIDADE DE CAIXAS RESTANTES
	*/
	$sql = "SELECT COUNT(dv.num_caixa) as numcaixa 
	FROM tbentrega e
	INNER JOIN tb_demillus_volumes dv
	ON e.idinterno = dv.idinterno
	WHERE numlista = $lista
	AND dv.status = 'P'";

	$qry->executa($sql);
	if($qry->nrw){
		$numcaixaRes = $qry->data['numcaixa'];
	}

	/*
	QUANTIDADE DE NOTAS RESTANTES
	*/
	$sql = "SELECT COUNT(DISTINCT e.numnotafiscal) as numnota
	FROM tbentrega e
	INNER JOIN tb_demillus_volumes dv
	ON e.idinterno = dv.idinterno
	WHERE numlista = $lista
	AND dv.status = 'P'";

	$qry->executa($sql);
	if($qry->nrw){
		$numnotaRes = $qry->data['numnota'];
	}

}else{
	$erroLista = 1;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Monitor de Caixas</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="animate.css" rel="stylesheet">
<style>
	*{
		margin: 0;
		padding: 0;
	}

	body{
		font-size: 35px;
	}

	.container-fluid input.form-control {
		font-size: 35px;
		height: auto;
	}
	#form_lista input.btn{
		font-size: 35px;
		height: auto;
	}

	.numres{
		font-size:350px; 
		font-weight:bold;
	}

	#myModalLabel{
		font-size: 100px;
	}

	.codigo_input_extra{
		font-size: 40px;
		height: auto;
	}

</style>
</head>

<body>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-warning erro-msg text-center" role="alert" style="display:none"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<form name="form_monitor" id="form_lista" action="<?php echo $selfLink;?>" method="post" class="form-inline">
						<div class="form-group">
							<label for="lista_input">INFORME A LISTA:</label>
							<input type="text" name="lista_input" class="form-control lista_input" />
							<input type="submit" class="btn btn-primary" name="Enviar" value="OK" />
						</div>
					</form>
				</div>
				<div class="panel-body">
				A LISTA <?php echo $lista ?> CONTEM <?php echo $numnota ;?> NOTAS E <?php echo $numcaixa ;?> CAIXAS
				</div>
			</div>
		</div>	
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading text-center">
					NOTAS RESTANTES
				</div>
				<div class="panel-body text-center numres resnota">
					<?php echo $numnotaRes ?>
				</div>	
			</div>			
		</div>
		<div class="col-md-6">
			<div class="panel panel-success">
				<div class="panel-heading text-center">
					CAIXAS RESTANTES
				</div>
				<div class="panel-body text-center numres rescaixa">
					<?php echo $numcaixaRes ?>
				</div>
			</div>					
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="form-group">
						<label for="codigo_input">CÓDIGO DE BARRAS DA CAIXA</label>
						<input type="text" name="codigo_input" class="form-control codigo_input" />
					</div>
				</div>
				<div class="panel-body">
					<ul class="list-group monitor">
					</ul>
				</div>
			</div>
		</div>	
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title" id="myModalLabel"></h1>
			</div>
			<div class="modal-body" id="modal-extra">
			</div>
		</div>
	</div>
</div>
<audio id="audio-sucess">
   <source src="success.mp3" type="audio/mp3" />
</audio>
<audio id="audio-fail">
   <source src="fail.mp3" type="audio/mp3" />
</audio>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript">
(function( $ ) {
	$(function() {
		$.fn.extend({
		    animateCss: function (animationName) {
		        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
		        $(this).addClass('animated ' + animationName).one(animationEnd, function() {
		            $(this).removeClass('animated ' + animationName);
		        });
		    }
		});
		var erroLista = <?php echo $erroLista; ?>;
		if(erroLista == 1){
			$('.erro-msg').text('INFORME O NÚMERO DA LISTA');
			$('.erro-msg').show('slow');
		}

		if(<?php echo $foco ?>){
			$('.codigo_input').focus();
			$('.codigo_input').keypress(function (e) {
				if (e.which == 13) {

					var codcaixa = $(this).val();
					var numlista = <?php echo $lista; ?>;
					$(this).val('');

					if(codcaixa == ''){
						$('.erro-msg').text('INFORME O NÚMERO DA CAIXA');
						$('.erro-msg').show('slow');
					}else if(numlista != 0){
						$.ajax({
							method: "POST",
							url: "<?php echo HOST."/Exec/monitor_caixas_ajax.php"; ?>",
							dataType:"json",
							data: {
								caixa: codcaixa,
								lista: numlista
							}
						})
						.done(function( obj ) {
							if(obj.status == 1){
								$('.monitor').after('<li class="list-group-item animated bounceInLeft">'+obj.caixa+'</li>');
								$('.resnota').animateCss('zoomIn');
								$('.resnota').text(obj.qtdnota);
								$('.rescaixa').animateCss('zoomIn');
								$('.rescaixa').text(obj.qtdcaixa);
								$('.erro-msg').hide('slow');
								$('#audio-sucess').get(0).play();
							}else{
								$('.monitor').after('<li class="list-group-item list-group-item-danger animated bounceInLeft">'+obj.caixa+' / '+obj.detalhesErr+'</li>');
								if(obj.detalhesErr != 'null'){
									$('.erro-msg').text(obj.detalhesErr);
									$('.erro-msg').show('slow');
								}
								$('#audio-fail').get(0).play();
							}
							if(obj.caixaex.length > 1){
								$('#myModalLabel').text(''+obj.caixaex.length+' CAIXAS');
								$('#myModal').on('shown.bs.modal', function () {
									$('#modal-extra').empty();
									for (i = 0; i < obj.caixaex.length; i++){
										$('#modal-extra').append('<input type="text" name="codigo_input_extra[]" class="form-control codigo_input_extra" /><br>');
										$('#modal-extra').append('<input type="hidden" name="num_caixa_extra[]" value="'+obj.caixaex[i]+'" class="form-control num_caixa_extra" />');
									}
									$('.codigo_input_extra').first().focus();

									$('.codigo_input_extra').on('keypress', function (e) {
										if (e.which == 13) {
											$(this).next().next().next().focus();
											if($(this).next().next().next().val() === undefined){
												var codigoInput= $(".codigo_input_extra").map(function() {
												   return $(this).val();
												}).get();
												var numCaixa= $(".num_caixa_extra").map(function() {
												   return $(this).val();
												}).get();
												var numlista = <?php echo $lista; ?>;
												$.ajax({
													method: "POST",
													url: "<?php echo HOST."/Exec/monitor_caixas_ajax.php"; ?>",
													dataType:"json",
													data: {
														caixa: numCaixa,
														codigo: codigoInput,
														lista: numlista
													}
												})
												.done(function( obj ) {
													if(obj.status == 1){
														$('#myModal').modal('hide');
														$('.resnota').animateCss('zoomIn');
														$('.resnota').text(obj.qtdnota);
														$('.rescaixa').animateCss('zoomIn');
														$('.rescaixa').text(obj.qtdcaixa);
														if(obj.detalhesErr != 'null'){
															$('.erro-msg').text(obj.detalhesErr);
															$('.erro-msg').show('slow');
														}
														$('.codigo_input').focus();
														$('#audio-sucess').get(0).play();
													}else{
														$('#myModal').modal('hide');
														if(obj.detalhesErr != 'null'){
															$('.erro-msg').text(obj.detalhesErr);
															$('.erro-msg').show('slow');
														}
														$('.codigo_input').focus();
														$('#audio-fail').get(0).play();
													}
												}).fail(function() {
													$('#myModal').modal('hide');
													$('.monitor').after('<li class="list-group-item list-group-item-danger animated bounceInLeft">ERRO AO ATUALIZAR</li>');
													$('.codigo_input').focus();
													$('#audio-fail').get(0).play();
												});
											}
										}
									});
								});
								$('#myModal').modal('show');

							}
						}).fail(function() {
							$('.monitor').after('<li class="list-group-item list-group-item-danger animated bounceInLeft">'+codcaixa+'</li>');
							$('#audio-fail').get(0).play();
						});
					}else{
						$('.erro-msg').text('INFORME O NÚMERO DA LISTA');
						$('.erro-msg').show('slow');
						$('.lista_input').focus();
					}
				}
			});
		}else{
			$('.lista_input').focus();
		}

	});
})( jQuery );
</script>
</body>

</html>


