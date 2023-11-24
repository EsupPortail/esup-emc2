create table configuration_entretienpro
(
    id                    serial
        constraint configuration_entretienpro_pk
        primary key,
    operation             varchar(64)  not null,
    valeur                varchar(128) not null,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint configuration_entretienpro_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp    not null,
    histo_modificateur_id integer      not null
        constraint configuration_entretienpro_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint configuration_entretienpro_destructeur_fk
        references unicaen_utilisateur_user
);

create unique index configuration_entretienpro_id_uindex
    on configuration_entretienpro (id);

create table configuration_fichemetier
(
    id                    serial
        constraint configuration_fichemetier_pk
        primary key,
    operation             varchar(64) not null,
    entity_type           varchar(255),
    entity_id             varchar(255),
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint configuration_fichemetier_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp   not null,
    histo_modificateur_id integer     not null
        constraint configuration_fichemetier_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint configuration_fichemetier_destructeur_fk
        references unicaen_utilisateur_user
);


