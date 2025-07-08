// On page load, fetch all prets
window.addEventListener('DOMContentLoaded', function () {
    fetchAllPrets();
});

document.getElementById('filterForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    fetchPrets(startDate, endDate);
});

function fetchAllPrets() {
    ajax('GET', '/prets', null, function (response) {
        renderPretTable(response);
    });
}

function fetchPrets(startDate, endDate) {
    ajax('POST', '/pret/find_between_dates', `start_date=${startDate}&end_date=${endDate}`, function (response) {
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
    window.location.href = `details-pret.php?id=${idPret}`;
}