<?php

require_once __DIR__ . '/../db.php';
class CalculTauxInteret {
    public static function getAll($db) {
        $stmt = $db->query("SELECT * FROM ef_calcul_taux_interet");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
