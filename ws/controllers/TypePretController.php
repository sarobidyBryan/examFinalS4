<?php
require_once __DIR__ . '/../models/TypePret.php';
require_once __DIR__ . '/../db.php';

class TypePretController {
    public static function getAll() {
        $db = getDB();
        $data = TypePret::all($db);
        Flight::json($data);
    }

    public static function getOne($id) {
        $db = getDB();
        $typePret = TypePret::find($db, $id);
        if ($typePret) {
            echo json_encode($typePret);
        } else {
            http_response_code(404);
            Flight::json(["error" => "Not found"]);
        }
    }

    public static function create() {
        $db = getDB();
        $data = $_POST;
        $id = TypePret::create($db, $data);
        Flight::json(["id" => $id]);
    }

    public static function update($id) {
        $db = getDB();
        parse_str(file_get_contents("php://input"), $put_vars);
        $ok = TypePret::update($db, $id, $put_vars);
        Flight::json(["success" => $ok]);
    }

    public static function delete($id) {
        $db = getDB();
        $ok = TypePret::delete($db, $id);
        Flight::json(["success" => $ok]);
    }
}
