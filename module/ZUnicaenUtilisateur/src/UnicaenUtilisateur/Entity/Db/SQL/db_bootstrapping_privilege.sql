-------------------------------------------------------------------------------
-- CATEGORIE ------------------------------------------------------------------
-------------------------------------------------------------------------------

create table categorie_privilege
(
    id serial not null
        constraint categorie_privilege_pk
            primary key,
    code varchar(150) not null,
    libelle varchar(200) not null,
    ordre integer default 0,
    namespace varchar(256)
);

create unique index categorie_privilege_id_uindex
    on categorie_privilege (id);

-------------------------------------------------------------------------------
-- PRIVILEGE ------------------------------------------------------------------
-------------------------------------------------------------------------------

create table privilege
(
    id serial not null
        constraint privilege_pk
            primary key,
    categorie_id integer not null
        constraint privilege_categorie_privilege_id_fk
            references categorie_privilege
            on delete cascade,
    code varchar(150),
    libelle varchar(200) not null,
    ordre integer default 0
);

create unique index privilege_id_uindex
    on privilege (id);

-------------------------------------------------------------------------------
-- LINKER ---------------------------------------------------------------------
-------------------------------------------------------------------------------

create table role_privilege
(
    role_id integer not null
        constraint role_privilege_user_role_id_fk
            references user_role
            on delete cascade,
    privilege_id integer not null
        constraint role_privilege_privilege_id_fk
            references privilege
            on delete cascade,
    constraint role_privilege_pk
        primary key (role_id, privilege_id)
);

-------------------------------------------------------------------------------
-- INSERTION ------------------------------------------------------------------
-------------------------------------------------------------------------------

INSERT INTO categorie_privilege (code, libelle, ordre, namespace) VALUES ('utilisateur', 'Gestion des utilisateurs', 1000, 'UnicaenUtilisateur\Provider\Privilege');
INSERT INTO categorie_privilege (code, libelle, ordre, namespace) VALUES ('role', 'Gestion des rôles', 1100, 'UnicaenUtilisateur\Provider\Privilege');

INSERT INTO privilege (categorie_id, code, libelle, ordre) VALUES (1, 'statut_changer', 'Changer le statut d''un utilisateur', 3);
INSERT INTO privilege (categorie_id, code, libelle, ordre) VALUES (1, 'utilisateur_afficher', 'Afficher les utilisateurs', 1);
INSERT INTO privilege (categorie_id, code, libelle, ordre) VALUES (1, 'utilisateur_ajouter', 'Ajouter un utilisateur', 2);
INSERT INTO privilege (categorie_id, code, libelle, ordre) VALUES (1, 'modifier_role', 'Affecter/Retirer un rôle', 4);
INSERT INTO privilege (categorie_id, code, libelle, ordre) VALUES (2, 'role_afficher', 'Afficher les rôles', 11);
INSERT INTO privilege (categorie_id, code, libelle, ordre) VALUES (2, 'role_effacer', 'Effacer un rôle', 13);
INSERT INTO privilege (categorie_id, code, libelle, ordre) VALUES (2, 'role_modifier', 'Modifier un rôle', 12);