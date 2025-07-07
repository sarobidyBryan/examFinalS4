INSERT INTO banque_status_pret (description) VALUES 
('en cours'),
('rembourse'),
('en retard');


INSERT INTO banque_type_mouvement (description) VALUES 
('debit'),
('credit');


INSERT INTO banque_calcul_taux_interet (description) VALUES 
('interet simple'),
('interet compose');


INSERT INTO banque_definition_annee (nombre_jours) VALUES 
(360),   
(365);   


INSERT INTO banque_type_pret (taux, duree_min, description, pret_min, pret_max, id_calcul_ti, id_def_annee) VALUES 
(5.0, 6, 'credit consommation', 100000, 1000000, 1, 1),
(6.5, 12, 'credit auto',         500000, 5000000, 1, 1),
(4.5, 60, 'pret immobilier',     5000000, 50000000, 2, 2),
(3.0, 12, 'pret etudiant',       200000, 2000000, 1, 1);








