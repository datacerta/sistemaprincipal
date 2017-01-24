<?php
/**
 * Auditoria
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel
$prgTit = "Auditoria do Sistema";

// pega a configuracao
require_once("inc/config.inc");
require_once("classes/fpdf/fpdf.php");

// consulta
$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry6 = new consulta($con);
$qry7 = new consulta($con);
$qry8 = new consulta($con);
$qry9 = new consulta($con);
$qry10 = new consulta($con);
$qry11 = new consulta($con);
$qryDigita = new consulta($con);
$qryFatBase = new consulta($con);
$qry_ponto = new consulta($con);
$qry_base = new consulta($con);

$sql = "SELECT nivelusuario FROM tblogin WHERE codlogin=".$_SESSION["IDUSER"];
$qry->executa($sql);
$nivelusuario = $qry->data["nivelusuario"];

// seta o link atual
$selfLink = HOST.$PHP_SELF."?token={$rnd}";

$tipo_data = "datapromessa";

// pega o header
require_once("inc/header.inc");
?>

<!-- CSS Local -->
<link href="<?=HOST?>/tablecloth/tablecloth.css"  rel="stylesheet" type="text/css" media="screen" />
<link href="<?=HOST?>/datetime/calendar-blue.css" rel="stylesheet" type="text/css" media="all"  title="blue" />

<!-- JS Local -->
<script type="text/javascript" src="<?=HOST?>/datetime/calendar.js?token=<?=$rnd?>"></script>
<script type="text/javascript" src="<?=HOST?>/datetime/lang/calendar-br.js?token=<?=$rnd?>"></script>
<script type="text/javascript" src="<?=HOST?>/datetime/calendar-setup.js?token=<?=$rnd?>"></script>
<script type="text/javascript" src="<?=HOST?>/classes/layer.js?token=<?=$rnd?>"></script>

<div style="width: 800px; margin: 0 auto;">

<form name="form" action="<?=$selfLink?>" method="post">
<input type="hidden" name="opt" value="B" />

<table>
         <?php
        
        
            ECHO "<th>Base</th>";
        ECHO "<th>Cliente</th>";
		

if ($id_cliente > 0)
		 ECHO "<th>Produto</th>";
         ?>
     
	     <th>Data De</th>
		 <th>Data At&eache;</th>
		 <th>Setor</td>
		 <th>Filtrar</tf>
		 <th></th>
		 <th></th>
		
		 
         
    

      <tr  align=center bgcolor=#eeeeee>

             <?php
			 
             if ( $_SESSION['IDTRANSP'] > 0){
			 //$transp =  $_SESSION['IDTRANSP'];
             ?>
         <td>
             <select name=id_base>
            <?php
			//echo $transp;
			
             $sql = "SELECT tb.codbase,tb.nomebase FROM tbdireitoauditoria as dir, tbbase as tb  
			 WHERE dir.codbase = '".$_SESSION['IDBASE']."' and tb.idtransportadora='".$_SESSION['IDTRANSP']."'
			 and tb.codbase = dir.codbasedireito ORDER BY tb.nomebase";
              echo $sql;
			 
			 $qry->executa($sql);

             if ($qry->nrw){

                     echo "<option value=0>Selecione a base</option>";

                     if($nivelusuario >= 2)
                     echo "<option value=-1 ".(($id_base==0 or $id_base==-1)?"selected":"").">TODOS</option>";

                     for ($i=0;$i<$qry->nrw;$i++){
                             $qry->navega($i);
                             echo "<option ".(($id_base==$qry->data["codbase"])?"selected":"")." 
							 value=".$qry->data["codbase"].">".substr($qry->data["nomebase"],0,15)."</option>";
                     }
             }
             else
             echo "<option selected value=-1>Nenhuma base cadastrada</option>";
             echo "</select>
                  </td>
               ";

             ?>
        <td>
             <select onchange="javascript:document.form.opt.value='';document.form.submit();"  name=id_cliente>
            <?php
             if($_SESSION['IDCLIENTE'] && $_SESSION['IDCLIENTE']<>" " && $_SESSION['IDCLIENTE'] > 0){
                     $sql = "SELECT codcliente,nomecliente FROM tbcliente WHERE status=5 and idtransportadora = '".$_SESSION['IDTRANSP']."' and codcliente in (". 					                     $_SESSION['IDCLIENTE'].")  and status = 5 ORDER BY nomecliente";
					 
             }else{
                     $sql = "SELECT codcliente,nomecliente FROM tbcliente WHERE idtransportadora = '".$_SESSION['IDTRANSP']."' and status =5 ORDER BY nomecliente";
             }       echo $sql;

             $qry->executa($sql);

             if ($qry->nrw){
                     echo "<option value=-1 ".(($id_cliente==0 or $id_cliente==-1)?"selected":"").">TODOS</selected>";
                     for ($i=0;$i<$qry->nrw;$i++){
                             $qry->navega($i);
                             echo "<option ".(($id_cliente==$qry->data["codcliente"])?"selected":"")." value=".$qry->data["codcliente"].">".substr($qry->data[                             "nomecliente"],0,15)."</option>";
                     }
             }
             else
             echo "<option selected value=-1>Nenhum cliente cadastrado</selected>";
             echo "</select>
                  </td>
               ";
             }

             if ($id_cliente > 0){
             ?>
         <td>
             <select name=id_produto>
            <?
             $sql = "SELECT
                              *
                       FROM
                              tbclienteproduto
                       WHERE
                              idtransportadora = '".$_SESSION['IDTRANSP']."'
                              and codcliente = '$id_cliente'";

             $qry->executa($sql);

             if ($qry->nrw){
                     echo "<option value=-1 ".(($id_produto==0 or $id_produto==-1)?"selected":"").">TODOS</selected>";
                     for ($i=0;$i<$qry->nrw;$i++){
                             $qry->navega($i);

                             $sql2 = "SELECT
                                        *
                                FROM
                                        tbproduto
                                WHERE
                                        codigoproduto = ".$qry->data["0"];
                             $qry2->executa($sql2);


                             echo "<option ".(($id_produto == $qry2->data["codigoproduto"])?"selected":"")." 
							 value=".$qry2->data["codigoproduto"].">".substr($qry2->data["nomeproduto"],0,10)."</option>";
                     }
             }
             else
             echo "<option selected value=-1>Nenhuma produto cadastrado</selected>";
             echo "</select>
            </td>";
             }
             ?>
         
              
                
              
               <td>
               <input type=text size=8 name=data_de id=data_de value='<?=(($data_de)?"$data_de":date("d/m/Y"));?>'>
               <img src='datetime/img.gif' border=0 id='data_entrega_de' style='cursor: pointer;' title='Selecione uma data'>
               <script type='text/javascript'>
Calendar.setup({   
        inputField     :    'data_de',     // id of the input field
        ifFormat       :    'dd/mm/y',      // format of the input field
        button         :    'data_entrega_de',  // trigger for the calendar (button ID)
        align          :    'Bl',           // alignment (defaults to 'Bl')
        singleClick    :    true
});
</script>
</td>
                 
                   
                    <td><input type=text size=8 name=data_ate id=data_ate value='<?=(($data_ate)?"$data_ate":date("d/m/Y"));?>'>
                                        <img src='datetime/img.gif' border=0 id='data_entrega_ate' style='cursor: pointer;' title='Selecione uma data'></td>
                                        <script type='text/javascript'>
Calendar.setup({
        inputField     :    'data_ate',     // id of the input field
        ifFormat       :    'dd/mm/y',      // format of the input field
        button         :    'data_entrega_ate',  // trigger for the calendar (button ID)
        align          :    'Bl',           // alignment (defaults to 'Bl')
        singleClick    :    true
});
</script>

                
                 
                 
         
		
		
		
		
		  
		
		
              
                    <td><input type=text size=8 name=setor value='<?=(($setor)?"$setor":"");?>'></td>
                 
                 
		
		  
		  
         
         
              
         
                    
                    
   
         <td colspan=2 align=left>
            <input type=radio name=audita_todos_movs value='s'  <?=(($audita_todos_movs=='s')?"checked":"");?>> Tudo
            <input type=radio name=audita_todos_movs value='n'  <?=(($audita_todos_movs=='n' or !isset($audita_todos_movs))?"checked":"");?>> Pendentes
         </td>
                   
         <td colspan="5">
             <input type=submit value="Procurar">
         </td>
         
        </tr>


 

      
     
       
      
		     
                  
       <?php

             switch($opt){

                     case("S"):
                     //informacoes completa da encomenda

                     $sql = "SELECT * FROM tbentrega WHERE idinterno = '$idinterno'";
                     $qry->executa($sql);

                     $id_cliente = $qry->data["codcliente"];
					           $caixalote = $qry->data["nr_caixa_lote"];
					           $lotecaixa = $qry->data["numlotedigital"];
					           
				          	 $listafatura = $qry->data["listafatura"];
                     $id_produto = $qry->data["codigoproduto"];
                     $codigodaregiao = $qry->data["codigodaregiao"];
                     $idexterno = $qry->data["idexterno"];
                     


                     if($qry->data["numlista"]){
                        $sql = "SELECT tbtipolista.nometipolista FROM tblista, tbtipolista WHERE 
							          tblista.numlista = '".$qry->data["numlista"]."' 
							          AND tblista.codigotipolista=tbtipolista.codigotipolista";
                        $qry2->executa($sql);
                        $lista_nometipo = "(".$qry2->data["nometipolista"].")";
                       }

                     $sql = "SELECT nomebase FROM tbbase WHERE codbase = '".$qry->data["codbase"]."'";
                     $qry2->executa($sql);
                     $nomebase = $qry2->data["nomebase"];

                     $sql = "SELECT nomecliente FROM tbcliente WHERE codcliente = '$id_cliente'";
                     $qry2->executa($sql);
                     $nomecliente = $qry2->data["nomecliente"];

                     $sql = "SELECT nomeproduto FROM tbproduto WHERE codigoproduto = '$id_produto'";
                     $qry2->executa($sql);
                     $nomeproduto = $qry2->data["nomeproduto"];

                     // agencia e conta corrente



             ?>

                     	     <th align=center bgcolor=#eeeeee colspan=7>
      <input type="button" name="ocorrencia" class="botao" onclick='javascript:window.open("ocorrencia.php?opt=I&idinterno=<?=$idinterno;?>&popup=1","Ocorrencia","width=640, height=480, scrollbars=auto, menubar=no, location=no, status=yes, toolbar=no, resizable=yes");' value='Criar Ocorrência'>
       
       
       
       <input type="button" name="imprimir" class="botao" onclick='javascript:window.open("ar_fast.php?ar=&<?=$idinterno;?>","Imprimir","width=640, height=480, scrollbars=yes, menubar=yes, location=yes, status=yes, toolbar=yes, resizable=yes");' value='Imprime AR da Entrega'>
				
				<?php
				if($opt){
           if($opt=="S")
             $link_botao_voltar = $HTTP_REFERER.$_SESSION["sessao_string_retornar"];
             echo "<input type='button' class='botao' onclick=\"document.location.href='$link_botao_voltar'\" value='Voltar para a página anterior'>";
          }
	
				  ?>
  
  
    <input type="button" name="ar" class="botao" 
    onclick='javascript:window.open("exibe_pdf.php?opt=I&idexterno=<?=$idexterno;?>");' value='VER FOTO DO LOCAL '>
  
             <?php







                     echo "<tr bgcolor=#eeeeee align=left>
                                 <th>Remetente:</th><th><b>".$nomecliente."</b></th>
                                 <th colspan=6><b>".$nomeproduto."</b></th>
                             </tr>";
                     echo "<tr>
                                 <td colspan=8><br></td>
                             </tr>";
                     $sql = "SELECT
                                       *
                               FROM
                                       tbentrega as entrega, tbenderecoentrega as endereco
                               WHERE
                                       entrega.idinterno = endereco.idinterno and entrega.idinterno='$idinterno'";
                $qry->executa($sql);
                $sql = "SELECT * FROM tbtipomovimento WHERE idtipomovimento = '".$qry->data["idtipomovimento"]."'";
                $qry3->executa($sql);

                $digitador="Arquivo";
                if ($qry->data["id_login_digita"]>0)
				        {
                   $sql = "SELECT * FROM tblogin WHERE codlogin = '".$qry->data["id_login_digita"]."'";
                   $qryDigita->executa($sql);
                   $digitador = $qryDigita->data["nomelogin"];
                }

					     if ($qry->data["numlista"]){
					     
					     $sql = "select * from tblistafaturamentobase where numlista =  '".$qry->data["numlista"]."' limit 1";
              // echo $sql;
               $qryFatBase->executa($sql);
               }
					
				            
                  
                        



                     echo "<tr bgcolor=#eeeeee>
                             <td>Destinatário:</td><td><b>".$qry->data["nomeentrega"]."</b></td>
                             
                             <td>Emissão:</td><td  width=20 
								             colspan=1><b>".mostra_data($qry->data["dataemissao"])."</b></td>
                             
                             
                             <td width=10>Digitador:</td><td  
								             ><b>".substr($digitador,0,10)."</b></td>
                             </tr>
                             
                             
                             <tr bgcolor=#eeeeee>
                             <td>Endereço:</td><td width=40%><b>".$qry->data["enderecoentrega"]."</b></td>
                             <td nowrap>Vencimento:</td>
								             <td  colspan=5><b>".mostra_data($qry->data["datavencimento"])."</b></td>
                             </tr>
                             <tr bgcolor=#eeeeee>
                                 <td>Complemento:</td><td><b>".$qry->data["complementoenderecoentrega"]."</b></td>
                                 <td>Lista:</td><td  colspan=5><b>".$qry->data["numlista"]." $lista_nometipo</b></td>
                             </tr>
                             <tr bgcolor=#eeeeee>
                                 <td>Bairro:</td><td><b>".$qry->data["bairroentrega"]."</b></td>
                                 <td>Lote Interno:</td><td  colspan=5><b>".$qry->data["numloteinterno"]."</b></td>
                             </tr>
                             <tr bgcolor=#eeeeee>
                                 <td>Cidade/Região:</td><td><b>".$qry->data["cidadeentrega"]."/".$codigodaregiao."</b></td>
                                 <td>Lote Externo:</td><td  colspan=5><b>".$qry->data["numlotecliente"]."</b></td>
                             </tr>
                             <tr bgcolor=#eeeeee>
                                 <td>CEP:</td><td><b>".$qry->data["cepentrega"]."</b></td>
                                 <td>Valor:</td><td  colspan=1><b>".$qry->data["valorentrega"]."</b></td>
                                 <td>peso:</td><td  colspan=2><b>".$qry->data["pesoentrega"]."</b></td>
                             </tr>
                             <tr bgcolor=#eeeeee>
                                 <td>Telefone:</td><td><b>".$qry->data["foneenderecoentrega"]."</b></td>
                                 <td>Nome Recebedor:</td><td  colspan=5><b>".$qry->data["nomerecebedor"]."</b></td>
                             </tr>
                             <tr bgcolor=#eeeeee>
                                 <td>UF:</td><td><b>".$qry->data["estadoentrega"]."</b></td>
                                 <td nowrap>doc. Recebedor:</td><td 
								             colspan=5><b>".$qry->data["docrecebedor"]."</b></td>
                             </tr>
                             <tr bgcolor=#eeeeee>
                                 <td>Data Promessa:</td><td><b>".mostra_data($qry->data["datapromessa"])."</b></td>
                 
                 
                                 <td>N. Sedex:</td>
                                 
                                 <td><a href='sro_remoto.php?opt=S&idinterno=".$qry->data["numerosedex"]."'>".$qry->data["numerosedex"]."</a></td>
                                 
                                 
                             
                                 <td>P.V :</td><td
								                  colspan=2><b>".$qry->data["numnotafiscal"]."</b></td>
                             
                             
                             
                             </tr>
                             
                             
                             
                             <tr bgcolor=#eeeeee>
                                 <td>Status:</td><td><b>".$qry3->data["nometipomovimento"]."</b></td>
                                 <td>Data Coleta:</td><td  
								 colspan=5><b>".mostra_data($qry->data["datacoletado"])."</b></td>
                             </tr>
                             <tr bgcolor=#eeeeee>
                                 <td>ID Interno:</td><td><b>".$idinterno."</b></td>
                                 <td>ID Externo:</td><td  colspan=5><b>".$qry->data["idexterno"]."</b></td>
                             </tr>

                             <tr bgcolor=#eeeeee>
                                 <td>Caixa Lote:</td><td><b>".$caixalote."/".$lotecaixa."</b></td>
                                 <td>Lista Fatura:</td><td width=8 colspan=1  ><b>".$listafatura/*$qry->data["numconta"] da tbentrega*/ ."</b></td>
                                 <td>Fat. Base:</td><td  colspan=3><b>".mostra_data($qryFatBase->data["data"])."</b></td>
                             </tr>";

                     //informacoes de cheque e talao
                                  

                    

                     $sql = "SELECT * FROM tbentrega WHERE idinterno = '$idinterno'";

                     $qry11->executa($sql);

                           if ($qry11->data["st"]=='E' or $qry11->data["st"]== 'N')
						   		{
								$dte=mostra_data($qry->data["dataentrega"]);
								}
							else
								{
								$dte='';	  
								}

                    
                           echo "<tr bgcolor=#eeeeee>
                           <td>Base:</td><td><b>".$nomebase."</b></td>
                           <td>Entrega no Cliente :</td><td f colspan=5> <b>".$dte."</b></td>
                                 
                            </tr>

                                 
                                 
                                 
                            </tr>

                            



                            <tr bgcolor=#eeeeee>
                                 <td nowrap>Obs Entrega :</td><td colspan=8><b>".$qry11->data["obsentrega"]."</b></td>

                            </tr>";
							
							
						

                     

                    
                     $qry2->nrw=0;
                     //ocorrencias
                     $sql = "SELECT tbocorrencia.codocorrencia,tbocorrenciatipo.tipo,tbocorrencia.datacriacao,tbocorrencia.assunto,tblogin.nomelogin FROM tbocorrencia, tbocorrenciatipo, tblogin WHERE tbocorrencia.codocorrenciatipo=tbocorrenciatipo.codocorrenciatipo AND tbocorrencia.codlogin=tblogin.codlogin AND tbocorrencia.idinterno='$idinterno' ORDER BY tbocorrencia.codocorrencia";

                     $qry2->executa($sql);
				
					 
                     if ($qry2->nrw){
                             echo "<tr bgcolor=#cccccc>";
                             echo "    <th><b>Cod. Ocorrência</b></th>";
                             echo "    <th><b>Tipo</b></th>";
                             echo "    <th><b>Assunto</b></th>";
                             echo "    <th><b>Data e Hora</b></th>";
                             echo "    <th><b>Usuário</b></th>";
                             echo "    <th>&nbsp;</th>";
                             echo "</tr>";

                             for($i=0;$i<$qry2->nrw;$i++){
                                     $qry2->navega($i);

                                     echo "<tr bgcolor=#".(($i%2)?"eeeeee":"ffffff").">";
                                     echo "    <td>".$qry2->data["codocorrencia"]."</td>";
                                     echo "    <td>".$qry2->data["tipo"]."</td>";
                                     echo "    <td>".$qry2->data["assunto"]."</td>";
                                     echo "    <td>".mostra_data($qry2->data["datacriacao"],1)."</td>";
                                     echo "    <td>".$qry2->data["nomelogin"]."</td>";
                                     echo "    <td align='center'><a href='#' onclick=\"javascript:window.open('ocorrencia.php?opt=V&codocorrencia=".$qry2->data["codocorrencia"]."&popup=1','VerOcorrencia','width=640, height=480, scrollbars=yes, menubar=no, location=no, status=yes, toolbar=no, resizable=yes');\">Ver detalhes</a></td>";
                                     echo "</tr>";
                             }
                     }

                     echo "          

                                   </td>
                             </tr>
                             <tr>
                               <td colspan=50 align=center width=100%>
                                  
                             ";
                     $qry2->nrw=0;
                  
                     $sql = "SELECT tbmovimento.dataoperacao,tbmovimento.horaoperacao,
                             tbmovimento.idmotivo,tbtipomovimento.nometipomovimento,tbmovimento.idtipomovimento,tbmovimento.idmovimento,tbmovimento.obs
                             FROM tbmovimento, tbtipomovimento
                             WHERE tbmovimento.idtipomovimento=tbtipomovimento.idtipomovimento 
                             and tbmovimento.idinterno = '$idinterno' and
                             tbmovimento.idtipomovimento IN ( '135','136','137','150','180','601', '602','160')
                             ORDER BY tbmovimento.dataoperacao ASC";
                     $qry2->executa($sql);

                     if ($qry2->nrw){
                             
							 echo "<tr>";
                             //comentada por daniel: echo "    <td><b>Tentativa</td>";
                             echo "    <th><b>Tipo</b></th>";
                             echo "    <th><b>Data e Hora</b></th>";
                             echo "    <th><b>Motivo</b></th>";
                             echo "    <th><b>Obs</b></th>";
			                 echo "    <th><b></b></th>";
							 echo "    <th><b></b></th>";
                             echo "</tr>";

                             for($i=0;$i<$qry2->nrw;$i++){
                                     $qry2->navega($i);

                                     $qry3->data["motivo"] = "";
                                     if ($qry2->data["idmotivo"]){
                                             $sql = "SELECT motivo FROM tbmotivo WHERE idmotivo = '".$qry2->data["idmotivo"]."'";
                                             $qry3->executa($sql);
                                     
                                     }



                                     echo "<tr bgcolor=#".(($i%2)?"eeeeee":"ffffff").">";
                                     //comentada por daniel: echo "    <td>".($i+1)."</td>";
                                     echo "    <td nowrap>".$qry2->data["nometipomovimento"]."</td>";
                                     echo "    <td>".mostra_data($qry2->data["dataoperacao"])." ".$qry2->data["horaoperacao"]."</td>";
                                     echo "    <td>". (($qry3->data["motivo"]) ? $qry3->data["motivo"] : "Motivo não cadastrado")."</td>";
                                     echo "    <td colspan=3>". $qry2->data["obs"]."</td>";
                                     echo "</tr>";
                             }
                     }

                     echo "          

                                   </td>
                             </tr>
                            ";
                     


                  
                     echo "<tr bgcolor=#cccccc>
                                     <td colspan=50 align=center><b>Nenhum movimento nesta encomenda</td>
                                 </tr>";

                     

 
					// echo "<td align='center'><a href="http://201.57.166.18/imagens/".$lotecaminho."/".$qry10->data["idexterno"].".png border=0></a>

					 

					 $sql = "SELECT numlotedigital,numloteinterno,numlotecliente,idexterno,nr_caixa_lote FROM tbentrega WHERE idinterno = '$idinterno'";
           $qry10->executa($sql);
           $lotecaminho =  $qry10->data["nr_caixa_lote"];
           //echo $lotecaminho;

				
         
        
     
					
                     break;
 
                                         //*******************************************************

                     case "B":
                     // quantidade de encomendas por status
                     $link_botao_voltar = $PHP_SELF;
                     
                     echo "<tr>
                     <td>";

                     if ($_SESSION['IDTRANSP'] > 0)
                     $where .= "  tbentrega.idtransportadora = '".$_SESSION['IDTRANSP']."' and";
                       else{
                             //todas: todas transportadoras que a pessoa tem direito
                             $qry->nrw = 0;
                             $sql = "SELECT idtransportadora FROM tblogin WHERE codlogin=".$_SESSION["IDUSER"];
                             $qry->executa($sql);
                             $where .= " (tbentrega.idtransportadora = '".$qry->data["idtransportadora"]."'";

                             $qry->nrw = 0;
                             $sql = "SELECT * FROM tbdireitotransportadora WHERE codlogin=".$_SESSION["IDUSER"]." and idtransportadora<>".$qry->data["idtransportadora"];
                             $qry->executa($sql);

                             for ($j=0;$j<$qry->nrw;$j++){
                                     $qry->navega($j);
                                     $where .= " or tbentrega.idtransportadora = '".$qry->data["idtransportadora"]."'";
                             }

                             $where .= ") and ";
                    }

                     if ($id_base > 0 && $id_base)
                             $where .= " tbentrega.codbase = '$id_base' and";
                     else{
                             //todas: todas bases que a pessoa tem direito
                             $qry->nrw = 0;
                             $sql = "SELECT * FROM tbdireitoauditoria WHERE codbase=".$_SESSION["IDBASE"]." and codbasedireito<>".$_SESSION["IDBASE"];
                             $qry->executa($sql);
                            // $where .= " (tbentrega.codbase = '".$_SESSION["IDBASE"]."'";

                             for ($j=0;$j<$qry->nrw;$j++){
                                     $qry->navega($j);
                          //           $where .= " or tbentrega.codbase = '".$qry->data["codbasedireito"]."'";
                             }

                          //   $where .= ") and ";
                     }

                     if ($id_cliente > 0 && $id_cliente)
                         $where .= " tbentrega.codcliente = '$id_cliente' and";

                     if ($_SESSION['IDCLIENTE'] && $_SESSION['IDCLIENTE']<>" " || $_SESSION['IDCLIENTE'] <> 0)
                         $where .= " tbentrega.codcliente = '".$_SESSION['IDCLIENTE']."' and";

                     if ($id_produto > 0)
                         $where .= " tbentrega.codigoproduto = '$id_produto' and";
                     
                     if ($setor > 0)
                          $where .= " tbentrega.numlotecliente = '$setor' and";




                     /*
                     Aqui se alterar o range de lotes
                     */
                     if($lote_de && $lote_ate && ($lote_ate - $lote_de) <= 5){
                             if (($lote_de) <= ($lote_ate)){
                                     $where .= "  tbentrega.$tipo_lote >= '$lote_de' and  tbentrega.$tipo_lote <= '$lote_ate' and";
                             }
                             else
                             echo "<tr><td colspan=2>Lote Inválido</td></tr>";
                     }
                     else
                     echo "<tr><td colspan=2></td></tr>";

                     if($data_de && $data_ate){
                             if (conv_data($data_de) <= conv_data($data_ate)){
                                     $campo = explode("/",$data_de);
                                     $mes_inicial = $campo[2]."-".$campo[1]."-".$campo[0];
                                     $campo = explode("/",$data_ate);
                                     $mes_ate = $campo[2]."-".$campo[1]."-".$campo[0];
                                     $where .= "  tbentrega.$tipo_data >= '$mes_inicial' and  tbentrega.$tipo_data <= '$mes_ate' and";
                             }
                             else
                             echo "<tr><td colspan=2></td></tr>";
                     }


                    if($audita_todos_movs!="s")
                    $where.=" (tbtipomovimento.audita='t' or tbtipomovimento.audita isnull) and";

                     if ($where){
                             $where = substr($where,0,strlen($where)-4);
                             //$where = "WHERE ".$where;
                                        $where = " AND ".$where;
                     }

                     if ($linhamovimento){
                     $sql = "SELECT
                             count(tbentrega.idtipomovimento) as qtde,
                             tbentrega.idtipomovimento,
                             tbtipomovimento.nometipomovimento,
                             tbtipomovimento.detalhenomemovimento
                             FROM tbentrega, tbtipomovimento
                             WHERE tbtipomovimento.idlinhamovimento = '$linhamovimento' and tbentrega.idtipomovimento=tbtipomovimento.idtipomovimento $where GROUP BY tbtipomovimento.nometipomovimento,tbentrega.idtipomovimento,tbtipomovimento.detalhenomemovimento ORDER BY tbtipomovimento.nometipomovimento,tbentrega.idtipomovimento,tbtipomovimento.detalhenomemovimento";
                     }
                     ELSE
                     {
                     $sql = "SELECT
 tbtipomovimento.idlinhamovimento as idtipomovimento,
      count(tbentrega.idinterno) as qtde, sum(tbentrega.valorentrega) as vtotal,
      (
       SELECT aaa.nometipomovimento FROM tbtipomovimento as aaa where aaa.idtipomovimento =  tbtipomovimento.idlinhamovimento
      ) as    nometipomovimento,
      (
       SELECT aaa.detalhenomemovimento FROM tbtipomovimento as aaa where aaa.idtipomovimento =  tbtipomovimento.idlinhamovimento
      ) as    detalhenomemovimento   ,
      (
        SELECT COUNT(bbb.idlinhamovimento) FROM tbtipomovimento as bbb WHERE bbb.idlinhamovimento =  tbtipomovimento.idlinhamovimento
      ) as temspringouflex
                            FROM
                                   tbentrega, tbtipomovimento
                            WHERE
                                   tbentrega.idtipomovimento=tbtipomovimento.idtipomovimento
                                   $where
                            GROUP BY
                                  tbtipomovimento.idlinhamovimento
                            ORDER BY
                                  tbtipomovimento.idlinhamovimento";
                       }
					   
					   //echo $sql."<br>";
                     $qry->executa($sql);
                     //echo $sql;

                     if ($qry->nrw){
                             echo "<tr>
                                 <th>Quantidade</th>
                                 <th  colspan=7 >Status</th>
                               </tr>";

                             For($i=0;$i<$qry->nrw;$i++){
                                     $qry->navega($i);

                                     $total_registros = $total_registros+$qry->data["qtde"];
                                     $valor_total = $total_registros+$qry->data["vtotal"];

                                     //$sql2 = "SELECT nometipomovimento FROM tbtipomovimento WHERE idtipomovimento = '".$qry->data["idtipomovimento"]."'";
                                     //$qry2->executa($sql2);

                                      $hamnsfleim = (($qry->data["temspringouflex"] > 1)?$opt."&linhamovimento=".$qry->data["idtipomovimento"]:"S2");
                                     echo "<tr bgcolor=#".(($i%2)?"eeeeee":"ffffff").">
                                 <td>".$qry->data["qtde"]."</td>
                                 <td  colspan=7 ><a href='$PHP_SELF?opt=$hamnsfleim&data_de=$data_de&data_ate=$data_ate&
                                                  id_transportadora=$id_transportadora&setor=$setor&id_cliente=$id_cliente
                                                  &id_produto=$id_produto&idtipomovimento=".$qry->data["idtipomovimento"]."
                                                  &lote_de=$lote_de&lote_ate=$lote_ate&tipo_lote=$tipo_lote&tipo_data=$tipo_data
                                                  &id_base=$id_base&audita_todos_movs=$audita_todos_movs' ".(($qry->data["detalhenomemovimento"])? " onmouseover=\"javascript:mostrarLayer('Informações','".addslashes($qry->data["detalhenomemovimento"])."')\" onmouseout=\"javascript:tirarLayer()\"":"").">".$qry->data["nometipomovimento"]."</a></td>
                               </tr>";      //S2
                             }

							 echo "<tr>
                                 <th> <font size =3>Total > </font> </th>
                                 <th  colspan=7 ><font size =3> $total_registros </font></th>
                               </tr>";
                               
                               
                               

							echo "<tr><td><b>Valor Total:</b> $valor_total</td></tr>";
                     }
                     else
                     echo "<tr>
                                 <td colspan=7 align=center><font color=#ff0000>Nenhum item a ser auditado com esse filtro</font></td>
                               </tr>";
                     break;


                     case "S2":
                     //encomendas com o status escolhido
                    // echo "fff0";
                    //die; 
                    
                     $link_botao_voltar = "$PHP_SELF?opt=B&id_transportadora=$id_transportadora&id_base=$id_base&id_cliente=$id_cliente&data_de=$data_de&data_ate=$data_ate&tipo_data=$tipo_data&audita_todos_movs=$audita_todos_movs";
                     echo "<tr>
                     <td>";

                     if ($_SESSION['IDTRANSP']> 0)
                     $where .= "  tbentrega.idtransportadora = '".$_SESSION['IDTRANSP']."' and";
                       else{
                             //todas: todas transportadoras que a pessoa tem direito
                             $qry->nrw = 0;
                             $sql = "SELECT idtransportadora FROM tblogin, tbentrega WHERE codlogin=".$_SESSION["IDUSER"];
                             $qry->executa($sql);
                             $where .= " (tbentrega.idtransportadora = '".$qry->data["idtransportadora"]."'";

                             $qry->nrw = 0;
                             $sql = "SELECT * FROM tbdireitotransportadora WHERE codlogin=".$_SESSION["IDUSER"]." and idtransportadora<>".$qry->data["idtransportadora"];
                             $qry->executa($sql);

                             for ($j=0;$j<$qry->nrw;$j++){
                                     $qry->navega($j);
                                     $where .= " or tbentrega.idtransportadora = '".$qry->data["idtransportadora"]."'";
                             }

                             $where .= ") and ";
                    }

                     if ($id_base > 0 && $id_base)
                             $where .= " tbentrega.codbase = '$id_base' and";
                     else{
                             //todas: todas bases que a pessoa tem direito
                             $qry->nrw = 0;
                             $sql = "SELECT * FROM tbdireitoauditoria WHERE tbdireitoauditoria.codbase=".$_SESSION["IDBASE"]." and tbdireitoauditoria.codbasedireito<>".$_SESSION["IDBASE"];
                             $qry->executa($sql);
                             $where .= " (tbentrega.codbase = '".$_SESSION["IDBASE"]."'";

                             for ($j=0;$j<$qry->nrw;$j++){
                                     $qry->navega($j);
                                     $where .= " or tbentrega.codbase = '".$qry->data["codbasedireito"]."'";
                             }

                             $where .= ") and ";
                     }

                     if ($id_cliente > 0 && $id_cliente)
                     $where .= " tbentrega.codcliente = '$id_cliente' and";

                     if ($_SESSION['IDCLIENTE'] && $_SESSION['IDCLIENTE']<>" " || $_SESSION['IDCLIENTE'] <> 0)
                     $where .= " tbentrega.codcliente = '".$_SESSION['IDCLIENTE']."' and";

                     if ($id_produto > 0)
                     $where .= " tbentrega.codigoproduto = '$id_produto' and";

                      if ($setor > 0)
                          $where .= " tbentrega.numlotecliente = '$setor' and";


                  

                     if($data_de && $data_ate){
                             if (conv_data($data_de) <= conv_data($data_ate)){
                                     $campo = explode("/",$data_de);
                                     $mes_inicial = $campo[2]."-".$campo[1]."-".$campo[0];
                                     $campo = explode("/",$data_ate);
                                     $mes_ate = $campo[2]."-".$campo[1]."-".$campo[0];
                                     $where .= "  tbentrega.$tipo_data >= '$mes_inicial' and  tbentrega.$tipo_data <= '$mes_ate' and";
                             }
                             else
                             echo "<tr><th colspan=8 align=center>Datas Inválidas, Favor verificar a</th></tr>";
                     }

                    if ($idtipomovimento > 0)
                       $where .= " tbentrega.idtipomovimento='$idtipomovimento' and";



                    if($audita_todos_movs!="s")
                       $where.=" (tbtipomovimento.audita='t' or tbtipomovimento.audita isnull) and";

                     if ($where){
                             $where = substr($where,0,strlen($where)-4);

                                        $where = " AND ".$where;
                     }




                     $sql = "SELECT tbentrega.idinterno,tbentrega.idexterno,tbentrega.dataoperacao,
                             tbentrega.idtipoentrega,tbentrega.datapromessa,tbentrega.dataentrega,tbentrega.codbase,
                             tbenderecoentrega.cidadeentrega,tbenderecoentrega.cepentrega,
                             tbenderecoentrega.nomeentrega,tbentrega.codcliente,tbentrega.datacoletado,
                             tbentrega.datacoletadobase, tbentrega.numlista
                             FROM tbentrega, tbenderecoentrega, tbtipomovimento
                             WHERE tbentrega.idtipomovimento=tbtipomovimento.idtipomovimento
                             AND tbentrega.idinterno=tbenderecoentrega.idinterno $where
                             
                             group by
                             
                             tbentrega.idinterno,tbentrega.idexterno,tbentrega.dataoperacao,
                             tbentrega.datapromessa,tbentrega.dataentrega,tbentrega.codbase,
                             tbenderecoentrega.cidadeentrega,tbenderecoentrega.cepentrega,
                             tbenderecoentrega.nomeentrega,tbentrega.codcliente,tbentrega.datacoletado,
                             tbentrega.datacoletadobase, tbentrega.numlista,tbentrega.idtipoentrega
                             ORDER BY tbentrega.codcliente,tbentrega.datacoletado ";
                             //echo $sql;
                             
                     $qry->executa($sql);


                     if ($qry->nrw){
                             if($idtipomovimento)
                             $desc_movimento = "Status: <font color='#990000'>".$qry->data["nometipomovimento"]."</font>&nbsp;&nbsp;&nbsp;&nbsp;";
                            
    
                             
                             echo "<tr><td colspan=11><b>$desc_movimento Total: <font color='#990000'>".$qry->nrw."</font></b></td></tr>";
                             echo "<tr bgcolor=#dddddd ><td colspan=11> <font color='#990000'>";
                                 echo "<table>";
                                 echo "<tr>
                                 <th>CÓDIGOO</th>
                                 <th>CÓDIGO</th>
                                 <th>NOME</th>
                                 <th>COLETA</th>
                                 <th>ÚLTIMO</th>
                                 <th>DATA</th>
                                 <th>RECEPÇÃO</th>
                                 <th>CIDADE</th>
                                 <th>RESPOSTA</th>
                                 <th>NOME</th>
                                 
                               </tr>";

                                 echo "<tr>
                                 <th>INTERNO</th>
                                 <th>EXTERNO</th>
                                 <th>DESTINATÁRIO</th>
                                 <th>CLIENTE</th>
                                 <th>MOVIMENTO</th>
                                 <th>PROMESSA</th>
                                 <th>NA BASE</th>
                                 <th>DESTINO</th>
                                 <th>TELEMARKETING</th>
                                 <th>BASE</th>
                               </tr>";
                              echo "</font></td></tr>";
                             For($i=0;$i<$qry->nrw;$i++){
                                     $qry->navega($i);

                                      $tipo='';
                             $tipo = $qry->data["idtipoentrega"];
                             $idinterno = $qry->data["idinterno"];
                             
                             
                             
                             $nome_entrega = '';
                             $nome_entrega = $qry->data["nomeentrega"];
                              if($tipo==9)
                         {
                                 $sql = "SELECT
                                          nome_titular,cod_contrato 
                                          from tb_amil
                                          WHERE
                                          idinterno = '$idinterno' LIMIT 1";
                                          $qry_ponto->executa($sql);
                                          $nome_entrega = $qry_ponto->data["nome_titular"];  
                                         
                                        
                                   }
                                     
                                     
                                     //$sql2 = "SELECT nomeentrega,cidadeentrega FROM tbenderecoentrega WHERE idinterno = '".$qry->data["idinterno"]."'";
                                     //$qry2->executa($sql2);
                                     $array_codcliente[$i] = $qry->data["codcliente"];


                                     if($array_codcliente[$i]!=$array_codcliente[$i-1]){
                                     if($i!=0){
                                       echo "<tr bgcolor=#dddddd ><td colspan=11><b>Total:</b> <font color='#990000'>".$qtd_cliente."</font></td></tr>";
                                       $qtd_cliente=0;
                                       }
                                      $qry2->data["nomecliente"] = "";
                                      $sql2 = "SELECT nomecliente FROM tbcliente WHERE codcliente= '".$qry->data["codcliente"]."'";
                                      $qry2->executa($sql2);
                                      echo "<tr bgcolor=#dddddd ><td colspan=11><b>Cliente:</b> <font color='#990000'>".$qry2->data["nomecliente"]."</font></td></tr>";
                                      $j=0;
                                      }

                                   if(trim($qry->data["codbase"])<>''){
                                   $sql2 = "SELECT nomebase FROM tbbase 
                                                WHERE codbase= '".$qry->data["codbase"]."'";
                                   $qry_base->executa($sql2);
                                   }  

                                 $qtd_cliente++;
                                 echo "<tr ".(($j%2)?"bgcolor=#eeeeee":"").">
                                 <td><a href='$PHP_SELF?opt=S&data_de=$data_de&data_ate=$data_ate&id_transportadora=$id_transportadora&id_base=$id_base&id_cliente=$id_cliente&id_produto=$id_produto&idinterno=".$qry->data["idinterno"]."&lote_de=$lote_de&lote_ate=$lote_ate&tipo_lote=$tipo_lote&idexter=$idexterno&tipo_data=$tipo_data&so_barrabairro=$so_barrabairro&audita_todos_movs=$audita_todos_movs'>".$qry->data["idinterno"]."</a></td>
                                 <td>".$qry->data["idexterno"]."</td>
                                
                                 <td>".strtoupper(substr($nome_entrega,0,12))."</td>
                                 <td>".mostra_data ($qry->data["datacoletado"])."</td>
                                 <td>".mostra_data($qry->data["dataoperacao"])."</td>
                                 <td>".mostra_data($qry->data["datapromessa"])."</td>
                                 <td>".mostra_data ($qry->data["datacoletadobase"])."</td>
                                 <td>".strtoupper($qry->data["cidadeentrega"]) ."</td>
                                 <td>".strtoupper($qry_base->data["nomebase"]) ."</td>
                                 ";
                                 

                                 //Encontrando a última resposta do telemarketing para esta encomenda
                                 $qrytele = new consulta($con);
                                 $qrytele->executa("Select resposta from tbrespostatele where idinterno = ".$qry->data["idinterno"]." order by dataentrada DESC LIMIT 1");
                                 echo "<td>".(($qrytele->nrw > 0)?strtoupper($qrytele->data["resposta"]):"")."</td>
                                 


                                 </tr>";
                                 
                                     $j++;

                                     if($i==($qry->nrw-1)){
                                             echo "<tr bgcolor=#dddddd ><td colspan=11><b>Total:</b> <font color='#990000'>".$qtd_cliente."</font></td></tr>";
                                         //   $qtd_cliente=0;
                                     }

                             }
                     }
                     else
                     echo   "<tr>
                                <td colspan=2 align=center><font color=#ff0000>Nenhum item a ser auditado com esse filtro</font></td>
                            </tr>";  
                     break;
             }
			 
			 
           ?>

       
		
                                <?php
								
                                
								                 
								 //botao voltar
                                

                 
                $link_imprimir = "print_ar_edn_pdf.php?idexterno=".$qry->data["idexterno"]."&idexterno=".$idexterno;

                ?>
				
             
</table>

</form>
</div>

<?php
// pega o Footer
require_once("inc/footer.inc");  