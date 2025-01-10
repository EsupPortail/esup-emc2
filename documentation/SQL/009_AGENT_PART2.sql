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
-- TEMPLATE ------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES
    ('LETTRE_MISSION', null, 'pdf', 'Application\Provider\Privilege',
     'Lettre de mission pour VAR[AGENT#Denomination] réalisant la mission spécifique VAR[MISSIONSPECIFIQUE#Libelle]',
     '<p>L''agent VAR[AGENT#Denomination] réalise la mission spécifique VAR[MISSIONSPECIFIQUE#Libelle]</p>',
     null);

-- ---------------------------------------------------------------------------------------------------------------------
-- MACROS --------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name)
VALUES
    ('AGENT#AffectationsActives', '<p>Affiche sous la forme d''une liste à puces les affectations actives d''un agent</p>', 'agent', 'toStringAffectationsActives'),
    ('AGENT#AffectationStructure', '<p>Affiche le libellé long de la structure de niveau 2 de l''agent.</p>', 'agent', 'toStringAffectationStructure'),
    ('AGENT#AffectationStructureFine', '<p>Affiche le libellé long de la structure fine de l''agent</p>', 'agent', 'toStringAffectationStructureFine'),
    ('AGENT#CorpsGrade', null, 'agent', 'toStringCorpsGrade'),
    ('AGENT#DateAffectationPrincipale', null, 'agent', 'toStringAffectationDate'),
    ('AGENT#DateNaissance', null, 'agent', 'toStringDateNaissance'),
    ('AGENT#Denomination', '<p>Retourne la d&eacute;nomination de l''agent (Pr&eacute;nom1 NomUsuel)</p>', 'agent', 'getDenomination'),
    ('Agent#Echelon', null, 'agent', 'toStringEchelon'),
    ('Agent#EchelonDate', null, 'agent', 'toStringEchelonPassage'),
    ('AGENT#EmploiType', null, 'agent', 'toStringEmploiType'),
    ('AGENT#GradesActifs', '<p>Affiche les grades actifs d''un agent sous la forme d''une liste &agrave; puce</p>', 'agent', 'toStringGradesActifs'),
    ('AGENT#IntitulePoste', null, 'agent', 'toStringIntitulePoste'),
    ('AGENT#Missions', null, 'agent', 'toStringMissions'),
    ('AGENT#MissionsSpecifiques', '<p>Affiches la section ''mission spécifique'' de la fiche de poste d''un agent (si il y a des missions spécifique)</p>', 'agent', 'toStringMissionsSpecifiques'),
    ('AGENT#NomFamille', null, 'agent', 'toStringNomFamille'),
    ('AGENT#NomUsage', null, 'agent', 'toStringNomUsage'),
    ('AGENT#Prenom', null, 'agent', 'toStringPrenom'),
    ('AGENT#Quotite', null, 'agent', 'toStringQuotite'),
    ('AGENT#QuotiteAffectation', null, 'agent', 'toStringQuotiteAffectation'),
    ('AGENT#StatutsActifs', '<p>Affiche la liste des statuts actifs d''un agent sous la forme d''une liste &agrave; puce</p>', 'agent', 'toStringStatutsActifs'),
    ('AGENT#StructureAffectationPrincipale', null, 'agent', 'toStringAffectationStructure')
;

INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('AGENT#AffectationStructureParente', '<p>Affiche le libellé long de la structure parente de l''agent.</p>', 'agent', 'toStringAffectationStructureParente');

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE -----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('missionspecifiqueaffectation', 'Gestion des affectations de mission spécifique', 680, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionspecifiqueaffectation_index', 'Affectation - Afficher l''index des affectation', 200 UNION
    SELECT 'missionspecifiqueaffectation_afficher', 'Affectation - Afficher une affectations de missions spécifiques', 210 UNION
    SELECT 'missionspecifiqueaffectation_ajouter', 'Affectation - Ajouter une affectation de mission spécifique', 220 UNION
    SELECT 'missionspecifiqueaffectation_modifier', 'Affectation - Modifier une affectation de mission specifique', 230 UNION
    SELECT 'missionspecifiqueaffectation_historiser', 'Affectation - Historiser/restaurer une affectation de mission spécifique', 240 UNION
    SELECT 'missionspecifiqueaffectation_detruire', 'Affectation - Détruire une affectation de mission spécifique', 250 UNION
    SELECT 'missionspecifiqueaffectation_onglet', 'Afficher l''onglet - Missions spécifiques -', 400
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionspecifiqueaffectation';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('agentaffichage', 'Affichage des informations relatives à l''agent', 510, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'agentaffichage_superieur', 'Afficher les supérieur·es hiérarchiques direct·es', 10 UNION
    SELECT 'agentaffichage_autorite', 'Afficher les autorités hiérarchiques', 20 UNION
    SELECT 'agentaffichage_dateresume', 'Afficher les dates sur le résumé de carrière', 30 UNION
    SELECT 'agentaffichage_carrierecomplete', 'Afficher la carrière complète', 40 UNION
    SELECT 'agentaffichage_compte', 'Afficher les informations sur le compte utilisateur', 50 UNION
    SELECT 'agentaffichage_temoin_affectation', 'Afficher les temoins liés aux affectations', 60 UNION
    SELECT 'agentaffichage_temoin_statut', 'Afficher les temoins liés aux statuts', 70

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'agentaffichage';


INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('agentmobilite', 'Gestion des mobilités des agents', 'Application\Provider\Privilege', 800);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'agentmobilite_index', 'Accéder à l''index', 10 UNION
    SELECT 'agentmobilite_afficher', 'Afficher', 20 UNION
    SELECT 'agentmobilite_ajouter', 'Ajouter', 30 UNION
    SELECT 'agentmobilite_modifier', 'Modifier', 40 UNION
    SELECT 'agentmobilite_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'agentmobilite_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'agentmobilite';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('agent', 'Gestion des agents', 500, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'agent_index', 'Accéder à l''index', 0 UNION
    SELECT 'agent_afficher', 'Afficher un agent', 10 UNION
    SELECT 'agent_info_source', 'Afficher les informations sur les identifiants sources', 11 UNION
    SELECT 'agent_afficher_donnees', 'Afficher le menu ''mes données''', 12 UNION
    SELECT 'agent_ajouter', 'Ajouter un agent', 20 UNION
    SELECT 'agent_editer', 'Modifier un agent', 30 UNION
    SELECT 'agent_effacer', 'Effacer un agent', 50 UNION
    SELECT 'agent_rechercher', 'Rechercher un agent', 99 UNION
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


-- Privileges

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('chaine', 'Gestion des chaînes hiérarchiques', 900, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
 SELECT 'chaine_index', 'Accèder à l''index', 10 UNION
 SELECT 'chaine_afficher', 'Afficher', 20 UNION
 SELECT 'chaine_synchroniser', 'Gérer les chaînes importées', 30 UNION
 SELECT 'chaine_gerer', 'Gérer les chaînes internes', 40 UNION
 SELECT 'chaine_importer', 'Accéder à l''importation et aux calculs', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'chaine';

-- --------------------------------------------------------------------------------------------------------------------
-- ROLE ---------------------------------------------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES
    ('Supérieur·e hiérarchique direct·e', 'Supérieur·e hiérarchique direct·e', false, true),
    ('Autorité hiérarchique', 'Autorité hiérarchique', false, true)
;

-- ---------------------------------------------------------------------------------------------------------------------
-- PARAMETRE -----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------


INSERT INTO unicaen_parametre_categorie (code, libelle, ordre, description)
VALUES ('AGENT', 'Paramètres liés à la partie Agent', 100, null);
INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE) AS (
    SELECT 'ONGLET_INFORMATIONS', 'Affichage de la partie - Informations générales - ', null, 'Boolean', 10 UNION
    SELECT 'ONGLET_FICHES', 'Affichage de la partie - Fiches de poste et missions spécifiques -', null, 'Boolean', 20 UNION
    SELECT 'ONGLET_MOBILITES', 'Affichage de la partie - Déclaration de mobilités -', null, 'Boolean', 30 UNION
    SELECT 'ONGLET_ACQUIS', 'Affichage de la partie - Acquis de l''agent -', null, 'Boolean', 40 UNION
    SELECT 'ONGLET_PORTFOLIO', 'Affichage de la partie - Portfolio -', null, 'Boolean', 50
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES, d.ORDRE
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'AGENT';


-- VALEUR RECOMMANDEE
update unicaen_parametre_parametre set valeur='true' where code='ONGLET_INFORMATIONS';
update unicaen_parametre_parametre set valeur='true' where code='ONGLET_FICHES';
update unicaen_parametre_parametre set valeur='true' where code='ONGLET_MOBILITES';
update unicaen_parametre_parametre set valeur='true' where code='ONGLET_ACQUIS';
update unicaen_parametre_parametre set valeur='false' where code='ONGLET_PORTFOLIO';
