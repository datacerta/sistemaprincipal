<?php
/**
 * Header - Include no Sistema
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta o link de host
$linkHost =  HOST."/index.php?token={$rnd}";

// pega as imagens
$iEditar  = "<img src='".HOST."/inc/img/ic_editar.png' alt='' title='Editar'  align='absmiddle' />";
$iExcluir = "<img src='".HOST."/inc/img/ic_delete.png' alt='' title='Excluir' align='absmiddle' />";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Titulo da Pagina -->
	<title>DataCerta - Controle de Courier On Line</title>

	<!-- Metas -->
	<meta charset="UTF-8">
    <meta name="robots"              content="noindex,nofollow" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma"        content="no-cache" />
	<meta name="viewport"            content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />

	<!-- Links -->
	<link href="<?=HOST?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet"    type="text/css"     href="<?=HOST?>/estilo.css" />
    <link rel="stylesheet"    type="text/css"     href="<?=HOST?>/tahoma.css" />
	<link rel="stylesheet"    type="text/css"     href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" />
	<link rel="stylesheet"    type="text/css"     href="<?=HOST?>/inc/css/base.css" />
	<link rel="stylesheet"    type="text/css"     href="<?=HOST?>/inc/css/style.css" />
	<link rel="stylesheet"    type="text/css"     href="<?=HOST?>/inc/css/lista.css" />
	<link rel="shortcut icon" type="image/x-icon" href="<?=HOST?>/inc/img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="<?=HOST?>/js/jquery-ui/jquery-ui.min.css"  media="all"   />

	<!-- CSS LightBox -->
    <link rel="stylesheet" href="<?=HOST?>/inc/lightbox/lightbox-1.0.0.css" />

	<!-- CSS Alert -->
    <link rel="stylesheet" href="<?=HOST?>/inc/alert/alert-1.0.0.css" />

	<!-- Script Alert -->
    <script type="text/javascript" src="<?=HOST?>/inc/alert/alert-1.0.0.js?token=<?=$rnd?>"></script>

    <!-- Script LightBox -->
    <script type="text/javascript" src="<?=HOST?>/inc/lightbox/lightbox-1.0.0.js?token=<?=$rnd?>"></script>

	<!-- JS Padrao -->

    <script type="text/javascript" src="<?=HOST?>/inc/js/ajax.js?token=<?=$rnd?>"></script>
    <script type="text/javascript" src="<?=HOST?>/inc/js/funcoes.js?token=<?=$rnd?>"></script>
	<script type="text/javascript" src="<?=HOST?>/inc/js/header.js?token=<?=$rnd?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="<?=HOST?>/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=HOST?>/js/jquery.mask.min.js"></script>	
<script type="text/javascript" src="<?=HOST?>/bootstrap/js/bootstrap.min.js"></script>	
	<script type="text/javascript">
	    // pega o HOST
	    host    = "<?=HOST?>";
		lastpag = parseInt("<?=$lastpag?>");
	   
	    // pre-carrega as imagens
        MM_preloadImages("<?=HOST?>/inc/img/ajax-loader.gif");
	</script>
</head>

<body>

<!-- Loading -->
<div id="preloader" class="preloader-overlay">
     <p><img src="<?=HOST?>/inc/img/ajax-loader.gif" border="0" alt="" /></p>
</div>

<!-- Header -->
<header id="noprint">
    <!-- Cabecalho/Menu -->
	<?php if (($prg == "login") || ($prg == "error") || ($prg == "externo")) {?>
	      <div class="bgfill1">
	           <div class="context">&nbsp;</div>
          </div>
	<?php } else { require_once("menu.inc"); }?>

	<!-- Logo -->
	<div class="below" onclick="headerClick()">
	    <?php if ($prg == "externo") {?>
	          <img src="<?=HOST?>/inc/img/logo-datacerta.png" title="DataCerta Log&iacute;stica" alt="" class="logotipo" />
	    <?php } else {?>
		      <a href="<?=$linkHost?>" class="logo"><img src="<?=HOST?>/inc/img/logo-datacerta.png" title="DataCerta Log&iacute;stica" alt="" class="logotipo" /></a>
		<?php }?>
		<span><!-- titulo da tela -->
		      <?=$prgTit?>
			  <?php if (!empty($prgPri)) { echo $prgPri; }?>
		</span>
    </div>
	<div class="clearfix"></div>
</header>

<!-- Body -->
<main>
    <div class="context" onclick="headerClick()">