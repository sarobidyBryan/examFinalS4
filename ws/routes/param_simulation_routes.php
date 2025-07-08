<?php
require_once __DIR__ . '/../controllers/ParamSimulationController.php';

Flight::route('POST /param', ['ParamSimulationController', 'create']);
Flight::route('GET /param', ['ParamSimulationController', 'getAll']);
Flight::route('GET /params', ['ParamSimulationController', 'get']);

