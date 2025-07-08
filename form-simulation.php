<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="ws/assets/css/form-simulation.css">
    <?php include('inc/head.php') ?>
</head>

<body>
    <?php include('inc/navigation.php') ?>
    <div class="main-content">
        <h2>Ajout d'une simulation pour la comparaison</h2>
        <div id="message"></div>
        <label for="description">Description : </label>
        <input type="text" id="description" placeholder="Ex: prêt de 10 mois" required>

        <label for="id_compte_client">Compte client</label>
        <select id="id_compte_client" required>
            <option value="">-- Compte client --</option>
        </select>

        <label for="id_type_pret">Type de prêt</label>
        <select id="id_type_pret" required>
            <option value="">-- Type de prêt --</option>
        </select>

        <label for="date_pret">Date du prêt</label>
        <input type="date" id="date_pret" required>

        <label for="montant">Montant du prêt</label>
        <input type="number" id="montant" placeholder="Ex: 10000" step="0.01" required>

        <label for="duree">Durée (mois)</label>
        <input type="number" id="duree" placeholder="Ex: 24" required>

        <label for="delai">Délai (mois)</label>
        <input type="number" id="delai" min="0" placeholder="Ex: 3" required>

        <label for="assurance">Assurance (%)</label>
        <input type="number" id="assurance" max="100" min="0" placeholder="Ex: 2" required>

        <button onclick="ajouterSimulation()">Simuler le prêt</button>
        <div id="message"></div>
    </div>
    </div>

    <script src="ws/assets/js/base.js"></script>
    <script src="ws/assets/js/form-simulation.js"></script>


</body>

</html>