-------------------------------------------------------------------------------
-- ROLE -----------------------------------------------------------------------
-------------------------------------------------------------------------------

-- Table gérant la liste des rôles
create table user_role
(
    id serial not null
        constraint user_role_pk
            primary key,
    libelle varchar(200) not null,
    role_id varchar(64) not null,
    is_default boolean default false not null,
    ldap_filter varchar(35),
    parent_id integer
);

create unique index user_role_id_uindex
    on user_role (id);

-------------------------------------------------------------------------------
-- UTILISATEUR ----------------------------------------------------------------
-------------------------------------------------------------------------------

-- Table gérant la liste des utilisateurs

create table "user"
(
    id serial not null
        constraint user_pk
            primary key,
    username varchar(255) not null,
    display_name varchar(255) not null,
    email varchar(255),
    password varchar(128) not null,
    state boolean default true not null,
    last_role_id integer
        constraint user_user_role_id_fk
            references user_role
            on delete set null
);

create unique index user_id_uindex
    on "user" (id);


-------------------------------------------------------------------------------
-- LINKER ---------------------------------------------------------------------
-------------------------------------------------------------------------------

create table user_role_linker
(
	user_id integer not null
		constraint user_role_linker_user_id_fk
			references "user",
	role_id integer not null
		constraint user_role_linker_role__fk
			references user_role,
	constraint user_role_linker_pk
		primary key (user_id, role_id)
);

-------------------------------------------------------------------------------
-- INSERTION ------------------------------------------------------------------
-------------------------------------------------------------------------------

INSERT INTO user_role (libelle, role_id) VALUES ('Administrateur', 'Administrateur');

INSERT INTO "user" (username, display_name, email, password) VALUES ('Annuaire UNICAN', 'Annuaire UNICAEN', NULL, 'none');
INSERT INTO "user" (username, display_name, email, password) VALUES ('metivier', 'Jean-Philippe METIVIER', 'jean-philippe.metivier@unicaen.fr', 'ldap');

INSERT INTO user_role_linker (user_id, role_id) VALUES (2,1);