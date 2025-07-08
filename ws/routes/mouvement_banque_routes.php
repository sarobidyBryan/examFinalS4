<?php
require_once __DIR__ . '/../controllers/MouvementBanqueController.php';

Flight::route('POST /mouvement-banque', [MouvementBanqueController::class, 'create']);
