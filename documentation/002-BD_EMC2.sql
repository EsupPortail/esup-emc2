create table agent
(
    c_individu varchar(40) not null constraint agent_pk primary key,
    utilisateur_id integer constraint agent_user_id_fk references unicaen_utilisateur_user on delete set null,
    prenom varchar(64),
    nom_usage varchar(64),
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0),
    octo_id varchar(40),
    preecog_id varchar(40),
    harp_id integer,
    login varchar(256),
    email varchar(1024),
    sexe varchar(1),
    t_contrat_long varchar(1),
    date_naissance date,
    nom_famille varchar(256)
);

