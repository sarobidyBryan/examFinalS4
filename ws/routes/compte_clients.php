<?php
require_once __DIR__ . '/../controllers/CompteClientController.php';

Flight::route('GET /comptes-clients', ['CompteClientController', 'getComptesClients']);
