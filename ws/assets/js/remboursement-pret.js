function renderTableAndChart(remboursements) {
    // Mettre à jour le tableau
    const tbody = document.querySelector('#rembTable tbody');
    tbody.innerHTML = '';
    if (!remboursements || !Array.isArray(remboursements) || remboursements.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:#888;">Aucun remboursement trouvé pour cette période.</td></tr>';
    } else {
        let hasData = false;
        remboursements.forEach(remboursement => {
            if (remboursement && remboursement.id_remboursement_pret) {
                hasData = true;
                tbody.innerHTML += `<tr>
                            <td>${remboursement.id_remboursement_pret}</td>
                            <td>${remboursement.date_remboursement}</td>
                            <td>${formatMontant(remboursement.montant_paye_base)}</td>
                            <td>${formatMontant(remboursement.montant_paye_interet)}</td>
                            <td>${formatMontant(remboursement.montant_restant)}</td>
                            <td>${formatMontant(remboursement.assurance)}</td>
                            <td>${remboursement.id_pret}</td>
                        </tr>`;
            }
        });
        if (!hasData) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:#888;">Aucun remboursement trouvé pour cette période.</td></tr>';
        }
    }

    // Agréger par mois
    const rows = document.querySelectorAll('#rembTable tbody tr');
    const monthlyData = {};
    rows.forEach(row => {
        const cols = row.querySelectorAll('td');
        // Ignore row if it's the "aucun remboursement" message
        if (cols.length < 7) return;
        const date = cols[1].innerText;
        const base = parseFloat(cols[2].innerText);
        const interet = parseFloat(cols[3].innerText);
        const month = date.slice(0, 7);
        if (!monthlyData[month]) {
            monthlyData[month] = { base: 0, interet: 0 };
        }
        monthlyData[month].base += base;
        monthlyData[month].interet += interet;
    });
    const months = Object.keys(monthlyData).sort();
    const dataMontantBase = months.map(m => monthlyData[m].base);
    const dataMontantInteret = months.map(m => monthlyData[m].interet);

    // Afficher un graphique simple en SVG (sans Chart.js)
    const graphContainer = document.getElementById('graphRemboursementPret');
    // Effacer le canvas
    graphContainer.width = graphContainer.width; // reset

    // Si pas de données, rien à dessiner
    if (months.length === 0) return;

    // Dimensions
    const width = graphContainer.width;
    const height = graphContainer.height;
    const padding = 40;
    const barWidth = Math.max(10, (width - 2 * padding) / months.length / 2);
    const maxY = Math.max(...dataMontantBase, ...dataMontantInteret, 1);

    // Axes
    const ctx = graphContainer.getContext('2d');
    ctx.clearRect(0, 0, width, height);
    ctx.strokeStyle = '#333';
    ctx.beginPath();
    ctx.moveTo(padding, padding);
    ctx.lineTo(padding, height - padding);
    ctx.lineTo(width - padding, height - padding);
    ctx.stroke();

    // Barres
    months.forEach((month, i) => {
        // Base
        const x = padding + i * barWidth * 2 + barWidth * 0.1;
        const y = height - padding - (dataMontantBase[i] / maxY) * (height - 2 * padding);
        const h = (dataMontantBase[i] / maxY) * (height - 2 * padding);
        ctx.fillStyle = 'rgba(75, 192, 192, 0.7)';
        ctx.fillRect(x, y, barWidth * 0.8, h);
        // Intérêt
        const x2 = x + barWidth;
        const y2 = height - padding - (dataMontantInteret[i] / maxY) * (height - 2 * padding);
        const h2 = (dataMontantInteret[i] / maxY) * (height - 2 * padding);
        ctx.fillStyle = 'rgba(153, 102, 255, 0.7)';
        ctx.fillRect(x2, y2, barWidth * 0.8, h2);
        // Mois label
        ctx.save();
        ctx.fillStyle = '#222';
        ctx.font = '10px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(month, x + barWidth * 0.9, height - padding + 12);
        ctx.restore();
    });

    // Légende
    ctx.save();
    ctx.font = '12px sans-serif';
    ctx.fillStyle = 'rgba(75, 192, 192, 0.7)';
    ctx.fillRect(width - padding - 120, padding, 15, 10);
    ctx.fillStyle = '#222';
    ctx.fillText('Montant payé (base)', width - padding - 100, padding + 10);
    ctx.fillStyle = 'rgba(153, 102, 255, 0.7)';
    ctx.fillRect(width - padding - 120, padding + 20, 15, 10);
    ctx.fillStyle = '#222';
    ctx.fillText('Montant payé (intérêt)', width - padding - 100, padding + 30);
    ctx.restore();
}

function filterData(start, end) {
    ajax('POST', '/remboursement_pret/find_montant_interet_between_dates', `start_date=${encodeURIComponent(start)}&end_date=${encodeURIComponent(end)}`, function (data) {
        renderTableAndChart(data);
    });
}

const canvas = document.getElementById('graphRemboursementPret');
const ctx = canvas.getContext('2d');

// Initialiser le graphique et le tableau avec les données actuelles (requête AJAX pour cohérence)
document.getElementById('filterForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const start = document.getElementById('start_date').value;
    const end = document.getElementById('end_date').value;
    filterData(start, end);
});

// Charger tous les remboursements au chargement initial
function loadAllRemboursements() {
    ajax('GET', '/remboursement_pret', null, function (data) {
        renderTableAndChart(data);
    });
}
loadAllRemboursements();


// Synchronise les dates du filtre avec le formulaire PDF
document.getElementById('filterForm').addEventListener('submit', function () {
    canvas.style.display = 'block'; // Afficher le canvas
    document.getElementById('pdf_start_date').value = document.getElementById('start_date').value;
    document.getElementById('pdf_end_date').value = document.getElementById('end_date').value;
});
// Initialiser les champs PDF au chargement
window.addEventListener('DOMContentLoaded', function () {
    document.getElementById('pdf_start_date').value = document.getElementById('start_date').value;
    document.getElementById('pdf_end_date').value = document.getElementById('end_date').value;
});