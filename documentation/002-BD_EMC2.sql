-- NIVEAU --------------------------------------------------------------------------------------------------------------

create table niveau_definition
(
    id serial not null constraint niveau_definition_pk primary key,
    niveau integer not null,
    libelle varchar(1024) not null,
    description text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint niveau_definition_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint niveau_definition_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint niveau_definition_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user,
    label varchar(64) not null
);

create unique index niveau_definition_id_uindex on niveau_definition (id);

create table niveau_enveloppe
(
    id serial not null constraint niveau_enveloppe_pk primary key,
    borne_inferieure_id integer not null constraint niveau_enveloppe_niveau_definition_id_fk references niveau_definition,
    borne_superieure_id integer not null constraint niveau_enveloppe_niveau_definition_id_fk_2 references niveau_definition,
    valeur_recommandee_id integer constraint niveau_enveloppe_niveau_definition_id_fk_3 references niveau_definition on delete set null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction integer,
    histo_destructeur_id integer constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user,
    description text
);

create unique index niveau_enveloppe_id_uindex on niveau_enveloppe (id);

-- MODULE CARRIERE -----------------------------------------------------------------------------------------------------

create table carriere_categorie
(
    id serial not null constraint categorie_pk primary key,
    code varchar(255) not null,
    libelle varchar(1024) not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint categorie_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint categorie_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint categorie_user_id_fk_3 references unicaen_utilisateur_user
);

create unique index categorie_code_uindex on carriere_categorie (code);
create unique index categorie_id_uindex on carriere_categorie (id);

create table carriere_corps
(
    id integer not null constraint corps_pk primary key,
    lib_court varchar(20),
    lib_long varchar(200),
    code varchar(10) not null,
    categorie varchar(10),
    histo timestamp,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0),
    niveau integer,
    niveau_id integer constraint corps_niveau_definition_id_fk references niveau_definition on delete set null
);

create unique index corps_code_uindex on carriere_corps (code);

create table carriere_correspondance
(
    id serial not null constraint correspondance_pk primary key,
    c_bap varchar(10),
    lib_court varchar(20),
    lib_long varchar(200),
    old_id integer,
    histo timestamp,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0)
);

create table carriere_grade
(
    id integer not null constraint grade_pk primary key,
    lib_court varchar(20),
    lib_long varchar(200),
    code varchar(10) not null,
    histo timestamp,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0)
);

-- MINIMUM -------------------------------------------------------------------------------------------------------------

create table agent
(
    c_individu varchar(40) not null constraint agent_pk primary key,
    utilisateur_id integer constraint agent_user_id_fk references unicaen_utilisateur_user on delete set null,
    prenom varchar(64),
    nom_usage varchar(64),
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0),
    octo_id varchar(40),
    preecog_id varchar(40),
    harp_id integer,
    login varchar(256),
    email varchar(1024),
    sexe varchar(1),
    t_contrat_long varchar(1),
    date_naissance date,
    nom_famille varchar(256)
);

