-- TABLE ----------------------------------------------------------------

create table carriere_niveau
(
    id                    serial
        constraint niveau_definition_pk primary key,
    niveau                integer                 not null,
    libelle               varchar(1024)           not null,
    description           text,
    label                 varchar(64)             not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint niveau_definition_unicaen_utilisateur_user_id_fk_1 references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint niveau_definition_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint niveau_definition_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index niveau_definition_id_uindex  on carriere_niveau (id);

create table carriere_niveau_enveloppe
(
    id                    serial
        constraint niveau_enveloppe_pk primary key,
    borne_inferieure_id   integer                 not null
        constraint niveau_enveloppe_niveau_definition_id_fk_1 references carriere_niveau,
    borne_superieure_id   integer                 not null
        constraint niveau_enveloppe_niveau_definition_id_fk_2 references carriere_niveau,
    valeur_recommandee_id integer
        constraint niveau_enveloppe_niveau_definition_id_fk_3 references carriere_niveau on delete set null,
    description           text,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk_1  references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction     integer,
    histo_destructeur_id  integer
        constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index niveau_enveloppe_id_uindex on carriere_niveau_enveloppe (id);


create table carriere_categorie
(
    id                    serial
        constraint categorie_pk primary key,
    code                  varchar(255)              not null,
    libelle               varchar(1024)             not null,
    histo_creation        timestamp default now()   not null,
    histo_createur_id     integer   default 0       not null
        constraint categorie_user_id_fk references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint categorie_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint categorie_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index categorie_code_uindex on carriere_categorie (code);
create unique index categorie_id_uindex on carriere_categorie (id);



create table carriere_corps
(
    id                    bigint                                                             not null
        constraint corps_pk
        primary key,
    lib_court             varchar(20),
    lib_long              varchar(200),
    code                  varchar(10)                                                        not null,
    categorie             varchar(10),
    niveau                integer,
    niveaux_id            integer
        constraint carriere_corps_carriere_niveau_enveloppe_id_fk
        references carriere_niveau_enveloppe
        on delete set null,
    d_ouverture           timestamp,
    d_fermeture           timestamp,
    source_id             varchar(128),
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint,
    superieur_as_autorite boolean      default false                                         not null
);

create table carriere_grade
(
    id                    bigint                                                             not null
        constraint grade_pk
        primary key,
    lib_court             varchar(20),
    lib_long              varchar(200),
    code                  varchar(20)                                                        not null,
    d_ouverture           timestamp,
    d_fermeture           timestamp,
    source_id             varchar(128),
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint
);

create table carriere_correspondance_type
(
    id                    bigint                                 not null
        constraint carriere_correspondance_type_pk
        primary key,
    code                  varchar(64)                            not null,
    libelle_court         varchar(256)                           not null,
    libelle_long          varchar(1024)                          not null,
    description           text,
    d_ouverture           timestamp,
    d_fermeture           timestamp,
    created_on            timestamp(0) default LOCALTIMESTAMP(0) not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint                                 not null,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint,
    source_id             varchar(128)                           not null,
    id_orig               varchar(100)
);

create table carriere_correspondance
(
    id                    bigint                                                             not null
        constraint correspondance_pk
        primary key,
    c_bap                 varchar(10),
    lib_court             varchar(20),
    lib_long              varchar(200),
    d_ouverture           timestamp,
    d_fermeture           timestamp,
    source_id             varchar(128),
    id_orig               varchar(100),
    created_on            timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on            timestamp(0),
    deleted_on            timestamp(0),
    histo_createur_id     bigint,
    histo_modificateur_id bigint,
    histo_destructeur_id  bigint,
    type_id               bigint
        constraint carriere_correspondance_carriere_correspondance_type_null_fk
        references carriere_correspondance_type
);

create table carriere_emploitype
(
    id            integer                 not null
        constraint carriere_emploitype_pk
        primary key,
    code          varchar(64)             not null,
    libelle_court varchar(256)            not null,
    libelle_long  varchar(1024)           not null,
    date_debut    timestamp default now() not null,
    date_fin      timestamp,
    created_on    timestamp default now() not null,
    updated_on    timestamp,
    deleted_on    timestamp,
    source_id     varchar(128)
);

-- PRIVILEGE -------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('categorie', 'Gestion des catégories (carrière)', 600, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'categorie_index', 'Accéder à l''index des catégories', 10 UNION
    SELECT 'categorie_afficher', 'Afficher une catégorie', 20 UNION
    SELECT 'categorie_modifier', 'Modifier une catégorie', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'categorie';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('corps', 'Gestion des corps', 610, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'corps_index', 'Accéder à l''index des corps', 10 UNION
    SELECT 'corps_afficher', 'Afficher les corps', 20 UNION
    SELECT 'corps_modifier', 'Modifier un corps', 30 UNION
    SELECT 'corps_lister_agents', 'Lister les agents', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'corps';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('grade', 'Gestion des grades', 620, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'grade_index', 'Accéder à l''index des grades', 10 UNION
    SELECT 'grade_afficher', 'Afficher un grade', 20 UNION
    SELECT 'grade_modifier', 'Modifier un grade', 30 UNION
    SELECT 'grade_lister_agents', 'Lister les agents', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'grade';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('correspondance', 'Gestion des correspondances', 630, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'correspondance_index', 'Accéder à l''index des correspondances', 10 UNION
    SELECT 'correspondance_afficher', 'Afficher une correspondance', 20 UNION
    SELECT 'correspondance_modifier', 'Modifier une correspondance', 30 UNION
    SELECT 'correspondance_lister_agents', 'Lister les agents', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'correspondance';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('emploitype', 'Gestion des emplois types', 620, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'emploitype_index', 'Accéder à l''index des grades', 10 UNION
    SELECT 'emploitype_afficher', 'Afficher un grade', 20 UNION
    SELECT 'emploitype_modifier', 'Modifier un grade', 30 UNION
    SELECT 'emploitype_lister_agents', 'Lister les agents', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'emploitype';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('niveaucarriere', 'Gestion des niveaux de carrière ', 1000, 'Carriere\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'niveaucarriere_index', 'Accéder à l''index', 1 UNION
    SELECT 'niveaucarriere_afficher', 'Afficher un niveau', 10 UNION
    SELECT 'niveaucarriere_ajouter', 'Ajouter un niveau', 20 UNION
    SELECT 'niveaucarriere_modifier', 'Modifier un niveau', 30 UNION
    SELECT 'niveaucarriere_historiser', 'Historiser/Restaurer un niveau', 40 UNION
    SELECT 'niveaucarriere_supprimer', 'Supprimer un niveau', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'niveaucarriere';

INSERT INTO unicaen_parametre_categorie (code, libelle, ordre) VALUES ('CARRIERE', 'Paramètre du module Carrière', 2);
INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, VALEUR, ORDRE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, VALEUR, ORDRE) AS (
    SELECT 'CorpsAvecAgent', 'Affichage seulement des corps avec agent', null, 'Boolean', 'true', 100 UNION
    SELECT 'GradeAvecAgent', 'Affichage seulement des grades avec agent', null, 'Boolean', 'true', 200 UNION
    SELECT 'CorrespondanceAvecAgent', 'Affichage seulement des correspondance ayant un agent', null, 'Boolean', 'true', 300 UNION
    SELECT 'ACTIF_ONLY','Ne considérer que les Corps/Correspondances/Grades actifs',null,'Boolean',true, 1000
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES, d.VALEUR, d.ORDRE
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'CARRIERE';

-- Bootstraping des données ------------------------------------------------------------

INSERT INTO carriere_categorie (code, libelle, histo_creation, histo_createur_id) VALUES ('A', 'La catégorie A');
INSERT INTO carriere_categorie (code, libelle, histo_creation, histo_createur_id) VALUES ('B', 'La catégorie B');
INSERT INTO carriere_categorie (code, libelle, histo_creation, histo_createur_id) VALUES ('C', 'La catégorie C');

INSERT INTO carriere_niveau (niveau, libelle, description, label) VALUES (1, 'Ingénieur de recherche et assimilé', null, 'IGR');
INSERT INTO carriere_niveau (niveau, libelle, description, label) VALUES (2, 'Ingénieur d''étude et assimilé', null, 'IGE');
INSERT INTO carriere_niveau (niveau, libelle, description, label) VALUES (3, 'Assistant-ingénieur', null, 'ASI');
INSERT INTO carriere_niveau (niveau, libelle, description, label) VALUES (4, 'Technicien', null, 'TCH');
INSERT INTO carriere_niveau (niveau, libelle, description, label) VALUES (5, 'Adjoint technique', null , 'ADT');


