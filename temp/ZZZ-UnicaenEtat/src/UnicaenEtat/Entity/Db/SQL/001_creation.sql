create table unicaen_etat_etat_type
(
    id serial not null
        constraint unicaen_etat_etat_type_pk
        primary key,
    code varchar(256) not null,
    libelle varchar(256) not null,
    icone varchar(256),
    couleur varchar(256),
    ordre integer DEFAULT 9999 not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null
        constraint unicaen_content_content_user_id_fk
        references emc2_demo.public.unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null
        constraint unicaen_content_content_user_id_fk_2
        references emc2_demo.public.unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer
        constraint unicaen_content_content_user_id_fk_3
        references emc2_demo.public.unicaen_utilisateur_user
);

alter table unicaen_etat_etat_type owner to ad_jp_test;

create table unicaen_etat_etat
(
    id serial not null
        constraint unicaen_etat_etat_pk
        primary key,
    code varchar(256) not null,
    libelle varchar(256) not null,
    type_id integer not null
        constraint unicaen_etat_etat_unicaen_etat_etat_type_id_fk
        references unicaen_etat_etat_type,
    icone varchar(256),
    couleur varchar(256),
    histo_creation timestamp not null,
    histo_createur_id integer not null
        constraint unicaen_content_content_user_id_fk
        references emc2_demo.public.unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null
        constraint unicaen_content_content_user_id_fk_2
        references emc2_demo.public.unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer
        constraint unicaen_content_content_user_id_fk_3
        references emc2_demo.public.unicaen_utilisateur_user
);

alter table unicaen_etat_etat owner to ad_jp_test;

create unique index unicaen_etat_etat_id_uindex
    on unicaen_etat_etat (id);

create table unicaen_etat_action_type
(
    id serial not null
        constraint unicaen_etat_action_type_pk
        primary key,
    code varchar(256) not null,
    libelle varchar(256) not null,
    icone varchar(256),
    couleur varchar(256),
    histo_creation timestamp not null,
    histo_createur_id integer not null
        constraint unicaen_content_content_user_id_fk
        references emc2_demo.public.unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null
        constraint unicaen_content_content_user_id_fk_2
        references emc2_demo.public.unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer
        constraint unicaen_content_content_user_id_fk_3
        references emc2_demo.public.unicaen_utilisateur_user
);

alter table unicaen_etat_action_type owner to ad_jp_test;

create unique index unicaen_etat_action_type_id_uindex
    on unicaen_etat_action_type (id);

create unique index unicaen_etat_etat_type_id_uindex
    on unicaen_etat_action_type (id);

create table unicaen_etat_action
(
    id serial not null
        constraint unicaen_etat_action_pk
        primary key,
    code varchar(256) not null,
    libelle varchar(256),
    type_id integer not null
        constraint unicaen_etat_action_unicaen_etat_action_type_id_fk
        references unicaen_etat_action_type,
    etat_id integer not null
        constraint unicaen_etat_action_unicaen_etat_etat_id_fk
        references unicaen_etat_etat,
    position integer not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null
        constraint unicaen_content_content_user_id_fk
        references emc2_demo.public.unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null
        constraint unicaen_content_content_user_id_fk_2
        references emc2_demo.public.unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer
        constraint unicaen_content_content_user_id_fk_3
        references emc2_demo.public.unicaen_utilisateur_user
);

alter table unicaen_etat_action owner to ad_jp_test;

create unique index unicaen_etat_action_id_uindex
    on unicaen_etat_action (id);

