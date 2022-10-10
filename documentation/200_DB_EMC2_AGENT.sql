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

create table agent_ccc_ppp
(
    id                     serial
        constraint agent_ppp_pk
            primary key,
    agent_id               varchar(64)   not null
        constraint agent_ppp_agent_c_individu_fk
            references agent
            on delete cascade,
    type                   varchar(1024) not null,
    libelle                varchar(1024) not null,
    complement             text,
    date_debut             timestamp,
    date_fin               timestamp,
    etat_id                integer
        constraint agent_ppp_unicaen_etat_etat_id_fk
            references unicaen_etat_etat
            on delete set null,
    formation_cpf          double precision,
    formation_cout         double precision,
    formation_prisencharge double precision,
    formation_organisme    varchar(1024),
    histo_creation         timestamp     not null,
    histo_createur_id      integer       not null
        constraint agent_ppp_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification     timestamp,
    histo_modificateur_id  integer
        constraint agent_ppp_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction      timestamp,
    histo_destructeur_id   integer
        constraint agent_ppp_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);

create unique index agent_ppp_id_uindex
    on agent_ccc_ppp (id);

create table agent_ccc_stageobs
(
    id                    serial
        constraint agent_stageobs_pk
            primary key,
    agent_id              varchar(64) not null
        constraint agent_stageobs_agent_c_individu_fk
            references agent
            on delete cascade,
    structure_id          integer,
    metier_id             integer
        constraint agent_stageobs_metier_id_fk
            references metier_metier
            on delete set null,
    complement            text,
    date_debut            timestamp,
    date_fin              timestamp,
    etat_id               integer
        constraint agent_stageobs_unicaen_etat_etat_id_fk
            references unicaen_etat_etat
            on delete set null,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint agent_stageobs_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_stageobs_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_stageobs_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);


create unique index agent_stageobs_id_uindex
    on agent_ccc_stageobs (id);

create table agent_ccc_tutorat
(
    id                    serial
        constraint agent_tutorat_pk
            primary key,
    agent_id              varchar(64) not null
        constraint agent_tutorat_agent_c_individu_fk
            references agent
            on delete cascade,
    cible_id              varchar(64)
        constraint agent_tutorat_agent_c_individu_fk_2
            references agent
            on delete set null,
    metier_id             integer
        constraint agent_tutorat_metier_id_fk
            references metier_metier
            on delete set null,
    date_debut            timestamp,
    date_fin              timestamp,
    complement            text,
    formation             boolean,
    etat_id               integer
        constraint agent_tutorat_unicaen_etat_etat_id_fk
            references unicaen_etat_etat
            on delete set null,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint agent_tutorat_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_tutorat_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_tutorat_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);


create unique index agent_tutorat_id_uindex
    on agent_ccc_tutorat (id);

create table agent_ccc_accompagnement
(
    id                    serial
        constraint agent_accompagnement_pk
            primary key,
    agent_id              varchar(64) not null
        constraint agent_accompagnement_agent_c_individu_fk
            references agent
            on delete cascade,
    cible_id              varchar(64)
        constraint agent_accompagnement_agent_c_individu_fk_2
            references agent
            on delete set null,
    bap_id                integer,
    corps_id              integer,
    complement            text,
    resultat              boolean,
    etat_id               integer
        constraint agent_accompagnement_unicaen_etat_etat_id_fk
            references unicaen_etat_etat
            on delete set null,
    date_debut            timestamp,
    date_fin              timestamp,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint agent_accompagnement_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_accompagnement_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_accompagnement_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);


create unique index agent_accompagnement_id_uindex
    on agent_ccc_accompagnement (id);

create table agent_element_application
(
    agent_id               varchar(40) not null
        constraint agent_application_agent_c_individu_fk
            references agent
            on delete cascade,
    application_element_id integer     not null
        constraint agent_application_application_element_id_fk
            references element_application_element
            on delete cascade,
    constraint agent_application_pk
        primary key (agent_id, application_element_id)
);

create table agent_element_competence
(
    agent_id              varchar(40) not null
        constraint agent_competence_agent_c_individu_fk
            references agent
            on delete cascade,
    competence_element_id integer     not null
        constraint agent_competence_competence_element_id_fk
            references element_competence_element
            on delete cascade,
    constraint agent_competence_pk
        primary key (agent_id, competence_element_id)
);

create table agent_element_formation
(
    agent_id             varchar(40) not null
        constraint agent_formation_agent_c_individu_fk
            references agent
            on delete cascade,
    formation_element_id integer     not null
        constraint agent_formation_formation_element_id_fk
            references formation_element
            on delete cascade,
    constraint agent_formation_pk
        primary key (agent_id, formation_element_id)
);

create table agent_fichier
(
    agent   varchar(40) not null,
    fichier varchar(13) not null
        constraint agent_fichier_fichier_fk
            references fichier_fichier
            on delete cascade,
    constraint agent_fichier_pk
        primary key (agent, fichier)
);

create table agent_complement
(
    agent_id      varchar(40) not null
        constraint agent_complement_agent_c_individu_fk
            references agent
            on delete cascade,
    complement_id integer     not null
        constraint agent_complement_complement_id_fk
            references complement
            on delete cascade,
    constraint agent_complement_pk
        primary key (agent_id, complement_id)
);


----------------------------------------------------------------------------------------------------------------------
-- INSERT ------------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_auto) VALUES ('Agent', 'Agent', true);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_auto) VALUES ('Supérieur·e hiérarchique direct·e', 'Supérieur·e hiérarchique direct·e', true);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_auto) VALUES ('Autorité hiérarchique', 'Autorité hiérarchique', true);
