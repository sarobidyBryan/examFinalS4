<?php
require_once __DIR__ . '/../controllers/SimulationPretController.php';

Flight::route('POST /simuler-pret', ['SimulationPretController', 'simuler']);
