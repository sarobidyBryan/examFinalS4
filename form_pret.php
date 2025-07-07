<!-- pret.html -->
<h2>Creer un pret</h2>
<div id="message"></div>


<form onsubmit="creerPret(event)">
  <select id="id_compte_client" required>
    <option value="">-- Compte client --</option>
  </select><br>

  <select id="id_type_pret" required>
    <option value="">-- Type de prêt --</option>
  </select><br>

  <input type="date" id="date_pret" required><br>
  <input type="number" id="montant" placeholder="Montant du prêt" step="0.01" required><br>
  <input type="number" id="duree" placeholder="Durée (mois)" required><br>
  <input type="number" id="delai" min="0" placeholder="Delai (mois)" required><br>
  <input type="number" id="assurance" max="100" min="0" placeholder="Taux(%)" required><br>

  <button type="submit">Créer le prêt</button>
</form>

<script src="ws\assets\js\base.js"></script>
<script>
function creerPret(e) {
  e.preventDefault();
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
    msgDiv.innerHTML = res.error 
      ? `<p style="color:red">${res.error}</p>` 
      : `<p style="color:green">${res.message}</p>`;
  });
}

function chargerDropdowns() {
  // Clients
  ajax("GET", "/comptes-clients", null, (data) => {
    const select = document.getElementById("id_compte_client");
    data.forEach(c => {
      const option = document.createElement("option");
      option.value = c.id_compte_client;
      option.text = `${c.id_compte_client} - ${c.nom} ${c.prenom}`;
      select.appendChild(option);
    });
  });

  // Types de prêt
  ajax("GET", "/type_pret", null, (data) => {
    const select = document.getElementById("id_type_pret");
    data.forEach(t => {
      const option = document.createElement("option");
      option.value = t.id_type_pret;
      option.text = `${t.description} (${t.taux}% de ${t.pret_min} à ${t.pret_max})`;
      select.appendChild(option);
    });
  });
}

chargerDropdowns();
</script>



