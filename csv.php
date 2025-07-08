<h2>Importer plusieurs prêts (CSV)</h2>

<form id="form-import-prets" method="post" enctype="multipart/form-data">
  <input type="file" name="csv" accept=".csv" required>
  <button type="submit">Importer</button>
</form>

<div id="import-resultat"></div>

<script>
const apiBaseee = "http://localhost/L2/examFinalS4/ws";

function ajaxx(method, url, data, callback, isFormData = false) {
  const xhr = new XMLHttpRequest();
  xhr.open(method, apiBaseee + url, true);

  if (!isFormData) {
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  }

  xhr.onreadystatechange = () => {
    if (xhr.readyState === 4) {
      const div = document.getElementById("import-resultat");
      try {
        const json = JSON.parse(xhr.responseText);
        if (xhr.status === 200) {
          callback(json);
        } else {
          div.innerHTML = `<p style="color:red;">Erreur serveur : ${json.error || 'Inconnue'}</p>`;
        }
      } catch (e) {
        div.innerHTML = `<p style="color:red;">Erreur de parsing JSON</p>`;
      }
    }
  };

  xhr.send(data);
}


document.getElementById('form-import-prets').addEventListener('submit', function (e) {
  e.preventDefault();
  const form = document.getElementById('form-import-prets');
  const formData = new FormData(form);

  ajaxx("POST", "/import-prets", formData, function (res) {
    const div = document.getElementById("import-resultat");

    if (Array.isArray(res)) {
      let html = '<h3>Résultat de l\'import :</h3><ul>';
      res.forEach((ligne, i) => {
        if (ligne.error) {
          html += `<li style="color:red;">[Ligne ${i+2}] Erreur : ${ligne.error}</li>`;
        } else {
          html += `<li style="color:green;">[Ligne ${i+2}] ${ligne.message}</li>`;
        }
      });
      html += '</ul>';
      div.innerHTML = html;
    } else if (res.error) {
      div.innerHTML = `<p style="color:red;">Erreur : ${res.error}</p>`;
    } else {
      div.innerHTML = `<p>Import terminé avec succès.</p>`;
    }
  }, true); // ← true indique que c'est du FormData
});
</script>
