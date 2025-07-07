function ajoutMontant() {
    const montant = document.getElementById("montant").value;

    const data = `montant=${encodeURIComponent(montant)}`;
    
    ajax("POST", "/fond", data, (response) => {
        resetForm();
        if (response.success) {
            document.getElementById("message").className = "success";
            document.getElementById("message").textContent = response.message;
            remplirSolde();
        } else {
            document.getElementById("message").className = "error";
            document.getElementById("message").textContent = response.error;
        }
    });
}

function resetForm() {
    document.getElementById("montant").value = "";
}

function remplirSolde() {
    ajax("GET", "/fond/solde", "", (response) => {
        if (response.success) {
            document.getElementById("solde").textContent = Number(response.solde).toLocaleString('fr-FR').replace(/\u00A0/g, ' ') + " Ar";
        } else {
            document.getElementById("message").className = "error";
            document.getElementById("message").textContent = response.error;
        }
    });
}

remplirSolde();