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
-- ROLE ----------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto, accessible_exterieur, description) VALUES
    ('Responsable de structure', 'Responsable de structure', false, true, true, null),
    ('Gestionnaire de structure', 'Gestionnaire de structure', false, true, true, null),
    ('Observateur·trice de la structure', 'Observateur·trice (Structure)', false, true, true, 'Observateur·trice limité·e au périmètre d''une structrure')
;

-- ---------------------------------------------------------------------------------------------------------------------
-- PARAMETRE -----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_parametre_categorie (code, libelle, ordre, description)
VALUES ('STRUCTURE', 'Paramètres liés aux structures', 1000, null);
INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, VALEUR, ORDRE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, VALEUR, ORDRE) AS (
    SELECT 'AGENT_TEMOIN_STATUT', 'Filtre sur les témoins de statuts associés aux agents affiché·es dans la partie structure', 'Il s''agit d''une cha&icirc;ne de caract&egrave;res reli&eacute;e par des ; avec les temoins suivant : cdi, cdd, titulaire, vacataire, enseignant, administratif, chercheur, doctorant, detacheIn, detacheOut, dispo <br/> Le modificateur ! est une n&eacute;gation.</p>', 'String', 'administratif;!dispo;!doctorant', 100 UNION
    SELECT 'AGENT_TEMOIN_AFFECTATION', 'Filtre sur les témoins d''affectations associés aux agents affiché·es dans la partie structure', 'Il s''agit d''une cha&icirc;ne de caract&egrave;res reli&eacute;e par des ; avec les temoins suivant : principale, hierarchique, fonctionnelle <br/> Le modificateur ! est une n&eacute;gation.</p>', 'String', 'principale', 200 UNION
    SELECT 'AGENT_TEMOIN_EMPLOITYPE', 'Filtres associés aux emploi-types', null, 'String', null, 300 UNION
    SELECT 'AGENT_TEMOIN_GRADE', 'Filtres associés aux grades', 'Filtrage basé sur les libellés courts (p.e. "IGE CN")', 'String', null, 400 UNION
    SELECT 'AGENT_TEMOIN_CORPS', 'Filtres associés aux corps', 'Filtrage basé sur les libellés courts (p.e. "ASI RF")', 'String', null, 500 UNION
    SELECT 'BLOC_OBSERVATEUR', 'Affichage du bloc - Observateur·trices -', null, 'Boolean', null, 10 UNION
    SELECT 'BLOC_GESTIONNAIRE', 'Affichage du bloc - Gestionnaires -', null, 'Boolean', null, 11
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES, d.VALEUR, d.ORDRE
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'STRUCTURE';

-- VALEUR RECOMMANDEE
update unicaen_parametre_parametre set valeur='false' where code='BLOC_OBSERVATEUR';
update unicaen_parametre_parametre set valeur='false' where code='BLOC_GESTIONNAIRE';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGES ----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('structure', 'Gestion des structures', 'Structure\Provider\Privilege', 100);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'structure_index', 'Accéder à l''index des structures', 0 UNION
    SELECT 'structure_afficher', 'Afficher les structures', 10 UNION
    SELECT 'structure_description', 'Édition de la description', 20 UNION
    SELECT 'structure_gestionnaire', 'Gérer les gestionnaire', 30 UNION
    SELECT 'structure_complement_agent', 'Ajouter des compléments à propos des agents', 40 UNION
    SELECT 'structure_agent_force', 'Ajouter/Retirer des agents manuellements', 50 UNION
    SELECT 'structure_agent_masque', 'Afficher/Masquer les agents exclus de la structure', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'structure';


INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('structureobservateur', 'Gestion des observateur·trice de structure', 'Structure\Provider\Privilege', 200);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'structureobservateur_index', 'Accéder à l''index', 10 UNION
    SELECT 'structureobservateur_afficher', 'Afficher', 20 UNION
    SELECT 'structureobservateur_ajouter', 'Ajouter', 30 UNION
    SELECT 'structureobservateur_modifier', 'Modifier', 40 UNION
    SELECT 'structureobservateur_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'structureobservateur_supprimer', 'Supprimer', 60 UNION
    SELECT 'structureobservateur_indexobservateur', 'Index - Les structures dont vous êtes observateur·trice - ', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'structureobservateur';

-- ---------------------------------------------------------------------------------------------------------------------
-- MACROS ASSOCIEES ----------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
('STRUCTURE#bloc', null, 'structure', 'toStringStructureBloc'),
('STRUCTURE#gestionnaires', '<p>Affiche sous la forme d''un listing les Gestionnaires de la structure</p>', 'structure', 'toStringGestionnaires'),
('STRUCTURE#libelle', '<p>Retourne le libellé de la structure</p>', 'structure', 'toStringLibelle'),
('STRUCTURE#libelle_long', '<p>Retourne le libellé de la structure + le libell&amp;eacute de la structure de niveau 2</p>', 'structure', 'toStringLibelleLong'),
('STRUCTURE#responsables', '<p>Affiches sous la forme d''un listing les Responsables d''une structure</p>', 'structure', 'toStringResponsables'),
('STRUCTURE#resume', null, 'structure', 'toStringResume');


