-- !!attention!! AGENT doit déjà être dans le schéma
--  ==> utilisé dans structure_agent_force
--  ==> utilisé dans structure_gestionnaire
--  ==> utilisé dans structure_responsable
-- !!attention!! FICHE_POSTE doit déjà être dans le schéma
-- ==> utilisé dans structure_ficheposte

------------------------------------------------------------------------------------------------------
-- TABLE----------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------

create table structure_type
(
    id  bigserial constraint structure_type_pk primary key,
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
    id  bigserial constraint structure_pk primary key,
    code                  varchar(40),
    sigle                 varchar(40),
    libelle_court         varchar(128),
    libelle_long          varchar(1024),
    type_id               bigint constraint structure_structure_type_id_fk references structure_type on delete set null,
    d_ouverture           timestamp,
    d_fermeture           timestamp,
    fermeture_ow          timestamp,
    resume_mere           boolean      default false,
    description           text,
    adresse_fonctionnelle varchar(1024),
    parent_id             bigint,
    niv2_id               bigint constraint structure_structure_id_saved_fk references structure on delete set null,
    niv2_id_ow            bigint constraint structure_structure_ow_id_fk references structure on delete set null,
    source_id             bigint,
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
    id  bigserial constraint structure_responsable_pk primary key,
    structure_id          integer                                                            not null,
    agent_id              varchar(40)                                                        not null,
    fonction_id           integer,
    date_debut            timestamp,
    date_fin              timestamp,
    source_id             bigint                                                             not null,
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint       default 0                                             not null,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table structure_gestionnaire
(
    id  bigserial  constraint structure_gestionnaire_pk primary key,
    structure_id          integer                                                            not null,
    agent_id              varchar(40)                                                        not null,
    fonction_id           integer,
    date_debut            timestamp,
    date_fin              timestamp,
    source_id             bigint                                                             not null,
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint       default 0                                             not null,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table structure_agent_force
(
    id  serial constraint structure_agent_force_pk primary key,
    structure_id          integer     not null,
    agent_id              varchar(40) not null constraint saf_agent_c_individu_fk references agent,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null constraint saf_unicaen_utilisateur_user_id_fk_1 references unicaen_utilisateur_user,
    histo_modification    timestamp   not null,
    histo_modificateur_id integer     not null constraint saf_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer              constraint saf_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index structure_agent_force_id_uindex  on structure_agent_force (id);

create table structure_ficheposte
(
    structure_id  integer not null,
    ficheposte_id integer not null constraint structure_ficheposte_fiche_poste_id_fk references ficheposte on delete cascade,
    constraint structure_ficheposte_pk primary key (structure_id, ficheposte_id)
);

------------------------------------------------------------------------------------------------------------------------
-- ROLE ----------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

insert into unicaen_utilisateur_role(role_id, libelle, is_auto) VALUES ('Gestionnaire de structure', 'Gestionnaire de structure',true);
insert into unicaen_utilisateur_role(role_id, libelle, is_auto) VALUES ('Responsable de structure', 'Responsable de structure',true);

------------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE -----------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('structure', 'Gestion des structures', 200, 'Structure\Provider\Privilege');

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
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'structure'
;

------------------------------------------------------------------------------------------------------------------------
-- MACRO ET TEMPLATE ---------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('STRUCTURE#libellé', '<p>Retourne le libell&eacute; de la structure</p>', 'structure', 'getLibelle');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('STRUCTURE#libellé_long', '<p>Retourne le libell&eacute; de la structure + le libell&eacute de la structure de niveau 2</p>', 'structure', 'getLibelleLong');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('STRUCTURE#résumé', null, 'structure', 'toStringResume');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('STRUCTURE#bloc', null, 'structure', 'toStringStructureBloc');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('STRUCTURE#gestionnaires', '<p>Affiche sous la forme d''un listing les Gestionnaires de la structure</p>', 'structure', 'toStringGestionnaires');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('STRUCTURE#responsables', '<p>Affiches sous la forme d''un listing les Responsables d''une structure</p>', 'structure', 'toStringResponsables');

