

-- MISSIONS PRINCIPALES ---------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('activite', 'Gestion des missions principales', 600, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'activite_index', 'Accéder à l''index des missions principales', 0 UNION
    SELECT 'activite_afficher', 'Afficher une mission principale', 10 UNION
    SELECT 'activite_ajouter', 'Ajouter une mission principale', 20 UNION
    SELECT 'activite_modifier', 'Modifier une mission principale ', 30 UNION
    SELECT 'activite_historiser', 'Historiser/restaurer une mission principale', 40 UNION
    SELECT 'activite_detruire', 'Détruire une activité', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'activite';

-- MISSIONS SPECIFIQUE ----------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('missionspecifique', 'Gestion des missions spécifiques', 650, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionspecifique_index', 'Gestion - Affichage de l''index des missions specifiques', 100 UNION
    SELECT 'missionspecifique_ajouter', 'Gestion - Ajouter une mission spécifiques', 120 UNION
    SELECT 'missionspecifique_afficher', 'Gestion - Afficher une mission spécifique', 110 UNION
    SELECT 'missionspecifique_modifier', 'Gestion - Modifier une mission spécifique', 130 UNION
    SELECT 'missionspecifique_historiser', 'Gestion - Historiser/restaurer une mission spécifique', 140 UNION
    SELECT 'missionspecifique_detruire', 'Gestion - Détruire une missions spécifique', 150
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionspecifique';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('missionspecifiquetheme', 'Gestion des thèmes de mission spécifique', 660, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionspecifiquetheme_index', 'Gestion - Affichage de l''index des thèmes de mission specifique', 100 UNION
    SELECT 'missionspecifiquetheme_afficher', 'Gestion - Afficher un thème de  mission spécifique', 110 UNION
    SELECT 'missionspecifiquetheme_ajouter', 'Gestion - Ajouter un thème de mission spécifique', 120 UNION
    SELECT 'missionspecifiquetheme_modifier', 'Gestion - Modifier un thème mission spécifique', 130 UNION
    SELECT 'missionspecifiquetheme_historiser', 'Gestion - Historiser/restaurer un thème de mission spécifique', 140 UNION
    SELECT 'missionspecifiquetheme_detruire', 'Gestion - Détruire un thème de  mission spécifique', 150
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionspecifiquetheme';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('missionspecifiquetype', 'Gestion des types de mission spécifique', 670, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionspecifiquetype_index', 'Gestion - Affichage de l''index des types de mission specifique', 100 UNION
    SELECT 'missionspecifiquetypee_afficher', 'Gestion - Afficher un type de mission spécifique', 110 UNION
    SELECT 'missionspecifiquetype_ajouter', 'Gestion - Ajouter un type de mission spécifique', 120 UNION
    SELECT 'missionspecifiquetype_modifier', 'Gestion - Modifier un type de mission spécifique', 130 UNION
    SELECT 'missionspecifiquetype_historiser', 'Gestion - Historiser/restaurer un type de mission spécifique', 140 UNION
    SELECT 'missionspecifiquetype_detruire', 'Gestion - Détruire un type de missions spécifique', 150
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionspecifiquetype';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('missionspecifiqueaffectation', 'Gestion des affectations de mission spécifique', 680, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionspecifiqueaffectation_index', 'Affectation - Afficher l''index des affectation', 200 UNION
    SELECT 'missionspecifiqueaffectation_afficher', 'Affectation - Afficher une affectations de missions spécifiques', 210 UNION
    SELECT 'missionspecifiqueaffectation__ajouter', 'Affectation - Ajouter une affectation de mission spécifique', 220 UNION
    SELECT 'missionspecifiqueaffectation_modifier', 'Affectation - Modifier une affectation de mission specifique', 230 UNION
    SELECT 'missionspecifiqueaffectation_historiser', 'Affectation - Historiser/restaurer une affectation de mission spécifique', 240 UNION
    SELECT 'missionspecifiqueaffectation_detruire', 'Affectation - Détruire une affectation de mission spécifique', 250
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionspecifiqueaffectation';

-- AGENT ------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('agent', 'Gestion des agents', 500, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'agent_index', 'Accéder à l''index', 0 UNION
    SELECT 'agent_afficher', 'Afficher un agent', 10 UNION
    SELECT 'agent_info_source', 'Afficher les informations sur les identifiants sources', 11 UNION
    SELECT 'agent_afficher_donnees', 'Afficher le menu "mes données"', 12 UNION
    SELECT 'agent_ajouter', 'Ajouter un agent', 20 UNION
    SELECT 'agent_editer', 'Modifier un agent', 30 UNION
    SELECT 'agent_effacer', 'Effacer un agent', 50 UNION
    SELECT 'agent_element_ajouter_epro', 'Ajouter un entretien professionnel associé à un agent', 100 UNION
    SELECT 'agent_element_voir', 'Afficher les éléments associés à l''agent', 510 UNION
    SELECT 'agent_element_ajouter', 'Ajouter un élément associé à l''agent', 520 UNION
    SELECT 'agent_element_modifier', 'Modifier un élément associé à l''agent', 530 UNION
    SELECT 'agent_element_historiser', 'Historiser/restaurer un élément associé à l''agent', 540 UNION
    SELECT 'agent_element_detruire', 'Détruire un élément associé à l''agent', 550 UNION
    SELECT 'agent_element_valider', 'Valider un élément associé à l''agent', 560 UNION
    SELECT 'agent_acquis_afficher', 'Afficher les acquis d''un agent', 1000 UNION
    SELECT 'agent_acquis_modifier', 'Modifier les acquis d''un agent', 1010 UNION
    SELECT 'agent_gestion_ccc', 'Gestion des agents CCC', 9999
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'agent';

-- FICHE METIER -----------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('fichemetier', 'Fiche métier', 1, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'fichemetier_index', 'Accéder à l''index des fiches métiers', 0 UNION
    SELECT 'fichemetier_afficher', 'Afficher une fiche métier', 10 UNION
    SELECT 'fichemetier_ajouter', 'Ajouter une fiche métier', 20 UNION
    SELECT 'fichemetier_modifier', 'Éditer une fiche métier', 30 UNION
    SELECT 'fichemetier_historiser', 'Historiser/Restaurer une fiche métier', 40 UNION
    SELECT 'fichemetier_detruire', 'Détruire une fiche métier', 50 UNION
    SELECT 'fichemetier_etat', 'Gestion des états des fiches métiers', 1000
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'fichemetier';

-- CONFIGURATION ---------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('configuration', 'Configuration de l''application', 1100, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'configuration_afficher', 'Afficher la configuration de l''application', 1 UNION
    SELECT 'configuration_ajouter', 'Ajouter des éléments à la configuration', 2 UNION
    SELECT 'configuration_detruire', 'Rétirer des éléments à la configuration', 3 UNION
    SELECT 'configuration_reappliquer', 'Ré-appliquer les éléments à ajouter ', 4
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'configuration';


-- ATTRIBUTION A L'ADMIN TECH ---------------------------------------------------------------------------------

TRUNCATE TABLE unicaen_privilege_privilege_role_linker;
INSERT INTO unicaen_privilege_privilege_role_linker (privilege_id, role_id)
WITH d(privilege_id) AS (
    SELECT id FROM unicaen_privilege_privilege
)
SELECT d.privilege_id, cp.id
FROM d
JOIN unicaen_utilisateur_role cp ON cp.role_id = 'Administrateur·trice technique'
;