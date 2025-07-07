<?php
require_once __DIR__ . '/../db.php';

class Pret {
  public static function create($data) {
    $db = getDB();

    // Vérifier existence du compte client
    $stmt = $db->prepare("SELECT 1 FROM ef_compte_client WHERE id_compte_client = ?");
    $stmt->execute([$data->id_compte_client]);
    if ($stmt->rowCount() == 0) {
      return ['error' => 'Compte client introuvable.'];
    }

    // Vérifier existence du type de prêt
    $stmt = $db->prepare("SELECT * FROM ef_type_pret WHERE id_type_pret = ?");
    $stmt->execute([$data->id_type_pret]);
    $typePret = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$typePret) {
      return ['error' => 'Type de prêt invalide.'];
    }

    // Vérifier si montant respecte les limites du type de prêt
    if ($data->montant < $typePret['pret_min'] || $data->montant > $typePret['pret_max']) {
      return ['error' => 'Montant en dehors des limites autorisées (' . $typePret['pret_min'] . ' - ' . $typePret['pret_max'] . ').'];
    }

    // Vérifier durée minimale
    if ($data->duree < $typePret['duree_min']) {
      return ['error' => 'Durée minimale requise : ' . $typePret['duree_min'] . ' mois.'];
    }

    // Insérer le prêt
    $stmt = $db->prepare("INSERT INTO ef_pret (date_pret, montant, duree, id_type_pret, id_compte_client) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
      $data->date_pret,
      $data->montant,
      $data->duree,
      $data->id_type_pret,
      $data->id_compte_client
    ]);

    return ['id' => $db->lastInsertId()];
  }
}
