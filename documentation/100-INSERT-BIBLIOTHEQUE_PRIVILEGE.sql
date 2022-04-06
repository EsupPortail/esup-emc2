-- UNICAEN - UTILISATEUR --------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('utilisateur', 'Gestion des utilisateurs', 20010, 'UnicaenUtilisateur\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'utilisateur_afficher', 'Rechercher/Afficher un utilisateur', 10 UNION
    SELECT 'utilisateur_ajouter', 'Ajouter/Supprimer un utilisateur', 20 UNION
    SELECT 'utilisateur_changerstatus', 'Changer le statut d''un d''utilisateur', 30 UNION
    SELECT 'utilisateur_modifierrole', 'Modifier les rôles associés à un utilisateur', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'utilisateur'
;

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
 JOIN unicaen_privilege_categorie cp ON cp.CODE = 'role'
;

-- UNICAEN - PRIVILEGE ----------------------------------------------------------------------------------------

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

-- UNICAEN - PARAMETRE ----------------------------------------------------------------------------------------

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
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'parametrecategorie'
;

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
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'parametre'
;

-- UNICAEN - MAIL ---------------------------------------------------------------------------------------------

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
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'mail'
;

-- UNICAEN - RENDERER -----------------------------------------------------------------------------------------

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
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documenttemplate'
;

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
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documentmacro'
;


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
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documentcontenu'
;

-- UNICAEN - VALIDATION ---------------------------------------------------------------------------------------

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
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'validationtype'
;


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
 JOIN unicaen_privilege_categorie cp ON cp.CODE = 'validationinstance'
;

-- UNICAEN - GLOSSAIRE ----------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('definition', 'UnicaenGlossaire - Gestion des définitions', 60000, 'UnicaenGlossaire\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'definition_index', 'Afficher l''index des définitions', 10 UNION
    SELECT 'definition_afficher', 'Afficher une définition', 20 UNION
    SELECT 'definition_ajouter', 'Ajouter une définition', 30 UNION
    SELECT 'definition_modifier', 'Modifier une définition', 40 UNION
    SELECT 'definition_historiser', 'Historiser/Restaurer une définition', 50 UNION
    SELECT 'definition_supprimer', 'Supprimer une définition', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'definition'
;

-- UNICAEN - ETAT ---------------------------------------------------------------------------------------------

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
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'etat'
;

-- UNICAEN - AUTOFORM -----------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('autoform', 'Gestion des formulaires', 1500, 'UnicaenAutoform\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'afficher-index', 'Affichage de l''index du module', 1 UNION
    SELECT 'afficher-formulaire', 'Visualiser les formualaires', 2 UNION
    SELECT 'creer-formulaire', 'Créer un formulaire', 3 UNION
    SELECT 'modifier-formulaire', 'Modifier un formulaire', 4 UNION
    SELECT 'historiser-formulaire', 'Historiser un formulaire', 5 UNION
    SELECT 'detruire-formulaire', 'Détruire une formulaire', 6 UNION
    SELECT 'creer-categorie', 'Créer une catégorie', 7 UNION
    SELECT 'modifier-categorie', 'Modifier une catégorie', 8 UNION
    SELECT 'historiser-categorie', 'Historiser une catégorie', 9 UNION
    SELECT 'detruire-categorie', 'Détruire une catégorie', 10 UNION
    SELECT 'creer-champ', 'Créer un champ', 11 UNION
    SELECT 'modifier-champ', 'Modifier un champ', 12 UNION
    SELECT 'historiser-champ', 'Historiser un champ', 13 UNION
    SELECT 'detruire-champ', 'Détruire un champ', 14 UNION
    SELECT 'afficher-validation', 'Afficher une validation', 40 UNION
    SELECT 'creer-validation', 'Créer une validation', 41 UNION
    SELECT 'modifier-validation', 'Modifier une validation', 42 UNION
    SELECT 'historiser-validation', 'Historiser une validation', 43 UNION
    SELECT 'detruire-validation', 'Détruire une validation', 44
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoform'
;

-- EVENEMENT --------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('evenementetat', 'Gestion des événements - État', 99991, 'UnicaenEvenement\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'etat_consultation', 'état - consultation', 10 UNION
    SELECT 'etat_ajout', 'état - ajout', 20 UNION
    SELECT 'etat_edition', 'état - édition', 30 UNION
    SELECT 'etat_suppression', 'état - suppression', 40
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

-- ATTRIBUTION A L'ADMIN TECH ---------------------------------------------------------------------------------

TRUNCATE TABLE unicaen_privilege_privilege_role_linker;
INSERT INTO unicaen_privilege_privilege_role_linker (privilege_id, role_id)
WITH d(privilege_id) AS (
    SELECT id FROM unicaen_privilege_privilege
)
SELECT d.privilege_id, cp.id
FROM d
JOIN unicaen_utilisateur_role cp ON cp.role_id = 'Administrateur·trice technique';

