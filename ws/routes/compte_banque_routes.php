<?php
require_once __DIR__ . '/../models/CompteBanque.php';

// Obtenir tous les comptes banque
Flight::route('GET /comptes-banque', function () {
    $comptes = CompteBanque::getAll();
    Flight::json($comptes);
});

// Obtenir le solde d’une banque à une date donnée
Flight::route('GET /solde-banque/@id_banque/@date', function ($id_banque, $date) {
    $resultat = CompteBanque::getSoldeAtDate($id_banque, $date);
    Flight::json($resultat);
});

