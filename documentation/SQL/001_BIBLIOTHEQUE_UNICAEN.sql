-- Date de MAJ 22/11/2023 ----------------------------------------------------------------------------------------------
-- Script avant version 4.1.2 ------------------------------------------------------------------------------------------
-- Color scheme : Violet et 654D70  ------------------------------------------------------------------------------------

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
--  TABLE UNICAEN UTILISATEUR ------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table unicaen_utilisateur_role
(
    id                   serial
        primary key,
    role_id              varchar(64)                not null,
    libelle              varchar(255)               not null,
    is_default           boolean      default false not null,
    is_auto              boolean      default false not null,
    parent_id            integer
        constraint fk_unicaen_utilisateur_role_parent
            references unicaen_utilisateur_role deferrable,
    ldap_filter          varchar(255) default NULL::character varying,
    accessible_exterieur boolean      default true  not null,
    description          text
);
create unique index un_unicaen_utilisateur_role_role_id on unicaen_utilisateur_role (role_id);
create index ix_unicaen_utilisateur_role_parent on unicaen_utilisateur_role (parent_id);

create table unicaen_utilisateur_user
(
    id                   serial
        primary key,
    username             varchar(255)                                          not null
        constraint un_unicaen_utilisateur_user_username
            unique,
    display_name         varchar(255)                                          not null,
    email                varchar(255),
    password             varchar(128) default 'application'::character varying not null,
    state                boolean      default true                             not null,
    password_reset_token varchar(256)
        constraint un_unicaen_utilisateur_user_password_reset_token
            unique,
    last_role_id         integer
        constraint fk_unicaen_utilisateur_user_last_role
            references unicaen_utilisateur_role
            deferrable
);
create index ix_unicaen_utilisateur_user_last_role on unicaen_utilisateur_user (last_role_id);

create table unicaen_utilisateur_role_linker
(
    user_id integer not null
        constraint fk_unicaen_utilisateur_role_linker_user
            references unicaen_utilisateur_user
            deferrable,
    role_id integer not null
        constraint fk_unicaen_utilisateur_role_linker_role
            references unicaen_utilisateur_role
            deferrable,
    constraint pk_unicaen_utilisateur_role_linker
        primary key (user_id, role_id)
);
create index ix_unicaen_utilisateur_role_linker_user on unicaen_utilisateur_role_linker (user_id);
create index ix_unicaen_utilisateur_role_linker_role on unicaen_utilisateur_role_linker (role_id);

-- ---------------------------------------------------------------------------------------------------------------------
--  TABLE UNICAEN PRIVILEGE --------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table unicaen_privilege_categorie
(
    id        serial
        primary key,
    code      varchar(150) not null,
    libelle   varchar(200) not null,
    namespace varchar(255),
    ordre     integer default 0
);
create unique index un_unicaen_privilege_categorie_code on unicaen_privilege_categorie (code);

create table unicaen_privilege_privilege
(
    id           serial
        primary key,
    categorie_id integer      not null
        constraint fk_unicaen_privilege_categorie
            references unicaen_privilege_categorie
            deferrable,
    code         varchar(150) not null,
    libelle      varchar(200) not null,
    ordre        integer default 0
);
create unique index un_unicaen_privilege_code on unicaen_privilege_privilege (categorie_id, code);
create index ix_unicaen_privilege_categorie on unicaen_privilege_privilege (categorie_id);

create table unicaen_privilege_privilege_role_linker
(
    role_id      integer not null
        constraint fk_unicaen_role_privilege_linker_role
            references unicaen_utilisateur_role
            on delete cascade
            deferrable,
    privilege_id integer not null
        constraint fk_unicaen_role_privilege_linker_privilege
            references unicaen_privilege_privilege
            on delete cascade
            deferrable,
    constraint pk_unicaen_role_privilege_linker
        primary key (role_id, privilege_id)
);
create index ix_unicaen_role_privilege_linker_role on unicaen_privilege_privilege_role_linker (role_id);
create index ix_unicaen_role_privilege_linker_privilege on unicaen_privilege_privilege_role_linker (privilege_id);

-- ---------------------------------------------------------------------------------------------------------------------
--  TABLE UNICAEN PARAMETRE --------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table unicaen_parametre_categorie
(
    id          serial
        constraint unicaen_parametre_categorie_pk
            primary key,
    code        varchar(1024) not null,
    libelle     varchar(1024) not null,
    ordre       integer default 9999,
    description text
);

create table unicaen_parametre_parametre
(
    id                serial
        constraint unicaen_parametre_parametre_pk
            primary key,
    categorie_id      integer       not null
        constraint unicaen_parametre_parametre_unicaen_parametre_categorie_id_fk
            references unicaen_parametre_categorie,
    code              varchar(1024) not null,
    libelle           varchar(1024) not null,
    description       text,
    valeurs_possibles text,
    valeur            text,
    ordre             integer default 9999
);

-- ---------------------------------------------------------------------------------------------------------------------
--  TABLE UNICAEN MAIL -------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table unicaen_mail_mail
(
    id                     serial
        constraint umail_pkey
            primary key,
    date_envoi             timestamp    not null,
    status_envoi           varchar(256) not null,
    destinataires          text         not null,
    destinataires_initials text,
    copies                 text,
    sujet                  text,
    corps                  text,
    mots_clefs             text,
    log                    text
);
create unique index ummail_id_uindex on unicaen_mail_mail (id);

-- ---------------------------------------------------------------------------------------------------------------------
--  TABLE UNICAEN RENDERER ---------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table unicaen_renderer_macro
(
    id            serial
        constraint unicaen_document_macro_pk
            primary key,
    code          varchar(256) not null,
    description   text,
    variable_name varchar(256) not null,
    methode_name  varchar(256) not null
);
create unique index unicaen_document_macro_code_uindex on unicaen_renderer_macro (code);
create unique index unicaen_document_macro_id_uindex on unicaen_renderer_macro (id);

create table unicaen_renderer_template
(
    id             serial
        constraint unicaen_content_content_pk
            primary key,
    code           varchar(256) not null,
    description    text,
    document_type  varchar(256) not null,
    document_sujet text         not null,
    document_corps text         not null,
    document_css   text,
    namespace      varchar(1024)
);
create unique index unicaen_content_content_code_uindex on unicaen_renderer_template (code);
create unique index unicaen_content_content_id_uindex on unicaen_renderer_template (id);
create unique index unicaen_document_rendu_id_uindex on unicaen_renderer_template (id);

create table unicaen_renderer_rendu
(
    id              serial
        constraint unicaen_document_rendu_pk
            primary key,
    template_id     integer
        constraint unicaen_document_rendu_template_id_fk
            references unicaen_renderer_template
            on delete set null,
    date_generation timestamp not null,
    sujet           text      not null,
    corps           text      not null
);

-- ---------------------------------------------------------------------------------------------------------------------
--  TABLE UNICAEN ETAT -------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table unicaen_etat_categorie
(
    id      serial
        primary key,
    code    varchar(256) not null,
    libelle varchar(256) not null,
    icone   varchar(256),
    couleur varchar(256),
    ordre   integer default 9999
);
create unique index unicaen_etat_categorie_id_uindex on unicaen_etat_categorie (id);

create table unicaen_etat_type
(
    id           serial
        primary key,
    code         varchar(256)         not null,
    libelle      varchar(256)         not null,
    categorie_id integer
        references unicaen_etat_categorie,
    icone        varchar(256),
    couleur      varchar(256),
    ordre        integer default 9999 not null
);
create unique index unicaen_etat_type_id_uindex on unicaen_etat_type (id);

create table unicaen_etat_instance
(
    id                    serial
        primary key,
    type_id               integer                 not null
        references unicaen_etat_type,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        references unicaen_utilisateur_user,
    complement            text,
    infos                 text
);
create unique index unicaen_etat_instance_id_index on unicaen_etat_instance (id);

-- ---------------------------------------------------------------------------------------------------------------------
--  TABLE UNICAEN VALIDATION -------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table unicaen_validation_type
(
    id                    serial
        constraint unicaen_validation_type_pk
            primary key,
    code                  varchar(256)            not null,
    libelle               varchar(1024)           not null,
    refusable             boolean   default true  not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint unicaen_validation_type_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint unicaen_validation_type_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint unicaen_validation_type_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index unicaen_validation_type_id_uindex on unicaen_validation_type (id);

create table unicaen_validation_instance
(
    id                    serial
        constraint unicaen_validation_instance_pk
            primary key,
    type_id               integer               not null
        constraint unicaen_validation_instance_unicaen_validation_type_id_fk
            references unicaen_validation_type
            on delete cascade,
    entity_class          varchar(1024),
    entity_id             varchar(64),
    histo_creation        timestamp             not null,
    histo_createur_id     integer               not null
        constraint unicaen_validation_instance_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp             not null,
    histo_modificateur_id integer               not null
        constraint unicaen_validation_instance_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint unicaen_validation_instance_destructeur_fk
            references unicaen_utilisateur_user,
    justification         text,
    refus                 boolean default false not null
);
create unique index unicaen_validation_instance_id_uindex on unicaen_validation_instance (id);

-- ---------------------------------------------------------------------------------------------------------------------
--  TABLE UNICAEN AIDE -------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table unicaen_aide_glossaire_definition
(
    id           serial
        constraint unicaen_glossaire_definition_pk
            primary key,
    terme        varchar(1024)         not null,
    definition   text                  not null,
    alternatives text,
    historisee   boolean default false not null
);
create unique index unicaen_glossaire_definition_id_uindex on unicaen_aide_glossaire_definition (id);
create unique index unicaen_glossaire_definition_terme_uindex on unicaen_aide_glossaire_definition (terme);

create table unicaen_aide_faq_question
(
    id         serial
        constraint unicaen_faq_question_pk
            primary key,
    question   varchar(4096) not null,
    reponse    text          not null,
    historisee boolean default false,
    ordre      integer
);
create unique index unicaen_faq_question_id_uindex on unicaen_aide_faq_question (id);

create table unicaen_aide_documentation_lien
(
    id          serial
        constraint unicaen_aide_documentation_lien_pk
            primary key,
    texte       varchar(1024),
    lien_texte  varchar(1024) not null,
    lien_url    varchar(1024) not null,
    description text,
    ordre       integer,
    historisee  boolean default false,
    role_ids    varchar(4096)
);
create unique index unicaen_aide_documentation_lien_id_uindex on unicaen_aide_documentation_lien (id);

-- ---------------------------------------------------------------------------------------------------------------------
--  TABLE UNICAEN AUTOFORM ---------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table unicaen_autoform_formulaire
(
    id                    serial
        constraint autoform_formulaire_pk
            primary key,
    libelle               varchar(128)            not null,
    description           varchar(2048),
    code                  varchar(256),
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint autoform_formulaire_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint autoform_formulaire_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint autoform_formulaire_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index autoform_formulaire_id_uindex on unicaen_autoform_formulaire (id);

create table unicaen_autoform_categorie
(
    id                    serial
        constraint autoform_categorie_pk
            primary key,
    code                  varchar(64)             not null,
    libelle               varchar(256)            not null,
    ordre                 integer   default 10000 not null,
    formulaire            integer                 not null
        constraint autoform_categorie_formulaire_fk
            references unicaen_autoform_formulaire,
    mots_clefs            varchar(1024),
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint autoform_categorie_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint autoform_categorie_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint autoform_categorie_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index autoform_categorie_code_uindex on unicaen_autoform_categorie (code);
create unique index autoform_categorie_id_uindex on unicaen_autoform_categorie (id);

create table unicaen_autoform_champ
(
    id                    serial
        constraint autoform_champ_pk
            primary key,
    categorie             integer                 not null
        constraint autoform_champ_categorie_fk
            references unicaen_autoform_categorie,
    code                  varchar(64)             not null,
    libelle               varchar(1024)           not null,
    texte                 text                    not null,
    ordre                 integer   default 10000 not null,
    element               varchar(64),
    balise                boolean,
    options               varchar(1024),
    mots_clefs            varchar(1024),
    obligatoire           boolean   default false not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint autoform_champ_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint autoform_champ_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint autoform_champ_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index autoform_champ_id_uindex on unicaen_autoform_champ (id);

create table unicaen_autoform_formulaire_instance
(
    id                    serial
        constraint autoform_formulaire_instance_pk
            primary key,
    formulaire            integer   not null
        constraint autoform_formulaire_instance_autoform_formulaire_id_fk
            references unicaen_autoform_formulaire,
    histo_creation        timestamp not null,
    histo_createur_id     integer
        constraint autoform_formulaire_instance_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp not null,
    histo_modificateur_id integer   not null
        constraint autoform_formulaire_instance_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint autoform_formulaire_instance_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index autoform_formulaire_instance_id_uindex on unicaen_autoform_formulaire_instance (id);

create table unicaen_autoform_formulaire_reponse
(
    id                    serial
        constraint autoform_reponse_pk
            primary key,
    instance              integer   not null
        constraint autoform_formulaire_reponse_instance_fk
            references unicaen_autoform_formulaire_instance
            on delete cascade,
    champ                 integer   not null
        constraint autoform_reponse_champ_fk
            references unicaen_autoform_champ
            on delete cascade,
    reponse               text,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint autoform_reponse_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp not null,
    histo_modificateur_id integer   not null
        constraint autoform_reponse_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint autoform_reponse_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index autoform_reponse_id_uindex on unicaen_autoform_formulaire_reponse (id);

-- ---------------------------------------------------------------------------------------------------------------------
--  TABLE UNICAEN EVENEMENT --------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table unicaen_evenement_etat
(
    id          serial
        constraint pk_evenement_etat
            primary key,
    code        varchar(255) not null
        constraint un_evenement_etat_code
            unique
                deferrable initially deferred,
    libelle     varchar(255) not null,
    description varchar(2047)
);

create table unicaen_evenement_type
(
    id          serial
        constraint pk_evenement_type
            primary key,
    code        varchar(255) not null
        constraint un_evenement_type_code
            unique
                deferrable initially deferred,
    libelle     varchar(255) not null,
    description varchar(2047),
    parametres  varchar(2047),
    recursion   varchar(64)
);

create table unicaen_evenement_instance
(
    id                 serial
        constraint pk_evenement_instance
            primary key,
    nom                varchar(255)  not null,
    description        varchar(1024) not null,
    type_id            integer       not null
        constraint fk_evenement_instance_type
            references unicaen_evenement_type
            deferrable,
    etat_id            integer       not null
        constraint fk_evenement_instance_etat
            references unicaen_evenement_etat
            deferrable,
    parametres         text,
    date_creation      timestamp     not null,
    date_planification timestamp     not null,
    date_traitement    timestamp,
    log                text,
    parent_id          integer
        constraint fk_evenement_instance_parent
            references unicaen_evenement_instance
            deferrable,
    date_fin           timestamp,
    mots_clefs         text
);
create index ix_evenement_instance_type on unicaen_evenement_instance (type_id);
create index ix_evenement_instance_etat on unicaen_evenement_instance (etat_id);
create index ix_evenement_instance_parent on unicaen_evenement_instance (parent_id);

create table unicaen_evenement_journal
(
    id             serial
        constraint unicaen_evenement_journal_pk
            primary key,
    date_execution timestamp not null,
    log            text,
    etat_id        integer
        constraint unicaen_evenement_journal_unicaen_evenement_etat_id_fk
            references unicaen_evenement_etat
            on delete set null
);
create unique index unicaen_evenement_journal_id_uindex on unicaen_evenement_journal (id);

-- ---------------------------------------------------------------------------------------------------------------------
--  TABLE UNICAEN INDICATEUR -------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------
create table unicaen_indicateur_categorie
(
    id          serial
        constraint unicaen_indicateur_categorie_pk
            primary key,
    code        varchar(256)
        constraint unicaen_indicateur_categorie_pk_2
            unique,
    libelle     varchar(1024)        not null,
    ordre       integer default 9999 not null,
    description text
);

create table unicaen_indicateur_indicateur
(
    id                       serial
        constraint indicateur_pk
            primary key,
    titre                    varchar(256)  not null,
    description              varchar(2048),
    requete                  varchar(4096) not null,
    dernier_rafraichissement timestamp,
    view_id                  varchar(256),
    entity                   varchar(256),
    namespace                varchar(1024),
    code                     varchar(256)
        constraint unicaen_indicateur_indicateur_pk
            unique,
    nb_elements              integer,
    categorie_id             integer
        constraint uii_unicaen_indicateur_categorie_id_fk
            references unicaen_indicateur_categorie
);
create unique index indicateur_id_uindex
    on unicaen_indicateur_indicateur (id);



create table unicaen_indicateur_abonnement
(
    id serial constraint abonnement_pk primary key,
    user_id integer constraint indicateur_abonnement_user_id_fk references unicaen_utilisateur_user on delete cascade,
    indicateur_id integer constraint indicateur_abonnement_indicateur_definition_id_fk references unicaen_indicateur_indicateur on delete cascade,
    frequence     varchar(256),
    dernier_envoi timestamp
);

create unique index abonnement_id_uindex on unicaen_indicateur_abonnement (id);

create table unicaen_indicateur_tableaudebord
(
    id serial constraint unicaen_indicateur_tableaudebord_pk primary key,
    titre varchar(1024) default 'Tableau de bord' not null,
    description text,
    namespace varchar(256),
    nb_column   integer       default 1                 not null
);

create table unicaen_indicateur_tableau_indicateur
(
    tableau_id    integer not null
        constraint unicaen_indicateur_tableau_indicateur_tableaudebord_null_fk
            references unicaen_indicateur_tableaudebord (id)
            on delete cascade,
    indicateur_id integer not null
        constraint unicaen_indicateur_tableau_indicateur_indicateur_null_fk
            references unicaen_indicateur_indicateur (id)
            on delete cascade,
    constraint unicaen_indicateur_tableau_indicateur_pk
        primary key (tableau_id, indicateur_id)
);

-- ---------------------------------------------------------------------------------------------------------------------
-- UNICAEN FICHIER -----------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table fichier_nature
(
    id          serial
        constraint fichier_nature_pk
            primary key,
    code        varchar(64)  not null,
    libelle     varchar(256) not null,
    description varchar(2048)
);
create unique index fichier_nature_code_uindex on fichier_nature (code);
create unique index fichier_nature_id_uindex on fichier_nature (id);

create table fichier_fichier
(
    id                    varchar(13)  not null
        constraint fichier_fichier_pk
            primary key,
    nom_original          varchar(256) not null,
    nom_stockage          varchar(256) not null,
    nature                integer      not null
        constraint fichier_fichier_fichier_nature_id_fk
            references fichier_nature,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null,
    histo_modification    timestamp    not null,
    histo_modificateur_id integer      not null,
    histo_destruction     timestamp,
    histo_destructeur_id  integer,
    type_mime             varchar(256) not null,
    taille                varchar(256)
);
create unique index fichier_fichier_id_uindex on fichier_fichier (id);
create unique index fichier_fichier_nom_stockage_uindex on fichier_fichier (nom_stockage);

-- UNICAEN OBSERVATION -----------------------------

create table unicaen_observation_observation_type
(
    id                    serial                  not null
        constraint unicaen_observation_observation_type_pk
            primary key,
    code                  varchar(256)            not null
        constraint unicaen_observation_observation_type_pk_2
            unique,
    libelle               varchar(1024)           not null,
    categorie             varchar(1024),
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint uot_unicaen_utilisateur_user_id_fk_1
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint uot_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint uot_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);

create table unicaen_observation_observation_instance
(
    id          serial  not null
        constraint unicaen_observation_observation_instance_pk
            primary key,
    type_id     integer not null
        constraint uoi_observation_type_id_fk
            references unicaen_observation_observation_type,
    observation text    not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint uoi_unicaen_utilisateur_user_id_fk_1
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint uoi_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint uoi_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);

create table unicaen_observation_observation_validation
(
    observation_instance_id integer not null
        constraint uov_observation_observation_id_fk
            references unicaen_observation_observation_instance
            on delete cascade,
    validation_id  integer
        constraint uov_unicaen_validation_instance_id_fk
            references unicaen_validation_instance
            on delete cascade,
    constraint uov_observation_validation_pk
        primary key (observation_instance_id, validation_id)
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
--  INSERT ROLE ET UTILISATEUR PAR DEFAUT ------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto, parent_id, ldap_filter, accessible_exterieur, description)
VALUES
    ('Administrateur·trice technique', 'Administrateur·trice technique', false, false, null, null, true, null),
    ('Administrateur·trice fonctionnel·le', 'Administrateur·trice fonctionnel·le', false, false, null, null, true, null),
    ('Observateur·trice', 'Observateur·trice', false, false, null, null, true, null),
    ('Directeur·trice des ressources humaines', 'Directeur·trice des ressources humaines', false, false, null, null, true, null)
;

INSERT INTO unicaen_utilisateur_user (id, username, display_name, email, password, state, password_reset_token, last_role_id)
VALUES
    (0, 'EMC2', 'EMC2', null, 'null', false, null, null)
;

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN UTILISATEUR -------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('utilisateur', 'Gestion des utilisateurs', 20010, 'UnicaenUtilisateur\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'utilisateur_afficher',      'Afficher un utilisateur',                      10 UNION
    SELECT 'utilisateur_ajouter',       'Ajouter/Supprimer un utilisateur',             20 UNION
    SELECT 'utilisateur_changerstatus', 'Changer le statut d''un d''utilisateur',       30 UNION
    SELECT 'utilisateur_modifierrole',  'Modifier les rôles associés à un utilisateur', 40 UNION
    SELECT 'utilisateur_rechercher',    'Rechercher',                                   100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'utilisateur';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('role', 'Gestion des rôles', 20010, 'UnicaenUtilisateur\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'role_afficher', 'Afficher les rôles', 10 UNION
    SELECT 'role_modifier', 'Modifier un rôle', 20 UNION
    SELECT 'role_effacer', 'Effacer un rôle', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'role';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN PRIVILEGE ---------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('privilege', 'Gestion des privilèges', 10000, 'UnicaenPrivilege\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'privilege_voir', 'Afficher les privilèges', 10 UNION
    SELECT 'privilege_ajouter', 'Ajouter un privilège', 20 UNION
    SELECT 'privilege_modifier', 'Modifier un privilège', 30 UNION
    SELECT 'privilege_supprimer', 'Supprimer un privilège', 40 UNION
    SELECT 'privilege_affecter', 'Affecter un privilège', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'privilege'
;

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN PARAMETRE ---------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('parametrecategorie', 'UnicaenParametre - Gestion des catégories de paramètres', 70000, 'UnicaenParametre\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'parametrecategorie_index', 'Affichage de l''index des paramètres', 10 UNION
    SELECT 'parametrecategorie_afficher', 'Affichage des détails d''une catégorie', 20 UNION
    SELECT 'parametrecategorie_ajouter', 'Ajouter une catégorie de paramètre', 30 UNION
    SELECT 'parametrecategorie_modifier', 'Modifier une catégorie de paramètre', 40 UNION
    SELECT 'parametrecategorie_supprimer', 'Supprimer une catégorie de paramètre', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'parametrecategorie';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('parametre', 'UnicaenParametre - Gestion des paramètres', 70001, 'UnicaenParametre\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'parametre_afficher', 'Afficher un paramètre', 10 UNION
    SELECT 'parametre_ajouter', 'Ajouter un paramètre', 20 UNION
    SELECT 'parametre_modifier', 'Modifier un paramètre', 30 UNION
    SELECT 'parametre_supprimer', 'Supprimer un paramètre', 50 UNION
    SELECT 'parametre_valeur', 'Modifier la valeur d''un parametre', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'parametre';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN MAIL --------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('mail', 'UnicaenMail - Gestion des mails', 9000, 'UnicaenMail\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'mail_index', 'Affichage de l''index', 10 UNION
    SELECT 'mail_afficher', 'Afficher un mail', 20 UNION
    SELECT 'mail_reenvoi', 'Ré-envoi d''un mail', 30 UNION
    SELECT 'mail_supprimer', 'Suppression d''un mail', 40 UNION
    SELECT 'mail_test', 'Envoi d''un mail de test', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'mail';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN RENDERER ---------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('documenttemplate', 'UnicaenRenderer - Gestion des templates', 11020, 'UnicaenRenderer\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documenttemplate_index', 'Afficher l''index des contenus', 1 UNION
    SELECT 'documenttemplate_afficher', 'Afficher un template', 10 UNION
    SELECT 'documenttemplate_ajouter', 'Ajouter un contenu', 15 UNION
    SELECT 'documenttemplate_modifier', 'Modifier un contenu', 20 UNION
    SELECT 'documenttemplate_supprimer', 'Supprimer un contenu', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documenttemplate';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('documentmacro', 'UnicaenRenderer - Gestion des macros', 11010, 'UnicaenRenderer\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documentmacro_index', 'Afficher l''index des macros', 1 UNION
    SELECT 'documentmacro_ajouter', 'Ajouter une macro', 10 UNION
    SELECT 'documentmacro_modifier', 'Modifier une macro', 20 UNION
    SELECT 'documentmacro_supprimer', 'Supprimer une macro', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documentmacro';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('documentcontenu', 'UnicaenRenderer - Gestion des contenus', 11030, 'UnicaenRenderer\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documentcontenu_index', 'Accès à l''index des contenus', 10 UNION
    SELECT 'documentcontenu_afficher', 'Afficher un contenu', 20 UNION
    SELECT 'documentcontenu_supprimer', 'Supprimer un contenu ', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documentcontenu';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN ETAT --------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('etat', 'UnicaenEtat - Gestion des états', 20000, 'UnicaenEtat\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'etat_index', 'Afficher l''index des états', 10 UNION
    SELECT 'etat_ajouter', 'Ajouter un état', 20 UNION
    SELECT 'etat_modifier', 'Modifier un état', 30 UNION
    SELECT 'etat_historiser', 'Historiser/Restaurer un etat', 40 UNION
    SELECT 'etat_detruire', 'Supprimer un état', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'etat' ;

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN VALIDATION --------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('validationtype', 'Gestion des types de validations', 40010, 'UnicaenValidation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'validationtype_afficher', 'Affichage des types de validations', 10 UNION
    SELECT 'validationtype_modifier', 'Modifier un type de validation', 30 UNION
    SELECT 'validationtype_historiser', 'Historiser/restaurer un type de validation', 40 UNION
    SELECT 'validationtype_detruire', 'Détruire un type de validation', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'validationtype';


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('validationinstance', 'Gestion des instances de validations', 40000, 'UnicaenValidation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'validationinstance_afficher', 'Affichage des instances de validations', 10 UNION
    SELECT 'validationinstance_modifier', 'Modifier une instance de validation', 20 UNION
    SELECT 'validationinstance_historiser', 'Historiser/restaurer une instance de validation', 40 UNION
    SELECT 'validationinstance_detruire', 'Détruire une isntance de validation', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'validationinstance';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN AIDE --------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('unicaenaideglossaire', 'UnicaenAide - Glossaire', 100300, 'UnicaenAide\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'glossaire_afficher', 'Affichage du glossaire', 10 UNION
    SELECT 'glossaire_index', 'Accès à l''index des défintions', 100 UNION
    SELECT 'glossaire_ajouter', 'Ajouter une définition', 110 UNION
    SELECT 'glossaire_modifier', 'Modifier une définition', 120 UNION
    SELECT 'glossaire_historiser', 'Historiser/restaurer une définition', 130 UNION
    SELECT 'glossaire_supprimer', 'Supprimer une supprimer', 140
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaenaideglossaire';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('unicaenaidefaq', 'UnicaenAide - F.A.Q.', 100200, 'UnicaenAide\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'faq_afficher', 'Afficher la FAQ', 10 UNION
    SELECT 'faq_index', 'Accès à l''index des questions', 20 UNION
    SELECT 'faq_ajouter', 'Ajouter une question', 30 UNION
    SELECT 'faq_modifier', 'Modifier une question', 40 UNION
    SELECT 'faq_historiser', 'Historiser/restaurer une question', 50 UNION
    SELECT 'faq_supprimer', 'Supprimer une question', 60

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaenaidefaq';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('unicaenaidedocumentation', 'UnicaenAide - Documentation', 100400, 'UnicaenAide\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documentation_afficher', 'Afficher la documentation', 10 UNION
    SELECT 'documentation_index', 'Accès à l''index des documentations', 20 UNION
    SELECT 'documentation_ajouter', 'Ajouter une documentation', 30 UNION
    SELECT 'documentation_modifier', 'Modifier une documentation', 40 UNION
    SELECT 'documentation_historiser', 'Historiser/restaurer une documentation', 50 UNION
    SELECT 'documentation_supprimer', 'Supprimer une documentation', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaenaidedocumentation';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN AUTOFORM ----------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('autoformindex', 'Gestion de l''index', 1500, 'UnicaenAutoform\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'index', 'Afficher le menu', 1
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformindex';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('autoformformulaire', 'Gestion des formulaires l''index', 1600, 'UnicaenAutoform\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formulaire_index', 'Accéder à l''index', 10 UNION
    SELECT 'formulaire_afficher', 'Afficher', 20 UNION
    SELECT 'formulaire_ajouter', 'Ajouter', 30 UNION
    SELECT 'formulaire_modifier', 'Modifier', 40 UNION
    SELECT 'formulaire_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'formulaire_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformformulaire';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('autoformcategorie', 'Gestion des catégorie', 1600, 'UnicaenAutoform\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'categorief_index', 'Accéder à l''index', 10 UNION
    SELECT 'categorief_afficher', 'Afficher', 20 UNION
    SELECT 'categorief_ajouter', 'Ajouter', 30 UNION
    SELECT 'categorief_modifier', 'Modifier', 40 UNION
    SELECT 'categorief_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'categorief_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformcategorie';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('autoformchamp', 'Gestion des catégorie', 1600, 'UnicaenAutoform\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'champ_index', 'Accéder à l''index', 10 UNION
    SELECT 'champ_afficher', 'Afficher', 20 UNION
    SELECT 'champ_ajouter', 'Ajouter', 30 UNION
    SELECT 'champ_modifier', 'Modifier', 40 UNION
    SELECT 'champ_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'champ_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformchamp';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN EVENEMENT ---------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_evenement_etat (id, code, libelle)
    VALUES (1, 'en_attente', 'En attente'),
           (2, 'en_cours', 'En cours'),
           (3, 'echec', 'Échec'),
           (4, 'succes', 'Succès');

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('evenementetat', 'Gestion des événements - État', 99991, 'UnicaenEvenement\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'etat_voir', 'état - consultation', 10 UNION
    SELECT 'etat_ajouter', 'état - ajout', 20 UNION
    SELECT 'etat_modifier', 'état - édition', 30 UNION
    SELECT 'etat_supprimer', 'état - suppression', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'evenementetat';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('evenementinstance', 'Gestion des événements - Instance', 99993, 'UnicaenEvenement\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'instance_consultation', 'instance - consultation', 10 UNION
    SELECT 'instance_ajout', 'instance - ajout', 20 UNION
    SELECT 'instance_edition', 'instance - édition', 30 UNION
    SELECT 'instance_suppression', 'instance - suppression', 40 UNION
    SELECT 'instance_traitement', 'instance - traitement', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'evenementinstance';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('evenementtype', 'Gestion des événements - Type', 99992, 'UnicaenEvenement\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'type_consultation', 'type - consultation', 10 UNION
    SELECT 'type_ajout', 'type - ajout', 20 UNION
    SELECT 'type_edition', 'type - édition', 30 UNION
    SELECT 'type_suppression', 'type - suppression', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'evenementtype';

-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE - UNICAEN INDICATEUR --------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('indicateur', 'Gestions des indicateurs', 800, 'UnicaenIndicateur\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'afficher_indicateur', 'Afficher un indicateur', 1   UNION
    SELECT 'editer_indicateur', 'Éditer un indicateur', 2   UNION
    SELECT 'detruire_indicateur', 'Effacer un indicateur', 3 UNION
    SELECT 'indicateur_mes_indicateurs', 'Affichage du menu - Mes Indicateurs -', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'indicateur';


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('abonnement', 'Gestion des abonnements', 99992, 'UnicaenIndicateur\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'afficher_abonnement', 'Afficher un abonnement', 110 UNION
    SELECT 'editer_abonnement', 'Modifier un abonnement', 120 UNION
    SELECT 'detruire_abonnement', 'Supprimer un abonnement', 130
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'abonnement';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('tableaudebord', 'Gestion des tableau de bord', 99992, 'UnicaenIndicateur\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'editer_tableaudebord', 'Éditer un tableau de bord', 5 UNION
    SELECT 'detruire_tableaudebord', 'Effacer un tableau de bord', 6 UNION
    SELECT 'afficher_tableaudebord', 'Afficher un tableau de bord', 4
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'tableaudebord';

-- OBSERVATION -

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre) VALUES
('observationtype', 'UnicaenObservation - Gestion des types d''observation', 'UnicaenObservation\Provider\Privilege', 11020);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'observationtype_index', 'Accéder à l''index', 10 UNION
    SELECT 'observationtype_afficher', 'Afficher', 20 UNION
    SELECT 'observationtype_ajouter', 'Ajouter', 30 UNION
    SELECT 'observationtype_modifier', 'Modifier', 40 UNION
    SELECT 'observationtype_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'observationtype_supprimer', 'Supprimer', 60

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'observationtype';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('observationinstance', 'UnicaenObservation - Gestion des observations', 'UnicaenObservation\Provider\Privilege', 11010);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'observationinstance_index', 'Accéder à l''index', 10 UNION
    SELECT 'observationinstance_afficher', 'Afficher', 20 UNION
    SELECT 'observationinstance_ajouter', 'Ajouter', 30 UNION
    SELECT 'observationinstance_modifier', 'Modifier', 40 UNION
    SELECT 'observationinstance_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'observationinstance_supprimer', 'Supprimer', 60

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'observationinstance';

-- ---------------------------------------------------------------------------------------------------------------------
-- PARAMETRE -----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_parametre_categorie (code, libelle, ordre, description)
VALUES ('GLOBAL', 'Paramètres globaux', 1, null);
INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES,
                                        ORDRE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE) AS (
    SELECT 'CODE_UNIV', 'Code de l''établissement porteur principal', '<p>Sert notamment pour l''affichage des status</p>', 'String',  1000 UNION
    SELECT 'INSTALL_PATH', 'Chemin d''installation (utiliser pour vérification)', null, 'String', 1000 UNION
    SELECT 'EMAIL_ASSISTANCE', 'Adresse électronique de l''assistance', null, 'String', 100
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES,  d.ORDRE
FROM d
         JOIN unicaen_parametre_categorie cp ON cp.CODE = 'GLOBAL';

-- ---------------------------------------------------------------------------------------------------------------------
-- TEMPLATE GENERAUX ---------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('EMC2_ACCUEIL', '<p>Texte de la page d''accueil</p>', 'texte', 'Instance de démonstration de EMC2', e'<p>Instance de démonstration de EMC2.</p>
<p><em>Ce texte est template modifiable dans la partie Administration &gt; Template.</em></p>', null, 'Application\Provider\Template');

-- ---------------------------------------------------------------------------------------------------------------------
-- MACROS --------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name)
VALUES
    ('EMC2#AppName', '', 'MacroService', 'getAppName'),
    ('EMC2#date', 'Affiche la date du jour d/m/Y', 'MacroService', 'getDate'),
    ('EMC2#datetime', 'Affiche la date et l heure d/m/Y à H:i', 'MacroService', 'getDateTime')
;