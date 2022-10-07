create table fichemetier
(
    id                    serial
        constraint fiche_type_metier_pkey
        primary key,
    metier_id             integer   not null
        constraint fichetype_metier__fk
        references metier_metier,
    expertise             boolean default false,
    etat_id               integer default 0
        constraint fichemetier_unicaen_etat_etat_id_fk
        references unicaen_etat_etat
        on delete set null,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint fichemetier_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp not null,
    histo_modificateur_id integer   not null,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
);


create table fichemetier_application
(
    fichemetier_id         integer not null
        constraint fichemetier_application_fichemetier_id_fk
        references fichemetier
        on delete cascade,
    application_element_id integer not null
        constraint fichemetier_application_application_element_id_fk
        references element_application_element
        on delete cascade,
    constraint fichemetier_application_pk
        primary key (fichemetier_id, application_element_id)
);


create table fichemetier_competence
(
    fichemetier_id        integer not null
        constraint fichemetier_competence_fichemetier_id_fk
        references fichemetier
        on delete cascade,
    competence_element_id integer not null
        constraint fichemetier_competence_competence_element_id_fk
        references element_competence_element
        on delete cascade,
    constraint fichemetier_competence_pk
        primary key (fichemetier_id, competence_element_id)
);

create table fichemetier_activite
(
    id          serial
        primary key,
    fiche_id    integer           not null
        constraint fichemetier_activite_fichemetier_id_fk
        references fichemetier
        on delete cascade,
    activite_id integer           not null
        constraint fichemetier_activite_activite_id_fk
        references activite
        on delete cascade,
    position    integer default 0 not null
);

create unique index fichemetier_activite_id_uindex
    on fichemetier_activite (id);

create table fichemetier_formation
(
    fiche_metier_id integer not null
        constraint fiche_metier_formation_fiche_metier_id_fk
        references fichemetier
        on delete cascade,
    formation_id    integer not null
       constraint fiche_metier_formation_formation_id_fk
       references formation
       on delete cascade
,
    constraint fiche_metier_formation_pk
        primary key (fiche_metier_id, formation_id)
);


create table fichemetier_fichetype
(
    id       serial
        primary key,
    fiche_id integer           not null
        constraint fichemetier_fichetype_fiche_type_metier_id_fk
        references fichemetier
        on delete cascade,
    activite integer           not null
        constraint fichemetier_fichetype_activite_id_fk
        references activite
        on delete cascade,
    position integer default 0 not null
);

create unique index fichemetier_fichetype_id_uindex
    on fichemetier_fichetype (id);

