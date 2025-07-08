<?php
require_once __DIR__ . '/../models/RemboursementPret.php';
require_once __DIR__ . '/../models/MouvementBanque.php';
require_once __DIR__ . '/../db.php';

class RemboursementPretController {
    public static function getAll() {
        $db = getDB();
        $data = RemboursementPret::getAll($db);
        Flight::json($data);
    }

public static function create() {
    $db = getDB();

$raw = Flight::request()->getBody();
$data = json_decode($raw);
$idPret = $data->id_pret ?? null;
    if (!$idPret) {
        Flight::json(['error' => 'ID du prêt requis.']);
        return;
    }


    // Récupérer les infos du prêt et du type
    $stmt = $db->prepare("SELECT p.*, t.taux, p.assurance as taux_assurance 
                          FROM ef_pret p 
                          JOIN ef_type_pret t ON p.id_type_pret = t.id_type_pret 
                          WHERE p.id_pret = ?");
    $stmt->execute([$idPret]);
    $pret = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pret) {
        Flight::json(['error' => 'Prêt introuvable.']);
        return;
    }

    // Recalcule simulation
    $montant = (float) $pret['montant'];
    $duree = (int) $pret['duree'];
    $delai = (int) $pret['delai'];
    $tauxMensuel = ((float) $pret['taux']) / 100 / 12;
    $assuranceTotale = ($montant * ((float) $pret['assurance']) / 100);
    $assuranceMensuelle = round($assuranceTotale / $duree, 2);
    $mensualite = ($montant * $tauxMensuel) / (1 - pow(1 + $tauxMensuel, -($duree )));
    $mensualite = round($mensualite, 2);

    $reste = $montant;
    $date = new DateTime($pret['date_pret']);

    for ($i = 1; $i <= $duree+$delai; $i++) {
        $date->modify('+1 month');
        $strDate = $date->format('Y-m-d');

        if ($i <= $delai) {
            $interet = 0;
            $amortissement = 0;
            $assuranceMensuelle = 0;
        } else {
            $assuranceMensuelle = round($assuranceTotale / $duree, 2);
            $interet = round($reste * $tauxMensuel, 2);
            $amortissement = round($mensualite - $interet, 2);
        }

        $reste = round($reste - $amortissement, 2);
        if ($reste < 0) $reste = 0;

        // Insertion dans remboursement
        RemboursementPret::create($db, (object)[
            'date_remboursement' => $strDate,
            'montant_paye_base' => $amortissement,
            'montant_paye_interet' => $interet,
            'montant_restant' => $reste,
            'assurance' => $assuranceMensuelle,
            'id_pret' => $idPret
        ]);

        // Insertion dans mouvement banque (entrée)
        MouvementBanque::create($db, [
            'date_mouvement' => $strDate,
            'montant' => $amortissement + $interet + $assuranceMensuelle,
            'id_compte_banque' => 1, // fixe ici
            'id_type_mouvement' => 2 // entrée
        ]);
    }

    Flight::json(['message' => 'Remboursements et mouvements enregistrés.']);
}



    public static function find_montant_interet_between_dates() {
        $db = getDB();
        $data = Flight::request()->data;
        // Accept both date_debut/date_fin and start_date/end_date for compatibility
        $start = isset($data->start_date) ? $data->start_date : (isset($data->date_debut) ? $data->date_debut : null);
        $end = isset($data->end_date) ? $data->end_date : (isset($data->date_fin) ? $data->date_fin : null);
        if (!$start || !$end) {
            Flight::json([]);
            return;
        }
        $result = RemboursementPret::find_montant_interet_between($db, $start, $end);
        Flight::json($result);
    }
}