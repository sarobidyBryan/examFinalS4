<?php
require_once __DIR__ . '/../models/MouvementBanque.php';
require_once __DIR__ . '/../db.php';

class MouvementBanqueController {
    public static function create() {
        $db = getDB();
        $data = Flight::request()->data;
        $result = MouvementBanque::create($db, $data);
        Flight::json($result);
    }
}
