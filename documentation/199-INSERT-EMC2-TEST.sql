INSERT INTO carriere_categorie (code, libelle, histo_creation, histo_createur_id) VALUES ('A', 'Catégorie A', current_date, 0);
INSERT INTO carriere_categorie (code, libelle, histo_creation, histo_createur_id) VALUES ('B', 'Catégorie B', current_date, 0);
INSERT INTO carriere_categorie (code, libelle, histo_creation, histo_createur_id) VALUES ('C', 'Catégorie C', current_date, 0);

-- NIVEAU (copie données unicaen)
INSERT INTO carriere_niveau (niveau, libelle, description, histo_creation, histo_createur_id, label) VALUES (1, 'Ingénieur de recherche et assimilé', null, current_date, 0, 'IGR');
INSERT INTO carriere_niveau (niveau, libelle, description, histo_creation, histo_createur_id, label) VALUES (2, 'Ingénieur d''étude et assimilé', null, current_date, 0, 'IGE');
INSERT INTO carriere_niveau (niveau, libelle, description, histo_creation, histo_createur_id, label) VALUES (3, 'Assistant-ingénieur', null, current_date, 0, 'ASI');
INSERT INTO carriere_niveau (niveau, libelle, description, histo_creation, histo_createur_id, label) VALUES (4, 'Technicien', null,  current_date, 0, 'TCH');
INSERT INTO carriere_niveau (niveau, libelle, description, histo_creation, histo_createur_id, label) VALUES (5, 'Adjoint technique', '<p>Ceci est le niveau des Adjoint technique et assimil&eacute;</p>',  current_date, 0, 'ADT');

-- NIVEAU ELEMENT (copie données unicaen)
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Competence', 'Débutant', 1, null, '2021-03-16 17:38:40.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Competence', 'Apprenti', 2, null, '2021-03-16 17:39:08.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Competence', 'Intermédiaire', 3, null, '2021-03-16 17:39:50.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Competence', 'Confirmé', 4, null, '2021-03-16 17:40:03.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Competence', 'Expert', 5, null, '2021-03-16 17:40:18.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Application', 'Débutant', 1, null, '2021-03-16 17:38:40.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Application', 'Apprenti', 2, null, '2021-03-16 17:39:08.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Application', 'Intermédiaire', 3, null, '2021-03-16 17:39:50.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Application', 'Confirmé', 4, null, '2021-03-16 17:40:03.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Application', 'Expert', 5, null, '2021-03-16 17:40:18.000000', 0);

-- COMPETENCE TYPE (copie données unicaen)
INSERT INTO element_competence_type (libelle, ordre, couleur, histo_creation, histo_createur_id) VALUES ('Comportementales', 30, 'DarkSeaGreen', '2019-09-25 17:15:05.000000', 0);
INSERT INTO element_competence_type (libelle, ordre, couleur, histo_creation, histo_createur_id) VALUES ('Connaissances', 10, 'IndianRed', '2019-09-25 17:15:05.000000', 0);
INSERT INTO element_competence_type (libelle, ordre, couleur, histo_creation, histo_createur_id) VALUES ('Opérationnelles', 20, 'CadetBlue', '2019-09-25 17:15:05.000000', 0);