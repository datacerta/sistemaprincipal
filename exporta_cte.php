<?php
// pega a configuracao
require_once("inc/config.inc");

// pega o header
require_once("inc/header.inc");
?>

 <form name="exporta_cte" action="exporta_cte_final.php" method="post">
  
  
<TABLE BORDER=0 style="margin:0 auto; width:500px">
        <tr>
        	<td>Data Emissão: </td>
            <td><input type=text name="dataemi" class="dataemi"></td>
            <td><input type=submit value="Gerar Arquivo Para CTE"></td>
            
			<td> <input type=hidden name=ok value=1></td>
        </tr>

      </table>
</form>
<script>
( function( $ ) {
	$(function() {
		$('.dataemi').datepicker({  dateFormat: 'dd/mm/yy',   dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
		    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
		    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
		    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		    nextText: 'Próximo',
		    prevText: 'Anterior' });
		$('.dataemi').mask("99/99/9999");
	});
} )( jQuery );	
</script>
<?php
// pega o Footer
require_once("inc/footer.inc");
