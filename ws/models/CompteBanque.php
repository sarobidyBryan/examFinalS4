<?php
require_once __DIR__ . '/../db.php';

class CompteBanque {
  public static function getAll() {
    $db = getDB();
    $stmt = $db->query("
      SELECT cb.id_compte_banque, cb.solde_actuel, cb.solde_precedent, b.nom 
      FROM ef_compte_banque cb
      JOIN ef_banque b ON cb.id_banque = b.id_banque
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getSoldeAtDate($id_banque, $date) {
    $db = getDB();

    $query = "
      SELECT
        SUM(CASE WHEN mb.id_type_mouvement = 2 THEN mb.montant ELSE 0 END) AS total_entrees,
        SUM(CASE WHEN mb.id_type_mouvement = 1 THEN mb.montant ELSE 0 END) AS total_sorties
      FROM ef_mouvement_banque mb
      JOIN ef_compte_banque cb ON mb.id_compte_banque = cb.id_compte_banque
      WHERE cb.id_banque = ? AND mb.date_mouvement <= ?
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([$id_banque, $date]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
      'entrees' => $row['total_entrees'] ?? 0,
      'sorties' => $row['total_sorties'] ?? 0,
      'solde' => ($row['total_entrees'] ?? 0) - ($row['total_sorties'] ?? 0)
    ];
  }
}
