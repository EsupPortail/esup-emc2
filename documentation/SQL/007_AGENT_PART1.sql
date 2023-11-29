-- Date de MAJ 23/11/2023 ----------------------------------------------------------------------------------------------
-- Script avant version 4.1.2 ------------------------------------------------------------------------------------------
-- Color scheme : BLue et 37495D  --------------------------------------------------------------------------------------

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
-- Entité de base ------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table agent
(
    c_individu            varchar(40)                                                        not null
        constraint agent_pk
        primary key,
    utilisateur_id        integer
        constraint agent_user_id_fk
        references unicaen_utilisateur_user
        on delete set null,
    prenom                varchar(64),
    nom_usage             varchar(64),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    octo_id               varchar(40),
    preecog_id            varchar(40),
    harp_id               integer,
    login                 varchar(256),
    email                 varchar(1024),
    sexe                  varchar(1),
    t_contrat_long        varchar(1),
    date_naissance        date,
    nom_famille           varchar(256),
    id                    bigint,
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint,
    source_id             varchar(128),
    id_orig               varchar(100)
);

-- ---------------------------------------------------------------------------------------------------------------------
-- TABLE - Hiérarchie  -------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table agent_hierarchie_superieur
(
    id                    serial
        constraint agent_superieur_pk
        primary key,
    agent_id              varchar(40)             not null
        constraint agent_superieur_agent_c_individu_fk
        references agent,
    superieur_id          varchar(40)             not null
        constraint agent_superieur_agent_c_individu_fk2
        references agent,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint agent_superieur___fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_superieur_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_superieur_unicaen_utilisateur_user_id_fk2
        references unicaen_utilisateur_user
);
create index agent_superieur_agent_id_index on agent_hierarchie_superieur (agent_id);
create index agent_superieur_superieur_id_index on agent_hierarchie_superieur (superieur_id);

create table agent_hierarchie_autorite
(
    id                    serial
        constraint agent_autorite_pk
        primary key,
    agent_id              varchar(40)             not null
        constraint agent_autorite_agent_c_individu_fk
        references agent,
    autorite_id           varchar(40)             not null
        constraint agent_autorite_agent_c_individu_fk2
        references agent,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint agent_autorite___fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_autorite_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_autorite_unicaen_utilisateur_user_id_fk2
        references unicaen_utilisateur_user
);
create index agent_autorite_agent_id_index on agent_hierarchie_autorite (agent_id);
create index agent_autorite_autorite_id_index on agent_hierarchie_autorite (autorite_id);

-- ---------------------------------------------------------------------------------------------------------------------
-- TABLE - Acquis ------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------


create table agent_element_application
(
    agent_id               varchar(40) not null
        constraint agent_application_agent_c_individu_fk
            references agent
            on delete cascade,
    application_element_id integer     not null
        constraint agent_application_application_element_id_fk
            references element_application_element
            on delete cascade,
    constraint agent_application_pk
        primary key (agent_id, application_element_id)
);


create table agent_element_competence
(
    agent_id              varchar(40) not null
        constraint agent_competence_agent_c_individu_fk
            references agent
            on delete cascade,
    competence_element_id integer     not null
        constraint agent_competence_competence_element_id_fk
            references element_competence_element
            on delete cascade,
    constraint agent_competence_pk
        primary key (agent_id, competence_element_id)
);

-- create table agent_element_formation
-- (
--     agent_id             varchar(40) not null
--         constraint agent_formation_agent_c_individu_fk
--             references agent
--             on delete cascade,
--     formation_element_id integer     not null
--         constraint agent_formation_formation_element_id_fk
--             references formation_element
--             on delete cascade,
--     constraint agent_formation_pk
--         primary key (agent_id, formation_element_id)
-- );

-- ---------------------------------------------------------------------------------------------------------------------
-- TABLE - Autre -------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table agent_fichier
(
    agent   varchar(40) not null
        constraint agent_fichier_agent_c_individu_fk
            references agent
            on delete set null,
    fichier varchar(13) not null
        constraint agent_fichier_fichier_fk
            references fichier_fichier
            on delete cascade,
    constraint agent_fichier_pk
        primary key (agent, fichier)
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
-- ROLES ---------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto, parent_id, ldap_filter, accessible_exterieur, description)
VALUES ('Agent', 'Agent', true, true, null, null, true, null);
