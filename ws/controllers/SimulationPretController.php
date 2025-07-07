<?php
require_once __DIR__ . '/../models/SimulationPret.php';

class SimulationPretController {
    public static function simuler() {
        $data = Flight::request()->data;

        if (
            empty($data['montant']) || empty($data['taux']) ||
            empty($data['duree']) /**|| empty($data['delai'])**/ ||
            empty($data['assurance']) || empty($data['date_pret'])
        ) {
            Flight::json(['error' => 'Tous les champs sont requis.']);
            return;
        }

        $montant = floatval($data['montant']);
        $taux = floatval($data['taux']);
        $duree = intval($data['duree']);
        $delai = intval($data['delai']);
        $assurance = floatval($data['assurance']);
        $date_pret = $data['date_pret'];

        if ($delai >= $duree) {
            Flight::json(['error' => 'Le délai ne peut pas dépasser ou être égal à la durée.']);
            return;
        }

        $resultat = SimulationPret::simulerPret($montant, $taux, $duree, $delai, $assurance, $date_pret);
        Flight::json($resultat);
    }
}
