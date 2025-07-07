<?php
require_once __DIR__ . '/../models/CalculTauxInteret.php';
require_once __DIR__ . '/../db.php';

class CalculTauxInteretController {
    public static function getAll() {
        $db = getDB();
        $data = CalculTauxInteret::getAll($db);
        Flight::json($data);
    }
}

