-- IIIIIIIIIINNNNNNNN        NNNNNNNN   SSSSSSSSSSSSSSS EEEEEEEEEEEEEEEEEEEEEERRRRRRRRRRRRRRRRR   TTTTTTTTTTTTTTTTTTTTTTT
-- I::::::::IN:::::::N       N::::::N SS:::::::::::::::SE::::::::::::::::::::ER::::::::::::::::R  T:::::::::::::::::::::T
-- I::::::::IN::::::::N      N::::::NS:::::SSSSSS::::::SE::::::::::::::::::::ER::::::RRRRRR:::::R T:::::::::::::::::::::T
-- II::::::IIN:::::::::N     N::::::NS:::::S     SSSSSSSEE::::::EEEEEEEEE::::ERR:::::R     R:::::RT:::::TT:::::::TT:::::T
--   I::::I  N::::::::::N    N::::::NS:::::S              E:::::E       EEEEEE  R::::R     R:::::RTTTTTT  T:::::T  TTTTTT
--   I::::I  N:::::::::::N   N::::::NS:::::S              E:::::E               R::::R     R:::::R        T:::::T
--   I::::I  N:::::::N::::N  N::::::N S::::SSSS           E::::::EEEEEEEEEE     R::::RRRRRR:::::R         T:::::T
--   I::::I  N::::::N N::::N N::::::N  SS::::::SSSSS      E:::::::::::::::E     R:::::::::::::RR          T:::::T
--   I::::I  N::::::N  N::::N:::::::N    SSS::::::::SS    E:::::::::::::::E     R::::RRRRRR:::::R         T:::::T
--   I::::I  N::::::N   N:::::::::::N       SSSSSS::::S   E::::::EEEEEEEEEE     R::::R     R:::::R        T:::::T
--   I::::I  N::::::N    N::::::::::N            S:::::S  E:::::E               R::::R     R:::::R        T:::::T
--   I::::I  N::::::N     N:::::::::N            S:::::S  E:::::E       EEEEEE  R::::R     R:::::R        T:::::T
-- II::::::IIN::::::N      N::::::::NSSSSSSS     S:::::SEE::::::EEEEEEEE:::::ERR:::::R     R:::::R      TT:::::::TT
-- I::::::::IN::::::N       N:::::::NS::::::SSSSSS:::::SE::::::::::::::::::::ER::::::R     R:::::R      T:::::::::T
-- I::::::::IN::::::N        N::::::NS:::::::::::::::SS E::::::::::::::::::::ER::::::R     R:::::R      T:::::::::T
-- IIIIIIIIIINNNNNNNN         NNNNNNN SSSSSSSSSSSSSSS   EEEEEEEEEEEEEEEEEEEEEERRRRRRRR     RRRRRRR      TTTTTTTTTTT


-- ---------------------------------------------------------------------------------------------------------------------
-- Macros et templates -------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name)
    VALUES ('MISSION_SPECIFIQUE#LIBELLE', '<p>Retourne le libell&eacute; d''une mission sp&eacute;cifique</p>', 'mission', 'getLibelle'),
           ('MISSION_SPECIFIQUE#DESCRIPTION', '<p>Affiche la description d''une mission sp&eacute;cifique ou&nbsp; ''Aucune description fournie pour cette mission sp&eacute;cifique'' si la description est manquante</p>', 'mission', 'toStringDescription')
;

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace)
VALUES ('MISSION_SPECIFIQUE_LETTRE', '<p>Lettre type associée à une mission spécifique d''un agent</p>', 'pdf', 'Mission_specifique_-_VAR[MISSION_SPECIFIQUE_AFFECTATION#ID]_-_VAR[AGENT#Denomination]', e'<h1>Justificatif de mission spécifique</h1>
<p> </p>
<p>L\'agent <strong>VAR[AGENT#Denomination]</strong> assure la mission spécifique <strong>VAR[MISSION_SPECIFIQUE#LIBELLE]</strong> dans le structure <strong>VAR[STRUCTURE#LIBELLE]</strong>.</p>
<ul>
<li>Il assume cette mission spécifique pour la période <strong>VAR[MISSION_SPECIFIQUE_AFFECTATION#PERIODE]</strong>.</li>
<li>Cette mission spécique permet une décharge horaire (<strong>VAR[MISSION_SPECIFIQUE_AFFECTATION#DECHARGE]</strong>)</li>
</ul>
<p>Ce document fait preuve.</p>', null, 'Application\Provider\Template');

-- ---------------------------------------------------------------------------------------------------------------------
-- Privileges ----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('missionprincipale', 'Gestion des missions principales', 800, 'FicheMetier\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionprincipale_index', 'Accéder à l''index', 10 UNION
    SELECT 'missionprincipale_afficher', 'Afficher', 20 UNION
    SELECT 'missionprincipale_ajouter', 'Ajouter', 30 UNION
    SELECT 'missionprincipale_modifier', 'Modifier', 40 UNION
    SELECT 'missionprincipale_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'missionprincipale_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionprincipale';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('missionspecifique', 'Gestion des missions spécifiques', 650, 'MissionSpecifique\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionspecifique_index', 'Gestion - Affichage de l''index des missions specifiques', 100 UNION
    SELECT 'missionspecifique_afficher', 'Gestion - Afficher une mission spécifique', 110 UNION
    SELECT 'missionspecifique_ajouter', 'Gestion - Ajouter une mission spécifiques', 120 UNION
    SELECT 'missionspecifique_modifier', 'Gestion - Modifier une mission spécifique', 130 UNION
    SELECT 'missionspecifique_historiser', 'Gestion - Historiser/restaurer une mission spécifique', 140 UNION
    SELECT 'missionspecifique_detruire', 'Gestion - Détruire une missions spécifique', 150
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionspecifique';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('missionspecifiquetheme', 'Gestion des thèmes de mission spécifique', 660, 'MissionSpecifique\Provider\Privilege');
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
VALUES ('missionspecifiquetype', 'Gestion des types de mission spécifique', 670, 'MissionSpecifique\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionspecifiquetype_index', 'Gestion - Affichage de l''index des types de mission specifique', 100 UNION
    SELECT 'missionspecifiquetype_afficher', 'Gestion - Afficher un type de mission spécifique', 110 UNION
    SELECT 'missionspecifiquetype_ajouter', 'Gestion - Ajouter un type de mission spécifique', 120 UNION
    SELECT 'missionspecifiquetype_modifier', 'Gestion - Modifier un type de mission spécifique', 130 UNION
    SELECT 'missionspecifiquetype_historiser', 'Gestion - Historiser/restaurer un type de mission spécifique', 140 UNION
    SELECT 'missionspecifiquetype_detruire', 'Gestion - Détruire un type de missions spécifique', 150
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionspecifiquetype';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('activite', 'Gestion des missions principales', 625, 'Application\Provider\Privilege');
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

-- ---------------------------------------------------------------------------------------------------------------------
-- MACRO ---------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

-- MACRO des missions spécifiques
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('MISSIONSPECIFIQUE#Type', '<p>Retourne le type de la mission spécifique</p>', 'missionspecifique', 'toStringType'),
    ('MISSIONSPECIFIQUE#Theme', '<p>Retourne le thème d''une mission spécifique</p>', 'missionspecifique', 'toStringTheme'),
    ('MISSIONSPECIFIQUE#Description', '<p>Retourne le descriptif d''une mission spécifique</p>', 'missionspecifique', 'toStringDescription'),
    ('MISSIONSPECIFIQUE#Libelle', '<p>Retourne le libellé d''une mission spécifique</p>', 'missionspecifique', 'toStringLibelle');
