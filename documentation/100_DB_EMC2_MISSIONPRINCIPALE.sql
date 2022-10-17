create table activite
(
    id                    serial
        primary key,
    histo_creation        timestamp not null,
    histo_modification    timestamp,
    histo_destruction     timestamp,
    histo_createur_id     integer   not null
        constraint activite_user_id_fk
        references unicaen_utilisateur_user,
    histo_modificateur_id integer
        constraint activite_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destructeur_id  integer
        constraint activite_user_id_fk_3
        references unicaen_utilisateur_user,
    niveaux_id            integer
        constraint activite_niveau_enveloppe_id_fk
        references carriere_niveau_enveloppe
        on delete set null
);


create unique index activite_id_uindex
    on activite (id);

create table activite_libelle
(
    id                    serial
        constraint activite_libelle_pk
        primary key,
    activite_id           integer   not null,
    libelle               text      not null,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint activite_description_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint activite_description_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint activite_description_user_id_fk
        references unicaen_utilisateur_user
);

create table activite_description
(
    id                    serial
        constraint activite_description_pk
        primary key,
    activite_id           integer   not null
        constraint activite_description_activite_fk
        references activite
        on delete cascade,
    description           text      not null,
    ordre                 integer,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint activite_description_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint activite_description_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint activite_description_user_id_fk
        references unicaen_utilisateur_user
);


create unique index activite_description_id_uindex
    on activite_description (id);

create table activite_application
(
    activite_id            integer not null
        constraint activite_application_activite_id_fk
        references activite
        on delete cascade,
    application_element_id integer not null
        constraint activite_application_application_element_id_fk
        references element_application_element
        on delete cascade,
    constraint activite_application_pk
        primary key (activite_id, application_element_id)
);


create table activite_competence
(
    activite_id           integer not null
        constraint activite_competence_activite_id_fk
        references activite
        on delete cascade,
    competence_element_id integer not null
        constraint activite_competence_competence_element_id_fk
        references element_competence_element
        on delete cascade,
    constraint activite_competence_pk
        primary key (activite_id, competence_element_id)
);


create table activite_formation
(
    activite_id          integer not null
        constraint activite_formation_activite_id_fk
        references activite
        on delete cascade,
    formation_element_id integer not null
        constraint activite_formation_formation_element_id_fk
        references formation_element
        on delete cascade,
    constraint activite_formation_pk
        primary key (activite_id, formation_element_id)
);


create table activite_domaine
(
    activite_id integer not null
        constraint activite_domaine_activite_id_fk
        references activite
        on delete cascade,
    domaine_id  integer not null
        constraint activite_domaine_domaine_id_fk
        references metier_domaine
        on delete cascade,
    constraint activite_domaine_pk
        primary key (activite_id, domaine_id)
);


