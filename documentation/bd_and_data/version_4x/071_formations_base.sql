create table formation_groupe
(
    id                    serial
        constraint formation_groupe_pk
        primary key,
    libelle               varchar(1024),
    couleur               varchar(255),
    ordre                 integer default 9999,
    source_id             varchar(128),
    histo_createur_id     integer   not null
        constraint formation_groupe_createur_fk
        references unicaen_utilisateur_user,
    histo_creation        timestamp not null,
    histo_modificateur_id integer
        constraint formation_groupe_modificateur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_destructeur_id  integer
        constraint formation_groupe_destructeur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    description           text,
    id_source             varchar(256)
);

create unique index formation_groupe_id_uindex
    on formation_groupe (id);

create table formation
(
    id                    serial
        constraint formation_pk
        primary key,
    libelle               varchar(256)         not null,
    description           text,
    lien                  varchar(1024),
    groupe_id             integer
        constraint formation_formation_groupe_id_fk
        references formation_groupe
        on delete set null,
    source_id             varchar(128),
    histo_createur_id     integer              not null
        constraint formation_createur_fk
        references unicaen_utilisateur_user,
    histo_creation        timestamp            not null,
    histo_modificateur_id integer
        constraint formation_modificateur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_destructeur_id  integer
        constraint formation_destructeur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    affichage             boolean default true not null,
    rattachement          varchar(1024),
    objectifs             text,
    programme             text,
    type                  varchar(64),
    id_source             varchar(256)
);

create unique index formation_id_uindex
    on formation (id);

create table formation_element
(
    id                    serial
        constraint formation_element_pk
        primary key,
    formation_id          integer   not null
        constraint formation_element_formation_informations_id_fk
        references formation
        on delete cascade,
    commentaire           text,
    validation_id         integer
        constraint formation_element_unicaen_validation_instance_id_fk
        references unicaen_validation_instance
        on delete set null,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint formation_element_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_element_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_element_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user
);

create unique index formation_element_id_uindex
    on formation_element (id);

create table formation_obtenue_application
(
    formation_id           integer not null
        constraint formation_application_obtenue_formation_id_fk
        references formation
        on delete cascade,
    application_element_id integer not null
        constraint formation_application_obtenue_application_element_id_fk
        references element_application_element
        on delete cascade,
    constraint formation_application_obtenue_pk
        primary key (formation_id, application_element_id)
);

create table formation_obtenue_competence
(
    formation_id          integer not null
        constraint formation_obtenue_competence_formation_id_fk
        references formation
        on delete cascade,
    competence_element_id integer not null
        constraint formation_obtenue_competence_competence_element_id_fk
        references element_competence_element
        on delete cascade,
    constraint formation_obtenue_competence_pk
        primary key (formation_id, competence_element_id)
);

create table formation_parcours
(
    id                    serial
        constraint formation_parcours_pk
        primary key,
    type                  varchar(255),
    reference_id          integer,
    libelle               varchar(1024),
    description           text,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null,
    histo_modification    timestamp,
    histo_modificateur_id integer,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
);

create unique index formation_parcours_id_uindex
    on formation_parcours (id);

create table formation_parcours_formation
(
    id                    serial
        constraint formation_parcours_formation_pk
        primary key,
    parcours_id           integer   not null
        constraint formation_parcours_formation_formation_parcours_id_fk
        references formation_parcours
        on delete cascade,
    formation_id          integer
        constraint formation_parcours_formation_formation_id_fk
        references formation
        on delete cascade,
    ordre                 integer,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint formation_parcours_formation_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_parcours_formation_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_parcours_formation_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user
);

create unique index formation_parcours_formation_id_uindex
    on formation_parcours_formation (id);

create table formation_formation_abonnement
(
    id                    serial
        constraint formation_formation_abonnement_pk
        primary key,
    formation_id          integer     not null
        constraint formation_formation_abonnement_formation_id_fk
        references formation
        on delete cascade,
    agent_id              varchar(40) not null
        constraint formation_formation_abonnement_agent_c_individu_fk
        references agent
        on delete cascade,
    date_inscription      timestamp   not null,
    description           text,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint formation_formation_abonnement_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_formation_abonnement_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_formation_abonnement_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user
);

create table formation_demande_externe
(
    id                        serial
        constraint formation_demande_externe_pk
        primary key,
    libelle                   varchar(1024)           not null,
    organisme                 varchar(1024)           not null,
    contact                   varchar(1024)           not null,
    pourquoi                  text,
    montant                   text,
    lieu                      varchar(1024)           not null,
    debut                     date                    not null,
    fin                       date                    not null,
    justification_agent       text                    not null,
    prise_en_charge           boolean   default true  not null,
    cofinanceur               varchar(1024),
    histo_creation            timestamp default now() not null,
    histo_createur_id         integer   default 0     not null
        constraint formation_demande_externe_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification        timestamp,
    histo_modificateur_id     integer
        constraint formation_demande_externe_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction         timestamp,
    histo_destructeur_id      integer
        constraint formation_demande_externe_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user,
    agent_id                  varchar(40)             not null
        constraint formation_demande_externe_agent_c_individu_fk
        references agent,
    justification_responsable text,
    justification_refus       text,
    modalite                  varchar(1024)
);

create unique index formation_demande_externe_id_uindex
    on formation_demande_externe (id);

create table formation_demande_externe_validation
(
    demande_id    integer not null
        constraint formation_demande_externe_validation_id1_fk
        references formation_demande_externe
        on delete cascade,
    validation_id integer not null
        constraint formation_demande_externe_validation_id2_fk
        references unicaen_validation_instance
        on delete cascade,
    constraint formation_demande_externe_validation_pk
        primary key (demande_id, validation_id)
);

create table formation_demande_externe_fichier
(
    demande_id integer     not null
        constraint formation_demande_externe_ficher_formation_demande_externe_id_f
        references formation_demande_externe
        on delete cascade,
    fichier_id varchar(13) not null
        constraint formation_demande_externe_ficher_fichier_fichier_id_fk
        references fichier_fichier
        on delete cascade,
    constraint formation_demande_externe_ficher_pk
        primary key (demande_id, fichier_id)
);

create table formation_enquete_categorie
(
    id                    serial
        primary key,
    libelle               varchar(1024) not null,
    description           text,
    ordre                 integer       not null,
    histo_createur_id     integer       not null
        constraint formation_enquete_categorie_utilisateur_id_fk_1
        references unicaen_utilisateur_user,
    histo_creation        timestamp     not null,
    histo_modificateur_id integer
        constraint formation_enquete_categorie_utilisateur_id_fk_2
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_destructeur_id  integer
        constraint formation_enquete_categorie_utilisateur_id_fk_3
        references unicaen_utilisateur_user,
    histo_destruction     timestamp
);

create unique index formation_enquete_categorie_id_uindex
    on formation_enquete_categorie (id);

create table formation_enquete_question
(
    id                    serial
        primary key,
    libelle               varchar(1024) not null,
    description           text,
    ordre                 integer       not null,
    histo_createur_id     integer       not null
        constraint formation_enquete_question_utilisateur_id_fk_1
        references unicaen_utilisateur_user,
    histo_creation        timestamp     not null,
    histo_modificateur_id integer
        constraint formation_enquete_question_utilisateur_id_fk_2
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_destructeur_id  integer
        constraint formation_enquete_question_utilisateur_id_fk_3
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    categorie_id          integer
        constraint formation_enquete_question_formation_enquete_categorie_id_fk
        references formation_enquete_categorie
);

create unique index formation_enquete_question_id_uindex
    on formation_enquete_question (id);

create table formation_session_parametre
(
    id                    serial
        constraint formation_session_parametre_pk
        primary key,
    mail                  boolean default true not null,
    evenement             boolean default true not null,
    enquete               boolean default true not null,
    histo_creation        timestamp            not null,
    histo_createur_id     integer              not null
        constraint formation_session_parametre_unicaen_utilisateur_user_null_fk_1
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_session_parametre_unicaen_utilisateur_user_null_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_session_parametre_unicaen_utilisateur_user_null_fk_3
        references unicaen_utilisateur_user
);

comment on table formation_session_parametre is 'Table permettant de parametre le comportement d''une session';

create table formation_instance
(
    id                      serial
        constraint formation_instance_pk
        primary key,
    formation_id            integer               not null
        constraint formation_instance_formation_id_fk
        references formation
        on delete cascade,
    nb_place_principale     integer default 0     not null,
    nb_place_complementaire integer default 0     not null,
    complement              text,
    lieu                    varchar(256),
    type                    varchar(256),
    auto_inscription        boolean default false not null,
    source_id               varchar(128),
    id_source               varchar(256),
    histo_creation          timestamp             not null,
    histo_createur_id       integer               not null
        constraint formation_instance_user_id_fk_1
        references unicaen_utilisateur_user,
    histo_modification      timestamp,
    histo_modificateur_id   integer
        constraint formation_instance_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction       timestamp,
    histo_destructeur_id    integer
        constraint formation_instance_user_id_fk_3
        references unicaen_utilisateur_user,
    cout_ht                 double precision,
    cout_ttc                double precision,
    affichage               boolean default true  not null,
    parametre_id            integer
        constraint formation_instance_formation_session_parametre_null_fk
        references formation_session_parametre
        on delete set null
);

create unique index formation_instance_id_uindex
    on formation_instance (id);

create table formation_instance_inscrit
(
    id                        serial
        constraint formation_instance_inscrit_pk
        primary key,
    instance_id               integer     not null
        constraint formation_instance_inscrit_formation_instance_id_fk
        references formation_instance
        on delete cascade,
    agent_id                  varchar(40) not null
        constraint formation_instance_inscrit_agent_c_individu_fk
        references agent
        on delete cascade,
    liste                     varchar(64),
    source_id                 varchar(128),
    id_source                 varchar(100),
    histo_creation            timestamp   not null,
    histo_createur_id         integer     not null
        constraint formation_instance_inscrit_unicaen_utilisateur_user_id_fk_1
        references unicaen_utilisateur_user,
    histo_modification        timestamp,
    histo_modificateur_id     integer
        constraint formation_instance_inscrit_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction         timestamp,
    histo_destructeur_id      integer
        constraint formation_instance_inscrit_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user,
    justification_agent       text,
    justification_responsable text,
    justification_refus       text,
    validation_enquete        timestamp
);

create unique index formation_instance_inscrit_id_uindex
    on formation_instance_inscrit (id);

create table formation_instance_frais
(
    id                    serial
        constraint formation_instance_frais_pk
        primary key,
    inscrit_id            integer   not null
        constraint formation_instance_frais_formation_instance_inscrit_id_fk
        references formation_instance_inscrit
        on delete cascade,
    frais_repas           double precision default 0,
    frais_hebergement     double precision default 0,
    frais_transport       double precision default 0,
    histo_creation        timestamp not null,
    source_id             varchar(128),
    id_source             varchar(64),
    histo_createur_id     integer   not null
        constraint formation_instance_frais_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_instance_frais_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_instance_frais_user_id_fk_3
        references unicaen_utilisateur_user
);

create unique index formation_instance_frais_id_uindex
    on formation_instance_frais (id);

create table formation_seance
(
    id                    serial not null
        constraint formation_instance_journee_pk
        primary key,
    instance_id           integer                                                           not null
        constraint formation_instance_journee_formation_instance_id_fk
        references formation_instance
        on delete cascade,
    jour                  timestamp,
    debut                 varchar(64),
    fin                   varchar(64),
    lieu                  varchar(1024)                                                     not null,
    remarque              text,
    source_id             varchar(128),
    histo_creation        timestamp                                                         not null,
    histo_createur_id     integer                                                           not null
        constraint formation_instance_journee_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_instance_journee_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_instance_journee_user_id_fk_3
        references unicaen_utilisateur_user,
    type                  varchar(255) default 'SEANCE'::character varying                  not null,
    volume                double precision,
    volume_debut          timestamp,
    volume_fin            timestamp,
    id_source             varchar(256)
);

create unique index formation_instance_journee_id_uindex
    on formation_seance (id);

create table formation_formateur
(
    id  serial not null
        constraint formation_instance_formateur_pk
        primary key,
    instance_id           integer                                                         not null
        constraint formation_instance_formateur_formation_instance_id_fk
        references formation_instance
        on delete cascade,
    prenom                varchar(256),
    nom                   varchar(256),
    email                 varchar(1024),
    attachement           varchar(1024),
    histo_creation        timestamp                                                       not null,
    histo_createur_id     integer                                                         not null
        constraint formation_instance_formateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_instance_formateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_instance_formateur_user_id_fk_3
        references unicaen_utilisateur_user,
    organisme             varchar(1024),
    telephone             varchar(64),
    type                  varchar(64)
);

create unique index formation_instance_formateur_id_uindex
    on formation_formateur (id);

create table formation_presence
(
    id   serial not null
        constraint formation_instance_presence_pk
        primary key,
    journee_id            integer                                                             not null
        constraint formation_instance_presence_formation_instance_journee_id_fk
        references formation_seance
        on delete cascade,
    inscrit_id            integer                                                             not null
        constraint formation_instance_presence_formation_instance_inscrit_id_fk
        references formation_instance_inscrit
        on delete cascade,
    presence_type         varchar(256)                                                        not null,
    commentaire           text,
    histo_creation        timestamp                                                           not null,
    histo_createur_id     integer                                                             not null
        constraint formation_instance_presence_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_instance_presence_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_instance_presence_user_id_fk_3
        references unicaen_utilisateur_user,
    source_id             varchar(128),
    id_source             varchar(256),
    statut                varchar(256) default 'NON RENSEIGNEE'::character varying            not null
);

create unique index formation_instance_presence_id_uindex
    on formation_presence (id);

create table formation_enquete_reponse
(
    id                    serial
        primary key,
    inscription_id        integer   not null
        constraint formation_enquete_reponse_inscription_id_fk
        references formation_instance_inscrit
        on delete cascade,
    question_id           integer   not null
        constraint formation_enquete_reponse_question_id_fk
        references formation_enquete_question
        on delete cascade,
    niveau                integer   not null,
    description           text,
    histo_createur_id     integer   not null
        constraint formation_enquete_reponse_utilisateur_id_fk_1
        references unicaen_utilisateur_user,
    histo_creation        timestamp not null,
    histo_modificateur_id integer
        constraint formation_enquete_reponse_utilisateur_id_fk_2
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_destructeur_id  integer
        constraint formation_enquete_reponse_utilisateur_id_fk_3
        references unicaen_utilisateur_user,
    histo_destruction     timestamp
);

create unique index formation_enquete_reponse_id_uindex
    on formation_enquete_reponse (id);

create table formation_plan_formation
(
    id    serial
        constraint formation_plan_formation_pk
        primary key,
    annee varchar(128) not null
);

create table formation_action_plan
(
    action_id integer not null
        constraint formation_action_plan_formation_id_fk
        references formation
        on delete cascade,
    plan_id   integer not null
        constraint formation_action_plan_formation_plan_formation_id_fk
        references formation_plan_formation
        on delete cascade,
    constraint formation_action_plan_pk
        primary key (action_id, plan_id)
);

create table formation_demande_externe_etat
(
    demande_id integer not null
        constraint formation_demande_externe_etat_formation_demande_externe_id_fk
        references formation_demande_externe
        on delete cascade,
    etat_id    integer not null
        constraint formation_demande_externe_etat_unicaen_etat_instance_id_fk
        references unicaen_etat_instance
        on delete cascade,
    constraint formation_demande_externe_etat_pk
        primary key (demande_id, etat_id)
);

create table formation_session_etat
(
    session_id integer not null
        constraint formation_instance_etat_session_id_fk
        references formation_instance
        on delete cascade,
    etat_id    integer not null
        constraint formation_instance_etat_etat_id_fk
        references unicaen_etat_instance
        on delete cascade,
    constraint formation_session_etat_pk
        primary key (session_id, etat_id)
);

create table formation_inscription_etat
(
    inscription_id integer not null
        constraint formation_inscription_etat_inscription_id_fk
        references formation_instance_inscrit
        on delete cascade,
    etat_id        integer not null
        constraint formation_inscription_etat_etat_id_fk
        references unicaen_etat_instance
        on delete cascade,
    constraint formation_inscription_etat_pk
        primary key (inscription_id, etat_id)
);

