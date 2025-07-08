<?php
require_once __DIR__ . '/../controllers/ExportController.php';

Flight::route('POST /export/comparaison-pdf', ['ExportController', 'exportComparaisonPdf']);