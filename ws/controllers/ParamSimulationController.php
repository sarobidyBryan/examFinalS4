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
        $data = ParamSimulation::create($data);
        Flight::json($data);
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
