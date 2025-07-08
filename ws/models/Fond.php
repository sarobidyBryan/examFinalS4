<?php
require_once __DIR__ . '/../db.php';

class Fond{
    public static function create($data) {
        $montant = $data['montant'] ?? 0;
        $date_de_depot = $data['dateDeDepot'] ?? date('Y-m-d');
        if($montant <= 0) {
            throw new Exception("Le montant doit être supérieur à zéro.");
        }
        $db = getDB();
        try {
            $db->beginTransaction();
            
            // Insérer dans ef_mouvement_banque
            $stmt = $db->prepare("INSERT INTO ef_mouvement_banque (date_mouvement, montant, id_compte_banque, id_type_mouvement) VALUES (?, ?, 1, 2)");
            $stmt->execute([$date_de_depot, $data['montant']]);
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
            $result = [];
            $result['id'] = $id_mouvement;
            $result['dateDeDepot'] = $date_de_depot;
            $result['solde'] = $solde_actuel;
            return $result;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function getFond() {
        $db = getDB();
        $stmt = $db->prepare("SELECT solde_actuel FROM ef_compte_banque WHERE id_compte_banque = 1");
        $stmt->execute();
        $compte = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$compte) {
            throw new Exception("Compte banque introuvable");
        }
        
        return $compte['solde_actuel'];
    }
}