<?php
require_once __DIR__ . '/../db.php';

class Pret
{
  public static function create($data)
  {
    $db = getDB();
    $id_banque = 1;
    $soldeData = CompteBanque::getSoldeAtDate($id_banque, $data->date_pret);
    $solde_banque = $soldeData['solde'];

    if ($solde_banque <= $data->montant) {
      return ['error' => "Solde insuffisant. Disponible: $solde_banque, requis: $data->montant"];
    }
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

    $stmt = $db->prepare("INSERT INTO ef_mouvement_banque (date_mouvement, montant, id_compte_banque, id_type_mouvement)
                        VALUES (?, ?, ?, ?)");
    $stmt->execute([
      $data->date_pret,
      $data->montant,
      1,  // id_compte_banque
      1   // id_type_mouvement = 1 => 'debit'
    ]);
    // Insérer le prêt
    $stmt = $db->prepare("INSERT INTO ef_pret (date_pret, montant, duree, id_type_pret, id_compte_client, delai, assurance) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
      $data->date_pret,
      $data->montant,
      $data->duree,
      $data->id_type_pret,
      $data->id_compte_client,
      $data->delai,
      $data->assurance
    ]);
    return ['id' => $db->lastInsertId()];
  }

  public static function getAll($db)
  {
    $stmt = $db->query("SELECT * FROM ef_pret");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function findBetweenDates($start_date, $end_date)
  {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM ef_pret WHERE date_pret BETWEEN ? AND ?");
    $stmt->execute([$start_date, $end_date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function findById($db, $id)
  {
    $stmt = $db->prepare("SELECT * FROM ef_pret WHERE id_pret = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public static function createSimlulation($data)
  {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO ef_param_simulation(date_pret,montant,duree,id_type_pret,id_compte_client,delai,assurance,date_simulation,description) values (?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
      $data['date_pret'],
      $data['montant'],
      $data['duree'],
      $data['id_type_pret'],
      $data['id_compte_client'],
      $data['delai'],
      $data['assurance'],
      date('Y-m-d'), // date_simulation = maintenant
      isset($data['description']) ? $data['description'] : null
    ]);
    return $db->lastInsertId();
  }
}
