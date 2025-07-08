<?php

class RemboursementPret{
    public $id_remboursement_pret;
    public $date_remboursement;
    public $montant_paye_base;
    public $montant_paye_interet;
    public $montant_restant;
    public $assurance;
    public $id_pret;

    public static function create($db, $data) {
        $stmt = $db->prepare("INSERT INTO ef_remboursement_pret (date_remboursement, montant_paye_base, montant_paye_interet, montant_restant, assurance, id_pret) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data->date_remboursement,
            $data->montant_paye_base,
            $data->montant_paye_interet,
            $data->montant_restant,
            $data->assurance,
            $data->id_pret
        ]);

        return ['id' => $db->lastInsertId()];
    }

    public static function find_montant_interet_between($db, $start_date, $end_date) {
        $stmt = $db->prepare("SELECT * FROM ef_remboursement_pret 
                              WHERE date_remboursement BETWEEN ? AND ?");
        $stmt->execute([$start_date, $end_date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll($db) {
        $stmt = $db->query("SELECT * FROM ef_remboursement_pret");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}