<?php
require_once __DIR__ . '/../models/DefinitionAnnee.php';
require_once __DIR__ . '/../db.php';

class DefinitionAnneeController {
    public static function getAll() {
        $db = getDB();
        $data = DefinitionAnnee::getAll($db);
        Flight::json($data);
    }
}
