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
        <h2>Ajouter un Fond</h2>
        <p id="solde-section"><strong>Fond actuel : </strong><span id="solde"></span></p>
        <label for="montant">Montant :</label>
        <input type="number" id="montant" name="montant" step="0.01" min="0" required placeholder="Entrez un montant">

        <button onclick="ajoutMontant()" class="btn-ajouter">
            <i class="fa fa-plus"></i> Ajouter
        </button>

        <p id="message" class="message"></p>
    </div>

    <script src="ws/assets/js/base.js"></script>
    <script src="ws/assets/js/ajout-fond.js"></script>
</body>

</html>