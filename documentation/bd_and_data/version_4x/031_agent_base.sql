create table agent
(
    c_individu            varchar(40)                                                        not null
        constraint agent_pk
        primary key,
    utilisateur_id        integer
        constraint agent_user_id_fk
        references unicaen_utilisateur_user
        on delete set null,
    prenom                varchar(64),
    nom_usage             varchar(64),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    octo_id               varchar(40),
    preecog_id            varchar(40),
    harp_id               integer,
    login                 varchar(256),
    email                 varchar(1024),
    sexe                  varchar(1),
    t_contrat_long        varchar(1),
    date_naissance        date,
    nom_famille           varchar(256),
    id                    bigint,
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint,
    source_id             varchar(128),
    id_orig               varchar(100)
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
        references unicaen_etat_instance
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
create unique index agent_ppp_id_uindex on agent_ccc_ppp (id);

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
        references unicaen_etat_instance
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
create unique index agent_stageobs_id_uindex on agent_ccc_stageobs (id);

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
        references unicaen_etat_instance
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
        references unicaen_etat_instance
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

create table agent_missionspecifique
(
    id                    serial
        constraint agent_missionspecifique_pk
        primary key,
    agent_id              varchar(40) not null,
    mission_id            integer     not null
        constraint agent_missionspecifique_mission_specifique_id_fk
        references mission_specifique
        on delete cascade,
    structure_id          integer,
    date_debut            timestamp,
    date_fin              timestamp,
    commentaire           varchar(2048),
    decharge              double precision,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint agent_missionspecifique_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_missionspecifique_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_missionspecifique_destructeur_fk
        references unicaen_utilisateur_user
);
create unique index agent_missionspecifique_id_uindex
    on agent_missionspecifique (id);

create table agent_carriere_affectation
(
    id                    bigint                                                             not null
        constraint agent_affectation_pk
        primary key,
    agent_id              varchar(40)                                                        not null,
    structure_id          bigint                                                             not null,
    date_debut            timestamp                                                          not null,
    date_fin              timestamp,
    t_principale          varchar(1)   default 'N'::character varying,
    source_id             varchar(128),
    id_orig               varchar(255),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint,
    t_hierarchique        varchar(1)   default 'O'::character varying,
    t_fonctionnelle       varchar(1)   default 'N'::character varying,
    quotite               integer
);

create table agent_carriere_echelon
(
    id                    bigint                         not null
        constraint agent_carriere_echelon_pk
        primary key,
    agent_id              varchar(40)                    not null
        constraint agent_carriere_echelon_agent_c_individu_fk
        references agent
        on delete cascade,
    echelon               integer                        not null,
    d_debut               date                           not null,
    d_fin                 date,
    source_id             varchar(128),
    id_orig               varchar(100),
    created_on            timestamp default CURRENT_DATE not null,
    updated_on            timestamp,
    deleted_on            timestamp,
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table agent_carriere_quotite
(
    id                    bigint                                                             not null
        constraint agent_quotite_pk
        primary key,
    agent_id              varchar(40)                                                        not null
        constraint agent_carriere_quotite_agent_c_individu_fk
        references agent
        on delete cascade,
    quotite               integer,
    d_debut               timestamp,
    d_fin                 timestamp,
    source_id             varchar(128),
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table agent_carriere_grade
(
    id                    bigint                                                             not null
        constraint agent_grade_pk
        primary key,
    agent_id              varchar(40)                                                        not null,
    structure_id          integer,
    grade_id              integer,
    corps_id              integer,
    bap_id                integer,
    d_debut               timestamp,
    d_fin                 timestamp,
    source_id             varchar(128),
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint,
    emploitype_id         integer
);

create table agent_carriere_statut
(
    id                    bigint                                                             not null
        constraint agent_statut_pk
        primary key,
    agent_id              varchar(40)                                                        not null,
    structure_id          integer,
    grade_id              integer,
    corps_id              integer,
    bap_id                integer,
    d_debut               timestamp,
    d_fin                 timestamp,
    source_id             varchar(128),
    id_orig               varchar(100),
    t_titulaire           varchar(1)                                                         not null,
    t_cdi                 varchar(1)                                                         not null,
    t_cdd                 varchar(1)                                                         not null,
    t_vacataire           varchar(1)                                                         not null,
    t_enseignant          varchar(1)                                                         not null,
    t_administratif       varchar(1)                                                         not null,
    t_chercheur           varchar(1)                                                         not null,
    t_doctorant           varchar(1)                                                         not null,
    t_detache_in          varchar(1)                                                         not null,
    t_detache_out         varchar(1)                                                         not null,
    t_dispo               varchar(1)                                                         not null,
    t_heberge             varchar(1)                                                         not null,
    t_emerite             varchar(1)                                                         not null,
    t_retraite            varchar(1)                                                         not null,
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table agent_poste
(
    id         serial
        constraint agent_poste_pk
        primary key,
    agent_id   varchar(40)             not null
        constraint agent_poste_agent_c_individu_fk
        references agent,
    code_poste varchar(128)            not null,
    intitule   varchar(1024)           not null,
    created_on timestamp default now() not null,
    updated_on timestamp,
    deleted_on timestamp,
    source_id  varchar(128),
    id_source  varchar(256)
);

create table agent_hierarchie_superieur
(
    id  serial
        constraint agent_superieur_pk
        primary key,
    agent_id              varchar(40)                                                   not null
        constraint agent_superieur_agent_c_individu_fk
        references agent,
    superieur_id          varchar(40)                                                   not null
        constraint agent_superieur_agent_c_individu_fk2
        references agent,
    histo_creation        timestamp default now()                                       not null,
    histo_createur_id     integer   default 0                                           not null
        constraint agent_superieur___fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_superieur_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_superieur_unicaen_utilisateur_user_id_fk2
        references unicaen_utilisateur_user
);
create index agent_superieur_agent_id_index on agent_hierarchie_superieur (agent_id);
create index agent_superieur_superieur_id_index on agent_hierarchie_superieur (superieur_id);

create table agent_hierarchie_autorite
(
    id  serial
        constraint agent_autorite_pk
        primary key,
    agent_id              varchar(40)                                                  not null
        constraint agent_autorite_agent_c_individu_fk
        references agent,
    autorite_id           varchar(40)                                                  not null
        constraint agent_autorite_agent_c_individu_fk2
        references agent,
    histo_creation        timestamp default now()                                      not null,
    histo_createur_id     integer   default 0                                          not null
        constraint agent_autorite___fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint agent_autorite_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint agent_autorite_unicaen_utilisateur_user_id_fk2
        references unicaen_utilisateur_user
);
create index agent_autorite_agent_id_index  on agent_hierarchie_autorite (agent_id);
create index agent_autorite_autorite_id_index   on agent_hierarchie_autorite (autorite_id);

-- PRIVILEGE --------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('agent', 'Gestion des agents', 'Application\Provider\Privilege', 500);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'agent_index', 'Accéder à l''index', 0 UNION
    SELECT 'agent_afficher', 'Afficher un agent', 10 UNION
    SELECT 'agent_info_source', 'Afficher les informations sur les identifiants sources', 11 UNION
    SELECT 'agent_afficher_donnees', 'Afficher le menu mes données', 12 UNION
    SELECT 'agent_ajouter', 'Ajouter un agent', 20 UNION
    SELECT 'agent_editer', 'Modifier un agent', 30 UNION
    SELECT 'agent_effacer', 'Effacer un agent', 50 UNION
    SELECT 'agent_rechercher', 'Rechercher un·e agent·e', 99 UNION
    SELECT 'agent_element_ajouter_epro', 'Ajouter un entretien professionnel associé à un agent', 100 UNION
    SELECT 'agent_element_voir', 'Afficher les éléments associés à l''agent', 510 UNION
    SELECT 'agent_element_ajouter', 'Ajouter un élément associé à l''agent', 520 UNION
    SELECT 'agent_element_modifier', 'Modifier un élément associé à l''agent', 530 UNION
    SELECT 'agent_element_historiser', 'Historiser/restaurer un élément associé à l''agent', 540 UNION
    SELECT 'agent_element_detruire', 'Détruire un élément associé à l''agent', 550 UNION
    SELECT 'agent_element_valider', 'Valider un élément associé à l''agent', 560 UNION
    SELECT 'agent_acquis_afficher', 'Afficher les acquis d''un agent', 1000 UNION
    SELECT 'agent_acquis_modifier', 'Modifier les acquis d''un agent', 1010 UNION
    SELECT 'agent_gestion_ccc', 'Gestion des agents CCC', 9999
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'agent';


INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('agentaffichage', 'Affichage des informations relatives à l''agent', 'Application\Provider\Privilege', 510);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'agentaffichage_superieur', 'Afficher les supérieur·es hiérarchiques direct·es', 10 UNION
    SELECT 'agentaffichage_autorite', 'Afficher les autorités hiérarchiques', 20 UNION
    SELECT 'agentaffichage_dateresume', 'Afficher les dates sur le résumé de carrière', 30 UNION
    SELECT 'agentaffichage_carrierecomplete', 'Afficher la carrière complète', 40 UNION
    SELECT 'agentaffichage_compte', 'Afficher les informations sur le compte utilisateur', 50 UNION
    SELECT 'agentaffichage_temoin_affectation', 'Afficher les temoins liés aux affectations', 60 UNION
    SELECT 'agentaffichage_temoin_statut', 'Afficher les temoins liés aux statuts', 70
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'agentaffichage';


