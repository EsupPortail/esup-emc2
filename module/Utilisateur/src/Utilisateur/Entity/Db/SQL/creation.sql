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
	state boolean default true not null
);

alter table "user" owner to ad_gaethan;

create unique index user_id_uindex
	on "user" (id);

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

alter table user_role owner to ad_gaethan;

create unique index user_role_id_uindex
	on user_role (id);

-- Table d'association des rôles
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

alter table user_role_linker owner to ad_gaethan;

-------------------------------------------------------------------------------
-- PRIVILEGES - ----------------------------------------------------------------
-------------------------------------------------------------------------------

-- Table listant les catégories de privilèges
create table categorie_privilege
(
	id serial not null
		constraint categorie_privilege_pk
			primary key,
	code varchar(150) not null,
	libelle varchar(200) not null,
	ordre integer default 0
);

alter table categorie_privilege owner to ad_gaethan;

create unique index categorie_privilege_id_uindex
	on categorie_privilege (id);

-- Table listant les privilèges
create table privilege
(
	id serial not null
		constraint privilege_pk
			primary key,
	categorie_id integer not null
		constraint privilege_categorie_privilege_id_fk
			references categorie_privilege,
	code varchar(150),
	libelle varchar(200) not null,
	ordre integer default 0
);

alter table privilege owner to ad_gaethan;

create unique index privilege_id_uindex
	on privilege (id);

-- Table d'association entre les rôles et les privilèges
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

alter table role_privilege owner to ad_gaethan;


