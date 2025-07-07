<?php
require 'vendor/autoload.php';
require 'db.php';

require 'routes/etudiant_routes.php';
require 'routes/pret_routes.php';
require 'routes/type_pret_routes.php';
require 'routes/calcul_taux_interet_routes.php';
require 'routes/definition_annee_routes.php';

Flight::start();