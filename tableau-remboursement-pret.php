<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des remboursements de pret (filtre par période)</title>
    <link rel="stylesheet" href="ws/assets/css/tableau-remboursement-pret.css">
    <?php include('inc/head.php') ?>
</head>

<body>
    <?php include('inc/navigation.php') ?>
    <div class="main-content">
        <h1>Tableau des remboursements de prêt (filtre par période)</h1>

        <!-- Formulaire de filtre -->
        <form id="filterForm" autocomplete="off">
            <label for="start_date">Date de début:</label>
            <input type="date" id="start_date" name="start_date" required>
            <label for="end_date">Date de fin:</label>
            <input type="date" id="end_date" name="end_date" required>
            <button type="submit">Filtrer</button>
        </form>

        <!-- ✅ Canvas déplacé juste ici, après le formulaire -->
        <canvas style="display: none;" id="graphRemboursementPret" width="500" height="300"></canvas>

        <!-- Tableau -->
        <table id="rembTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date de remboursement</th>
                    <th>Montant payé (base)</th>
                    <th>Montant payé (intérêt)</th>
                    <th>Montant restant</th>
                    <th>Assurance</th>
                    <th>ID Prêt</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <br>
        <!-- Export PDF -->
        <form id="pdfExportForm" method="get" action="export_remboursements_pdf.php" target="_blank" style="display:inline;">
            <input type="hidden" name="start_date" id="pdf_start_date">
            <input type="hidden" name="end_date" id="pdf_end_date">
            <button type="submit">Export PDF</button>
        </form>
    </div>

    <script src="ws/assets/js/base.js"></script>
    <script src="ws/assets/js/remboursement-pret.js"></script>
</body>

</html>