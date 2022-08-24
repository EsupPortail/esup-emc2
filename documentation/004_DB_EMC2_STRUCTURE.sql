create table structure_type
(
    id                    bigint not null unique
        constraint structure_type_pk primary key,
    code                  varchar(64)                                                        not null,
    libelle               varchar(256)                                                       not null,
    description           text,
    source_id             bigint,
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table structure
(
    id                    bigint not null unique
        constraint structure_pk primary key,
    code                  varchar(40),
    sigle                 varchar(40),
    libelle_court         varchar(128),
    libelle_long          varchar(1024),
    type_id               bigint
        constraint structure_structure_type_id_fk references structure_type on delete set null,
    d_ouverture           timestamp,
    d_fermeture           timestamp,
    fermeture_ow          timestamp,
    resume_mere           boolean default false,
    description           text,
    adresse_fonctionnelle varchar(1024),
    parent_id             bigint,
    niv2_id               bigint
        constraint structure_structure_id_saved_fk references structure on delete set null,
    niv2_id_ow            bigint
        constraint structure_structure_ow_id_fk references structure on delete set null,
    source_id             bigint,
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);



