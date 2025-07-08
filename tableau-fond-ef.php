<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des fonds (filtre par periode)</title>
</head>

<body>
    <!-- formulaire filtre -->
    <form id="filterForm" autocomplete="off">
        <label for="start_date">Date de début:</label>
        <input type="date" id="start_date" name="start_date" required>
        <label for="end_date">Date de fin:</label>
        <input type="date" id="end_date" name="end_date" required>
        <button type="submit">Filtrer</button>
    </form>
    <!-- tableau -->
    <table id="fondTable">
        <tr>
            <th>Mois</th>
            <th>Annee</th>
            <th>Montant payé (base)</th>
            <th>Montant payé (remboursements)</th>
            <th>Montant Total</th>
        </tr>

    </table>

    <script src="ws/assets/js/base.js"></script>
    <script src="ws/assets/js/resume-fond.js"></script>


</body>

</html>