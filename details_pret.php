<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Prêt</title>
</head>
<body>
    <h1>Détails du Prêt</h1>
    <div id="pretDetails"></div>

    <script src="ws/assets/js/base.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');

        function fetchPretDetails(id) {
            ajax('GET', `/prets/${id}`, null, function(data) {
                if (data.error) {
                    document.getElementById('pretDetails').innerText = data.error;
                    return;
                }
                // Fetch type_pret label if not present
                if (!data.type_pret && data.id_type_pret) {
                    ajax('GET', `/type_pret`, null, function(types) {
                        let typeLabel = data.id_type_pret;
                        if (Array.isArray(types)) {
                            const found = types.find(t => t.id_type_pret == data.id_type_pret);
                            if (found) typeLabel = `${found.description} (${found.taux}% de ${found.pret_min} à ${found.pret_max})`;
                        }
                        renderPretDetails(data, typeLabel);
                    });
                } else {
                    renderPretDetails(data, data.type_pret || data.id_type_pret || '');
                }
            });
        }

        function renderPretDetails(data, typeLabel) {
            document.getElementById('pretDetails').innerHTML = `
                <p><strong>ID:</strong> ${data.id_pret}</p>
                <p><strong>Date de prêt:</strong> ${data.date_pret}</p>
                <p><strong>Montant:</strong> ${data.montant}</p>
                <p><strong>Type de prêt:</strong> ${typeLabel}</p>
                <p><strong>Durée:</strong> ${data.duree} mois</p>
                <p><strong>Assurance:</strong> ${data.assurance ? data.assurance + '%' : ''}</p>
                <p><strong>Délai:</strong> ${data.delai ? data.delai + ' mois' : ''}</p>
                <p><strong>ID Compte Client:</strong> ${data.id_compte_client}</p>
            `;
        }

        if (id) fetchPretDetails(id);
    </script>
</body>
</html>