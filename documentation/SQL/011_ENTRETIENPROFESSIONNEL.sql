-- Date de MAJ 24/11/2023 ----------------------------------------------------------------------------------------------
-- Script avant version 4.2.0 ------------------------------------------------------------------------------------------
-- Color scheme : 284848  ----------------------------------------------------------------------------------------------

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


create table entretienprofessionnel_campagne
(
    id                    serial
        constraint entretienprofessionnel_campagne_pk
        primary key,
    annee                 varchar(256) not null,
    precede_id            integer
        constraint entretienprofessionnel_campagne_entretienprofessionnel_campagne
        references entretienprofessionnel_campagne
        on delete set null,
    date_debut            timestamp    not null,
    date_fin              timestamp    not null,
    date_circulaire       timestamp,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint entretienprofessionnel_campagne_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint entretienprofessionnel_campagne_user_id_fk1
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint entretienprofessionnel_campagne_user_id_fk2
        references unicaen_utilisateur_user
);
create unique index entretienprofessionnel_campagne_id_uindex on entretienprofessionnel_campagne (id);

create table entretienprofessionnel
(
    id                    serial
        constraint entretien_professionnel_pk
        primary key,
    agent                 varchar(40) not null
        constraint entretien_professionnel_agent_c_individu_fk
        references agent,
    responsable_id        varchar(40) not null
        constraint entretien_professionnel_agent_c_individu_fk_2
        references agent,
    formulaire_instance   integer,
    date_entretien        timestamp,
    campagne_id           integer     not null
        constraint entretien_professionnel_campagne_id_fk
        references entretienprofessionnel_campagne
        on delete set null,
    formation_instance    integer,
    lieu                  text,
    token                 varchar(255),
    acceptation           timestamp,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint entretienprofessionnel_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint entretienprofessionnel_user_id_fk1
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint entretienprofessionnel_user_id_fk2
        references unicaen_utilisateur_user
);
create unique index entretien_professionnel_id_uindex on entretienprofessionnel (id);

create table entretienprofessionnel_observation
(
    id                            serial
        constraint entretienprofessionnel_observation_pk
        primary key,
    entretien_id                  integer   not null
        constraint entretienprofessionnel_observation_entretien_professionnel_id_f
        references entretienprofessionnel
        on delete cascade,
    observation_agent_entretien   text,
    observation_agent_perspective text,
    histo_creation                timestamp not null,
    histo_createur_id             integer   not null
        constraint entretienprofessionnel_observation_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification            timestamp,
    histo_modificateur_id         integer
        constraint entretienprofessionnel_observation_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction             timestamp,
    histo_destructeur_id          integer
        constraint entretienprofessionnel_observation_user_id_fk_3
        references unicaen_utilisateur_user
);
create unique index entretienprofessionnel_observation_id_uindex on entretienprofessionnel_observation (id);

create table entretienprofessionnel_validation
(
    entretien_id  integer not null
        constraint entretienprofessionnel_validation_entretien_professionnel_id_fk
        references entretienprofessionnel
        on delete cascade,
    validation_id integer not null
        constraint entretienprofessionnel_validation_unicaen_validation_instance_i
        references unicaen_validation_instance
        on delete cascade,
    constraint entretienprofessionnel_validation_pk
        primary key (entretien_id, validation_id)
);

create table entretienprofessionnel_critere_competence
(
    id                    serial
        constraint entretienprofessionnel_critere_competence_pk
        primary key,
    libelle               varchar(1024)           not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null,
    histo_modification    timestamp,
    histo_modificateur_id integer,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
);
create unique index entretienprofessionnel_critere_competence_id_uindex on entretienprofessionnel_critere_competence (id);

create table entretienprofessionnel_critere_contribution
(
    id                    serial
        constraint entretienprofessionnel_critere_contribution_pk
        primary key,
    libelle               varchar(1024)           not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null,
    histo_modification    timestamp,
    histo_modificateur_id integer,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
);
create unique index entretienprofessionnel_critere_contribution_id_uindex on entretienprofessionnel_critere_contribution (id);

create table entretienprofessionnel_critere_personnelle
(
    id                    serial
        constraint entretienprofessionnel_critere_qualitepersonnelle_pk
        primary key,
    libelle               varchar(1024)           not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null,
    histo_modification    timestamp,
    histo_modificateur_id integer,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
);
create unique index entretienprofessionnel_critere_qualitepersonnelle_id_uindex on entretienprofessionnel_critere_personnelle (id);

create table entretienprofessionnel_critere_encadrement
(
    id                    serial
        constraint entretienprofessionnel_critere_encadrement_pk
        primary key,
    libelle               varchar(1024)           not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null,
    histo_modification    timestamp,
    histo_modificateur_id integer,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
);
create unique index entretienprofessionnel_critere_encadrement_id_uindex on entretienprofessionnel_critere_encadrement (id);

create table entretienprofessionnel_etat
(
    entretien_id integer not null
        constraint entretienprofessionnel_etat_entretien_id_fk
        references entretienprofessionnel
        on delete cascade,
    etat_id      integer not null
        constraint entretienprofessionnel_etat_etat_id_fk
        references unicaen_etat_instance
        on delete cascade,
    constraint entretienprofessionnel_etat_pk
        primary key (entretien_id, etat_id)
);

create table entretienprofessionnel_agent_force_sansobligation
(
    id                    serial
        constraint entretienprofessionnel_agent_force_sansobligation_pk
        primary key,
    agent_id              varchar(40) not null
        constraint ep_agent_force_sansobligation_agent_c_individu_fk
        references agent
        on delete cascade,
    campagne_id           integer     not null
        constraint ep_agent_force_sansobligation_campagne_id_fk
        references entretienprofessionnel_campagne
        on delete cascade,
    raison                text,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint ep_agent_force_sansobligation_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint ep_agent_force_sansobligation_unicaen_utilisateur_user_id_fk2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint ep_agent_force_sansobligation_unicaen_utilisateur_user_id_fk3
        references unicaen_utilisateur_user
);
comment on table entretienprofessionnel_agent_force_sansobligation is 'Table listant les agents pour lesquels on a forcé le fait qu''ils n''avait pas d''obligation d''entretien professionnel';



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


------------------------------------------------------------------------------------------------------------------------
-- ETAT ----------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_etat_categorie (code, libelle, icone, couleur, ordre)
VALUES ('ENTRETIEN_PROFESSIONNEL', 'États associés aux entretiens professionnels', 'fas fa-briefcase', '#75507b', 200);
INSERT INTO unicaen_etat_type(categorie_id, code, libelle, icone, couleur, ordre)
WITH e(code, libelle, icone, couleur, ordre) AS (
    SELECT 'ENTRETIEN_VALIDATION_HIERARCHIE', 'Validation de l''autorité hiérarchique', 'fas fa-user-tie', '#ffae00', 9999 UNION
    SELECT 'ENTRETIEN_VALIDATION_AGENT', 'Validation de l&rsquo;agent', 'far fa-check-square', '#44c200', 9999 UNION
    SELECT 'ENTRETIEN_ACCEPTATION', 'En attente confirmation de l&rsquo;agent',  'fas fa-user-clock', '#b90b80', 9999 UNION
    SELECT 'ENTRETIEN_ACCEPTER', 'Entretien accepté par l&rsquo;agent',  'fas fa-user-check', '#c100b5', 9999 UNION
    SELECT 'ENTRETIEN_VALIDATION_RESPONSABLE', 'Validation du responsable de l&rsquo;entretien professionnel', 'fas fa-user', '#c75000', 9999 UNION
    SELECT 'ENTRETIEN_VALIDATION_OBSERVATION', 'Expression des observations faite', 'far fa-comment-alt', '#ff6c0a', 9999
)
SELECT et.id, e.code, e.libelle, e.icone, e.couleur, e.ordre
FROM e
JOIN unicaen_etat_categorie et ON et.CODE = 'ENTRETIEN_PROFESSIONNEL';

-- ---------------------------------------------------------------------------------------------------------------------
-- VALDIATION ----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_validation_type (code, libelle, refusable) VALUES
    ('ENTRETIEN_AGENT', 'Validation de l''agent d''un entretien professionnel', false),
    ('ENTRETIEN_HIERARCHIE', 'Validation de la DRH d''un entretien professionnel', false),
    ('ENTRETIEN_OBSERVATION', 'Validation des observations', false),
    ('ENTRETIEN_RESPONSABLE', 'Validation du responsable d''un entretien professionnel', false);

-- ---------------------------------------------------------------------------------------------------------------------
-- PARAMETRE -----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_parametre_categorie (code, libelle, ordre, description)
VALUES ('ENTRETIEN_PROFESSIONNEL', 'Paramètres liés aux entretiens professionnels', 10, null);
INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE) AS (
    SELECT 'MAIL_LISTE_DAC', 'Adresse électronique de la liste de liste de diffusion pour les DAC', '<p>Utilis&eacute;e lors de la cr&eacute;ation d''une campagne d''entretien professionnel</p>', 'String', 10 UNION
    SELECT 'MAIL_LISTE_BIATS', 'Adresse électronique de la liste de diffusion pour le personnel', '<p>Utilis&eacute;e lors de la cr&eacute;ation d''une campagne d''entretien profesionnel</p>', 'String', 11 UNION
    SELECT 'TEMOIN_AFFECTATION', 'Témoin', null, 'String', 100 UNION
    SELECT 'DELAI_ACCEPTATION_AGENT', 'Délai d''acceptation de l''entretien par l''agent (en jours)', null, 'Number', 100 UNION
    SELECT 'INTRANET_DOCUMENT', 'Lien vers les documents associés à l''entretien professionnel', null, 'String', 1000 UNION
    SELECT 'TEMOIN_EMPLOITYPE', 'Filtrage sur les codes des emploi-types', null, 'String', 2000
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES, d.ORDRE
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'ENTRETIEN_PROFESSIONNEL';

-- ---------------------------------------------------------------------------------------------------------------------
-- Macros --------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

-- macro de la campagne
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('CAMPAGNE#debut', 'Date de début de la campagne d''entretien professionnel', 'campagne', 'getDateDebutToString'),
    ('CAMPAGNE#fin', 'Date de fin de la campagne d''entretien professionnel', 'campagne', 'getDateFinToString'),
    ('CAMPAGNE#annee', 'Année de la campagne d''entretien professionnel', 'campagne', 'getAnnee'),
    ('CAMPAGNE#datecirculaire', 'Date de circulaire de la campagne d''entretien professionnel', 'campagne', 'getDateCirculaireToString');

-- macro de l'entretien
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('ENTRETIEN#ActiviteService', null, 'entretien', 'toStringActiviteService'),
    ('ENTRETIEN#Agent', '<p>Affiche la d&eacute;nomination de l''agent passant son entretien professionnel</p>', 'entretien', 'toStringAgent'),
    ('ENTRETIEN#CompetencesPersonnelles', null, 'entretien', 'toStringCompetencesPersonnelles'),
    ('ENTRETIEN#CompetencesTechniques', null, 'entretien', 'toStringCompetencesTechniques'),
    ('ENTRETIEN#CREF_Champ', 'Retourne le contenu du champ (AutoForm) dans l''identification passe par les mots clefs passés en paramètre', 'entretien', 'toStringCREF_Champ'),
    ('ENTRETIEN#CREF_Champs', 'Retourne le contenu des champs (AutoForm) dans l''identification passe par les mots clefs passés en paramètre', 'entretien', 'toStringCREF_Champs'),
    ('ENTRETIEN#CREP_Champ', 'Retourne le contenu du champ (AutoForm) dans l''identification passe par les mots clefs passés en paramètre', 'entretien', 'toStringCREP_Champ'),
    ('ENTRETIEN#CREP_encadrement', null, 'entretien', 'toString_CREP_encadrement'),
    ('ENTRETIEN#CREP_encadrementA', null, 'entretien', 'toString_CREP_encadrementA'),
    ('ENTRETIEN#CREP_encadrementB', null, 'entretien', 'toString_CREP_encadrementB'),
    ('ENTRETIEN#CREP_encadrementC', null, 'entretien', 'toString_CREP_encadrementC'),
    ('ENTRETIEN#CREP_experiencepro', null, 'entretien', 'toString_CREP_experiencepro'),
    ('ENTRETIEN#CREP_projet', null, 'entretien', 'toString_CREP_projet'),
    ('ENTRETIEN#date', '<p>Retourne la date de l''entretien professionnel</p>', 'entretien', 'toStringDate'),
    ('ENTRETIEN#EncadrementConduite', null, 'entretien', 'toStringEncadrementConduite'),
    ('ENTRETIEN#lieu', '<p>Retourne le lieu de l''entretien professionnel</p>', 'entretien', 'toStringLieu'),
    ('ENTRETIEN#ObservationEntretien', 'Retourne l''observation sur l''entretien faite par l''agent', 'entretien', 'toStringObservationEntretien'),
    ('ENTRETIEN#ObservationPerspective', 'Retourne l''observation sur les perspectives faite par l''agent', 'entretien', 'toStringObservationPerspective'),
    ('ENTRETIEN#ReponsableCorpsGrade', 'Retourne le Corps/Garde du responsable de l''entretien professionnel', 'entretien', 'toStringReponsableCorpsGrade'),
    ('ENTRETIEN#ReponsableIntitlePoste', null, 'entretien', 'toStringReponsableIntitulePoste'),
    ('ENTRETIEN#ReponsableNomFamille', 'Retourne le nom de famille du responsable de l''entretien professionnel', 'entretien', 'toStringReponsableNomFamille'),
    ('ENTRETIEN#ReponsableNomUsage', 'Retourne le nom d''usage du responsable de l''entretien professionnel', 'entretien', 'toStringReponsableNomUsage'),
    ('ENTRETIEN#ReponsablePrenom', 'Retourne le prénom du responsable de l''entretien professionnel', 'entretien', 'toStringReponsablePrenom'),
    ('ENTRETIEN#ReponsableStructure', '<p>Affiche le libellé long d''affectation du responsable de l''entretien professionnel</p>', 'entretien', 'toStringReponsableStructure'),
    ('ENTRETIEN#Responsable', 'Retourne la d&eacute;nomination du responsable de l''entretien professionnel', 'entretien', 'toStringResponsable'),
    ('ENTRETIEN#VALIDATION_AGENT', 'Retourne la date (si validation) de la validation de l''entretien professionnel par l''agent', 'entretien', 'toStringValidationAgent'),
    ('ENTRETIEN#VALIDATION_AUTORITE', 'Retourne la date (si validation) de la validation de l''entretien professionnel par l''autorité', 'entretien', 'toStringValidationHierarchie'),
    ('ENTRETIEN#VALIDATION_SUPERIEUR', 'Retourne la date (si validation) de la validation de l''entretien professionnel par le responsable', 'entretien', 'toStringValidationResponsable');


-- ---------------------------------------------------------------------------------------------------------------------
-- Template ------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_VALIDATION_1-RESPONSABLE', null, 'mail', 'Validation de l''entretien professionnel pour la campagne VAR[CAMPAGNE#annee] de VAR[AGENT#denomination] par le responsable de l''entretien VAR[ENTRETIEN#Responsable]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Votre entretien professionnel pour la campagne VAR[CAMPAGNE#annee] a été validé par VAR[ENTRETIEN#responsable], vous êtes invité<span style="color: #4d5156; font-family: arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;">·</span>e à en prendre connaissance et y apporter des observations si vous l\'estimez nécessaire.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Vous pouvez dés à présent émettre, si besoin, des observations en lien avec :</p>
<ul>
<li style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">votre entretien professionnel et son déroulé ;</li>
<li style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">vos perspectives d\'évolution professionnelle.</li>
</ul>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Votre entretien professionnel est disponible sur l\'application VAR[EMC2#Nom] : VAR[URL#EntretienRenseigner].</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="text-decoration: underline;">Attention :</span> Vous disposez d\'un délai d\'une semaine pour émettre, si besoin, vos observations. <br />N\'oubliez pas de valider celles-ci.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Pour rappel, l\'entretien professionnel est un moment privilégié d\'échange et de dialogue avec votre responsable hiérarchique direct.<br />Nous vous invitons, si besoin, à vous rapprocher de votre responsable hiérarchique direct avant d\'émettre vos observations.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><br />Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_VALIDATION_3-HIERARCHIE', '<p>Validation du responsable hierarchique</p>', 'mail', 'Validation de l''autorité hiérarchique de votre entretien professionnel VAR[CAMPAGNE#annee]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span style="text-decoration: underline;">Objet :</span> validation de l\'autorité hiérarchique de votre entretien professionnel VAR[CAMPAGNE#annee]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">L\'autorité hiérarchique vient de valider votre entretien professionnel pour la campagne VAR[CAMPAGNE#annee].<br />Vous êtes invité-e à accuser réception de votre compte-rendu en cliquant dans l\'onglet validation de votre entretien : VAR[URL#EntretienRenseigner]<br />Cet accusé de réception clôturera votre entretien professionnel.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_VALIDATION_2-PAS_D_OBSERVATION', '<p>Mail envoyé au responsable hiérarchique après le dépassement du délai d''émission des observation</p>', 'mail', 'Ouverture de la validation de l''entretien professionnel de VAR[ENTRETIEN#Agent]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="text-decoration: underline;">Objet :</span> ouverture de la validation de l\'entretien professionnel de VAR[ENTRETIEN#Agent]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p>Vous pouvez maintenant valider l\'entretien professionnel de VAR[ENTRETIEN#Agent].<br />Vous pouvez consulter et valider cet entretien en suivant le lien : https://emc2.unicaen.fr/entretien-professionnel/acceder/VAR[ENTRETIEN#Id]</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br /><br /></p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('CAMPAGNE_DELEGUE', '<p>Courrier électronique envoyé vers les délégués lors de leur nomination</p>', 'mail', 'Nomination en tant que délégué·e pour la campagne d''entretien professionnel VAR[CAMPAGNE#annee]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p> </p>
<p><span style="text-decoration: underline;">Objet :</span> Nomination en tant que délégué·e pour la campagne d\'entretien professionnel VAR[CAMPAGNE#annee]</p>
<p>Bonjour VAR[AGENT#denomination],</p>
<p>Vous avez été nommé·e délégué·e pour la campagne d\'entretien professionnel VAR[CAMPAGNE#annee] pour la structure VAR[STRUCTURE#LIBELLE].<br />Vous serez informé par courrier électronique des entretiens professionnel dont vous aurez la charge.</p>
<p>Vous pourrez trouver sous le lien suivant la circulaire d\'ouverture en date du VAR[CAMPAGNE#datecirculaire] ainsi que plusieurs documents pouvant vous aider à préparer votre entretien : http://intranet.unicaen.fr/services-/ressources-humaines/gestion-des-personnels/entretien-professionnel-540109.kjsp?RH=1574253529391</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>
<p> </p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('CREF - Compte rendu d''entretien de formation', '<p>..</p>', 'pdf', 'Entretien_formation_VAR[CAMPAGNE#annee]_VAR[AGENT#NomUsage]_VAR[AGENT#Prenom].pdf', e'<h1>Annexe C9 bis - Compte rendu d\'entretien de formation</h1>
<p><strong>Année : VAR[CAMPAGNE#annee]</strong></p>
<table style="width: 998.25px;">
<tbody>
<tr>
<th style="width: 482px; text-align: center;"><strong>AGENT</strong></th>
<th style="width: 465.25px; text-align: center;"><strong>SUPÉRIEUR·E HIÉRARCHIQUE DIRECT·E</strong></th>
</tr>
<tr>
<td style="width: 482px;">
<p>Nom d\'usage : VAR[AGENT#NomUsage]</p>
<p>Nom de famille : VAR[AGENT#NomFamille]</p>
<p>Prénom : VAR[AGENT#Prenom]</p>
<p>Date de naissance: VAR[AGENT#DateNaissance]</p>
<p>Corps-grade : VAR[AGENT#CorpsGrade]<strong><br /></strong></p>
<p> </p>
</td>
<td style="width: 465.25px;">
<p>Nom d\'usage : VAR[ENTRETIEN#ReponsableNomUsage]</p>
<p>Nom de famille : VAR[ENTRETIEN#ReponsableNomFamille]</p>
<p>Prénom : VAR[ENTRETIEN#ReponsablePrenom]</p>
<p>Corps-grade : VAR[ENTRETIEN#ReponsableCorpsGrade]</p>
<p>Intitulé de la fonction : VAR[ENTRETIEN#ReponsableIntitlePoste]VAR[ENTRETIEN#CREP_Champ|CREP;responsable_date]</p>
<p>Structure : VAR[ENTRETIEN#ReponsableStructure]</p>
</td>
</tr>
</tbody>
</table>
<p> </p>
<table style="width: 1002px;">
<tbody>
<tr>
<td style="width: 346.797px;">Date de l\'entretien de formation</td>
<td style="width: 659.203px;">VAR[ENTRETIEN#date]</td>
</tr>
<tr>
<td style="width: 346.797px;">Date du précédent entretien de formation</td>
<td style="width: 659.203px;">VAR[ENTRETIEN#CREF_Champ|CREF;precedent]</td>
</tr>
<tr>
<td style="width: 346.797px;">Solde des droits CPF au 1er janvier</td>
<td style="width: 659.203px;">VAR[ENTRETIEN#CREF_Champ|CREF;CPF_solde]</td>
</tr>
<tr>
<td style="width: 346.797px;">L\'agent envisage-t\'il de mobiliser son CPF cette annee ?</td>
<td style="width: 659.203px;">VAR[ENTRETIEN#CREF_Champ|CREF;CPF_mobilisation] </td>
</tr>
</tbody>
</table>
<h2>1. Description du poste occupé par l\'agent</h2>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 722px;">
<p>Structure : VAR[AGENT#AffectationStructure]</p>
<p>Intitulé du poste : VAR[AGENT#IntitulePoste]VAR[ENTRETIEN#CREP_Champ|CREP;agent_poste]</p>
<p>Date d\'affectation : VAR[ENTRETIEN#CREP_Champ|CREP;affectation_date]</p>
<p>Emploi type (cf. REME ou REFERENS) : VAR[AGENT#EmploiType] VAR[ENTRETIEN#CREP_Champ|CREP;emploi-type]</p>
<p>Positionnement du poste dans le structure : VAR[AGENT#AffectationStructureFine]</p>
<p>Quotité travaillée : VAR[AGENT#Quotite]</p>
<p>Quotité d\'affectation : VAR[AGENT#QuotiteAffectation]</p>
</td>
</tr>
<tr>
<td style="width: 722px;">
<p>Missions du postes :<br />VAR[AGENT#Missions]</p>
<p> VAR[ENTRETIEN#CREP_Champ|CREP;missions]</p>
</td>
</tr>
<tr>
<td style="width: 722px;">
<p>Le cas échéant, fonctions d\'encadrement ou de conduite de projet :</p>
<ul>
<li>l\'agent assume-t\'il des fonctions de conduite de projet ? VAR[ENTRETIEN#CREP_projet]</li>
<li>l\'agent  assume-t\'il des fonctions d\'encadrements ? VAR[ENTRETIEN#CREP_encadrement]<br />Si oui préciser le nombre d\'agents et leur répartition par catégorie : VAR[ENTRETIEN#CREP_encadrementA] A - VAR[ENTRETIEN#CREP_encadrementB] B - VAR[ENTRETIEN#CREP_encadrementC] C</li>
</ul>
</td>
</tr>
</tbody>
</table>
<h3>Activités de transfert de compétences ou d’accompagnement des agents</h3>
<table style="width: 726px;">
<tbody>
<tr>
<td style="width: 726px;">VAR[ENTRETIEN#CREF_Champ|CREF;1.1]</td>
</tr>
</tbody>
</table>
<h3>Formation dispensé par l\'agent</h3>
<table style="width: 730px;">
<tbody>
<tr>
<td style="width: 730px;">VAR[ENTRETIEN#CREF_Champs|CREF;1.2]</td>
</tr>
</tbody>
</table>
<h2>2. Bilan des formations suivies sur la période écoulée</h2>
<p>Session réalisée du 1er septembre au 31 aout de l\'année VAR[CAMPAGNE#annee]</p>
<table style="width: 726px;">
<tbody>
<tr>
<td style="width: 716px;">
<p>VAR[ENTRETIEN#CREF_Champs|CREF;2]</p>
<p>VAR[ENTRETIEN#CREF_Champs|CREF;AutresFormations]</p>
</td>
</tr>
</tbody>
</table>
<h2>3. Formations demandées sur la période écoulée et non suivies</h2>
<p>(Formation demandées lors de l\'entretien précédent)</p>
<table style="width: 737px;">
<tbody>
<tr>
<td style="width: 737px;">VAR[ENTRETIEN#CREF_Champs|CREF;3]</td>
</tr>
</tbody>
</table>
<h1>FORMATIONS DEMANDÉES POUR LA NOUVELLE PÉRIODE</h1>
<h2>4. Formation continue</h2>
<p><strong>Type 1 : formations d\'adaptation immédiate au poste de travail</strong><br />Stage d\'adaptation à l\'emploi, de prise de poste après une mutation ou une promotion</p>
<p><strong>Type 2 : formations à l\'évolution des métiers ou des postes de travail</strong><br />Approfondir ses compétences techniques, actualiser ses savoir-faire professionnels, acquérir des fondamentaux ou remettre à niveau ses connaissances pour se préparer à des changements fortement probables, induits par la mise en place d\'une réforme, d\'un nouveau système d\'information ou de nouvelles techniques.</p>
<p><strong>Type 3 : formations d\'acquisition de qualifications nouvelles</strong><br />Favoriser sa culture professionnelle ou son niveau d\'expertise, approfondir ses connaissances dans un domaine qui ne relève pas de son activité actuelle, pour se préparer à de nouvelles fonctions, surmonter des difficultés sur son poste actuel.</p>
<table>
<tbody>
<tr>
<td>VAR[ENTRETIEN#CREF_Champs|CREF;4.1]</td>
</tr>
</tbody>
</table>
<p><strong>Actions de formations demandées par l\'agent et recueillant un avis défavorable du supérieur hiérarchique direct</strong></p>
<table>
<tbody>
<tr>
<td>VAR[ENTRETIEN#CREF_Champs|CREF;4.2]</td>
</tr>
</tbody>
</table>
<p>N.B. : l\'avis défavorable émis par le supérieur hiérarchique direct conduisant l\'entretien ne préjuge pas de la suite donnée à la demande de formation</p>
<h2>5. Formation de préparation à un concours ou examen professionnel</h2>
<p>Pour acquérir les bases et connaissances générales utiles à un concours, dans le cadre de ses perspectives professionnelles pour préparer un changement d\'orientation pouvant impliquer le départ de son ministère ou de la fonction publique</p>
<table style="width: 723px;">
<tbody>
<tr>
<td style="width: 713px;">VAR[ENTRETIEN#CREF_Champs|CREF;5]</td>
</tr>
</tbody>
</table>
<h2>6. Formations pour construire un projet personnel à caractère professionnel</h2>
<p><strong>VAE : Validation des acquis de l\'expérience<br /></strong>Pour obtenir un diplôme, d\'un titre ou d\'une certification inscrite au répertoire national des certifications professionnelles</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;VAE]</td>
</tr>
</tbody>
</table>
<p><strong>Bilan de compétences</strong><br />Pour permettre une mobilité fonctionnelle ou géographique</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;bilan]</td>
</tr>
</tbody>
</table>
<p><strong>Période de professionnalisation<br /></strong>Pour prévenir des risques d\'inadaptation à l\'évolution des méthodes et techniques, pour favoriser l\'accès à des emplois exigeant des compétences nouvelles ou qualifications différentes, pour accéder à un autre corps ou cadre d\'emplois, pour les agents qui reprennent leur activité professionnelle après un congé maternité ou parental.</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;periode]</td>
</tr>
</tbody>
</table>
<p><strong>Congé de formation professionnelle</strong><br />Pour suivre une formation</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;conge]</td>
</tr>
</tbody>
</table>
<p><strong>Entretien de carrière<br /></strong>Pour évaluer son parcours et envisager des possibilités d\'évolution professionnelle à 2~3 ans</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;ecarriere]</td>
</tr>
</tbody>
</table>
<p><strong>Bilan de carrière<br /></strong>Pour renouveler ses perspectives professionnelles à 4~5 ans ou préparer un projet de seconde carrière</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;bcarriere]</td>
</tr>
</tbody>
</table>
<h2>7. Signature du supérieur·e hiérarchique direct·e</h2>
<table style="width: 648px;">
<tbody>
<tr>
<td style="width: 648px;"> Date de l\'entretien : VAR[ENTRETIEN#date]
<p>Date de transmission du compte rendu : </p>
<p>Nom, qualité et signature du responsable hiérarchique :</p>
<p> </p>
<p>VAR[ENTRETIEN#VALIDATION_SUPERIEUR]</p>
</td>
</tr>
</tbody>
</table>
<h2>8. Signature et observation de l\'agent sur son entretien de formation</h2>
<table style="width: 648px;">
<tbody>
<tr>
<td style="width: 648px;">Date de l\'entretien : VAR[ENTRETIEN#date]
<p>Date de transmission du compte rendu : </p>
<p>Nom, qualité et signature du responsable hiérarchique :</p>
<p> </p>
<p> </p>
<p>VAR[ENTRETIEN#VALIDATION_AGENT]</p>
</td>
</tr>
<tr>
<td style="width: 648px;">
<p>Observations :</p>
<p> </p>
<p> </p>
<p> </p>
</td>
</tr>
</tbody>
</table>
<h2>9. Réglementation</h2>
<p>Décret n°2007-1470 du 15 octobre 2007 relatif à la formation professionnelle tout au long de la vie des fonctionnaires de l\'État</p>
<p>Article 5 :</p>
<ul>
<li>Le compte rendu de l\'entretien de formation est établi sous la responsabilité du supérieur hiérarchique.</li>
<li>Les objectifs de formation proposés pour l\'agent y sont inscrits.</li>
<li>Le fonctionnaire en reçoit communication et peut y ajouter ses observations.</li>
<li>Ce compte rendu ainsi qu\'une fiche retraçant les actions de formation auxquelles le fonctionnaire a participé sont versés à son dossier.</li>
<li>Les actions conduites en tant que formateur y figurent également.</li>
<li>Le fonctionnaire est informé par son supérieur hiérarchique des suites données à son entretien de formation.</li>
<li>Les refus opposés aux demandes de formation présentées à l\'occasion de l\'entretien de formation sont motivés.</li>
</ul>', 'body {font-size:9pt;} h1 {font-size: 14pt; color: #154360;} h2 {font-size:12pt; color:#154360;} h3 {font-size: 11pt; color: #154360;}table {border:1px solid black;border-collapse:collapse; width: 100%;} td {border:1px solid black;} th {border:1px solid black; color:#154360;}', 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_VALIDATION_2-OBSERVATION_TRANSMISSION', '<p>Transmission des observations aux responsable d''entretien professionnel</p>', 'mail', 'L''expression des observations de VAR[AGENT#Denomination] sur son entretien professionnel de la campagne VAR[CAMPAGNE#annee]', e'<p>VAR[AGENT#Denomination] vient de valider ses observations pour l\'entretien professionnel de la campagne VAR[CAMPAGNE#annee].</p>
<p><span style="text-decoration: underline;">Observations sur l\'entretien professionnel</span></p>
<p>VAR[ENTRETIEN#ObservationEntretien]</p>
<p><span style="text-decoration: underline;">Observation sur les perspectives</span></p>
<p>VAR[ENTRETIEN#ObservationPerspective]</p>
<p> </p>
<p>Cordialement,<br />EMC2</p>
<p> </p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('RAPPEL_ATTENTE_VALIDATION_AGENT', null, 'mail', 'Entretien·s professionnel·s en attente de votre validation', e'<p>Bonjour VAR[AGENT#Denomination],</p>
<p>Vous êtes un·e agent de l\'Université de Caen Normandie et au moins un entretien professionnel de la campagne VAR[CAMPAGNE#annee] attend votre validation en tant qu\'Agent.<br />Veuillez vous connecter à l\'application EMC2 (VAR[URL#App]) afin de valider ceux-ci.</p>
<p>Bonne journée,<br />L\'équipe EMC2</p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('RAPPEL_ATTENTE_VALIDATION_AUTORITE', null, 'mail', 'Entretien·s professionnel·s en attente de votre validation', e'<p>Bonjour VAR[AGENT#Denomination],<br /><br />Vous êtes l\'autorité hiérarchique d\'au moins un·e agent de l\'Université de Caen Normandie dont l\'entretien professionnel de la campagne VAR[CAMPAGNE#annee] attend votre validation en tant qu\'Autorité hiérarchique.<br />Veuillez vous connecter à l\'application EMC2 (VAR[URL#App]) afin de valider ceux-ci.</p>
<p>Voici la liste des entretiens professionnels en attente :<br />###SERA REMPLACÉ###</p>
<p><br />Bonne journée,<br />L\'équipe EMC2</p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('RAPPEL_ATTENTE_VALIDATION_SUPERIEUR', null, 'mail', 'Entretien·s professionnel·s en attente de votre validation ', '<p><br /><br />Bonjour VAR[AGENT#Denomination],<br /><br />Vous êtes le supérieur·e hiérarchique direct·e d''au moins un·e agent de l''Université de Caen Normandie dont l''entretien professionnel de la campagne VAR[CAMPAGNE#annee] attend votre validation en tant que Supérieur·e hiérarchique direct·e.<br />Veuillez vous connecter à l''application EMC2 (VAR[URL#App]) afin de valider ceux-ci.<br /><br />Voici la liste des entretiens professionnels en attente :<br />###SERA REMPLACÉ###<br /><br /><br />Bonne journée,<br />L''équipe EMC2<br /><br /></p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('NOTIFICATION_RAPPEL_CAMPAGNE_AUTORITE', '<p>Mail envoyé au autorité pour l''avancement de la campagne</p>', 'mail', 'Avancement de la campagne d''entretien professionnel VAR[CAMPAGNE#annee]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p><span style="text-decoration: underline;">Objet :</span> état d\'avancement de la campagne d\'entretien professionnel VAR[CAMPAGNE#annee] en tant qu\'autorité</p>
<p>Bonjour,</p>
<p>Vous recevez ce courrier électronique concernant l\'avancement du ou des entretiens professionnels de la campagne VAR[CAMPAGNE#annee] dont vous avez la responsabilité. Attention cette campagne sera clôturée le VAR[CAMPAGNE#fin].</p>
<p>###A REMPLACER###</p>
<p>Pour gérer vos entretiens professionnels vous pouvez vous rendre dans EMC2.</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences</p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('NOTIFICATION_RAPPEL_CAMPAGNE_SUPERIEUR', '<p>Mail envoyé au supérieurs pour l''avancement de la campagne</p>', 'mail', 'Avancement de la campagne d''entretien professionnel VAR[CAMPAGNE#annee]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p><span style="text-decoration: underline;">Objet :</span> état d\'avancement de la campagne d\'entretien professionnel VAR[CAMPAGNE#annee] en tant que supérieur·e</p>
<p>Bonjour,</p>
<p>Vous recevez ce courrier électronique concernant l\'avancement du ou des entretiens professionnels de la campagne VAR[CAMPAGNE#annee] dont vous avez la responsabilité. Attention cette campagne sera clôturée le VAR[CAMPAGNE#fin].</p>
<p>###A REMPLACER###</p>
<p>Pour gérer vos entretiens professionnels vous pouvez vous rendre dans EMC2.</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences</p>
<p> </p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_VALIDATION_4-AGENT', null, 'mail', 'VAR[AGENT#Denomination] vient de valider son entretien professionnel', e'<p>Bonjour,</p>
<p>VAR[AGENT#Denomination] vient de valider son entretien professionnel pour la campagne VAR[CAMPAGNE#annee].<br />Ceci, clôt son entretien professionnel.</p>
<p>Bonne journée,<br />L\'application EMC2</p>
<p> </p>', null, 'EntretienProfessionnel\Provider\Template');

------------------------------------------------------------------------------------------------------------------------
-- FORMULAIRE DE L'ENTRETIEN PROFESSIONNEL -----------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

-- TODO ICI faire mieux :: plus d'id mais des références

-- CREP ----------------------------------------------------------------------------------------------------------------
INSERT INTO unicaen_autoform_formulaire (id,libelle, code, description) VALUES (1,'Compte-Rendu d''Entretien Professionnel (CREP)', 'CREP', 'Formulaire lié à l''entretien professionnel partie CREP ');
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire) VALUES (22, '1_621f65bb938e6', 'Compléments d''informations', 1, 1);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire) VALUES (1, '1_5cab4652d229b', 'DESCRIPTION DU POSTE OCCUPE PAR L’AGENT', 2, 1);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire) VALUES (2, '1_5cab477d6ebce', 'ÉVALUATION DE L’ANNEE ECOULÉE', 3, 1);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire) VALUES (3, '1_5d1379332f0da', 'VALEUR PROFESSIONNELLE ET MANIERE DE SERVIR DU FONCTIONNAIRE', 4, 1);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire) VALUES (4, '1_5d137a9a599ae', 'ACQUIS DE L’EXPERIENCE PROFESSIONNELLE', 5, 1);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire) VALUES (5, '1_5d137abaa7421', 'OBJECTIFS FIXÉS POUR LA NOUVELLE ANNÉE', 6, 1);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire) VALUES (6, '1_5d137af8278b2', 'PERSPECTIVES D’ÉVOLUTION PROFESSIONNELLE', 7, 1);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire) VALUES (10, '1_5f5a3e8fc5a1f', 'MODALITÉS DE RECOURS', 8, 1);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (1, 1, '1_1_5cab466f19d47', 'Compléments relatif aux missions du poste', '', 1, 'Textarea', null, '', 'CREP;missions;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (2, 1, '1_1_5cab4698af8f9', 'Fonctions d’encadrement ou de conduite de projet :', '', 3, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (3, 1, '1_1_5cab46a9a8177', 'SPACER', '', 2, 'Spacer', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (4, 1, '1_1_5cab46ef716d3', 'l''agent assume des fonctions de conduite de projet', '', 4, 'Checkbox', null, '', 'CREP;projet');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (5, 1, '1_1_5cab470a5fa12', 'l''agent assume des fonctions d''encadrement', '', 5, 'Checkbox', null, '', 'CREP;encadrement');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (6, 1, '1_1_5cab47308ce7e', 'Nombre d’agents encadrés de catégorie A', '', 7, 'Number', null, '', 'CREP;encadrement_A;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (7, 2, '1_2_5cab479a1c8d1', 'Rappel des objectifs d’activités attendus', 'Merci d''indiquer si des démarches ou moyens spécifiques ont été mis en oeuvre pour atteindre ces objectifs', 1, 'Textarea', null, '', 'CREP;2.1;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (8, 2, '1_2_5cab47aedf9d6', 'Événements survenus au cours de la période écoulée', 'Nouvelles orientations, réorganisations, nouvelles méthodes, nouveaux outils, ... ', 2, 'Textarea', null, '', 'CREP;2.2;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (10, 3, '1_3_5d13794397825', 'Critères d’appréciation', '', 1, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (11, 3, '1_3_5d1379525e0fb', 'Compléments à la section compétences professionnelles et technicité', '', 0, 'Textarea', null, '', 'CREP;3.1.1old;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (12, 3, '1_3_5d13795e6ae93', 'Compléments à la section contributions à l’activité du service', '', 3, 'Textarea', null, '', 'CREP;3.1.2old;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (13, 3, '1_3_5d13796aafdec', 'Compléments à la section capacités professionnelles et relationnelles', '', 5, 'Textarea', null, '', 'CREP;3.1.3old;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (14, 3, '1_3_5d137977d1aa2', 'Compléments à la section cas échéant, aptitude à l’encadrement et/ou à la conduite de projets', '', 7, 'Textarea', null, '', 'CREP;3.1.4old;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (15, 3, '1_3_5d1379913541d', 'SPACER', '', 8, 'Spacer', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (16, 3, '1_3_5d1379989b6a2', 'Appréciation générale sur la valeur professionnelle, la manière de servir et la réalisation des objectifs', '', 9, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (17, 3, '1_3_5d1379c58b19d', 'Compétences professionnelles et technicité', '', 11, 'Select', null, 'à acquerir;à développer;maîtrise;expert', 'CREP;3.2.1;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (18, 3, '1_3_5d1379d3c13fb', 'Contribution à l’activité du service', '', 13, 'Select', null, 'à acquerir;à développer;maîtrise;expert', 'CREP;3.2.2;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (19, 3, '1_3_5d1379e00e967', 'Capacités professionnelles et relationnelles', '', 15, 'Select', null, 'à acquerir;à développer;maîtrise;expert', 'CREP;3.2.3;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (20, 3, '1_3_5d1379efc6c51', 'Aptitude à l’encadrement et/ou à la conduite de projets (le cas échéant)', '', 17, 'Select', null, 'à acquerir;à développer;maîtrise;expert', 'CREP;3.2.4;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (21, 3, '1_3_5d137a74c3969', 'SPACER', '', 18, 'Spacer', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (22, 3, '1_3_5d137a7f1e54d', 'Réalisation des objectifs de l’année écoulée', '', 19, 'Textarea', null, '', 'CREP;realisation;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (23, 3, '1_3_5d137a8a5954e', 'Appréciation littérale', '', 20, 'Textarea', null, '', 'CREP;appreciation;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (24, 4, '1_4_5d137aae286e4', 'Autres', '', 2, 'Textarea', null, '', 'CREP;exppro_2');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (25, 5, '1_5_5d137ac5c2f5f', 'Objectifs d''activités attendus', '', 1, 'Textarea', null, '', 'CREP;5.1;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (26, 5, '1_5_5d137ad379d3c', 'Démarche envisagée, et moyens à prévoir dont la formation, pour faciliter l’atteinte des objectifs', '', 2, 'Textarea', null, '', 'CREP;5.2;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (27, 6, '1_6_5d137b04786f3', 'Évolution des activités (préciser l''échéance envisagée)', '', 1, 'Textarea', null, '', 'CREP;6.1;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (28, 6, '1_6_5d137b0c3ad1a', 'Évolution de carrière', '', 2, 'Textarea', null, '', 'CREP;6.2;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (33, 1, '1_1_5ea7f8d6c78fe', 'Nombre d’agents encadrés de catégorie B', '', 8, 'Number', null, '', 'CREP;encadrement_B;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (34, 1, '1_1_5ea7f8e36f50b', 'Nombre d’agents encadrés de catégorie C', '', 9, 'Number', null, '', 'CREP;encadrement_C;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (35, 1, '1_1_5ea7f8f881344', 'SPACER', '', 6, 'Spacer', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (36, 4, '1_4_5ec4f4d8f18c7', 'Missions spécifiques', '', 1, 'Multiple', null, 'Référent formation professionnelle (FC);Référent formation intiale;Membre de jury;Référent assistant prévention;Mandat électif', 'CREP;exppro_1');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (40, 6, '1_6_5f5a3b70e0ea9', 'ATTENTION', 'À compléter obligatoirement pour les agents ayant atteint le dernier échelon de leur grade depuis au moins trois ans au 31/12 de l''année au titre de la présente évaluation, et lorsque la nomination à ce grade ne résulte pas d''un avancement de grade ou d''un accès à celui-ci par concours ou promotion internes (Décret n° 2017-722 du 02/05/2017  relatif aux modalités d''appréciation de la valeur et de l''expérience professionnelles de certains fonctionnaires éligibles à un avancement de grade)', 3, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (41, 3, '1_3_5f5a3e0e540dc', 'ATTENTION', 'L’évaluateur retient, pour apprécier la valeur professionnelle des agents au cours de l''entretien professionnel, les critères annexés à l’arrêté ministériel et qui sont adaptés à la nature des tâches qui leur sont confiées, au niveau de leurs responsabilités et au contexte professionnel. Pour les infirmiers et les médecins seules les parties 2, 3 et 4 doivent être renseignées en tenant compte des limites légales et règlementaires en matière de secret professionnel imposées à ces professionnels', 0, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (42, 10, '1_10_5f5a3ea78845a', 'Recours spécifique (Article 6 du décret n° 2010-888 du 28 juillet 2010)', 'L’agent peut saisir l’autorité hiérarchique d’une demande de révision de son compte rendu d’entretien professionnel. Ce recours hiérarchique doit être exercé dans le délai de 15 jours francs suivant la notification du compte rendu d’entretien professionnel. La réponse de l’autorité hiérarchique doit être notifiée dans un délai de 15 jours francs à compter de la date de réception de la demande de révision du compte rendu de l’entretien professionnel.  A compter de la date de la notification de cette réponse l’agent peut saisir la commission administrative paritaire dans un délai d''un mois. Le recours hiérarchique est le préalable obligatoire à la saisine de la CAP.', 1, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (43, 10, '1_10_5f5a3ec4ad926', 'Recours de droit commun', 'L’agent qui souhaite contester son compte rendu d’entretien professionnel peut exercer un recours de droit commun devant le juge administratif dans les 2 mois suivant la notification du compte rendu de l’entretien professionnel, sans exercer de recours gracieux ou hiérarchique (et sans saisir la CAP) ou après avoir exercé un recours administratif de droit commun (gracieux ou hiérarchique). Il peut enfin saisir le juge administratif à l’issue de la procédure spécifique définie par l’article 6 précité. Le délai de recours contentieux, suspendu durant cette procédure, repart à compter de la notification de la décision finale de l’administration faisant suite à l’avis rendu par la CAP. ', 2, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (72, 3, '1_3_60743c9141737', 'empty', '1. Les compétences professionnelles et technicités : maîtrise technique ou expertise scientifique du domaine d''activité, connaissance de l''environnement professionnel et capacité à s''y situer, qualité d''expression écrite, qualité orale, ...', 10, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (73, 3, '1_3_60743d1605955', 'empty', '2. La contribution à l''activité du service : capacité à partager l''information, à transférer les connaissances et à rendre compte, capacité à s''investir dans des projets, sens du service public et conscience professionnelle, capacité à respecter l''organisation collective du travail, ...', 12, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (74, 3, '1_3_60743d71e5ca5', 'empty', '3. Les capacités professionnelles et relationnelles : autonomie, discernement et sens des initiative dans l''exercice de ses attributions, capacité d''adaptation, capacité à travailler en équipe, ... ', 14, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (75, 3, '1_3_60743dcea4a1d', 'empty', '4. Le cas échéant, aptitude à l''encadrement et/ou à la conduite de projets : capacité d''organisation et de pilotage, aptitude à la conduite de projets, capacité à déléguer, aptitude au dialogue, à la communication et à la négociation, ...', 16, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (76, 3, '1_4_60743e9db5ee9', 'ATTENTION', 'Merci d''apporter un soin particulier à cette appréciation qui constitue un critère pour l''avancement de grade des agents et pourra être repris dans les rapports liés à la promotion de grade.', 21, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (143, 22, '1_22_621f660627418', 'Remarque', 'Les informations suivantes ne sont pas encore disponible dans le référentiel OCTOPUS. Merci de les compléter afin de remonter celle-ci dans le compte-rendu d''entretien professionnel (CREP).', 1, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (146, 22, '1_22_621f66a5a2f7e', 'SPACER', '', 3, 'Spacer', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (147, 22, '1_22_621f66ec87b1e', 'Remarque', 'Toutes les fiches de poste ne sont pas encore présentes dans EMC2. Merci de compléter les informations suivantes si l''agent ou le responsable de l''entretien n''ont pas de fiche de poste EMC2. Sinon laisser vide.', 4, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (148, 22, '1_22_621f670a1f912', 'Intitulé du poste de l''agent', '', 5, 'Text', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (149, 22, '1_22_621f671898bfc', 'Emploi-type de l''agent', '', 6, 'Text', null, '', 'CREP;emploi-type;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (150, 22, '1_22_621f673370344', 'Intitulé du poste du responsable de l''entretien', '', 7, 'Text', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (151, 22, '1_22_621f98213fe4e', 'Date affectation de l''agent', '', 2, 'Text', null, '', 'CREP;affectation_date;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (1003, 3, '1_3_634d6c3912fcd', 'Les compétences professionnelles et technicité', 'Maîtrise technique ou expertise scientifique du domaine d''activité, connaissance de l''environnement professionnel et capacité à s''y situer, qualité d''expression écrite, qualité d''expression orale, ... ', 0, 'Entity Multiple', null, 'EntretienProfessionnel\Entity\Db\CritereCompetence', 'CREP;3.1.1;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (1004, 3, '1_3_5d13795e6ae93', 'La contribution à l’activité du service', 'Capacité à partager l''information, à transférer les connaissances et à rendre compte, capacité à s''invertir dans des projets, sens du service public et conscience professionnelle, capacité à respecter l''organisation collective du travail, ...', 2, 'Entity Multiple', null, 'EntretienProfessionnel\Entity\Db\CritereContribution', 'CREP;3.1.2;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (1005, 3, '1_3_5d137977d1aa2', 'Le cas échéant, aptitude à l’encadrement et/ou à la conduite de projets', 'Capacité d''organisation et de pilotage, aptitude à la conduite de projets, capacité à déléguer, aptitude au dialogue, à la communication et à la négociation, ... ', 6, 'Entity Multiple', null, 'EntretienProfessionnel\Entity\Db\CritereEncadrement', 'CREP;3.1.4;');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (1006, 3, '1_3_5d13796aafdec', 'Les capacités professionnelles et relationnelles', 'Autonomie, discernement et sens des initiatives dans l''exercice de ses attributions, capacité d''adaptation, capacité à travailler en équipe, etc.', 4, 'Entity Multiple', null, 'EntretienProfessionnel\Entity\Db\CriterePersonnelle', 'CREP;3.1.3;');

-- CREF ----------------------------------------------------------------------------------------------------------------
INSERT INTO unicaen_autoform_formulaire (id, libelle, code, description) VALUES (2,'Compte-Rendu d''Entretien de formation (CREF)', 'CREF', 'Formulaire lié à l''entretien professionnel partie CREF ');
INSERT INTO unicaen_autoform_categorie (id, code, libelle, mots_clefs, ordre, formulaire) VALUES (23, '2_62289f96346ec', 'Informations complémentaires', null, 1, 2);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, mots_clefs, ordre, formulaire) VALUES (15, '2_60744315c477c', 'Activités de transfert de compétences ou d''accompagnement des agents', null, 2, 2);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, mots_clefs, ordre, formulaire) VALUES (21, '2_6218b5ee5b3e5', 'Bilan des formations suivies sur la période écoulée', 'CREF;Bilan', 3, 2);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, mots_clefs, ordre, formulaire) VALUES (16, '2_6074464c768c6', 'Formations demandées sur la période écoulée et non suivies', null, 4, 2);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, mots_clefs, ordre, formulaire) VALUES (17, '2_607447155a1f3', 'Formation continue (demandée pour la nouvelle période)', null, 5, 2);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, mots_clefs, ordre, formulaire) VALUES (18, '2_60744aa24dcab', 'Formation de préparation à un concours ou examen professionnel', null, 6, 2);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, mots_clefs, ordre, formulaire) VALUES (19, '2_60744b5f4443d', 'Formations pour construire un projet personnel à caractère professionnel', null, 7, 2);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, mots_clefs, ordre, formulaire) VALUES (20, '2_60744ea79edce', 'Règlementation', null, 11, 2);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (128, 17, '2_17_60755072d7b3e', 'Formation 3', '', 7, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (130, 17, '2_17_6075509516253', 'Formation 5', '', 9, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (135, 17, '2_17_6075516c4fe19', 'Formation 4', '', 15, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 'CREF;6;conge');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (136, 17, '2_17_6075518c15cb4', 'Formation 5', '', 16, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (132, 17, '2_17_607551207d6f6', 'Formation 1', '', 12, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 'CREF;6;bcarriere');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (107, 19, '2_19_60744d51db3cd', 'Entretien de carrière', 'Pour évaluer son parcours et envisager des possibilités d''évolution professionnelle à 2~3 ans', 9, 'Label', null, '', 'CREF;6;ecarriere');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (109, 19, '2_19_60744dbef393b', 'Bilan de carrière', 'Pour renouveler ses perspectives professionnelles à 4~5 ans ou préparer un projet de seconde carrière ', 11, 'Label', null, '', 'CREF;6;bcarriere');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (105, 19, '2_19_60744d0f0925f', 'Congé de formation professionnelle', 'Pour suivre une formation', 7, 'Label', null, '', 'CREF;6;conge');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (152, 23, '2_23_62289ff6f20b6', 'Remarque', 'Les informations suivantes ne sont pas encore disponible dans le référentiel OCTOPUS. Merci de les compléter afin de remonter celle-ci dans le compte-rendu de formation (CREF).', 1, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (1001, 21, '2_21_627d2a0187a25', 'Autres formations', '', 7, 'Textarea', null, '', 'CREF;AutresFormations');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (77, 15, '2_15_6074438447701', 'Activités ', '', 1, 'Multiple', null, 'Formateur;Tuteur/Mentor;Président/Vice-Président de jury;Membre de jury', 'CREF;1.1');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (79, 15, '2_15_60744459b46fe', 'Formations dispensées', 'Préciser les activités de formation encadrée par l''agent hors des activités de sa fiche de poste.', 2, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (100, 19, '2_19_60744bd69fb44', 'Bilan de compétences', 'Pour permettre une mobilité fonctionnelle ou géographique', 3, 'Label', null, '', 'CREF;6;bilan');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (157, 23, '2_23_6228a12b4f737', 'Date du dernier entretien professionnel', '', 6, 'Text', null, '', 'CREF;precedent');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (153, 23, '2_23_6228a0589d84b', 'Solde du CPF', '', 2, 'Text', null, '', 'CREF;CPF_solde');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (131, 17, '2_17_607550eeb08f5', 'S', '', 10, 'Spacer', null, '', 'CREF;6;ecarriere');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (104, 19, '2_19_60744cee1b307', 'empty', '', 6, 'Textarea', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (106, 19, '2_19_60744d1729cb0', 'empty', '', 8, 'Textarea', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (83, 16, '2_16_6074468e37a47', 'Formations demandées lors de l''entretien précédent', '', 1, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (101, 19, '2_19_60744c902899b', 'Période de professionnalisation', 'Pour prévenir des risques d''inadaptation à l''évolution des méthodes et techniques, pour favoriser l''accès à des emplois exigeant des compétences nouvelles ou qualifications différentes, pour accéder à un autre corps ou cadre d''emplois, pour les agents qui reprennent leur activité professionnelle après un congé maternité ou parental.', 5, 'Label', null, '', 'CREF;6;periode');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (108, 19, '2_19_60744d68d6786', 'empty', '', 10, 'Textarea', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (110, 19, '2_19_60744dceac729', 'empty', '', 12, 'Textarea', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (155, 23, '2_23_6228a0c290261', 'SPACER', '', 4, 'Spacer', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (137, 21, '2_21_6218b697c42ef', 'Sessions réalisées du 1er septembre au 31 août de l''année de la campagne en cours', '', 1, 'Label', null, '', 'CREF;6;VAE');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (156, 23, '2_23_6228a0db1e483', 'Remarque', 'Tous les agents n''ont pas participé à la campagne précédente d''entretien professionnel sur EMC2. Pour ceux-ci veuillez compléter les informations suivantes.', 5, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (94, 17, '2_17_607449d5be974', 'Actions de formations demandées par l''agent et recueillant un avis défavorable du supérieur hiérarchique direct', 'N.B. : l''avis défavorable émis par le supérieur hiérarchique direct conduisant l''entretien ne préjuge pas de la suite donnée à la demande de formation', 11, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (118, 16, '2_16_60754015d1010', 'Formation 5', '', 6, 'Multiple Text', null, 'Action de formation;Nombre d''heures', 'CREF;3');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (116, 16, '2_16_60753ff8a5d43', 'Formation 3', '', 4, 'Multiple Text', null, 'Action de formation;Nombre d''heures', 'CREF;3');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (88, 17, '2_17_607448d43441d', 'Type 3 : formations d''acquisition de qualifications nouvelles', 'Favoriser sa culture professionnelle ou son niveau d''expertise, approfondir ses connaissances dans un domaine qui ne relève pas de son activité actuelle, pour se préparer à de nouvelles fonctions, surmonter des difficultés sur son poste actuel.', 3, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (93, 17, '2_17_6074499de3661', 'SPACER', '', 4, 'Spacer', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (113, 16, '2_16_60753fb0ec093', 'Formation 1', '', 2, 'Multiple Text', null, 'Action de formation;Nombre d''heures', 'CREF;3');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (129, 17, '2_17_6075508684e5a', 'Formation 4', '', 8, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (122, 15, '2_15_607548067e991', 'Formation 4', '', 6, 'Multiple_champs_paramètrables', null, 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 'CREF;1.2');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (121, 15, '2_15_607547f62955e', 'Formation 3', '', 5, 'Multiple_champs_paramètrables', null, 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 'CREF;1.2');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (119, 15, '2_15_6075478d8c15d', 'Formation 1', '', 3, 'Multiple_champs_paramètrables', null, 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 'CREF;1.2');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (123, 15, '2_15_6075481869150', 'Formation 5', '', 7, 'Multiple_champs_paramètrables', null, 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 'CREF;1.2');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (120, 15, '2_15_607547e8d6b8b', 'Formation 2', '', 4, 'Multiple_champs_paramètrables', null, 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 'CREF;1.2');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (99, 19, '2_19_60744bb9411f9', 'VAE : Validation des acquis de l''expérience', 'Pour obtenir un diplôme, d''un titre ou d''une certification inscrite au répertoire national des certifications professionnelles', 1, 'Label', null, '', 'CREF;6;VAE');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (98, 18, '2_18_60744b38c89a6', 'Libellé des formations', '', 2, 'Textarea', null, '', 'CREF;5');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (117, 16, '2_16_6075400784143', 'Formation 4', '', 5, 'Multiple Text', null, 'Action de formation;Nombre d''heures', 'CREF;3');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (138, 21, '2_21_6218b75939b77', 'Formation 1', '', 2, 'Multiple_champs_paramètrables', null, 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (111, 20, '2_20_60744f2c924a7', 'empty', 'Décret n°2007-1470 du 15 octobre 2007 relatif à la formation professionnelle tout au long de la vie des fonctionnaires de l''État <br> Article 5 :  <ul><li>  Le compte rendu de l''entretien de formation est établi sous la responsabilité du supérieur  hiérarchique.  </li><li>  Les objectifs de formation proposés pour l''agent y sont inscrits.  </li><li>  Le fonctionnaire en reçoit communication et peut y ajouter ses observations.  </li><li>  Ce compte rendu ainsi qu''une fiche retraçant les actions de formation auxquelles le fonctionnaire a participé sont versés à son dossier.  </li><li>  Les actions conduites en tant que formateur y figurent également.  </li><li>  Le fonctionnaire est informé par son supérieur hiérarchique des suites données à son entretien de formation.  </li><li>  Les refus opposés aux demandes de formation présentées à l''occasion de l''entretien de formation sont motivés. </li></ul>', 1, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (97, 18, '2_18_60744afe9f52a', 'empty', 'Pour acquérir les bases et connaissances générales utiles à un concours, dans le cadre de ses perspectives professionnelles pour préparer un changement d''orientation pouvant impliquer le départ de son ministère ou de la fonction publique', 1, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (125, 17, '2_17_60754efa821b5', 'Formation 1', '', 5, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', 'CREF;4.1');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (87, 17, '2_17_60744863be8e2', 'Type 2 : formations à l''évolution des métiers ou des postes de travail', 'Approfondir ses compétences techniques, actualiser ses savoir-faire professionnels, acquérir des fondamentaux ou remettre à niveau ses connaissances pour se préparer à des changements fortement probables, induits par la mise en place d''une réforme, d''un nouveau système d''information ou de nouvelles techniques.', 2, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (103, 19, '2_19_60744cdce435b', 'empty', '', 4, 'Textarea', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (86, 17, '2_17_607447d31b71e', 'Type 1 : formations d''adaptation immédiate au poste de travail', 'Stage d''adaptation à l''emploi, de prise de poste après une mutation ou une promotion', 1, 'Label', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (102, 19, '2_19_60744cc11f86e', 'empty', '', 2, 'Textarea', null, '', null);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (139, 21, '2_21_6218b76610c37', 'Formation 2', '', 3, 'Multiple_champs_paramètrables', null, 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 'CREF;2');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (141, 21, '2_21_6218b78c7739e', 'Formation 4', '', 5, 'Multiple_champs_paramètrables', null, 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 'CREF;2');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (115, 16, '2_16_60753fe993b28', 'Formation 2', '', 3, 'Multiple Text', null, 'Action de formation;Nombre d''heures', 'CREF;3');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (133, 17, '2_17_6075514d57c98', 'Formation 2', '', 13, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 'CREF;4.2');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (140, 21, '2_21_6218b77fb5e18', 'Formation 3', '', 4, 'Multiple_champs_paramètrables', null, 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 'CREF;2');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (126, 17, '2_17_6075500f85b1a', 'Formation 2', '', 6, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', 'CREF;4.1');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (142, 21, '2_21_6218b799a5357', 'Formation 5', '', 6, 'Multiple_champs_paramètrables', null, 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 'CREF;2');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (154, 23, '2_23_6228a0ae78c65', 'L''agent envisage-t''il de mobiliser son CPF cette année', '', 3, 'Select', null, 'Oui;Non', 'CREF;CPF_mobilisation');
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs) VALUES (134, 17, '2_17_60755158e867c', 'Formation 3', '', 14, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 'CREF;6;bilan');

------------------------------------------------------------------------------------------------------------------------
-- EVENEMENT -----------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion) VALUES
    ('rappel_entretienpro', 'Rappel de l''entretien professionnel', 'Rappel à J-4 de l''entretien professionnel', 'entretien', null),
    ('rappel_campagne', 'Rappel de l''avancement de la campagne', 'Mail envoyé périodiquement lors de la campagne aux responsables de structures pour leur rappeler l''état d''avancement de la campagne', 'campagne', 'P4W'),
    ('rappel_pas_observation_entretienpro', 'Rappel en cas où il n''y a pas d''observation', 'Rappel en cas où il n''y a pas d''observation', 'entretien', null),
    ('rappel_campagne_autorite', 'Rappel avancement campagne EP [Autorité hiérachique]', 'Rappel avancement campagne EP [Autorité hiérachique]', 'campagne', 'P4W'),
    ('rappel_campagne_superieur', 'Rappel avancement campagne EP [Supérieur·e hiérachique]', 'Rappel avancement campagne EP [Supérieur·e hiérachique]', 'campagne', 'P2W');

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGES ----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('campagne', 'Gestion des campagnes d''entretiens professionnels', 1050, 'EntretienProfessionnel\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'campagne_afficher', 'Afficher les campagnes', 10 UNION
    SELECT 'campagne_ajouter', 'Ajouter une campagne', 20 UNION
    SELECT 'campagne_modifier', 'Modifier une campagne', 30 UNION
    SELECT 'campagne_historiser', 'Historiser une campagne', 40 UNION
    SELECT 'campagne_detruire', 'Supprimer une campagne', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'campagne';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('entretienpro', 'Gestion des entretiens professionnels', 1000, 'EntretienProfessionnel\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'entretienpro_index', 'Afficher l''index des entretiens professionnels', 0 UNION
    SELECT 'entretienpro_mesentretiens', 'Menu --Mes entretiens pro--', 1 UNION
    SELECT 'entretienpro_convoquer', 'Convoquer ou modifier une convocation', 5 UNION
    SELECT 'entretienpro_afficher', 'Afficher les entretiens professionnels', 10 UNION
    SELECT 'entretienpro_exporter', 'Exporter un entretien (CREP, CREF)', 15 UNION
    SELECT 'entretienpro_modifier', 'Modifier un entretien professionnel', 30 UNION
    SELECT 'entretienpro_historiser', 'Historiser/restaurer un entretien professionnel', 40 UNION
    SELECT 'entretienpro_detruire', 'Supprimer un entretien professionnel', 50 UNION
    SELECT 'entretienpro_valider_agent', 'Valider en tant qu''Agent', 900 UNION
    SELECT 'entretienpro_valider_responsable', 'Valider en tant que Responsable', 910 UNION
    SELECT 'entretienpro_valider_drh', 'Valider en tant que DRH', 920 UNION
    SELECT 'entretienpro_valider_observation', 'valider_observation', 921
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'entretienpro';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('observation', 'Gestion des observation d''entretien professionnel', 1010, 'EntretienProfessionnel\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'observation_afficher', 'Afficher une observation', 10 UNION
    SELECT 'observation_ajouter', 'Ajouter une observation ', 20 UNION
    SELECT 'observation_modifier', 'Modifier une observation ', 30 UNION
    SELECT 'observation_historiser', 'Historiser/Restaurer une observation ', 40 UNION
    SELECT 'observation_supprimer', 'Supprimer une observation ', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'observation';

--TODO check si cela sert encore
INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('sursis', 'Gestion des sursis d''entretien professionnel', 1020, 'EntretienProfessionnel\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'sursis_afficher', 'Afficher un sursis', 10 UNION
    SELECT 'sursis_ajouter', 'Ajouter un sursis', 20 UNION
    SELECT 'sursis_modifier', 'Modifier une sursis', 30 UNION
    SELECT 'sursis_historiser', 'Historiser/Restaurer un sursis', 40 UNION
    SELECT 'sursis_supprimer', 'Supprimer un sursis', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'sursis';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('agentforcesansobligation', 'Gestion des agent·es forcé·es sans obligation d''entretien professionnel', 'EntretienProfessionnel\Provider\Privilege', 5000);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'agentforcesansobligation_index', 'Accéder à l''index', 10 UNION
    SELECT 'agentforcesansobligation_afficher', 'Afficher', 20 UNION
    SELECT 'agentforcesansobligation_ajouter', 'Ajouter', 30 UNION
    SELECT 'agentforcesansobligation_modifier', 'Modifier', 40 UNION
    SELECT 'agentforcesansobligation_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'agentforcesansobligation_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'agentforcesansobligation';




