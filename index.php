<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include('inc/head.php') ?>
</head>

<body>
    <?php include('inc/navigation.php') ?>
    <div class="main-content">
        <h1>Bienvenue</h1>
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
    </div>
    <script src="ws\assets\js\base.js" ></script>
    <script src="ws\assets\js\ajout-fond.js" ></script>
</body>

</html>