<?php
require('classes/fpdf3/fpdf.php');
require_once("inc/config.inc");

$valor = $_GET['valor'];
$voucher = $_GET['voucher'];

class PDF extends FPDF
{


	// Page header
	function Header()
	{
		global $valor;
		global $voucher;

	    $this->Rect(5,5,200,15);
	    $this->Rect(6,6,198,13);
	    $this->Line(100, 6, 100, 19);
	    $this->Line(145, 6, 145, 19);
	    $this->Line(31, 34, 80, 34);
	    $this->Line(24, 41, 80, 41);
	    $this->Line(22, 48, 80, 48);

	    $this->Rect(6,23,198,60);
	    $this->Rect(5,22,200,62);

	    $this->SetFont('Arial','B', 20);
	    $this->SetTextColor(230,230,230);
	    $this->Cell(96,6,'VALE COMBUSTIVEL',0,0,'L');	    

	    $this->SetTextColor(0,0,0);
	    $this->Cell(50,6, utf8_decode('Nº '.$voucher),0,0);

	    $this->SetFont('Arial','B', 20);
	    $this->SetTextColor(0,0,0);
	    $this->Cell(0,6, utf8_decode('R$ '.$valor),0,0);

	    $this->SetFont('Arial','', 12);
	    $this->Ln(20);
		$this->Cell(0,5, utf8_decode('Motorista:'),0,0);
		$this->Ln(7);
		$this->Cell(0,5, utf8_decode('Placa:'),0,0);
		$this->Ln(7);
		$this->Cell(0,5,'Data: ',0,0);
		$this->Ln(27);
		$this->Cell(100,6,'',0,0,'L');
		$this->Line(120, 70, 190, 70);
		$this->Cell(0,6,'Assinatura',0,0,'C');

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
$pdf->Output();
?>
