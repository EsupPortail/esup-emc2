-- Date de MAJ 23/11/2023 ----------------------------------------------------------------------------------------------
-- Script avant version 4.1.2 ------------------------------------------------------------------------------------------
-- Color scheme : 6B2A64 et   --------------------------------------------------------------------------------------

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
-- Partie suivie des prestations CCC - TODO statuer sur le fait de garder ou pas ---------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table agent_ccc_ppp
(
    id                     serial
        constraint agent_ppp_pk
            primary key,
    agent_id               varchar(64)   not null
        constraint agent_ppp_agent_c_individu_fk
            references agent
            on delete cascade,
    type                   varchar(1024) not null,
    libelle                varchar(1024) not null,
    complement             text,
    date_debut             timestamp,
    date_fin               timestamp,
    etat_id                integer
        constraint agent_ppp_unicaen_etat_etat_id_fk
            references unicaen_etat_instance
            on delete set null,
    formation_cpf          double precision,
    formation_cout         double precision,
    formation_prisencharge double precision,
    formation_organisme    varchar(1024),
    histo_creation         timestamp     not null,
    histo_createur_id      integer       not null
        constraint agent_ppp_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification     timestamp,
    histo_modificateur_id  integer
        constraint agent_ppp_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction      timestamp,
    histo_destructeur_id   integer
        constraint agent_ppp_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);
create unique index agent_ppp_id_uindex on agent_ccc_ppp (id);

create table agent_ccc_stageobs
(
    id                    serial
        constraint agent_stageobs_pk
            primary key,
    agent_id              varchar(64) not null
        constraint agent_stageobs_agent_c_individu_fk
            references agent
            on delete cascade,
    structure_id          integer,
    metier_id             integer
        constraint agent_stageobs_metier_id_fk
            references metier_metier
            on delete set null,
    complement            text,
    date_debut            timestamp,
    date_fin              timestamp,
    etat_id               integer
        constraint agent_stageobs_unicaen_etat_etat_id_fk
            references unicaen_etat_instance
            on delete set null,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint agent_stageobs_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_stageobs_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_stageobs_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);
create unique index agent_stageobs_id_uindex on agent_ccc_stageobs (id);

create table agent_ccc_tutorat
(
    id                    serial
        constraint agent_tutorat_pk
            primary key,
    agent_id              varchar(64) not null
        constraint agent_tutorat_agent_c_individu_fk
            references agent
            on delete cascade,
    cible_id              varchar(64)
        constraint agent_tutorat_agent_c_individu_fk_2
            references agent
            on delete set null,
    metier_id             integer
        constraint agent_tutorat_metier_id_fk
            references metier_metier
            on delete set null,
    date_debut            timestamp,
    date_fin              timestamp,
    complement            text,
    formation             boolean,
    etat_id               integer
        constraint agent_tutorat_unicaen_etat_etat_id_fk
            references unicaen_etat_instance
            on delete set null,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint agent_tutorat_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_tutorat_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_tutorat_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);
create unique index agent_tutorat_id_uindex on agent_ccc_tutorat (id);

create table agent_ccc_accompagnement
(
    id                    serial
        constraint agent_accompagnement_pk
            primary key,
    agent_id              varchar(64) not null
        constraint agent_accompagnement_agent_c_individu_fk
            references agent
            on delete cascade,
    cible_id              varchar(64)
        constraint agent_accompagnement_agent_c_individu_fk_2
            references agent
            on delete set null,
    bap_id                integer,
    corps_id              integer,
    complement            text,
    resultat              boolean,
    etat_id               integer
        constraint agent_accompagnement_unicaen_etat_etat_id_fk
            references unicaen_etat_instance
            on delete set null,
    date_debut            timestamp,
    date_fin              timestamp,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint agent_accompagnement_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_accompagnement_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_accompagnement_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);
create unique index agent_accompagnement_id_uindex on agent_ccc_accompagnement (id);

-- ---------------------------------------------------------------------------------------------------------------------
-- TABLE élements liés à la carrière -----------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table agent_carriere_affectation
(
    id                    bigint                                                             not null
        constraint agent_affectation_pk
            primary key,
    agent_id              varchar(40)                                                        not null,
    structure_id          bigint                                                             not null,
    date_debut            timestamp                                                          not null,
    date_fin              timestamp,
    t_principale          varchar(1)   default 'N'::character varying,
    source_id             varchar(128),
    id_orig               varchar(255),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint,
    t_hierarchique        varchar(1)   default 'O'::character varying,
    t_fonctionnelle       varchar(1)   default 'N'::character varying,
    quotite               integer
);

create table agent_carriere_echelon
(
    id                    bigint                         not null
        constraint agent_carriere_echelon_pk
            primary key,
    agent_id              varchar(40)                    not null
        constraint agent_carriere_echelon_agent_c_individu_fk
            references agent
            on delete cascade,
    echelon               integer                        not null,
    d_debut               date                           not null,
    d_fin                 date,
    source_id             varchar(128),
    id_orig               varchar(100),
    created_on            timestamp default CURRENT_DATE not null,
    updated_on            timestamp,
    deleted_on            timestamp,
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table agent_carriere_quotite
(
    id                    bigint                                                             not null
        constraint agent_quotite_pk
            primary key,
    agent_id              varchar(40)                                                        not null
        constraint agent_carriere_quotite_agent_c_individu_fk
            references agent
            on delete cascade,
    quotite               integer,
    d_debut               timestamp,
    d_fin                 timestamp,
    source_id             varchar(128),
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table agent_carriere_grade
(
    id                    bigint                                                             not null
        constraint agent_grade_pk
            primary key,
    agent_id              varchar(40)                                                        not null,
    structure_id          integer,
    grade_id              integer,
    corps_id              integer,
    correspondance_id     integer,
    d_debut               timestamp,
    d_fin                 timestamp,
    source_id             varchar(128),
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint,
    emploitype_id         integer
);

create table agent_carriere_statut
(
    id                    bigint                                                             not null
        constraint agent_statut_pk
            primary key,
    agent_id              varchar(40)                                                        not null,
    structure_id          integer,
    grade_id              integer,
    corps_id              integer,
    bap_id                integer,
    d_debut               timestamp,
    d_fin                 timestamp,
    source_id             varchar(128),
    id_orig               varchar(100),
    t_titulaire           varchar(1)                                                         not null,
    t_cdi                 varchar(1)                                                         not null,
    t_cdd                 varchar(1)                                                         not null,
    t_vacataire           varchar(1)                                                         not null,
    t_enseignant          varchar(1)                                                         not null,
    t_administratif       varchar(1)                                                         not null,
    t_chercheur           varchar(1)                                                         not null,
    t_doctorant           varchar(1)                                                         not null,
    t_detache_in          varchar(1)                                                         not null,
    t_detache_out         varchar(1)                                                         not null,
    t_dispo               varchar(1)                                                         not null,
    t_heberge             varchar(1)                                                         not null,
    t_emerite             varchar(1)                                                         not null,
    t_retraite            varchar(1)                                                         not null,
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table agent_carriere_mobilite
(
    id                    serial
        constraint agent_carriere_mobilite_pk
            primary key,
    agent_id              varchar(40)             not null
        constraint agent_carriere_mobilite_agent_c_individu_fk
            references agent,
    mobilite_id           integer                 not null
        constraint agent_carriere_mobilite_carriere_mobilite_type_id_fk
            references carriere_mobilite,
    commentaire           text,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint agent_carriere_mobilite_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_carriere_mobilite_unicaen_utilisateur_user_id_fk2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_carriere_mobilite_unicaen_utilisateur_user_id_fk3
            references unicaen_utilisateur_user
);

-- ---------------------------------------------------------------------------------------------------------------------
-- TABLE -- Élements liés aux poste et aux missions spécifiques --------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table agent_poste
(
    id         serial
        constraint agent_poste_pk
            primary key,
    agent_id   varchar(40)             not null
        constraint agent_poste_agent_c_individu_fk
            references agent,
    code_poste varchar(128)            not null,
    intitule   varchar(1024)           not null,
    created_on timestamp default now() not null,
    updated_on timestamp,
    deleted_on timestamp,
    source_id  varchar(128),
    id_source  varchar(256)
);

create table agent_missionspecifique
(
    id                    serial
        constraint agent_missionspecifique_pk
            primary key,
    agent_id              varchar(40) not null,
    mission_id            integer     not null
        constraint agent_missionspecifique_mission_specifique_id_fk
            references mission_specifique
            on delete cascade,
    structure_id          integer,
    date_debut            timestamp,
    date_fin              timestamp,
    commentaire           varchar(2048),
    decharge              double precision,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint agent_missionspecifique_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_missionspecifique_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_missionspecifique_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index agent_missionspecifique_id_uindex on agent_missionspecifique (id);


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

