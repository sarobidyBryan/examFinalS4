<?php
require_once __DIR__ . '/../db.php';
class DefinitionAnnee {
    public static function getAll($db) {
        $stmt = $db->query("SELECT * FROM ef_definition_annee");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
