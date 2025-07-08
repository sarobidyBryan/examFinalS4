<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="comparaison.css"> -->
    <?php include('inc/head.php') ?>
</head>
<body>
    <?php include('inc/navigation.php') ?>
    <div class="main-content">
        <h1>Comparaison de 2 simulation de prêt</h1>
        <i>Vous pouvez <a href="form-pret.php">ajouter une simulation</a> pour pouvoir faire d'autre comparaison</i>
        <div id="formulaire">
            <label for="">Choix pour simulation 1 :</label>
            <select name="choix1" id="choix1">-- simulation 1-- 
                <!-- <option disabled value="">-- simulation 1-- </option> -->
            </select>
            <label for="">Choix pour simulation 1 :</label>
            <select name="choix2" id="choix2">-- simulation 2-- 
                <!-- <option disabled value="">-- simulation 1-- </option> -->
            </select>
            <button onclick="faireComparaison()">Comparer</button>
        </div>
        <div id="resultats-comparaison" style="display: flex; gap: 20px; flex-wrap: wrap;"></div>
    </div>
    <script src="ws/assets/js/base.js"></script>
    <script src="ws/assets/js/comparaison-simulation.js"></script>
</body>
</html>