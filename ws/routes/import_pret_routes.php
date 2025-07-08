<?php
require_once __DIR__ . '/../controllers/PretController.php';

Flight::route('POST /import-prets', ['PretController', 'import']);
