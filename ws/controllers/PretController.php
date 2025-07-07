<?php
require_once __DIR__ . '/../models/Pret.php';
require_once __DIR__ . '/../helpers/Utils.php';
class PretController {
  public static function create() {
    $data = Flight::request()->data;

    // Validation côté backend
    if (!$data->id_compte_client || !$data->id_type_pret || !$data->date_pret || !$data->montant || !$data->duree) {
      Flight::json(['error' => 'Tous les champs sont obligatoires.']);
      return;
    }

    $result = Pret::create($data);

    if (isset($result['error'])) {
      Flight::json(['error' => $result['error']]);
    } else {
      Flight::json(['message' => 'Prêt créé avec succès.', 'id' => $result['id']]);
    }
  }

  public static function getAll() {
    $db = getDB();
    $data = Pret::getAll($db);
    Flight::json($data);
  }

  public static function findBetweenDates() {
    $data = Flight::request()->data;
    $startDate = $data->start_date;
    $endDate = $data->end_date;

    $result = Pret::findBetweenDates($startDate, $endDate);
    Flight::json($result);
  }

  public static function getById($id) {
    $db = getDB();
    $pret = Pret::findById($db, $id);
    if ($pret) {
      Flight::json($pret);
    } else {
      Flight::json(['error' => 'Prêt non trouvé.'], 404);
    }
  }

}
