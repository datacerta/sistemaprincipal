<?php
/**
 * Exibe a foto
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// define o HOST
define("HOST", "http://".$_SERVER["HTTP_HOST"]);

// recebe o ID como parametro
$id = (isset($_GET["id"])) ? $_GET["id"] : 0;

// seta o link da Foto
$urlFoto = HOST."/mostra_imagens_final.php?id={$id}";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title></title>
  	<meta charset="UTF-8">
    <meta name="robots"              content="noindex,nofollow" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma"        content="no-cache" />
	<meta name="viewport"            content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />
    <style>
	    body {
			-webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
		    margin: 0px;
		    padding: 0px;
	    }
		
		img {
			width: 99%;
			border: 0px;
			margin: 0px;
		}
	</style>
</head>

<body>

<!-- Foto -->
<img src="<?=$urlFoto?>" title="" alt="" />

</body>
</html>