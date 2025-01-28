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
-- ETAT ----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_etat_categorie (code, libelle, icone, couleur, ordre)
VALUES ('FICHE_POSTE', 'États associés aux fiches de poste', 'fas fa-book-reader', '#8ae234', 200);
INSERT INTO unicaen_etat_type(code, libelle, categorie_id, icone, couleur)
WITH d(code, libelle, icone, couleur) AS (
    SELECT 'FICHE_POSTE_REDACTION', 'Fiche de poste en cours de rédaction', 'fas fa-pencil-ruler', '#ff9500', 10 UNION
    SELECT 'FICHE_POSTE_OK', 'Fiche de poste validée', 'fas fa-check', '#00a004', 20 UNION
    SELECT 'FICHE_POSTE_SIGNEE', 'Fiche de poste signée', 'fas fa-signature', '#002aff', 30 UNION
    SELECT 'FICHE_POSTE_MASQUEE', 'Fiche de poste masquée', 'fas fa-mask', '#cb0000', 100
)
SELECT d.code, d.libelle, cp.id, d.icone, d.couleur
FROM d
JOIN unicaen_etat_categorie cp ON cp.CODE = 'FICHE_POSTE';

-- ---------------------------------------------------------------------------------------------------------------------
-- VALIDATION ----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_validation_type (code, libelle, refusable) VALUES
    ('FICHEPOSTE_RESPONSABLE', 'Validation du responsable', false),
    ('FICHEPOSTE_AGENT', 'Validation de l''agent', false);

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE -----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('ficheposte', 'Fiche de poste', 2, 'FichePoste\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'ficheposte_index', 'Accéder à l''index des fiches de poste', 10 UNION
    SELECT 'ficheposte_afficher', 'Afficher une fiche de poste', 20 UNION
    SELECT 'ficheposte_ajouter', 'Ajouter une fiche de poste', 30 UNION
    SELECT 'ficheposte_modifier', 'Modifier une fiche de poste', 40 UNION
    SELECT 'ficheposte_historiser', 'Historiser/Restaurer une fiche de poste', 50 UNION
    SELECT 'ficheposte_detruire', 'Détruire une fiche de poste ', 60 UNION
    SELECT 'ficheposte_associeragent', 'Associer un agent à une fiche de poste', 100 UNION
    SELECT 'ficheposte_afficher_poste', 'Afficher les informations sur le poste', 200 UNION
    SELECT 'ficheposte_modifier_poste', 'Modifier les informations sur le poste', 210 UNION
    SELECT 'ficheposte_etat', 'Modifier l''état d''une fiche de poste', 300 UNION
    SELECT 'ficheposte_valider_responsable', 'Valider la fiche de poste en tant que responsable', 400 UNION
    SELECT 'ficheposte_valider_agent', 'Valider la fiche de poste en tant qu''agent', 410 UNION
    SELECT 'ficheposte_graphique', 'Affiche graphique sur les fiche de poste', 500

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'ficheposte';

-- ---------------------------------------------------------------------------------------------------------------------
-- PARAMETRE -----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------


INSERT INTO unicaen_parametre_categorie (code, libelle, ordre, description)
VALUES ('FICHE_POSTE', 'Paramètres liés aux fiches de poste', 200, null);
INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE) AS (
    SELECT 'CODE_FONCTION', 'Utilisation de code fonction', '<p>Le code fonction est une codification associ&eacute; au niveau associ&eacute; au poste li&eacute; &agrave; la fiche de poste utilis&eacute;e dans certains &eacute;tablissement.<br>Si ce param&egrave;tre est &agrave; la valeur <em>false</em> alors le code fonction n''est plus affich&eacute; sur la fiche de poste.</p>', 'Boolean', 10000
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES,  d.ORDRE
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'FICHE_POSTE';



-- ---------------------------------------------------------------------------------------------------------------------
-- MACROS --------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('FICHE_POSTE#Applications', '<p>Affichage des applications li&eacute;es &agrave; une fiche de poste (comprend les applications li&eacute;es aux activit&eacute;s si non retir&eacute;es)</p>', 'ficheposte', 'toStringApplications'),
    ('FICHE_POSTE#Cadre', '<p>Affichage du cadre du m&eacute;tier (de plus haut niveau) ! manque le lien vers le corps ! manque le lien vers la bap</p>', 'ficheposte', 'toStringCadre'),
    ('FICHE_POSTE#CompetencesComportementales', '<p>Affiche les comp&eacute;tences comportementales (non retir&eacute;es)</p>', 'ficheposte', 'toStringCompetencesComportementales'),
    ('FICHE_POSTE#CompetencesComportementalesToutes', '<p>Affiche la liste des comp&eacute;tences comportementales (y compris les retir&eacute;es)</p>', 'ficheposte', 'toStringAllCompetencesComportementales'),
    ('FICHE_POSTE#CompetencesOperationnelles', '<p>Affiche la liste des comp&eacute;tences op&eacute;rationnelles (non retir&eacute;es)</p>', 'ficheposte', 'toStringCompetencesOperationnelles'),
    ('FICHE_POSTE#CompetencesOperationnellesToutes', '<p>Affiche la liste des comp&eacute;tences op&eacute;rationnelles d''une fiche de poste (y compris les retir&eacute;es)</p>', 'ficheposte', 'toStringAllCompetencesOperationnelles'),
    ('FICHE_POSTE#Composition', '<p>Listing des fiches m&eacute;tiers composant une fiche de poste</p>', 'ficheposte', 'toStringCompositionFichesMetiers'),
    ('FICHE_POSTE#Connaissances', '<p>Affiche la liste des connaissances (non retir&eacute;es)</p>', 'ficheposte', 'toStringCompetencesConnaissances'),
    ('FICHE_POSTE#ConnaissancesToutes', '<p>Affiche les connaissances (y compris les retir&eacute;es)</p>', 'ficheposte', 'toStringAllCompetencesConnaissances'),
    ('FICHE_POSTE#FichesMetiers', '<p>Affichage des fiches m&eacute;tiers</p>', 'ficheposte', 'toStringFichesMetiers'),
    ('FICHE_POSTE#FichesMetiersCourt', '<p>Liste des fiches m&eacute;tiers et des libell&eacute;s de activit&eacute;s des m&eacute;tiers</p>', 'ficheposte', 'toStringFichesMetiersCourt'),
    ('FICHE_POSTE#Formations', '<p>Affichages des formations associ&eacute;es &agrave; une fiche de poste</p>', 'ficheposte', 'toStringFormations'),
    ('FICHE_POSTE#LIBELLE', null, 'ficheposte', 'toStringFicheMetierPrincipal'),
    ('FICHE_POSTE#LIBELLE_COMPLEMENTAIRE', '<p>Affichage du libell&eacute; compl&eacute;mentaire</p>', 'ficheposte', 'toStringLibelleComplementaire'),
    ('FICHE_POSTE#Parcours', '<p>Affichages des parcours de formations associ&eacute;s &agrave; une fiche de poste</p>', 'ficheposte', 'toStringParcours'),
    ('FICHE_POSTE#Specificite', '<p>Affichage des sp&eacute;cificit&eacute; du poste</p>', 'ficheposte', 'toStringSpecificiteComplete'),
    ('FICHE_POSTE#SpecificiteActivites', '', 'ficheposte', 'toStringSpecificiteActivites'),
    ('FICHE_POSTE#SpecificiteContraintes', null, 'ficheposte', 'toStringSpecificiteContraintes'),
    ('FICHE_POSTE#SpecificiteEncadrement', null, 'ficheposte', 'toStringSpecificiteEncadrement'),
    ('FICHE_POSTE#SpecificiteFormations', null, 'ficheposte', 'toStringSpecificiteFormations'),
    ('FICHE_POSTE#SpecificiteMoyens', null, 'ficheposte', 'toStringSpecificiteMoyens'),
    ('FICHE_POSTE#SpecificiteRelations', null, 'ficheposte', 'toStringSpecificiteRelations'),
    ('FICHE_POSTE#SpecificiteSpecificites', '<p>Liste des sp&eacute;cificit&eacute;s pr&eacute;cis&eacute;es dans la bloc ''Sp&eacute;cificit&eacute;'' de la partie ''Sp&eacute;cificit&eacute; du poste'' de la fiche de poste</p>', 'ficheposte', 'toStringSpecificiteSpecificite');


-- ---------------------------------------------------------------------------------------------------------------------
-- TEMPLATE - MAIL -----------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('FICHE_POSTE_VALIDATION_AGENT', null, 'mail', 'Validation de VAR[AGENT#Denomination] de sa fiche de poste', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">VAR[AGENT#Denomination] vient de valider sa fiche de poste.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences<br />VAR[EMC2#Nom]</p>
<p> </p>', null, null);
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('FICHE_POSTE_VALIDATION_RESPONSABLE', '<p>Mail envoyé à l''agent·e après la validation d''une fiche de poste par le·la responsable de l''agent·e</p>', 'mail', 'Votre fiche de poste de poste vient d''être validée par votre responsable', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Université de Caen Normandie<br />Direction des Ressources Humaines<br /><br />Bonjour,<br /><br />Votre fiche de poste vient d\'être validée par votre responsable.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Vous pouvez maintenant vous rendre dans EMC2 pour valider à votre tour celle-ci.<br />Vous retrouverez celle-ci à l\'adresse suivante : VAR[URL#FichePosteAcceder]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><br />Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences<br />VAR[EMC2#Nom]</p>
<p> </p>', null, null);

-- ---------------------------------------------------------------------------------------------------------------------
-- TEMPLATE - PDF ------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------


INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('FICHE_POSTE', '<p>Fiche de poste de l''agent</p>', 'pdf', 'FICHE_POSTE_VAR[AGENT#Denomination].pdf', e'<h1>VAR[FICHE_POSTE#LIBELLE]</h1>
<h2>Agent occupant le poste</h2>
<table style="width: 595px;">
<tbody>
<tr>
<td><strong>Dénomination</strong></td>
<td>VAR[AGENT#Denomination]</td>
</tr>
<tr>
<td><strong>Affectation principale<br /></strong></td>
<td>VAR[AGENT#AffectationStructure] / VAR[AGENT#AffectationStructureFine]</td>
</tr>
<tr>
<td><strong>Statut</strong></td>
<td> VAR[AGENT#StatutsActifs]</td>
</tr>
<tr>
<td><strong>Corps / Grade</strong></td>
<td> VAR[AGENT#GradesActifs]</td>
</tr>
<tr>
<td><strong>Quotité travaillée</strong></td>
<td>VAR[AGENT#Quotite]</td>
</tr>
<tr>
<td><strong>Échelon</strong></td>
<td>VAR[Agent#Echelon] (Date de passage : VAR[Agent#EchelonDate])</td>
</tr>
</tbody>
</table>
<p>VAR[AGENT#MissionsSpecifiques]</p>
<h2>Environnement du poste de travail</h2>
<p>VAR[STRUCTURE#resume]</p>
<h2>Composition de la fiche de poste</h2>
<p>VAR[FICHE_POSTE#FichesMetiers]</p>
<h2>Applications et compétences associées</h2>
<p>VAR[FICHE_POSTE#Applications]<br />VAR[FICHE_POSTE#Connaissances]<br />VAR[FICHE_POSTE#CompetencesOperationnelles]<br />VAR[FICHE_POSTE#CompetencesComportementales]</p>
<h2>Spécificité du poste</h2>
<p>VAR[FICHE_POSTE#Specificite]</p>
<p>VAR[FICHE_POSTE#SpecificiteActivites]</p>', 'body {font-size: 9pt;}h1 {font-size: 14pt; color:#123456;}h2 {font-size: 12pt; color:#123456; border-bottom: 1px solid #123456;}h3 {font-size: 10pt; color:#123456;}li.formation-groupe {font-weight:bold;} .mission-principale { padding-bottom:0; margin-bottom:0;}span.activite{border-left:1px solid #123456; display:block; }', null);
