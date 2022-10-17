create table element_niveau
(
    id                    serial
        constraint maitrise_niveau_pk
            primary key,
    type                  varchar(256) not null,
    libelle               varchar(256) not null,
    niveau                integer      not null,
    description           text,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint maitrise_niveau_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint maitrise_niveau_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint maitrise_niveau_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);
create unique index maitrise_niveau_type_niveau_uindex on element_niveau (type, niveau);

-- APPLICATION ----------------------------------------

create table element_application_theme
(
    id                    serial
        constraint application_groupe_pk
        primary key,
    libelle               varchar(1024),
    couleur               varchar(255),
    ordre                 integer   default 9999,
    histo_creation        timestamp default ('now'::text)::date not null,
    histo_createur_id     integer   default 0                   not null
        constraint application_groupe_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint application_groupe_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint application_groupe_user_id_fk_3
        references unicaen_utilisateur_user
);
create unique index application_groupe_id_uindex  on element_application_theme (id);

create table element_application
(
    id                    serial
        primary key,
    libelle               varchar(128)                          not null,
    description           varchar(2048),
    url                   varchar(128),
    actif                 boolean   default true                not null,
    groupe_id             integer
        constraint element_application_theme_id_fk
        references element_application_theme
        on delete set null,
    histo_creation        timestamp default ('now'::text)::date not null,
    histo_createur_id     integer   default 0                   not null
        constraint element_application_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint element_application_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint element_application_user_id_fk_3
        references unicaen_utilisateur_user
);

create unique index application_informations_id_uindex    on element_application (id);



create table element_application_element
(
    id                    serial
        constraint application_element_pk
        primary key,
    application_id        integer   not null
        constraint application_element_application_informations_id_fk
        references element_application
        on delete cascade,
    commentaire           text,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint application_element_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp not null,
    histo_modificateur_id integer   not null
        constraint application_element_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint application_element_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user,
    validation_id         integer
        constraint application_element_unicaen_validation_instance_id_fk
        references unicaen_validation_instance
        on delete set null,
    niveau_id             integer
        constraint application_element_maitrise_niveau_id_fk
        references element_niveau
        on delete set null,
    clef                  boolean
);


create unique index application_element_id_uindex
    on element_application_element (id);

-- COMPETENCE ----------------------------------------

create table element_competence_theme
(
    id                    serial
        constraint competence_theme_pk
        primary key,
    libelle               varchar(256) not null,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint competence_theme_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp    not null,
    histo_modificateur_id integer
        constraint competence_theme_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint competence_theme_user_id_fk
        references unicaen_utilisateur_user
);

create unique index competence_theme_id_uindex
    on element_competence_theme (id);

create table element_competence_type
(
    id                    serial
        constraint competence_type_pk
        primary key,
    libelle               varchar(256) not null,
    ordre                 integer,
    couleur               varchar(255),
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint competence_type_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint competence_type_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint competence_type_user_id_fk
        references unicaen_utilisateur_user
);


create unique index competence_type_id_uindex
    on element_competence_type (id);

create table element_competence
(
    id                    serial
        constraint competence_pk
        primary key,
    libelle               varchar(256) not null,
    description           text,
    histo_creation        timestamp    not null,
    type_id               integer
        constraint competence_type__fk
        references element_competence_type
        on delete set null,
    theme_id              integer
        constraint competence_theme__fk
        references element_competence_theme
        on delete set null,
    source                varchar(256),
    id_source             integer,
    histo_createur_id     integer      not null
        constraint competence_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint competence_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint competence_destructeur_fk
        references unicaen_utilisateur_user
);

create unique index competence_id_uindex
    on element_competence (id);

create table element_competence_element
(
    id                    serial
        constraint competence_element_pk
        primary key,
    competence_id         integer   not null
        constraint competence_element_competence_informations_id_fk
        references element_competence
        on delete cascade,
    commentaire           text,
    validation_id         integer
        constraint competence_element_unicaen_validation_instance_id_fk
        references unicaen_validation_instance
        on delete set null,
    niveau_id             integer
        constraint competence_element_maitrise_niveau_id_fk
        references element_niveau
        on delete set null,
    clef                  boolean default false,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint competence_element_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp not null,
    histo_modificateur_id integer
        constraint competence_element_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint competence_element_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user
);


create unique index competence_element_id_uindex
    on element_competence_element (id);

-- FORMATION --------------------------------

create table formation_groupe
(
    id                    serial
        constraint formation_groupe_pk
            primary key,
    libelle               varchar(1024),
    couleur               varchar(255),
    ordre                 integer default 9999,
    source_id             bigint,
    id_source             integer,
    histo_createur_id     integer   not null
        constraint formation_groupe_createur_fk
            references unicaen_utilisateur_user,
    histo_creation        timestamp not null,
    histo_modificateur_id integer
        constraint formation_groupe_modificateur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_destructeur_id  integer
        constraint formation_groupe_destructeur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    description           text
);

create unique index formation_groupe_id_uindex
    on formation_groupe (id);

create table formation_theme
(
    id                    serial
        constraint formation_theme_pk
            primary key,
    libelle               varchar(1024) not null,
    histo_creation        timestamp     not null,
    histo_createur_id     integer       not null
        constraint formation_theme_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_theme_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_theme_destructeur_fk
            references unicaen_utilisateur_user
);


create unique index formation_theme_id_uindex
    on formation_theme (id);

create table formation
(
    id                    serial
        constraint formation_pk
            primary key,
    libelle               varchar(256)         not null,
    description           text,
    lien                  varchar(1024),
    theme_id              integer
        constraint formation_formation_theme_id_fk
            references formation_theme
            on delete set null,
    groupe_id             integer
        constraint formation_formation_groupe_id_fk
            references formation_groupe
            on delete set null,
    source_id             bigint,
    id_source             integer,
    histo_createur_id     integer              not null
        constraint formation_createur_fk
            references unicaen_utilisateur_user,
    histo_creation        timestamp            not null,
    histo_modificateur_id integer
        constraint formation_modificateur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_destructeur_id  integer
        constraint formation_destructeur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    affichage             boolean default true not null,
    rattachement          varchar(1024)
);


create unique index formation_id_uindex
    on formation (id);

create table formation_element
(
    id                    serial
        constraint formation_element_pk
            primary key,
    formation_id          integer   not null
        constraint formation_element_formation_informations_id_fk
            references formation
            on delete cascade,
    commentaire           text,
    validation_id         integer
        constraint formation_element_unicaen_validation_instance_id_fk
            references unicaen_validation_instance
            on delete set null,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint formation_element_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_element_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_element_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);

create unique index formation_element_id_uindex
    on formation_element (id);

create table formation_obtenue_application
(
    formation_id integer not null constraint formation_application_obtenue_formation_id_fk references formation on delete cascade,
    application_element_id integer not null constraint formation_application_obtenue_application_element_id_fk references element_application_element on delete cascade,
    constraint formation_application_obtenue_pk primary key (formation_id, application_element_id)
);

create table formation_obtenue_competence
(
    formation_id integer not null constraint formation_obtenue_competence_formation_id_fk references formation on delete cascade,
    competence_element_id integer not null constraint formation_obtenue_competence_competence_element_id_fk references element_competence_element on delete cascade,
    constraint formation_obtenue_competence_pk primary key (formation_id, competence_element_id)
);


-----------------------------------------------------------------------------------------------------------
-- PRIVILEGE ----------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('niveau', 'Gestion des niveaux', 'Element\Provider\Privilege', 70900);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'niveau_index', 'Accéder à l''index des niveaux', 10 UNION
    SELECT 'niveau_afficher', 'Afficher un niveau', 20 UNION
    SELECT 'niveau_modifier', 'Modifier un niveau', 30 UNION
    SELECT 'niveau_effacer', 'Supprimer un niveau', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'niveau';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('application', 'Gestion des applications', 'Element\Provider\Privilege', 70100);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'application_index', 'Accéder à l''index', 10 UNION
    SELECT 'application_afficher', 'Afficher une application', 20 UNION
    SELECT 'application_modifier', 'Modifier une application', 30 UNION
    SELECT 'application_effacer', 'Supprimer une application', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'application';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('applicationtheme', 'Gestion des thèmes d''application', 'Element\Provider\Privilege', 70200);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'applicationtheme_index', 'Acceder à l''index des thèmes d''application', 10 UNION
    SELECT 'applicationtheme_afficher', 'Afficher un thème d''application', 20 UNION
    SELECT 'applicationtheme_modifier', 'Modifier un thème d''application', 30 UNION
    SELECT 'applicationtheme_effacer', 'Supprimer un thème d''application', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'applicationtheme';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('competence', 'Gestion des compétences', 'Element\Provider\Privilege', 70500);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'competence_index', 'Accéder à l''index des compétences', 10 UNION
    SELECT 'competence_afficher', 'Afficher une compétence', 20 UNION
    SELECT 'competence_modifier', 'Modifier une compétence', 30 UNION
    SELECT 'competence_effacer', 'Supprimer une compétence', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'competence';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('competencetype', 'Gestions des types de compétence', 'Element\Provider\Privilege', 70700);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'competencetype_index', 'Accéder à l''index des types de compétence', 10 UNION
    SELECT 'competencetype_afficher', 'Afficher un type de compétence', 20 UNION
    SELECT 'competencetype_modifier', 'Modifier un type de compétence', 30 UNION
    SELECT 'competencetype_effacer', 'Supprimer un type de compétence', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'competencetype';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('competencetheme', 'Gestion des thèmes de compétence', 'Element\Provider\Privilege', 70600);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'competencetheme_index', 'Accéder à l''index des thèmes de compétence', 10 UNION
    SELECT 'competencetheme_afficher', 'Afficher un thème de compétence', 20 UNION
    SELECT 'competencetheme_modifier', 'Modifier un thème de compétence', 30 UNION
    SELECT 'competencetheme_effacer', 'Supprimer un thème de compétence', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'competencetheme';

------------------------------------------------------------------------------------------------------------------------
-- INSERT DATA ---------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------


-- COMPETENCE TYPE (copie données unicaen)
INSERT INTO element_competence_type (libelle, ordre, couleur, histo_creation, histo_createur_id) VALUES ('Comportementales', 30, 'DarkSeaGreen', '2019-09-25 17:15:05.000000', 0);
INSERT INTO element_competence_type (libelle, ordre, couleur, histo_creation, histo_createur_id) VALUES ('Connaissances', 10, 'IndianRed', '2019-09-25 17:15:05.000000', 0);
INSERT INTO element_competence_type (libelle, ordre, couleur, histo_creation, histo_createur_id) VALUES ('Opérationnelles', 20, 'CadetBlue', '2019-09-25 17:15:05.000000', 0);

-- NIVEAU ELEMENT (copie données unicaen)
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Competence', 'Débutant', 1, null, '2021-03-16 17:38:40.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Competence', 'Apprenti', 2, null, '2021-03-16 17:39:08.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Competence', 'Intermédiaire', 3, null, '2021-03-16 17:39:50.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Competence', 'Confirmé', 4, null, '2021-03-16 17:40:03.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Competence', 'Expert', 5, null, '2021-03-16 17:40:18.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Application', 'Débutant', 1, null, '2021-03-16 17:38:40.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Application', 'Apprenti', 2, null, '2021-03-16 17:39:08.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Application', 'Intermédiaire', 3, null, '2021-03-16 17:39:50.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Application', 'Confirmé', 4, null, '2021-03-16 17:40:03.000000', 0);
INSERT INTO element_niveau (type, libelle, niveau, description, histo_creation, histo_createur_id) VALUES ('Application', 'Expert', 5, null, '2021-03-16 17:40:18.000000', 0);