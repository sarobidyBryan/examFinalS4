<h2>Créer un prêt</h2>
<div id="message"></div>

<form onsubmit="simulerPaiement(event)">
  <select id="id_compte_client" required>
    <option value="">-- Compte client --</option>
  </select><br>

  <select id="id_type_pret" required>
    <option value="">-- Type de prêt --</option>
  </select><br>

  <input type="date" id="date_pret" required><br>
  <input type="number" id="montant" placeholder="Montant du prêt" step="0.01" required><br>
  <input type="number" id="duree" placeholder="Durée (mois)" required><br>
  <input type="number" id="delai" min="0" placeholder="Délai (mois)" required><br>
  <input type="number" id="assurance" max="100" min="0" placeholder="Assurance (%)" required><br>

  <button type="submit">Simuler le prêt</button>
</form>

<div id="simulation-resultat"></div>
<div id="bouton-valider" style="display:none;">
  <button onclick="validerPret()">Valider le prêt</button>
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
      <table border="1" cellpadding="5">
        <tr>
          <th>Mois</th><th>Date</th><th>Mensualité</th><th>Assurance</th>
          <th>Amortissement</th><th>Intérêt</th><th>A payer</th><th>Capital restant dû</th>
        </tr>`;

    res.paiements.forEach(p => {
      html += `<tr>
        <td>${p.mois}</td><td>${p.date}</td><td>${p.mensualite}</td>
        <td>${p.assurance}</td><td>${p.amortissement}</td>
        <td>${p.interet}</td><td>${p.total_a_payer}</td>
        <td>${p.capital_restant}</td>
      </tr>`;
    });

    html += `</table><br>
      <p><strong>Total capital :</strong> ${res.totaux.capital}</p>
      <p><strong>Total intérêts :</strong> ${res.totaux.interet}</p>
      <p><strong>Total assurance :</strong> ${res.totaux.assurance}</p>
      <p><strong>Total général :</strong> ${res.totaux.total}</p>`;

    div.innerHTML = html;
    document.getElementById("bouton-valider").style.display = "block";
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
  ajax("POST", "/remboursement_pret", JSON.stringify(data), (res) => {
    const msgDiv = document.getElementById("message");
    msgDiv.innerHTML += res.error 
      ? `<p style="color:red">${res.error}</p>` 
      : `<p style="color:green">${res.message}</p>`;
  }, true); // true = JSON
}

</script>
