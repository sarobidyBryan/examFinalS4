<?php
require_once __DIR__ . '/../models/ParamSimulation.php';
require_once __DIR__ . '/../db.php';

class ParamSimulationController
{
    public static function getAll()
    {
        $db = getDB();
        $data = ParamSimulation::getAll($db);
        Flight::json($data);
    }

 public static function create()
  {
    $data = Flight::request()->data;

    // Validation côté backend
    if (!$data->id_compte_client || !$data->id_type_pret || !$data->date_pret || !$data->montant || !$data->duree) {
      Flight::json(['error' => 'Tous les champs sont obligatoires.']);
      return;
    }

    $result = ParamSimulation::create($data);

    if (isset($result['error'])) {
      Flight::json(['error' => $result['error']]);
    } else {
      Flight::json(['message' => 'simulation créé avec succès.', 'id' => $result['id']]);
    }
  }


    public static function get()
    {
        $data = Flight::request()->data;
        $param = ParamSimulation::get($data);
        if ($param) {
            Flight::json($param);
        } else {
            Flight::json(['error' => 'Parametres non trouvé.'], 404);
        }
    }
}
