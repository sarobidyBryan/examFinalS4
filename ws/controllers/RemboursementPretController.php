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
        $id_pret = $data->id_pret ?? null;
        $date_debut = $data->date_debut ?? null;
        $date_fin = $data->date_fin ?? null;
        $result = RemboursementPret::find_montant_interet_between($db, $id_pret, $date_debut, $date_fin);
        Flight::json($result);
    }
}