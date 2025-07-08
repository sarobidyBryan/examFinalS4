<?php include('ws/helpers/Utils.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un Fond</title>
    <link rel="stylesheet" href="ws/assets/css/ajout-fond.css">
    <?php include('inc/head.php') ?>
</head>

<body>
    <?php include('inc/navigation.php') ?>
    <div class="main-content">
        <h2>Solde de la banque à une date donnée</h2>

        <form id="form-solde">
            <label for="date_solde">Date :</label>
            <input type="date" id="date_solde" required>
            <button type="submit" class="btn-ajouter">Afficher le solde</button>
        </form>

        <p id="solde-section" style="margin-top: 1em;">
            <strong>Solde à la date donnée : </strong>
            <span id="solde">-</span>
        </p>

        <div id="message"></div>

        <h2>Ajouter un Fond</h2>
        <label for="montant">Montant :</label>
        <input type="number" id="montant" name="montant" step="0.01" min="0" required placeholder="Entrez un montant">
        <label for="dateDeDepot">Date de dépot</label>
        <input type="date" id="dateDeDepot" value="<?= date('Y-m-d'); ?>" name="dateDeDepot" required>
        <button onclick="ajoutMontant()" class="btn-ajouter">
            <i class="fa fa-plus"></i> Ajouter
        </button>

        <p id="message" class="message"></p>
    </div>

    <script src="ws/assets/js/base.js"></script>
    <script src="ws/assets/js/ajout-fond.js"></script>
</body>

</html>