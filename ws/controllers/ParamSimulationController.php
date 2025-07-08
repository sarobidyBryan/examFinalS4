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

    public static function create($data)
    {
<<<<<<< Updated upstream
        $data = ParamSimulation::create($data);
        Flight::json($data);
=======
        $data = Flight::request()->data;
        $result = ParamSimulation::create($data);

        if (is_array($result) && isset($result['error'])) {
            Flight::json(['success' => false, 'error' => $result['error']]);
        } else {
            Flight::json([
                'success' => true,
                'message' => 'Simulation créée avec succès.',
                'id' => $result
            ]);
        }
>>>>>>> Stashed changes
    }


    public static function get($id)
    {
        $param = ParamSimulation::get($id);
        if ($param) {
            Flight::json($param);
        } else {
            Flight::json(['error' => 'Parametres non trouvé.'], 404);
        }
    }
}
