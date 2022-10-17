INSERT INTO carriere_categorie (code, libelle, histo_creation, histo_createur_id) VALUES ('A', 'Catégorie A', current_date, 0);
INSERT INTO carriere_categorie (code, libelle, histo_creation, histo_createur_id) VALUES ('B', 'Catégorie B', current_date, 0);
INSERT INTO carriere_categorie (code, libelle, histo_creation, histo_createur_id) VALUES ('C', 'Catégorie C', current_date, 0);

-- NIVEAU (copie données unicaen)
INSERT INTO carriere_niveau (niveau, libelle, description, histo_creation, histo_createur_id, label) VALUES (1, 'Ingénieur de recherche et assimilé', null, current_date, 0, 'IGR');
INSERT INTO carriere_niveau (niveau, libelle, description, histo_creation, histo_createur_id, label) VALUES (2, 'Ingénieur d''étude et assimilé', null, current_date, 0, 'IGE');
INSERT INTO carriere_niveau (niveau, libelle, description, histo_creation, histo_createur_id, label) VALUES (3, 'Assistant-ingénieur', null, current_date, 0, 'ASI');
INSERT INTO carriere_niveau (niveau, libelle, description, histo_creation, histo_createur_id, label) VALUES (4, 'Technicien', null,  current_date, 0, 'TCH');
INSERT INTO carriere_niveau (niveau, libelle, description, histo_creation, histo_createur_id, label) VALUES (5, 'Adjoint technique', '<p>Ceci est le niveau des Adjoint technique et assimil&eacute;</p>',  current_date, 0, 'ADT');

