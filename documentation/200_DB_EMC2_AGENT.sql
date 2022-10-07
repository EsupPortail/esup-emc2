create table agent
(
    c_individu            varchar(40) not null unique
        constraint agent_pk primary key,
    prenom                varchar(64),
    nom_usage             varchar(64),
    nom_famille           varchar(256),
    date_naissance        date,
    sexe                  varchar(1),
    utilisateur_id        integer constraint agent_user_id_fk references unicaen_utilisateur_user on delete set null,
    login                 varchar(256),
    email                 varchar(1024),
    t_contrat_long        varchar(1),
    id                    bigint,
    source_id             bigint,
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table agent_carriere_affectation
(
    id                    bigint not null unique
        constraint agent_affectation_pk primary key,
    agent_id              varchar(40) not null,
    structure_id          bigint not null,
    date_debut            timestamp not null,
    date_fin              timestamp,
    t_principale          varchar(1)   default 'N'::character varying,
    source_id             bigint,
    id_orig               varchar(255),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table agent_carriere_echelon
(
    id                    bigint not null unique
        constraint agent_carriere_echelon_pk primary key,
    agent_id              varchar(40) not null
        constraint agent_carriere_echelon_agent_c_individu_fk references agent on delete cascade,
    echelon               integer                        not null,
    d_debut               date                           not null,
    d_fin                 date                           ,
    source_id             bigint                         not null,
    id_orig               varchar(100),
    created_on            timestamp default CURRENT_DATE not null,
    updated_on            timestamp,
    deleted_on            timestamp,
    histo_createur_id     bigint                         not null,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table agent_carriere_quotite
(
    id                    bigint not null unique
        constraint agent_quotite_pk primary key,
    agent_id              varchar(40) not null
        constraint agent_carriere_quotite_agent_c_individu_fk references agent on delete cascade,
    quotite               integer,
    d_debut               timestamp,
    d_fin                 timestamp,
    source_id             bigint                         not null,
    id_orig               varchar(100),
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0),
    histo_createur_id     bigint                         not null,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table agent_carriere_grade
(
    id varchar(40) not null constraint agent_grade_pk primary key,
    agent_id varchar(40) not null,
    structure_id integer,
    grade_id integer,
    corps_id integer,
    bap_id integer,
    d_debut timestamp,
    d_fin timestamp,
    source_id             bigint                         not null,
    id_orig               varchar(100),
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0),
    histo_createur_id     bigint                         not null,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table agent_carriere_statut
(
    id BIGINT not null constraint agent_statut_pk primary key,
    agent_id varchar(40) not null,
    structure_id integer,
    grade_id integer,
    corps_id integer,
    bap_id integer,
    d_debut timestamp,
    d_fin timestamp,
    source_id             bigint                         not null,
    id_orig               varchar(100),
    t_titulaire varchar(1) not null,
    t_cdi varchar(1) not null,
    t_cdd varchar(1) not null,
    t_vacataire varchar(1) not null,
    t_enseignant varchar(1) not null,
    t_administratif varchar(1) not null,
    t_chercheur varchar(1) not null,
    t_doctorant varchar(1) not null,
    t_detache_in varchar(1) not null,
    t_detache_out varchar(1) not null,
    t_dispo varchar(1) not null,
    t_heberge varchar(1) not null,
    t_emerite varchar(1) not null,
    t_retraite varchar(1) not null,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0),
    histo_createur_id     bigint                         not null,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);
