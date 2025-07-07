<?php
require_once __DIR__ . '/../models/Fond.php';

class FondController {
    public static function create() {
        $data = Flight::request()->data;
        $id = Fond::create($data);
        Flight::json(['message' => 'Fond ajouté', 'id' => $id]);
    }
}                                                                                                                              