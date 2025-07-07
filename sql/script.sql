CREATE database etablissement_financier;
USE etablissement_financier;

-- 1. Client
CREATE TABLE ef_client (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50),
    prenom VARCHAR(50)
);

-- 2. Compte client
CREATE TABLE ef_compte_client (
    id_compte_client INT AUTO_INCREMENT PRIMARY KEY,
    solde_actuel DECIMAL(15,2),
    solde_precedent DECIMAL(15,2),
    id_client INT,
    FOREIGN KEY (id_client) REFERENCES ef_client(id_client)
);

-- 3. Statut prêt
CREATE TABLE ef_status_pret (
    id_status_pret INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(50)
);

-- 4. Banque
CREATE TABLE ef_banque (
    id_banque INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50)
);

-- 5. Compte banque
CREATE TABLE ef_compte_banque (
    id_compte_banque INT AUTO_INCREMENT PRIMARY KEY,
    solde_actuel DECIMAL(15,2),
    solde_precedent DECIMAL(15,2),
    id_banque INT,
    FOREIGN KEY (id_banque) REFERENCES ef_banque(id_banque)
);

-- 6. Type de mouvement
CREATE TABLE ef_type_mouvement (
    id_type_mouvement INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(50)
);


-- 12. Mouvement banque
CREATE TABLE ef_mouvement_banque (
    id_mouvement_banque INT AUTO_INCREMENT PRIMARY KEY,
    date_mouvement DATE,
    montant DECIMAL(15,2),
    id_compte_banque INT,
    id_type_mouvement INT,
    FOREIGN KEY (id_compte_banque) REFERENCES ef_compte_banque(id_compte_banque),
    FOREIGN KEY (id_type_mouvement) REFERENCES ef_type_mouvement(id_type_mouvement)
);


-- 7. Calcul taux d’intérêt
CREATE TABLE ef_calcul_taux_interet (
    id_calcul_ti INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(50)
);

-- 8. Définition année
CREATE TABLE ef_definition_annee (
    id_def_annee INT AUTO_INCREMENT PRIMARY KEY,
    nombre_jours INT
);

-- 9. Type de prêt
CREATE TABLE ef_type_pret (
    id_type_pret INT AUTO_INCREMENT PRIMARY KEY,
    taux DOUBLE,
    duree_min INT,
    description VARCHAR(50),
    pret_min INT,
    pret_max INT,
    id_calcul_ti INT,
    id_def_annee INT,
    FOREIGN KEY (id_calcul_ti) REFERENCES ef_calcul_taux_interet(id_calcul_ti),
    FOREIGN KEY (id_def_annee) REFERENCES ef_definition_annee(id_def_annee)
);

-- 10. Prêt
CREATE TABLE ef_pret (
    id_pret INT AUTO_INCREMENT PRIMARY KEY,
    date_pret DATE,
    montant DECIMAL(15,2),
    duree INT,
    id_type_pret INT,
    id_compte_client INT,
    delai int,
    assurance int,
    FOREIGN KEY (id_type_pret) REFERENCES ef_type_pret(id_type_pret),
    FOREIGN KEY (id_compte_client) REFERENCES ef_compte_client(id_compte_client)
);

-- 11. Mouvement client
CREATE TABLE ef_mouvement_client (
    id_mouvement_client INT AUTO_INCREMENT PRIMARY KEY,
    date_mouvement DATE,
    montant DECIMAL(15,2),
    id_compte_client INT,
    id_type_mouvement INT,
    FOREIGN KEY (id_compte_client) REFERENCES ef_compte_client(id_compte_client),
    FOREIGN KEY (id_type_mouvement) REFERENCES ef_type_mouvement(id_type_mouvement)
);

-- 13. Remboursement prêt
CREATE TABLE ef_remboursement_pret (
    id_remboursement_pret INT AUTO_INCREMENT PRIMARY KEY,
    date_remboursement DATE,
    montant_paye_base DECIMAL(15,2),
    montant_paye_interet DECIMAL(15,2),
    montant_restant DECIMAL(15,2),
    assurance DECIMAL(15,2),
    id_pret INT,
    FOREIGN KEY (id_pret) REFERENCES ef_pret(id_pret)
);

-- 14. Historique statut prêt
CREATE TABLE ef_historique_status_pret (
    id_status_pret INT,
    id_pret INT,
    date_changement DATE,
    PRIMARY KEY (id_status_pret, id_pret, date_changement),
    FOREIGN KEY (id_status_pret) REFERENCES ef_status_pret(id_status_pret),
    FOREIGN KEY (id_pret) REFERENCES ef_pret(id_pret)
);
