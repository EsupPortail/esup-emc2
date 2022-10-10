
-- ---------------------------------------------------------------------------------------------------------------------
-- TABLE -----------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------


-- TABLE DES ROLES -----------------------------------------------------------------------------------------------------

CREATE TABLE UNICAEN_UTILISATEUR_ROLE (
  ID                    SERIAL        PRIMARY KEY,
  ROLE_ID               VARCHAR(64)   NOT NULL,
  LIBELLE               VARCHAR(255)  NOT NULL,
  IS_DEFAULT            BOOLEAN       DEFAULT false NOT NULL,
  IS_AUTO               BOOLEAN       DEFAULT false NOT NULL,
  PARENT_ID             INTEGER,
  LDAP_FILTER           VARCHAR(255)  DEFAULT NULL::character varying,
  ACCESSIBLE_EXTERIEUR  BOOLEAN       DEFAULT true NOT NULL,
  CONSTRAINT FK_UNICAEN_UTILISATEUR_ROLE_PARENT FOREIGN KEY (PARENT_ID) REFERENCES UNICAEN_UTILISATEUR_ROLE (ID) DEFERRABLE INITIALLY IMMEDIATE
);

CREATE UNIQUE INDEX UN_UNICAEN_UTILISATEUR_ROLE_ROLE_ID ON UNICAEN_UTILISATEUR_ROLE (ROLE_ID);
CREATE INDEX IX_UNICAEN_UTILISATEUR_ROLE_PARENT ON UNICAEN_UTILISATEUR_ROLE (PARENT_ID);

-- TABLE DES UTILISATEURS ----------------------------------------------------------------------------------------------

CREATE TABLE UNICAEN_UTILISATEUR_USER (
  ID                    SERIAL        PRIMARY KEY,
  USERNAME              VARCHAR(255)  NOT NULL,
  DISPLAY_NAME          VARCHAR(255)  NOT NULL,
  EMAIL                 VARCHAR(255),
  PASSWORD              VARCHAR(128)  DEFAULT 'application'::character varying NOT NULL,
  STATE                 BOOLEAN       DEFAULT true NOT NULL,
  PASSWORD_RESET_TOKEN  VARCHAR(256),
  LAST_ROLE_ID          INTEGER,
  CONSTRAINT UN_UNICAEN_UTILISATEUR_USER_USERNAME UNIQUE (USERNAME),
  CONSTRAINT UN_UNICAEN_UTILISATEUR_USER_PASSWORD_RESET_TOKEN UNIQUE (PASSWORD_RESET_TOKEN),
  CONSTRAINT FK_UNICAEN_UTILISATEUR_USER_LAST_ROLE FOREIGN KEY (LAST_ROLE_ID) REFERENCES UNICAEN_UTILISATEUR_ROLE(ID) DEFERRABLE INITIALLY IMMEDIATE
);

CREATE INDEX IX_UNICAEN_UTILISATEUR_USER_LAST_ROLE ON UNICAEN_UTILISATEUR_USER(LAST_ROLE_ID);

-- LINKER ROLE <-> UTILISATEUR -----------------------------------------------------------------------------------------

CREATE TABLE UNICAEN_UTILISATEUR_ROLE_LINKER (
 USER_ID         INTEGER NOT NULL,
 ROLE_ID         INTEGER NOT NULL,
 CONSTRAINT PK_UNICAEN_UTILISATEUR_ROLE_LINKER PRIMARY KEY (USER_ID, ROLE_ID),
 CONSTRAINT FK_UNICAEN_UTILISATEUR_ROLE_LINKER_USER FOREIGN KEY (USER_ID) REFERENCES UNICAEN_UTILISATEUR_USER (ID) DEFERRABLE INITIALLY IMMEDIATE,
 CONSTRAINT FK_UNICAEN_UTILISATEUR_ROLE_LINKER_ROLE FOREIGN KEY (ROLE_ID) REFERENCES UNICAEN_UTILISATEUR_ROLE (ID) DEFERRABLE INITIALLY IMMEDIATE
);

CREATE INDEX IX_UNICAEN_UTILISATEUR_ROLE_LINKER_USER ON UNICAEN_UTILISATEUR_ROLE_LINKER (USER_ID);
CREATE INDEX IX_UNICAEN_UTILISATEUR_ROLE_LINKER_ROLE ON UNICAEN_UTILISATEUR_ROLE_LINKER (ROLE_ID);

-- Partie privileges ---------------------------------------------------------------------------------------------------

-- TABLE DES CATEGORIES DE PRIVILEGE -----------------------------------------------------------------------------------

CREATE TABLE UNICAEN_PRIVILEGE_CATEGORIE (
    ID        SERIAL        PRIMARY KEY,
    CODE      VARCHAR(150)  NOT NULL,
    LIBELLE   VARCHAR(200)  NOT NULL,
    NAMESPACE VARCHAR(255),
    ORDRE     INTEGER       DEFAULT 0
);

CREATE UNIQUE INDEX UN_UNICAEN_PRIVILEGE_CATEGORIE_CODE ON UNICAEN_PRIVILEGE_CATEGORIE (CODE);

-- TABLES DES PRIVILEGES -----------------------------------------------------------------------------------------------

CREATE TABLE UNICAEN_PRIVILEGE_PRIVILEGE (
    ID           SERIAL       PRIMARY KEY,
    CATEGORIE_ID INTEGER      NOT NULL,
    CODE         VARCHAR(150) NOT NULL,
    LIBELLE      VARCHAR(200) NOT NULL,
    ORDRE        INTEGER      DEFAULT 0,
    CONSTRAINT FK_UNICAEN_PRIVILEGE_CATEGORIE FOREIGN KEY (CATEGORIE_ID) REFERENCES UNICAEN_PRIVILEGE_CATEGORIE (ID) DEFERRABLE INITIALLY IMMEDIATE
);

CREATE UNIQUE INDEX UN_UNICAEN_PRIVILEGE_CODE ON UNICAEN_PRIVILEGE_PRIVILEGE(CATEGORIE_ID, CODE);
CREATE INDEX IX_UNICAEN_PRIVILEGE_CATEGORIE ON UNICAEN_PRIVILEGE_PRIVILEGE(CATEGORIE_ID);

-- LINKER PRIVILEGE <-> ROLE -------------------------------------------------------------------------------------------

CREATE TABLE UNICAEN_PRIVILEGE_PRIVILEGE_ROLE_LINKER (
    ROLE_ID      INTEGER  NOT NULL,
    PRIVILEGE_ID INTEGER  NOT NULL,
    CONSTRAINT PK_UNICAEN_ROLE_PRIVILEGE_LINKER PRIMARY KEY (ROLE_ID, PRIVILEGE_ID),
    CONSTRAINT FK_UNICAEN_ROLE_PRIVILEGE_LINKER_ROLE FOREIGN KEY (ROLE_ID) REFERENCES UNICAEN_UTILISATEUR_ROLE (ID) DEFERRABLE INITIALLY IMMEDIATE,
    CONSTRAINT FK_UNICAEN_ROLE_PRIVILEGE_LINKER_PRIVILEGE FOREIGN KEY (PRIVILEGE_ID) REFERENCES UNICAEN_PRIVILEGE_PRIVILEGE (ID) DEFERRABLE INITIALLY IMMEDIATE
);

CREATE INDEX IX_UNICAEN_ROLE_PRIVILEGE_LINKER_ROLE ON unicaen_privilege_privilege_role_linker (ROLE_ID);
CREATE INDEX IX_UNICAEN_ROLE_PRIVILEGE_LINKER_PRIVILEGE ON unicaen_privilege_privilege_role_linker (PRIVILEGE_ID);

-- UNICAEN RENDERER ----------------------------------------------------------------------------------------------------

create table unicaen_renderer_macro
(
    id serial not null constraint unicaen_document_macro_pk primary key,
    code varchar(256) not null,
    description text,
    variable_name varchar(256) not null,
    methode_name varchar(256) not null
);

create unique index unicaen_document_macro_code_uindex on unicaen_renderer_macro (code);
create unique index unicaen_document_macro_id_uindex on unicaen_renderer_macro (id);

create table unicaen_renderer_template
(
    id serial not null constraint unicaen_content_content_pk primary key,
    code varchar(256) not null,
    description text,
    document_type varchar(256) not null,
    document_sujet text not null,
    document_corps text not null,
    document_css text
);

create unique index unicaen_content_content_code_uindex on unicaen_renderer_template (code);
create unique index unicaen_content_content_id_uindex on unicaen_renderer_template (id);
create unique index unicaen_document_rendu_id_uindex on unicaen_renderer_template (id);


create table unicaen_renderer_rendu
(
    id serial not null   constraint unicaen_document_rendu_pk primary key,
    template_id integer constraint unicaen_document_rendu_template_id_fk references unicaen_renderer_template on delete set null,
    date_generation timestamp not null,
    sujet text not null,
    corps text not null
);

-- UNICAEN MAIL --------------------------------------------------------------------------------------------------------

create table unicaen_mail_mail
(
    id serial not null constraint umail_pkey primary key,
    date_envoi timestamp not null,
    status_envoi varchar(256) not null,
    destinataires text not null,
    destinataires_initials text,
    sujet text,
    corps text,
    mots_clefs text,
    log text
);

create unique index ummail_id_uindex on unicaen_mail_mail (id);

-- UNICAEN PARAMETRE ---------------------------------------------------------------------------------------------------

create table unicaen_parametre_categorie
(
    id serial not null constraint unicaen_parametre_categorie_pk primary key,
    code varchar(1024) not null,
    libelle varchar(1024) not null,
    ordre integer default 9999,
    description text
);

create unique index unicaen_parametre_categorie_code_uindex on unicaen_parametre_categorie (code);
create unique index unicaen_parametre_categorie_id_uindex on unicaen_parametre_categorie (id);

create table unicaen_parametre_parametre
(
    id serial not null constraint unicaen_parametre_parametre_pk primary key,
    categorie_id integer not null constraint unicaen_parametre_parametre_unicaen_parametre_categorie_id_fk references unicaen_parametre_categorie,
    code varchar(1024) not null,
    libelle varchar(1024) not null,
    description text,
    valeurs_possibles text,
    valeur text,
    ordre integer default 9999
);

create unique index unicaen_parametre_parametre_id_uindex on unicaen_parametre_parametre (id);
create unique index unicaen_parametre_parametre_code_categorie_id_uindex on unicaen_parametre_parametre (code, categorie_id);

-- UNICAEN ETAT --------------------------------------------------------------------------------------------------------

create table unicaen_etat_etat_type
(
    id serial not null constraint unicaen_etat_etat_type_pk primary key,
    code varchar(256) not null,
    libelle varchar(256) not null,
    icone varchar(256),
    couleur varchar(256),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint unicaen_content_content_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint unicaen_content_content_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint unicaen_content_content_user_id_fk_3 references unicaen_utilisateur_user
);

create table unicaen_etat_etat
(
    id serial not null constraint unicaen_etat_etat_pk primary key,
    code varchar(256) not null,
    libelle varchar(256) not null,
    type_id integer not null constraint unicaen_etat_etat_unicaen_etat_etat_type_id_fk references unicaen_etat_etat_type,
    icone varchar(256),
    couleur varchar(256),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint unicaen_content_content_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint unicaen_content_content_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint unicaen_content_content_user_id_fk_3 references unicaen_utilisateur_user,
    ordre integer default 9999 not null
);

create unique index unicaen_etat_etat_id_uindex on unicaen_etat_etat (id);

-- UNICAEN VALIDATION --------------------------------------------------------------------------------------------------

create table unicaen_validation_type
(
    id serial not null constraint unicaen_validation_type_pk primary key,
    code varchar(256) not null,
    libelle varchar(1024) not null,
    refusable boolean default true not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint unicaen_validation_type_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint unicaen_validation_type_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint unicaen_validation_type_destructeur_fk references unicaen_utilisateur_user
);

create table unicaen_validation_instance
(
    id serial not null constraint unicaen_validation_instance_pk primary key,
    type_id integer not null constraint unicaen_validation_instance_unicaen_validation_type_id_fk references unicaen_validation_type on delete cascade,
    valeur text,
    entity_class varchar(1024),
    entity_id varchar(64),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint unicaen_validation_instance_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint unicaen_validation_instance_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint unicaen_validation_instance_destructeur_fk references unicaen_utilisateur_user
);

create unique index unicaen_validation_instance_id_uindex on unicaen_validation_instance (id);
create unique index unicaen_validation_type_id_uindex on unicaen_validation_type (id);

-- UNICAEN EVENEMENT ---------------------------------------------------------------------------------------------------

create table unicaen_evenement_etat
(
    id serial not null constraint pk_evenement_etat primary key,
    code varchar(255) not null constraint un_evenement_etat_code unique deferrable initially deferred,
    libelle varchar(255) not null,
    description varchar(2047)
);

create table unicaen_evenement_type
(
    id serial not null constraint pk_evenement_type primary key,
    code varchar(255) not null constraint un_evenement_type_code unique deferrable initially deferred,
    libelle varchar(255) not null,
    description varchar(2047),
    parametres varchar(2047),
    recursion varchar(64)
);

create table unicaen_evenement_instance
(
    id serial not null constraint pk_evenement_instance primary key,
    nom varchar(255) not null,
    description varchar(1024) not null,
    type_id integer not null constraint fk_evenement_instance_type references unicaen_evenement_type deferrable,
    etat_id integer not null constraint fk_evenement_instance_etat references unicaen_evenement_etat deferrable,
    parametres text,
    date_creation timestamp not null,
    date_planification timestamp not null,
    date_traitement timestamp,
    log text,
    parent_id integer constraint fk_evenement_instance_parent references unicaen_evenement_instance deferrable,
    date_fin timestamp
);

create index ix_evenement_instance_type on unicaen_evenement_instance (type_id);
create index ix_evenement_instance_etat on unicaen_evenement_instance (etat_id);
create index ix_evenement_instance_parent on unicaen_evenement_instance (parent_id);

create table unicaen_evenement_journal
(
    id serial not null constraint unicaen_evenement_journal_pk primary key,
    date_execution timestamp not null,
    log text,
    etat_id integer constraint unicaen_evenement_journal_unicaen_evenement_etat_id_fk references unicaen_evenement_etat on delete set null
);

create unique index unicaen_evenement_journal_id_uindex on unicaen_evenement_journal (id);

-- AUTOFORM ------------------------------------------------------------------------------------------------------------

create table unicaen_autoform_formulaire
(
    id serial not null constraint autoform_formulaire_pk primary key,
    libelle varchar(128) not null,
    description varchar(2048),
    code varchar(256),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint autoform_formulaire_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp ,
    histo_modificateur_id integer  constraint autoform_formulaire_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint autoform_formulaire_destructeur_fk references unicaen_utilisateur_user
);

create table unicaen_autoform_categorie
(
    id serial not null constraint autoform_categorie_pk primary key,
    code varchar(64) not null,
    libelle varchar(256) not null,
    ordre integer default 10000 not null,
    formulaire integer not null constraint autoform_categorie_formulaire_fk references unicaen_autoform_formulaire,
    mots_clefs varchar(1024),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint autoform_categorie_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint autoform_categorie_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint autoform_categorie_destructeur_fk references unicaen_utilisateur_user
);

create unique index autoform_categorie_code_uindex on unicaen_autoform_categorie (code);
create unique index autoform_categorie_id_uindex on unicaen_autoform_categorie (id);

create table unicaen_autoform_champ
(
    id serial not null constraint autoform_champ_pk primary key,
    categorie integer not null constraint autoform_champ_categorie_fk references unicaen_autoform_categorie,
    code varchar(64) not null,
    libelle varchar(1024) not null,
    texte text not null,
    ordre integer default 10000 not null,
    element varchar(64),
    balise boolean,
    options varchar(1024),
    mots_clefs varchar(1024),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint autoform_champ_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint autoform_champ_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint autoform_champ_destructeur_fk references unicaen_utilisateur_user
);

create unique index autoform_champ_id_uindex on unicaen_autoform_champ (id);
create unique index autoform_formulaire_id_uindex on unicaen_autoform_formulaire (id);

create table unicaen_autoform_formulaire_instance
(
    id serial not null constraint autoform_formulaire_instance_pk primary key,
    formulaire integer not null constraint autoform_formulaire_instance_autoform_formulaire_id_fk references unicaen_autoform_formulaire,
    histo_creation timestamp not null,
    histo_createur_id integer constraint autoform_formulaire_instance_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint autoform_formulaire_instance_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint autoform_formulaire_instance_destructeur_fk references unicaen_utilisateur_user
);

create unique index autoform_formulaire_instance_id_uindex on unicaen_autoform_formulaire_instance (id);

create table unicaen_autoform_formulaire_reponse
(
    id serial not null constraint autoform_reponse_pk primary key,
    instance integer not null constraint autoform_formulaire_reponse_instance_fk references unicaen_autoform_formulaire_instance on delete cascade,
    champ integer not null constraint autoform_reponse_champ_fk references unicaen_autoform_champ on delete cascade,
    reponse text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint autoform_reponse_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint autoform_reponse_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint autoform_reponse_destructeur_fk references unicaen_utilisateur_user
);

create unique index autoform_reponse_id_uindex on unicaen_autoform_formulaire_reponse (id);

create table unicaen_autoform_validation
(
    id serial not null constraint validation_pk primary key,
    type varchar(64) not null,
    instance integer not null constraint validation_instance_fk references unicaen_autoform_formulaire_instance on delete cascade,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint validation_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint validation_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint validation_destructeur_fk references unicaen_utilisateur_user
);

create unique index validation_id_uindex on unicaen_autoform_validation (id);

create table unicaen_autoform_validation_reponse
(
    id serial not null constraint validation_reponse_pk primary key,
    validation integer not null constraint autoform_validation_reponse_autoform_validation_id_fk references unicaen_autoform_validation on delete cascade,
    reponse integer not null constraint validation_reponse_autoform_reponse_id_fk references unicaen_autoform_formulaire_reponse on delete cascade,
    value boolean default false not null
);

create unique index validation_reponse_id_uindex on unicaen_autoform_validation_reponse (id);

-- FICHIER -------------------------------------------------------------------------------------------------------------

create table fichier_nature
(
    id serial not null constraint fichier_nature_pk primary key,
    code varchar(64) not null,
    libelle varchar(256) not null,
    description varchar(2048)
);
create unique index fichier_nature_code_uindex on fichier_nature (code);
create unique index fichier_nature_id_uindex on fichier_nature (id);

create table fichier_fichier
(
    id varchar(13) not null constraint fichier_fichier_pk primary key,
    nom_original varchar(256) not null,
    nom_stockage varchar(256) not null,
    nature integer not null,
    type_mime varchar(256) not null,
    taille varchar(256),
    histo_creation timestamp not null,
    histo_createur_id integer not null,
    histo_modification timestamp,
    histo_modificateur_id integer,
    histo_destruction timestamp,
    histo_destructeur_id integer
);
create unique index fichier_fichier_id_uindex on fichier_fichier (id);
create unique index fichier_fichier_nom_stockage_uindex on fichier_fichier (nom_stockage);


-----------------------------------------------------------------------------------------
-- UNICAEN INDICATEUR -------------------------------------------------------------------
-----------------------------------------------------------------------------------------


create table unicaen_indicateur
(
    id serial not null constraint indicateur_pk primary key,
    titre varchar(256) not null,
    description varchar(2048),
    requete varchar(4096) not null,
    dernier_rafraichissement timestamp,
    view_id varchar(256),
    entity varchar(256)
);

create table unicaen_indicateur_abonnement
(
    id serial not null constraint abonnement_pk primary key,
    user_id integer not null constraint indicateur_abonnement_user_id_fk references unicaen_utilisateur_user on delete cascade,
    indicateur_id integer not null constraint indicateur_abonnement_indicateur_definition_id_fk references unicaen_indicateur on delete cascade,
    frequence varchar(256),
    dernier_envoi timestamp
);
create unique index abonnement_id_uindex on unicaen_indicateur_abonnement (id);
create unique index indicateur_id_uindex on unicaen_indicateur (id);


-- IMPORT

create table import_log
(
    type         varchar(128)          not null,
    name         varchar(128)          not null,
    log          text                  not null,
    started_on   timestamp(0)          not null,
    ended_on     timestamp(0)          not null,
    import_hash  varchar(64),
    has_problems boolean default false not null,
    id           serial
        constraint import_log_pk
            primary key,
    success      boolean default true  not null
);
CREATE SEQUENCE agent_carriere_echelon_ID_SEQ ;
create unique index import_log_id_uindex
    on import_log (id);

create table source
(
    id         bigint       not null
        primary key,
    code       varchar(64)  not null
        unique,
    libelle    varchar(128) not null,
    importable boolean      not null
);

-- PRIVILEGE -----------------------------------------------------------------------------------------------------------

-- UNICAEN - UTILISATEUR --------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('utilisateur', 'Gestion des utilisateurs', 20010, 'UnicaenUtilisateur\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'utilisateur_afficher', 'Rechercher/Afficher un utilisateur', 10 UNION
    SELECT 'utilisateur_ajouter', 'Ajouter/Supprimer un utilisateur', 20 UNION
    SELECT 'utilisateur_changerstatus', 'Changer le statut d''un d''utilisateur', 30 UNION
    SELECT 'utilisateur_modifierrole', 'Modifier les rôles associés à un utilisateur', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'utilisateur'
;

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('role', 'Gestion des rôles', 20010, 'UnicaenUtilisateur\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'role_afficher', 'Afficher les rôles', 10 UNION
    SELECT 'role_modifier', 'Modifier un rôle', 20 UNION
    SELECT 'role_effacer', 'Effacer un rôle', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'role'
;

-- UNICAEN - PRIVILEGE ----------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('privilege', 'Gestion des privilèges', 10000, 'UnicaenPrivilege\Provider\Privilege');

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
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'privilege'
;

-- UNICAEN - PARAMETRE ----------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('parametrecategorie', 'UnicaenParametre - Gestion des catégories de paramètres', 70000, 'UnicaenParametre\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'parametrecategorie_index', 'Affichage de l''index des paramètres', 10 UNION
    SELECT 'parametrecategorie_afficher', 'Affichage des détails d''une catégorie', 20 UNION
    SELECT 'parametrecategorie_ajouter', 'Ajouter une catégorie de paramètre', 30 UNION
    SELECT 'parametrecategorie_modifier', 'Modifier une catégorie de paramètre', 40 UNION
    SELECT 'parametrecategorie_supprimer', 'Supprimer une catégorie de paramètre', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'parametrecategorie'
;

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('parametre', 'UnicaenParametre - Gestion des paramètres', 70001, 'UnicaenParametre\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'parametre_afficher', 'Afficher un paramètre', 10 UNION
    SELECT 'parametre_ajouter', 'Ajouter un paramètre', 20 UNION
    SELECT 'parametre_modifier', 'Modifier un paramètre', 30 UNION
    SELECT 'parametre_supprimer', 'Supprimer un paramètre', 50 UNION
    SELECT 'parametre_valeur', 'Modifier la valeur d''un parametre', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'parametre'
;

-- UNICAEN - MAIL ---------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('mail', 'UnicaenMail - Gestion des mails', 9000, 'UnicaenMail\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'mail_index', 'Affichage de l''index', 10 UNION
    SELECT 'mail_afficher', 'Afficher un mail', 20 UNION
    SELECT 'mail_reenvoi', 'Ré-envoi d''un mail', 30 UNION
    SELECT 'mail_supprimer', 'Suppression d''un mail', 40 UNION
    SELECT 'mail_test', 'Envoi d''un mail de test', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'mail'
;

-- UNICAEN - RENDERER -----------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('documenttemplate', 'UnicaenRenderer - Gestion des templates', 11020, 'UnicaenRenderer\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documenttemplate_index', 'Afficher l''index des contenus', 1 UNION
    SELECT 'documenttemplate_afficher', 'Afficher un template', 10 UNION
    SELECT 'documenttemplate_ajouter', 'Ajouter un contenu', 15 UNION
    SELECT 'documenttemplate_modifier', 'Modifier un contenu', 20 UNION
    SELECT 'documenttemplate_supprimer', 'Supprimer un contenu', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documenttemplate'
;

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('documentmacro', 'UnicaenRenderer - Gestion des macros', 11010, 'UnicaenRenderer\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documentmacro_index', 'Afficher l''index des macros', 1 UNION
    SELECT 'documentmacro_ajouter', 'Ajouter une macro', 10 UNION
    SELECT 'documentmacro_modifier', 'Modifier une macro', 20 UNION
    SELECT 'documentmacro_supprimer', 'Supprimer une macro', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documentmacro'
;


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('documentcontenu', 'UnicaenRenderer - Gestion des contenus', 11030, 'UnicaenRenderer\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documentcontenu_index', 'Accès à l''index des contenus', 10 UNION
    SELECT 'documentcontenu_afficher', 'Afficher un contenu', 20 UNION
    SELECT 'documentcontenu_supprimer', 'Supprimer un contenu ', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documentcontenu'
;

-- UNICAEN - VALIDATION ---------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('validationtype', 'Gestion des types de validations', 40010, 'UnicaenValidation\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'validationtype_afficher', 'Affichage des types de validations', 10 UNION
    SELECT 'validationtype_modifier', 'Modifier un type de validation', 30 UNION
    SELECT 'validationtype_historiser', 'Historiser/restaurer un type de validation', 40 UNION
    SELECT 'validationtype_detruire', 'Détruire un type de validation', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'validationtype'
;


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('validationinstance', 'Gestion des instances de validations', 40000, 'UnicaenValidation\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'validationinstance_afficher', 'Affichage des instances de validations', 10 UNION
    SELECT 'validationinstance_modifier', 'Modifier une instance de validation', 20 UNION
    SELECT 'validationinstance_historiser', 'Historiser/restaurer une instance de validation', 40 UNION
    SELECT 'validationinstance_detruire', 'Détruire une isntance de validation', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'validationinstance'
;

-- UNICAEN - GLOSSAIRE ----------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('definition', 'UnicaenGlossaire - Gestion des définitions', 60000, 'UnicaenGlossaire\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'definition_index', 'Afficher l''index des définitions', 10 UNION
    SELECT 'definition_afficher', 'Afficher une définition', 20 UNION
    SELECT 'definition_ajouter', 'Ajouter une définition', 30 UNION
    SELECT 'definition_modifier', 'Modifier une définition', 40 UNION
    SELECT 'definition_historiser', 'Historiser/Restaurer une définition', 50 UNION
    SELECT 'definition_supprimer', 'Supprimer une définition', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'definition'
;

-- UNICAEN - ETAT ---------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('etat', 'UnicaenEtat - Gestion des états', 20000, 'UnicaenEtat\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'etat_index', 'Afficher l''index des états', 10 UNION
    SELECT 'etat_ajouter', 'Ajouter un état', 20 UNION
    SELECT 'etat_modifier', 'Modifier un état', 30 UNION
    SELECT 'etat_historiser', 'Historiser/Restaurer un etat', 40 UNION
    SELECT 'etat_detruire', 'Supprimer un état', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'etat'
;

-- UNICAEN - AUTOFORM -----------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('autoform', 'Gestion des formulaires', 1500, 'UnicaenAutoform\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'afficher-index', 'Affichage de l''index du module', 1 UNION
    SELECT 'afficher-formulaire', 'Visualiser les formualaires', 2 UNION
    SELECT 'creer-formulaire', 'Créer un formulaire', 3 UNION
    SELECT 'modifier-formulaire', 'Modifier un formulaire', 4 UNION
    SELECT 'historiser-formulaire', 'Historiser un formulaire', 5 UNION
    SELECT 'detruire-formulaire', 'Détruire une formulaire', 6 UNION
    SELECT 'creer-categorie', 'Créer une catégorie', 7 UNION
    SELECT 'modifier-categorie', 'Modifier une catégorie', 8 UNION
    SELECT 'historiser-categorie', 'Historiser une catégorie', 9 UNION
    SELECT 'detruire-categorie', 'Détruire une catégorie', 10 UNION
    SELECT 'creer-champ', 'Créer un champ', 11 UNION
    SELECT 'modifier-champ', 'Modifier un champ', 12 UNION
    SELECT 'historiser-champ', 'Historiser un champ', 13 UNION
    SELECT 'detruire-champ', 'Détruire un champ', 14 UNION
    SELECT 'afficher-validation', 'Afficher une validation', 40 UNION
    SELECT 'creer-validation', 'Créer une validation', 41 UNION
    SELECT 'modifier-validation', 'Modifier une validation', 42 UNION
    SELECT 'historiser-validation', 'Historiser une validation', 43 UNION
    SELECT 'detruire-validation', 'Détruire une validation', 44
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoform'
;

-- EVENEMENT --------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('evenementetat', 'Gestion des événements - État', 99991, 'UnicaenEvenement\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'etat_consultation', 'état - consultation', 10 UNION
    SELECT 'etat_ajout', 'état - ajout', 20 UNION
    SELECT 'etat_edition', 'état - édition', 30 UNION
    SELECT 'etat_suppression', 'état - suppression', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'evenementetat';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('evenementinstance', 'Gestion des événements - Instance', 99993, 'UnicaenEvenement\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'instance_consultation', 'instance - consultation', 10 UNION
    SELECT 'instance_ajout', 'instance - ajout', 20 UNION
    SELECT 'instance_edition', 'instance - édition', 30 UNION
    SELECT 'instance_suppression', 'instance - suppression', 40 UNION
    SELECT 'instance_traitement', 'instance - traitement', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'evenementinstance';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('evenementtype', 'Gestion des événements - Type', 99992, 'UnicaenEvenement\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'type_consultation', 'type - consultation', 10 UNION
    SELECT 'type_ajout', 'type - ajout', 20 UNION
    SELECT 'type_edition', 'type - édition', 30 UNION
    SELECT 'type_suppression', 'type - suppression', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'evenementtype';

-- indicateur -----------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('indicateur', 'Gestion des indicateurs', 99992, 'Indicateur\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'afficher-indicateur', 'Afficher un indicateur', 10 UNION
    SELECT 'editer-indicateur', 'Modifier un indicateur', 20 UNION
    SELECT 'detruire-indicateur', 'Supprimer un indicateur', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'indicateur';
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'afficher-abonnement', 'Afficher un abonnement', 110 UNION
    SELECT 'editer-abonnement', 'Modifier un abonnement', 120 UNION
    SELECT 'detruire-abonnement', 'Supprimer un abonnement', 130
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'indicateur';

-- synchro ----------------------------------------------------------------------------------------------------
INSERT INTO public.unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('unicaen-db-import-import', 'DB Import - Import', 'UnicaenDbImport\Privilege', 99994);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'lister', 'Lister les logs', 1 UNION
    SELECT 'consulter', 'Consulter un log', 2 UNION
    SELECT 'lancer', 'Lancer un import', 3

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaen-db-import-import';

INSERT INTO public.unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('unicaen-db-import-log', 'DB Import - Log', 'UnicaenDbImport\Privilege', 99995);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'lister', 'Lister les logs', 1 UNION
    SELECT 'consulter', 'Consulter un log', 2
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaen-db-import-log';


INSERT INTO public.unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('unicaen-db-import-observation', 'DB Import - Observation', 'UnicaenDbImport\Privilege', 99996);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'lister', 'Lister les observations', 1 UNION
    SELECT 'consulter-resultat', 'Consulter les résultats', 2
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaen-db-import-observation';

INSERT INTO public.unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('unicaen-db-import-synchro', 'DB Import - Synchro', 'UnicaenDbImport\Privilege', 99997);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'lister', 'Lister les synchro', 1 UNION
    SELECT 'consulter', 'Consulter une synchro', 2 UNION
    SELECT 'lancer', 'Lancer un synchro', 3

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
 JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaen-db-import-synchro';

-- UNICAEN - AIDE ----------------------------------------------------------------------------------------

create table unicaen_aide_glossaire_definition
(
    id  serial constraint unicaen_glossaire_definition_pk primary key,
    terme        varchar(1024)                                                                 not null,
    definition   text                                                                          not null,
    alternatives text,
    historisee   boolean default false                                                         not null
);
create unique index unicaen_glossaire_definition_id_uindex on unicaen_aide_glossaire_definition (id);
create unique index unicaen_glossaire_definition_terme_uindex on unicaen_aide_glossaire_definition (terme);

create table unicaen_aide_faq_question
(
    id         serial
        constraint unicaen_faq_question_pk
            primary key,
    question   varchar(4096) not null,
    reponse    text          not null,
    historisee boolean default false,
    ordre      integer
);

create unique index unicaen_faq_question_id_uindex
    on unicaen_aide_faq_question (id);

create table unicaen_aide_documentation_lien
(
    id          serial
        constraint unicaen_aide_documentation_lien_pk
            primary key,
    texte       varchar(1024),
    lien_texte  varchar(1024) not null,
    lien_url    varchar(1024) not null,
    description text,
    ordre       integer,
    historisee  boolean default false,
    role_ids    varchar(4096)
);


create unique index unicaen_aide_documentation_lien_id_uindex
    on unicaen_aide_documentation_lien (id);

-----------------------------------------------------------------------------------------------
-- INSERT -------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------

-- ROLE et UTILISATEUR ------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_auto) VALUES ('Administrateur·trice technique', 'Administrateur·trice technique', false);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_auto) VALUES ('Administrateur·trice fonctionnel·le', 'Administrateur·trice fonctionnel·le', false);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_auto) VALUES ('Directeur·trice des ressources humaines', 'Directeur·trice des ressources humaines', false);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_auto) VALUES ('Observateur·trice', 'Observateur·trice', false);

INSERT INTO unicaen_utilisateur_user (id, username, display_name, email, password, state, password_reset_token, last_role_id) VALUES (0, 'emc2', 'EMC2', 'ne-pas-repondre@domain.fr', 'ldap', false, null, null);

-- ETAT EVENT

INSERT INTO unicaen_evenement_etat (id, code, libelle) VALUES (1, 'en_attente', 'En attente');
INSERT INTO unicaen_evenement_etat (id, code, libelle) VALUES (2, 'en_cours', 'En cours');
INSERT INTO unicaen_evenement_etat (id, code, libelle) VALUES (3, 'echec', 'Échec');
INSERT INTO unicaen_evenement_etat (id, code, libelle) VALUES (4, 'succes', 'Succès');