-- TABLE DES MACROS

create table unicaen_document_macro
(
	id serial not null
		constraint unicaen_document_macro_pk
			primary key,
	code varchar(256) not null,
	description text,
	variable_name varchar(256) not null,
	methode_name varchar(256) not null,
	histo_creation timestamp not null,
	histo_createur_id integer not null
		constraint unicaen_document_macro_user_id_fk
			references "user",
	histo_modification timestamp not null,
	histo_modificateur_id integer not null
		constraint unicaen_document_macro_user_id_fk_2
			references "user",
	histo_destruction timestamp,
	histo_destructeur_id integer
		constraint unicaen_document_macro_user_id_fk_3
			references "user"
);

create unique index unicaen_document_macro_id_uindex
	on unicaen_document_macro (id);

create unique index unicaen_document_macro_code_uindex
	on unicaen_document_macro (code);

-- TABLE DES CONTENUS

create table unicaen_document_content
(
	id serial not null
		constraint unicaen_content_content_pk
			primary key,
	code varchar(256) not null,
	description text,
	document_type varchar(256) not null,
	document_complement text not null,
	document_content text not null,
	histo_creation timestamp not null,
	histo_createur_id integer not null
		constraint unicaen_content_content_user_id_fk
			references "user",
	histo_modification timestamp not null,
	histo_modificateur_id integer not null
		constraint unicaen_content_content_user_id_fk_2
			references "user",
	histo_destruction timestamp,
	histo_destructeur_id integer
		constraint unicaen_content_content_user_id_fk_3
			references "user"
);

create unique index unicaen_content_content_id_uindex
	on unicaen_document_content (id);

create unique index unicaen_content_content_code_uindex
	on unicaen_document_content (code);



