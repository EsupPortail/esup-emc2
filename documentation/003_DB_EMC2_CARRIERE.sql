create table carriere_correspondance
(
    id                    bigint not null unique constraint correspondance_pk primary key,
    c_bap                 varchar(10),
    lib_court             varchar(20),
    lib_long              varchar(200),
    d_ouverture           timestamp,
    d_fermeture           timestamp,
    source_id             bigint,
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table carriere_corps
(
    id                    bigint not null unique
        constraint corps_pk primary key,
    lib_court             varchar(20),
    lib_long              varchar(200),
    code                  varchar(10) not null,
    categorie             varchar(10),
    niveau                integer,
    niveaux_id            integer
        constraint carriere_corps_carriere_niveau_enveloppe_id_fk references carriere_niveau_enveloppe on delete set null,
    d_ouverture           timestamp,
    d_fermeture           timestamp,
    source_id             bigint,
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table carriere_grade
(
    id                    bigint not null unique
        constraint grade_pk primary key,
    lib_court             varchar(20),
    lib_long              varchar(200),
    code                  varchar(20) not null,
    d_ouverture           timestamp,
    d_fermeture           timestamp,
    source_id             bigint,
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);
