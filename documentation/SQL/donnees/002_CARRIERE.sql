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
--  INSERT DEFAUT ------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO carriere_categorie (code, libelle, histo_creation, histo_createur_id)
    VALUES ('A', 'La catégorie A', now(), 0),
           ('B', 'La catégorie B', now(), 0),
           ('C', 'La catégorie C', now(), 0);

INSERT INTO carriere_mobilite (code, libelle, description)
    VALUES ('RECHERCHE_PASSIVE', 'J''étudirai des propositions qui me seraient faites', null),
           ('PAS_DE_DEMANDE', 'Je ne cherche ni étudie de mobilité', null),
           ('RECHERCHE_ACTIVE', 'En recherche active', '<p>Je d&eacute;sire changer de structure</p>');

--------------------------------------------------------------------------------------------------------------
-- PRIVILEGE -------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('niveaucarriere', 'Gestion des niveaux de carrière ', 1000, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'niveaucarriere_index', 'Accéder à l''index', 1 UNION
    SELECT 'niveaucarriere_afficher', 'Afficher un niveau', 10 UNION
    SELECT 'niveaucarriere_ajouter', 'Ajouter un niveau', 20 UNION
    SELECT 'niveaucarriere_modifier', 'Modifier un niveau', 30 UNION
    SELECT 'niveaucarriere_historiser', 'Historiser/Restaurer un niveau', 40 UNION
    SELECT 'niveaucarriere_supprimer', 'Supprimer un niveau', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'niveaucarriere';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('categorie', 'Gestion des catégories (carrière)', 600, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'categorie_index', 'Accéder à l''index des catégories', 10 UNION
    SELECT 'categorie_afficher', 'Afficher une catégorie', 20 UNION
    SELECT 'categorie_modifier', 'Modifier une catégorie', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'categorie';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('correspondance', 'Gestion des correspondances', 630, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'correspondance_index', 'Accéder à l''index des correspondances', 10 UNION
    SELECT 'correspondance_afficher', 'Afficher une correspondance', 20 UNION
    SELECT 'correspondance_modifier', 'Modifier une correspondance', 30 UNION
    SELECT 'correspondance_lister_agents', 'Lister les agents', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'correspondance';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('corps', 'Gestion des corps', 610, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'corps_index', 'Accéder à l''index des corps', 10 UNION
    SELECT 'corps_afficher', 'Afficher les corps', 20 UNION
    SELECT 'corps_modifier', 'Modifier un corps', 30 UNION
    SELECT 'corps_lister_agents', 'Lister les agents', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'corps';


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('emploitype', 'Gestion des emplois types', 620, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'emploitype_index', 'Accéder à l''index des grades', 10 UNION
    SELECT 'emploitype_afficher', 'Afficher un grade', 20 UNION
    SELECT 'emploitype_modifier', 'Modifier un grade', 30 UNION
    SELECT 'emploitype_lister_agents', 'Lister les agents', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'emploitype';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('grade', 'Gestion des grades', 620, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'grade_index', 'Accéder à l''index des grades', 10 UNION
    SELECT 'grade_afficher', 'Afficher un grade', 20 UNION
    SELECT 'grade_modifier', 'Modifier un grade', 30 UNION
    SELECT 'grade_lister_agents', 'Lister les agents', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'grade';


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('mobilite', 'Gestion des types de mobilités', 640, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'mobilite_index', 'Accéder à l''index', 10 UNION
    SELECT 'mobilite_afficher', 'Afficher', 20 UNION
    SELECT 'mobilite_ajouter', 'Ajouter', 30 UNION
    SELECT 'mobilite_modifier', 'Modifier', 40 UNION
    SELECT 'mobilite_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'mobilite_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'mobilite';

--------------------------------------------------------------------------------------------------------------
-- PARAMS ----------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_parametre_categorie (code, libelle, ordre) VALUES ('CARRIERE', 'Paramètre du module Carrière', 20);
INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, VALEUR, ORDRE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, VALEUR, ORDRE) AS (
    SELECT 'CorpsAvecAgent', 'Affichage seulement des corps avec agent', null, 'Boolean', 'true', 100 UNION
    SELECT 'GradeAvecAgent', 'Affichage seulement des grades avec agent', null, 'Boolean', 'true', 200 UNION
    SELECT 'CorrespondanceAvecAgent', 'Affichage seulement des correspondance ayant un agent', null, 'Boolean', 'true', 300 UNION
    SELECT 'ACTIF_ONLY','Ne considérer que les Corps/Correspondances/Grades actifs',null,'Boolean','true', 1000
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES, d.VALEUR, d.ORDRE
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'CARRIERE';
