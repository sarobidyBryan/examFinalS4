// vs-data.js - Logique de comparaison des prêts

// Données des prêts à comparer
const pretA = {
    montant: 2000000,
    duree: 14,
    delai: 2,
    taux: 5,
    assurance: 2,
    mensualite: 171214.96,
    total: 2094579.56,
    paiements: [  // Extrait abrégé
        { mois: 1, date: "2025-07-09", mensualite: 0, assurance: 0, amortissement: 0, interet: 0, total_a_payer: 0, capital_restant: 2000000 },
        { mois: 2, date: "2025-08-09", mensualite: 0, assurance: 0, amortissement: 0, interet: 0, total_a_payer: 0, capital_restant: 2000000 },
        { mois: 3, date: "2025-09-09", mensualite: 171214.96, assurance: 3333.33, amortissement: 162881.63, interet: 8333.33, total_a_payer: 174548.3, capital_restant: 1837118.37 },
        // ... autres mois ...
    ]
};

const pretB = {
    montant: 1500000,
    duree: 12,
    delai: 0,
    taux: 6,
    assurance: 1,
    mensualite: 132000,
    total: 1620000,
    paiements: [
        { mois: 1, date: "2025-07-01", mensualite: 132000, assurance: 1250, amortissement: 125000, interet: 5750, total_a_payer: 133250, capital_restant: 1375000 },
        // ... autres mois ...
    ]
};

// Configuration des critères de comparaison
const comparatifs = [
    { label: " Montant demandé", key: "montant", comparer: "none", format: "currency" },
    { label: " Durée (mois)", key: "duree", comparer: "min", format: "number" },
    { label: " Délai de grâce", key: "delai", comparer: "min", format: "number" },
    { label: " Taux d'intérêt", key: "taux", comparer: "min", format: "percent" },
    { label: " Assurance", key: "assurance", comparer: "min", format: "percent" },
    { label: " Mensualité estimée", key: "mensualite", comparer: "min", format: "currency" },
    { label: " Total remboursé", key: "total", comparer: "min", format: "currency" }
];

/**
 * Formate une valeur selon le type spécifié
 * @param {number} value - La valeur à formater
 * @param {string} format - Le type de format (currency, percent, number)
 * @returns {string} La valeur formatée
 */
function formatValue(value, format) {
    switch (format) {
        case "currency":
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 0
            }).format(value);
        case "percent":
            return value + '%';
        case "number":
        default:
            return value.toLocaleString('fr-FR');
    }
}

/**
 * Détermine quelle classe CSS appliquer selon la comparaison
 * @param {number} valueA - Valeur du prêt A
 * @param {number} valueB - Valeur du prêt B
 * @param {string} compareType - Type de comparaison (min, max, none)
 * @returns {object} Objet contenant les classes pour A et B
 */
function getComparisonClasses(valueA, valueB, compareType) {
    let classA = "neutral", classB = "neutral";

    if (compareType === "min") {
        if (valueA < valueB) {
            classA = "better";
            classB = "worse";
        } else if (valueB < valueA) {
            classA = "worse";
            classB = "better";
        } else {
            classA = classB = "equal";
        }
    } else if (compareType === "max") {
        if (valueA > valueB) {
            classA = "better";
            classB = "worse";
        } else if (valueB > valueA) {
            classA = "worse";
            classB = "better";
        } else {
            classA = classB = "equal";
        }
    }

    return { classA, classB };
}

/**
 * Calcule le score de chaque prêt
 * @returns {object} Scores des prêts A et B
 */
function calculateScores() {
    let scoreA = 0;
    let scoreB = 0;

    comparatifs.forEach(({ key, comparer }) => {
        if (comparer !== "none") {
            const a = pretA[key];
            const b = pretB[key];
            
            if (comparer === "min") {
                if (a < b) scoreA++;
                else if (b < a) scoreB++;
            } else if (comparer === "max") {
                if (a > b) scoreA++;
                else if (b > a) scoreB++;
            }
        }
    });

    return { scoreA, scoreB };
}

/**
 * Affiche le résumé de la comparaison
 * @param {string} winner - Le gagnant ("A", "B" ou "égalité")
 * @param {number} winnerScore - Score du gagnant
 * @param {number} loserScore - Score du perdant
 */
function showSummary(winner, winnerScore, loserScore) {
    const summary = document.getElementById("summary");
    
    if (winner === "égalité") {
        summary.innerHTML = `
            <h3>Résultat : Égalité parfaite</h3>
            <p>Les deux prêts présentent des avantages équivalents. Votre choix peut se baser sur d'autres critères personnels comme la réputation de l'établissement ou la qualité du service client.</p>
        `;
    } else {
        const recommendations = getRecommendations(winner);
        summary.innerHTML = `
            <h3>Prêt ${winner} remporte la comparaison</h3>
            <p>Avec ${winnerScore} critères avantageux contre ${loserScore}, le Prêt ${winner} offre globalement de meilleures conditions.</p>
            
        `;
    }
}

/**
 * Génère des recommandations basées sur le prêt gagnant
 * @param {string} winner - Le prêt gagnant
 * @returns {string} Recommandations personnalisées
 */
function getRecommendations(winner) {
    const pret = winner === "A" ? pretA : pretB;
    const recommendations = [];

    if (pret.taux <= 5) {
        recommendations.push("taux d'intérêt compétitif");
    }
    if (pret.assurance <= 1.5) {
        recommendations.push("assurance avantageuse");
    }
    if (pret.delai === 0) {
        recommendations.push("pas de délai de grâce");
    }
    if (pret.duree <= 18) {
        recommendations.push("durée optimisée");
    }

    return recommendations.length > 0 ? recommendations.join(", ") : "conditions générales attractives";
}

/**
 * Ajoute des effets visuels d'animation
 * @param {HTMLElement} element - L'élément à animer
 * @param {number} delay - Délai d'animation
 */
function addAnimationEffects(element, delay) {
    element.style.animationDelay = `${delay * 0.1}s`;
    
    // Ajouter un effet de survol personnalisé
    element.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-3px) scale(1.02)';
    });
    
    element.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
}

/**
 * Fonction principale d'affichage du comparatif
 */
function afficherComparatif() {
    const container = document.getElementById("vs-rows");
    
    // Vérifier que le container existe
    if (!container) {
        console.error("Container 'vs-rows' non trouvé");
        return;
    }

    // Calculer les scores
    const { scoreA, scoreB } = calculateScores();

    // Générer les lignes de comparaison
    comparatifs.forEach(({ label, key, comparer, format }, index) => {
        const a = pretA[key];
        const b = pretB[key];
        
        // Déterminer les classes CSS
        const { classA, classB } = getComparisonClasses(a, b, comparer);

        // Créer la ligne
        const row = document.createElement("div");
        row.className = "vs-row";
        
        // Ajouter les effets d'animation
        addAnimationEffects(row, index);

        // Générer le contenu HTML
        row.innerHTML = `
            <div class="vs-label">${label}</div>
            <div class="vs-value ${classA}" data-value="${a}">${formatValue(a, format)}</div>
            <div class="vs-value ${classB}" data-value="${b}">${formatValue(b, format)}</div>
        `;

        container.appendChild(row);
    });

    // Afficher le gagnant avec un délai pour l'effet
    setTimeout(() => {
        displayWinner(scoreA, scoreB);
    }, 1000);
}

/**
 * Affiche le gagnant et met à jour l'interface
 * @param {number} scoreA - Score du prêt A
 * @param {number} scoreB - Score du prêt B
 */
function displayWinner(scoreA, scoreB) {
    const winnerAElement = document.getElementById("winner-a");
    const winnerBElement = document.getElementById("winner-b");
    
    if (scoreA > scoreB) {
        if (winnerAElement) winnerAElement.style.display = "block";
        showSummary("A", scoreA, scoreB);
    } else if (scoreB > scoreA) {
        if (winnerBElement) winnerBElement.style.display = "block";
        showSummary("B", scoreB, scoreA);
    } else {
        showSummary("égalité", scoreA, scoreB);
    }
}

/**
 * Initialise l'application au chargement de la page
 */
function initComparatif() {
    // Vérifier que tous les éléments nécessaires sont présents
    const requiredElements = ["vs-rows", "summary"];
    const missingElements = requiredElements.filter(id => !document.getElementById(id));
    
    if (missingElements.length > 0) {
        console.error("Éléments manquants:", missingElements);
        return;
    }

    // Lancer l'affichage du comparatif
    afficherComparatif();
    afficherDetailsPaiements();
}

// Initialiser l'application quand le DOM est prêt
document.addEventListener("DOMContentLoaded", initComparatif);

// Exporter les fonctions pour utilisation externe si nécessaire
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        pretA,
        pretB,
        comparatifs,
        formatValue,
        afficherComparatif,
        calculateScores
    };
}

function afficherDetailsPaiements() {
    const container = document.getElementById("details-comparaison-id");
    if (!container) {
        console.error("Élément 'details-comparaison-id' introuvable");
        return;
    }

    container.innerHTML = ''; // Réinitialiser

    const table = document.createElement("table");
    table.style.width = "100%";
    table.style.borderCollapse = "collapse";
    table.style.marginTop = "30px";
    table.style.fontSize = "14px";
    table.style.border = "1px solid #ddd";
    table.style.background = "#fff";
    table.style.boxShadow = "0 2px 6px rgba(0,0,0,0.05)";

    const thead = `
        <thead>
            <tr style="background:#f9f9f9;">
                <th style="padding:8px;border:1px solid #ddd;">Mois</th>
                <th style="padding:8px;border:1px solid #ddd;">Date</th>
                <th style="padding:8px;border:1px solid #ddd;">Mensualité<br><small>(A / B)</small></th>
                <th style="padding:8px;border:1px solid #ddd;">Amortissement<br><small>(A / B)</small></th>
                <th style="padding:8px;border:1px solid #ddd;">Intérêt<br><small>(A / B)</small></th>
                <th style="padding:8px;border:1px solid #ddd;">Assurance<br><small>(A / B)</small></th>
                <th style="padding:8px;border:1px solid #ddd;">Total à payer<br><small>(A / B)</small></th>
            </tr>
        </thead>
    `;

    const maxLignes = Math.max(pretA.paiements.length, pretB.paiements.length);
    let rows = '';

    for (let i = 0; i < maxLignes; i++) {
        const a = pretA.paiements[i] || {};
        const b = pretB.paiements[i] || {};
        rows += `
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:6px 8px;text-align:center;">${a.mois || b.mois || ''}</td>
                <td style="padding:6px 8px;text-align:center;">${a.date || b.date || ''}</td>
                <td style="padding:6px 8px;text-align:right;">${formatCurrency(a.mensualite)}<br><span style="color:#888;">${formatCurrency(b.mensualite)}</span></td>
                <td style="padding:6px 8px;text-align:right;">${formatCurrency(a.amortissement)}<br><span style="color:#888;">${formatCurrency(b.amortissement)}</span></td>
                <td style="padding:6px 8px;text-align:right;">${formatCurrency(a.interet)}<br><span style="color:#888;">${formatCurrency(b.interet)}</span></td>
                <td style="padding:6px 8px;text-align:right;">${formatCurrency(a.assurance)}<br><span style="color:#888;">${formatCurrency(b.assurance)}</span></td>
                <td style="padding:6px 8px;text-align:right;">${formatCurrency(a.total_a_payer)}<br><span style="color:#888;">${formatCurrency(b.total_a_payer)}</span></td>
            </tr>
        `;
    }

    table.innerHTML = thead + "<tbody>" + rows + "</tbody>";
    container.appendChild(table);
}


function formatCurrency(val) {
    if (val == null) return "-";
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MGA', minimumFractionDigits: 0 }).format(val);
}
function exporterComparaisonPDF() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", apiBase + "/export/comparaison-pdf", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.responseType = "blob"; // important pour fichier PDF

    xhr.onload = function () {
        if (xhr.status === 200) {
            const blob = xhr.response;
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "comparaison_prets.pdf";
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        } else {
            alert("Erreur lors de l'export PDF : code " + xhr.status);
        }
    };

    xhr.onerror = function () {
        alert("Erreur réseau ou serveur injoignable");
    };

    xhr.send(JSON.stringify({ pretA, pretB }));
}


