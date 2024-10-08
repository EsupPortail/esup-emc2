-- Date de MAJ 29/11/2023 ----------------------------------------------------------------------------------------------
-- Script avant version 4.2.0 ------------------------------------------------------------------------------------------
-- Color scheme : 540000 -----------------------------------------------------------------------------------------------

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

create table formation_demande_externe
(
    id                        serial
        constraint formation_demande_externe_pk
        primary key,
    libelle                   varchar(1024)           not null,
    organisme                 varchar(1024)           not null,
    contact                   varchar(1024)           not null,
    pourquoi                  text,
    montant                   float,
    lieu                      varchar(1024)           not null,
    debut                     date                    not null,
    fin                       date                    not null,
    missions                  text                    not null,
    justification_agent       text                    not null,
    justification_responsable text,
    justification_gestionnaire text,
    justification_drh         text,
    justification_refus       text,
    prise_en_charge           boolean   default true  not null,
    cofinanceur               varchar(1024),
    conge_formation_syndicale bool      default false not null,
    histo_creation            timestamp default now() not null,
    histo_createur_id         integer   default 0     not null
        constraint formation_demande_externe_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification        timestamp,
    histo_modificateur_id     integer
        constraint formation_demande_externe_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction         timestamp,
    histo_destructeur_id      integer
        constraint formation_demande_externe_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user,
    agent_id                  varchar(40)             not null
        constraint formation_demande_externe_agent_c_individu_fk
        references agent,
    modalite                  varchar(1024)
);

create unique index formation_demande_externe_id_uindex
    on formation_demande_externe (id);

create table formation_demande_externe_validation
(
    demande_id    integer not null
        constraint formation_demande_externe_validation_id1_fk
        references formation_demande_externe
        on delete cascade,
    validation_id integer not null
        constraint formation_demande_externe_validation_id2_fk
        references unicaen_validation_instance
        on delete cascade,
    constraint formation_demande_externe_validation_pk
        primary key (demande_id, validation_id)
);


create table formation_demande_externe_fichier
(
    demande_id integer     not null
        constraint formation_demande_externe_ficher_formation_demande_externe_id_f
        references formation_demande_externe
        on delete cascade,
    fichier_id varchar(13) not null
        constraint formation_demande_externe_ficher_fichier_fichier_id_fk
        references fichier_fichier
        on delete cascade,
    constraint formation_demande_externe_ficher_pk
        primary key (demande_id, fichier_id)
);

create table formation_session_parametre
(
    id                    serial
        constraint formation_session_parametre_pk
        primary key,
    mail                  boolean default true not null,
    evenement             boolean default true not null,
    enquete               boolean default true not null,
    emargement            boolean default true not null,
    histo_creation        timestamp            not null,
    histo_createur_id     integer              not null
        constraint formation_session_parametre_unicaen_utilisateur_user_null_fk_1
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_session_parametre_unicaen_utilisateur_user_null_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_session_parametre_unicaen_utilisateur_user_null_fk_3
        references unicaen_utilisateur_user
);

comment on table formation_session_parametre is 'Table permettant de parametre le comportement d''une session';

create table formation_plan_formation
(
    id                    serial
        constraint formation_plan_formation_pk
            primary key,
    libelle               varchar(1024)           not null,
    date_debut            timestamp default now() not null,
    date_fin              timestamp,
    description           text,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint formation_plan_formation_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_plan_formation_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_plan_formation_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);

create table formation_demande_externe_etat
(
    demande_id integer not null
        constraint formation_demande_externe_etat_formation_demande_externe_id_fk
        references formation_demande_externe
        on delete cascade,
    etat_id    integer not null
        constraint formation_demande_externe_etat_unicaen_etat_instance_id_fk
        references unicaen_etat_instance
        on delete cascade,
    constraint formation_demande_externe_etat_pk
        primary key (demande_id, etat_id)
);


create table formation_axe
(
    id                    serial
        constraint formation_axe_pk
        primary key,
    libelle               varchar(1024)           not null,
    description           text,
    ordre                 integer   default 9999  not null,
    couleur               varchar(64),
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint formation_axe_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_axe_unicaen_utilisateur_user_id_fk2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_axe_unicaen_utilisateur_user_id_fk3
        references unicaen_utilisateur_user
);


create table formation_groupe
(
    id                    serial
        constraint formation_groupe_pk
        primary key,
    libelle               varchar(1024),
    couleur               varchar(255),
    ordre                 integer default 9999,
    source_id             varchar(128),
    histo_createur_id     integer   not null
        constraint formation_groupe_createur_fk
        references unicaen_utilisateur_user,
    histo_creation        timestamp not null,
    histo_modificateur_id integer
        constraint formation_groupe_modificateur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_destructeur_id  integer
        constraint formation_groupe_destructeur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    description           text,
    id_source             varchar(256),
    axe_id                integer
        constraint formation_groupe_formation_axe_id_fk
        references formation_axe
        on delete set null
);

create unique index formation_groupe_id_uindex
    on formation_groupe (id);


create table formation_action_type
(
    id                    serial                  not null
        constraint formation_action_type_pk
            primary key,
    code                  varchar(256)            not null
        constraint formation_action_type_pk_2
            unique,
    libelle               varchar(1024)           not null,
    description           text,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint formation_action_type_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_action_type_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_action_type_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);


create table formation
(
    id                    serial
        constraint formation_pk
        primary key,
    libelle               varchar(256)         not null,
    description           text,
    lien                  varchar(1024),
    groupe_id             integer
        constraint formation_formation_groupe_id_fk
        references formation_groupe
        on delete set null,
    source_id             varchar(128),
    histo_createur_id     integer              not null
        constraint formation_createur_fk
        references unicaen_utilisateur_user,
    histo_creation        timestamp            not null,
    histo_modificateur_id integer
        constraint formation_modificateur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_destructeur_id  integer
        constraint formation_destructeur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    affichage             boolean default true not null,
    rattachement          varchar(1024),
    objectifs             text,
    programme             text,
    prerequis             text,
    public                text,
    complement            text,
    action_type_id        integer
        constraint formation_type_fk
            references formation_action_type on delete set null,
    type                  varchar(64),
    id_source             varchar(256)
);
create unique index formation_id_uindex on formation (id);
comment on column formation.complement is 'Information à faire figurer sur les convocations';

create table formation_element
(
    id                    serial
        constraint formation_element_pk
        primary key,
    formation_id          integer   not null
        constraint formation_element_formation_informations_id_fk
        references formation
        on delete cascade,
    commentaire           text,
    validation_id         integer
        constraint formation_element_unicaen_validation_instance_id_fk
        references unicaen_validation_instance
        on delete set null,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint formation_element_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_element_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_element_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user
);


create unique index formation_element_id_uindex
    on formation_element (id);

create table formation_obtenue_application
(
    formation_id           integer not null
        constraint formation_application_obtenue_formation_id_fk
        references formation
        on delete cascade,
    application_element_id integer not null
        constraint formation_application_obtenue_application_element_id_fk
        references element_application_element
        on delete cascade,
    constraint formation_application_obtenue_pk
        primary key (formation_id, application_element_id)
);


create table formation_obtenue_competence
(
    formation_id          integer not null
        constraint formation_obtenue_competence_formation_id_fk
        references formation
        on delete cascade,
    competence_element_id integer not null
        constraint formation_obtenue_competence_competence_element_id_fk
        references element_competence_element
        on delete cascade,
    constraint formation_obtenue_competence_pk
        primary key (formation_id, competence_element_id)
);

create table formation_formation_abonnement
(
    id                    serial
        constraint formation_formation_abonnement_pk
        primary key,
    formation_id          integer     not null
        constraint formation_formation_abonnement_formation_id_fk
        references formation
        on delete cascade,
    agent_id              varchar(40) not null
        constraint formation_formation_abonnement_agent_c_individu_fk
        references agent
        on delete cascade,
    date_inscription      timestamp   not null,
    description           text,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint formation_formation_abonnement_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_formation_abonnement_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_formation_abonnement_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user
);

create table formation_action_cout_previsionnel
(
    id                    serial  not null
        constraint fapc_pk
            primary key,
    action_id             integer not null
        constraint fapc_formation_id_fk
            references formation
            on delete cascade,
    plan_id               integer
        constraint fapc_formation_plan_formation_id_fk
            references formation_plan_formation
            on delete cascade,
    cout_par_session      float   not null,
    nombre_de_session     integer not null,
    histo_creation        timestamp  not null default now(),
    histo_createur_id     integer not null default 0
        constraint fapc_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint fapc_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint fapc_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);

create table formation_instance
(
    id                      serial
        constraint formation_instance_pk
        primary key,
    formation_id            integer               not null
        constraint formation_instance_formation_id_fk
        references formation
        on delete cascade,
    complement              text,
    auto_inscription        boolean default false not null,

    nb_place_principale     integer default 0     not null,
    nb_place_complementaire integer default 0     not null,
    lieu                    varchar(256),
    type                    varchar(256),
    affichage               boolean default true  not null,

    source_id               varchar(128),
    id_source               varchar(256),
    histo_creation          timestamp             not null,
    histo_createur_id       integer               not null
        constraint formation_instance_user_id_fk_1
        references unicaen_utilisateur_user,
    histo_modification      timestamp,
    histo_modificateur_id   integer
        constraint formation_instance_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction       timestamp,
    histo_destructeur_id    integer
        constraint formation_instance_user_id_fk_3
        references unicaen_utilisateur_user,
    cout_ht                 double precision,
    cout_ttc                double precision,
    cout_vacation           double precision,
    recette_ttc             double precision,
    parametre_id            integer
        constraint formation_instance_formation_session_parametre_null_fk
        references formation_session_parametre
        on delete set null
);
create unique index formation_instance_id_uindex on formation_instance (id);


create table formation_session_gestionnaire
(
    session_id      integer not null
        constraint formation_session_gestionnaire_formation_instance_id_fk
            references formation_instance on delete cascade,
    gestionnaire_id integer not null
        constraint formation_session_gestionnaire_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user on delete cascade,
    constraint formation_session_gestionnaire_pk primary key (session_id, gestionnaire_id)
);



create table formation_demande_externe_gestionnaire
(
    demande_externe_id integer not null
        constraint fdeg_formation_demande_externe_id_fk
            references formation_demande_externe
            on delete cascade,
    gestionnaire_id    integer not null
        constraint fdeg_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user
            on delete cascade,
    constraint formation_demande_externe_gestionnaire_pk
        primary key (demande_externe_id, gestionnaire_id)
);



create table formation_formateur
(
    id                    serial
        constraint formation_instance_formateur_pk
            primary key,
    instance_id           integer   not null
        constraint formation_instance_formateur_formation_instance_id_fk
            references formation_instance
            on delete cascade,
    prenom                varchar(256),
    nom                   varchar(256),
    email                 varchar(1024),
    attachement           varchar(1024),
    user_id               int
        constraint formation_formateur_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user on delete set null,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint formation_instance_formateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_instance_formateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_instance_formateur_user_id_fk_3
            references unicaen_utilisateur_user,
    organisme             varchar(1024),
    telephone             varchar(64),
    type                  varchar(64)
);

create table formation_formateur_session
(
    formateur_id integer not null
        constraint formation_formateur_session_formation_formateur_id_fk
            references formation_formateur
            on delete cascade,
    session_id   integer not null
        constraint formation_formateur_session_formation_instance_id_fk
            references formation_instance
            on delete cascade,
    constraint formation_formateur_session_pk
        primary key (formateur_id, session_id)
);


create table formation_stagiaire_externe
(
    id                    serial                  not null
        constraint formation_stagiaire_externe_pk
            primary key,
    prenom                varchar(1024)           not null,
    nom                   varchar(1024)           not null,
    date_naissance        timestamp,
    sexe                  varchar(1),
    structure             varchar(1024),
    email                 varchar(1024)           not null,
    login                 varchar(1024),
    utilisateur_id        integer
        constraint formation_stagiaire_externe_unicaen_utilisateur_user_id_fk4
            references unicaen_utilisateur_user,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint formation_stagiaire_externe_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_stagiaire_externe_unicaen_utilisateur_user_id_fk3
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_stagiaire_externe_unicaen_utilisateur_user_id_fk2
            references unicaen_utilisateur_user
);

create table formation_inscription
(
    id                        serial
        constraint formation_inscription_pk
            primary key,
    agent_id                  varchar(40)
        constraint formation_inscription_agent_c_individu_fk
            references agent
            on delete cascade,
    stagiaire_id              integer
        constraint formation_inscription_formation_stagiaire_externe_id_fk
            references formation_stagiaire_externe,
    session_id                integer                 not null
        constraint formation_inscription_formation_instance_id_fk
            references formation_instance
            on delete cascade,
    liste                     varchar(64),
    missions                  text,
    justification_agent       text,
    justification_responsable text,
    justification_drh         text,
    justification_refus       text,
    enquete_instance_id       integer
        constraint formation_inscription_unicaen_enquete_instance_id_fk
            references unicaen_enquete_instance
            on delete set null,
    rqth                      boolean default false not null,
    precision_rqth            text,
    source_id                 varchar(128),
    id_source                 varchar(100),
    histo_creation            timestamp default now() not null,
    histo_createur_id         integer   default 0     not null
        constraint formation_inscription_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification        timestamp,
    histo_modificateur_id     integer
        constraint formation_inscription_unicaen_utilisateur_user_id_fk2
            references unicaen_utilisateur_user,
    histo_destruction         timestamp,
    histo_destructeur_id      integer
        constraint formation_inscription_unicaen_utilisateur_user_id_fk3
            references unicaen_utilisateur_user
);

create table formation_inscription_frais
(
    id                    serial
        constraint formation_inscription_frais_pk
            primary key,
    inscription_id        integer   not null
        constraint formation_inscription_frais_formation_inscription_id_fk
            references formation_inscription
            on delete cascade,
    frais_repas           double precision default 0,
    frais_hebergement     double precision default 0,
    frais_transport       double precision default 0,
    histo_creation        timestamp not null,
    source_id             varchar(128),
    id_source             varchar(64),
    histo_createur_id     integer   not null
        constraint formation_inscription_frais_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_inscription_frais_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_inscription_frais_user_id_fk_3
            references unicaen_utilisateur_user
);

create table formation_lieu
(
    id                    serial                  not null
        constraint formation_lieu_pk
            primary key,
    libelle               varchar(1024),
    batiment              varchar(1024),
    campus                varchar(1024),
    ville                 varchar(1024),
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint formation_lieu_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_lieu_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_lieu_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);

create table formation_seance
(
    id                    serial
        constraint formation_instance_journee_pk
        primary key,
    instance_id           integer                                          not null
        constraint formation_instance_journee_formation_instance_id_fk
        references formation_instance
        on delete cascade,
    jour                  timestamp,
    debut                 varchar(64),
    fin                   varchar(64),
    lieu_old              varchar(1024),
    lieu                  integer
        constraint formation_seance_formation_lieu_id_fk
        references formation_lieu on delete set null,
    lien                  varchar(1024),
    remarque              text,
    source_id             varchar(128),
    histo_creation        timestamp                                        not null,
    histo_createur_id     integer                                          not null
        constraint formation_instance_journee_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_instance_journee_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_instance_journee_user_id_fk_3
        references unicaen_utilisateur_user,
    type                  varchar(255) default 'SEANCE'::character varying not null,
    volume                double precision,
    volume_debut          timestamp,
    volume_fin            timestamp,
    id_source             varchar(256)
);


create unique index formation_instance_journee_id_uindex
    on formation_seance (id);




create unique index formation_instance_formateur_id_uindex
    on formation_formateur (id);

create table formation_presence
(
    id                    serial
        constraint formation_instance_presence_pk
            primary key,
    journee_id            integer                                                  not null
        constraint formation_instance_presence_formation_instance_journee_id_fk
            references formation_seance
            on delete cascade,
    inscrit_id            integer,
    presence_type         varchar(256)                                             not null,
    commentaire           text,
    histo_creation        timestamp                                                not null,
    histo_createur_id     integer                                                  not null
        constraint formation_instance_presence_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_instance_presence_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_instance_presence_user_id_fk_3
            references unicaen_utilisateur_user,
    source_id             varchar(128),
    id_source             varchar(256),
    statut                varchar(256) default 'NON RENSEIGNEE'::character varying not null,
    inscription_id        integer                                                  not null
        constraint formation_presence_formation_inscription_id_fk
            references formation_inscription
            on delete cascade
);
create unique index formation_instance_presence_id_uindex on formation_presence (id);

create table formation_action_plan
(
    action_id integer not null
        constraint formation_action_plan_formation_id_fk
        references formation
        on delete cascade,
    plan_id   integer not null
        constraint formation_action_plan_formation_plan_formation_id_fk
        references formation_plan_formation
        on delete cascade,
    constraint formation_action_plan_pk
        primary key (action_id, plan_id)
);


create table formation_session_etat
(
    session_id integer not null
        constraint formation_instance_etat_session_id_fk
        references formation_instance
        on delete cascade,
    etat_id    integer not null
        constraint formation_instance_etat_etat_id_fk
        references unicaen_etat_instance
        on delete cascade,
    constraint formation_session_etat_pk
        primary key (session_id, etat_id)
);


create table formation_inscription_etat
(
    inscription_id          integer         not null constraint inscription_etat_inscription_id_fk references formation_inscription on delete cascade,
    etat_id                 integer         not null constraint inscription_etat_etat_id_fk references unicaen_etat_instance on delete cascade,
    constraint formation_inscription_etat_pk_ primary key (inscription_id, etat_id)
);

create table formation_domaine
(
    id                    serial
        constraint formation_domaine_pk
        primary key,
    libelle               varchar(1024)           not null,
    description           text,
    ordre                 integer   default 9999  not null,
    couleur               varchar(64),
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint formation_domaine_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_domaine_unicaen_utilisateur_user_id_fk2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_domaine_unicaen_utilisateur_user_id_fk3
        references unicaen_utilisateur_user
);


create table formation_formation_domaine
(
    formation_id integer not null
        constraint formaton_formation_domaine_formation_id_fk
        references formation
        on delete cascade,
    domaine_id   integer not null
        constraint formaton_formation_domaine_formation_domaine_id_fk
        references formation_domaine
        on delete cascade,
    constraint formaton_formation_domaine_pk
        primary key (formation_id, domaine_id)
);

create table formation_demande_externe_session
(
    demande_id integer not null
        constraint fdea_formation_demande_externe_id_fk references formation_demande_externe on delete cascade,
    session_id  integer not null
        constraint fdea_formation_instance_id_fk references formation_instance on delete cascade,
    constraint fdea_pk primary key (demande_id, session_id)
);

create table agent_element_formation
(
    agent_id             varchar(40) not null
        constraint agent_formation_agent_c_individu_fk
            references agent
            on delete cascade,
    formation_element_id integer     not null
        constraint agent_formation_formation_element_id_fk
            references formation_element
            on delete cascade,
    constraint agent_formation_pk
        primary key (agent_id, formation_element_id)
);

create table formation_demande_externe_observation
(
    demande_id              integer not null
        constraint fdeo_formation_demande_externe_id_fk references formation_demande_externe on delete cascade,
    observation_instance_id integer not null
        constraint fdeo_unicaen_observation_observation_instance_id_fk references unicaen_observation_observation_instance on delete cascade,
    constraint formation_demande_externe_observation_pk
        primary key (demande_id, observation_instance_id)
);

create table lagaf_stagiaire
(
    id         serial
        constraint lagaf_stagiaire_pk
            primary key,
    nom        varchar(255),
    prenom     varchar(255),
    annee      integer,
    harp_id    varchar(255),
    octopus_id varchar(255),
    nstagiaire integer
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

-- ACTION TYPE

INSERT INTO formation_action_type (code, libelle, description) VALUES
('T1', 'Type 1 : formations d''adaptation immédiate au poste de travail', 'Stage d''adaptation à l''emploi'),
('T2', 'Type 2 : formations à l''évolution des métiers ou des postes de travail', 'Approfondir ses compétences techniques'),
('T3', 'Type 3 : formations d''acquisition de qualifications nouvelles', 'Favoriser sa culture professionnelle ou son niveau d''expertise');

-- ---------------------------------------------------------------------------------------------------------------------
-- ROLE ----------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, is_auto, accessible_exterieur, description) VALUES
    ('Formateur·trice', 'Formateur·trice', false, false, true, null),
    ('Gestionnaire de formation', 'Gestionnaire de formation', false, false, true, null),
    ('Responsable de formation', 'Responsable de formation', false, false, true, 'Rôle attribué aux personnes en charge du bureau des formations.'),
    ('Stagiaire externe', 'Stagiaire externe', false, true, true, 'Stagiaire qui n''est pas un·e agent·e connu·e ')
;

-- ---------------------------------------------------------------------------------------------------------------------
-- ETAT ----------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_etat_categorie (code, libelle, icone, couleur, ordre) VALUES
    ('DEMANDE_EXTERNE', 'Gestion des demandes de formations externes', 'fas fa-school', 'cadetblue', 200),
    ('FORMATION_SESSION', 'Gestion des sessions de formation', 'fas fa-chalkboard', '#3465a4', 300),
    ('FORMATION_INSCRIPTION', 'Gestion des inscriptions au formation', 'fas fa-chalkboard-teacher', '#204a87', 400);

INSERT INTO unicaen_etat_type(code, libelle, categorie_id, icone, couleur, ordre)
WITH d(code, libelle, icone, couleur, ordre) AS (
    SELECT 'DEMANDE_EXTERNE_REDACTION', 'Demande en cours de rédaction', 'fas fa-edit', '#75507b', 10 UNION
    SELECT 'DEMANDE_EXTERNE_AGENT', 'Validation de l''agent', 'fas fa-user', '#f57900', 20 UNION
    SELECT 'DEMANDE_EXTERNE_RESP', 'Validation du responsable de l''agent', 'fas fa-user-tie', '#edd400', 30 UNION
    SELECT 'DEMANDE_EXTERNE_FORCEE_PARAPHEUR', 'Demande envoyée dans le parapheur', 'icon icon-importer', '#cbcb00', 35 UNION
    SELECT 'DEMANDE_EXTERNE_GESTIONNAIRE', 'Demande validée par un gestionnaire', 'fas fa-hourglass', '#cbcb00', 36 UNION
    SELECT 'DEMANDE_EXTERNE_DRH', 'Validation par le bureau de gestion des formations', 'fas fa-user-check', '#8ae234', 40 UNION
    SELECT 'DEMANDE_EXTERNE_TERMINEE', 'Demande de formation externe traitée', 'far fa-check-square', '#4e9a06', 50 UNION
    SELECT 'DEMANDE_EXTERNE_REJETEE', 'Demande de formation externe rejetée', 'fas fa-times', '#a40000', 60
)
SELECT d.code, d.libelle, cp.id, d.icone, d.couleur, d.ordre
FROM d
JOIN unicaen_etat_categorie cp ON cp.CODE = 'DEMANDE_EXTERNE';

INSERT INTO unicaen_etat_type(code, libelle, categorie_id, icone, couleur)
WITH d(code, libelle, icone, couleur) AS (
    SELECT 'EN_CREATION', 'En cours de saisie', 'fas fa-edit', '#75507b', 10 UNION
    SELECT 'INSCRIPTION_OUVERTE', 'Inscription ouverte', 'fas fa-book-open', '#729fcf', 20 UNION
    SELECT 'INSCRIPTION_FERMEE', 'Inscription close', 'fas fa-book', '#204a87', 30 UNION
    SELECT 'CONVOCATION', 'Convocations envoyées', 'fas fa-file-contract', '#fcaf3e', 40 UNION
    SELECT 'ATTENTE_RETOUR', 'Demande des retours', 'far fa-comments', '#ce5c00', 50 UNION
    SELECT 'FERMEE', 'Session fermée', 'far fa-check-square', '#4e9a06', 60 UNION
    SELECT 'SESSION_ANNULEE', 'Session de formation annulée', 'fas fa-times', '#a40000', 70
)
SELECT d.code, d.libelle, cp.id, d.icone, d.couleur
FROM d
JOIN unicaen_etat_categorie cp ON cp.CODE = 'FORMATION_SESSION';

INSERT INTO unicaen_etat_type(code, libelle, categorie_id, icone, couleur)
WITH d(code, libelle, icone, couleur) AS (
    SELECT 'FORMATION_INSCRIPTION_DEMANDE', 'Demande d''inscription en cours de validation', 'fas fa-user', '#f57900', 10 UNION
    SELECT 'FORMATION_INSCRIPTION_RESPONSABLE', 'Demande validée par le responsable', 'fas fa-user-tie', '#edd400', 20 UNION
    SELECT 'FORMATION_INSCRIPTION_DRH', 'Demande validée', 'far fa-check-square', '#4e9a06', 100 UNION
    SELECT 'FORMATION_INSCRIPTION_REFUSER', 'Demande refusée', 'fas fa-times', '#a40000', 110
)
SELECT d.code, d.libelle, cp.id, d.icone, d.couleur
FROM d
JOIN unicaen_etat_categorie cp ON cp.CODE = 'FORMATION_INSCRIPTION';

-- ---------------------------------------------------------------------------------------------------------------------
-- VALIDATION ----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_validation_type (code, libelle, refusable) VALUES
    ('FORMATION_DEMANDE_AGENT', 'Validation d''un demande de formation externe par l''agent', false),
    ('FORMATION_DEMANDE_RESPONSABLE', 'Validation d''un demande de formation externe par le responsable de l''agent', false),
    ('FORMATION_DEMANDE_DRH', 'Validation d''un demande de formation externe par la DRH', false),
    ('FORMATION_DEMANDE_REFUS', 'Refus d''une demande externe', false);

INSERT INTO unicaen_validation_type (code, libelle, refusable) VALUES
    ('FORMATION_CHARTE_SIGNEE', 'Signature de la charte de formation', false);


-- ---------------------------------------------------------------------------------------------------------------------
-- PARAMETRE -----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------
INSERT INTO unicaen_parametre_categorie (code, libelle, ordre, description)
VALUES ('FORMATION', 'Paramètres liés aux formations', 2000, null);
INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE) AS (
    SELECT 'MAIL_PERSONNEL', 'Adresse électronique du personnel', null, 'String', 10 UNION
    SELECT 'MAIL_DRH_FORMATION', 'Adresse électronique du bureau de gestion des formations', null, 'String', 10 UNION
    SELECT 'MAIL_PREVENTION_FORMATION', 'Adresse électronique du bureau de formation prévention', null, 'String', 30 UNION
    SELECT 'NB_PLACE_PRINCIPALE', 'Nombre de place par défaut en liste principale', null, 'Number', 100 UNION
    SELECT 'NB_PLACE_COMPLEMENTAIRE', 'Nombre de place par défaut en liste complémentaire', null, 'Number', 110 UNION
    SELECT 'URL_PPP', 'Lien vers intranet Projet professionnel personnel', null, 'String', 200 UNION
    SELECT 'AUTO_FERMETURE', 'Délai pour fermeture automatique des inscriptions (en jours)', null, 'String', 400 UNION
    SELECT 'AUTO_CONVOCATION', 'Délai pour convocation automatique des inscrits (en jours)', null, 'String', 410 UNION
    SELECT 'AUTO_RETOUR', 'Délai pour la demande de retour', null, 'Number', 420 UNION
    SELECT 'AUTO_CLOTURE', 'Délai pour la cloture de la session (en jours)', null, 'Number', 430 UNION
    SELECT 'LOGO', 'Logo de l''établissement', 'Le logo de l''établissement qui figurera sur les documents', 'String', 1010 UNION
    SELECT 'LIBELLE', 'Libellé de l''établissement', 'Le libellé de l''établissement', 'String', 1020 UNION
    SELECT 'SOUSLIBELLE', 'Sous libellé', 'Un complément au libellé de l''établissement (par exemple : Direction des ressources humaines <br> Formation)', 'String', 1030 UNION
    SELECT 'CONVOCATION_SUPERIEUR_COPIE', 'Mise en copie des supérieur·es hiérarchiques lors de convocation', null, 'Bool', 2000 UNION
    SELECT 'DEMANDE_EXTERNE_PLAFOND', 'Plafond du montant des formations externes exigeant validation', null , 'String', 3000 UNION
    SELECT 'CODE_ENQUETE'           ,'Enquête active','Code de l''enquête active' , 'String', 10000
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES, d.ORDRE
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'FORMATION';

-- ---------------------------------------------------------------------------------------------------------------------
-- NATURE DE FICHIER ---------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO fichier_nature (code, libelle, description)
VALUES
    ('DEMANDEEXTERNE_DEVIS', 'Devis de demande externe', null),
    ('INSCRIPTION_ATTESTATION', 'Attestation de formation', 'Attestion de formation déposer dans [Mes Formations] qui se substituera à l''attestation générée')
;

-- ---------------------------------------------------------------------------------------------------------------------
-- TEMPLATE - TEXTE ----------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('MES_FORMATIONS_ACCUEIL', '<p>Texte de l''accueil de la partie formation</p>', 'texte', 'Formation\Provider\Template', 'Mes formations', '<p>Mes formations permet la gestion des formations du personnel ...</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('PARCOURS_ENTREE_TEXTE', '<p>Texte descriptif du parcours d''entrée</p>', 'texte', 'Formation\Provider\Template', '...', e'<p>Ceci est le texte d\'introduction au parcours d\'entrée à la formation</p>
<p> </p>
<p>&gt;&gt; ICI LIEN VERS MOODLE ET LE PARCOURS DE L\'AGENT</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace)
VALUES ('MES_FORMATIONS_PROJETPERSO', '<p>Texte associé à la page du projet personnel</p>', 'texte', 'Mon projet personnel', '<p>Dans le cadre de la formation professionnelle tout au long de la vie, vous pouvez mobiliser différents dispositifs pour la réalisation de projets professionnels d''ordre personnel, tels que le bilan de compétences, la validation des acquis de l''expérience, le congé de Formation professionnelle ou le compte personnel de formation.<br /><br />Quel que soit le dispositif choisi, une commission a lieu deux fois par an. Vous pouvez consulter le lien intranet pour connaître l''ensemble des modalités.</p>', null, 'Formation\Provider\Template');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENQUETE_EXPLICATION', '<p>Texte précisant le caractère anonyme et <em>facultatif</em> de l''enquête</p>', 'texte', 'À propos des formulaires de retour d''expérience', e'<p>Ces formulaires sont anonymes et si vous ne souhaitez pas fournir de réponse à une des questions de l\'enquête vous pouvez sélectionner la réponse "<em>Sans avis</em>".</p>
<p>Vous ne pourrez récupérer l\'attestation pour une formation qu\'une fois l\'enquête associée saisie et validée.</p>', null, 'Formation\Provider\Privilege');

-- Bandeau en haut des pages des agents
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace)
VALUES ('PLANS_DE_FORMATION', '<p>Bandeau en haut de la page Plans de formation</p>', 'texte', 'Plans de formation', '<p>En vous abonnant aux notifications, vous recevrez un mail une fois la session ouverte et vous pourrez vous inscrire.</p>', null, 'Formation\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace)
VALUES ('INSCRIPTION_STAGE_HORS_PLAN', '<p>Bloc de texte afficher en haut de la page de demande des stages hors du plan de formation</p>', 'texte', 'Stage hors plan', '<p><strong>La demande d''inscription ne vaut pas acceptation.</strong> Votre inscription est soumise à validation et en fonction des places disponibles.</p>', null, 'Formation\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace)
VALUES ('INSCRIPTION_FORMATION_DU_PLAN', '<p>Bloc en haut de la page d''inscription à une formation du plan de formation sur la page des agents</p>', 'texte', '.', '<p><strong>La demande d''inscription ne vaut pas acceptation.</strong> Votre inscription est soumise à validation et en fonction des places disponibles.</p>', null, 'Formation\Provider\Template');

-- Chartre
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('FORMATION_CHARTE', null, 'texte', 'Charte d''engagement', '<p>Je, VAR[AGENT#Denomination], m''engage à suivre les formations que je demande et à me montrer respectueux des personnes associés à ses formations.</p>', null, 'Formation\Provider\Template');
-- ---------------------------------------------------------------------------------------------------------------------
-- TEMPLATE - PDF ------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_ATTESTATION', null, 'pdf', 'Formation\Provider\Template', 'Attestation de formation de VAR[AGENT#Denomination] à la formation VAR[SESSION#libelle]', e'<p> </p>
<p> </p>
<p> </p>
<p> </p>
<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p> </p>
<p style="text-align: right;">À Caen le VAR[EMC2#Date]</p>
<p> </p>
<h1 style="text-align: center;">Attestation de stage</h1>
<p> </p>
<p>Le bureau conseil, carrière, compétences, certifie que le stagiaire :</p>
<p>VAR[AGENT#Denomination] a suivi la formation : <strong>VAR[SESSION#libelle] </strong>qui s\'est déroulée du VAR[SESSION#periode] (Durée : VAR[SESSION#duree])VAR[SESSION#lieu].</p>
<p> </p>
<p>L\'agent a suivi VAR[INSCRIPTION#duree] de formation.</p>
<p> </p>
<p> </p>
<p style="text-align: left;">Le bureau conseil, carrière, compétences.</p>
<p><span dir="ltr" style="left: 70.8661px; top: 794.321px; font-size: 16.6667px; font-family: sans-serif; transform: scaleX(1);" role="presentation"> </span></p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_CONVOCATION', null, 'pdf', 'Formation\Provider\Template', 'Convocation à la session de formation VAR[SESSION#libelle]', e'<p> </p>
<p> </p>
<p> </p>
<p> </p>
<h1 style="text-align: center;">Convocation à une action de formation</h1>
<p style="text-align: right;"> À Caen, le VAR[EMC2#Date]</p>
<p>Bonjour <strong>VAR[AGENT#Denomination]</strong>,</p>
<p> </p>
<p>Le bureau conseil carrière compétences vous informe que vous êtes retenu-e à la formation :</p>
<p style="text-align: center;"><strong>VAR[SESSION#libelle]<br /></strong></p>
<p>à laquelle vous êtes inscrit-e et se déroulera selon le calendrier ci-dessous :</p>
<p>VAR[SESSION#seances] </p>
<p>En cas d\'impossibilité d\'assister à tout ou partie de ce stage, vous êtes invité-e à vous connecter dans la partie "Formation"/"S\'inscrire à une formation" de VAR[EMC2#AppName], dans les meilleurs délais, afin de nous permettre de contacter une personne sur liste d\'attente.</p>
<p style="text-align: justify;"> </p>
<p style="text-align: justify;">Le bureau conseil, carrière, compétences vous souhaite un stage fructueux.</p>
<p style="text-align: justify;"> </p>
<p style="text-align: justify;">La responsable du bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr </p>
<p style="text-align: left;"><em>P.S. : Cette convocation vaut ordre de mission</em></p>', 'table { border-collapse: collapse;} tr th {border-bottom:1px solid black;} td {text-align:center;}');
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_HISTORIQUE', null, 'pdf', 'Formation\Provider\Template', 'Historique des formations de VAR[AGENT#Denomination]', e'<h1 style="text-align: center;"> Historique de formation</h1>
<p> </p>
<p>L\'agent <strong>VAR[AGENT#Denomination]</strong> a suivi les formations suivantes  : ###A REMPLACER###</p>
<p> </p>
<p style="text-align: right;">La responsable du bureau conseil, carrière, compétences.<br /><br /></p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('FORMATION_ABSENCE', '<p>Document émis pour les inscrit·es qui ont été absent·es à une formation</p>', 'pdf', 'constat_absence_session_VAR[SESSION#id]', e'<p><strong>Université de démonstration</strong><br />DRH - Bureau des formations</p>
<h1 style="text-align: center;">Constat d\'absence à un stage</h1>
<p><br />le VAR[EMC2#date]</p>
<p><br />Je soussigné, Prenom NOM, Président de l’Université de démonstration, certifie que VAR[AGENT#Denomination] n\'a pas participé au stage de formation suivant : </p>
<p>VAR[SESSION#libelle]</p>
<p>qui s\'est déroulé du VAR[SESSION#periode] ayant une durée de VAR[SESSION#duree].</p>
<p> </p>
<p>VAR[AGENT#Denomination] a suivi une durée de VAR[INSCRIPTION#duree].</p>
<p> </p>
<p>Je vous prie de bien vouloir justifier votre absence auprès du bureau de la formation.</p>
<p> </p>
<p><br />Le bureau de formation<br />drh.formation@univ-demo.fr<br /><br /><br /><br /></p>
<p> </p>', null, 'Formation\Provider\Template');


-- ---------------------------------------------------------------------------------------------------------------------
-- TEMPLATE - MAIL ------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_DEMANDE_EXTERNE_TOTALEMENT_VALIDEE', '<p>Mail envoyé vers le CCC lorsque d''une demande extérieur est totalement validée</p>', 'mail', 'Formation\Provider\Template', 'La demande stage externe de VAR[AGENT#Denomination] est maintenant totalement validée', e'<p>Bonjour,</p>
<p>La demande de stage externe suivante est maintenant totalement validée.</p>
<table style="width: 450px; height: 116px;">
<tbody>
<tr>
<td style="width: 197.717px;">Agent</td>
<td style="width: 234.283px;">VAR[AGENT#Denomination]</td>
</tr>
<tr>
<td style="width: 197.717px;">Libellé du stage</td>
<td style="width: 234.283px;">VAR[DEMANDE#libelle]</td>
</tr>
<tr>
<td style="width: 197.717px;">Organisme formateur</td>
<td style="width: 234.283px;">VAR[DEMANDE#organisme]</td>
</tr>
<tr>
<td style="width: 197.717px;">Date de début</td>
<td style="width: 234.283px;">VAR[DEMANDE#debut]</td>
</tr>
<tr>
<td style="width: 197.717px;">Date de fin</td>
<td style="width: 234.283px;">VAR[DEMANDE#fin]</td>
</tr>
</tbody>
</table>
<p>L\'application Mes Formations<br />VAR[EMC2#AppLink]</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_DEMANDE_EXTERNE_VALIDATION_AGENT', '<p>Courrier envoyé vers le responsable de l''agent lorsque celui-ci valide un demande de formation externe.</p>', 'mail', 'Formation\Provider\Template', 'Demande de validation d''une demande de formation externe de VAR[AGENT#Denomination]', e'<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#Date]</p>
<p> Bonjour,</p>
<p>VAR[AGENT#Prenom] VAR[AGENT#NomUsage] vient d\'effectuer une demande de formation externe (hors plan de formation).<br />Vous pouvez désormais en tant que Responsable de VAR[AGENT#Prenom] VAR[AGENT#NomUsage] valider (ou refuser) cette demande.</p>
<p> </p>
<p>Informations à propos de la demande :</p>
<table style="width: 572px; height: 89px;">
<tbody>
<tr>
<td style="width: 184.217px;"><strong>Intitulé de la formation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#libelle]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Organisme formateur :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#organisme]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Lieu :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#lieu]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Début :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#debut]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Fin :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#fin]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Motivation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#motivation]</td>
</tr>
</tbody>
</table>
<p> </p>
<p>Vous pouvez valider (ou refuser) celle-ci en vous connectant à Mes Formations (VAR[EMC2#AppLink]), dans l\'onglet "Demande de formation de votre structure".</p>
<p> </p>
<p>Le bureau conseil carrière compétences,<br />drh.formation@unicaen.fr<br />VAR[EMC2#AppLink]</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_DEMANDE_EXTERNE_VALIDATION_DRH', '<p>Courrier envoyé lors de la validation par la drh d''une demande externe (Agent et Responsable)</p>', 'mail', 'Formation\Provider\Template', 'Validation de la demande de formation externe de VAR[AGENT#Denomination] par le bureau gérant les formations', e'<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#Date]</p>
<p> </p>
<p>Bonjour,</p>
<p>Le demande suivante de formation externe (hors plan de formation) pour VAR[AGENT#Denomination] est <strong>validée</strong>.</p>
<table style="width: 572px; height: 89px;">
<tbody>
<tr>
<td style="width: 184.217px;"><strong>Intitulé de la formation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#libelle]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Organisme formateur :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#organisme]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Lieu :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#lieu]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Début :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#debut]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Fin :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#fin]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Motivation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#motivation]</td>
</tr>
</tbody>
</table>
<p> </p>
<p>VAR[AGENT#Denomination] est invité-e à procéder à son inscription auprès de l\'organisme sollicité.</p>
<p> </p>
<p>Le bureau conseil carrière compétences est à votre disposition et se charge des démarches administratives.</p>
<p> </p>
<p>Le bureau conseil carrière compétences,<br />drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_DEMANDE_EXTERNE_VALIDATION_REFUS', '<p>Mail envoyé lors du refus par le responsable ou la DRH</p>', 'mail', 'Formation\Provider\Template', 'Refus de la demande de formation externe de VAR[AGENT#Denomination]', e'<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p> </p>
<p>Bonjour,</p>
<p>Le demande suivante de formation externe (hors plan de formation) pour VAR[AGENT#Denomination] vient d\'être refusée.</p>
<table style="width: 572px; height: 89px;">
<tbody>
<tr>
<td style="width: 184.217px;"><strong>Intitulé de la formation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#libelle]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Organisme formateur :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#libelle]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Lieu :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#lieu]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Début :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#debut]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Fin :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#fin]</td>
</tr>
</tbody>
</table>
<p>Voici le motif du refus : <br />VAR[DEMANDE#refus]</p>
<p>Le bureau conseil carrière compétences,<br />drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_DEMANDE_EXTERNE_VALIDATION_RESP_AGENT', '<p>Courrier envoyé vers la DRH lorsque celui-ci valide une demande de formation externe est validé par un responsable.</p>', 'mail', 'Formation\Provider\Template', 'Validation de votre demande de formation externe de votre responsable', e'<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN 5</p>
<p>A Caen, le VAR[EMC2#Date]</p>
<p> </p>
<p>Bonjour,</p>
<p>Votre responsable vient de valider votre demande de formation externe (hors plan de formation), toutefois votre demande reste soumise à la validation du bureau conseil carrière compétences.</p>
<p><br />Informations à propos de la demande :</p>
<table style="width: 572px; height: 89px;">
<tbody>
<tr style="height: 35px;">
<td style="width: 184.217px; height: 35px;"><strong>Intitulé de la formation :</strong></td>
<td style="width: 368.783px; height: 35px;"> VAR[DEMANDE#libelle]</td>
</tr>
<tr style="height: 17px;">
<td style="width: 184.217px; height: 17px;"><strong>Organisme formateur :</strong></td>
<td style="width: 368.783px; height: 17px;"> VAR[DEMANDE#organisme]</td>
</tr>
<tr style="height: 17px;">
<td style="width: 184.217px; height: 17px;"><strong>Lieu :</strong></td>
<td style="width: 368.783px; height: 17px;"> VAR[DEMANDE#lieu]</td>
</tr>
<tr style="height: 17px;">
<td style="width: 184.217px; height: 17px;"><strong>Début :</strong></td>
<td style="width: 368.783px; height: 17px;"> VAR[DEMANDE#debut]</td>
</tr>
<tr style="height: 20.6px;">
<td style="width: 184.217px; height: 20.6px;"><strong>Fin :</strong></td>
<td style="width: 368.783px; height: 20.6px;"> VAR[DEMANDE#fin]</td>
</tr>
<tr style="height: 17px;">
<td style="width: 184.217px; height: 17px;"><strong>Motivation :</strong></td>
<td style="width: 368.783px; height: 17px;"> VAR[DEMANDE#motivation]</td>
</tr>
</tbody>
</table>
<p>Vous serez prochainement informé-e par courrier électronique de la suite réservée à votre demande.</p>
<p>Le bureau conseil carrière compétences,<br />drh.formation@unicaen.fr</p>
<p> </p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_DEMANDE_EXTERNE_VALIDATION_RESP_DRH', '<p>Courrier envoyé vers la DRH lorsque celui-ci valide une demande de formation externe validée par un responsable.</p>', 'mail', 'Formation\Provider\Template', 'Validation d''une demande de formation externe de VAR[AGENT#Denomination]', e'<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#Date]</p>
<p> </p>
<p>Bonjour,</p>
<p>Le responsable de l\'agent VAR[AGENT#Denomination] vient de valider une demande de formation externe (hors plan de formation).<br /><br /></p>
<p>La responsable du bureau conseil carrière compétences est chargée de procéder à la validation définitive de cette demande.</p>
<p>Informations à propos de la demande :</p>
<table style="width: 572px; height: 89px;">
<tbody>
<tr>
<td style="width: 184.217px;"><strong>Intitulé de la formation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#libelle]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Organisme formateur :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#organisme]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Lieu :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#lieu]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Début :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#debut]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Fin :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#fin]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Motivation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#motivation]</td>
</tr>
</tbody>
</table>
<p> Vous pouvez valider celle-ci en vous connectant à VAR[URL#App] &gt; Formations externes</p>
<p> </p>
<p><strong>EMC2</strong></p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_INSCRIPTION_1_AGENT', '<p>Demande d''inscription à une formation (Agent &gt; Responsable)</p>', 'mail', 'Formation\Provider\Template', 'Demande d''inscription de VAR[AGENT#Denomination] à la formation VAR[SESSION#libelle] du VAR[SESSION#periode]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Université de Caen Normandie<br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">À Caen, le VAR[EMC2#Date]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> <span style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;">VAR[AGENT#Denomination]</span> vient de procéder à une demande d\'inscription pour la formation <span style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;">VAR[SESSION#libelle] </span>du VAR[SESSION#periode]. </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Merci de vous connecter à l\'application "Mes Formations" (VAR[URL#App]) afin d\'accepter ou de refuser sa demande.<br />La liste des "demandes en attente" est accessible dans l\'onglet \'Demande de formation\' dans votre structure.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr<br />VAR[URL#App]</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_INSCRIPTION_2_RESPONSABLE_REFUS', '<p>Refus de la demande d''inscription par le responsable (Responsable &gt; Agent)</p>', 'mail', 'Formation\Provider\Template', 'Refus de la demande d''inscription à la formation VAR[SESSION#libelle] du VAR[SESSION#periode] par votre responsable', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#Date]</p>
<p><br />Bonjour,<br /><br />Votre responsable hiérarchique vient de refuser votre demande d\'inscription à la formation VAR[SESSION#libelle] du VAR[SESSION#periode].<br /><br />Vous trouverez ci-après le motif du refus de votre inscription :</p>
<table style="height: 30px;" width="648">
<tbody>
<tr>
<td style="width: 638px;">VAR[INSCRIPTION#justificationRefus]</td>
</tr>
</tbody>
</table>
<p><br />La responsable du bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr<br />VAR[URL#App]</p>', 'table {border:1px solid black;}');
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_INSCRIPTION_2_RESPONSABLE_VALIDATION', '<p>Demande d''inscription à une formation (Responsable &gt; DRH)</p>', 'mail', 'Formation\Provider\Template', 'Validation par le responsable de structure de la demande de formation de VAR[AGENT#Denomination] à la formation VAR[SESSION#libelle] du VAR[SESSION#periode]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#Date]</p>
<p>Bonjour,<br /><br />La demande de formation de VAR[AGENT#Denomination] à VAR[SESSION#libelle] du VAR[SESSION#periode] vient d\'être validée par le responsable hiérarchique.<br />Vous pouvez maintenant valider ou refuser cette demande de formation via l\'application "Mes Formations" : VAR[URL#SessionAfficher]<br /><br /></p>
<p>L\'application Mes Formations</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_INSCRIPTION_3_DRH_REFUS', '<p>Refus de la demande d''inscription par la DRH (DRH &gt; Agent)</p>', 'mail', 'Formation\Provider\Template', 'Refus de la demande d''inscription à la formation VAR[SESSION#libelle] du VAR[SESSION#periode] par la direction des ressources humaines.', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#Date]</p>
<p><br />Bonjour,<br /><br />Le bureau conseil carrière compétences vient de refuser votre demande d\'inscription à la formation VAR[SESSION#libelle] du VAR[SESSION#periode].<br />Vous trouverez ci-après le motif du refus de votre inscription :</p>
<table style="height: 30px;" width="648">
<tbody>
<tr>
<td style="width: 638px;">VAR[INSCRIPTION#justificationRefus]</td>
</tr>
</tbody>
</table>
<p> Le bureau conseil carrière compétences se tient à votre disposition pour toute information complémentaire.</p>
<p>La responsable du bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr<br />VAR[URL#App]</p>
<p> </p>', 'table {border:1px solid black;}');
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_INSCRIPTION_3_DRH_VALIDATION', '<p>Demande d''inscription à une formation (DRH &gt; Agent)</p>', 'mail', 'Formation\Provider\Template', 'Validation de la demande de formation de VAR[AGENT#Denomination] à la formation VAR[SESSION#libelle] du VAR[SESSION#periode]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#Date]<br /><br /> <br /><br />Bonjour,<br /><br /><br />La demande de formation de VAR[AGENT#Denomination] à VAR[SESSION#libelle] du VAR[SESSION#periode] vient d\'être validée par le bureau conseil, carrière, compétences.<br />Vous recevrez une notification quelques jours avant la formation et pourrez ainsi télécharger si besoin votre convocation directement sur l\'application Mes Formations (VAR[EMC2#AppLink])<br /><br />Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr<br />VAR[EMC2#AppLink]</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_INSCRIPTION_PREVENTION', null, 'mail', 'Formation\Provider\Template', 'Validation de l''inscription de VAR[AGENT#Denomination] à une formation de prévention', e'<p>Bonjour,</p>
<p>VAR[AGENT#Denomination] vient classé en liste principale pour une formation de prévention</p>
<table style="width: 573px;">
<tbody>
<tr>
<td style="width: 241.65px;">Agent</td>
<td style="width: 335.35px;">VAR[AGENT#Denomination]</td>
</tr>
<tr>
<td style="width: 241.65px;">Date de naissance</td>
<td style="width: 335.35px;">VAR[AGENT#datenaissance]</td>
</tr>
<tr>
<td style="width: 241.65px;">Libellé de la formation</td>
<td style="width: 335.35px;">VAR[SESSION#libelle]</td>
</tr>
<tr>
<td style="width: 241.65px;">Date de la session</td>
<td style="width: 335.35px;">VAR[SESSION#periode]</td>
</tr>
</tbody>
</table>
<p> </p>
<p>Bonne journée,<br />L\'application VAR[EMC2#appname]</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_NOTIFICATION_ABONNEMENT_FORMATION', '<p>Mail envoyé aux agents abonnés lors d''une nouvelle session</p>', 'mail', 'Formation\Provider\Template', 'Un session de formation vient être ouverte pour l''action de formation "VAR[SESSION#libelle]"', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br />DRH Bureau de la formation, conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">A Caen, le VAR[EMC2#Date]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Une nouvelle session de formation (VAR[SESSION#identification]) vient d\'ouvrir pour la formation "VAR[SESSION#libelle]".<br />Cette session se déroulera sur la période du VAR[SESSION#periode] aux dates suivantes : VAR[SESSION#seances]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">La formation sera assurée par : VAR[SESSION#formateurs]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Pour vous inscrire à cette formation, connectez vous à l\'application VAR[EMC2#AppName] (VAR[EMC2#AppLink]) dans l\'onglet \'Formations\'.<br /><br /></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Le bureau conseil carrière compétences,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">drh.formation@unicaen.fr</p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_NOTIFICATION_NOUVELLES_SESSIONS', '<p>Mail envoyé périodiquement pour annoncer les nouvelles sessions</p>', 'mail', 'Formation\Provider\Template', 'Nouvelles sessions de formation', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p> </p>
<p>Bonjour,<br /><br />Vous trouverez la liste des nouvelles sessions de formation ouvertes à l\'inscription : ###A REMPLACER###<br /><br />Si vous souhaitez vous inscrire à une de ces sessions, connectez vous sur EMC2 : VAR[URL#App].<br /><br /><br /></p>
<p>Le bureau conseil carrière compétences,</p>
<p>drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_NOTIFICATION_SESSION_IMMINENTE', '<p>Mail de rappel envoyé aux agents inscrits à une session de formation quelques jours avant la celle-ci</p>', 'mail', 'Formation\Provider\Template', 'Rappel de votre inscription à la formation VAR[FORMATION_INSTANCE#Libelle] du VAR[FORMATION_INSTANCE#Periode]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p>                                                                                                                                                                          <br /> <br /><br />Bonjour,<br /><br /> <br />Ce mail automatique est un rappel de votre inscription à la formation suivante :<br /><br />Libellé    VAR[FORMATION_INSTANCE#Libelle]<br />Période    VAR[FORMATION_INSTANCE#Periode]<br />Lieu    VAR[FORMATION_INSTANCE#Lieu]</p>
<p>En cas d\'empêchement, merci de vous désinscrire via l\'application EMC2 : VAR[URL#App].<br /><br /> <br /><br />Le bureau conseil, carrière, compétences vous souhaite une session de formation enrichissante.<br /><br /> <br /><br />La responsable du bureau conseil, carrière, compétences.<br /><br />drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_RAPPORT_CLOTURE_AUTOMATIQUE', '<p>Mail envoyé lors de clôture automatique des inscriptions ( &gt; DRH)</p>', 'mail', 'Formation\Provider\Template', 'Rapport de clôture automatique des inscriptions à une session de formation', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p> </p>
<p>Bonjour,<br /><br />Voici la liste des sessions de formations dont les inscriptions ont été automatiquement clôturées :<br />###A REMPLACER###<br /><br />Mes Formations</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_RAPPORT_CONVOCATION_AUTOMATIQUE', '<p>Mail envoyé lors de clôture automatique des inscriptions ( &gt; DRH)</p>', 'mail', 'Formation\Provider\Template', 'Rapport de clôture automatique des inscriptions à une session de formation', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p>Bonjour,<br /><br />Voici la liste des sessions de formation dont les convocations ont été automatiquement envoyées :<br />###A REMPLACER###</p>
<p>Mes Formations</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_SESSION_ANNULEE', '<p>Mail envoyé aux inscrits d''une session venant d''être annulée</p>', 'mail', 'Formation\Provider\Template', 'La session de formation VAR[SESSION#libelle] du VAR[SESSION#periode] est annulée', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN                                                                                                                                                                                                 </p>
<p>A Caen, le VAR[EMC2#date]</p>
<p> </p>
<p>Bonjour VAR[AGENT#Denomination],</p>
<p>Nous vous informons que la session de formation VAR[SESSION#libelle] du VAR[SESSION#periode] pour laquelle vous étiez inscrit, est annulée.</p>
<p>Si vous souhaitez vous inscrire de nouveau sur cette même formation, vous serez alors notifié dès la réouverture d\'une prochaine session.</p>
<p> </p>
<p>Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_SESSION_CONVOCATION', '<p>Mail de convocation envoyé aux agents</p>', 'mail', 'Formation\Provider\Template', 'La session de formation VAR[SESSION#libelle] du VAR[SESSION#periode] va bientôt commencer', '<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN                                                                                                                       <br /><br />A Caen, le VAR[EMC2#date]<br /><br /> <br />Bonjour VAR[AGENT#Denomination],<br /> <br /><br />La session de formation VAR[SESSION#libelle] (VAR[SESSION#identification]) du VAR[SESSION#periode] à laquelle vous êtes inscrit va débuter prochainement.<br /><br /><br />Pour rappel, cette formation se déroulera selon le calendrier suivant :<br />VAR[SESSION#seances]<br /><br />Vous pouvez retrouver sur l''application EMC2 (VAR[URL#App]) votre convocation qui vaut ordre de mission.<br />N''imprimez celle-ci que si nécessaire.<br /><br /> <br />Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr<br /><br /> P.S.: Vous pouvez retrouver les plans d’accès et des campus sur le site de l''Université https://www.unicaen.fr/universite/decouvrir/territoire/caen</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_SESSION_DEMANDE_RETOUR', e'<p>Mail demandant aux agents ayant suivi la formation de remplir le formulaire</p>
<p> </p>', 'mail', 'Formation\Provider\Template', 'Demande de saisie des formulaires de retour de la formation VAR[SESSION#libelle] du VAR[SESSION#periode]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN 5</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">À Caen, le VAR[EMC2#date]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour VAR[AGENT#Denomination],</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Vous venez de participer à la formation VAR[SESSION#libelle] du VAR[SESSION#periode]. Afin d\'obtenir votre attestation de présence, merci de compléter le questionnaire de satisfaction à votre disposition dans l\'application VAR[EMC2#appname].</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Le bureau conseil carrière compétences vous remercie.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_SESSION_EMARGEMENT', '<p>Mail notifiant des listes d''émargement</p>', 'mail', 'Formation\Provider\Template', 'La session de formation VAR[FORMATION_INSTANCE#Libelle] du VAR[FORMATION_INSTANCE#Periode] va bientôt commencer', e'<p>Université de Caen Normandie<br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN 5                                                                                                                                                     <br /><br />A Caen, le VAR[EMC2#date]</p>
<p><br /><br />Bonjour,<br /> <br /><br />Vous animez prochainement la formation VAR[FORMATION_INSTANCE#Libelle] du VAR[FORMATION_INSTANCE#Periode].<br /><br /><br />En vous connectant à l\'application EMC2, vous pourrez récupérer les listes d\'émargement de cette session de formation à l\'adresse suivante : VAR[URL#FormationInstanceAfficher].<br /><br /> <br /><br />Le bureau conseil, carrière, compétences vous souhaite une bonne animation de formation.<br /><br /> <br /><br />Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_SESSION_LISTE_COMPLEMENTAIRE', '<p>Mail envoyé aux agents (individuellement) lors d''une inscription en liste complémentaire</p>', 'mail', 'Formation\Provider\Template', 'Vous êtes inscrit sur la liste complémentaire de la session de formation VAR[SESSION#libelle]', e'<p>Université de Caen Normandie<br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN 5                                                                                                                                    <br /><br />À Caen, le VAR[EMC2#Date]<br /><br />Bonjour VAR[AGENT#Prenom] VAR[AGENT#NomUsage],<br /> <br />Vous êtes positionné-e sur liste complémentaire pour la formation VAR[SESSION#libelle] (VAR[SESSION#identification]) du VAR[SESSION#periode].<br />Dès qu\'une place se libère, vous en serez informé-e par courrier électronique. <br /><br />Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr<br />VAR[EMC2#AppLink]</p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, namespace, document_sujet, document_corps, document_css) VALUES ('FORMATION_SESSION_LISTE_PRINCIPALE', '<p>Mail envoyé (individuellement) aux agents de la liste principale.</p>', 'mail', 'Formation\Provider\Template', 'Vous êtes inscrit sur la liste principale de la session de formation VAR[SESSION#libelle]', e'<p>Université de Caen Normandie<br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN 5                                                                                                                                                                                     <br /><br />A Caen, le VAR[EMC2#Date]<br /><br /><br /><br />Bonjour VAR[AGENT#Prenom] VAR[AGENT#NomUsage],<br /><br />Votre inscription à la session de formation formation VAR[SESSION#libelle] (formation VAR[SESSION#identification]) du VAR[SESSION#periode] est confirmée.<br />Vous allez prochainement recevoir par courrier électronique votre convocation et vous pourrez la télécharger via l\'application Mes Formations (VAR[EMC2#AppLink]).<br /><br />Cette convocation vaut ordre de mission.<br /><br />Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr<br />VAR[EMC2#AppLink]<br /><br /><br /></p>
<p> </p>', null);

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('FORMATION_ABONNEMENT_POST_CLOTURE', '<p>Courrier électronique envoyé aux inscrit·es de la liste complémentaire les prévenants qu''ils ou elles ont été abonné·es à la formation</p>', 'mail', 'Abonnement à la formation VAR[SESSION#libelle] suite à la clôture de la session dont vous étiez inscrit·e', e'<p>Bonjour,</p>
<p>Vous étiez sur la liste complémentaire de la session de formation VAR[SESSION#libelle] se déroulant sur la période du VAR[SESSION#periode]).<br />Malheureusement aucune place n\'a pu se libéré et vous n\'avez pu participer à cette session de formation.</p>
<p>Afin de vous permettre de participer à la prochaine session de formation VAR[SESSION#libelle], vous avez été automatiquement abonné·e aux notifications associées à cette formation.<br />Vous serez ainsi prévenu des prochaines ouvertures de session de formation.</p>
<p>Vous pouvez vous désinscrire de ces notifications en allant sur l\'application VAR[MesFormations#AppLink] et en consultant la page "Plan de formation" accessible en suivant le lien VAR[URL#PlanDeFormation].</p>
<p>En vous souhaitant une bonne journée,<br />VAR[MesFormations#AppLink]</p>
<p> </p>', null, 'Formation\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('FORMATION_SESSION_ATTESTATION', '<p>Mail pour l''attestation envoyé aux agents</p>', 'mail',
'La session de formation VAR[SESSION#libelle] du VAR[SESSION#periode] va est maintenant terminée',
e'<p><strong>Université de démonstration</strong><br />DRH - Bureau des formations<br /><br />le VAR[EMC2#date]<br /><br /> <br />Bonjour VAR[AGENT#Denomination],<br /> <br />La session de formation VAR[SESSION#libelle] (VAR[SESSION#identification]) du VAR[SESSION#periode] à laquelle vous avez participé est maintenant terminée.</p><p>Vous pouvez récupérer votre attestation à l\'adresse suivante : VAR[URL#Attestation].<br />Cette attestation est aussi disponible (et restera disponible) directement sur l\'application VAR[MesFormations#AppLink] dans la rubrique "Mes Formations" &gt; "Mes formations réalisées".<br /><br /> <br />Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr<br /><br /><br /></p>', null, 'Formation\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('FORMATION_SESSION_ABSENCE', '<p>Mail pour le constat d''absence envoyé aux agents</p>', 'mail', 'La session de formation VAR[SESSION#libelle] du VAR[SESSION#periode] va est maintenant terminée', e'<p><strong>Université de démonstration</strong><br />DRH - Bureau des formations<br /><br />le VAR[EMC2#date]<br /><br /> <br />Bonjour VAR[AGENT#Denomination],<br /> <br />La session de formation VAR[SESSION#libelle] (VAR[SESSION#identification]) du VAR[SESSION#periode] à laquelle vous avez participé est maintenant terminée.</p>
<p>Des absences ont été renseignées pour cette session de formation. Vous pouvez récupérer votre constat d\'absence à l\'adresse suivante : VAR[URL#Absence].<br />Ce constat est aussi disponible (et restera disponible) directement sur l\'application VAR[MesFormations#AppLink] dans la rubrique "Mes Formations" &gt; "Mes formations réalisées".<br /><br /> <br />Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr<br /><br /><br /><br /></p>
<p> </p>', null, 'Formation\Provider\Template');

-- ---------------------------------------------------------------------------------------------------------------------
-- MACROS --------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('URL#PlanDeFormation', '<p>Fourni un lieu vers la page des plans de formation courants</p>', 'UrlService', 'getUrlPlanDeFormation'),
    ('MesFormations#AppLink', '<p>Affiche le nom de l''application de formation et fourni un lien vers celle-ci</p>', 'UrlService', 'getMesFormationsUrl')
;

-- Macro portant sur les inscriptions
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('INSCRIPTION#justificationResponsable', '<p>Retourne la motivation du responsable</p>', 'inscription', 'getJustificationResponsable'),
    ('INSCRIPTION#justificationRefus', '<p>Retourne la motivation de la désinscription ou du refus</p>', 'inscription', 'getJustificationRefus'),
    ('INSCRIPTION#justificationAgent', '<p>Retourne la motivation de l agent</p>', 'inscription', 'getJustificationAgent'),
    ('INSCRIPTION#duree', '<p>Affiche la durée de présence de l inscrit</p>', 'inscription', 'getDureePresence');

-- Macro portant sur les actions de formation
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('FORMATION#Libelle', '<p>Retourne le libell&eacute; de la formation</p>', 'formation', 'toStringLibelle');

-- Macro portant sur les sessions
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('SESSION#seances', '', 'session', 'getListeJournees'),
    ('SESSION#periode', 'retourne la période sous la forme <em>DEBUT au FIN</em>', 'session', 'getPeriode'),
    ('SESSION#lieu', '', 'session', 'getLieuString'),
    ('SESSION#libelle', 'retourne le libellé de la session', 'session', 'getInstanceLibelle'),
    ('SESSION#identification', 'retourne l identifiant unique de la session sous la forme <em>ACTION/SESSION</em>', 'session', 'getInstanceCode'),
    ('SESSION#formateurs', 'retourne la liste des formateurs sous la forme d un tableau', 'session', 'getListeFormateurs'),
    ('SESSION#duree', 'retourne la durée en heure de la session', 'session', 'getDuree'),
    ('SESSION#id', '<p>Retroune l''id numérique de la session</p>', 'session', 'getId')
;

-- Macro portant sur les inscrits -- TODO REGROUPER AVEC INSCRIPTION
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('FORMATION_INSCRIT#TEMPS_PRESENCE', '<p>Dur&eacute;e de pr&eacute;sence d''un agent inscrit &agrave; une action de formation.</p>', 'inscrit', 'getDureePresence'),
    ('FORMATION_INSCRIT#Complement', null, 'inscrit', 'getComplement');

-- Macro d'affichage d'URL
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('URL#FormationInstanceAfficher', '<p>Retourne l''URL pour accéder à l''affichage d''une instance de formation</p>', 'UrlService', 'getUrlFormationInstanceAfficher');

-- Macro portant sur les demandes de formation externe
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
    ('DEMANDE#debut', '', 'demande', 'getDebutAsString'),
    ('DEMANDE#fin', '', 'demande', 'getFinAsString'),
    ('DEMANDE#libelle', '<p>Retourne le libellé de la formation associé à la demande externe</p>', 'demande', 'getLibelle'),
    ('DEMANDE#lieu', '', 'demande', 'getLieu'),
    ('DEMANDE#motivation', '', 'demande', 'getJustificationAgent'),
    ('DEMANDE#organisme', '<p>Retourne l''organisme de la formation associée à la demande externe</p>', 'demande', 'getOrganisme')
;

-- Macro portant sur les UrlService et MacroService
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES
('URL#Attestation', '<p>Retroune le lien vers le téléchargement de l''inscription</p>', 'UrlService', 'getUrlAttestation'),
('URL#Convocation', '<p>Retroune le lien vers le téléchargement de la convocation</p>', 'UrlService', 'getUrlConvocation'),
('URL#Absence', '<p>Retroune le lien vers le téléchargement du constat d''absence</p>', 'UrlService', 'getUrlAbsence')
;



-- ---------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE -----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('axe','Gestion des axes de formation',310,'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'axe_index', 'Accéder à l''index', 10 UNION
    SELECT 'axe_ajouter', 'Ajouter', 20 UNION
    SELECT 'axe_modifier', 'Modifier', 30 UNION
    SELECT 'axe_historiser', 'Historiser/Restaurer', 40 UNION
    SELECT 'axe_supprimer', 'Supprimer', 50 UNION
    SELECT 'axe_afficher', 'Afficher', 15
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'axe';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationdomaine', 'Gestion des domaines (Formation)', 309, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationdomaine_index', 'Accéder à l''index', 10 UNION
    SELECT 'formationdomaine_afficher', 'Afficher', 20 UNION
    SELECT 'formationdomaine_ajouter', 'Ajouter', 30 UNION
    SELECT 'formationdomaine_modifier', 'Modifier', 40 UNION
    SELECT 'formationdomaine_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'formationdomaine_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationdomaine';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('demandeexterne', 'Gestion des demandes de formations externes', 'Formation\Provider\Privilege', 10000);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'demandeexterne_index', 'Accéder l''index', 10 UNION
    SELECT 'demandeexterne_afficher', 'Afficher une demande', 20 UNION
    SELECT 'demandeexterne_ajouter', 'Ajouter une demande', 30 UNION
    SELECT 'demandeexterne_modifier', 'Modifier une demande', 40 UNION
    SELECT 'demandeexterne_historiser', 'Historiser/restaurer une demande', 50 UNION
    SELECT 'demandeexterne_supprimer', 'Supprimer une demande ', 60 UNION
    SELECT 'demandeexterne_valider_agent', 'Valider une demande en tant qu''agent', 110 UNION
    SELECT 'demandeexterne_valider_responsable', 'Valider une demande en tant que responsable', 120 UNION
    SELECT 'demandeexterne_valider_gestionnaire', 'Valider une demande en tant que gestionnaire des formations', 125 UNION
    SELECT 'demandeexterne_valider_drh', 'Valider une demande en tant que direction des ressources humaines', 130 UNION
    SELECT 'demandeexterne_gerer', 'Gérer la demande externe', 140
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'demandeexterne';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('formation', 'Gestion des formations ', 'Formation\Provider\Privilege', 100);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formation_acces', 'Accés à l''index des formations', 10 UNION
    SELECT 'formation_afficher', 'Afficher une formation ', 20 UNION
    SELECT 'formation_ajouter', 'Ajouter une formation ', 30 UNION
    SELECT 'formation_modifier', 'Modifier une formation ', 40 UNION
    SELECT 'formation_historiser', 'Historiser/Restaurer une formation ', 50 UNION
    SELECT 'formation_supprimer', 'Supprimer une formation ', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formation';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('formationabonnement', 'Gestion du abonnement aux formations', 'Formation\Provider\Privilege', 1100);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationabonnement_abonner', 'S''abonner une formation', 0 UNION
    SELECT 'formationabonnement_desabonner', 'Se desinscrire d''une formation', 10 UNION
    SELECT 'formationabonnement_liste_agent', 'Lister les abonnements par agents', 20 UNION
    SELECT 'formationabonnement_liste_formation', 'Lister les abonnements par foramtions', 40 UNION
    SELECT 'formationabonnement_gerer', 'Gérer les abonnements', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationabonnement';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('formationinstancedocument', 'Gestion des formations - Documents', 'Formation\Provider\Privilege', 319);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationinstancedocument_convocation', 'Génération des convocations', 10 UNION
    SELECT 'formationinstancedocument_emargement', 'Génération des listes d''émargement', 20 UNION
    SELECT 'formationinstancedocument_attestation', 'Génération des attestations de formation', 30 UNION
    SELECT 'formationinstancedocument_absence', 'Génération des constats d''absence', 35 UNION
    SELECT 'formationinstancedocument_historique', 'Génération des historiques de formation', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstancedocument';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('formationinstance', 'Gestion des formations - Actions de formation', 'Formation\Provider\Privilege', 313);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationinstance_index', 'Accéder à l''index', 5 UNION
    SELECT 'formationinstance_afficher', 'Afficher une action de formation', 10 UNION
    SELECT 'formationinstance_ajouter', 'Ajouter une action de formation', 20 UNION
    SELECT 'formationinstance_modifier', 'Modifier une action de formation', 30 UNION
    SELECT 'formationinstance_historiser', 'Historiser/Restaurer une action de formation', 40 UNION
    SELECT 'formationinstance_supprimer', 'Supprimer une instance de formation', 50 UNION
    SELECT 'formationinstance_afficher_inscription', 'Afficher/Exporter les inscriptions à une instance de formation', 100 UNION
    SELECT 'formationinstance_gerer_inscription', 'Gérer les inscriptions à une instance de formation', 101 UNION
    SELECT 'formationinstance_gerer_seance', 'Gérer les séances d''une instance de formation', 110 UNION
    SELECT 'formationinstance_gerer_formateur', 'Gérer les formations d''une instance de formation', 120 UNION
    SELECT 'formationinstance_annuler', 'Annuler une session', 130
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstance';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('projetpersonnel', 'Gestion du projet personnel', 'Formation\Provider\Privilege', 1050);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'projetpersonnel_acces', 'Accéder au projet personnel', 10
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'projetpersonnel';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('planformation', 'Gestion du plan de formation', 'Formation\Provider\Privilege', 1000);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'planformation_courant', 'Accéder au plan de formation courant', 10 UNION
    SELECT 'planformation_index', 'Accéder à l''index', 20 UNION
    SELECT 'planformation_afficher', 'Afficher un plan de formation', 30 UNION
    SELECT 'planformation_ajouter', 'Ajouter un plan de formation', 40 UNION
    SELECT 'planformation_modifier', 'Modifier un plan de formation', 50 UNION
    SELECT 'planformation_supprimer', 'Supprimer un plan de formation', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'planformation';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('formationgroupe', 'Gestion des formations - Groupe', 'Formation\Provider\Privilege', 311);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationgroupe_afficher', 'Afficher un groupe de formation', 10 UNION
    SELECT 'formationgroupe_ajouter', 'Ajouter un groupe de formation', 20 UNION
    SELECT 'formationgroupe_modifier', 'Modifier un groupe de formation', 30 UNION
    SELECT 'formationgroupe_historiser', 'Historiser/Restaurer un groupe de formation', 40 UNION
    SELECT 'formationgroupe_supprimer', 'Supprimer un groupe de formation ', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationgroupe';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('formationtheme', 'Gestion des formations - Thème', 'Formation\Provider\Privilege', 312);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationtheme_afficher', 'Afficher un thème de formation ', 10 UNION
    SELECT 'formationtheme_ajouter', 'Ajouter un thème de formation', 20 UNION
    SELECT 'formationtheme_modifier', 'Modifier un thème de formation', 30 UNION
    SELECT 'formationtheme_historiser', 'Modifier un thème de formation ', 40 UNION
    SELECT 'formationtheme_supprimer', 'Supprimer un thème de formation ', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationtheme';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('formationinstancepresence', 'Gestion des formations - Présences', 'Formation\Provider\Privilege', 314);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationinstancepresence_afficher', 'Afficher les présences d''une action de formation', 10 UNION
    SELECT 'formationinstancepresence_modifier', 'Modifier les présences d''une action de formation', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstancepresence';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('formationinstancefrais', 'Gestion des formations - Frais', 'Formation\Provider\Privilege', 317);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationinstancefrais_afficher', 'Afficher les frais d''un agent', 10 UNION
    SELECT 'formationinstancefrais_modifier', 'Modifier les frais d''un agent', 20
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstancefrais';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('formationagent', 'Formation - Gestion des agents', 'Formation\Provider\Privilege', 1000);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationagent_index', 'Accéder à l''index des agents', 10 UNION
    SELECT 'formationagent_afficher', 'Afficher un agent', 20 UNION
    SELECT 'formationagent_mesagents', 'Affichage du menu - Mes agents -', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationagent';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('formationstructure', 'Gestion des structures', 'Formation\Provider\Privilege', 1100);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationstructure_index', 'Accéder à l''index des structures', 10 UNION
    SELECT 'formationstructure_afficher', 'Afficher une structure', 20 UNION
    SELECT 'formationstructure_messtructures', 'Affichage du menu - Mes structures -', 30

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationstructure';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('lagaf', 'Importation depuis les données de LAGAF', 'Formation\Provider\Privilege', 99998);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'import_lagaf', 'Lancer l''importation', 1
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'lagaf';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('formationinstanceinscrit', 'Gestion des formations - Inscrits', 'Formation\Provider\Privilege', 316);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationinstanceinscrit_modifier', 'Modifier un inscrit à une action de formation', 10 UNION
    SELECT 'inscription_valider_superieure', 'Valider une demande en tant que supérieure hiérarchique', 20 UNION
    SELECT 'inscription_valider_gestionnaire', 'Valider une inscription en tant que gestionnaire', 30

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstanceinscrit';


INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('stagiaireexterne', 'Gestion des stagiaires externes', 'Formation\Provider\Privilege', 100402);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'stagiaireexterne_index', 'Accéder à l''index', 10 UNION
    SELECT 'stagiaireexterne_afficher', 'Afficher', 20 UNION
    SELECT 'stagiaireexterne_ajouter', 'Ajouter', 30 UNION
    SELECT 'stagiaireexterne_modifier', 'Modifier', 40 UNION
    SELECT 'stagiaireexterne_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'stagiaireexterne_supprimer', 'Supprimer', 60 UNION
    SELECT 'stagiaireexterne_rechercher', 'Rechercher', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'stagiaireexterne';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('coutprevisionnel', 'Gestion des coûts prévisionnel', 2000, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'coutprevisionnel_index', 'Accéder à l''index', 10 UNION
    SELECT 'coutprevisionnel_afficher', 'Afficher', 20 UNION
    SELECT 'coutprevisionnel_ajouter', 'Ajouter', 30 UNION
    SELECT 'coutprevisionnel_modifier', 'Modifier', 40 UNION
    SELECT 'coutprevisionnel_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'coutprevisionnel_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'coutprevisionnel';


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formateur','Gestion des formateurs',350,'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formateur_index', 'Accéder à l''index', 10 UNION
    SELECT 'formateur_ajouter', 'Ajouter', 20 UNION
    SELECT 'formateur_modifier', 'Modifier', 30 UNION
    SELECT 'formateur_historiser', 'Historiser/Restaurer', 40 UNION
    SELECT 'formateur_supprimer', 'Supprimer', 50 UNION
    SELECT 'formateur_afficher', 'Afficher', 15 UNION
    SELECT 'formateur_mes_sessions', 'Accéder à la page - Mes Sessions -', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formateur';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('inscription','Gestion des inscriptions',400,'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'inscription_afficher', 'Afficher', 20 UNION
    SELECT 'inscription_enquete' ,'Répondre et Valider l''enquete',110
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'inscription';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationlieu','Gestion des lieus',360,'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationlieu_index', 'Accéder à l''index', 10 UNION
    SELECT 'formationlieu_afficher', 'Afficher', 20 UNION
    SELECT 'formationlieu_ajouter', 'Ajouter', 30 UNION
    SELECT 'formationlieu_modifier', 'Modifier', 40 UNION
    SELECT 'formationlieu_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'formationlieu_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationlieu';

-- ---------------------------------------------------------------------------------------------------------------------
-- EVENEMENT -----------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion) VALUES
    ('notification_nouvelle_session', 'notification_nouvelle_session', 'Notification hebdomadaire de nouvelle session de formation', null, 'P1W'),
    ('notification_rappel_session_imminente', 'notification_rappel_session_imminente', 'Notification de rappel d''une session de formation', null, null),
    ('cloture_automatique_inscription', 'cloture_automatique_inscription', 'cloture_automatique_inscription', null, 'P1D'),
    ('convocation_automatique', 'Convocation des agents aux formations imminentes', 'Convocation des agents aux formations imminentes', null, null),
    ('formation_demande_retour', 'Notification de demandes de retour pour les formations passées', 'Notification de demandes de retour pour les formations passées', null, null),
    ('formation_session_cloture', 'Clotûre des sessions ', 'Clotûre des sessions ', null, null);

-- ---------------------------------------------------------------------------------------------------------------------
-- INSERT ELEMENTS MINIMAUX --------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO formation_axe (libelle, description, couleur, ordre) VALUES
('Formations externes', '<p>Cet axe est utilis&eacute; pour regrouper tous les th&egrave;mes des formations externes</p>', '#555753', 9999);

-- ---------------------------------------------------------------------------------------------------------------------
-- OBSERVATION ---------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_observation_observation_type (code, libelle, categorie) VALUES
('OBS_DE_COMMENTAIRE', 'Observations émises sur une demande à titre individuel par les bureaux des formations', 'Formation');