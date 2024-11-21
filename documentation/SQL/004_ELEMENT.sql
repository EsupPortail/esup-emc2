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
-- INSERTION DE DONNEES ------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

-- NIVEAU ELEMENT (Compétence)
INSERT INTO element_niveau (type, libelle, niveau)
    VALUES ('Competence', 'Débutant', 1),
           ('Competence', 'Apprenti', 2),
           ('Competence', 'Intermédiaire', 3),
           ('Competence', 'Confirmé', 4),
           ('Competence', 'Expert', 5);
INSERT INTO element_niveau (type, libelle, niveau)
    VALUES ('Application', 'Débutant', 1),
           ('Application', 'Apprenti', 2),
           ('Application', 'Intermédiaire', 3),
           ('Application', 'Confirmé', 4),
           ('Application', 'Expert', 5);

INSERT INTO element_competence_type (libelle, ordre)
    VALUES ('Savoir être', 30),
           ('Savoir faire', 20),
           ('Connaissances', 10);

-----------------------------------------------------------------------------------------------------------
-- PRIVILEGE ----------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('niveau', 'Gestion des niveaux', 'Element\Provider\Privilege', 70900);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'niveau_index', 'Accéder à l''index des niveaux', 10 UNION
    SELECT 'niveau_afficher', 'Afficher un niveau', 20 UNION
    SELECT 'niveau_ajouter', 'Ajouter', 30 UNION
    SELECT 'niveau_modifier', 'Modifier un niveau', 40 UNION
    SELECT 'niveau_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'niveau_effacer', 'Supprimer un niveau', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'niveau';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('application', 'Gestion des applications', 'Element\Provider\Privilege', 70100);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'application_index', 'Accéder à l''index', 10 UNION
    SELECT 'application_afficher', 'Afficher une application', 20 UNION
    SELECT 'application_ajouter', 'Ajouter', 30 UNION
    SELECT 'application_modifier', 'Modifier une application', 40 UNION
    SELECT 'application_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'application_effacer', 'Supprimer une application', 60 UNION
    SELECT 'application_cartographie', 'Cartographie des applications', 100

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'application';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('applicationtheme', 'Gestion des thèmes d''application', 'Element\Provider\Privilege', 70200);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'applicationtheme_index', 'Acceder à l''index des thèmes d''application', 10 UNION
    SELECT 'applicationtheme_afficher', 'Afficher un thème d''application', 20 UNION
    SELECT 'applicationtheme_ajouter', 'Ajouter', 30 UNION
    SELECT 'applicationtheme_modifier', 'Modifier un thème d''application', 40 UNION
    SELECT 'applicationtheme_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'applicationtheme_effacer', 'Supprimer un thème d''application', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'applicationtheme';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('competence', 'Gestion des compétences', 'Element\Provider\Privilege', 70500);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'competence_index', 'Accéder à l''index des compétences', 10 UNION
    SELECT 'competence_afficher', 'Afficher une compétence', 20 UNION
    SELECT 'competence_ajouter', 'Ajouter', 30 UNION
    SELECT 'competence_modifier', 'Modifier une compétence', 40 UNION
    SELECT 'competence_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'competence_effacer', 'Supprimer une compétence', 60 UNION
    SELECT 'competence_substituer', 'Fusionner', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'competence';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('competencetype', 'Gestions des types de compétence', 'Element\Provider\Privilege', 70700);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'competencetype_index', 'Accéder à l''index des types de compétence', 10 UNION
    SELECT 'competencetype_afficher', 'Afficher un type de compétence', 20 UNION
    SELECT 'competencetype_ajouter', 'Ajouter', 30 UNION
    SELECT 'competencetype_modifier', 'Modifier un type de compétence', 40 UNION
    SELECT 'competencetype_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'competencetype_effacer', 'Supprimer un type de compétence', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'competencetype';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('competencetheme', 'Gestion des thèmes de compétence', 'Element\Provider\Privilege', 70600);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'competencetheme_index', 'Accéder à l''index des thèmes de compétence', 10 UNION
    SELECT 'competencetheme_afficher', 'Afficher un thème de compétence', 20 UNION
    SELECT 'competencetheme_ajouter', 'Ajouter', 30 UNION
    SELECT 'competencetheme_modifier', 'Modifier un thème de compétence', 40 UNION
    SELECT 'competencetheme_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'competencetheme_effacer', 'Supprimer un thème de compétence', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'competencetheme';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('competencereferentiel', 'Gestion des référentiels de compétences', 'Element\Provider\Privilege', 70800);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'competencereferentiel_index', 'Accéder à l''index', 10 UNION
    SELECT 'competencereferentiel_afficher', 'Afficher', 20 UNION
    SELECT 'competencereferentiel_ajouter', 'Ajouter', 30 UNION
    SELECT 'competencereferentiel_modifier', 'Modifier', 40 UNION
    SELECT 'competencereferentiel_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'competencereferentiel_effacer', 'Supprimer', 60 UNIOn
    SELECT 'competencereferentiel_gerer_competence', 'Gérer les compétences associées', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'competencereferentiel';