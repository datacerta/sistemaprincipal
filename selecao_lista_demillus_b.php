<?php
/**
 * Montagem de Lista Demillus
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega a configuracao
require_once("inc/config.inc");

// monta a consulta
echo $dtb;
$qry = new consulta($con);

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>

<!-- local -->
<script type="text/javascript">

function validar_form_lista(){

	for (var i=0; i < document.form_lista.op.length;i++){
		if(document.form_lista.op[i].checked)
		op = document.form_lista.op[i].value;
	}
	
	cblista = parseInt(document.form_lista.cblista.value);
	
	if(cblista > 0){

		op = parseInt(op);
		
 		//if((op!=3 && op!=5 && op!=6 && op!=7 && ((op==1 || op==2) && ( ( (cblista==2 || cblista==5 || cblista==8) && parseInt(document.form_lista.base.value) > 0)) || (cblista!=2 && cblista!=5 && cblista!=8)))) //verificar essa condicao...
 		if((op!=3 && op!=5 && op!=6 && op!=7 && ((op==1 || op==2) && ( ( (cblista==2 || cblista==5 || cblista==18) && parseInt(document.form_lista.base.value) > 0)) || (cblista!=2 && cblista!=5 && cblista!=18)))) //verificar essa condicao...
		document.form_lista.action='monta_lista_demillus_b.php';
		else if(op==3)
		document.form_lista.action='libera_lista.php';
		else if(op==5 && ((cblista==1 && parseInt(document.form_lista.courier.value) > 0) || cblista!=1))
		document.form_lista.action='expedir_lista.php';
		else if(op==6)
		document.form_lista.action='recebe_lista.php';
		else if(op==7)
		document.form_lista.action='print_lista_demillus.php';
		

		document.form_lista.submit();
		
	}

}

<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>


<div class="box" style="width: 900px; margin: 0 auto;">
<form  name="form_lista" action="<?=$selfLink?>" method="post" >
    <table>
	  <thead>
      <tr>
        <th colspan=5><font size="6"><b><font size="4"> </font></b><font color="#990000" size="3"><b>
            MONTAGEM DE LISTA DEMILLUS</b></font></font><font size="4"><b> </b></font></th>
      </tr>
	  </thead>
      <tr>
        <td colspan="5" align="right">&nbsp;</td>
      </tr>
      <tr>
        <td width="83" align="right">
			<input name='op' type='radio' value='1' <?=(($op==1)?"checked":"")?> onclick="javascript:validar_form_lista();">
        </td>
        <td width="180"> Criar nova lista </td>
        <td colspan="2" nowrap><div align="right">Tipo de Lista:</div></td>
        <td><select name="cblista" onChange="javascript:validar_form_lista();">
        
            <?php
		
		
	
		combo("SELECT codigotipolista,nometipolista 
           FROM tbtipolista WHERE codigotipolista in(2,9,30,27,28,29) 
           ORDER BY nometipolista",$cblista);
		
		

             ?>
          </select></td>
      </tr>
      <tr>
        <td align="right">
    	<input name='op' type='radio' value='2' <?=(($op==2)?"checked":"")?> onclick="javascript:validar_form_lista();">
        </td>
        <td nowrap>Reabrir lista existente</td>
        <?php
             
             if(($op==1 || $op==2) && ($cblista==1 || $cblista==2 || $cblista==5 || $cblista==18)){
             	echo "<td colspan='2' align='right'> Selecione uma Base:</td>";
             	//BASES PARA SACA OU LIBERAÇÃO
             	echo "<td colspan='2'>";
             	//cria combo para seleção de bases
				
				
				$sql = "select tbbasearray.depbase,tbbase.nomebase,
                tbbasearray.codbase,tbbase.codbase as tbbase_codbase from 
				        tbbasearray inner join 
                tbbase on tbbasearray.depbase=tbbase.codbase 
                where 
                tbbasearray.codbase =".$_SESSION['IDBASE']." order by nomebase";
				
				
				//echo $sql;
				$qry->executa($sql);
				
				echo "<select name='base' onChange='javascrip:validar_form_lista();'>";
				echo "<option value='-1'>Selecione uma opção</option>";
				for($i=0;$i<$qry->nrw;$i++){
					$qry->navega($i);
					echo "<option value='".$qry->data["depbase"]."' ".(($base==$qry->data["depbase"] and $base==$qry->data["tbbase_codbase"])?"selected":"").">".$qry->data["nomebase"]."</option>";
				}
           		echo "</select></td></tr>";
           		echo "<input type='hidden' name='courier' value='0'>";//para nao dar erro na funcao javascript validar_form_lista quando nao precisar selecionar a couerir
             	            	            	
             	//COURIER PARA LIBERAÇÃO
             }else if(($cblista==1 && $op==5) || ($cblista==1 && $op==6)){
             	echo "<td colspan='2' align='right'> Selecione um Courier:</td>";
             	echo "<td colspan='2'>
                      <select name='courier' onchange='javascrip:validar_form_lista();'>";
				echo "<option value='-1'>Selecione uma opção</option>";
				
             	//cria combo para seleção de courier para liberação
           		$sql = "select codigocourier,nomecourier from tbcourier where codbase=".$_SESSION['IDBASE']." order by nomecourier";
           		$qry->executa($sql);
				for($i=0;$i<$qry->nrw;$i++){
					$qry->navega($i);
					echo "<option value='".$qry->data["codigocourier"]."' ".(($courier==$qry->data["codigocourier"])?"selected":"").">".$qry->data["nomecourier"]."</option>";
				}
            	
             	echo "</select>
                                </td>
                </tr>";
             	echo "<input type='hidden' name='base' value='-1'>";//echo "<input type='hidden' name='base' value='".$_SESSION["IDBASE"]."'>";//para nao dar erro na funcao javascript validar_form_lista quando nao precisar selecionar a base
             }else{
             	echo "<input type='hidden' name='base' value='-1'>";//echo "<input type='hidden' name='base' value='".$_SESSION["IDBASE"]."'>";//para nao dar erro na funcao javascript validar_form_lista quando nao precisar selecionar a base
             	echo "<input type='hidden' name='courier' value='0'>";//para nao dar erro na funcao javascript validar_form_lista quando nao precisar selecionar a couerir
             }
          ?>
      </tr>
      <tr>
        <td height="21" align="right">
		<input name='op' type='radio' value='5'<?=(($op==5)?"checked":"")?> onclick="validar_form_lista();" >
        </td>
        <td colspan='4'>Expedir lista</td>
       
      </tr>
      
      <tr>
        <td height="21" align="right">
		<input name='op' type='radio' value='7' <?=(($op==7)?"checked":"")?> onclick="validar_form_lista();">
        </td>
        <td>Imprimir Lista</td>
        <td colspan="2"><div align="center"> </div>
          <div align="right"></div></td>
        <td width="183">&nbsp; </td>
      </tr>
      <tr> 
        
      
        
        <?php
        echo"<td colspa=5><input name='data_promessa' type='text' id='data_promessa' value=".(($data_promessa)? 
            "$data_promessa": date("d/m/Y"))." size='10' maxlength='10'> <font color='FF0000'>
            </td>"; 
         ?>   
      
    </table>
    
  </form>
</div>

<?php
// pega o Footer
require_once("inc/footer.inc");