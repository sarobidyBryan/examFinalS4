<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des types de prêt</title>
  <style>
    body { font-family: sans-serif; padding: 20px; }
    input, button, select { margin: 5px; padding: 5px; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>

  <h1>Gestion des types de prêt</h1>

  <div>
    <!-- <input type="hidden" id="id_type_pret"> -->
    <input type="number" id="taux" placeholder="Taux (%)" step="0.01">
    <input type="number" id="duree_min" placeholder="Durée min (mois)">
    <input type="text" id="description" placeholder="Description">
    <input type="number" id="pret_min" placeholder="Montant min">
    <input type="number" id="pret_max" placeholder="Montant max">
    <select id="id_calcul_ti">
      <option value="">Sélectionner calcul taux intérêt</option>
    </select>
    <select id="id_def_annee">
      <option value="">Sélectionner définition année</option>
    </select>
    
    
    <button onclick="ajouterOuModifier()">Ajouter / Modifier</button>
    <button onclick="resetForm()">Annuler</button>
  </div>

  <table id="table-type-pret">
    <thead>
      <tr>
        <th>ID</th><th>Taux</th><th>Durée min</th><th>Description</th><th>Min</th><th>Max</th><th>ID calcul TI</th><th>ID def année</th><th>Actions</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
  <a href="index.html">Retour étudiants</a>

  <script>
    const apiBase = "http://localhost:80/examFinalS4/ws";

    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
          callback(JSON.parse(xhr.responseText));
        }
      };
      xhr.send(data);
    }
    function chargerSelects() {
      ajax("GET", "/calcul_taux_interet", null, (data) => {
        const select = document.getElementById("id_calcul_ti");
        let options = '<option value="">Sélectionner calcul taux intérêt</option>';
        data.forEach(item => {
          options += `<option value="${item.id_calcul_ti}">${item.description}</option>`;
        });
        select.innerHTML = options;
      });
      ajax("GET", "/definition_annee", null, (data) => {
        const select = document.getElementById("id_def_annee");
        let options = '<option value="">Sélectionner définition année</option>';
        data.forEach(item => {
          options += `<option value="${item.id_def_annee}">${item.nombre_jours} jours</option>`;
        });
        select.innerHTML = options;
      });
    }

    function chargerTypePret() {
      ajax("GET", "/type_pret", null, (data) => {
        const tbody = document.querySelector("#table-type-pret tbody");
        tbody.innerHTML = "";
        data.forEach(t => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${t.id_type_pret}</td>
            <td>${t.taux}</td>
            <td>${t.duree_min}</td>
            <td>${t.description}</td>
            <td>${t.pret_min}</td>
            <td>${t.pret_max}</td>
            <td>${t.id_calcul_ti}</td>
            <td>${t.id_def_annee}</td>
            <td>
              <button onclick='remplirFormulaire(${JSON.stringify(t)})'>✏️</button>
              <button onclick='supprimerTypePret(${t.id_type_pret})'>🗑️</button>
            </td>
          `;
          tbody.appendChild(tr);
        });
      });
    }

    function ajouterOuModifier() {
      const id = window.currentTypePretId || "";
      const taux = document.getElementById("taux").value;
      const duree_min = document.getElementById("duree_min").value;
      const description = document.getElementById("description").value;
      const pret_min = document.getElementById("pret_min").value;
      const pret_max = document.getElementById("pret_max").value;
      const id_calcul_ti = document.getElementById("id_calcul_ti").value;
      const id_def_annee = document.getElementById("id_def_annee").value;

      const data = `taux=${encodeURIComponent(taux)}&duree_min=${encodeURIComponent(duree_min)}&description=${encodeURIComponent(description)}&pret_min=${encodeURIComponent(pret_min)}&pret_max=${encodeURIComponent(pret_max)}&id_calcul_ti=${encodeURIComponent(id_calcul_ti)}&id_def_annee=${encodeURIComponent(id_def_annee)}`;

      if (id) {
        ajax("PUT", `/type_pret/${id}`, data, () => {
          resetForm();
          chargerTypePret();
        });
      } else {
        ajax("POST", "/type_pret", data, () => {
          resetForm();
          chargerTypePret();
        });
      }
    }

    function remplirFormulaire(t) {
      window.currentTypePretId = t.id_type_pret;
      document.getElementById("taux").value = t.taux;
      document.getElementById("duree_min").value = t.duree_min;
      document.getElementById("description").value = t.description;
      document.getElementById("pret_min").value = t.pret_min;
      document.getElementById("pret_max").value = t.pret_max;
      document.getElementById("id_calcul_ti").value = t.id_calcul_ti;
      document.getElementById("id_def_annee").value = t.id_def_annee;
    }

    function supprimerTypePret(id) {
      if (confirm("Supprimer ce type de prêt ?")) {
        ajax("DELETE", `/type_pret/${id}`, null, () => {
          chargerTypePret();
        });
      }
    }

    function resetForm() {
      window.currentTypePretId = "";
      document.getElementById("taux").value = "";
      document.getElementById("duree_min").value = "";
      document.getElementById("description").value = "";
      document.getElementById("pret_min").value = "";
      document.getElementById("pret_max").value = "";
      document.getElementById("id_calcul_ti").value = "";
      document.getElementById("id_def_annee").value = "";
    }

    chargerTypePret();
    chargerSelects();
  </script>

</body>
</html>
