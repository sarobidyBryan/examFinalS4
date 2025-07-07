<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Gestion des types de prêt</title>
  <link rel="stylesheet" href="ws/assets/css/typePret.css">
  <?php include('inc/head.php') ?>
</head>

<body>
  <?php include('inc/navigation.php') ?>
  <div class="main-content">

    <h1>Gestion des types de prêt</h1>

    <div>
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
      <button onclick="resetForm()" id="reset-btn">Annuler</button>
    </div>

    <table id="table-type-pret">
      <thead>
        <tr>
          <th>ID</th>
          <th>Taux</th>
          <th>Durée min</th>
          <th>Description</th>
          <th>Min</th>
          <th>Max</th>
          <th>ID calcul TI</th>
          <th>ID def année</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
  <script src="ws/assets/js/base.js"></script>
  <script src="ws/assets/js/typePret.js"></script>
</body>

</html>