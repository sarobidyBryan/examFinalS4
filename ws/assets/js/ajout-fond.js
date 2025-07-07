function ajoutMontant() {
    const montant = document.getElementById("montant").value;

    const data = `montant=${encodeURIComponent(montant)}`;


    // Correction de l'URL pour inclure le bon chemin relatif
    ajax("POST", "/fond", data, (response) => {
        resetForm();
        if (response.success) {
            document.getElementById("message").className = "success";
            document.getElementById("message").textContent = response.message;
        } else {
            document.getElementById("message").className = "error";
            document.getElementById("message").textContent = response.error;
        }
    });
}

function remplirFormulaire(e) {
    document.getElementById("montant").value = e.montant;
}

function resetForm() {
    document.getElementById("montant").value = "";
}