create table unicaen_validation_type
(
	id serial not null
		constraint unicaen_validation_type_pk
			primary key,
	code varchar(256) not null,
	libelle varchar(1024) not null,
	refusable boolean default true not null,
	histo_creation timestamp not null,
	histo_createur_id integer not null
		constraint unicaen_validation_type_createur_fk
			references emc2_demo.public.unicaen_utilisateur_user,
	histo_modification timestamp not null,
	histo_modificateur_id integer not null
		constraint unicaen_validation_type_modificateur_fk
			references emc2_demo.public.unicaen_utilisateur_user,
	histo_destruction timestamp,
	histo_destructeur_id integer
		constraint unicaen_validation_type_destructeur_fk
			references emc2_demo.public.unicaen_utilisateur_user
);

create unique index unicaen_validation_type_id_uindex
	on unicaen_validation_type (id);

create table unicaen_validation_instance
(
	id serial not null
		constraint unicaen_validation_instance_pk
			primary key,
	type_id integer not null
		constraint unicaen_validation_instance_unicaen_validation_type_id_fk
			references unicaen_validation_type
				on delete cascade,
	valeur text,
	entity_class varchar(1024),
	entity_id varchar(64),
	histo_creation timestamp not null,
	histo_createur_id integer not null
		constraint unicaen_validation_instance_createur_fk
			references emc2_demo.public.unicaen_utilisateur_user,
	histo_modification timestamp not null,
	histo_modificateur_id integer not null
		constraint unicaen_validation_instance_modificateur_fk
			references emc2_demo.public.unicaen_utilisateur_user,
	histo_destruction timestamp,
	histo_destructeur_id integer
		constraint unicaen_validation_instance_destructeur_fk
			references emc2_demo.public.unicaen_utilisateur_user
);

create unique index unicaen_validation_instance_id_uindex
	on unicaen_validation_instance (id);

