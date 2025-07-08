<?php
class MouvementBanque {
    public static function create($db, $data) {
        $stmt = $db->prepare("
            INSERT INTO ef_mouvement_banque (date_mouvement, montant, id_compte_banque, id_type_mouvement)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['date_mouvement'],
            $data['montant'],
            $data['id_compte_banque'],
            $data['id_type_mouvement']
        ]);
        return ['id' => $db->lastInsertId()];
    }
}
