<?
//montagem da AR para impressão
//inclui biblioteca de controles
include("classes/diversos.inc.php");
include ('classes/ezpdf/class.ezpdf.php');

//testa sessão
if (VerSessao()==false){
        header ("location: aviso.php?ider=2");//apresenta aviso de bloqueio
}
//definição de objetos
 
$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);



$sql="";
$sql.="select ";
//tbEntrega - Campos
$sql.=" 	tbentrega.*, ";

$sql.="tbenderecoentrega.*, ";
                        


$sql.=" tbcliente.* ";
                      


$sql.=" From tbEntrega,tbEnderecoEntrega,tbCliente ";

//joins
$sql.=" where tbentrega.idinterno = tbenderecoentrega.idinterno and 
      tbentrega.codcliente = tbcliente.codcliente ";  
        
        

$sql.=" and tbentrega.idinterno > 0 ";

if($ar > 0)
$sql.=" AND tbentrega.idinterno = $ar";

//WHERE de entrega por lotes
if($lote > 0)
$sql.=" AND tbentrega.numloteinterno = '$lote'";

if($idtransportadora > 0)
$sql.=" AND tbentrega.idtransportadora = $idtransportadora";

if($codbase > 0)
$sql.=" AND tbentrega.codbase = $codbase";

if($codcliente > 0)
$sql.=" AND tbentrega.codcliente = $codcliente";

if($codigoproduto > 0)
$sql.=" AND tbentrega.codigoproduto = $codigoproduto";

if($numlista > 0)
$sql.=" AND tbentrega.numlista = '$numlista'";

//$sql.=" AND tbEnderecoEntrega.cepentrega >= '26022350' and tbEnderecoEntrega.cepentrega <='26545560'";

$sql.=" ORDER BY tbentrega.numloteinterno,tbentrega.sequencialoteinterno,tbEnderecoEntrega.cepentrega";

//echo "$sql<br>".$qry->nrw;
//exit;

//Executa QUERY
$qry->executa($sql);


//incio do arquivo em pdf
$pdf =& new Cezpdf();
$pdf->selectFont('classes/ezpdf/fonts/Helvetica.afm');

if(!$qry->nrw)
$pdf->ezText("Nenhum registro encontrado");

$contador=0;
//inicio do for de registros
for($i=0;$i<$qry->nrw;$i++){
 
        $qry->navega($i);

        $contador++;

        if($contador==1)
        $conta_y = 0;
        elseif($contador==2)
        $conta_y = 265;
        elseif($contador==3){
                $conta_y = 530;
                $contador = 0;
        }

        /*
        $dest = substr(mostra_nome($qry->data["nomeentrega"]),0,204);
        $tam_dest = strlen($dest);
        //ifs para medir a quantidade de caracteres e pular a qtd certa de linhas (arrumar uma maneira melhor de fazer isso...)
        if($tam_dest <= 204){
                $pulalinhadest = "";
                if($tam_dest <= 170){
                        $pulalinhadest = "\n";
                        if($tam_dest <= 136){
                                $pulalinhadest = "\n\n";
                                if($tam_dest <= 102){
                                        $pulalinhadest = "\n\n\n";
                                        if($tam_dest <= 68){
                                                $pulalinhadest = "\n\n\n\n";
                                                if($tam_dest <= 34)
                                                $pulalinhadest = "\n\n\n\n\n";
                                        }
                                }
                        }
                }
        }

        $dest = $dest.$pulalinhadest;



        $end = substr(mostra_nome($qry->data["enderecoentrega"])." - ".mostra_nome($qry->data["bairroentrega"])." ".mostra_nome($qry->data["complementoenderecoentrega"])." Cep:".mostra_cep($qry->data["cepentrega"])." ".mostra_nome($qry->data["cidadeentrega"]),0,374);
        $tam_end = strlen($end);
        //ifs para medir a quantidade de caracteres e pular a qtd certa de linhas
        if($tam_end <= 374){
                $pulalinhaend = "\n";
                if($tam_end <= 340){
                        $pulalinhaend = "\n\n";
                        if($tam_end <= 306){
                                $pulalinhaend = "\n\n\n";
                                if($tam_end <= 272){
                                        $pulalinhaend = "\n\n\n\n";
                                        if($tam_end <= 238){
                                                $pulalinhaend = "\n\n\n\n\n";
                                                if($tam_end <= 204){
                                                        $pulalinhaend = "\n\n\n\n\n\n";
                                                        if($tam_end <= 170){
                                                                $pulalinhaend = "\n\n\n\n\n\n\n";
                                                                if($tam_end <= 136){
                                                                        $pulalinhaend = "\n\n\n\n\n\n\n\n";
                                                                        if($tam_end <= 102){
                                                                                $pulalinhaend = "\n\n\n\n\n\n\n\n\n";
                                                                                if($tam_end <= 68){
                                                                                        $pulalinhaend = "\n\n\n\n\n\n\n\n\n\n";
                                                                                        if($tam_end <= 34)
                                                                                        $pulalinhaend = "\n\n\n\n\n\n\n\n\n\n\n";
                                                                                }
                                                                        }
                                                                }
                                                        }
                                                }
                                        }
                                }
                        }
                }
        }
        $end = $end.$pulalinhaend;
        */

        $dest = substr(mostra_nome($qry->data["nomeentrega"]),0,204);
        $tam_dest = strlen($dest);
        //ifs para medir a quantidade de caracteres e pular a qtd certa de linhas (arrumar uma maneira melhor de fazer isso...)
        if($tam_dest <= 204){
                $pulalinhadest = "";
                if($tam_dest <= 170){
                        $pulalinhadest = "\n";
                        if($tam_dest <= 136){
                                $pulalinhadest = "\n\n";
                                if($tam_dest <= 102){
                                        $pulalinhadest = "\n\n\n";
                                        if($tam_dest <= 68){
                                                $pulalinhadest = "\n\n\n\n";
                                                if($tam_dest <= 34)
                                                $pulalinhadest = "\n\n\n\n\n";
                                        }
                                }
                        }
                }
        }

        $dest = $dest.$pulalinhadest;

    


        //$end = substr(mostra_nome($qry->data["enderecoentrega"])." - ".mostra_nome($qry->data["bairroentrega"])." ".
		//mostra_nome($qry->data["complementoenderecoentrega"])." Cep:".mostra_cep($qry->data["cepentrega"])." ".mostra_nome($qry->data["cidadeentrega"]),0,374);
        $tam_end = strlen($end);
        //ifs para medir a quantidade de caracteres e pular a qtd certa de linhas
               
        $end = $end.$pulalinhaend;


        $enc = substr(strtoupper($qry->data["idexterno"]),0,102);
        $tam_enc = strlen($enc);
        //ifs para medir a quantidade de caracteres e pular a qtd certa de linhas (arrumar uma maneira melhor de fazer isso...)

        if($tam_enc <= 102){
                $pulalinhaenc = "";
                if($tam_enc <= 68){
                        $pulalinhaenc = "\n";
                        if($tam_enc <= 34)
                        $pulalinhaenc = "\n\n";
                }
        }

        $enc = $enc.$pulalinhaenc;

        $pdf->ezSetY((811.2 - $conta_y));
        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";

        $valor_linha_col_0="";
        $valor_linha_col_1="";
        $valor_linha_col_2="";
        $valor_linha_col_3="";
        $valor_linha_col_0["coluna01"] = "<b>DATA CERTA</b>\n\nSAO PAULO - SP\nhttp://www.datacerta.com\ncontato@datacerta.com\n";
        $valor_linha_col_1["coluna01"] = "DESTINATÁRIO\n<b>".$dest."</b>";//cabe ao todo 204 caracteres em 6 linhas. 34 por linha
        $valor_linha_col_2["coluna01"] = "ENDEREÇO\n<b>".$end."</b>";//\n\n\n\n\n\n\n\n\n\n";//cabe ao todo 374 caracteres em 11 linhas. 34 por linha
        $valor_linha_col_3["coluna01"] = "ENCOMENDA\n<b>".$enc."</b>";//\n\n";//cabe ao todo 102 caracteres em 3 linhas. 34 por linha
        

        /*
        $valor_linha_col_0="";
        $valor_linha_col_1="";
        $valor_linha_col_2="";
        $valor_linha_col_3="";
        $valor_linha_col_0["coluna01"] = "<b>FAST COURIER</b>\n\nRIO DE JANEIRO - RJ\nhttp://www.fastcourier.com.br\nfastcourier@nfastcourier.com.br\n";
        $valor_linha_col_1["coluna01"] = "DESTINATÁRIO\n";
        $valor_linha_col_2["coluna01"] = "ENDERECO\n\n\n\n";
        $valor_linha_col_3["coluna01"] = "ENCOMENDA\n";
        $valor_linha_col_4["coluna01"] = "\n\n\n\n\n\n\n\n\n\n\n\n\n";

        */
        $data[0] = $valor_linha_col_0;
        $data[1] = $valor_linha_col_1;
        $data[2] = $valor_linha_col_2;
        $data[3] = $valor_linha_col_3;
        //$data[4] = $valor_linha_col_4;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>190,'shaded'=> 0,'showLines'=>2,'showHeadings'=>0,'fontSize' => 7,'cols'=>array(
        'coluna01'=>array('justification'=>'left','width'=>160)
        )));


        $pdf->ezSetY((811.2 - $conta_y));

        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna02"] = "Titulo da coluna01 - nao vai aparecer";

        $valor_linha_col_0="";
        $valor_linha_col_1="";
        //$valor_linha_col_0["coluna01"] = "REMETENTE\n<b>".substr(mostra_nome($qry->data["nomecliente"]),0,52)."</b>";
        $valor_linha_col_0["coluna01"] = "REMETENTE\n<b>".$qry->data["codcliente"]." - ".substr(mostra_nome($qry->data["nomecliente"]),0,45)."</b>";
        $valor_linha_col_1["coluna01"] = "DESTINATÁRIO\n<b>".substr(mostra_nome($qry->data["nomeentrega"]),0,52)."</b>";
        $valor_linha_col_0["coluna02"] = "<b>COMPROVANTE DE RECEBIMENTO - CR</b>";
        $valor_linha_col_1["coluna02"] = "RESPONSÁVEL\n<b>".substr(mostra_nome($qry->data["responsavelentrega"]),0,30)."</b>";

        $data="";
        $data[0] = $valor_linha_col_0;
        $data[1] = $valor_linha_col_1;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>580,'shaded'=> 0,'showLines'=>2,'showHeadings'=>0,'fontSize' => 7,'cols'=>array(
        'coluna01'=>array('justification'=>'left','width'=>242),
        'coluna02'=>array('justification'=>'left','width'=>148)
        )));

        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";

        $valor_linha_col_0="";
        $valor_linha_col_0["coluna01"] = "ENDEREÇO\n<b>".substr(mostra_nome($qry->data["enderecoentrega"]),0,90)."</b>";

        $data="";
        $data[0] = $valor_linha_col_0;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>580,'shaded'=> 0,'showLines'=>2,'showHeadings'=>0,'fontSize' => 7,'cols'=>array(
        'coluna01'=>array('justification'=>'left','width'=>390)
        )));

        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna02"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna03"] = "Titulo da coluna01 - nao vai aparecer";

        $valor_linha_col_0="";
        $valor_linha_col_0["coluna01"] = "BAIRRO\n<b>".substr(mostra_nome($qry->data["bairroentrega"]),0,50)."</b>";
        $valor_linha_col_0["coluna02"] = "CIDADE\n<b>".substr(mostra_nome($qry->data["cidadeentrega"]),0,29)."</b>";
        $valor_linha_col_0["coluna03"] = "UF\n<b>".strtoupper($qry->data["estadoentrega"])."</b>";

        $data="";
        $data[0] = $valor_linha_col_0;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>580,'shaded'=> 0,'showLines'=>2,'showHeadings'=>0,'fontSize' => 7,'cols'=>array(
        'coluna01'=>array('justification'=>'left','width'=>233),
        'coluna02'=>array('justification'=>'left','width'=>134),
        'coluna03'=>array('justification'=>'left','width'=>23)
        )));

        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna02"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna03"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna04"] = "Titulo da coluna01 - nao vai aparecer";


        $datapromessa = explode("-", $qry->data["datapromessa"]);
        $valor_linha_col_0="";
        $valor_linha_col_0["coluna01"] = "CEP\n<b>".mostra_cep($qry->data["cepentrega"])."</b>";
        $valor_linha_col_0["coluna02"] = "VALOR DECLARADO\n<b>R$".substr(number_format($qry->data["valorentrega"] ,2,",","."),0,25)."</b>";//number_format($qry->data["valorentrega"],2,",",".");
        $valor_linha_col_0["coluna03"] = "Nº ENVELOPE\n ".(($qry->data["primeiroenvelope"])?$qry->data["primeiroenvelope"]." - ".$qry->data["ultimoenvelope"]:"");
        $valor_linha_col_0["coluna04"] = "DATA DE ENTREGA\n<b>".$datapromessa[2]."/".$datapromessa[1]."/".$datapromessa[0]."</b>";
  

        $data="";
        $data[0] = $valor_linha_col_0;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>580,'shaded'=> 0,'showLines'=>2,'showHeadings'=>0,'fontSize' => 7,'cols'=>array(
        'coluna01'=>array('justification'=>'left','width'=>50),
        'coluna02'=>array('justification'=>'left','width'=>130),
        'coluna03'=>array('justification'=>'left','width'=>130),
        'coluna04'=>array('justification'=>'left','width'=>80)
        )));



        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna02"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna03"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna04"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna05"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna06"] = "Titulo da coluna01 - nao vai aparecer";



        $qry2->nrw = 0;
        $peso = 0;
        $sql2 = "SELECT pesoentrega as peso FROM tbentrega WHERE idtransportadora='".$qry->data["idtransportadora"]."' AND codigoproduto='".$qry->data["codigoproduto"]."' AND codcliente='".$qry->data["codcliente"]."'";
        $qry2->executa($sql2);
        $peso = $qry2->data["peso"];
        if(!$peso)
        $peso = 0;

        $dataemissao =  explode("-", $qry->data["dataemissao"]);
        $valor_linha_col_0="";
        //$valor_linha_col_0["coluna01"] = "TARIFA\n";
        $valor_linha_col_0["coluna01"] = "EMISSÃO\n<b>".$dataemissao[2]."/".$dataemissao[1]."/".$dataemissao[0]."</b>";
        $valor_linha_col_0["coluna02"] = "VOLUMES\n<b>".substr($qry->data["quantidadevolumes"],0,7)."</b>";
        $valor_linha_col_0["coluna03"] = "PARCELAS\n<b>1</b>";
        $valor_linha_col_0["coluna04"] = "VALOR PARCELA\n<b>R$".substr(number_format($qry->data["valorentrega"] ,2,",","."),0,20)."</b>";
        $valor_linha_col_0["coluna05"] = "VALOR TOTAL\n<b>R$".substr(number_format($qry->data["valorentrega"] ,2,",","."),0,20)."</b>";
        $valor_linha_col_0["coluna06"] = "PESO\n<b>".substr(number_format($peso,2,",",""),0,5)."Kg</b>";

        $data="";
        $data[0] = $valor_linha_col_0;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>580,'shaded'=> 0,'showLines'=>2,'showHeadings'=>0,'fontSize' => 7,'cols'=>array(
        'coluna01'=>array('justification'=>'left','width'=>50),
        'coluna02'=>array('justification'=>'left','width'=>45),
        'coluna03'=>array('justification'=>'left','width'=>48),
        'coluna04'=>array('justification'=>'left','width'=>99),
        'coluna05'=>array('justification'=>'left','width'=>99),
        'coluna06'=>array('justification'=>'left','width'=>49)
        )));



        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna02"] = "Titulo da coluna01 - nao vai aparecer";

        $tam_obs = 0;
        $qry2->nrw = 0;
        /*$sql2 = "SELECT iddetalhe FROM tbdetalhe WHERE idexterno='".$qry->data["idexterno"]."' ORDER BY iddetalhe";
        $qry2->executa($sql2);
        if($qry2->nrw){
                $detalhes = "";
                for($j=0;$j<$qry2->nrw;$j++){
                        $qry2->navega($j);

                        $detalhes.= " ".$qry2->data["iddetalhe"];

                        if($j!=$qry2->nrw-1)
                        $detalhes.=",";
                }

                $obs = substr($detalhes,0,246);

        }
        */
        $sql2 = "SELECT obsentrega FROM tbentrega WHERE idexterno='".$qry->data["idexterno"]."' ";
        $qry2->executa($sql2);
        if($qry2->nrw){
                $detalhes = "";
                for($j=0;$j<$qry2->nrw;$j++){
                        $qry2->navega($j);

                        $detalhes.= " ".$qry2->data["obsentrega"];

                        if($j!=$qry2->nrw-1)
                        $detalhes.=",";
                }

                $obs = substr($detalhes,0,246);

        }


        $tam_obs = strlen($obs);
        //ifs para medir a quantidade de caracteres e pular a qtd certa de linhas
        /*if($tam_obs <= 246){
                $pulalinhaobs = "";
                if($tam_obs <= 205){
                        $pulalinhaobs = "\n";
                        if($tam_obs <= 164){
                                $pulalinhaobs = "\n\n";
                                if($tam_obs <= 123){
                                        $pulalinhaobs = "\n\n\n";
                                        if($tam_obs <= 82){
                                                $pulalinhaobs = "\n\n\n\n";
                                                if($tam_obs <= 41)
                                                $pulalinhaobs = "\n\n\n\n\n";
                                        }

                                }
                        }
                }
        }
        */

                if($tam_obs <= 205){
                        $pulalinhaobs = "";
                        if($tam_obs <= 164){
                                $pulalinhaobs = "\n";
                                if($tam_obs <= 123){
                                        $pulalinhaobs = "\n\n";
                                        if($tam_obs <= 82){
                                                $pulalinhaobs = "\n\n\n";
                                                if($tam_obs <= 41)
                                                $pulalinhaobs = "\n\n\n\n";
                                        }

                                }
                        }
                }

        $obs = $obs.$pulalinhaobs;

        $valor_linha_col_0="";
        //total de 246 caracteres ocupando as 6 linhas. 41 caracteres por linha
        $valor_linha_col_0["coluna01"] = "OBSERVAÇÕES\n<b>".$obs."</b>";//total de 205 caracteres ocupando as 5 linhas. 41 caracteres por linha
        $valor_linha_col_0["coluna02"] = "";//espaço do codigo de barras

        $data="";
        $data[0] = $valor_linha_col_0;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>580,'shaded'=> 0,'showLines'=>2,'showHeadings'=>0,'fontSize' => 7,'cols'=>array(
        'coluna01'=>array('justification'=>'left','width'=>195),
        'coluna02'=>array('justification'=>'center','width'=>195)
        )));


        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna02"] = "Titulo da coluna01 - nao vai aparecer";

        $valor_linha_col_0="";
        $valor_linha_col_0["coluna01"] = "<b>MOTIVO DEVOLUÇÃO</b>";
        $valor_linha_col_0["coluna02"] = "<b>GRAU AFINIDADE</b>";
        $valor_linha_col_1["coluna01"] = "(  ) Mudou-se";
        $valor_linha_col_1["coluna02"] = "(  ) Próprio";
        $valor_linha_col_2["coluna01"] = "(  ) Desconhecido";
        $valor_linha_col_2["coluna02"] = "(  ) Irmão/Irmã";
        $valor_linha_col_3["coluna01"] = "(  ) Endereço Insuficiente";
        $valor_linha_col_3["coluna02"] = "(  ) Conjuge";
        $valor_linha_col_4["coluna01"] = "(  ) Recusado";
        $valor_linha_col_4["coluna02"] = "(  ) Portaria";
        $valor_linha_col_5["coluna01"] = "(  ) Outros______________";
        $valor_linha_col_5["coluna02"] = "(  ) Outros______________";


        $data="";
        $data[0] = $valor_linha_col_0;
        $data[1] = $valor_linha_col_1;
        $data[2] = $valor_linha_col_2;
        $data[3] = $valor_linha_col_3;
        $data[4] = $valor_linha_col_4;
        $data[5] = $valor_linha_col_5;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>385,'shaded'=> 0,'showLines'=>1,'showHeadings'=>0,'fontSize' => 7,'cols'=>array(
        'coluna01'=>array('justification'=>'left','width'=>97.5),
        'coluna02'=>array('justification'=>'left','width'=>97.5)
        )));

        $pdf->ezSetY((637.5 - $conta_y));

        /*
        $pdf->ezSetY((640.5 - $conta_y));

        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";

        $valor_linha_col_0="";
        $valor_linha_col_0["coluna01"] = "RECEBIMENTO";

        $data="";
        $data[0] = $valor_linha_col_0;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>580,'shaded'=> 0,'showLines'=>1,'showHeadings'=>0,'fontSize' => 7,'cols'=>array(
        'coluna01'=>array('justification'=>'center','width'=>195)
        )));
        */
        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";

        $valor_linha_col_0="";
        $valor_linha_col_0["coluna01"] = "NOME LEGÍVEL/CARIMBO\n";

        $data="";
        $data[0] = $valor_linha_col_0;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>580,'shaded'=> 0,'showLines'=>2,'showHeadings'=>0,'fontSize' => 6,'cols'=>array(
        'coluna01'=>array('justification'=>'left','width'=>195)
        )));

        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";

        $valor_linha_col_0="";
        $valor_linha_col_0["coluna01"] = "ASSINATURA DESTINATÁRIO\n";

        $data="";
        $data[0] = $valor_linha_col_0;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>580,'shaded'=> 0,'showLines'=>2,'showHeadings'=>0,'fontSize' => 6,'cols'=>array(
        'coluna01'=>array('justification'=>'left','width'=>195)
        )));

        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna02"] = "Titulo da coluna01 - nao vai aparecer";

        $valor_linha_col_0="";
        $valor_linha_col_0["coluna01"] = "DATA E HORA RECEBIMENTO\n";
        $valor_linha_col_0["coluna02"] = "RG / MATRÍCULA\n";

        $data="";
        $data[0] = $valor_linha_col_0;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>580,'shaded'=> 0,'showLines'=>2,'showHeadings'=>0,'fontSize' => 6,'cols'=>array(
        'coluna01'=>array('justification'=>'left','width'=>97.5),
        'coluna02'=>array('justification'=>'left','width'=>97.5)
        )));

        $cols="";
        $cols["coluna01"] = "Titulo da coluna01 - nao vai aparecer";
        $cols["coluna02"] = "Titulo da coluna01 - nao vai aparecer";

        $valor_linha_col_0="";
        $valor_linha_col_0["coluna01"] = "NOME DO COURIER\n";
        $valor_linha_col_0["coluna02"] = "ASSINATURA DO COURIER\n";

        $data="";
        $data[0] = $valor_linha_col_0;

        $pdf->ezTable($data,$cols,'',array('xOrientation'=>'left','xPos'=>580,'shaded'=> 0,'showLines'=>2,'showHeadings'=>0,'fontSize' => 6,'cols'=>array(
        'coluna01'=>array('justification'=>'left','width'=>97.5),
        'coluna02'=>array('justification'=>'left','width'=>97.5)
        )));


        //codigo de barras - //decima primeira linha da tabela em pdf - corresponde a um retangulo
        codigodebarras($qry->data["idexterno"],$qry->data["idexterno"],"idexterno","jpeg","C39","200","50","1","1");
        $pdf->addJpegFromFile("barcode/idexterno/".$qry->data["idexterno"].".jpg",381,(640-$conta_y),190);
        $pdf->addJpegFromFile("inc/img/logo-datacerta.jpg",120,(780 - $conta_y),50);

        if(($i+1)%3==0 and $i!=0 and $i!=($qry->nrw-1)){
                $pdf->ezSetMargins(30,30,30,30);
                $pdf->ezNewPage();
        }

}//fim do for de registros

//gera o pdf
$pdf->ezStream();

?>