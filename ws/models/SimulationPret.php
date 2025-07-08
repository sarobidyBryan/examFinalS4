<?php
require_once __DIR__ . '/../db.php';

class SimulationPret {
    public static function simulerPret($montant, $taux, $duree, $delai, $assurance, $date_pret) {
        $taux_mensuel = $taux / 100 / 12;
        $assurance_total = $montant * $assurance / 100;
        $assurance_mensuelle = $assurance_total / $duree;

        // Calcul de la mensualité constante (hors assurance)
        $mensualite = ($montant * $taux_mensuel) / (1 - pow(1 + $taux_mensuel, -($duree )));

        $reste = $montant;
        $paiements = [];

        $date = new DateTime($date_pret);
        $total_interet = 0;
        $total_amortissement = 0;
        $total_mensualite = 0;

        for ($mois = 1; $mois <= $duree+$delai; $mois++) {
            $interet = 0;
            $amortissement = 0;
            $mens = 0;
            $total_a_payer = 0;

            if ($mois <= $delai) {
                $mens = 0;
                $assurance_mensuelle = 0;
            } else {
                $assurance_mensuelle = $assurance_total / $duree;
                $interet = $reste * $taux_mensuel;
                $amortissement = $mensualite - $interet;
                $reste -= $amortissement;
                $mens = $mensualite;
                $total_a_payer = $mensualite + $assurance_mensuelle;
                $total_interet += $interet;
                $total_amortissement += $amortissement;
                $total_mensualite += $mensualite;
            }

            $paiements[] = [
                'mois' => $mois,
                'date' => $date->format('Y-m-d'),
                'mensualite' => round($mens, 2),
                'assurance' => round($assurance_mensuelle, 2),
                'amortissement' => round($amortissement, 2),
                'interet' => round($interet, 2),
                'capital_restant' => round($reste, 2),
                'total_a_payer' => round($total_a_payer, 2)
            ];

            $date->modify('+1 month');
        }

        return [
            'paiements' => $paiements,
            'totaux' => [
                'assurance' => round($assurance_total, 2),
                'interet' => round($total_interet, 2),
                'capital' => round($total_amortissement, 2),
                'total' => round($total_mensualite + $assurance_total, 2)
            ]
        ];
    }
}
