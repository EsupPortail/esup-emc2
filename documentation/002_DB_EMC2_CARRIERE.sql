create table carriere_niveau
(
    id serial                                       constraint niveau_definition_pk primary key,
    niveau                integer       not null,
    libelle               varchar(1024) not null,
    description           text,
    label                 varchar(64)   not null,
    histo_creation        timestamp     not null,
    histo_createur_id     integer       not null    constraint niveau_definition_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer                   constraint niveau_definition_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer                   constraint niveau_definition_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index niveau_definition_id_uindex on carriere_niveau (id);

create table carriere_niveau_enveloppe
(
    id serial                                       constraint niveau_enveloppe_pk primary key,
    borne_inferieure_id   integer   not null        constraint niveau_enveloppe_niveau_definition_id_fk references carriere_niveau,
    borne_superieure_id   integer   not null        constraint niveau_enveloppe_niveau_definition_id_fk_2 references carriere_niveau,
    valeur_recommandee_id integer                   constraint niveau_enveloppe_niveau_definition_id_fk_3 references carriere_niveau on delete set null,
    description           text,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null        constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer                   constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction     integer,
    histo_destructeur_id  integer                   constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index niveau_enveloppe_id_uindex on carriere_niveau_enveloppe (id);

create table carriere_categorie
(
    id serial                                       constraint categorie_pk primary key,
    code                  varchar(255)  not null,
    libelle               varchar(1024) not null,
    histo_creation        timestamp     not null,
    histo_createur_id     integer       not null    constraint categorie_user_id_fk references unicaen_utilisateur_user,
    histo_modification    timestamp     not null,
    histo_modificateur_id integer       not null    constraint categorie_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer                   constraint categorie_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index categorie_code_uindex on carriere_categorie (code);
create unique index categorie_id_uindex on carriere_categorie (id);

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
    id                    bigint not null unique constraint corps_pk primary key,
    lib_court             varchar(20),
    lib_long              varchar(200),
    code                  varchar(10) not null,
    categorie             varchar(10),
    niveau                integer,
    niveaux_id            integer constraint carriere_corps_carriere_niveau_enveloppe_id_fk references carriere_niveau_enveloppe on delete set null,
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
    id                    bigint not null unique constraint grade_pk primary key,
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
