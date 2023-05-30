-- Partie structure ----------------------------------------------------------------------------------------------------

create table structure_type
(
    id                  serial                      constraint pk_structure_type primary key,
    code                varchar(10)     not null    constraint un_structure_type_code unique,
    libelle             varchar(255)    not null,
    description         text
);
INSERT INTO structure_type (id, code, libelle, description) VALUES (1, 'ETAB', 'Établissement', 'Établissement');
INSERT INTO structure_type (id, code, libelle, description) VALUES (2, 'COMP', 'Composante', 'Composante');
INSERT INTO structure_type (id, code, libelle, description) VALUES (3, 'SREC', 'Structure de recherche', 'Structure de recherche');
INSERT INTO structure_type (id, code, libelle, description) VALUES (4, 'SCEN', 'Service central', 'Service central');
INSERT INTO structure_type (id, code, libelle, description) VALUES (5, 'SCOM', 'Service commun', 'Service commun');
INSERT INTO structure_type (id, code, libelle, description) VALUES (6, 'BIBL', 'Bibliothèque', 'Bibliothèque');
INSERT INTO structure_type (id, code, libelle, description) VALUES (7, 'ANTN', 'Antenne', 'Antenne');
INSERT INTO structure_type (id, code, libelle, description) VALUES (8, 'DEPT', 'Département', 'Département');
INSERT INTO structure_type (id, code, libelle, description) VALUES (9, 'SSAD', 'Sous-structure administrative', 'Sous-structure administrative');
INSERT INTO structure_type (id, code, libelle, description) VALUES (10, 'FICT', 'Structure fictive', 'Structure fictive');
INSERT INTO structure_type (id, code, libelle, description) VALUES (11, 'ED', 'École doctorale', 'École doctorale');
INSERT INTO structure_type (id, code, libelle, description) VALUES (12, 'TL', 'Tiers-lieu', 'Tiers-lieu');

-- parent_id    structure directement au dessus de la structure considérée
-- niv2_id      structure de rattachement au plus haut (sous l'université) de rattachement
create table structure
(
    id                  serial                      constraint pk_structure primary key,
    code                varchar(10)     not null,
    sigle               varchar(30),
    libelle_court       varchar(50)     not null,
    libelle_long        varchar(1024)   not null,
    type_id             integer                     constraint fk_structure_type references structure_type deferrable,
    date_ouverture      date,
    date_fermeture      date,
    parent_id           integer                     constraint structure_structure_parent_id_fk references structure on delete set null,
    niv2_id             integer                     constraint structure_structure_niv2id_fk references structure on delete set null
);

-- Partie corps, grade, corresponsdance --------------------------------------------------------------------------------

create table corps
(
    id              serial                          constraint pk_corps primary key,
    code            varchar(10),
    categorie_code  varchar(1),
    lib_court       varchar(20),
    lib_long        varchar(200),
    d_ouverture     date,
    d_fermeture     date
);

create table grade
(
    id              serial                          constraint pk_grade primary key,
    code            varchar(10),
    lib_court       varchar(20),
    lib_long        varchar(200),
    d_ouverture     date,
    d_fermeture     date
);

create table correspondance
(
    id              serial                          constraint pk_correspondance primary key,
    code            varchar(10),
    lib_court       varchar(20),
    lib_long        varchar(200),
    d_ouverture     date,
    d_fermeture     date
);

-- Partie Agent --------------------------------------------------------------------------------------------------------

-- Tables du concentrateur pour reférence
create table individu
(
    c_individu_chaine   serial
        constraint pk_individu
            primary key,
    c_source            varchar(10),
    c_etu               varchar(11),
    c_ine               varchar(11),
    sexe                varchar(1),
    prenom              varchar(64),
    prenom2             varchar(64),
    prenom3             varchar(64),
    nom_famille         varchar(64),
    nom_usage           varchar(64),
    d_naissance         date,
    ville_de_naissance  varchar(64),
    c_commune_naissance varchar(5),
    c_dept_naissance    varchar(3),
    c_pays_naissance    varchar(3),
    c_pays_nationalite  varchar(3),
    tel_perso           varchar(20),
    email_perso         varchar(255),
    d_modif             date
);

create table individu_compte
(
    id          serial,
    individu_id integer      not null,
    type_id     integer,
    login       varchar(256) not null,
    email       varchar(256) not null
);

create view v_agent (c_individu, prenom, nom_usage, nom_famille, login, email, sexe, date_naissance, t_contrat_long) as
SELECT individu.c_individu_chaine AS c_individu,
       individu.prenom,
       individu.nom_usage,
       individu.nom_famille,
       ic.login,
       ic.email,
       individu.sexe,
       individu.d_naissance       AS date_naissance,
       'O'::text                  AS t_contrat_long
FROM individu
JOIN individu_affectation ia ON individu.c_individu_chaine = ia.individu_id
JOIN individu_compte ic ON ic.individu_id = individu.c_individu_chaine
WHERE ia.type_id = 1;

-- Partie Responsabilité/Gestion de structure

create table structure_responsable
(
    id           serial                             constraint structure_reponsable_pk primary key,
    structure_id bigint     not null,
    individu_id  integer     not null,
    fonction_id  integer,
    date_debut   date        not null,
    date_fin     date
);
create unique index structure_reponsable_id_uindex on structure_responsable (id);

create table structure_gestionnaire
(
    id           serial                             constraint structure_gestionnaire_pk primary key,
    structure_id bigint not null,
    individu_id  integer not null,
    fonction_id  integer,
    date_debut   date    not null,
    date_fin     date
);
create unique index structure_gestionnaire_id_uindex on structure_gestionnaire (id);

-- Partie Carriere -----------------------------------------------------------------------------------------------------

create table individu_echelon
(
    id           serial                             constraint individu_echelon_pk primary key,
    individu_id  integer not null,
    echelon      integer not null,
    date_passage date
);
create unique index individu_echelon_id_uindex on individu_echelon (id);

create table individu_quotite
(
    id          serial                              constraint individu_quotite_pk primary key,
    individu_id integer   not null,
    quotite     integer   not null,
    debut       timestamp not null,
    fin         timestamp
);
create unique index individu_quotite_id_uindex on individu_quotite (id);

create table individu_grade
(
    id                integer not null                    constraint individu_grade_pk primary key,
    id_orig           varchar(1024),
    agent_id          integer not null,
    structure_id      bigint,
    corps_id          integer,
    grade_id          integer,
    emploitype_id     integer,
    correspondance_id integer,
    d_debut           date,
    d_fin             date
);

-- source peut-être mis à SIHAM partout
create table individu_statut
(
    id               serial                             constraint pk_ind_sta primary key deferrable initially deferred,
    id_orig          varchar(255)   not null            constraint un_ind_sta_idorig unique deferrable initially deferred,
    c_source         varchar(10)    not null            constraint fk_ind_sta_csource references source deferrable,
    individu_id      integer        not null            constraint fk_ind_sta_ind references individu deferrable,
    structure_id     bigint                            constraint fk_ind_sta_str references structure deferrable,
    d_debut          date,
    d_fin            date,
    t_titulaire      varchar(1) default 'N'::character varying not null,
    t_cdi            varchar(1) default 'N'::character varying not null,
    t_cdd            varchar(1) default 'N'::character varying not null,
    t_vacataire      varchar(1) default 'N'::character varying not null,
    t_enseignant     varchar(1) default 'N'::character varying not null,
    t_administratif  varchar(1) default 'N'::character varying not null,
    t_chercheur      varchar(1) default 'N'::character varying not null,
    t_etudiant       varchar(1) default 'N'::character varying not null,
    t_auditeur_libre varchar(1) default 'N'::character varying not null,
    t_doctorant      varchar(1) default 'N'::character varying not null,
    t_detache_in     varchar(1) default 'N'::character varying not null,
    t_detache_out    varchar(1) default 'N'::character varying not null,
    t_dispo          varchar(1) default 'N'::character varying not null,
    t_heberge        varchar(1) default 'N'::character varying not null,
    t_emerite        varchar(1) default 'N'::character varying not null,
    t_retraite       varchar(1) default 'N'::character varying not null,
    t_cld            varchar(1) default 'N'::character varying not null,
    t_clm            varchar(1) default 'N'::character varying not null,
    t_apprenant      varchar(1) default 'N'::character varying not null,
    t_invite         varchar(1) default 'N'::character varying not null,
    t_fictif         varchar(1) default 'N'::character varying not null,
    t_lecteur_scd    varchar(1) default 'N'::character varying not null,
    t_pamsu          varchar(1) default 'N'::character varying not null
);
create index ix_ind_sta_ind on individu_statut (individu_id);
create index ix_ind_sta_src on individu_statut (c_source);
create index ix_ind_sta_dat on individu_statut (d_debut, d_fin);

create table individu_affectation_type
(
    id          serial                          constraint pk_individu_affectation_type primary key,
    nom         varchar(50)     not null        constraint un_individu_affectation_type unique,
    libelle     varchar(255)    not null,
    description varchar(1024)
);
create unique index un_affectation_type_nom on individu_affectation_type (nom);
create unique index pk_affectation_type on individu_affectation_type (id);

INSERT INTO individu_affectation_type (id, nom, libelle, description) VALUES (1, 'EMPLOI', 'est employé principalement par', null);
INSERT INTO individu_affectation_type (id, nom, libelle, description) VALUES (2, 'AFFECTATION', 'est affecté à', null);
INSERT INTO individu_affectation_type (id, nom, libelle, description) VALUES (3, 'RECHERCHE', 'est chercheur dans', null);
INSERT INTO individu_affectation_type (id, nom, libelle, description) VALUES (4, 'ENSEIGNEMENT', 'enseigne dans', null);
INSERT INTO individu_affectation_type (id, nom, libelle, description) VALUES (5, 'ETUDE', 'étudie dans', null);

-- source peut-être mis à SIHAM partout
create table individu_affectation
(
    id              serial                           constraint pk_individu_affectation primary key,
    individu_id     integer     not null             constraint fk_individu_affectation_ind references individu deferrable,
    structure_id    bigint     not null             constraint fk_individu_affectation_str references structure deferrable,
    type_id         integer     not null             constraint fk_individu_affectation_typ references individu_affectation_type deferrable,
    source_id       varchar(10) not null             constraint fk_individu_affectation_src references source deferrable,
    date_debut      date        not null,
    date_fin        date,
    id_orig         varchar(100),
    t_principale    varchar(1)
);
create index ix_individu_affectation_ind on individu_affectation (individu_id);
create index ix_individu_affectation_str on individu_affectation (structure_id);
create index ix_individu_affectation_typ on individu_affectation (type_id);
create index ix_individu_affectation_src on individu_affectation (source_id);


