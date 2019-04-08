-----------------------------------
-- FORMULAIRE
-----------------------------------
create table autoform_formulaire
(
	id serial not null,
	libelle varchar(128) not null,
	histo_creation timestamp not null,
	histo_createur_id integer not null
		constraint autoform_formulaire_createur_fk
			references "user",
	histo_modification timestamp not null,
	histo_modificateur_id integer not null
		constraint autoform_formulaire_modificateur_fk
			references "user",
	histo_destruction timestamp,
	histo_destructeur_id integer
		constraint autoform_formulaire_destructeur_fk
			references "user"
);

alter table autoform_formulaire owner to ad_gaethan;

create unique index autoform_formulaire_id_uindex
	on autoform_formulaire (id);


-----------------------------------
-- CATEGORIE
-----------------------------------
create table autoform_categorie
(
	id serial not null
		constraint autoform_categorie_pk
			primary key,
	code varchar(64) not null,
	libelle varchar(256) not null,
	ordre integer default 10000 not null,
	formulaire integer not null
		constraint autoform_categorie_formulaire_fk
			references autoform_formulaire (id),
	histo_creation timestamp not null,
	histo_createur_id integer not null
		constraint autoform_categorie_createur_fk
			references "user",
	histo_modification timestamp not null,
	histo_modificateur_id integer not null
		constraint autoform_categorie_modificateur_fk
			references "user",
	histo_destruction timestamp,
	histo_destructeur_id integer
		constraint autoform_categorie_destructeur_fk
			references "user"
);

alter table autoform_categorie owner to ad_gaethan;

create unique index autoform_categorie_code_uindex
	on autoform_categorie (code);

create unique index autoform_categorie_id_uindex
	on autoform_categorie (id);

-----------------------------------
-- CHAMP
-----------------------------------
create table autoform_champ
(
	id serial not null
		constraint autoform_champ_pk
			primary key,
	categorie integer not null
		constraint autoform_champ_categorie_fk
			references autoform_categorie,
	code varchar(64) not null,
	libelle varchar(256) not null,
	texte varchar(256) not null,
	ordre integer default 10000 not null,
	element varchar(64),
	balise boolean not null,
	options varchar(1024),
	histo_creation timestamp not null,
	histo_createur_id integer not null
		constraint autoform_champ_createur_fk
			references "user",
	histo_modification timestamp not null,
	histo_modificateur_id integer not null
		constraint autoform_champ_modificateur_fk
			references "user",
	histo_destruction timestamp,
	histo_destructeur_id integer
		constraint autoform_champ_destructeur_fk
			references "user"
);

alter table autoform_champ owner to ad_gaethan;

create unique index autoform_champ_id_uindex
	on autoform_champ (id);

-----------------------------------
-- REPONSE
-----------------------------------
create table autoform_formulaire_reponse
(
	id serial not null
		constraint autoform_reponse_pk
			primary key,
	demande integer not null
		constraint autoform_reponse_demande_fk
			references demande,
	champ integer not null
		constraint autoform_reponse_champ_fk
			references autoform_champ,
	reponse varchar(256),
	histo_creation timestamp not null,
	histo_createur_id integer not null
		constraint autoform_reponse_createur_fk
			references "user",
	histo_modification timestamp not null,
	histo_modificateur_id integer not null
		constraint autoform_reponse_modificateur_fk
			references "user",
	histo_destruction timestamp,
	histo_destructeur_id integer
		constraint autoform_reponse_destructeur_fk
			references "user"
);

alter table autoform_formulaire_reponse owner to ad_gaethan;

create unique index autoform_reponse_id_uindex
	on autoform_formulaire_reponse (id);

