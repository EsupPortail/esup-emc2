-- TABLE - INDICATEUR -------------------------------------------------------------

create table unicaen_indicateur_indicateur
(
    id  serial          constraint indicateur_pk primary key,
    titre                    varchar(256)                                                              not null,
    description              varchar(2048),
    requete                  varchar(4096)                                                             not null,
    dernier_rafraichissement timestamp,
    view_id                  varchar(256),
    entity                   varchar(256),
    namespace                varchar(1024)
);
create unique index indicateur_id_uindex on unicaen_indicateur_indicateur (id);

create table unicaen_indicateur_tableaudebord
(
    id          serial
        constraint unicaen_indicateur_tableaudebord_pk
            primary key,
    titre       varchar(1024) default 'Tableau de bord'::character varying not null,
    description text,
    nb_column   integer       default 1                                    not null,
    namespace   varchar(1024)
);

create table unicaen_indicateur_tableau_indicateur
(
    tableau_id    integer
        constraint unicaen_indicateur_tableau_indicateur_tableaudebord_null_fk
            references unicaen_indicateur_tableaudebord
            on delete cascade,
    indicateur_id integer not null
        constraint unicaen_indicateur_tableau_indicateur_indicateur_null_fk
            references unicaen_indicateur_indicateur
            on delete cascade,
    constraint unicaen_indicateur_tableau_indicateur_pk
        primary key (tableau_id, indicateur_id)
);


create table unicaen_indicateur_abonnement
(
    id serial not null constraint abonnement_pk primary key,
    user_id integer not null constraint indicateur_abonnement_user_id_fk references unicaen_utilisateur_user on delete cascade,
    indicateur_id integer not null constraint indicateur_abonnement_indicateur_definition_id_fk references unicaen_indicateur_indicateur on delete cascade,
    frequence varchar(256),
    dernier_envoi timestamp
);
create unique index abonnement_id_uindex on unicaen_indicateur_abonnement (id);
-- TABLES - EVENEMENT ----------------------------------------------------------------------

create table unicaen_evenement_etat
(
    id          serial
        constraint pk_evenement_etat
            primary key,
    code        varchar(255) not null
        constraint un_evenement_etat_code
            unique
                deferrable initially deferred,
    libelle     varchar(255) not null,
    description varchar(2047)
);

create table unicaen_evenement_type
(
    id          serial
        constraint pk_evenement_type
            primary key,
    code        varchar(255) not null
        constraint un_evenement_type_code
            unique
                deferrable initially deferred,
    libelle     varchar(255) not null,
    description varchar(2047),
    parametres  varchar(2047),
    recursion   varchar(64)
);

create table unicaen_evenement_instance
(
    id                 serial
        constraint pk_evenement_instance
            primary key,
    nom                varchar(255)  not null,
    description        varchar(1024) not null,
    type_id            integer       not null
        constraint fk_evenement_instance_type
            references unicaen_evenement_type
            deferrable,
    etat_id            integer       not null
        constraint fk_evenement_instance_etat
            references unicaen_evenement_etat
            deferrable,
    parametres         text,
    date_creation      timestamp     not null,
    date_planification timestamp     not null,
    date_traitement    timestamp,
    log                text,
    parent_id          integer
        constraint fk_evenement_instance_parent
            references unicaen_evenement_instance
            deferrable,
    date_fin           timestamp,
    mots_clefs         text
);
create index ix_evenement_instance_type on unicaen_evenement_instance (type_id);
create index ix_evenement_instance_etat on unicaen_evenement_instance (etat_id);
create index ix_evenement_instance_parent on unicaen_evenement_instance (parent_id);

create table unicaen_evenement_journal
(
    id             serial
        constraint unicaen_evenement_journal_pk
            primary key,
    date_execution timestamp not null,
    log            text,
    etat_id        integer
        constraint unicaen_evenement_journal_unicaen_evenement_etat_id_fk
            references unicaen_evenement_etat
            on delete set null
);
create unique index unicaen_evenement_journal_id_uindex on unicaen_evenement_journal (id);

-- TABLES - PARAMETRE ----------------------------------------------------------------------

create table unicaen_parametre_categorie
(
    id          serial
        constraint unicaen_parametre_categorie_pk
            primary key,
    code        varchar(1024) not null,
    libelle     varchar(1024) not null,
    ordre       integer default 9999,
    description text
);
create unique index unicaen_parametre_categorie_code_uindex on unicaen_parametre_categorie (code);
create unique index unicaen_parametre_categorie_id_uindex on unicaen_parametre_categorie (id);

create table unicaen_parametre_parametre
(
    id                serial
        constraint unicaen_parametre_parametre_pk
            primary key,
    categorie_id      integer       not null
        constraint unicaen_parametre_parametre_unicaen_parametre_categorie_id_fk
            references unicaen_parametre_categorie,
    code              varchar(1024) not null,
    libelle           varchar(1024) not null,
    description       text,
    valeurs_possibles text,
    valeur            text,
    ordre             integer default 9999
);
create unique index unicaen_parametre_parametre_id_uindex on unicaen_parametre_parametre (id);
create unique index unicaen_parametre_parametre_code_categorie_id_uindex on unicaen_parametre_parametre (code, categorie_id);


-- PRIVILEGES - INDICATEUR -----------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('abonnement', 'Gestions des abonnement', 'UnicaenIndicateur\Provider\Privilege', 810);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'afficher_abonnement', 'Afficher un abonnement', 4 UNION
    SELECT 'editer_abonnement', 'Éditer un abonnement', 5 UNION
    SELECT 'detruire_abonnement', 'Effacer un abonnement', 6
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformvalidation';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('indicateur', 'Gestions des indicateurs', 'UnicaenIndicateur\Provider\Privilege', 800);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'afficher_indicateur', 'Afficher un indicateur', 1 UNION
    SELECT 'editer_indicateur', 'Éditer un indicateur', 2 UNION
    SELECT 'detruire_indicateur', 'Effacer un indicateur', 3
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'indicateur';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('tableaudebord', 'Gestions des tableaux', 'UnicaenIndicateur\Provider\Privilege', 820);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'afficher_tableaudebord', 'Afficher un tableau de bord', 4 UNION
    SELECT 'editer_tableaudebord', 'Éditer un tableau de bord', 5 UNION
    SELECT 'detruire_tableaudebord', 'Effacer un tableau de bord', 6
    )
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'tableaudebord';

-- PRIVILEGES - EVENEMENT --------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('evenementetat', 'Gestion des événements - État', 'UnicaenEvenement\Provider\Privilege', 99991);
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

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('evenementinstance', 'Gestion des événements - Instance', 'UnicaenEvenement\Provider\Privilege', 99993);
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

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('evenementtype', 'Gestion des événements - Type', 'UnicaenEvenement\Provider\Privilege', 99992);
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

-- PRIVILEGES - PARAMETRE ---------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('parametre', 'UnicaenParametre - Gestion des paramètres', 'UnicaenParametre\Provider\Privilege', 70001);
INSERT INTO unicaen_privilege_privilege (CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'parametre_afficher', 'Afficher un paramètre', 10 UNION
    SELECT 'parametre_ajouter', 'Ajouter un paramètre', 20 UNION
    SELECT 'parametre_modifier', 'Modifier un paramètre', 30 UNION
    SELECT 'parametre_supprimer', 'Supprimer un paramètre', 50 UNION
    SELECT 'parametre_valeur', 'Modifier la valeur d''un parametre', 100
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'parametre';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('parametrecategorie', 'UnicaenParametre - Gestion des catégories de paramètres', 'UnicaenParametre\Provider\Privilege', 70000);
INSERT INTO unicaen_privilege_privilege (CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'parametrecategorie_index', 'Affichage de l''index des paramètres', 10 UNION
    SELECT 'parametrecategorie_afficher', 'Affichage des détails d''une catégorie', 20 UNION
    SELECT 'parametrecategorie_ajouter', 'Ajouter une catégorie de paramètre', 30 UNION
    SELECT 'parametrecategorie_modifier', 'Modifier une catégorie de paramètre', 40 UNION
    SELECT 'parametrecategorie_supprimer', 'Supprimer une catégorie de paramètre', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'parametrecategorie';

-- ETAT DE EVENEMENT

INSERT INTO unicaen_evenement_etat (code, libelle, description) VALUES ('en_attente', 'En attente', null);
INSERT INTO unicaen_evenement_etat (code, libelle, description) VALUES ('en_cours', 'En cours', null);
INSERT INTO unicaen_evenement_etat (code, libelle, description) VALUES ('echec', 'Échec', null);
INSERT INTO unicaen_evenement_etat (code, libelle, description) VALUES ('succes', 'Succès', null);
INSERT INTO unicaen_evenement_etat (code, libelle, description) VALUES ('annule', 'Événement dont le traitement a été annulé', null);
