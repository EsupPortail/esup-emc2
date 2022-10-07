create table element_niveau
(
    id                    serial
        constraint maitrise_niveau_pk
            primary key,
    type                  varchar(256) not null,
    libelle               varchar(256) not null,
    niveau                integer      not null,
    description           text,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint maitrise_niveau_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint maitrise_niveau_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint maitrise_niveau_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);
create unique index maitrise_niveau_type_niveau_uindex on element_niveau (type, niveau);

-- APPLICATION ----------------------------------------

create table element_application_theme
(
    id                    serial
        constraint application_groupe_pk
        primary key,
    libelle               varchar(1024),
    couleur               varchar(255),
    ordre                 integer   default 9999,
    histo_creation        timestamp default ('now'::text)::date not null,
    histo_createur_id     integer   default 0                   not null
        constraint application_groupe_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint application_groupe_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint application_groupe_user_id_fk_3
        references unicaen_utilisateur_user
);
create unique index application_groupe_id_uindex  on element_application_theme (id);

create table element_application
(
    id                    serial
        primary key,
    libelle               varchar(128)                          not null,
    description           varchar(2048),
    url                   varchar(128),
    actif                 boolean   default true                not null,
    groupe_id             integer
        constraint element_application_theme_id_fk
        references element_application_theme
        on delete set null,
    histo_creation        timestamp default ('now'::text)::date not null,
    histo_createur_id     integer   default 0                   not null
        constraint element_application_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint element_application_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint element_application_user_id_fk_3
        references unicaen_utilisateur_user
);

create unique index application_informations_id_uindex    on element_application (id);



create table element_application_element
(
    id                    serial
        constraint application_element_pk
        primary key,
    application_id        integer   not null
        constraint application_element_application_informations_id_fk
        references element_application
        on delete cascade,
    commentaire           text,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint application_element_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp not null,
    histo_modificateur_id integer   not null
        constraint application_element_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint application_element_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user,
    validation_id         integer
        constraint application_element_unicaen_validation_instance_id_fk
        references unicaen_validation_instance
        on delete set null,
    niveau_id             integer
        constraint application_element_maitrise_niveau_id_fk
        references element_niveau
        on delete set null,
    clef                  boolean
);


create unique index application_element_id_uindex
    on element_application_element (id);

-- COMPETENCE ----------------------------------------

create table element_competence_theme
(
    id                    serial
        constraint competence_theme_pk
        primary key,
    libelle               varchar(256) not null,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint competence_theme_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp    not null,
    histo_modificateur_id integer
        constraint competence_theme_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint competence_theme_user_id_fk
        references unicaen_utilisateur_user
);

create unique index competence_theme_id_uindex
    on element_competence_theme (id);

create table element_competence_type
(
    id                    serial
        constraint competence_type_pk
        primary key,
    libelle               varchar(256) not null,
    ordre                 integer,
    couleur               varchar(255),
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint competence_type_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint competence_type_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint competence_type_user_id_fk
        references unicaen_utilisateur_user
);


create unique index competence_type_id_uindex
    on element_competence_type (id);

create table element_competence
(
    id                    serial
        constraint competence_pk
        primary key,
    libelle               varchar(256) not null,
    description           text,
    histo_creation        timestamp    not null,
    type_id               integer
        constraint competence_type__fk
        references element_competence_type
        on delete set null,
    theme_id              integer
        constraint competence_theme__fk
        references element_competence_theme
        on delete set null,
    source                varchar(256),
    id_source             integer,
    histo_createur_id     integer      not null
        constraint competence_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint competence_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint competence_destructeur_fk
        references unicaen_utilisateur_user
);

create unique index competence_id_uindex
    on element_competence (id);

create table element_competence_element
(
    id                    serial
        constraint competence_element_pk
        primary key,
    competence_id         integer   not null
        constraint competence_element_competence_informations_id_fk
        references element_competence
        on delete cascade,
    commentaire           text,
    validation_id         integer
        constraint competence_element_unicaen_validation_instance_id_fk
        references unicaen_validation_instance
        on delete set null,
    niveau_id             integer
        constraint competence_element_maitrise_niveau_id_fk
        references element_niveau
        on delete set null,
    clef                  boolean default false,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint competence_element_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp not null,
    histo_modificateur_id integer
        constraint competence_element_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint competence_element_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user
);


create unique index competence_element_id_uindex
    on element_competence_element (id);

-- FORMATION --------------------------------

create table formation_groupe
(
    id                    serial
        constraint formation_groupe_pk
            primary key,
    libelle               varchar(1024),
    couleur               varchar(255),
    ordre                 integer default 9999,
    source_id             bigint,
    id_source             integer,
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
    description           text
);

create unique index formation_groupe_id_uindex
    on formation_groupe (id);

create table formation_theme
(
    id                    serial
        constraint formation_theme_pk
            primary key,
    libelle               varchar(1024) not null,
    histo_creation        timestamp     not null,
    histo_createur_id     integer       not null
        constraint formation_theme_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_theme_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_theme_destructeur_fk
            references unicaen_utilisateur_user
);


create unique index formation_theme_id_uindex
    on formation_theme (id);

create table formation
(
    id                    serial
        constraint formation_pk
            primary key,
    libelle               varchar(256)         not null,
    description           text,
    lien                  varchar(1024),
    theme_id              integer
        constraint formation_formation_theme_id_fk
            references formation_theme
            on delete set null,
    groupe_id             integer
        constraint formation_formation_groupe_id_fk
            references formation_groupe
            on delete set null,
    source_id             bigint,
    id_source             integer,
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
    rattachement          varchar(1024)
);


create unique index formation_id_uindex
    on formation (id);

