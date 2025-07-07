<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un Fond</title>
    <link rel="stylesheet" href="ws/assets/css/ajout-fond.css">
    <?php include('inc/head.php') ?>
</head>

<body>
    <?php include('inc/navigation.php') ?>
    <div class="main-content">
        <h2>Ajouter un Fond</h2>

        <label for="montant">Montant :</label>
        <input type="number" id="montant" name="montant" step="0.01" min="0" required placeholder="Entrez un montant">

        <button onclick="ajoutMontant()" class="btn-ajouter">
            <i class="fa fa-plus"></i> Ajouter
        </button>

        <p id="message" class="message"></p>
    </div>


    <script>
        const apiBase = "http://localhost:90/examFinalS4/ws";

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
    </script>
</body>

</html>