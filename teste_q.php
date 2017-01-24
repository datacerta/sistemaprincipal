<?php
/**
 * Auditoria DM
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega a configuracao
require_once("inc/config.inc");

// seta o parent e redireciona
$_SESSION["PARENT"     ] = false;
$_SESSION["REDIRECIONA"] = false;

// consulta
$qry = new consulta($con);


// pega o header
require_once("inc/header.inc");
?>

<div>
<?php
$teste = '09';
echo (int)$teste;
?>
</div>
 <?php
// pega o Footer
require_once("inc/footer.inc");