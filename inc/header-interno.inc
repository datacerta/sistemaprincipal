<?php
/**
 * Header - Include no Sistema (Interno)
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
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
	<link rel="stylesheet" type="text/css" href="<?=HOST?>/estilo.css" />
    <link rel="stylesheet" type="text/css" href="<?=HOST?>/tahoma.css" />
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" />
	<link rel="stylesheet" type="text/css" href="<?=HOST?>/inc/css/base.css" />
	<link rel="stylesheet" type="text/css" href="<?=HOST?>/inc/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?=HOST?>/inc/css/lista.css" />

	<!-- JS Padrao -->
    <script type="text/javascript" src="<?=HOST?>/inc/js/ajax.js?token=<?=$rnd?>"></script>
    <script type="text/javascript" src="<?=HOST?>/inc/js/funcoes.js?token=<?=$rnd?>"></script>
	<script type="text/javascript" src="<?=HOST?>/inc/js/header.js?token=<?=$rnd?>"></script>
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
