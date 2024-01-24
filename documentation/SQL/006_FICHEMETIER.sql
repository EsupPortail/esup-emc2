-- Date de MAJ 22/11/2023 ----------------------------------------------------------------------------------------------
-- Script avant version 4.1.2 ------------------------------------------------------------------------------------------
-- Color scheme : B66D35  ----------------------------------------------------------------------------------------------

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

create table fichemetier
(
    id                    serial
        constraint fiche_type_metier_pkey
            primary key,
    metier_id             integer   not null
        constraint fichetype_metier__fk
            references metier_metier,
    expertise             boolean default false,
    raison                text,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint fichemetier_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp not null,
    histo_modificateur_id integer   not null,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
);

create table fichemetier_application
(
    fichemetier_id         integer not null
        constraint fichemetier_application_fichemetier_id_fk
            references fichemetier
            on delete cascade,
    application_element_id integer not null
        constraint fichemetier_application_application_element_id_fk
            references element_application_element
            on delete cascade,
    constraint fichemetier_application_pk
        primary key (fichemetier_id, application_element_id)
);

create table fichemetier_competence
(
    fichemetier_id        integer not null
        constraint fichemetier_competence_fichemetier_id_fk
            references fichemetier
            on delete cascade,
    competence_element_id integer not null
        constraint fichemetier_competence_competence_element_id_fk
            references element_competence_element
            on delete cascade,
    constraint fichemetier_competence_pk
        primary key (fichemetier_id, competence_element_id)
);

create table fichemetier_mission
(
    id             serial
        constraint fichemetier_activite_pkey
            primary key,
    fichemetier_id integer           not null
        constraint fichemetier_activite_fichemetier_id_fk
            references fichemetier
            on delete cascade,
    mission_id     integer           not null
        constraint fichemetier_activite_missionprincipale_id_fk
            references missionprincipale
            on delete cascade,
    ordre          integer default 0 not null
);
create unique index fichemetier_activite_id_uindex on fichemetier_mission (id);

create table fichemetier_etat
(
    fichemetier_id integer not null
        constraint fichemetier_etat_fichemetier_id_fk
            references fichemetier
            on delete cascade,
    etat_id        integer not null
        constraint fichemetier_etat_etat_id_fk
            references unicaen_etat_instance
            on delete cascade,
    constraint fichemetier_etat_pk
        primary key (fichemetier_id, etat_id)
);

create table fichemetier_thematique_type
(
    id                    serial                  not null
        constraint fichemetier_thematique_type_pk
            primary key,
    code                  varchar(256)            not null
        constraint fichemetier_thematique_type_pk_2
            unique,
    libelle               varchar(1024),
    description           text,
    obligatoire           bool      default false not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint fichemetier_thematique_type_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint fichemetier_thematique_type_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint fichemetier_thematique_type_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);

create table fichemetier_thematique_element
(
    id                    serial                  not null
        constraint fichemetier_thematique_element_pk
            primary key,
    fichemetier_id        integer                 not null
        constraint fichemetier_thematique_element_fichemetier_id_fk
            references fichemetier
            on delete cascade,
    thematiquetype_id     integer                 not null
        constraint fme_fichemetier_thematique_type_id_fk
            references fichemetier_thematique_type
            on delete cascade,
    complement            text,
    niveau_id             integer
        constraint fichemetier_thematique_element_element_niveau_id_fk
            references element_niveau
            on delete set null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint fichemetier_thematique_element_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint fichemetier_thematique_element_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint fichemetier_thematique_element_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
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
            ('FICHE_METIER#CONNAISSANCES', null, 'fichemetier', 'getConnaissances');

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

