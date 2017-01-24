<?php
/**
 * Footer - Include no Sistema (Interno)
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
?>

<!-- iFrame escondido para gravacao -->
<iframe id="gravar" name="gravar"></iframe>
 
</body>
</html>

<!-- Disconnecta o Banco de Dados -->
<?php $con->desconecta(); ?>