
create table complement
(
    id serial not null constraint complement_pk primary key,
    attachment_type varchar(1024) not null,
    attachment_id varchar(40) not null,
    complement_type varchar(1024),
    complement_id varchar(40),
    complement_text text,
    type varchar(1024) not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint complement_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint complement_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint complement_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index complement_id_uindex on complement (id);
