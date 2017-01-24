<?php
/**
 * Geracao de Manifesto Demillus - Lista
 * -------------------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// acerta a data de emissao
$dataEmis = (!empty($data)) ? Util::transformaData($data) : "";

// seta o EXEC
$_Exec  = HOST."/Exec/geracao-manifesto-demillus_ajax.php";
// cria o modulo de consulta
$lqry = new consulta($con);

// monta a query
$lsql = "SELECT  ((1-row_number()OVER(order by (select preco from tb_preco_demillus WHERE 1=1  AND codigoproduto='543' and setor= cast(e.numlotecliente as integer ) limit 1)))*-1) AS positi," .
        "( SELECT sum(tbentrega.valorentrega) as vl_entrega FROM tbentrega WHERE tbentrega.idtransportadora=1 AND tbentrega.codcliente='{$codCliente}' AND tbentrega.codigoproduto='{$codProduto}'  " .
        " AND tbentrega.dataemissao >= '{$dataEmis}' AND tbentrega.dataemissao <= '{$dataEmis}' AND tbentrega.dataemissao='{$dataEmis}' and tbentrega.numlotecliente =e.numlotecliente ) AS valor, " .
        " (select count(*) from tbentrega x where x.numlotecliente = e.numlotecliente and x.dataemissao='{$dataEmis}') AS notas," .
        " round(Cast(sum(e.pesoentrega)as numeric),2) AS peso, " .
        " (SELECT a.numnotafiscal as primeiranota FROM tbentrega a WHERE a.idtransportadora=1 AND a.numlotecliente = e.numlotecliente AND a.codcliente='{$codCliente}' AND a.codigoproduto='{$codProduto}' AND a.dataemissao = '{$dataEmis}' order by numnotafiscal  asc limit 1) AS pnota, " .
        " (SELECT a.numnotafiscal as primeiranota FROM tbentrega a WHERE a.idtransportadora=1 AND a.numlotecliente = e.numlotecliente AND a.codcliente='{$codCliente}' AND a.codigoproduto='{$codProduto}' AND a.dataemissao = '{$dataEmis}' order by numnotafiscal desc limit 1) AS unota, " .
        " numlotecliente as setor, " .
        " to_char(dataemissao,'DD/MM/YYYY') as dataemissao, nd.numero_manifesto, " .
        " ((" .
        " select preco from tb_preco_demillus WHERE 1=1 " .
        " AND codigoproduto='{$codProduto}'" .
        " and setor= cast(e.numlotecliente as integer ) limit 1" .
        " )* (select count(*) from tbentrega x where x.numlotecliente = e.numlotecliente and x.dataemissao='{$dataEmis}')) AS tarifa, " .
        " (select num_campanha from tb_demillus_campanha where  data_coleta = '{$dataEmis}' limit 1) AS campanha,  " .
        " (select preco from tb_preco_demillus WHERE 1=1  AND codigoproduto='{$codProduto}' and setor= cast(e.numlotecliente AS integer ) limit 1) AS tarifa1, " .
		" nd.cte                                   ,
		  nd.id_nota_demillus                      ,
		  nd.numero_cte||'/'|| nd.serie AS seriecte,
		  nd.dataemissao_cte                       ,
		  nd.numero_nota_demillus " .
        " FROM tbentrega e " .
        " LEFT join tb_preco_demillus pd on (setor= cast(e.numlotecliente as integer ))" .
        //" LEFT join tbnotademillus nd on (l.cte = nd.cte)  " .
		" LEFT join tbnotademillus nd ON (nd.datanota_demillus = to_char(e.dataemissao,'DD/MM/YYYY')) AND (nd.numero_setor = e.numlotecliente) " .
        " WHERE e.idtransportadora=1 AND e.codcliente='{$codCliente}' AND e.codigoproduto='{$codProduto}' AND e.dataemissao = '{$dataEmis}' " .
        " GROUP BY e.numlotecliente   ,
		           dataemissao        ,
				   e.codcliente       ,
				   e.codigoproduto    ,
				   e.num_manifesto    ,
				   nd.cte             ,
				   nd.id_nota_demillus,
				   nd.serie           ,
				   nd.numero_cte      ,
				   nd.dataemissao_cte ".
		" ORDER BY positi                  ASC
		
				   ";

// executa a query
$lqry->executa($lsql);

// numero de linhas
$num_lin = $lqry->nrw;
?> 

<!-- monta a tabela de lista -->
<div class="lista">
	<div style="float:right; margin-right:50px; margin-bottom:25px;"><span><strong>Data de Vencimento:</strong></span><input type="text" name="data-venc-fat" class="data-venc-fat" /></div>
    <!-- <form action="<?=$_Exec?>" name="fList" method="post"> -->
	<input type="hidden" name="num_lin" value="<?=$num_lin?>" />
    <table class="tabela1">
    <tr>
      <th>Data Emiss&atilde;o</th>
      <th>Setor</th>
      <th>Notas</th>
	  <th>Valor</th>
	  <th>Peso Total</th>
	  <th>Frete</th>
	  <th>ICMS</th>
	  <th>Data Emis. CTE</th>
	  <th>N<sup>o</sup> Nota Demillus</th>
	  <th>N<sup>o</sup> CTE / Serie do<br />CTE</th>
	  <th>CTE</th>
	  <th>Limpar</th>
	  <th>Manifesto</th>
	  <th>Gravar</th>
	  <th>Pdf</th>
	  <th>Txt</th>
    </tr>
	<?php
	// percorre o resultado da query
    for($i = 0; $i < $num_lin; $i++) {
        // navega
        $lqry->navega($i);

		// pega alguns campos
		$positi  = $lqry->data["positi"];      // position
		$dataExi = $lqry->data["dataemissao"];
		$setor   = $lqry->data["setor"];
		$totalNF = $lqry->data["notas"];
		$pnota   = $lqry->data["pnota"];
		$unota   = $lqry->data["unota"];
		$valor   = $lqry->data["valor"];
		$frete   = $lqry->data["tarifa"];
		$peso    = $lqry->data["peso"];
		$tarifa  = $lqry->data["tarifa1"];
		
		// dados da tabela tbnotademillus
		$cte                  = $lqry->data["cte"];
		$seriecte             = $lqry->data["seriecte"];
		$id_nota_demillus     = $lqry->data["id_nota_demillus"];
		$dataemissao_cte      = $lqry->data["dataemissao_cte"];
		$numero_nota_demillus = $lqry->data["numero_nota_demillus"];
		$numero_manifesto     = $lqry->data["numero_manifesto"];

        // valida o numero do manifesto
        if (empty($numero_manifesto)) { $numero_manifesto = date("Ymd"); }

		// aliquotas
		$aliquota_base_icms = 0.88;
		$aliquota_icms      = 12;
		
		// calcular GRIS
		$gris = round((($valor * 0.1) / 100), 2);
		
		// calcular ADV
		$advis = round((($valor * 0.3) / 100), 2);
		
		// calculo do ICMS
		$icms = round_half_down((($frete / $aliquota_base_icms) * $aliquota_icms) / 100, 2);

		// formata os valores
		$nvalor = number_format($valor, 2, ",", "");
		$ngris  = number_format($gris , 2, ",", "");
		$nadvis = number_format($advis, 2, ",", "");
		$nfrete = number_format($frete, 2, ",", "");
		$npeso  = number_format($peso , 2, ",",  "");
		$nicms  = number_format($icms , 2, ",", "");
		
		// monta o TEXTO
		$texto = "LOCAL DA ENTREGA: SETOR {$setor}. ".
		         "TOTAL NF {$totalNF}. ".
				 "TOMADOR: 33.115.817/0001-50 DEMILLUS INDUSTRIA E COMERCIO LTDA. ".
				 "NOTAS FISCAIS DE {$pnota} / {$unota} ".
				 "N<sup>o</sup> campanha: ".
				 "Data: {$dataExi} || ".
				 "GRIS: R$ {$ngris} || ".
				 "Adiv R$ {$nadvis}";

        // link de limpar				 

		// cria os campos hidden
	
		echo "<input type='hidden' name='valor{$positi}'             value='{$valor}' />\n";
		echo "<input type='hidden' name='peso{$positi}'              value='{$peso}' />\n";
		echo "<input type='hidden' name='icms{$positi}'              value='{$icms}' />\n";
		echo "<input type='hidden' name='primeira_nota{$positi}'     value='{$pnota}' />\n";
		echo "<input type='hidden' name='ultima_nota{$positi}'       value='{$unota}' />\n";
		echo "<input type='hidden' name='numero_setor{$positi}'      value='{$setor}' />\n";
		echo "<input type='hidden' name='numero_notas{$positi}'      value='{$totalNF}' />\n";
		echo "<input type='hidden' name='tarifa{$positi}'            value='{$tarifa}' />\n";
		echo "<input type='hidden' name='datanota_demillus{$positi}' value='{$dataExi}' />\n";
		echo "<input type='hidden' name='id_nota_demillus{$positi}'  value='{$id_nota_demillus}' />\n";
		echo "<input type='hidden' name='numero_manifesto{$positi}'  value='{$numero_manifesto}' />\n";
		echo "<input type='hidden' name='frete{$positi}'  value='{$frete}' />\n";
	?>
	<!-- mostra o resultado -->
    <tr onmouseover="fLightTr(this,'#CCE1F0')" onmouseout="fLightTr(this,this.bgColor)">
      <td align="center"><?=$dataExi?></td>
	  <td align="center"><?=$setor?></td>
	  <td align="center"><?=$totalNF?></td>
	  <td align="right">R$ <?=$nvalor?></td>
	  <td align="right"><?=$npeso?> (Kg)</td>
	  <td align="right">R$ <?=$nfrete?></td>
	  <td align="right"><?=$nicms?></td>
	  <td align="center">

	            <input <?php if (!empty($dataemissao_cte)): echo 'readonly value="'.$dataemissao_cte.'"'; endif; ?> type="text" class="campo dataemi" name="dataemissao_cte<?=$positi?>" style="text-align: center;" size="12" />
	  </td>
	  <td align="center">
	            <input <?php if (!empty($numero_nota_demillus)): echo 'readonly value="'.$numero_nota_demillus.'"'; endif; ?>  type="text" class="campo" name="numero_nota_demillus<?=$positi?>" style="text-align: center;" size="10" />
	  </td>
	  <td align="center">
	            <input <?php if (!empty($seriecte)): echo 'readonly value="'.$seriecte.'"'; endif; ?> type="text" class="campo" name="numero_cte<?=$positi?>" style="text-align: center;" size="10" />
	  </td>
	  <td align="center">
	  	<input <?php if (!empty($cte)): echo 'readonly value="'.$cte.'"'; endif; ?> type="text" class="campo" name="cte<?=$positi?>" size="4" />
	  </td>
	  <td align="center"><button class="botao-limpar" value="<?=$positi?>">Limpar</button></td>
	  <td align="center"><?=$numero_manifesto?></td>
	  <td align="center"><button <?php if (!empty($dataemissao_cte) && !empty($numero_nota_demillus) && !empty($seriecte) && !empty($cte)): echo "disabled style='color:white; background-color:green'"; endif; ?> class="botao-gravar btn-gravar<?=$positi?>" value="<?=$positi?>"><?php if (!empty($dataemissao_cte) && !empty($numero_nota_demillus) && !empty($seriecte) && !empty($cte)): echo "Salvo"; else: echo "Gravar"; ?><?php endif;?></button></td>
		<td align="center"><a class="gera-pdf<?=$positi?> gerapdf-val" target="_blank" href="<?php echo HOST."/geracao-demillus-pdf-bsoft.php?numero_nota_demillus=$numero_nota_demillus&datanota_demillus=$dataExi&numero_setor=$setor"; ?>">Pdf</a></td>
		<td align="center"><a class="gera-txt<?=$positi?>" target="_blank" href="<?php echo HOST."/geracao-demillus-txt.php?numero_nota_demillus=$numero_nota_demillus&datanota_demillus=$dataExi&numero_setor=$setor"; ?>">Txt</a></td>
	</tr>
	<tr class="zebra" style="border-bottom:3px solid #003869">
	   <td colspan="16"><?=$texto?></td>
	</tr>
	<?php }?>
	<tr><td colspan="16">&nbsp;</td></tr>
    </table>
	<!-- </form> -->
</div>
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

		$('.data-venc-fat').datepicker({  dateFormat: 'dd/mm/yy',   dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
		    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
		    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
		    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		    nextText: 'Próximo',
		    prevText: 'Anterior' });
		$('.data-venc-fat').mask("99/99/9999");

		$('.gerapdf-val').on('click',function(){
			if($('.data-venc-fat').val() == ''){
				alert('Entre com a data de vencimento');
				return false;
			}else{
				$(this).prop('href',$(this).prop('href')+'&datavenc='+$('.data-venc-fat').val());
				return true;
			}
		})

		$('.botao-limpar').on('click',function(){
			if(confirm('Tem certeza que deseja limpar os campos?')){
				var posicao = $(this).val();
				var datanota_demillus = $('input[name = "datanota_demillus'+posicao+'"');
				var numero_setor = $('input[name = "numero_setor'+posicao+'"');

				$.ajax({
					method: "POST",
					url: "<?php echo $_Exec; ?>",
					dataType:"json",
					data: { 
						limpar: 1,
						pos: posicao,
						datanota_demillus: datanota_demillus.val(),
						numero_setor: numero_setor.val()
					}
				})
				.done(function( obj ) {
					if(obj.status == 2){
						alert('Setor ou data de nota inválidos');
					}else if(obj.status == 1){
						$('.btn-gravar'+posicao).prop('disabled', false);
						$('.btn-gravar'+posicao).css({'background-color':'#DDDDDD','color':'black'});
						$('.btn-gravar'+posicao).text('Gravar');
						$('input[name = "dataemissao_cte'+posicao+'"').prop('readOnly', false);
						$('input[name = "dataemissao_cte'+posicao+'"').val('');
						$('input[name = "numero_nota_demillus'+posicao+'"').prop('readOnly', false);
						$('input[name = "numero_nota_demillus'+posicao+'"').val('');
						$('input[name = "numero_cte'+posicao+'"').prop('readOnly', false);
						$('input[name = "numero_cte'+posicao+'"').val('');
						$('input[name = "cte'+posicao+'"').prop('readOnly', false);
						$('input[name = "cte'+posicao+'"').val('');
					}				
				});				
			}
		});
		$('.botao-gravar').on('click',function(){
			var posicao = $(this).val();
			var valor = $('input[name = "valor'+posicao+'"');
			var peso = $('input[name = "peso'+posicao+'"');
			var icms = $('input[name = "icms'+posicao+'"');
			var primeira_nota = $('input[name = "primeira_nota'+posicao+'"');
			var ultima_nota = $('input[name = "ultima_nota'+posicao+'"');
			var numero_setor = $('input[name = "numero_setor'+posicao+'"');
			var numero_notas = $('input[name = "numero_notas'+posicao+'"');
			var tarifa = $('input[name = "tarifa'+posicao+'"');
			var datanota_demillus = $('input[name = "datanota_demillus'+posicao+'"');
			var id_nota_demillus = $('input[name = "id_nota_demillus'+posicao+'"');
			var dataemissao_cte = $('input[name = "dataemissao_cte'+posicao+'"');
			var numero_nota_demillus = $('input[name = "numero_nota_demillus'+posicao+'"');
			var numero_cte = $('input[name = "numero_cte'+posicao+'"');
			var numero_manifesto = $('input[name = "numero_manifesto'+posicao+'"');
			var cte = $('input[name = "cte'+posicao+'"');
			var frete = $('input[name = "frete'+posicao+'"');

			if(dataemissao_cte.val() == ''){
				alert('Campo data de emissão vazio');
				return false;
			}
			if(numero_nota_demillus.val() == ''){
				alert('Campo nº de nota demillus vazio');
				return false;
			}
			if(numero_cte.val() == ''){
				alert('Campo número do cte vazio');
				return false;
			}
			if(cte.val() == ''){
				alert('Campo cte vazio');
				return false;
			}

			$.ajax({
				method: "POST",
				url: "<?php echo $_Exec; ?>",
				dataType:"json",
				data: { 
					pos: posicao,
					valor: valor.val(),
					peso: peso.val(),
					icms: icms.val(),
					primeira_nota: primeira_nota.val(),
					ultima_nota: ultima_nota.val(),
					numero_setor: numero_setor.val(),
					numero_notas: numero_notas.val(),
					tarifa: tarifa.val(),
					datanota_demillus: datanota_demillus.val(),
					id_nota_demillus: id_nota_demillus.val(),
					dataemissao_cte: dataemissao_cte.val(),
					numero_nota_demillus: numero_nota_demillus.val(),
					numero_cte: numero_cte.val(),
					numero_manifesto: numero_manifesto.val(),
					cte: cte.val(),
					frete: frete.val()
				}
			})
			.done(function( obj ) {
				if(obj.status == 2){
					alert('Entrada salva');
				}else if(obj.status == 1){
					$('.btn-gravar'+posicao).prop('disabled', true);
					$('.btn-gravar'+posicao).text('Salvo');
					$('.btn-gravar'+posicao).css({'background-color':'green','color':'white'});
					$('input[name = "dataemissao_cte'+posicao+'"').prop('readOnly', true);
					$('input[name = "numero_nota_demillus'+posicao+'"').prop('readOnly', true);
					$('input[name = "numero_cte'+posicao+'"').prop('readOnly', true);
					$('input[name = "cte'+posicao+'"').prop('readOnly', true);
					$('.gera-pdf'+posicao).prop('href',"<?php echo HOST."/geracao-demillus-pdf.php?numero_nota_demillus=";?>"+numero_nota_demillus.val()+"<?php echo "&datanota_demillus="; ?>"+datanota_demillus.val()+"<?php echo "&numero_setor="; ?>"+numero_setor.val());
					$('.gera-txt'+posicao).prop('href',"<?php echo HOST."/geracao-demillus-txt.php?numero_nota_demillus=";?>"+numero_nota_demillus.val()+"<?php echo "&datanota_demillus="; ?>"+datanota_demillus.val()+"<?php echo "&numero_setor="; ?>"+numero_setor.val());
				}
			});
		});

	});
} )( jQuery );	
</script>
