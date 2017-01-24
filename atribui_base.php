<?php
/**
 * Atirui Base
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// pega a configuracao
require_once("inc/config.inc");

// consulta        
$qry = new consulta($con);
$qry2 = new consulta($con); 
$qry3 = new consulta($con);


if($id_externo)
   $input_foco=sedex;
else
  $input_foco=id_externo;
  
// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>    

<!-- CSS Local -->
<link href="<?=HOST?>/css/table_2.css" rel="stylesheet" type="text/css" />
<link href="<?=HOST?>/css/tip.css"     rel="stylesheet" type="text/css" />

<!-- JS Local -->
<script type="text/javascript">
  function init() { inicio(document.form_busca); }

  function inicio(form){
	    form.id_externo.value="";
		form.id_externo.focus();
}
</script>

<div style="width: 900px; margin: 0 auto;">

    <table class="tabela" style="border: none;">
      <tr bgcolor="#eeeeee">
        <td align="center" colspan=3><font size="6"><b><font size="4">..:: </font></b><font color="#FFF" size="3"><b>SEPARA&Ccedil;&Atilde;O POR EPS </b></font></font><font size="4"><b> ::..</b></font></td>
      </tr>
   
    <form name="form_busca" action="<?=$selfLink?>" method="post">
<?
            if ($msg){
                    echo "<TR>
                                            <TD colspan=2><font color=#ff0000><B>$msg</font></td>
                              </tr>";
            }

            ?>
	<br><br>		
  
      <tr bgcolor="#FFFFFF">
        <td style="border: none; width: 15%;">C&oacute;digo de Barras :</td>
        <td style="border: none;"><input type=text value="<?=$id_externo;?>"  name=id_externo></td>
        <td style="border: none;"><input type=submit value="Procurar"></td>
		
      </tr>
	  </table>
	  <br />
	  <br />
   
    <?php
           
                
                    $id_externo = trim($id_externo);
            if ($id_externo){
                 
				  $sql = "SELECT numconta
                           
					        FROM tbentrega where
                            idexterno = '$id_externo'";
		                 	$qry->executa($sql);
			                $chave=abs($qry->data["numconta"]);
				 
				 $sql = "select tbbase.nomebase,tbentrega.sequencialoteinterno, tbentrega.datapromessa
				 from tbentrega,tbbase
				 where tbentrega.codbase = tbbase.codbase
				 and
				 tbentrega.codbase >1
				 and
				 tbentrega.numconta = '$chave'
				 order by datapromessa desc limit 5";
				 $qry->executa($sql);
				
                    
                echo " <table class='tabela' style='border: none;'>";	
                for($i=0;$i<$qry->nrw;$i++){
                            $qry->navega($i);	
                 						
                    echo "<tr align=left>
					      <td style='border: none;'><font size=10><strong>".mostra_data($qry->data["datapromessa"])."</strong></font></td>
						  <td style='border-left: 1px solid #fff; text-align: center;'><font size=10><strong>".$qry->data["nomebase"]."</strong></font></td>
						  <td style='border-left: 1px solid #fff;'><font size=10><strong>".$qry->data["sequencialoteinterno"]."</strong></font></td>
                          </tr>";
						  
				}
				echo "</table> ";
            }
    ?>

</table>
    
</div>

<?php
// pega o Footer
require_once("inc/footer.inc");