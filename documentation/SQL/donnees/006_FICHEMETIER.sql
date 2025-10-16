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
-- États et validations  -----------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_etat_categorie (code, libelle, icone, couleur, ordre)
VALUES ('FICHE_METIER', 'États liés aux fiches métiers', 'far fa-check-square', '#ad7fa8', 200);
INSERT INTO unicaen_etat_type (categorie_id, code, libelle, icone, couleur, ordre)
WITH d(code, libelle, icone, couleur, ordre) AS (
    SELECT 'FICHE_METIER_REDACTION', 'Fiche métier en rédaction', 'icon icon-editer', '#729fcf', 10 UNION
    SELECT 'FICHE_METIER_MASQUEE', 'Fiche métier masquée', 'fas fa-mask', '#a40000', 20 UNION
    SELECT 'FICHE_METIER_OK', 'Fiche métier validée', 'icon icon-checked', '#4e9a06', 30
)
SELECT cp.id, d.code, d.libelle, d.icone, d.couleur, d.ordre
FROM d
JOIN unicaen_etat_categorie cp ON cp.CODE = 'FICHE_METIER';

-- ---------------------------------------------------------------------------------------------------------------------
-- Privilèges ----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('fichemetier', 'Fiche métier', 100, 'FicheMetier\Provider\Privilege');
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

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('tendancetype', 'Gestion des types de tendance', 200, 'FicheMetier\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'tendancetype_index', 'Accéder à l''index', 10 UNION
    SELECT 'tendancetype_afficher', 'Afficher', 20 UNION
    SELECT 'tendancetype_ajouter', 'Ajouter', 30 UNION
    SELECT 'tendancetype_modifier', 'Modifier', 40 UNION
    SELECT 'tendancetype_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'tendancetype_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'tendancetype';


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('thematiquetype', 'Gestion des types de thématiques', 200, 'FicheMetier\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'thematiquetype_index', 'Accéder à l''index', 10 UNION
    SELECT 'thematiquetype_afficher', 'Afficher', 20 UNION
    SELECT 'thematiquetype_ajouter', 'Ajouter', 30 UNION
    SELECT 'thematiquetype_modifier', 'Modifier', 40 UNION
    SELECT 'thematiquetype_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'thematiquetype_supprimer', 'Supprimer', 60

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'thematiquetype';
-- ---------------------------------------------------------------------------------------------------------------------
-- Macros et templates -------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name)
    VALUES  ('FICHE_METIER#COMPETENCES_OPERATIONNELLES', null, 'fichemetier', 'getCompetencesOperationnelles'),
            ('FICHE_METIER#MISSIONS_PRINCIPALES', '<p>Retour le paragraphe des missions principales d''une fiche m&eacute;tier</p>', 'fichemetier', 'getMissions'),
            ('FICHE_METIER#COMPETENCES', '<p>Retourne la liste des comp&eacute;tences d''une fiche m&eacute;tier</p>', 'fichemetier', 'getCompetences'),
            ('FICHE_METIER#COMPETENCES_COMPORTEMENTALES', null, 'fichemetier', 'getCompetencesComportementales'),
            ('FICHE_METIER#APPLICATIONS', '<p>Affiche les applications associ&eacute;s &agrave; une fiche m&eacute;tier</p>', 'fichemetier', 'getApplicationsAffichage'),
            ('FICHE_METIER#INTITULE', '<p>Retourne le titre du m&eacute;tier associ&eacute; &agrave; la fiche m&eacute;tier</p>', 'fichemetier', 'getIntitule'),
            ('FICHE_METIER#CONNAISSANCES', null, 'fichemetier', 'getConnaissances'),
            ('FICHEMETIER#Environnement', '<p>Affiche le tableau contenant les thématiques déclarées pour la partie <strong>Contexte et environnement de travail</strong></p>', 'fichemetier', 'toStringThematiques')
;

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace)
    VALUES ('FICHE_METIER',
            '<p>Exportation pdf d''une fiche métier</p>', 'pdf',
            'Fiche_Métier_VAR[FICHE_METIER#INTITULE].pdf',
e'<h1>VAR[FICHE_METIER#INTITULE]</h1>
<table style="width: 517px;">
<tbody>
<tr>
<td style="width: 234.533px;">VAR[METIER#REFERENCE]</td>
<td style="width: 265.467px;">VAR[METIER#Domaine]</td>
</tr>
</tbody>
</table>
<h2>Missions principales</h2>
<p>VAR[FICHE_METIER#MISSIONS_PRINCIPALES]</p>
<h2>Compétences</h2>
<p>VAR[FICHE_METIER#COMPETENCES_COMPORTEMENTALES]<br />VAR[FICHE_METIER#CONNAISSANCES]<br />VAR[FICHE_METIER#COMPETENCES_OPERATIONNELLES]</p>
<h2>Applications</h2>
<p>VAR[FICHE_METIER#APPLICATIONS]</p>
<h2>Parcours de formation</h2>
<p>VAR[PARCOURS#FORMATIONS]</p>', 'body {font-size: 9pt;}h1 {font-size: 14pt; color:#123456;}h2 {font-size: 12pt; color:#123456; border-bottom: 1px solid #123456;}h3 {font-size: 10pt; color:#123456;}li.formation-groupe {font-weight:bold;} .mission-principale { padding-bottom:0; margin-bottom:0;}', 'FicheMetier\Provider\Template');

-- ---------------------------------------------------------------------------------------------------------------------
-- Paramètres ----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

-- NOUVELLE CATÉGORIE DE PARAMETRE
INSERT INTO unicaen_parametre_categorie (code, libelle, ordre, description)
VALUES ('FICHE_METIER', 'Paramètres liés aux fiches métiers', 200, null);
-- NOUVEAU PARAMÈTRES
INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE) AS (
    SELECT 'DISPLAY_TITRE', 'Affichage du bloc "Intitulé de la fiche métier"', null, 'Boolean',  10 UNION
    SELECT 'DISPLAY_RESUME', 'Affichage du bloc "Résumé"', null, 'Boolean',  20 UNION
    SELECT 'DISPLAY_RAISON', 'Affichage du bloc "Raison d''être du métier dans l''établissement"', null, 'Boolean',  30 UNION
    SELECT 'DISPLAY_MISSION', 'Affichage du bloc "Missions principales "', null, 'Boolean',  40 UNION
    SELECT 'DISPLAY_COMPETENCE', 'Affichage du bloc "Compétences"', null, 'Boolean', 50 UNION
    SELECT 'DISPLAY_COMPETENCE_SPECIFIQUE', 'Affichage du bloc "Compétences spécifiques"', null, 'Boolean', 55 UNION
    SELECT 'DISPLAY_APPLICATION', 'Affichage du bloc "Applications"', null, 'Boolean', 60 UNION
    SELECT 'DISPLAY_CONTEXTE', 'Affichage du bloc "Contexte et environnement de travail"', null, 'Boolean', 70 UNION
    SELECT 'CODE_FONCTION', 'Utilisation de code fonction', '<p>Le code fonction est une codification associ&eacute; au niveau associ&eacute; au poste li&eacute; &agrave; la fiche de poste utilis&eacute;e dans certains &eacute;tablissement.<br>Si ce param&egrave;tre est &agrave; la valeur <em>false</em> alors le code fonction n''est plus affich&eacute; sur la fiche de poste.</p>', 'Boolean', 10000
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES,  d.ORDRE
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'FICHE_METIER';