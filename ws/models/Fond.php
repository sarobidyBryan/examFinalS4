<?php
require_once __DIR__ . '/../db.php';

class Fond{
    public static function create($data) {
        $db = getDB();
        try {
            $db->beginTransaction();
            
            // Insérer dans ef_mouvement_banque
            $stmt = $db->prepare("INSERT INTO ef_mouvement_banque (date_mouvement, montant, id_compte_banque, id_type_mouvement) VALUES (?, ?, 1, 1)");
            $stmt->execute([date('Y-m-d'), $data['montant']]);
            $id_mouvement = $db->lastInsertId();

            // Mettre à jour le compte_banque (supposons que $data->id_compte_banque est fourni)
            $stmt = $db->prepare("SELECT solde_actuel FROM ef_compte_banque WHERE id_compte_banque = 1");
            $stmt->execute();
            $compte = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$compte) {
            throw new Exception("Compte banque introuvable");
            }

            $solde_precedent = $compte['solde_actuel'];
            $solde_actuel = $solde_precedent + $data['montant'];

            $stmt = $db->prepare("UPDATE ef_compte_banque SET solde_precedent = ?, solde_actuel = ? WHERE id_compte_banque = 1");
            $stmt->execute([$solde_precedent, $solde_actuel]);

            $db->commit();
            return $id_mouvement;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}