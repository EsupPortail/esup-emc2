create table entretienprofessionnel_campagne
(
    id serial not null constraint entretienprofessionnel_campagne_pk primary key,
    annee varchar(256) not null,
    precede_id integer constraint entretienprofessionnel_campagne_entretienprofessionnel_campagne references entretienprofessionnel_campagne on delete set null,
    date_debut timestamp not null,
    date_fin timestamp not null,
    date_circulaire timestamp,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint entretienprofessionnel_campagne_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer  constraint entretienprofessionnel_campagne_user_id_fk1 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer  constraint entretienprofessionnel_campagne_user_id_fk2 references unicaen_utilisateur_user
);

create table entretienprofessionnel
(
    id serial not null constraint entretien_professionnel_pk primary key,
    agent varchar(40) not null constraint entretien_professionnel_agent_c_individu_fk references agent,
    responsable_id varchar(40) not null constraint entretien_professionnel_agent_c_individu_fk_2 references agent,
    formulaire_instance integer,
    date_entretien timestamp,
    campagne_id integer not null constraint entretien_professionnel_campagne_id_fk references entretienprofessionnel_campagne on delete set null,
    formation_instance integer,
    lieu text,
    token varchar(255),
    acceptation timestamp,
    etat_id integer constraint entretien_professionnel_unicaen_etat_etat_id_fk references unicaen_etat_etat on delete set null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint entretienprofessionnel_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer  constraint entretienprofessionnel_user_id_fk1 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer  constraint entretienprofessionnel_user_id_fk2 references unicaen_utilisateur_user
);
create unique index entretien_professionnel_id_uindex on entretienprofessionnel (id);
create unique index entretienprofessionnel_campagne_id_uindex on entretienprofessionnel_campagne (id);

create table entretienprofessionnel_observation
(
    id serial not null constraint entretienprofessionnel_observation_pk primary key,
    entretien_id integer not null constraint entretienprofessionnel_observation_entretien_professionnel_id_f references entretienprofessionnel on delete cascade,
    observation_agent_entretien text,
    observation_agent_perspective text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint entretienprofessionnel_observation_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint entretienprofessionnel_observation_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint entretienprofessionnel_observation_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index entretienprofessionnel_observation_id_uindex on entretienprofessionnel_observation (id);

create table entretienprofessionnel_sursis
(
    id serial not null constraint entretienprofessionnel_sursis_pk primary key,
    entretien_id integer not null constraint entretienprofessionnel_sursis_entretien_professionnel_id_fk references entretienprofessionnel on delete cascade,
    sursis timestamp not null,
    description text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint entretienprofessionnel_sursis_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint entretienprofessionnel_sursis_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint entretienprofessionnel_sursis_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index entretienprofessionnel_sursis_id_uindex on entretienprofessionnel_sursis (id);

create table entretienprofessionnel_delegue
(
    id serial not null constraint entretienprofessionnel_delegue_pk primary key,
    campagne_id integer not null constraint entretienprofessionnel_delegue_entretienprofessionnel_campagne_ references entretienprofessionnel_campagne on delete cascade,
    agent_id varchar(40) not null constraint entretienprofessionnel_delegue_agent_c_individu_fk references agent on delete cascade,
    structure_id integer not null constraint entretienprofessionnel_delegue_structure_id_fk references structure on delete cascade,
    description text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint entretienprofessionnel_delegue_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint entretienprofessionnel_delegue_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint entretienprofessionnel_delegue_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index entretienprofessionnel_delegue_id_uindex on entretienprofessionnel_delegue (id);

create table entretienprofessionnel_validation
(
    entretien_id integer not null constraint entretienprofessionnel_validation_entretien_professionnel_id_fk references entretienprofessionnel on delete cascade,
    validation_id integer not null constraint entretienprofessionnel_validation_unicaen_validation_instance_i references unicaen_validation_instance on delete cascade,
    constraint entretienprofessionnel_validation_pk primary key (entretien_id, validation_id)
);

