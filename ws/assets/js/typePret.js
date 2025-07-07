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