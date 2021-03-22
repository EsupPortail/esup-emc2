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


