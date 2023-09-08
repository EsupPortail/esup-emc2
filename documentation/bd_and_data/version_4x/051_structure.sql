create table structure_agent_force
(
    id                    serial
        constraint structure_agent_force_pk
        primary key,
    structure_id          integer     not null,
    agent_id              varchar(40) not null
        constraint structure_agent_force_agent_c_individu_fk
        references agent,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint structure_agent_force_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp   not null,
    histo_modificateur_id integer     not null
        constraint structure_agent_force_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint structure_agent_force_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user
);
create unique index structure_agent_force_id_uindex on structure_agent_force (id);

create table structure_ficheposte
(
    structure_id  integer not null,
    ficheposte_id integer not null
        constraint structure_ficheposte_fiche_poste_id_fk
        references ficheposte
        on delete cascade,
    constraint structure_ficheposte_pk
        primary key (structure_id, ficheposte_id)
);

create table structure_type
(
    id                    bigint                                                             not null
        constraint structure_type_pk
        primary key,
    code                  varchar(64)                                                        not null,
    libelle               varchar(256)                                                       not null,
    description           text,
    source_id             varchar(128),
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
    id                    bigint                                                             not null
        constraint structure_pk
        primary key,
    code                  varchar(40),
    sigle                 varchar(40),
    libelle_court         varchar(128),
    libelle_long          varchar(1024),
    type_id               bigint
        constraint structure_structure_type_id_fk
        references structure_type
        on delete set null,
    d_ouverture           timestamp,
    d_fermeture           timestamp,
    fermeture_ow          timestamp,
    resume_mere           boolean      default false,
    description           text,
    adresse_fonctionnelle varchar(1024),
    parent_id             bigint,
    niv2_id               bigint
        constraint structure_structure_id_saved_fk
        references structure
        on delete set null,
    niv2_id_ow            bigint
        constraint structure_structure_ow_id_fk
        references structure
        on delete set null,
    source_id             varchar(128),
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table structure_responsable
(
    id                    bigserial
        constraint structure_responsable_pk
        primary key,
    structure_id          integer                                                            not null,
    agent_id              varchar(40)                                                        not null,
    fonction_id           integer,
    date_debut            timestamp,
    date_fin              timestamp,
    source_id             varchar(128),
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint       default 0,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table structure_gestionnaire
(
    id                    bigserial
        constraint structure_gestionnaire_pk
        primary key,
    structure_id          integer                                                            not null,
    agent_id              varchar(40)                                                        not null,
    fonction_id           integer,
    date_debut            timestamp,
    date_fin              timestamp,
    source_id             varchar(128),
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint       default 0,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

-- PRIVILEGES --------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('structure', 'Gestion des structures', 'Structure\Provider\Privilege', 200);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'structure_index', 'Accéder à l''index des structures', 0 UNION
    SELECT 'structure_afficher', 'Afficher les structures', 10 UNION
    SELECT 'structure_description', 'Édition de la description', 20 UNION
    SELECT 'structure_gestionnaire', 'Gérer les gestionnaire', 30 UNION
    SELECT 'structure_complement_agent', 'Ajouter des compléments à propos des agents', 40 UNION
    SELECT 'structure_agent_force', 'Ajouter/Retirer des agents manuellements', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'structure';

