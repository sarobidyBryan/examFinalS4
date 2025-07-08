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


document.getElementById("form-solde").addEventListener("submit", function (e) {
  e.preventDefault();
  const date = document.getElementById("date_solde").value;
  const id_banque=1;
  if (!date) {
    document.getElementById("message").innerHTML = `<span style="color:red;">Veuillez choisir une date.</span>`;
    return;
  }

  ajax("GET", `/solde-banque/${id_banque}/${date}`, null, (response) => {
    if (response.error) {
      document.getElementById("solde").textContent = "-";
      document.getElementById("message").innerHTML = `<span style="color:red;">${response.error}</span>`;
    } else {
      document.getElementById("solde").textContent =
        Number(response.solde).toLocaleString('fr-FR').replace(/\u00A0/g, ' ') + " Ar";
      document.getElementById("message").innerHTML = "";
    }
  });
});
