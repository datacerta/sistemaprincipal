<?php 
require_once("inc/config.inc");
$qry  = new consulta($con);
$qry2  = new consulta($con);

$numlista = $_POST['lista'];
if(empty($numlista)){
    require_once("inc/header.inc");
?>
    <form action=<?=$PHP_SELF;?> method=POST>
        <table class="tabela" style="width:800px; margin:0 auto">
            <tr bgcolor="#eeeeee">
              <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Routeasy</b></font></font><font size="4"><b> ::..</b></font></td>
            </tr>
        </table>
        <br><br>    
        <table class="tabela" style="width:800px; margin:0 auto">
            <tr bgcolor="#FFFFFF">
              <td >Lista:</td>
              <td ><input type=text name="lista"></td>
              <td ><input type=submit name="enviar" value="Gerar"></td>
            </tr>
        </table>
    </form>
<?php
}else{

    include  'PHPExcel.php';
    $objPHPExcel = new PHPExcel();

    /*********************
    CABEÇALHO
    *********************/

    $header = 'a1:q1';
    $objPHPExcel->getActiveSheet()->setTitle('deliveries');
    $objPHPExcel->getActiveSheet()->getStyle($header)->getFont()->setBold(true);
    //$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00ffff00');
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Código do Cliente' )
        ->setCellValue('B1', "Nome do Cliente" )
        ->setCellValue("C1", "CEP" )
        ->setCellValue("D1", "Rua" )
        ->setCellValue("E1", "Número" )
        ->setCellValue("F1", "Complemento" )
        ->setCellValue("G1", "Município" )
        ->setCellValue("H1", "Estado" )
        ->setCellValue("I1", "País" )
        ->setCellValue("J1", "Peso (kg)" )
        ->setCellValue("K1", "Volume (m³)" )
        ->setCellValue("L1", "Tempo de atendimento no cliente (min.)" )
        ->setCellValue("M1", "Início do intervalo permitido" )
        ->setCellValue("N1", "Fim do intervalo permitido" )
        ->setCellValue("O1", "Latitude" )
        ->setCellValue("P1", "Longitude" )
        ->setCellValue("Q1", "Observações" );

    /*********************
    CONTEUDO
    *********************/

    $qry->executa(
        "SELECT e.idinterno, e.numnotafiscal, e.idexterno, ee.nomeentrega, ee.cepentrega, ee.enderecoentrega, ee.complementoenderecoentrega, ee.cidadeentrega, ee.estadoentrega, e.pesoentrega, dr.latitude, dr.longitude,ee.bairroentrega, dr.id_revend
        FROM tbentrega e
        LEFT JOIN tbenderecoentrega ee ON e.idinterno = ee.idinterno
        LEFT JOIN tb_demillus_revend dr ON CAST(e.numconta as integer) = dr.id_revend
        WHERE numlista=$numlista");

    for($i=0;$i<$qry->nrw;$i++){
        $qry->navega($i);
        $data_virg = explode(',',$qry->data['enderecoentrega'],2);
        $numero_esp = explode(' ', $data_virg[1],2);
        $endereco = $data_virg[0];
        $numero = $numero_esp[0];

        if(empty($numero)){
            $data_esp = explode(' ',$qry->data['enderecoentrega']);

            foreach($data_esp as $value){
                if(is_numeric(trim($value))){
                    $numero = trim($value);
                    break 1;
                }
            }
        }

    $qry2->executa(
        "SELECT COUNT(idinterno) as totalcaixas
        FROM tb_demillus_volumes dv
        WHERE idinterno=".$qry->data['idinterno'].
        " GROUP BY idinterno");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A".($i+2), $qry->data['numnotafiscal'] )
            ->setCellValue("B".($i+2), $qry->data['nomeentrega'] )
            ->setCellValue("C".($i+2), $qry->data['cepentrega'] )
            ->setCellValue("D".($i+2), $endereco )
            ->setCellValue("E".($i+2), $numero )
            ->setCellValue("F".($i+2), "" )
            ->setCellValue("G".($i+2), $qry->data['cidadeentrega'] )
            ->setCellValue("H".($i+2), $qry->data['estadoentrega'] )
            ->setCellValue("I".($i+2), "Brasil" )
            ->setCellValue("J".($i+2), $qry->data['pesoentrega'] )
            ->setCellValue("K".($i+2), 0 )
            ->setCellValue("L".($i+2), "" )
            ->setCellValue("M".($i+2), "" )
            ->setCellValue("N".($i+2), "" )
            ->setCellValue("O".($i+2), $qry->data['latitude'] )
            ->setCellValue("P".($i+2), $qry->data['longitude'] )
            ->setCellValue("Q".($i+2), $qry->data['id_revend'] );
    }

    /*****************
    FORÇA DOWNLOAD
    *****************/

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="arquivo_de_exemplo01.xls"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); 
    exit;

}