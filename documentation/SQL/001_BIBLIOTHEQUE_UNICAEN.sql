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
--  INSERT ROLE ET UTILISATEUR PAR DEFAUT ------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto, parent_id, ldap_filter, accessible_exterieur, description)
VALUES
    ('Administrateur·trice technique', 'Administrateur·trice technique', false, false, null, null, true, null),
    ('Administrateur·trice fonctionnel·le', 'Administrateur·trice fonctionnel·le', false, false, null, null, true, null),
    ('Observateur·trice', 'Observateur·trice', false, false, null, null, true, null),
    ('Directeur·trice des ressources humaines', 'Directeur·trice des ressources humaines', false, false, null, null, true, null)
;

INSERT INTO unicaen_utilisateur_user (id, username, display_name, email, password, state, password_reset_token, last_role_id)
VALUES
    (0, 'EMC2', 'EMC2', null, 'null', false, null, null)
;

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN UTILISATEUR -------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('utilisateur', 'Gestion des utilisateurs', 20010, 'UnicaenUtilisateur\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'utilisateur_afficher',      'Afficher un utilisateur',                      10 UNION
    SELECT 'utilisateur_ajouter',       'Ajouter/Supprimer un utilisateur',             20 UNION
    SELECT 'utilisateur_changerstatus', 'Changer le statut d''un d''utilisateur',       30 UNION
    SELECT 'utilisateur_modifierrole',  'Modifier les rôles associés à un utilisateur', 40 UNION
    SELECT 'utilisateur_rechercher',    'Rechercher',                                   100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'utilisateur';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('role', 'Gestion des rôles', 20010, 'UnicaenUtilisateur\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'role_afficher', 'Afficher les rôles', 10 UNION
    SELECT 'role_modifier', 'Modifier un rôle', 20 UNION
    SELECT 'role_effacer', 'Effacer un rôle', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'role';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN PRIVILEGE ---------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('privilege', 'Gestion des privilèges', 10000, 'UnicaenPrivilege\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'privilege_voir', 'Afficher les privilèges', 10 UNION
    SELECT 'privilege_ajouter', 'Ajouter un privilège', 20 UNION
    SELECT 'privilege_modifier', 'Modifier un privilège', 30 UNION
    SELECT 'privilege_supprimer', 'Supprimer un privilège', 40 UNION
    SELECT 'privilege_affecter', 'Affecter un privilège', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'privilege'
;

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN PARAMETRE ---------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('parametrecategorie', 'UnicaenParametre - Gestion des catégories de paramètres', 70000, 'UnicaenParametre\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'parametrecategorie_index', 'Affichage de l''index des paramètres', 10 UNION
    SELECT 'parametrecategorie_afficher', 'Affichage des détails d''une catégorie', 20 UNION
    SELECT 'parametrecategorie_ajouter', 'Ajouter une catégorie de paramètre', 30 UNION
    SELECT 'parametrecategorie_modifier', 'Modifier une catégorie de paramètre', 40 UNION
    SELECT 'parametrecategorie_supprimer', 'Supprimer une catégorie de paramètre', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'parametrecategorie';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('parametre', 'UnicaenParametre - Gestion des paramètres', 70001, 'UnicaenParametre\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'parametre_afficher', 'Afficher un paramètre', 10 UNION
    SELECT 'parametre_ajouter', 'Ajouter un paramètre', 20 UNION
    SELECT 'parametre_modifier', 'Modifier un paramètre', 30 UNION
    SELECT 'parametre_supprimer', 'Supprimer un paramètre', 50 UNION
    SELECT 'parametre_valeur', 'Modifier la valeur d''un parametre', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'parametre';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN MAIL --------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('mail', 'UnicaenMail - Gestion des mails', 9000, 'UnicaenMail\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'mail_index', 'Affichage de l''index', 10 UNION
    SELECT 'mail_afficher', 'Afficher un mail', 20 UNION
    SELECT 'mail_reenvoi', 'Ré-envoi d''un mail', 30 UNION
    SELECT 'mail_supprimer', 'Suppression d''un mail', 40 UNION
    SELECT 'mail_test', 'Envoi d''un mail de test', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'mail';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN RENDERER ---------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('documenttemplate', 'UnicaenRenderer - Gestion des templates', 11020, 'UnicaenRenderer\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documenttemplate_index', 'Afficher l''index des contenus', 1 UNION
    SELECT 'documenttemplate_afficher', 'Afficher un template', 10 UNION
    SELECT 'documenttemplate_ajouter', 'Ajouter un contenu', 15 UNION
    SELECT 'documenttemplate_modifier', 'Modifier un contenu', 20 UNION
    SELECT 'documenttemplate_supprimer', 'Supprimer un contenu', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documenttemplate';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('documentmacro', 'UnicaenRenderer - Gestion des macros', 11010, 'UnicaenRenderer\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documentmacro_index', 'Afficher l''index des macros', 1 UNION
    SELECT 'documentmacro_ajouter', 'Ajouter une macro', 10 UNION
    SELECT 'documentmacro_modifier', 'Modifier une macro', 20 UNION
    SELECT 'documentmacro_supprimer', 'Supprimer une macro', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documentmacro';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('documentcontenu', 'UnicaenRenderer - Gestion des contenus', 11030, 'UnicaenRenderer\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documentcontenu_index', 'Accès à l''index des contenus', 10 UNION
    SELECT 'documentcontenu_afficher', 'Afficher un contenu', 20 UNION
    SELECT 'documentcontenu_supprimer', 'Supprimer un contenu ', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documentcontenu';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN ETAT --------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('etat', 'UnicaenEtat - Gestion des états', 20000, 'UnicaenEtat\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'etat_index', 'Afficher l''index des états', 10 UNION
    SELECT 'etat_ajouter', 'Ajouter un état', 20 UNION
    SELECT 'etat_modifier', 'Modifier un état', 30 UNION
    SELECT 'etat_historiser', 'Historiser/Restaurer un etat', 40 UNION
    SELECT 'etat_detruire', 'Supprimer un état', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'etat' ;

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN VALIDATION --------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('validationtype', 'Gestion des types de validations', 40010, 'UnicaenValidation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'validationtype_afficher', 'Affichage des types de validations', 10 UNION
    SELECT 'validationtype_modifier', 'Modifier un type de validation', 30 UNION
    SELECT 'validationtype_historiser', 'Historiser/restaurer un type de validation', 40 UNION
    SELECT 'validationtype_detruire', 'Détruire un type de validation', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'validationtype';


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('validationinstance', 'Gestion des instances de validations', 40000, 'UnicaenValidation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'validationinstance_afficher', 'Affichage des instances de validations', 10 UNION
    SELECT 'validationinstance_modifier', 'Modifier une instance de validation', 20 UNION
    SELECT 'validationinstance_historiser', 'Historiser/restaurer une instance de validation', 40 UNION
    SELECT 'validationinstance_detruire', 'Détruire une isntance de validation', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'validationinstance';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN AIDE --------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('unicaenaideglossaire', 'UnicaenAide - Glossaire', 100300, 'UnicaenAide\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'glossaire_afficher', 'Affichage du glossaire', 10 UNION
    SELECT 'glossaire_index', 'Accès à l''index des défintions', 100 UNION
    SELECT 'glossaire_ajouter', 'Ajouter une définition', 110 UNION
    SELECT 'glossaire_modifier', 'Modifier une définition', 120 UNION
    SELECT 'glossaire_historiser', 'Historiser/restaurer une définition', 130 UNION
    SELECT 'glossaire_supprimer', 'Supprimer une supprimer', 140
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaenaideglossaire';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('unicaenaidefaq', 'UnicaenAide - F.A.Q.', 100200, 'UnicaenAide\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'faq_afficher', 'Afficher la FAQ', 10 UNION
    SELECT 'faq_index', 'Accès à l''index des questions', 20 UNION
    SELECT 'faq_ajouter', 'Ajouter une question', 30 UNION
    SELECT 'faq_modifier', 'Modifier une question', 40 UNION
    SELECT 'faq_historiser', 'Historiser/restaurer une question', 50 UNION
    SELECT 'faq_supprimer', 'Supprimer une question', 60

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaenaidefaq';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('unicaenaidedocumentation', 'UnicaenAide - Documentation', 100400, 'UnicaenAide\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documentation_afficher', 'Afficher la documentation', 10 UNION
    SELECT 'documentation_index', 'Accès à l''index des documentations', 20 UNION
    SELECT 'documentation_ajouter', 'Ajouter une documentation', 30 UNION
    SELECT 'documentation_modifier', 'Modifier une documentation', 40 UNION
    SELECT 'documentation_historiser', 'Historiser/restaurer une documentation', 50 UNION
    SELECT 'documentation_supprimer', 'Supprimer une documentation', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaenaidedocumentation';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN AUTOFORM ----------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('autoformindex', 'Gestion de l''index', 1500, 'UnicaenAutoform\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'index', 'Afficher le menu', 1
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformindex';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('autoformformulaire', 'Gestion des formulaires l''index', 1600, 'UnicaenAutoform\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formulaire_index', 'Accéder à l''index', 10 UNION
    SELECT 'formulaire_afficher', 'Afficher', 20 UNION
    SELECT 'formulaire_ajouter', 'Ajouter', 30 UNION
    SELECT 'formulaire_modifier', 'Modifier', 40 UNION
    SELECT 'formulaire_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'formulaire_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformformulaire';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('autoformcategorie', 'Gestion des catégorie', 1600, 'UnicaenAutoform\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'categorief_index', 'Accéder à l''index', 10 UNION
    SELECT 'categorief_afficher', 'Afficher', 20 UNION
    SELECT 'categorief_ajouter', 'Ajouter', 30 UNION
    SELECT 'categorief_modifier', 'Modifier', 40 UNION
    SELECT 'categorief_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'categorief_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformcategorie';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('autoformchamp', 'Gestion des catégorie', 1600, 'UnicaenAutoform\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'champ_index', 'Accéder à l''index', 10 UNION
    SELECT 'champ_afficher', 'Afficher', 20 UNION
    SELECT 'champ_ajouter', 'Ajouter', 30 UNION
    SELECT 'champ_modifier', 'Modifier', 40 UNION
    SELECT 'champ_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'champ_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformchamp';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN EVENEMENT ---------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_evenement_etat (id, code, libelle)
    VALUES (1, 'en_attente', 'En attente'),
           (2, 'en_cours', 'En cours'),
           (3, 'echec', 'Échec'),
           (4, 'succes', 'Succès');

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('evenementetat', 'Gestion des événements - État', 99991, 'UnicaenEvenement\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'etat_voir', 'état - consultation', 10 UNION
    SELECT 'etat_ajouter', 'état - ajout', 20 UNION
    SELECT 'etat_modifier', 'état - édition', 30 UNION
    SELECT 'etat_supprimer', 'état - suppression', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'evenementetat';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('evenementinstance', 'Gestion des événements - Instance', 99993, 'UnicaenEvenement\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'instance_consultation', 'instance - consultation', 10 UNION
    SELECT 'instance_ajout', 'instance - ajout', 20 UNION
    SELECT 'instance_edition', 'instance - édition', 30 UNION
    SELECT 'instance_suppression', 'instance - suppression', 40 UNION
    SELECT 'instance_traitement', 'instance - traitement', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'evenementinstance';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('evenementtype', 'Gestion des événements - Type', 99992, 'UnicaenEvenement\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'type_consultation', 'type - consultation', 10 UNION
    SELECT 'type_ajout', 'type - ajout', 20 UNION
    SELECT 'type_edition', 'type - édition', 30 UNION
    SELECT 'type_suppression', 'type - suppression', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'evenementtype';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN INDICATEUR --------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('indicateur', 'Gestions des indicateurs', 800, 'UnicaenIndicateur\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'indicateur_index', 'Index du module', 1   UNION
    SELECT 'indicateur_mes_indicateurs', 'Menu - Mes indicateurs - ', 1   UNION
    SELECT 'afficher_indicateur', 'Afficher un indicateur', 1   UNION
    SELECT 'editer_indicateur', 'Éditer un indicateur', 2   UNION
    SELECT 'detruire_indicateur', 'Effacer un indicateur', 3
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'indicateur';


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('abonnement', 'Gestion des abonnements', 99992, 'UnicaenIndicateur\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'afficher_abonnement', 'Afficher un abonnement', 110 UNION
    SELECT 'editer_abonnement', 'Modifier un abonnement', 120 UNION
    SELECT 'detruire_abonnement', 'Supprimer un abonnement', 130
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'abonnement';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('tableaudebord', 'Gestion des tableau de bord', 99992, 'UnicaenIndicateur\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'editer_tableaudebord', 'Éditer un tableau de bord', 5 UNION
    SELECT 'detruire_tableaudebord', 'Effacer un tableau de bord', 6 UNION
    SELECT 'afficher_tableaudebord', 'Afficher un tableau de bord', 4
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'tableaudebord';

-- OBSERVATION -

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre) VALUES
('observationtype', 'UnicaenObservation - Gestion des types d''observation', 'UnicaenObservation\Provider\Privilege', 11020);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'observationtype_index', 'Accéder à l''index', 10 UNION
    SELECT 'observationtype_afficher', 'Afficher', 20 UNION
    SELECT 'observationtype_ajouter', 'Ajouter', 30 UNION
    SELECT 'observationtype_modifier', 'Modifier', 40 UNION
    SELECT 'observationtype_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'observationtype_supprimer', 'Supprimer', 60

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'observationtype';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('observationinstance', 'UnicaenObservation - Gestion des observations', 'UnicaenObservation\Provider\Privilege', 11010);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'observationinstance_index', 'Accéder à l''index', 10 UNION
    SELECT 'observationinstance_afficher', 'Afficher', 20 UNION
    SELECT 'observationinstance_ajouter', 'Ajouter', 30 UNION
    SELECT 'observationinstance_modifier', 'Modifier', 40 UNION
    SELECT 'observationinstance_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'observationinstance_supprimer', 'Supprimer', 60

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'observationinstance';

-- ---------------------------------------------------------------------------------------------------------------------
-- PARAMETRE -----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_parametre_categorie (code, libelle, ordre, description)
VALUES ('GLOBAL', 'Paramètres globaux', 1, null);
INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE) AS (
    SELECT 'CODE_UNIV', 'Code de l''établissement porteur principal', '<p>Sert notamment pour l''affichage des status</p>', 'String',  1000 UNION
    SELECT 'INSTALL_PATH', 'Chemin d''installation (utiliser pour vérification)', null, 'String', 1000 UNION
    SELECT 'EMAIL_ASSISTANCE', 'Adresse électronique de l''assistance', null, 'String', 100 UNION
    SELECT 'FAVICON','Chemin vers le favicon', null,'String',1000
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES,  d.ORDRE
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'GLOBAL';

INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE, MODIFIABLE, AFFICHABLE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE, MODIFIABLE, AFFICHABLE) AS (
    SELECT 'VERSION', 'Version de l''application', null, 'String',  11, false, true UNION
    SELECT 'APP_NAME', 'Nom de l''application', null, 'String', 10, true, true UNION
    SELECT 'RELEASE_DATE', 'Date de la publication de la version', null, 'String', 12, true, true
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES,  d.ORDRE
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'GLOBAL';

-- ---------------------------------------------------------------------------------------------------------------------
-- TEMPLATE GENERAUX ---------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('EMC2_ACCUEIL', '<p>Texte de la page d''accueil</p>', 'texte',
'Instance de démonstration de EMC2',
e'<p>Instance de démonstration de EMC2.</p>
<p><em>Ce texte est template modifiable dans la partie Administration &gt; Template.</em></p>',
null,
'Application\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('EMC2_APROPOS', '<p>Texte affiché dans la partie "À propos" présentée en pied de page de l''application.</p>', 'texte',
'À propos de VAR[Macro#Parametre|GLOBAL;APP_NAME]',
'<p>La version courante de <strong>VAR[Macro#Parametre|GLOBAL;APP_NAME]</strong> est  VAR[Macro#Parametre|GLOBAL;VERSION] (Date de publication: VAR[Macro#Parametre|GLOBAL;RELEASE_DATE])</p>',
null,
'Application\Provider\Template');

-- ---------------------------------------------------------------------------------------------------------------------
-- MACROS --------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name)
VALUES
    ('EMC2#AppName', '', 'MacroService', 'getAppName'),
    ('EMC2#date', 'Affiche la date du jour d/m/Y', 'MacroService', 'getDate'),
    ('EMC2#datetime', 'Affiche la date et l heure d/m/Y à H:i', 'MacroService', 'getDateTime'),
    ('Macro#Parametre', '<p>Retourne la valeur d''un paramètre (Attention il faut préciser la catégorie et le paramètre de la façon suivante :  VAR[Macro#Parametre|CATEGORIE;PARAMETRE])</p>', 'MacroService', 'getValeurParametre');

;