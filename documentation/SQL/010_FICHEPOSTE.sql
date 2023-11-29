-- Date de MAJ 24/11/2023 ----------------------------------------------------------------------------------------------
-- Script avant version 4.2.0 ------------------------------------------------------------------------------------------
-- Color scheme : AB7D01  ----------------------------------------------------------------------------------------------

-- TTTTTTTTTTTTTTTTTTTTTTT         AAA               BBBBBBBBBBBBBBBBB   LLLLLLLLLLL             EEEEEEEEEEEEEEEEEEEEEE
-- T:::::::::::::::::::::T        A:::A              B::::::::::::::::B  L:::::::::L             E::::::::::::::::::::E
-- T:::::::::::::::::::::T       A:::::A             B::::::BBBBBB:::::B L:::::::::L             E::::::::::::::::::::E
-- T:::::TT:::::::TT:::::T      A:::::::A            BB:::::B     B:::::BLL:::::::LL             EE::::::EEEEEEEEE::::E
-- TTTTTT  T:::::T  TTTTTT     A:::::::::A             B::::B     B:::::B  L:::::L                 E:::::E       EEEEEE
--         T:::::T            A:::::A:::::A            B::::B     B:::::B  L:::::L                 E:::::E
--         T:::::T           A:::::A A:::::A           B::::BBBBBB:::::B   L:::::L                 E::::::EEEEEEEEEE
--         T:::::T          A:::::A   A:::::A          B:::::::::::::BB    L:::::L                 E:::::::::::::::E
--         T:::::T         A:::::A     A:::::A         B::::BBBBBB:::::B   L:::::L                 E:::::::::::::::E
--         T:::::T        A:::::AAAAAAAAA:::::A        B::::B     B:::::B  L:::::L                 E::::::EEEEEEEEEE
--         T:::::T       A:::::::::::::::::::::A       B::::B     B:::::B  L:::::L                 E:::::E
--         T:::::T      A:::::AAAAAAAAAAAAA:::::A      B::::B     B:::::B  L:::::L         LLLLLL  E:::::E       EEEEEE
--       TT:::::::TT   A:::::A             A:::::A   BB:::::BBBBBB::::::BLL:::::::LLLLLLLLL:::::LEE::::::EEEEEEEE:::::E
--       T:::::::::T  A:::::A               A:::::A  B:::::::::::::::::B L::::::::::::::::::::::LE::::::::::::::::::::E
--       T:::::::::T A:::::A                 A:::::A B::::::::::::::::B  L::::::::::::::::::::::LE::::::::::::::::::::E
--       TTTTTTTTTTTAAAAAAA                   AAAAAAABBBBBBBBBBBBBBBBB   LLLLLLLLLLLLLLLLLLLLLLLLEEEEEEEEEEEEEEEEEEEEEE

create table ficheposte
(
    id                    serial
        primary key,
    libelle               varchar(256),
    agent                 varchar(40),
    rifseep               integer,
    nbi                   integer,
    fin_validite          timestamp,
    histo_creation        timestamp not null,
    histo_modification    timestamp,
    histo_destruction     timestamp,
    histo_createur_id     integer   not null
        constraint ficheposte_createur_fk
        references unicaen_utilisateur_user
        on delete cascade,
    histo_modificateur_id integer
        constraint ficheposte_modificateur_fk
        references unicaen_utilisateur_user
        on delete cascade,
    histo_destructeur_id  integer
);
create unique index fiche_metier_id_uindex on ficheposte (id);

create table ficheposte_expertise
(
    id                    serial
        constraint expertise_pk
        primary key,
    ficheposte_id         integer   not null
        constraint expertise_ficheposte_fk
        references ficheposte
        on delete cascade,
    libelle               text,
    description           text,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint expertise_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint expertise_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint expertise_destructeur_fk
        references unicaen_utilisateur_user
);

create table ficheposte_specificite
(
    id                 serial
        constraint ficheposte_specificite_pk
        primary key,
    ficheposte_id      integer
        constraint ficheposte_specificite_fiche_metier_id_fk
        references ficheposte
        on delete cascade,
    specificite        text,
    encadrement        text,
    relations_internes text,
    relations_externes text,
    contraintes        text,
    moyens             text,
    formations         text
);
create unique index ficheposte_specificite_id_uindex on ficheposte_specificite (id);

create table ficheposte_missionsadditionnelles
(
    id                    serial
        constraint specificite_activite_pk
        primary key,
    ficheposte_id         integer                 not null
        constraint ficheposte_missionsadditionnelles_ficheposte_id_fk
        references ficheposte
        on delete cascade,
    mission_id            integer                 not null
        constraint ficheposte_activite_specifique_missionprincipale_id_fk
        references missionprincipale
        on delete cascade,
    retrait               varchar(1024),
    description           text,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint specificite_activite_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint specificite_activite_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint specificite_activite_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user
);
create unique index specificite_activite_id_uindex on ficheposte_missionsadditionnelles (id);

create table ficheposte_fichemetier
(
    id          serial
        constraint fiche_type_externe_pk
        primary key,
    fiche_poste integer not null,
    fiche_type  integer not null
        constraint ficheposte_fichemetier_fichemetier_id_fk
        references fichemetier,
    quotite     integer not null,
    principale  boolean,
    activites   varchar(128)
);
create unique index fiche_type_externe_id_uindex on ficheposte_fichemetier (id);

create table ficheposte_activitedescription_retiree
(
    id                    serial
        constraint ficheposte_activitedescription_retiree_pk
        primary key,
    ficheposte_id         integer   not null
        constraint fadr_ficheposte_fk
        references ficheposte
        on delete cascade,
    fichemetier_id        integer   not null
        constraint fadr_fichemetier_fk
        references fichemetier
        on delete cascade,
    activite_id           integer   not null,
    description_id        integer   not null
        constraint fadr_description_fk
        references missionprincipale_activite
        on delete cascade,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint fadr_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint fadr_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint fadr_destructeur_id
        references unicaen_utilisateur_user
);

create table ficheposte_application_retiree
(
    id                    serial
        constraint ficheposte_application_conservee_pk
        primary key,
    ficheposte_id         integer   not null
        constraint fcc_ficheposte_fk
        references ficheposte
        on delete cascade,
    application_id        integer   not null
        constraint fcc_application_fk
        references element_application
        on delete cascade,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint fcc_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint fcc_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint fcc_destructeur_fk
        references unicaen_utilisateur_user
);
create unique index ficheposte_application_conservee_id_uindex on ficheposte_application_retiree (id);

create table ficheposte_competence_retiree
(
    id                    serial
        constraint ficheposte_competence_conservee_pk
        primary key,
    ficheposte_id         integer   not null
        constraint fcc_ficheposte_fk
        references ficheposte
        on delete cascade,
    competence_id         integer   not null
        constraint fcc_competence_fk
        references element_competence
        on delete cascade,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint fcc_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint fcc_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint fcc_destructeur_fk
        references unicaen_utilisateur_user
);
create unique index ficheposte_competence_conservee_id_uindex on ficheposte_competence_retiree (id);

create table ficheposte_fichemetier_domaine
(
    id                    serial
        constraint ficheposte_fichemetier_domaine_pk
        primary key,
    fichemetierexterne_id integer             not null
        constraint ficheposte_fichemetier_domaine_fiche_type_externe_id_fk
        references ficheposte_fichemetier
        on delete cascade,
    domaine_id            integer             not null
        constraint ficheposte_fichemetier_domaine_domaine_id_fk
        references metier_domaine
        on delete cascade,
    quotite               integer default 100 not null
);
create unique index ficheposte_fichemetier_domaine_id_uindex on ficheposte_fichemetier_domaine (id);

create table ficheposte_validation
(
    ficheposte_id integer not null
        constraint ficheposte_validations_ficheposte_id_fk
        references ficheposte
        on delete cascade,
    validation_id integer not null
        constraint ficheposte_validations_unicaen_validation_instance_id_fk
        references unicaen_validation_instance
        on delete cascade,
    constraint ficheposte_validations_pk
        primary key (ficheposte_id, validation_id)
);

create table ficheposte_etat
(
    ficheposte_id integer not null
        constraint ficheposte_etat_fichemetier_id_fk
        references ficheposte
        on delete cascade,
    etat_id       integer not null
        constraint ficheposte_etat_etat_id_fk
        references unicaen_etat_instance
        on delete cascade,
    constraint ficheposte_etat_pk
        primary key (ficheposte_id, etat_id)
);




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
