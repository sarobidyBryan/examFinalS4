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

// Fonction pour formater les montants avec séparateur et suffixe
function formatMontant(montant) {
  return Number(montant).toLocaleString('fr-FR') + ' Ar';
}