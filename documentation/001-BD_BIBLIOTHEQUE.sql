-- ---------------------------------------------------------------------------------------------------------------------
-- Partie roles & utilisateurs -----------------------------------------------------------------------------------------
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

-- ROLE ET UTILISATEUR MINI --------------------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_user (id, username, display_name, email, password, state, password_reset_token, last_role_id) VALUES (0, 'preecog', 'PrEECoG', 'ne-pas-repondre@domain.fr', 'ldap', false, null, null);

INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Administrateur·trice technique', 'Administrateur·trice technique', false, null, null, false, true);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Observateur', 'Observateur', false, null, null, false, true);

-- ---------------------------------------------------------------------------------------------------------------------
-- Partie privileges ---------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

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

CREATE TABLE UNICAEN_ROLE_PRIVILEGE_LINKER (
    ROLE_ID      INTEGER  NOT NULL,
    PRIVILEGE_ID INTEGER  NOT NULL,
    CONSTRAINT PK_UNICAEN_ROLE_PRIVILEGE_LINKER PRIMARY KEY (ROLE_ID, PRIVILEGE_ID),
    CONSTRAINT FK_UNICAEN_ROLE_PRIVILEGE_LINKER_ROLE FOREIGN KEY (ROLE_ID) REFERENCES UNICAEN_UTILISATEUR_ROLE (ID) DEFERRABLE INITIALLY IMMEDIATE,
    CONSTRAINT FK_UNICAEN_ROLE_PRIVILEGE_LINKER_PRIVILEGE FOREIGN KEY (PRIVILEGE_ID) REFERENCES UNICAEN_PRIVILEGE_PRIVILEGE (ID) DEFERRABLE INITIALLY IMMEDIATE
);

CREATE INDEX IX_UNICAEN_ROLE_PRIVILEGE_LINKER_ROLE ON unicaen_privilege_privilege_role_linker (ROLE_ID);
CREATE INDEX IX_UNICAEN_ROLE_PRIVILEGE_LINKER_PRIVILEGE ON unicaen_privilege_privilege_role_linker (PRIVILEGE_ID);

-- ---------------------------------------------------------------------------------------------------------------------
-- UNICAEN RENDERER ----------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

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

-- ---------------------------------------------------------------------------------------------------------------------
-- UNICAEN MAIL --------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

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

-- ---------------------------------------------------------------------------------------------------------------------
-- UNICAEN PARAMETRE ---------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

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

-- ---------------------------------------------------------------------------------------------------------------------
-- UNICAEN ETAT --------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

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

-- ---------------------------------------------------------------------------------------------------------------------
-- UNICAEN GLOSSAIRE ---------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table unicaen_glossaire_definition
(
    id serial not null constraint unicaen_glossaire_definition_pk primary key,
    terme varchar(1024) not null,
    definition text not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint unicaen_glossaire_definition_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint unicaen_glossaire_definition_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint unicaen_glossaire_definition_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user,
    alternatives text
);

create unique index unicaen_glossaire_definition_id_uindex on unicaen_glossaire_definition (id);
create unique index unicaen_glossaire_definition_terme_uindex on unicaen_glossaire_definition (terme);

-- ---------------------------------------------------------------------------------------------------------------------
-- UNICAEN VALIDATION --------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

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

-- ---------------------------------------------------------------------------------------------------------------------
-- UNICAEN EVENEMENT ---------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

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

-- ---------------------------------------------------------------------------------------------------------------------
-- AUTOFORM ------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------

create table unicaen_autoform_formulaire
(
    id serial not null constraint autoform_formulaire_pk primary key,
    libelle varchar(128) not null,
    description varchar(2048),
    code varchar(256),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint autoform_formulaire_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint autoform_formulaire_modificateur_fk references unicaen_utilisateur_user,
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

-- ---------------------------------------------------------------------------------------------------------------------

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


