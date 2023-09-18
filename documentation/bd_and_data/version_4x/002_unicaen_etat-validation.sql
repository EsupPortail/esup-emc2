-- TABLES - ETAT -------------------------------------------------------------------------------

create table unicaen_etat_categorie (
       id serial primary key,
       code character varying(256) not null,
       libelle character varying(256) not null,
       icone character varying(256),
       couleur character varying(256),
       ordre integer default 9999
);
create unique index unicaen_etat_categorie_id_uindex on unicaen_etat_categorie using btree (id);

create table unicaen_etat_type (
       id serial primary key,
       code character varying(256) not null,
       libelle character varying(256) not null,
       categorie_id integer,
       icone character varying(256),
       couleur character varying(256),
       ordre integer not null default 9999,
       foreign key (categorie_id) references unicaen_etat_categorie (id)
           match simple on update no action on delete no action
);
create unique index unicaen_etat_type_id_uindex on unicaen_etat_type using btree (id);

create table unicaen_etat_instance (
          id serial primary key,
          type_id integer not null,
          histo_creation timestamp without time zone not null default now(),
          histo_createur_id integer not null default 0,
          histo_modification timestamp without time zone,
          histo_modificateur_id integer,
          histo_destruction timestamp without time zone,
          histo_destructeur_id integer,
          complement text,
          foreign key (histo_createur_id) references unicaen_utilisateur_user (id)
              match simple on update no action on delete no action,
          foreign key (histo_modificateur_id) references unicaen_utilisateur_user (id)
              match simple on update no action on delete no action,
          foreign key (histo_destructeur_id) references unicaen_utilisateur_user (id)
              match simple on update no action on delete no action,
          foreign key (type_id) references unicaen_etat_type (id)
              match simple on update no action on delete no action
);
create unique index unicaen_etat_instance_id_index on unicaen_etat_instance using btree (id);

-- TABLES - VALIDATION ----------------------------------------------------------------------

create table unicaen_validation_type
(
    id                    serial
        constraint unicaen_validation_type_pk
            primary key,
    code                  varchar(256)         not null,
    libelle               varchar(1024)        not null,
    refusable             boolean default true not null,
    histo_creation        timestamp            default now() not null,
    histo_createur_id     integer              default 0 not null
        constraint unicaen_validation_type_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint unicaen_validation_type_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint unicaen_validation_type_destructeur_fk
            references unicaen_utilisateur_user
);

create unique index unicaen_validation_type_id_uindex
    on unicaen_validation_type (id);

create table unicaen_validation_instance
(
    id                    serial
        constraint unicaen_validation_instance_pk
            primary key,
    type_id               integer               not null
        constraint unicaen_validation_instance_unicaen_validation_type_id_fk
            references unicaen_validation_type
            on delete cascade,
    entity_class          varchar(1024),
    entity_id             varchar(64),
    histo_creation        timestamp             not null,
    histo_createur_id     integer               not null
        constraint unicaen_validation_instance_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp             not null,
    histo_modificateur_id integer               not null
        constraint unicaen_validation_instance_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint unicaen_validation_instance_destructeur_fk
            references unicaen_utilisateur_user,
    justification         text,
    refus                 boolean default false not null
);

create unique index unicaen_validation_instance_id_uindex
    on unicaen_validation_instance (id);

-- PRIVILEGES - ETAT ------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('etat', 'Unicaen - Gestion des états - État', 'UnicaenEtat\Provider\Privilege', 20000);
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
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'etat';

-- PRIVILEGES - VALIDATION --------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('validationtype', 'Gestion des types de validations', 'UnicaenValidation\Provider\Privilege', 40010);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'validationtype_afficher', 'Affichage des types de validations', 10 UNION
    SELECT 'validationtype_modifier', 'Modifier un type de validation', 30 UNION
    SELECT 'validationtype_historiser', 'Historiser/restaurer un type de validation', 40 UNION
    SELECT 'validationtype_detruire', 'Détruire un type de validation', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'validationtype';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('validationinstance', 'Gestion des instances de validations', 'UnicaenValidation\Provider\Privilege', 40000);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'validationinstance_afficher', 'Affichage des instances de validations', 10 UNION
    SELECT 'validationinstance_modifier', 'Modifier une instance de validation', 20 UNION
    SELECT 'validationinstance_historiser', 'Historiser/restaurer une instance de validation', 40 UNION
    SELECT 'validationinstance_detruire', 'Détruire une isntance de validation', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'validationinstance';

