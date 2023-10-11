-- TABLES - UTILISATEUR -------------------------------------------------------------------

create table unicaen_utilisateur_role
(
    id                   serial
        primary key,
    role_id              varchar(64)                not null,
    libelle              varchar(255)               not null,
    is_default           boolean      default false not null,
    is_auto              boolean      default false not null,
    parent_id            integer
        constraint fk_unicaen_utilisateur_role_parent
            references unicaen_utilisateur_role
            deferrable,
    ldap_filter          varchar(255) default NULL::character varying,
    accessible_exterieur boolean      default true  not null,
    description          text
);
create unique index un_unicaen_utilisateur_role_role_id
    on unicaen_utilisateur_role (role_id);
create index ix_unicaen_utilisateur_role_parent
    on unicaen_utilisateur_role (parent_id);

create table unicaen_utilisateur_user
(
    id                   serial
        primary key,
    username             varchar(255)                                          not null
        constraint un_unicaen_utilisateur_user_username
        unique,
    display_name         varchar(255)                                          not null,
    email                varchar(255),
    password             varchar(128) default 'application'::character varying not null,
    state                boolean      default true                             not null,
    password_reset_token varchar(256)
        constraint un_unicaen_utilisateur_user_password_reset_token
        unique,
    last_role_id         integer
        constraint fk_unicaen_utilisateur_user_last_role
        references unicaen_utilisateur_role
        deferrable
);

create table unicaen_utilisateur_role_linker
(
    user_id integer not null
        constraint fk_unicaen_utilisateur_role_linker_user
            references unicaen_utilisateur_user
            deferrable,
    role_id integer not null
        constraint fk_unicaen_utilisateur_role_linker_role
            references unicaen_utilisateur_role
            deferrable,
    constraint pk_unicaen_utilisateur_role_linker
        primary key (user_id, role_id)
);
create index ix_unicaen_utilisateur_role_linker_user
    on unicaen_utilisateur_role_linker (user_id);
create index ix_unicaen_utilisateur_role_linker_role
    on unicaen_utilisateur_role_linker (role_id);
create index ix_unicaen_utilisateur_user_last_role on unicaen_utilisateur_user (last_role_id);

-- TABLES - PRIVILEGES --------------------------------------------------------------------------

create table unicaen_privilege_categorie
(
    id        serial
        primary key,
    code      varchar(150) not null,
    libelle   varchar(200) not null,
    namespace varchar(255),
    ordre     integer default 0
);
create unique index un_unicaen_privilege_categorie_code
    on unicaen_privilege_categorie (code);

create table unicaen_privilege_privilege
(
    id           serial
        primary key,
    categorie_id integer      not null
        constraint fk_unicaen_privilege_categorie
            references unicaen_privilege_categorie
            deferrable,
    code         varchar(150) not null,
    libelle      varchar(200) not null,
    ordre        integer default 0
);

create unique index un_unicaen_privilege_code
    on unicaen_privilege_privilege (categorie_id, code);

create index ix_unicaen_privilege_categorie
    on unicaen_privilege_privilege (categorie_id);

create table unicaen_privilege_privilege_role_linker
(
    role_id      integer not null
        constraint fk_unicaen_role_privilege_linker_role
            references unicaen_utilisateur_role
            on delete cascade
            deferrable,
    privilege_id integer not null
        constraint fk_unicaen_role_privilege_linker_privilege
            references unicaen_privilege_privilege
            on delete cascade
            deferrable,
    constraint pk_unicaen_role_privilege_linker
        primary key (role_id, privilege_id)
);

create index ix_unicaen_role_privilege_linker_role
    on unicaen_privilege_privilege_role_linker (role_id);
create index ix_unicaen_role_privilege_linker_privilege
    on unicaen_privilege_privilege_role_linker (privilege_id);

-- PRIVILEGES - UTILISATEUR ------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('utilisateur', 'Gestion des utilisateurs', 'UnicaenUtilisateur\Provider\Privilege', 20010);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'utilisateur_afficher', 'Rechercher/Afficher un utilisateur', 10 UNION
    SELECT 'utilisateur_ajouter', 'Ajouter/Supprimer un utilisateur', 20 UNION
    SELECT 'utilisateur_changerstatus', 'Changer le statut d''un d''utilisateur', 30 UNION
    SELECT 'utilisateur_modifierrole', 'Modifier les rôles associés à un utilisateur', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'utilisateur';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('role', 'Gestion des rôles', 'UnicaenUtilisateur\Provider\Privilege', 20010);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'role_afficher', 'Afficher les rôles', 10 UNION
    SELECT 'role_modifier', 'Modifier un rôle', 20 UNION
    SELECT 'role_effacer', 'Effacer un rôle', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'role';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('privilege', 'Gestion des privilèges', 'UnicaenPrivilege\Provider\Privilege', 10000);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'privilege_voir', 'Afficher les privilèges', 10 UNION
    SELECT 'privilege_ajouter', 'Ajouter un privilège', 20 UNION
    SELECT 'privilege_modifier', 'Modifier un privilège', 30 UNION
    SELECT 'privilege_supprimer', 'Supprimer un privilège', 40 UNION
    SELECT 'privilege_affecter', 'Affecter un privilège', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'privilege';

-- DATA -- TODO

