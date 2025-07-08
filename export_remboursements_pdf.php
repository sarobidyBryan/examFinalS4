<?php
require_once __DIR__ . '/ws/db.php';
require_once __DIR__ . '/ws/models/RemboursementPret.php';
require_once __DIR__ . '/ws/vendor/fpdf/fpdf186/fpdf.php';
function formatAr($montant) {
    return number_format($montant, 0, ',', ' ') . ' Ar';
}
// Get filter dates from GET or POST
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : (isset($_POST['start_date']) ? $_POST['start_date'] : null);
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : (isset($_POST['end_date']) ? $_POST['end_date'] : null);

$db = getDB();
if ($start_date && $end_date) {
    $remboursements = RemboursementPret::find_montant_interet_between($db, $start_date, $end_date);
} else {
    $remboursements = RemboursementPret::getAll($db);
}

$pdf = new FPDF();
$pdf->AddPage('L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Tableau des remboursements de prêt'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(5);

// Table header
$header = ['ID', 'Date de remboursement', 'Montant payé (base)', 'Montant payé (intérêt)', 'Montant restant', 'Assurance', 'ID Prêt'];
$widths = [20, 40, 40, 40, 35, 30, 25];
foreach ($header as $i => $col) {
    $pdf->Cell($widths[$i], 8, utf8_decode($col), 1, 0, 'C');
}
$pdf->Ln();

// Table rows
foreach ($remboursements as $r) {
    $pdf->Cell($widths[0], 8, $r['id_remboursement_pret'], 1);
    $pdf->Cell($widths[1], 8, $r['date_remboursement'], 1);
    $pdf->Cell($widths[2], 8, formatAr($r['montant_paye_base']), 1);
    $pdf->Cell($widths[3], 8, formatAr($r['montant_paye_interet']), 1);
    $pdf->Cell($widths[4], 8, formatAr($r['montant_restant']), 1);
    $pdf->Cell($widths[5], 8, formatAr($r['assurance']), 1);
    $pdf->Cell($widths[6], 8, $r['id_pret'], 1);
    $pdf->Ln();
}

$pdf->Output('D', 'remboursements.pdf');
