insert into ef_banque (nom) values ('admin');

INSERT INTO ef_type_mouvement (description) VALUES 
('debit'),
('credit');

insert into ef_compte_banque (solde_actuel,solde_precedent,id_banque) values (0,0,1);

INSERT INTO ef_status_pret (description) VALUES 
('en cours'),
('rembourse'),
('en retard');


INSERT INTO ef_calcul_taux_interet (description) VALUES 
('interet simple'),
('interet compose');


INSERT INTO ef_definition_annee (nombre_jours) VALUES 
(360),   
(365);   


INSERT INTO ef_type_pret (taux, duree_min, description, pret_min, pret_max, id_calcul_ti, id_def_annee) VALUES 
(5.0, 6, 'credit consommation', 100000, 1000000, 1, 1),
(6.5, 12, 'credit auto',         500000, 5000000, 1, 1),
(4.5, 60, 'pret immobilier',     5000000, 50000000, 2, 2),
(3.0, 12, 'pret etudiant',       200000, 2000000, 1, 1);

insert into ef_banque(nom) values ('admin');
insert into ef_compte_banque(solde_actuel,solde_precedent,id_banque) values (0,0,1);


INSERT INTO ef_client (nom, prenom) VALUES 
('Rakoto', 'Jean'),
('Rasoa', 'Marie');


INSERT INTO ef_compte_client (solde_actuel, solde_precedent, id_client) VALUES 
(500000.00, 600000.00, 1),
(300000.00, 300000.00, 2);



INSERT INTO ef_definition_annee (nombre_jours) VALUES 
(360), (365);
