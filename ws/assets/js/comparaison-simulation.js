function chargerSimulationsPourComparaison() {
    ajax("GET", "/param", null, (res) => {
        if (res.error) {
            alert("Erreur lors du chargement des simulations : " + res.error);
            return;
        }

        const select1 = document.getElementById("choix1");
        const select2 = document.getElementById("choix2");

        res.forEach(sim => {
            const option1 = document.createElement("option");
            option1.value = sim.id_param_simulation;
            option1.text = sim.description ? `${sim.description}` : `Simulation ID ${sim.id_param_simulation}`;
            select1.appendChild(option1);

            const option2 = document.createElement("option");
            option2.value = sim.id_param_simulation;
            option2.text = sim.description ? `${sim.description}` : `Simulation ID ${sim.id_param_simulation}`;
            select2.appendChild(option2);
        });
        console.log('debug');
        
    });
}

chargerSimulationsPourComparaison();

function fetchSimulationById(id) {
    return new Promise((resolve, reject) => {
        ajax("GET", `/param/${id}`, null, (res) => {
            if (res.error) {
                reject(res.error);
            } else {
                resolve(res);
            }
        });
    });
}

function faireComparaison() {
    const id1 = document.getElementById("choix1").value;
    const id2 = document.getElementById("choix2").value;
    Promise.all([
        fetchSimulationById(id1),
        fetchSimulationById(id2)
    ])
        .then(([sim1, sim2]) => {
            afficherDeuxSimulationsCoteACote(sim1, sim2);
        })
        .catch((error) => {
            const container = document.getElementById("resultats-comparaison");
            container.innerHTML = `<p style="color:red;">Erreur lors de la récupération : ${error}</p>`;
        });
}

function afficherDeuxSimulationsCoteACote(sim1, sim2) {
    const container = document.getElementById("resultats-comparaison");
    container.innerHTML = ''; // reset

    // Générer pour chaque simulation
    genererSimulationHTML(sim1, (html1) => {
        const div1 = document.createElement("div");
        div1.innerHTML = `<h3>Simulation 1</h3>` + html1;
        container.appendChild(div1);
    });

    genererSimulationHTML(sim2, (html2) => {
        const div2 = document.createElement("div");
        div2.innerHTML = `<h3>Simulation 2</h3>` + html2;
        container.appendChild(div2);
    });
}

function genererSimulationHTML(data, callback) {
    const payload =
        `date_pret=${data.date_pret}&` +
        `montant=${data.montant}&` +
        `duree=${data.duree}&` +
        `delai=${data.delai}&` +
        `assurance=${data.assurance}&` +
        `taux=${data.taux}`;

    ajax("POST", "/simuler-pret", payload, (res) => {
        if (res.error) {
            callback(`<p style="color:red;">Erreur: ${res.error}</p>`);
            return;
        }

        let html = `
        <table border="1" cellpadding="5">
            <tr>
                <th>Mois</th><th>Date</th><th>Mensualité</th><th>Assurance</th>
                <th>Amortissement</th><th>Intérêt</th><th>A payer</th><th>Capital restant dû</th>
            </tr>`;

        res.paiements.forEach(p => {
            html += `<tr>
                <td>${p.mois}</td>
                <td>${p.date}</td>
                <td>${formatMontant(p.mensualite)}</td>
                <td>${formatMontant(p.assurance)}</td>
                <td>${formatMontant(p.amortissement)}</td>
                <td>${formatMontant(p.interet)}</td>
                <td>${formatMontant(p.total_a_payer)}</td>
                <td>${formatMontant(p.capital_restant)}</td>
            </tr>`;
        });

        html += `</table><br>
        <table border="1" cellpadding="5">
            <tr><th>Total</th><th>Capital</th><th>Intérets</th><th>Assurance</th></tr>
            <tr>
                <td>${formatMontant(res.totaux.total)}</td>
                <td>${formatMontant(res.totaux.capital)}</td>
                <td>${formatMontant(res.totaux.interet)}</td>
                <td>${formatMontant(res.totaux.assurance)}</td>
            </tr>
        </table>`;

        callback(html);
    });
}

function formatMontant(valeur) {
    return parseFloat(valeur).toLocaleString('fr-FR', { minimumFractionDigits: 0 }) + ' Ar';
}
