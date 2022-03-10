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
 UTILISATEUR_ID  INTEGER NOT NULL,
 ROLE_ID         INTEGER NOT NULL,
 CONSTRAINT PK_UNICAEN_UTILISATEUR_ROLE_LINKER PRIMARY KEY (UTILISATEUR_ID, ROLE_ID),
 CONSTRAINT FK_UNICAEN_UTILISATEUR_ROLE_LINKER_USER FOREIGN KEY (UTILISATEUR_ID) REFERENCES UNICAEN_UTILISATEUR_USER (ID) DEFERRABLE INITIALLY IMMEDIATE,
 CONSTRAINT FK_UNICAEN_UTILISATEUR_ROLE_LINKER_ROLE FOREIGN KEY (ROLE_ID) REFERENCES UNICAEN_UTILISATEUR_ROLE (ID) DEFERRABLE INITIALLY IMMEDIATE
);

CREATE INDEX IX_UNICAEN_UTILISATEUR_ROLE_LINKER_USER ON UNICAEN_UTILISATEUR_ROLE_LINKER (UTILISATEUR_ID);
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

CREATE INDEX IX_UNICAEN_ROLE_PRIVILEGE_LINKER_ROLE ON UNICAEN_ROLE_PRIVILEGE_LINKER (ROLE_ID);
CREATE INDEX IX_UNICAEN_ROLE_PRIVILEGE_LINKER_PRIVILEGE ON UNICAEN_ROLE_PRIVILEGE_LINKER (PRIVILEGE_ID);

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
