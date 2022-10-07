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

create unique index formation_formation_abonnement_id_uindex
    on formation_formation_abonnement (id);


create table formation_demande_externe
(
    id                    serial
        constraint formation_demande_externe_pk
        primary key,
    libelle               varchar(1024)           not null,
    organisme             varchar(1024)           not null,
    contact               varchar(1024)           not null,
    pourquoi              text,
    montant               text,
    lieu                  varchar(1024)           not null,
    debut                 date                    not null,
    fin                   date                    not null,
    motivation            text                    not null,
    prise_en_charge       boolean   default false not null,
    cofinanceur           varchar(1024),
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint formation_demande_externe_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_demande_externe_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_demande_externe_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user,
    agent_id              varchar(40)             not null
        constraint formation_demande_externe_agent_c_individu_fk
        references agent
);

create unique index formation_demande_externe_id_uindex
    on formation_demande_externe (id);

create table formation_demande_externe_validation
(
    demande_id integer not null
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


