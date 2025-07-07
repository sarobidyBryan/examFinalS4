<?php
require_once __DIR__ . '/../models/Fond.php';

class FondController
{
    public static function create()
    {
        $data = Flight::request()->data;
        try {
            $id = Fond::create($data);
            Flight::json(['success' => true, 'message' => 'Fond ajouté', 'id' => $id]);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
