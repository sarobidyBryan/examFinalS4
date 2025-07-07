<?php
require_once __DIR__ . '/../controllers/CalculTauxInteretController.php';
Flight::route('GET /calcul_taux_interet', ['CalculTauxInteretController', 'getAll']);
