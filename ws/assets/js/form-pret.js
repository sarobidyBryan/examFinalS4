function chargerDropdowns() {
    ajax("GET", "/comptes-clients", null, (data) => {
        const select = document.getElementById("id_compte_client");
        data.forEach(c => {
            const option = document.createElement("option");
            option.value = c.id_compte_client;
            option.text = `${c.id_compte_client} - ${c.nom} ${c.prenom}`;
            select.appendChild(option);
        });
    });

    ajax("GET", "/type_pret", null, (data) => {
        const select = document.getElementById("id_type_pret");
        data.forEach(t => {
            const option = document.createElement("option");
            option.value = t.id_type_pret;
            option.text = `${t.description} (${t.taux}% de ${t.pret_min} à ${t.pret_max})`;
            option.dataset.taux = t.taux;
            select.appendChild(option);
        });
    });
}
chargerDropdowns();

function simulerPaiement(e) {
    e.preventDefault();
    const data =
        `date_pret=${document.getElementById("date_pret").value}&` +
        `montant=${document.getElementById("montant").value}&` +
        `duree=${document.getElementById("duree").value}&` +
        `delai=${document.getElementById("delai").value}&` +
        `assurance=${document.getElementById("assurance").value}&` +
        `taux=${document.getElementById("id_type_pret").selectedOptions[0].dataset.taux}`;

    ajax("POST", "/simuler-pret", data, (res) => {
        const div = document.getElementById("simulation-resultat");
        if (res.error) {
            div.innerHTML = `<p style="color:red">${res.error}</p>`;
            document.getElementById("bouton-valider").style.display = "none";
            return;
        }

        let html = `
      <table>
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
        html += `</table>`;
        html += `<table id=\"totaux-table\" border=\"1\" cellpadding=\"5\">
      <tr><th>Total</th><th>Capital</th><th>Intérets</th><th>Assurance</th></tr>
      <tr>
        <td>${formatMontant(res.totaux.total)}</td>
        <td>${formatMontant(res.totaux.capital)}</td>
        <td>${formatMontant(res.totaux.interet)}</td>
        <td>${formatMontant(res.totaux.assurance)}</td>
      </tr>
    </table>`;
        div.innerHTML = html;
        document.getElementById("bouton-valider").style.display = "block";
        // PDF export button (must use the latest res)
        let oldExport = document.getElementById('export-sim-pdf');
        if (oldExport) oldExport.remove();
        const exportForm = document.createElement('form');
        exportForm.method = 'post';
        exportForm.action = 'export_simulation_pdf.php';
        exportForm.target = '_blank';
        exportForm.id = 'export-sim-pdf';
        exportForm.style.marginTop = '10px';
        exportForm.innerHTML = `
          <input type=\"hidden\" name=\"paiements\" value='${JSON.stringify(res.paiements)}'>
          <input type=\"hidden\" name=\"totaux\" value='${JSON.stringify(res.totaux)}'>
          <button type=\"submit\">Exporter la simulation en PDF</button>
        `;
        div.appendChild(exportForm);
    });
}

function validerPret() {
    const data =
        `id_compte_client=${document.getElementById("id_compte_client").value}&` +
        `id_type_pret=${document.getElementById("id_type_pret").value}&` +
        `date_pret=${document.getElementById("date_pret").value}&` +
        `montant=${document.getElementById("montant").value}&` +
        `duree=${document.getElementById("duree").value}&` +
        `delai=${document.getElementById("delai").value}&` +
        `assurance=${document.getElementById("assurance").value}`;

    ajax("POST", "/prets", data, (res) => {
        const msgDiv = document.getElementById("message");
        if (res.error) {
            msgDiv.innerHTML = `<p style="color:red">${res.error}</p>`;
        } else {
            msgDiv.innerHTML = `<p style="color:green">${res.message}</p>`;
            const idPret = res.id;
            console.log("idpret   " + idPret)
            rembourserPret(idPret);  // appel ici
        }
    });
}

// 🔁 Cette fonction lit les lignes déjà affichées dans le tableau et les envoie
function rembourserPret(idPret) {
    const data = { id_pret: idPret };
    console.log("idddd  " + data.id_pret);
    ajax("POST", "/remboursement_pret/create", JSON.stringify(data), (res) => {
        const msgDiv = document.getElementById("message");
        msgDiv.innerHTML += res.error
            ? `<p style="color:red">${res.error}</p>`
            : `<p style="color:green">${res.message}</p>`;
    }, true); // true = JSON
}
