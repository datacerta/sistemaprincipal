<?php
/**
 * Footer - Include no Sistema
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
?>
	</div>
</main> 

<!-- Footer -->
<!--footer> 
  	<div class="context" onclick="headerClick()">
		<small class="copyright">&copy; 2015 Data Certa. All Rights Reserved</small>
	</div>
</footer>-->
 
<!-- iFrame escondido para gravacao -->
<iframe id="gravar" name="gravar"></iframe>
 
</body>
</html>

<!-- Disconnecta o Banco de Dados -->
<?php $con->desconecta(); ?>