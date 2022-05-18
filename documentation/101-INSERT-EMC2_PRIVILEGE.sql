-- STRUCTURE --------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('structure', 'Gestion des structures', 200, 'Structure\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'structure_index', 'Accéder à l''index des structures', 0 UNION
    SELECT 'structure_afficher', 'Afficher les structures', 10 UNION
    SELECT 'structure_description', 'Édition de la description', 20 UNION
    SELECT 'structure_gestionnaire', 'Gérer les gestionnaire', 30 UNION
    SELECT 'structure_complement_agent', 'Ajouter des compléments à propos des agents', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'structure'
;

-- METIER -----------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('metier', 'Gestion des métiers', 551, 'Metier\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'metier_index', 'Afficher l''index des métiers', 10 UNION
    SELECT 'metier_afficher', 'Afficher un métier', 20 UNION
    SELECT 'metier_ajouter', 'Ajouter un métier', 30 UNION
    SELECT 'metier_modifier', 'Modifier un métier', 40 UNION
    SELECT 'metier_historiser', 'Historiser/Réstaurer un métier', 50 UNION
    SELECT 'metier_supprimer', 'Supprimer un métier', 60 UNION
    SELECT 'metier_cartographie', 'Extraire la cartographie', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'metier';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('domaine', 'Gestion des domaines', 552, 'Metier\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'domaine_index', 'Afficher l''index ', 10 UNION
    SELECT 'domaine_afficher', 'Afficher un domaine', 20 UNION
    SELECT 'domaine_ajouter', 'Ajouter un domaine', 30 UNION
    SELECT 'domaine_modifier', 'Modifier un domaine', 40 UNION
    SELECT 'domaine_historiser', 'Historiser/Restaurer un domaine', 50 UNION
    SELECT 'domaine_supprimer', 'Supprimer un domaine', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'domaine';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('familleProfessionnelle', 'Gestion des familles professionnelles ', 553, 'Metier\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'famille_professionnelle_index', 'Afficher l''index', 10 UNION
    SELECT 'famille_professionnelle_afficher', 'Afficher une famille professionnelle', 20 UNION
    SELECT 'famille_professionnelle_ajouter', 'Ajouter une famille professionnelle', 30 UNION
    SELECT 'famille_professionnelle_modifier', 'Modifier une famille professionnelle', 40 UNION
    SELECT 'famille_professionnelle_historiser', 'Historiser/Restaurer une famille professionnelle', 50 UNION
    SELECT 'famille_professionnelle_supprimer', 'Supprimer une famille professionnelle', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'familleProfessionnelle';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('referentielMetier', 'Gestion des référentiels métiers', 554, 'Metier\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'referentiel_index', 'Afficher l''index des référentiels métiers', 10 UNION
    SELECT 'referentiel_afficher', 'Afficher un référentiel métier', 20 UNION
    SELECT 'referentiel_ajouter', 'Ajouter un référentiel métier', 30 UNION
    SELECT 'referentiel_modifier', 'Modifier un référentiel métier', 40 UNION
    SELECT 'referentiel_historiser', 'Historiser/Restaurer un référentiel métier', 50 UNION
    SELECT 'referentiel_supprimer', 'Supprimer un référentiel métier', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'referentielMetier';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('referenceMetier', 'Gestion des références métiers', 555, 'Metier\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'reference_index', 'Afficher l''indes des références métiers', 10 UNION
    SELECT 'reference_afficher', 'Afficher une référence métier', 20 UNION
    SELECT 'reference_ajouter', 'Ajouter une référence métier', 30 UNION
    SELECT 'reference_modifier', 'Modifier une référence métier', 40 UNION
    SELECT 'reference_historiser', 'Historiser/Restaurer une référence métier', 50 UNION
    SELECT 'reference_supprimer', 'Supprimer une référence métier', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'referenceMetier';

-- CARRIERE ---------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('correspondance', 'Gestion des correspondances', 630, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'correspondance_index', 'Accéder à l''index des correspondances', 10 UNION
    SELECT 'correspondance_afficher', 'Afficher une correspondance', 20 UNION
    SELECT 'correspondance_modifier', 'Modifier une correspondance', 30
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
    SELECT 'corps_modifier', 'Modifier un corps', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'corps';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('grade', 'Gestion des grades', 620, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'grade_index', 'Accéder à l''index des grades', 10 UNION
    SELECT 'grade_afficher', 'Afficher un grade', 20 UNION
    SELECT 'grade_modifier', 'Modifier un grade', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'grade';

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

-- ELEMENT ---------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
    VALUES ('niveau', 'Gestion des niveaux', 'Element\Provider\Privilege', 70900);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'niveau_index', 'Accéder à l''index des niveaux', 10 UNION
    SELECT 'niveau_afficher', 'Afficher un niveau', 20 UNION
    SELECT 'niveau_modifier', 'Modifier un niveau', 30 UNION
    SELECT 'niveau_effacer', 'Supprimer un niveau', 40
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
    SELECT 'application_modifier', 'Modifier une application', 30 UNION
    SELECT 'application_effacer', 'Supprimer une application', 40
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
    SELECT 'applicationtheme_modifier', 'Modifier un thème d''application', 30 UNION
    SELECT 'applicationtheme_effacer', 'Supprimer un thème d''application', 40
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
    SELECT 'competence_modifier', 'Modifier une compétence', 30 UNION
    SELECT 'competence_effacer', 'Supprimer une compétence', 40
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
    SELECT 'competencetype_modifier', 'Modifier un type de compétence', 30 UNION
    SELECT 'competencetype_effacer', 'Supprimer un type de compétence', 40
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
    SELECT 'competencetheme_modifier', 'Modifier un thème de compétence', 30 UNION
    SELECT 'competencetheme_effacer', 'Supprimer un thème de compétence', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'competencetheme';

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

-- FICHE POSTE ------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
    VALUES ('ficheposte', 'Fiche de poste', 2, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'ficheposte_index', 'Accéder à l''index des fiches de poste', 0 UNION
    SELECT 'ficheposte_afficher', 'Afficher une fiche de poste', 10 UNION
    SELECT 'ficheposte_ajouter', 'Ajouter une fiche de poste', 20 UNION
    SELECT 'ficheposte_modifier', 'Modifier une fiche de poste', 30 UNION
    SELECT 'ficheposte_associeragent', 'Associer un agent à une fiche de poste', 31 UNION
    SELECT 'ficheposte_historiser', 'Historiser/Restaurer une fiche de poste', 40 UNION
    SELECT 'ficheposte_detruire', 'Détruire une fiche de poste ', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'ficheposte';

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