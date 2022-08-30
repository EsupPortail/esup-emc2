INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('campagne', 'Gestion des campagnes d''entretiens professionnels', 1050, 'EntretienProfessionnel\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'campagne_index', 'Accéder à l''index des campagnes', 10 UNION
    SELECT 'campagne_afficher', 'Afficher une campagne', 20 UNION
    SELECT 'campagne_ajouter', 'Ajouter une campagne', 30 UNION
    SELECT 'campagne_modifier', 'Modifier une campagne', 35 UNION
    SELECT 'campagne_historiser', 'Historiser une campagne', 40 UNION
    SELECT 'campagne_detruire', 'Supprimer une campagne', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'campagne';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('observation', 'Gestion des observation d''entretien professionnel', 1010, 'EntretienProfessionnel\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'observation_afficher', 'Afficher une observation', 10 UNION
    SELECT 'observation_ajouter', 'Ajouter une observation ', 20 UNION
    SELECT 'observation_modifier', 'Modifier une observation ', 30 UNION
    SELECT 'observation_historiser', 'Historiser/Restaurer une observation ', 40 UNION
    SELECT 'observation_supprimer', 'Supprimer une observation ', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'observation';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('sursis', 'Gestion des sursis d''entretien professionnel', 1020, 'EntretienProfessionnel\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'sursis_afficher', 'Afficher un sursis', 10 UNION
    SELECT 'sursis_ajouter', 'Ajouter un sursis', 20 UNION
    SELECT 'sursis_modifier', 'Modifier une sursis', 30 UNION
    SELECT 'sursis_historiser', 'Historiser/Restaurer un sursis', 40 UNION
    SELECT 'sursis_supprimer', 'Supprimer un sursis', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'sursis';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('entretienpro', 'Gestion des entretiens professionnels', 1000, 'EntretienProfessionnel\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'entretienpro_index', 'Afficher l''index des entretiens professionnels', 0 UNION
    SELECT 'entretienpro_afficher', 'Afficher les entretiens professionnels', 10 UNION
    SELECT 'entretienpro_exporter', 'Exporter les entretiens professionnels', 15 UNION
    SELECT 'entretienpro_ajouter', 'Ajouter un entretien professionnel', 20 UNION
    SELECT 'entretienpro_modifier', 'Modifier un entretien professionnel', 30 UNION
    SELECT 'entretienpro_historiser', 'Historiser/restaurer un entretien professionnel', 40 UNION
    SELECT 'entretienpro_detruire', 'Supprimer un entretien professionnel', 50 UNION
    SELECT 'entretienpro_valider_agent', 'Valider en tant qu''Agent', 900 UNION
    SELECT 'entretienpro_valider_responsable', 'Valider en tant que Responsable', 910 UNION
    SELECT 'entretienpro_valider_drh', 'Valider en tant que DRH', 920 UNION
    SELECT 'entretienpro_valider_observation', 'Valider les observation', 930
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'entretienpro';

-- TEMPLATE - MAIL ---



-- EVENT ---

INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('rappel_campagne','rappel_campagne','rappel_campagne', 'campagne', 'P4W');
INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('rappel_entretienpro','rappel_entretienpro','rappel_entretienpro', 'entretien', null);
INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('rappel_pas_observation_entretienpro','rappel_pas_observation_entretienpro','rappel_pas_observation_entretienpro', 'entretien', null);