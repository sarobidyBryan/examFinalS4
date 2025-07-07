<?php
require_once __DIR__ . '/../controllers/DefinitionAnneeController.php';
Flight::route('GET /definition_annee', ['DefinitionAnneeController', 'getAll']);
