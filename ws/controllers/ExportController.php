<?php

require_once __DIR__ . '/../models/ExportModel.php';
require_once __DIR__ . '/../vendor/fpdf/fpdf186/fpdf.php';

class ExportController
{
    public static function exportComparaisonPdf()
    {
        ob_clean(); // Nettoie tout tampon précédent

        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo "Donnees JSON manquantes ou invalides.";
            return;
        }

        $pretA = $data['pretA'] ?? null;
        $pretB = $data['pretB'] ?? null;

        if (!$pretA || !$pretB) {
            http_response_code(400);
            echo "Les donnees des prêts A et B sont requises.";
            return;
        }

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, utf8_decode('Comparaison des Prets'), 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 10);

        // Résumé
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(60, 8, 'Critere', 1, 0, 'C', true);
        $pdf->Cell(90, 8, 'Pret A', 1, 0, 'C', true);
        $pdf->Cell(90, 8, 'Pret B', 1, 1, 'C', true);

        $fields = [
            "Montant" => ["value" => "montant", "unit" => "Ar"],
            "Duree (mois)" => ["value" => "duree"],
            "Delai de grace" => ["value" => "delai"],
            "Taux d'interet" => ["value" => "taux", "unit" => "%"],
            "Assurance" => ["value" => "assurance", "unit" => "%"],
            "Mensualite estimee" => ["value" => "mensualite", "unit" => "Ar"],
            "Total a rembourser" => ["value" => "total", "unit" => "Ar"]
        ];

        foreach ($fields as $label => $info) {
            $unit = $info['unit'] ?? '';
            $pdf->Cell(60, 8, utf8_decode($label), 1);
            $pdf->Cell(90, 8, ExportModel::formatVal($pretA[$info['value']], $unit), 1);
            $pdf->Cell(90, 8, ExportModel::formatVal($pretB[$info['value']], $unit), 1);
            $pdf->Ln();
        }

        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Detail des paiements mensuels'), 0, 1, 'L');
        $pdf->SetFont('Arial', '', 9);

        $header = ['Mois', 'Date', 'Mensualite A / B', 'Amort. A / B', 'Interêts A / B', 'Assurance A / B', 'Total a payer A / B'];
        $widths = [15, 25, 40, 40, 40, 40, 45];

        foreach ($header as $i => $h) {
            $pdf->Cell($widths[$i], 8, utf8_decode($h), 1, 0, 'C', true);
        }
        $pdf->Ln();

        $max = max(count($pretA['paiements']), count($pretB['paiements']));
        for ($i = 0; $i < $max; $i++) {
            $a = $pretA['paiements'][$i] ?? [];
            $b = $pretB['paiements'][$i] ?? [];

            $pdf->Cell($widths[0], 8, $a['mois'] ?? $b['mois'], 1);
            $pdf->Cell($widths[1], 8, $a['date'] ?? $b['date'], 1);
            $pdf->Cell($widths[2], 8, ExportModel::pair($a['mensualite'] ?? 0, $b['mensualite'] ?? 0), 1, 0, 'R');
            $pdf->Cell($widths[3], 8, ExportModel::pair($a['amortissement'] ?? 0, $b['amortissement'] ?? 0), 1, 0, 'R');
            $pdf->Cell($widths[4], 8, ExportModel::pair($a['interet'] ?? 0, $b['interet'] ?? 0), 1, 0, 'R');
            $pdf->Cell($widths[5], 8, ExportModel::pair($a['assurance'] ?? 0, $b['assurance'] ?? 0), 1, 0, 'R');
            $pdf->Cell($widths[6], 8, ExportModel::pair($a['total_a_payer'] ?? 0, $b['total_a_payer'] ?? 0), 1, 1, 'R');
        }

        // Important : headers AVANT Output
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=\"comparaison_prets.pdf\"");

        $pdf->Output('I'); // inline → fonctionne avec fetch Blob
    }
}

