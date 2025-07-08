// resume-fond.js
// Affiche le tableau des fonds avec filtre par période

function renderFondTable(data) {
    const table = document.getElementById('fondTable');
    // Supprimer toutes les lignes sauf l'en-tête
    while (table.rows.length > 1) table.deleteRow(1);
    if (!data || data.length === 0) {
        const row = table.insertRow();
        const cell = row.insertCell();
        cell.colSpan = 5;
        cell.innerText = 'Aucune donnée pour cette période.';
        return;
    }
    data.forEach(item => {
        const row = table.insertRow();
        row.insertCell().innerText = item.mois;
        row.insertCell().innerText = item.annee;
        row.insertCell().innerText = item.base_fond;
        row.insertCell().innerText = item.remboursements;
        row.insertCell().innerText = item.total;
    });
}

function fetchResumeFond(start, end) {
    ajax('POST', '/resume-fond', `start_date=${encodeURIComponent(start)}&end_date=${encodeURIComponent(end)}`, function(data) {
        renderFondTable(data);
    });
}

document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const start = document.getElementById('start_date').value;
    const end = document.getElementById('end_date').value;
    fetchResumeFond(start, end);
});

// Optionally, load all by default (e.g., for current year)
window.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const yyyy = today.getFullYear();
    document.getElementById('start_date').value = `${yyyy}-01-01`;
    document.getElementById('end_date').value = `${yyyy}-12-31`;
    fetchResumeFond(`${yyyy}-01-01`, `${yyyy}-12-31`);
});
