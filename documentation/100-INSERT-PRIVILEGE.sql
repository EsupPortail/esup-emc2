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

