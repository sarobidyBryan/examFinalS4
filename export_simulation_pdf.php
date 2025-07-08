<?php
require_once __DIR__ . '/ws/vendor/fpdf/fpdf186/fpdf.php';
function formatAr($montant) {
    return number_format($montant, 0, ',', ' ') . ' Ar';
}

// Recuperer les donnees de simulation depuis POST
$paiements = isset($_POST['paiements']) ? json_decode($_POST['paiements'], true) : [];
$totaux = isset($_POST['totaux']) ? json_decode($_POST['totaux'], true) : [];

$pdf = new FPDF();
$pdf->AddPage('L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Simulation de pret'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(5);

// Tableau des paiements
$header = ['Mois', 'Date', 'Mensualite', 'Assurance', 'Amortissement', 'Interet', 'A payer', 'Capital restant dû'];
$widths = [15, 25, 25, 25, 30, 25, 25, 35];
foreach ($header as $i => $col) {
    $pdf->Cell($widths[$i], 8, utf8_decode($col), 1, 0, 'C');
}
$pdf->Ln();

foreach ($paiements as $p) {
    $pdf->Cell($widths[0], 8, $p['mois'], 1);
    $pdf->Cell($widths[1], 8, $p['date'], 1);
    $pdf->Cell($widths[2], 8, formatAr($p['mensualite']), 1);
    $pdf->Cell($widths[3], 8, formatAr($p['assurance']), 1);
    $pdf->Cell($widths[4], 8, formatAr($p['amortissement']), 1);
    $pdf->Cell($widths[5], 8, formatAr($p['interet']), 1);
    $pdf->Cell($widths[6], 8, formatAr($p['total_a_payer']), 1);
    $pdf->Cell($widths[7], 8, formatAr($p['capital_restant']), 1);
    $pdf->Ln();
}

$pdf->Ln(8);
// Tableau des totaux
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, utf8_decode('Totaux'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(35, 8, 'Total', 1);
$pdf->Cell(35, 8, 'Capital', 1);
$pdf->Cell(35, 8, 'Interets', 1);
$pdf->Cell(35, 8, 'Assurance', 1);
$pdf->Ln();
$pdf->Cell(35, 8, formatAr($totaux['total']), 1);
$pdf->Cell(35, 8, formatAr($totaux['capital']), 1);
$pdf->Cell(35, 8, formatAr($totaux['interet']), 1);
$pdf->Cell(35, 8, formatAr($totaux['assurance']), 1);

$pdf->Output('D', 'simulation_pret.pdf');
