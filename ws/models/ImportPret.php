<?php

class ImportPret {
    public static function processCSV($db, $file) {
        $handle = fopen($file, 'r');
        if (!$handle) return ['error' => 'Impossible d’ouvrir le fichier.'];

        $entete = fgetcsv($handle, 1000, ','); // sauter l'en-tête
        $reponses = [];
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            [$id_compte_client, $id_type_pret, $date_pret, $montant, $duree, $delai, $assurance] = $row;

            $dataPret = (object)[
                'id_compte_client' => $id_compte_client,
                'id_type_pret' => $id_type_pret,
                'date_pret' => $date_pret,
                'montant' => $montant,
                'duree' => $duree,
                'delai' => $delai,
                'assurance' => $assurance
            ];

            // 1. Création du prêt
            $result = Pret::create($dataPret);
            if (isset($result['error'])) {
                $reponses[] = ['ligne' => $row, 'error' => $result['error']];
                continue;
            }

            $idPret = $result['id'];

            // 2. Récupérer les infos prêt pour recalculer remboursements
            $stmt = $db->prepare("SELECT p.*, t.taux FROM ef_pret p JOIN ef_type_pret t ON p.id_type_pret = t.id_type_pret WHERE p.id_pret = ?");
            $stmt->execute([$idPret]);
            $pret = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pret) {
                $reponses[] = ['ligne' => $row, 'error' => 'Prêt introuvable après insertion.'];
                continue;
            }

            $tauxMensuel = ((float)$pret['taux']) / 100 / 12;
            $montant = $pret['montant'];
            $duree = $pret['duree'];
            $delai = $pret['delai'];
            $assuranceTotale = $montant * $pret['assurance'] / 100;
            $assuranceMensuelle = round($assuranceTotale / $duree, 2);
            $mensualite = ($montant * $tauxMensuel) / (1 - pow(1 + $tauxMensuel, -($duree - $delai)));
            $mensualite = round($mensualite, 2);
            $reste = $montant;
            $date = new DateTime($pret['date_pret']);

            for ($i = 1; $i <= $duree; $i++) {
                $date->modify('+1 month');
                $strDate = $date->format('Y-m-d');

                $interet = round($reste * $tauxMensuel, 2);
                $amortissement = ($i <= $delai) ? 0 : round($mensualite - $interet, 2);
                $reste = round($reste - $amortissement, 2);
                if ($reste < 0) $reste = 0;

                RemboursementPret::create($db, (object)[
                    'date_remboursement' => $strDate,
                    'montant_paye_base' => $amortissement,
                    'montant_paye_interet' => $interet,
                    'montant_restant' => $reste,
                    'assurance' => $assuranceMensuelle,
                    'id_pret' => $idPret
                ]);

                MouvementBanque::create($db, [
                    'date_mouvement' => $strDate,
                    'montant' => $amortissement + $interet + $assuranceMensuelle,
                    'id_compte_banque' => 1,
                    'id_type_mouvement' => 2
                ]);
            }

            $reponses[] = ['ligne' => $row, 'message' => 'Prêt et remboursements créés'];
        }

        fclose($handle);
        return $reponses;
    }
}
