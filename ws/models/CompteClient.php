<?php

require_once __DIR__ . '/../db.php';

class CompteClient
{
    public static function getComptesClients($db)
    {

        $stmt = $db->query("
        SELECT cc.id_compte_client, c.nom, c.prenom
        FROM ef_compte_client cc
        JOIN ef_client c ON cc.id_client = c.id_client
    ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
