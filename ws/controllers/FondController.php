<?php
require_once __DIR__ . '/../models/Fond.php';

class FondController
{
    public static function fond()
    {
        try {
            $solde = Fond::getFond();
            Flight::json(['success' => true, 'solde' => $solde]);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    public static function create()
    {
        $data = Flight::request()->data;
        try {
            $result = Fond::create($data);
            $id = $result['id'] ?? null;
            $dateDeDepot = $result['dateDeDepot'] ?? null;
            $solde = $result['solde'] ?? null;
            Flight::json(['success' => true, 'message' => 'Fond ajouté', 'id' => $id,'solde' => $solde, 'dateDeDepot' => $dateDeDepot]);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
