<?php
require_once __DIR__ . '/../models/CompteBanque.php';

Flight::route('GET /comptes-banque', function() {
  $comptes = CompteBanque::getAll();
  Flight::json($comptes);
});

Flight::route('GET /solde-banque/@id_banque/@date', function($id_banque, $date) {
  $resultat = CompteBanque::getSoldeAtDate($id_banque, $date);
  Flight::json($resultat);
});
