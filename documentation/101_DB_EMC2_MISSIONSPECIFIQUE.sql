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

create unique index mission_specifique_theme_id_uindex
    on mission_specifique_theme (id);

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

create unique index mission_specifique_type_id_uindex
    on mission_specifique_type (id);

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

create unique index mission_specifique_id_uindex
    on mission_specifique (id);

