<?php
class TypePret {
    public $id_type_pret;
    public $taux;
    public $duree_min;
    public $description;
    public $pret_min;
    public $pret_max;
    public $id_calcul_ti;
    public $id_def_annee;

    public static function all($db) {
        $stmt = $db->query("SELECT * FROM ef_type_pret");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($db, $id) {
        $stmt = $db->prepare("SELECT * FROM ef_type_pret WHERE id_type_pret = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($db, $data) {
        $stmt = $db->prepare("INSERT INTO ef_type_pret (taux, duree_min, description, pret_min, pret_max, id_calcul_ti, id_def_annee) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['taux'], $data['duree_min'], $data['description'], $data['pret_min'], $data['pret_max'], $data['id_calcul_ti'], $data['id_def_annee']
        ]);
        return $db->lastInsertId();
    }

    public static function update($db, $id, $data) {
        $stmt = $db->prepare("UPDATE ef_type_pret SET taux=?, duree_min=?, description=?, pret_min=?, pret_max=?, id_calcul_ti=?, id_def_annee=? WHERE id_type_pret=?");
        return $stmt->execute([
            $data['taux'], $data['duree_min'], $data['description'], $data['pret_min'], $data['pret_max'], $data['id_calcul_ti'], $data['id_def_annee'], $id
        ]);
    }

    public static function delete($db, $id) {
        $stmt = $db->prepare("DELETE FROM ef_type_pret WHERE id_type_pret = ?");
        return $stmt->execute([$id]);
    }
}
