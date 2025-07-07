<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../models/CompteClient.php';

class CompteClientController {
    public static function getComptesClients() {
        $db = getDB();
        $comptes = CompteClient::getComptesClients($db);
        Flight::json($comptes);
    }
}