<?php
require_once __DIR__ . '/../models/Pret.php';
require_once __DIR__ . '/../helpers/Utils.php';
require_once __DIR__ . '/../models/ImportPret.php';
class PretController
{

  public static function createSimulation()
  {
    $data = Flight::request()->data;
    // if (!$data->id_compte_client || !$data->id_type_pret || !$data->date_pret || !$data->montant || !$data->duree) {
    //   Flight::json(['error' => 'Tous les champs sont obligatoires.']);
    //   return;
    // }
    $id = Pret::createSimlulation($data);
    if ($id) {
      Flight::json(['success' => true, 'message' => 'Simulation créé avec succès.', 'id' => $id]);
    } else {
      Flight::json(['success' => false, 'error' => 'insertion de simulation non réussi']);
    }
  }
  public static function create()
  {
    $data = Flight::request()->data;

    // Validation côté backend
    if (!$data->id_compte_client || !$data->id_type_pret || !$data->date_pret || !$data->montant || !$data->duree) {
      Flight::json(['error' => 'Tous les champs sont obligatoires.']);
      return;
    }

    $result = Pret::create($data);

    if (isset($result['error'])) {
      Flight::json(['error' => $result['error']]);
    } else {
      Flight::json(['message' => 'Prêt créé avec succès.', 'id' => $result['id']]);
    }
  }

  public static function getAll()
  {
    $db = getDB();
    $data = Pret::getAll($db);
    Flight::json($data);
  }

  public static function findBetweenDates()
  {
    $data = Flight::request()->data;
    $startDate = $data->start_date;
    $endDate = $data->end_date;

    $result = Pret::findBetweenDates($startDate, $endDate);
    Flight::json($result);
  }

  public static function getById($id)
  {
    $db = getDB();
    $pret = Pret::findById($db, $id);
    if ($pret) {
      Flight::json($pret);
    } else {
      Flight::json(['error' => 'Prêt non trouvé.'], 404);
    }
  }


  public static function import()
  {
    if (!isset($_FILES['csv'])) {
      Flight::json(['error' => 'Aucun fichier reçu.'], 400);
      return;
    }

    switch ($_FILES['csv']['error']) {
      case UPLOAD_ERR_OK:
        // ✅ Pas d'erreur, on continue
        break;
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        Flight::json(['error' => 'Le fichier est trop volumineux.'], 400);
        return;
      case UPLOAD_ERR_PARTIAL:
        Flight::json(['error' => 'Le fichier a été partiellement envoyé.'], 400);
        return;
      case UPLOAD_ERR_NO_FILE:
        Flight::json(['error' => 'Aucun fichier sélectionné.'], 400);
        return;
      case UPLOAD_ERR_NO_TMP_DIR:
        Flight::json(['error' => 'Dossier temporaire manquant sur le serveur.'], 500);
        return;
      case UPLOAD_ERR_CANT_WRITE:
        Flight::json(['error' => 'Erreur d’écriture sur le disque.'], 500);
        return;
      case UPLOAD_ERR_EXTENSION:
        Flight::json(['error' => 'Une extension PHP a bloqué l’envoi du fichier.'], 500);
        return;
      default:
        Flight::json(['error' => 'Erreur inconnue lors de l’upload.'], 500);
        return;
    }


    $db = getDB();
    $file = $_FILES['csv']['tmp_name'];

    $resultats = ImportPret::processCSV($db, $file);
    Flight::json($resultats);
  }
}
