<?php
/**
 * Buscar
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel
$prgTit = "Pesquisa";

// pega a configuracao
require_once("inc/config.inc");

// consulta
$qry   = new consulta($con);
$qry2  = new consulta($con);
$qry3  = new consulta($con);
$qry4  = new consulta($con);
$qry5  = new consulta($con);
$qry6  = new consulta($con);
$qry7  = new consulta($con);
$qry11 = new consulta($con);
$qry12 = new consulta($con);
$qry13 = new consulta($con);
$qry47 = new consulta($con);
$qrybase = new consulta($con);

$qryE  = new consulta($con); //QuantidadeEntregue
$qryD  = new consulta($con); //QuantidadeDevolvida

if($campanha)
$input_foco = "campanha";

if($codbase)
$input_foco = "codbase";

if($data_de)
$input_foco = "data_de";

if($remetente)
$input_foco = "remetente";



if($nome)
$input_foco = "nome";

if($idexterno)
$input_foco = "idexterno";

if($idinterno)
$input_foco = "idinterno";

if($numlista)
{
$input_foco = "numlista";
$numlista2 = $numlista;
}

if($numloteinterno)
$input_foco = "numloteinterno";

if($cpf)
$input_foco = "cpf";

if($agencia)
$input_foco = "agencia";

if($contacorrente)
$input_foco = "contacorrente";

if(!$input_foco)
$input_foco = "idexterno";




if(!$input_foco)
$input_foco = "lote";


if(!$input_foco)
$input_foco = "chave2";


if(!$input_foco)
$input_foco = "lista2";

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

// pega o header
require_once("inc/header.inc");
?>

<!-- CSS Local -->
<link href="<?=HOST?>/css/table_2.css" rel="stylesheet" type="text/css" />
<link href="<?=HOST?>/css/tip.css"     rel="stylesheet" type="text/css" />

<!-- Script local -->
<script type="text/javascript">
  function init() {
	  // seta o FOCUS
	  form_busca.<?=$input_foco;?>.focus();
  }
</script>

<div align="center">

  <table class="tabela" style="width: 800px;">
        
        <form name="form_busca" action="<?=$selfLink?>" method="post">
         <input type="hidden" name="opt" value="B">
         



  <th colspan="6" class=""> <div align="center"><font size="2">  PARAMETROS PARA RASTREAMENTO </font> </div></th>


 
      
      
      
      
      <tr>




      <tr>
        <td>DESTINATARIO: </td>
        <td><input type=text class = menutt size=50 name=nome value='' />
		&nbsp;
       <span class="formata">
       <a href="#" class="dcontexto">Ajuda
       <span><strong>Informe no nome do destinat&aacute;rio, evite nomes comuns, exemplo, no caso 
                     de Maria Prestes Paiva escreva somente Prestes Paiva, 
                     isso aumenta a velocidade na pesquisa. 
                     Outra dica &eacute; escolher o REMETENTE na caixa logo acima o resultado ser&aacute; mais fiel a sua busca.
       </strong>
	   </span>
	   </span>
        
        </td>

       </tr>
	   
	     




      <tr>
        <td>COD. BARRAS </td>
        <td><input type size=50  name="idexterno" onkeypress="onenter_submit(event,'form_busca')" />
        &nbsp;
          <span class="formata">
       <a href="#" class="dcontexto">Ajuda
       <span><strong>Para pesquisar v&aacute;rios pedidos separe por ESPA&Ccedil;O ou BARRA  Exemplo1: 12345 87878 9898998
       Exemplo 2 : 8874874/88784847/6698956
       </strong>
	   </span>
       </span>
        </td>
      </tr>
    <tr>
        <td>NOTA FISCAL </td>
        <td><input type=text size=10 name=notafiscal value='' /></td>
      </tr>
	  <tr>
        <td>ID INTERNO </td>
        <td><input type=text size=10 name=idinterno value='' /></td>
      </tr>
      
      <tr>
        <td>N. LISTA</td>
        <td><input type=text size=10 name=numlista value='' /></td>
      </tr>
      
      
      <tr>
        <td>LISTAS ANTERIORES</td>
        <td><input type=text size=10 name=numlista2 value='' /></td>
      </tr>
      
      
      
      
      <tr>
        <td>NUMERO DA RA (EXCLUSIVO DM)</td>
        <td><input type=text name=contacorrente value=''></td>
      </tr>
      <tr>


<tr>
        <td>CPF da RA (EXCLUSIVO DM)</td>
        <td><input type=text name=cpf value=''></td>
      </tr>
      <tr>
   

<tr>
        <td>DATA DE EMISSAO</td>
        <td><input type=text name=data_de class="dataemi" value=''></td>
      </tr>
      <tr>

<tr>
        <td>SETOR</td>
        <td><input type=text name=envelope value=''></td>
      </tr>
      <tr>   
<tr>
        <td>BASE</td>
        <td><select name='codbase' style='width:300px;'><?php combo("SELECT tb.codbase,tb.nomebase FROM tbdireitoauditoria as dir, tbbase as tb  WHERE dir.codbase = '".$_SESSION['IDBASE']."' and tb.ativa = 'S' and tb.idtransportadora='".$_SESSION['IDTRANSP']."' and tb.codbase = dir.codbasedireito ORDER BY tb.nomebase",$codbase,"T") ;?></select></td>
      </tr>

    <tr>
        <td>CAMPANHA/ANO</td>
        <td><input type=text name=campanha value=''>

    &nbsp;
       <span class="formata">
       <a href="#" class="dcontexto">Ajuda
       <span><strong>Informe a campanha no formato CC/AAAA
       </strong>
     </span>
     </span>
        </td>
      </tr>

      <tr>     



      
  
      


             <td colspan="2">
             <input type=submit value="Procurar">
         </td>
     </tr>
     <th colspan="2"></th>
  
     </table>   
  
     
     <br>
     <br>
     <br>
     
      
     <table class=tabela width=800 >

       <?php

             switch($opt){

                     case "B":
                     
                     if($codcliente)
                     {
                     if(!$nome)
                     {
                    // echo "VOCE DEVE INFORMAR O NOME DO DESTINAT�RIO";
                    // DIE;
                     }
                     
                     }
                     
                     
                     echo "<tr>
                     <td width=90% colspan=13>";

                

                     if ($nome)
                            {
                             $sql = "SELECT * FROM tbenderecoentrega WHERE nomeentrega ILIKE '%".strtoupper(str_replace(" ","%",trim($nome)))."%'";
                              if (strlen(trim($nome)) < 3) {
                 				      echo "Nome deve conter ao menos 3 caracteres";
			            	          die;
            			            }
 
                             $qry->executa($sql);
 
                             if ($qry->nrw){
                                     for($i=0;$i<$qry->nrw;$i++){
                                             $qry->navega($i);

                                             $w2 .= " tbentrega.idinterno = '".$qry->data["idinterno"]."' OR";
                                     }
                                     $where .= " (".substr($w2,0,strlen($w2)-2).") and ";
                             }
                             else
                             $nao_achou=1;

                     }
                     
                     
                     
                     if ($numlista2)
                            {
                             $sql = "SELECT * FROM tbmovimento WHERE numlista = $numlista2";
                              
 
                             $qry->executa($sql);
                                 
 
                             if ($qry->nrw){
                                     for($i=0;$i<$qry->nrw;$i++){
                                             $qry->navega($i);

                                             $w2 .= " tbentrega.idinterno = '".$qry->data["idinterno"]."' OR";
                                     }
                                     $where .= " (".substr($w2,0,strlen($w2)-2).") and ";
                             }
                             else
                             $nao_achou=1;
                             
                             
                             $sqlE = "select count(*) as total 
                                      from tbentrega
                                      where  st= 'E' and numlista in($numlista2)";
                                
                                $qryE->executa($sqlE);
                                
                            $sqlD = "select count(*) as total 
                                      from tbentrega
                                      where  st='D' and numlista in($numlista2)";
                                      
                                $qryD->executa($sqlD);

                     }

                     
                     
                     
                     
                     
                     
                     
                     if ($notfis)
                            {
                             $sql = "SELECT * FROM tb_notfis_nota_fiscal WHERE numero_nota_fiscal ILIKE '%".strtoupper(str_replace(" ","%",trim($notfis)))."%'";
                              if (strlen(trim($notfis)) < 3) {
                 				      echo "Nome deve conter ao menos 3 caracteres";
			            	          die;
            			            }
 
                             $qry->executa($sql);
 
                             if ($qry->nrw){
                                     for($i=0;$i<$qry->nrw;$i++){
                                             $qry->navega($i);
                                             $iiid = $qry->data["idinterno"];
                                             if($iiid)
                                                  $w2 .= " tbentrega.idinterno = '".$qry->data["idinterno"]."' OR";
                                     }
                                     $where .= " (".substr($w2,0,strlen($w2)-2).") and ";
                             }
                             else
                             $nao_achou=1;

                     }



                     if($chave2){
                     
                     
                     if($chave2 and strpos($chave2,"/")>0)
                     $where .= " tbentrega.numagencia in ('".trim(strtoupper(str_replace("/","','",$chave2)))."') and";
                     
                     elseif($chave2 and strpos($chave2," ")>0)
                     $where .= " tbentrega.numagencia in ('".trim(strtoupper(str_replace(" ","','",$chave2)))."') and";
                     
                     
                     
                     
                     }




                       if ($responsavel)
                            {
                             $sql = "SELECT * FROM tbenderecoentrega WHERE 
                                     responsavelentrega 
                                    ILIKE '%".strtoupper(str_replace(" ","%",trim($responsavel)))."%'";
                              if (strlen(trim($responsavel)) < 3) {
                 				      echo "Nome deve conter ao menos 3 caracteres";
			            	          die;
            			            }
 
                             $qry->executa($sql);
 
                             if ($qry->nrw){
                                     for($i=0;$i<$qry->nrw;$i++){
                                             $qry->navega($i);

                                             $w2 .= " tbentrega.idinterno = '".$qry->data["idinterno"]."' OR";
                                     }
                                     $where .= " (".substr($w2,0,strlen($w2)-2).") and ";
                             }
                             else
                             $nao_achou=1;

                     }


                     $qry->nrw = 0;//limpando o qry

                     $idexterno = trim($idexterno);
                     $idexterno = str_replace("%O", "_",$idexterno);
                
                    
                     

                    if($idexterno and strpos($idexterno,"/")>0)
                     $where .= " tbentrega.idexterno in ('".trim(strtoupper(str_replace("/","','",$idexterno)))."') and";
                     
                     elseif($idexterno and strpos($idexterno," ")>0)
                     $where .= " tbentrega.idexterno in ('".trim(strtoupper(str_replace(" ","','",$idexterno)))."') and";

                     elseif ($idexterno)
                     //$where .= " tbentrega.idexterno ilike  '%".$idexterno."%' and"; 
                     $where .= " tbentrega.idexterno =  '".$idexterno."' and"; 					 
                    
                    


                     //echo $where;
                     
                     
                     
                     if ($idinterno)
                     $where .= " tbentrega.idinterno='".$idinterno."' and";
                     if ($numlista)
                     $where .= " tbentrega.numlista='".$numlista."' and";

                  //  if ($lote)
                  //   $where .= " tbentrega.numloteinterno='".$numloteinterno."' and";

                     if ($lote)
                     $where .= " tbentrega.numlotecliente='".$lote."' and";

                 
                   if ($codcliente)
                     $where .= " tbentrega.codcliente = $codcliente and";
                     
                       if ($cpf)
                     $where .= " tbentrega.cpf = $cpf and";
                 
                 
                 
                 
                 
                                if ($contacorrente)
                     $where .= " tbentrega.numconta='".$contacorrente."' AND";//$where .= " numconta='".$contacorrente."' and";

                                if ($envelope)
                     $where .= "  tbentrega.primeiroenvelope='".$envelope."' AND"; //$where .= " numagencia='".$agencia."' and";

                         if ($notafiscal)
                     $where .= "  tbentrega.numnotafiscal LIKE '%".($notafiscal*1)."%' AND"; //$where .= " numagencia='".$agencia."' and";
                        

                    if(!empty($data_de)){
                      $data_format = explode("/", $data_de);
                      $data_de = $data_format[2]."-".$data_format[1]."-".$data_format[0];
                      $where .= " dataemissao = '".$data_de."' AND";
                    }
                      
                    if(!empty($codbase)){
                      $where .= " codbase = '$codbase' AND "; 
                    }

                    if(!empty($campanha)){
                      $campanhaformat = explode("/", $campanha);
                      $campanha = ((int)$campanhaformat[0])."/".$campanhaformat[1];
                      $where .= " numloteinterno LIKE '".$campanha."' AND ";
                    }

                     //Se for um cliente conectado separa somente o q for dele
                     if ($_SESSION['IDCLIENTE'] && $_SESSION['IDCLIENTE']<>" " && $_SESSION['IDCLIENTE'] <> 0){
                             $where .= " tbentrega.codcliente in(".$_SESSION['IDCLIENTE'].") and";
                     }

                     if ($where){
                             $where = substr($where,0,strlen($where)-4);
                             $where = "WHERE ".$where;
                     }else
                     $nao_achou=1;

                     $where .= " and tbentrega.idtransportadora = '".$_SESSION['IDTRANSP']."'";
				          	 $where = $where.'order by dataemissao';
                     $sql = "SELECT tbentrega.codbase, tbentrega.idinterno,tbentrega.dataemissao,tbentrega.numnotafiscal,tbentrega.valorentrega,
                     tbentrega.idtipomovimento,tbentrega.codcliente,tbentrega.numlotedigital,tbentrega.numconta,
                     tbentrega.numloteinterno,tbentrega.codcliente,
                     tbentrega.idexterno,tbentrega.dataoperacao, tbentrega.numlista,tbentrega.primeiroenvelope  FROM tbentrega $where LIMIT 1000";
                    //echo $sql;
                    //die;
                     //break;
                     if (!$nao_achou)
                     $qry->executa($sql);
					 
                      
                     
                     if ($qry->nrw){

                           echo "
                           
                           
                           <tr><td colspan=13><b>Total de Encomendas Localizadas: ".$qry->nrw." Total Encomendas Entregue: ".$qryE->data["total"]." Total Encomendas Devolvido: ".$qryD->data["total"]."</b></td></tr>
                           <tr  bgcolor=#cccccc>
                           <th align=left>ID INTERNO</th>
                           <th align=left>C. BAR/NOTA FISCAL</th>
                           <th align=left>REMETENTE</th>
                           <th align=left>DESTINATARIO</th>
						              <th align=left>CEP</th>
						   <th align=left>EMISS&Atilde;O</th>
                           <th align=left>STATUS</th>
                           <th align=left>ENTREGADOR</th>
                           <th align=left>NUM LISTA</th>
                           <th align=left>SETOR</th>
                           <th align=left>VALOR</th>
                           <th align=left>CAMPANHA</th> 
                           <th align=left>RA</th> 
                           </tr>";
                         
                         $ttt=0;
                         For($i=0;$i<$qry->nrw;$i++){
                            $qry->navega($i);
						
                            $qry2->data["nomeentrega"] = "";
                            $qry2->data["cidadeentrega"] = "";
                            $sql2 = "SELECT nomeentrega,cidadeentrega,cepentrega FROM tbenderecoentrega WHERE idinterno = '".$qry->data["idinterno"]."'";
                            $qry2->executa($sql2);
                            $qry3->data["nometipomovimento"] = "";
                            $sql2 = "SELECT nometipomovimento FROM tbtipomovimento WHERE idtipomovimento = '".$qry->data["idtipomovimento"]."'";
                            $qry3->executa($sql2);
                            $qry7->data["codocorrencia"] = "";
                           
                            $sql2 = "SELECT codocorrencia from tbocorrencia WHERE idinterno = '".$qry->data["idinterno"]."'";
                            $qry7->executa($sql2);
                           
                            $sql2 = "SELECT nomecliente from tbcliente WHERE codcliente = '".$qry->data["codcliente"]."'";
                            $qry12->executa($sql2);
                           
                           $sql2 = "SELECT tbcourier.nomecourier from tbcourier,tbmovimento
                            WHERE  tbcourier.codigocourier = tbmovimento.codigocourier 
                             and idinterno = '".$qry->data["idinterno"]."'
                            limit 1";
                            $qry13->executa($sql2);
                            $cu = $qry13->data["nomecourier"];
                           
                           $sql2 = "SELECT codbase,nomebase FROM tbbase  WHERE codbase = '".$qry->data["codbase"]."'
                            limit 1";
                            $qrybase->executa($sql2);
                            $cu = $qrybase->data["nomebase"];
                           
                            if(!$idinterno)
                                 $idinterno = $qry->data["idinterno"];
                            $de = $qry2->data["nomeentrega"];
                            $remetente = $qry12->data["nomecliente"];
                            $cep = $qry2->data['cepentrega'];
                    
                          $numnotafiscal = abs($qry->data["numnotafiscal"]);
                       
						
                            if(empty($ttt))
						    {
								$ttt = $qry->data["valorentrega"].'.';
							} else {
								$ttt = $ttt + $qry->data["valorentrega"].'.';
							}
						
						
						
                            echo "<tr bgcolor=#".(($i%2)?"eeeeee":"ffffff").">
                                 <td><a href='auditoria_dm.php?opt=S&idinterno=".$qry->data["idinterno"]."&idexterno=".$qry->data["idexterno"]." '>".$qry->data["idinterno"]."</a></td>
                                 <td>".$qry->data["idexterno"]."</td>
                                 
                                 <td>".substr($remetente,0,15)."</td>
								                 <td>".substr($de,0,20)."</td>
                                 <td>".$cep."</td>
                                 <td>".mostra_data($qry->data["dataemissao"])."</td>
                                 
                                 <td>".substr($qry3->data["nometipomovimento"],0,15)."</td>
                                 <td>".$cu."</td> 
                                 <td>".$qry->data["numlista"]."</td>  
                                 <td>".$qry->data["primeiroenvelope"]."</td>  
                                 <td>".$qry->data["valorentrega"]."</td>
                                 <td>".$qry->data["numloteinterno"]."</td>
                                 <td>".(($qry->data["codcliente"]== 6670)?$qry->data["numconta"]:$qry->data["numnotafiscal"])."</td>
                                 </tr>";
								             
                            } 
                            
							if($_SESSION['IDUSER']=='1448' or $_SESSION['IDUSER'] == '643'){
								echo "TOTAL DA LISTA:  ".$qry->nrw." // Valor total de: ".number_format($ttt, 2, ',', '.')."";
							} else {
								echo "TOTAL DA LISTA:  ".$qry->nrw."";
							
							}
							              }
							       else
							       
							       
                           echo "<tr>
                           <td colspan=2 align=center><font color=#ff0000>Nenhum item a ser auditado com esse filtro</font></td>
                           </tr>";
							             break;   
                       echo "</tr>";
			
								      }
								      
								 
			 
			 
           ?>
           </td>
       </tr>


   </form>     
  </table>

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
  });
} )( jQuery );   
</script>
<?php
// pega o Footer
require_once("inc/footer.inc");