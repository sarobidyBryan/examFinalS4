<link rel="stylesheet" href="ws/assets/css/vs-comparaison.css">
<script defer src="ws/assets/js/base.js"></script>
<div class="comparison-container">
    <button onclick="exporterComparaisonPDF()">Exporter en PDF</button>
    <div class="vs-container">
        <div class="vs-icon">VS</div>

        <div class="vs-header">
            <div class="vs-label-header">Critères de comparaison</div>
            <div class="vs-pret-header vs-pret-a">
                Prêt A
            </div>
            <div class="vs-pret-header vs-pret-b">
                Prêt B
            </div>
        </div>

        <div class="vs-rows" id="vs-rows"></div>
    </div>

    <div class="summary" id="summary">
       
    </div>
     <div id="details-comparaison-id" style="margin-top: 30px;"></div>
</div>

<script defer src="ws/assets/js/vs-data.js"></script>