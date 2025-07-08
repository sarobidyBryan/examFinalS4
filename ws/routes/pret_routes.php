<?php
require_once __DIR__ . '/../controllers/PretController.php';

Flight::route('POST /prets', ['PretController', 'create']);
Flight::route('GET /prets', ['PretController', 'getAll']);
Flight::route('GET /prets/@id', ['PretController', 'getById']);
Flight::route('DELETE /prets/@id', ['PretController', 'delete']);
Flight::route('GET /creer-pret', ['PretController', 'getCreateForm']);
Flight::route('POST /pret/find_between_dates', ['PretController', 'findBetweenDates']);
Flight::route('POST /pret/ajout-simulation',['PretController','createSimulation']);