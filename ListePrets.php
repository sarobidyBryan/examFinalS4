<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste Prets avec selection details</title>
</head>
<body>
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

    <script src="ws/assets/js/base.js"></script>
    <script>
        // On page load, fetch all prets
        window.addEventListener('DOMContentLoaded', function() {
            fetchAllPrets();
        });

        document.getElementById('filterForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            fetchPrets(startDate, endDate);
        });

        function fetchAllPrets() {
            ajax('GET', '/prets', null, function(response) {
                renderPretTable(response);
            });
        }

        function fetchPrets(startDate, endDate) {
            ajax('POST', '/pret/find_between_dates', `start_date=${startDate}&end_date=${endDate}`, function(response) {
                renderPretTable(response);
            });
        }

        function renderPretTable(response) {
            const tbody = document.querySelector('#pretTable tbody');
            tbody.innerHTML = '';
            if (!response || response.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5">Aucun prêt trouvé pour cette période.</td></tr>';
                return;
            }
            response.forEach(pret => {
                tbody.innerHTML += `<tr>
                    <td>${pret.id_pret}</td>
                    <td>${pret.date_pret}</td>
                    <td>${pret.montant}</td>
                    <td>${pret.type_pret ? pret.type_pret : (pret.id_type_pret || '')}</td>
                    <td><button onclick="showDetails(${pret.id_pret})">Voir Détails</button></td>
                </tr>`;
            });
        }

        function showDetails(idPret) {
            window.location.href = `details_pret.php?id=${idPret}`;
        }
    </script>
    
</body>
</html>