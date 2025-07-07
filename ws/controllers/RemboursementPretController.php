<?php
require_once __DIR__ . '/../models/RemboursementPret.php';
require_once __DIR__ . '/../db.php';

class RemboursementPretController {
    public static function getAll() {
        $db = getDB();
        $data = RemboursementPret::getAll($db);
        Flight::json($data);
    }

    public static function create() {
        $db = getDB();
        $data = Flight::request()->data;
        $result = RemboursementPret::create($db, $data);
        Flight::json($result);
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