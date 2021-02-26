-- CREATION DES TABLES --------------------------------------------------------------------------

-- table catégorie
create table unicaen_parametre_categorie
(
    id serial not null,
    code varchar(1024) not null,
    libelle varchar(1024) not null,
    description text,
    ordre integer default 9999
);

create unique index unicaen_parametre_categorie_code_uindex
    on unicaen_parametre_categorie (code);

create unique index unicaen_parametre_categorie_id_uindex
    on unicaen_parametre_categorie (id);

alter table unicaen_parametre_categorie
    add constraint unicaen_parametre_categorie_pk
        primary key (id);

-- table parametre
create table unicaen_parametre_parametre
(
    id serial not null
        constraint unicaen_parametre_parametre_pk
            primary key,
    categorie_id integer not null
        constraint unicaen_parametre_parametre_unicaen_parametre_categorie_id_fk
            references unicaen_parametre_categorie,
    code varchar(1024) not null,
    libelle varchar(1024) not null,
    description text,
    valeurs_possibles text,
    valeur text,
    ordre integer default 9999
);

alter table unicaen_parametre_parametre owner to ad_preecog_prod;

create unique index unicaen_parametre_parametre_id_uindex
    on unicaen_parametre_parametre (id);

create unique index unicaen_parametre_parametre_code_categorie_id_uindex
    on unicaen_parametre_parametre (code, categorie_id);

