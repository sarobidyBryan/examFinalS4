<h2>Créer un pret</h2>
<div id="message"></div>

<form onsubmit="simulerPaiement(event)">
  <select id="id_compte_client" required>
    <option value="">-- Compte client --</option>
  </select><br>

  <select id="id_type_pret" required>
    <option value="">-- Type de pret --</option>
  </select><br>

  <input type="date" id="date_pret" required><br>
  <input type="number" id="montant" placeholder="Montant du pret" step="0.01" required><br>
  <input type="number" id="duree" placeholder="Durée (mois)" required><br>
  <input type="number" id="delai" min="0" placeholder="Délai (mois)" required><br>
  <input type="number" id="assurance" max="100" min="0" placeholder="Assurance (%)" required><br>

  <button type="submit">Simuler le pret</button>
</form>

<div id="simulation-resultat"></div>
<div id="bouton-valider" style="display:none;">
  <button onclick="validerPret()">Valider le pret</button>
</div>

<script src="ws/assets/js/base.js"></script>

<script>
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

  let simulationResult = null; // Stockage sécurisé

  function simulerPaiement(e) {
    e.preventDefault();

    const data = new URLSearchParams({
      date_pret: document.getElementById("date_pret").value,
      montant: document.getElementById("montant").value,
      duree: document.getElementById("duree").value,
      delai: document.getElementById("delai").value,
      assurance: document.getElementById("assurance").value,
      taux: document.getElementById("id_type_pret").selectedOptions[0].dataset.taux
    });

    ajax("POST", "/simuler-pret", data.toString(), function(res) {
      const div = document.getElementById("simulation-resultat");
      if (res.error) {
        div.innerHTML = `<p style=\"color:red\">${res.error}</p>`;
        document.getElementById("bouton-valider").style.display = "none";
        simulationResult = null;
        return;
      }
      simulationResult = res;
      let html = `
      <table id=\"simulation-table\" border=\"1\" cellpadding=\"5\">
        <tr>
          <th>Mois</th><th>Date</th><th>Mensualité</th><th>Assurance</th>
          <th>Amortissement</th><th>Intéret</th><th>A payer</th><th>Capital restant dû</th>
        </tr>`;
      res.paiements.forEach(function(p) {
        html += `<tr>
        <td>${p.mois}</td><td>${p.date}</td><td>${p.mensualite}</td>
        <td>${p.assurance}</td><td>${p.amortissement}</td>
        <td>${p.interet}</td><td>${p.total_a_payer}</td>
        <td>${p.capital_restant}</td>
      </tr>`;
      });
      html += `</table>`;
      html += `<table id=\"totaux-table\" border=\"1\" cellpadding=\"5\">
      <tr><th>Total</th><th>Capital</th><th>Intérets</th><th>Assurance</th></tr>
      <tr>
        <td>${res.totaux.total}</td>
        <td>${res.totaux.capital}</td>
        <td>${res.totaux.interet}</td>
        <td>${res.totaux.assurance}</td>
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

    

    function validerPret() {
      const dataPret = new URLSearchParams({
        id_compte_client: document.getElementById("id_compte_client").value,
        id_type_pret: document.getElementById("id_type_pret").value,
        date_pret: document.getElementById("date_pret").value,
        montant: document.getElementById("montant").value,
        duree: document.getElementById("duree").value,
        delai: document.getElementById("delai").value,
        assurance: document.getElementById("assurance").value
      });

      ajax("POST", "/prets", dataPret.toString(), (res) => {
        if (res.error) {
          document.getElementById("message").innerHTML = `<p style="color:red">${res.error}</p>`;
          return;
        }

        const id_pret = res.id;
        console.log("Pret créé avec ID :", id_pret);
        // Utiliser les données de simulation stockées
        simulationResult.paiements.forEach(p => {
          const remboursement = new URLSearchParams({
            date_remboursement: p.date,
            montant_paye_base: p.amortissement,
            montant_paye_interet: p.interet,
            montant_restant: p.capital_restant,
            assurance: p.assurance,
            id_pret: id_pret
          });

          ajax("POST", "/remboursement_pret", remboursement.toString(), () => {});
        });

        document.getElementById("message").innerHTML = `<p style="color:green">${res.message}. Remboursements enregistrés.</p>`;
      });
    }
  }
</script>