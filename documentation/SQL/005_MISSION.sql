-- Date de MAJ 21/11/2023 ----------------------------------------------------------------------------------------------
-- Script avant version 4.1.2 ------------------------------------------------------------------------------------------
-- Color scheme : Green et 48794F --------------------------------------------------------------------------------------

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


-- ---------------------------------------------------------------------------------------------------------------------
-- TABLE - MISSION SPECIFIQUE ------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table mission_specifique_theme
(
    id                    serial
        constraint mission_specifique_theme_pk
            primary key,
    libelle               varchar(256) not null,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint mission_specifique_theme_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint mission_specifique_theme_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint mission_specifique_theme_destructeur_fk
            references unicaen_utilisateur_user
);

create unique index mission_specifique_theme_id_uindex on mission_specifique_theme (id);

create table mission_specifique_type
(
    id                    serial
        constraint mission_specifique_type_pk
            primary key,
    libelle               varchar(256) not null,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint mission_specifique_type_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint mission_specifique_type_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint mission_specifique_type_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index mission_specifique_type_id_uindex on mission_specifique_type (id);

create table mission_specifique
(
    id                    serial
        constraint mission_specifique_pk
            primary key,
    libelle               varchar(256) not null,
    theme_id              integer
        constraint mission_specifique_mission_specifique_theme_id_fk
            references mission_specifique_theme
            on delete set null,
    type_id               integer
        constraint mission_specifique_mission_specifique_type_id_fk
            references mission_specifique_type
            on delete set null,
    description           text,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint mission_specifique_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint mission_specifique_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint mission_specifique_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index mission_specifique_id_uindex on mission_specifique (id);

-- ---------------------------------------------------------------------------------------------------------------------
-- TABLE - MISSION PRINCIPALE ------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table missionprincipale_activite
(
    id                    serial
        constraint activite_description_pk
            primary key,
    mission_id            integer   not null,
    libelle               text      not null,
    ordre                 integer,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint activite_description_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint activite_description_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint activite_description_user_id_fk
            references unicaen_utilisateur_user
);
create unique index activite_description_id_uindex on missionprincipale_activite (id);

create table missionprincipale
(
    id                    serial
        constraint missionprincipale_pk
            primary key,
    libelle               varchar(1024),
    niveau_id             integer
        constraint missionprincipale_carriere_niveau_enveloppe_id_fk
            references carriere_niveau_enveloppe
            on delete cascade,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint missionprincipale_unicaen_utilisateur_user_id_fk3
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint missionprincipale_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint missionprincipale_unicaen_utilisateur_user_id_fk2
            references unicaen_utilisateur_user
);

create table missionprincipale_application
(
    mission_id             integer not null
        constraint missionprincipale_application_missionprincipale_id_fk
            references missionprincipale
            on delete cascade,
    application_element_id integer not null
        constraint activite_application_application_element_id_fk
            references element_application_element
            on delete cascade,
    constraint activite_application_pk
        primary key (mission_id, application_element_id)
);

create table missionprincipale_competence
(
    mission_id            integer not null
        constraint missionprincipale_competence_missionprincipale_id_fk
            references missionprincipale
            on delete cascade,
    competence_element_id integer not null
        constraint activite_competence_competence_element_id_fk
            references element_competence_element
            on delete cascade,
    constraint activite_competence_pk
        primary key (mission_id, competence_element_id)
);

create table missionprincipale_domaine
(
    mission_id integer not null
        constraint missionprincipale_domaine_missionprincipale_id_fk
            references missionprincipale
            on delete cascade,
    domaine_id integer not null
        constraint activite_domaine_domaine_id_fk
            references metier_domaine
            on delete cascade,
    constraint activite_domaine_pk
        primary key (mission_id, domaine_id)
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
-- Macros et templates -------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name)
    VALUES ('MISSION_SPECIFIQUE#LIBELLE', '<p>Retourne le libell&eacute; d''une mission sp&eacute;cifique</p>', 'mission', 'getLibelle'),
           ('MISSION_SPECIFIQUE#DESCRIPTION', '<p>Affiche la description d''une mission sp&eacute;cifique ou&nbsp; ''Aucune description fournie pour cette mission sp&eacute;cifique'' si la description est manquante</p>', 'mission', 'toStringDescription')
;

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace)
VALUES ('MISSION_SPECIFIQUE_LETTRE', '<p>Lettre type associée à une mission spécifique d''un agent</p>', 'pdf', 'Mission_specifique_-_VAR[MISSION_SPECIFIQUE_AFFECTATION#ID]_-_VAR[AGENT#Denomination]', e'<h1>Justificatif de mission spécifique</h1>
<p> </p>
<p>L\'agent <strong>VAR[AGENT#Denomination]</strong> assure la mission spécifique <strong>VAR[MISSION_SPECIFIQUE#LIBELLE]</strong> dans le structure <strong>VAR[STRUCTURE#LIBELLE]</strong>.</p>
<ul>
<li>Il assume cette mission spécifique pour la période <strong>VAR[MISSION_SPECIFIQUE_AFFECTATION#PERIODE]</strong>.</li>
<li>Cette mission spécique permet une décharge horaire (<strong>VAR[MISSION_SPECIFIQUE_AFFECTATION#DECHARGE]</strong>)</li>
</ul>
<p>Ce document fait preuve.</p>', null, 'Application\Provider\Template');

-- ---------------------------------------------------------------------------------------------------------------------
-- Privileges ----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('missionprincipale', 'Gestion des missions principales', 800, 'FicheMetier\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionprincipale_index', 'Accéder à l''index', 10 UNION
    SELECT 'missionprincipale_afficher', 'Afficher', 20 UNION
    SELECT 'missionprincipale_ajouter', 'Ajouter', 30 UNION
    SELECT 'missionprincipale_modifier', 'Modifier', 40 UNION
    SELECT 'missionprincipale_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'missionprincipale_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionprincipale';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('missionspecifique', 'Gestion des missions spécifiques', 650, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionspecifique_index', 'Gestion - Affichage de l''index des missions specifiques', 100 UNION
    SELECT 'missionspecifique_afficher', 'Gestion - Afficher une mission spécifique', 110 UNION
    SELECT 'missionspecifique_ajouter', 'Gestion - Ajouter une mission spécifiques', 120 UNION
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
    SELECT 'missionspecifiquetype_afficher', 'Gestion - Afficher un type de mission spécifique', 110 UNION
    SELECT 'missionspecifiquetype_ajouter', 'Gestion - Ajouter un type de mission spécifique', 120 UNION
    SELECT 'missionspecifiquetype_modifier', 'Gestion - Modifier un type de mission spécifique', 130 UNION
    SELECT 'missionspecifiquetype_historiser', 'Gestion - Historiser/restaurer un type de mission spécifique', 140 UNION
    SELECT 'missionspecifiquetype_detruire', 'Gestion - Détruire un type de missions spécifique', 150
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionspecifiquetype';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('activite', 'Gestion des missions principales', 625, 'Application\Provider\Privilege');
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

-- ---------------------------------------------------------------------------------------------------------------------
-- MACRO ---------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

-- MACRO des missions spécifiques
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('MISSIONSPECIFIQUE#Type', '<p>Retourne le type de la mission spécifique</p>', 'missionspecifique', 'toStringType'),
    ('MISSIONSPECIFIQUE#Theme', '<p>Retourne le thème d''une mission spécifique</p>', 'missionspecifique', 'toStringTheme'),
    ('MISSIONSPECIFIQUE#Description', '<p>Retourne le descriptif d''une mission spécifique</p>', 'missionspecifique', 'toStringDescription'),
    ('MISSIONSPECIFIQUE#Libelle', '<p>Retourne le libellé d''une mission spécifique</p>', 'missionspecifique', 'toStringLibelle');
