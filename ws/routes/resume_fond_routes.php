<?php
require_once __DIR__ . '/../models/Fond.php';
require_once __DIR__ . '/../models/RemboursementPret.php';

// Route: POST /resume-fond
Flight::route('POST /resume-fond', function() {
    $data = Flight::request()->data;
    $start = $data['start_date'] ?? null;
    $end = $data['end_date'] ?? null;
    if (!$start || !$end) {
        Flight::json([]);
        return;
    }
    $db = getDB();
    // Get all months between start and end
    $startDate = new DateTime($start);
    $endDate = new DateTime($end);
    $endDate->modify('first day of next month');
    $months = [];
    for ($dt = clone $startDate; $dt < $endDate; $dt->modify('+1 month')) {
        $months[] = [
            'mois' => $dt->format('m'),
            'annee' => $dt->format('Y'),
            'firstDay' => $dt->format('Y-m-01'),
            'lastDay' => $dt->format('Y-m-t')
        ];
    }
    // Get all remboursements in period
    $remboursements = RemboursementPret::find_montant_interet_between($db, $start, $end);
    // Aggregate remboursements by month and year
    $rembByMonth = [];
    foreach ($remboursements as $r) {
        $dt = new DateTime($r['date_remboursement']);
        $mois = $dt->format('m');
        $annee = $dt->format('Y');
        $key = $annee.'-'.$mois;
        if (!isset($rembByMonth[$key])) {
            $rembByMonth[$key] = 0;
        }
        $rembByMonth[$key] += floatval($r['montant_paye_base']) + floatval($r['montant_paye_interet']);
    }
    // For each month, get the base fond at the start of the month and sum
    $result = [];
    foreach ($months as $m) {
        $key = $m['annee'].'-'.$m['mois'];
        $baseFond = Fond::getFondAtDate($m['firstDay']);
        $remb = $rembByMonth[$key] ?? 0;
        $result[] = [
            'mois' => $m['mois'],
            'annee' => $m['annee'],
            'base_fond' => $baseFond,
            'remboursements' => $remb,
            'total' => $baseFond + $remb
        ];
    }
    Flight::json($result);
});
