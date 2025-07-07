<?php

require_once __DIR__ . '/../controllers/RemboursementPretController.php';
Flight::route('GET /remboursement_pret', ['RemboursementPretController', 'getAll']);
Flight::route('POST /remboursement_pret/create', ['RemboursementPretController', 'create']);
Flight::route('POST /remboursement_pret/find_montant_interet_between_dates', ['RemboursementPretController', 'find_montant_interet_between_dates']);
