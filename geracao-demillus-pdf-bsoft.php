<?php
require('classes/fpdf3/fpdf.php');
require_once("inc/config.inc");

$datanota_demillus = $_GET['datanota_demillus'];
$numero_setor = $_GET['numero_setor'];
$datavenc = $_GET['datavenc'];

// inicia a consulta
$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry4 = new consulta($con);
$qry5 = new consulta($con);
$qry6 = new consulta($con);

if($_GET['cliente'] == '6670' OR $_GET['cliente'] == '6671'){
	$cliente = "(codcliente = '6670' OR codcliente = '6671')";
}else{
	$cliente = " codcliente = '".$_GET['cliente']."' ";
}

$qry->executa("SELECT serie_cte,emissao_cte,dataemissao,numlotecliente,tarifa FROM tbentrega WHERE dataemissao = '".$datanota_demillus."' AND primeiroenvelope = '".$numero_setor."' AND $cliente ORDER BY serie_cte LIMIT 1");
$qry3->executa("SELECT serie_cte, codcliente FROM tbentrega WHERE dataemissao = '".$datanota_demillus."' AND primeiroenvelope = '".$numero_setor."' AND $cliente ORDER BY serie_cte  DESC LIMIT 1");
$qry2->executa("SELECT COUNT(tarifa) as total, SUM(valorentrega) as valor, SUM(tarifa) as tarifa,SUM(valor_icms) as icms FROM tbentrega WHERE dataemissao = '".$datanota_demillus."' AND primeiroenvelope = '".$numero_setor."' AND $cliente ");
$qry5->executa("SELECT * FROM tbcliente WHERE codcliente = '".$qry3->data['codcliente']."'");
$qry6->executa(
    "SELECT aliquota_icms
    FROM tb_preco_demillus 
    WHERE setor=".$numero_setor);


$datetime = new DateTime(str_replace('/', '-',$qry->data['emissao_cte']));
$dataemissao_cte = $datetime->format('d/m/Y');
$numero_nota_demillus = $qry->data["serie_cte"];

$datetime = new DateTime(str_replace('/', '-',$qry->data['dataemissao']));
$dataemissao = $datetime->format('d/m/Y');
$razaosocial = $qry5->data['razaosocial'];
$endereco = trim($qry5->data['endereco'])." - ".trim($qry5->data['bairro'])." - CEP: ".trim($qry5->data['cep'])." - ".trim($qry5->data['cidade'])."/".trim($qry5->data['uf']);
$cnpj = $qry5->data['cnpj'];

class PDF extends FPDF
{

	
	// Page header
	function Header()
	{
		global $numero_nota_demillus;
		global $dataemissao_cte;
		global $datanota_demillus;
		global $datavenc;
		global $razaosocial;
		global $cnpj;
		global $endereco;

		$datetime = new DateTime(str_replace('/', '-',$datanota_demillus));
		

	    // Logo
	    $this->Image('http://54.207.92.38/inc/img/logo-datacerta.jpg',10,6,30);
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	    // Move to the right

	    // Title
	    $this->SetTextColor(230,230,230);
	    $this->Cell(0,6,'FATURA',0,0,'R');
	    // Line break
	    $this->Ln(10);
	    $this->SetTextColor(0,0,0);

		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('Nº DA FATURA:'),1,0);
		$this->SetFont('Arial','',8);
		$this->Cell(33,5, utf8_decode($numero_nota_demillus."/".$datetime->format('Y')),1,0);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('DATA DE EMISSÃO:'),1,0);
		$this->SetFont('Arial','',8);
		$this->Cell(33,5, utf8_decode($dataemissao_cte),1,0);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('VENCIMENTO:'),1,0);
		$this->SetFont('Arial','',8);
		$datetime->modify('+10 days');
		$this->Cell(0,5, utf8_decode($datavenc),1,1);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('RAZÃO SOCIAL:'),1,0);
		$this->SetFont('Arial','',8);
		$this->Cell(0,5, utf8_decode('Data Certa Transporte Logística Eireli'),1,1);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('ENDEREÇO:'),1,0);
		$this->SetFont('Arial','',8);
		$this->Cell(0,5, utf8_decode('Rua Nova Hamburgo, 99 - Vila Carioca'),1,1);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('ESTADO:'),1,0);
		$this->SetFont('Arial','',8);
		$this->Cell(65,5, utf8_decode('SP'),1,0);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('INFO. BANCÁRIAS:'),1,0);
		$this->SetFont('Arial','',8);
		$this->Cell(0,5, utf8_decode('Itaú: Ag.: 0194/ C/C : 40822-5'),1,1);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('TELEFONE:'),1,0);
		$this->SetFont('Arial','',8);
		$this->Cell(65,5, utf8_decode('(11)2061-3138'),1,0);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('FAX:'),1,0);
		$this->SetFont('Arial','',8);
		$this->Cell(0,5, utf8_decode('(11)2061-3138'),1,1);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('CEP:'),1,0);
		$this->SetFont('Arial','',8);
		$this->Cell(65,5, utf8_decode('04217-040'),1,0);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('CNPJ:'),1,0);
		$this->SetFont('Arial','',8);
		$this->Cell(0,5, utf8_decode('19.232.334/0001-19'),1,1);
		$this->Ln(2);

		$this->setFillColor(240,240,240);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('NOME:'),1,0,'L',1);
		$this->SetFont('Arial','',8);
		$this->Cell(0,5, utf8_decode($razaosocial),1,1,'L',1);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('ENDEREÇO:'),1,0,'L',1);
		$this->SetFont('Arial','',8);
		$this->Cell(0,5, utf8_decode($endereco),1,1,'L',1);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('CNPJ:'),1,0,'L',1);
		$this->SetFont('Arial','',8);
		$this->Cell(0,5, utf8_decode($cnpj),1,1,'L',1);
		$this->SetFont('Arial','B',8);
		$this->Cell(30,5, utf8_decode('REFERÊNCIA:'),1,0,'L',1);
		$this->SetFont('Arial','',8);
		$this->Cell(0,5, utf8_decode('Prestação de Serviços de Transportes'),1,1,'L',1);
		$this->Ln(2);

		$this->setFillColor(0,0,0);
		$this->SetTextColor(250,250,250);
		$this->SetDrawColor(250,250,250);
		$this->SetFont('Arial','B',8);
		$this->Cell(27,5, utf8_decode('CTE'),1,0,'C',1);
		$this->Cell(27,5, utf8_decode('DATA'),1,0,'C',1);
		$this->Cell(27,5, utf8_decode('SETOR'),1,0,'C',1);
		$this->Cell(27,5, utf8_decode('NOTAS'),1,0,'C',1);
		$this->Cell(27,5, utf8_decode('TARIFA'),1,0,'C',1);
		$this->Cell(27,5, utf8_decode('ICMS'),1,0,'C',1);
		$this->Cell(0,5, utf8_decode('VALOR'),1,1,'C',1);
		$this->setFillColor(250,250,250);
		$this->SetTextColor(0,0,0);
		$this->SetDrawColor(0,0,0);
		$this->Ln(1);

	}

	// Page footer
	function Footer()
	{
	    // Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Page number
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

// Instanciation of inherited class
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);


$qry->navega($i);
$pdf->Cell(27,5, utf8_decode($qry->data["serie_cte"]."/".$qry3->data["serie_cte"]),1,0,'C');
$pdf->Cell(27,5, utf8_decode($dataemissao),1,0,'C');
$pdf->Cell(27,5, utf8_decode($qry->data["numlotecliente"]),1,0,'C');
$pdf->Cell(27,5, utf8_decode($qry2->data["total"]),1,0,'C');
$pdf->Cell(27,5, utf8_decode("R$".number_format($qry->data["tarifa"], 2, ',', '.')),1,0,'C');
$pdf->Cell(27,5, utf8_decode("R$".number_format($qry2->data["icms"], 2, ',', '.')),1,0,'C');
$pdf->Cell(0,5, utf8_decode("R$".number_format($qry2->data["tarifa"]+str_replace(",", ".", $qry2->data["icms"]), 2, ',', '.')),1,1,'C');

$pdf->Output();
?>
