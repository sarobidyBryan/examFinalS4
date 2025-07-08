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

function ajouterSimulation() {
    const data =
        `date_pret=${document.getElementById("date_pret").value}&` +
        `montant=${document.getElementById("montant").value}&` +
        `duree=${document.getElementById("duree").value}&` +
        `delai=${document.getElementById("delai").value}&` +
        `assurance=${document.getElementById("assurance").value}&` +
        `taux=${document.getElementById("id_type_pret").selectedOptions[0].dataset.taux}`;
    ajax("POST", "/pret/ajout-simulation", data, (res) => {
        const msgDiv = document.getElementById("message");

        if (res.error) {
            msgDiv.innerHTML = `<p style="color:red;"><strong>Erreur :</strong> ${res.error}</p>`;
        } else if (res.success) {
            msgDiv.innerHTML = `<p style="color:green;"><strong>✔</strong> ${res.message}</p>`;
            // tu peux aussi faire autre chose avec res.id ici, ex: rediriger ou afficher plus
        } else {
            msgDiv.innerHTML = `<p style="color:orange;">Une réponse inattendue a été reçue.</p>`;
        }
    });
}