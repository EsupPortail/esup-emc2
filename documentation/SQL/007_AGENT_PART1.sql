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

create unique index agent_missionspecifique_id_uindex
    on agent_missionspecifique (id);

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

alter table agent_hierarchie_superieur
    owner to ad_emc2_demo;

create index agent_superieur_agent_id_index
    on agent_hierarchie_superieur (agent_id);

create index agent_superieur_superieur_id_index
    on agent_hierarchie_superieur (superieur_id);

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

alter table agent_hierarchie_autorite
    owner to ad_emc2_demo;

create index agent_autorite_agent_id_index
    on agent_hierarchie_autorite (agent_id);

create index agent_autorite_autorite_id_index
    on agent_hierarchie_autorite (autorite_id);

create table agent_fichier
(
    agent   varchar(40) not null,
    fichier varchar(13) not null
        constraint agent_fichier_fichier_fk
        references fichier_fichier
        on delete cascade,
    constraint agent_fichier_pk
        primary key (agent, fichier)
);

alter table agent_fichier
    owner to ad_emc2_demo;

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

alter table agent_element_application
    owner to ad_emc2_demo;

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

alter table agent_element_formation
    owner to ad_emc2_demo;

