<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste Prets avec selection details</title>
    <link rel="stylesheet" href="ws/assets/css/liste-pret.css">
    <?php include('inc/head.php') ?>
</head>

<body>
    <?php include('inc/navigation.php') ?>
    <div class="main-content">
        <h1>Liste des Prêts</h1>
        <form id="filterForm" autocomplete="off">
            <label for="start_date">Date de début:</label>
            <input type="date" id="start_date" name="start_date" required>
            <label for="end_date">Date de fin:</label>
            <input type="date" id="end_date" name="end_date" required>
            <button type="submit">Filtrer</button>
        </form>
        <table id="pretTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date de prêt</th>
                    <th>Montant</th>
                    <th>Type de prêt</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <script src="ws/assets/js/base.js"></script>
    <script src="ws/assets/js/liste-pret.js"></script>
</body>

</html>