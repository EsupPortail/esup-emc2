create table fichier_fichier
(
    id                    varchar(13)  not null
        constraint fichier_fichier_pk
        primary key,
    nom_original          varchar(256) not null,
    nom_stockage          varchar(256) not null,
    nature                integer      not null,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null,
    histo_modification    timestamp    not null,
    histo_modificateur_id integer      not null,
    histo_destruction     timestamp,
    histo_destructeur_id  integer,
    type_mime             varchar(256) not null,
    taille                varchar(256)
);

create unique index fichier_fichier_id_uindex
    on fichier_fichier (id);
create unique index fichier_fichier_nom_stockage_uindex
    on fichier_fichier (nom_stockage);

create table fichier_nature
(
    id          serial not null
        constraint fichier_nature_pk
        primary key,
    code        varchar(64)                                                not null,
    libelle     varchar(256)                                               not null,
    description varchar(2048)
);

create unique index fichier_nature_code_uindex
    on fichier_nature (code);

create unique index fichier_nature_id_uindex
    on fichier_nature (id);

