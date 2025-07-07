<?php
require_once __DIR__ . '/../controllers/FondController.php';

Flight::route('POST /fond', ['FondController', 'create']);
