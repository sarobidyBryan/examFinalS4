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
  
  
}
