-- Date de MAJ 21/11/2023 ----------------------------------------------------------------------------------------------
-- Script avant version 4.1.2 ------------------------------------------------------------------------------------------
-- Color scheme : 70625D -----------------------------------------------------------------------------------------------

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
-- TABLE - Famille profesionnelle et domaine ---------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table metier_familleprofessionnelle
(
    id                    serial
        constraint metier_famille_pk
        primary key,
    libelle               varchar(128)            not null,
    couleur               varchar(64),
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint famille_professionnelle_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint famille_professionnelle_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint famille_professionnelle_user_id_fk_3
        references unicaen_utilisateur_user
);
create unique index metier_famille_id_uindex on metier_familleprofessionnelle (id);

create table metier_domaine
(
    id                    serial
        constraint domaine_pk
            primary key,
    libelle               varchar(256)            not null,
    type_fonction         varchar(256),
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint domaine_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint domaine_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint domaine_user_id_fk_3
            references unicaen_utilisateur_user
);

create unique index domaine_id_uindex on metier_domaine (id);

create table metier_domaine_famille
(
    domaine_id integer not null
        constraint metier_domaine_famille_metier_domaine_id_fk
            references metier_domaine,
    famille_id integer not null
        constraint metier_domaine_famille_metier_familleprofessionnelle_id_fk
            references metier_familleprofessionnelle,
    constraint metier_domaine_famille_pk
        primary key (domaine_id, famille_id)
);

-- ---------------------------------------------------------------------------------------------------------------------
-- TABLE - Métier et domaine -------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table metier_metier
(
    id                    serial
        constraint metier_pkey
            primary key,
    libelle_default       varchar(256)            not null,
    niveau                integer,
    libelle_feminin       varchar(256),
    libelle_masculin      varchar(256),
    categorie_id          integer
        constraint metier_categorie_id_fk
            references carriere_categorie
            on delete set null,
    niveaux_id            integer
        constraint metier_niveau_enveloppe_id_fk
            references carriere_niveau_enveloppe
            on delete set null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint metier_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint metier_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint metier_user_id_fk_3
            references unicaen_utilisateur_user
);
create unique index metier_id_uindex on metier_metier (id);

create table metier_metier_domaine
(
    metier_id  integer not null
        constraint metier_domaine_metier_id_fk
            references metier_metier
            on delete cascade,
    domaine_id integer not null
        constraint metier_domaine_domaine_id_fk
            references metier_domaine
            on delete cascade,
    constraint metier_domaine_pk
        primary key (metier_id, domaine_id)
);

-- ---------------------------------------------------------------------------------------------------------------------
-- TABLE - REFERENTIEL ET REFERENCE ------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table metier_referentiel
(
    id                    serial
        constraint metier_referentiel_pk
            primary key,
    libelle_court         varchar(256)  not null,
    libelle_long          varchar(1024) not null,
    prefix                varchar(1024) not null,
    type                  varchar(255),
    histo_creation        timestamp     not null,
    histo_createur_id     integer       not null
        constraint metier_referentiel_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp     not null,
    histo_modificateur_id integer       not null
        constraint metier_referentiel_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint metier_referentiel_user_id_fk_3
            references unicaen_utilisateur_user
);
create unique index metier_referentiel_id_uindex on metier_referentiel (id);

create table metier_reference
(
    id                    serial
        constraint metier_reference_pk
            primary key,
    metier_id             integer      not null
        constraint metier_reference_metier_id_fk
            references metier_metier
            on delete cascade,
    referentiel_id        integer      not null
        constraint metier_reference_metier_referentiel_id_fk
            references metier_referentiel
            on delete cascade,
    code                  varchar(256) not null,
    lien                  varchar(1024),
    page                  integer,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint metier_reference_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp    not null,
    histo_modificateur_id integer      not null
        constraint metier_reference_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint metier_reference_user_id_fk_3
            references unicaen_utilisateur_user
);
create unique index metier_reference_id_uindex on metier_reference (id);

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

----------------------------------------------------------------------------------------
-- PRIVILEGE ---------------------------------------------------------------------------
----------------------------------------------------------------------------------------

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
VALUES ('metier', 'Gestion des métiers', 551, 'Metier\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'metier_index', 'Afficher l''index des métiers', 10 UNION
    SELECT 'metier_afficher', 'Afficher un métier', 20 UNION
    SELECT 'metier_ajouter', 'Ajouter un métier', 30 UNION
    SELECT 'metier_modifier', 'Modifier un métier', 40 UNION
    SELECT 'metier_historiser', 'Historiser/Réstaurer un métier', 50 UNION
    SELECT 'metier_supprimer', 'Supprimer un métier', 60 UNION
    SELECT 'metier_cartographie', 'Extraire la cartographie', 100 UNION
    SELECT 'metier_lister_agents', 'Lister les agents', 110
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
 JOIN unicaen_privilege_categorie cp ON cp.CODE = 'metier';

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