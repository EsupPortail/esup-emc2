-- Date de MAJ 24/11/2023 ----------------------------------------------------------------------------------------------
-- Script avant version 4.2.0 ------------------------------------------------------------------------------------------
-- Color scheme : 284848  ----------------------------------------------------------------------------------------------

-- TTTTTTTTTTTTTTTTTTTTTTT         AAA               BBBBBBBBBBBBBBBBB   LLLLLLLLLLL             EEEEEEEEEEEEEEEEEEEEEE
-- T:::::::::::::::::::::T        A:::A              B::::::::::::::::B  L:::::::::L             E::::::::::::::::::::E
-- T:::::::::::::::::::::T       A:::::A             B::::::BBBBBB:::::B L:::::::::L             E::::::::::::::::::::E
-- T:::::TT:::::::TT:::::T      A:::::::A            BB:::::B     B:::::BLL:::::::LL             EE::::::EEEEEEEEE::::E
-- TTTTTT  T:::::T  TTTTTT     A:::::::::A             B::::B     B:::::B  L:::::L                 E:::::E       EEEEEE
--         T:::::T            A:::::A:::::A            B::::B     B:::::B  L:::::L                 E:::::E
--         T:::::T           A:::::A A:::::A           B::::BBBBBB:::::B   L:::::L                 E::::::EEEEEEEEEE
--         T:::::T          A:::::A   A:::::A          B:::::::::::::BB    L:::::L                 E:::::::::::::::E
--         T:::::T         A:::::A     A:::::A         B::::BBBBBB:::::B   L:::::L                 E:::::::::::::::E
--         T:::::T        A:::::AAAAAAAAA:::::A        B::::B     B:::::B  L:::::L                 E::::::EEEEEEEEEE
--         T:::::T       A:::::::::::::::::::::A       B::::B     B:::::B  L:::::L                 E:::::E
--         T:::::T      A:::::AAAAAAAAAAAAA:::::A      B::::B     B:::::B  L:::::L         LLLLLL  E:::::E       EEEEEE
--       TT:::::::TT   A:::::A             A:::::A   BB:::::BBBBBB::::::BLL:::::::LLLLLLLLL:::::LEE::::::EEEEEEEE:::::E
--       T:::::::::T  A:::::A               A:::::A  B:::::::::::::::::B L::::::::::::::::::::::LE::::::::::::::::::::E
--       T:::::::::T A:::::A                 A:::::A B::::::::::::::::B  L::::::::::::::::::::::LE::::::::::::::::::::E
--       TTTTTTTTTTTAAAAAAA                   AAAAAAABBBBBBBBBBBBBBBBB   LLLLLLLLLLLLLLLLLLLLLLLLEEEEEEEEEEEEEEEEEEEEEE


create table entretienprofessionnel_campagne
(
    id                    serial
        constraint entretienprofessionnel_campagne_pk
        primary key,
    annee                 varchar(256) not null,
    precede_id            integer
        constraint entretienprofessionnel_campagne_entretienprofessionnel_campagne
        references entretienprofessionnel_campagne
        on delete set null,
    date_debut            timestamp    not null,
    date_fin              timestamp    not null,
    date_circulaire       timestamp,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint entretienprofessionnel_campagne_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint entretienprofessionnel_campagne_user_id_fk1
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint entretienprofessionnel_campagne_user_id_fk2
        references unicaen_utilisateur_user
);
create unique index entretienprofessionnel_campagne_id_uindex on entretienprofessionnel_campagne (id);

create table entretienprofessionnel
(
    id                    serial
        constraint entretien_professionnel_pk
        primary key,
    agent                 varchar(40) not null
        constraint entretien_professionnel_agent_c_individu_fk
        references agent,
    responsable_id        varchar(40) not null
        constraint entretien_professionnel_agent_c_individu_fk_2
        references agent,
    formulaire_instance   integer,
    date_entretien        timestamp,
    campagne_id           integer     not null
        constraint entretien_professionnel_campagne_id_fk
        references entretienprofessionnel_campagne
        on delete set null,
    formation_instance    integer,
    lieu                  text,
    token                 varchar(255),
    acceptation           timestamp,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint entretienprofessionnel_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint entretienprofessionnel_user_id_fk1
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint entretienprofessionnel_user_id_fk2
        references unicaen_utilisateur_user
);
create unique index entretien_professionnel_id_uindex on entretienprofessionnel (id);

create table entretienprofessionnel_observation
(
    id                            serial
        constraint entretienprofessionnel_observation_pk
        primary key,
    entretien_id                  integer   not null
        constraint entretienprofessionnel_observation_entretien_professionnel_id_f
        references entretienprofessionnel
        on delete cascade,
    observation_agent_entretien   text,
    observation_agent_perspective text,
    histo_creation                timestamp not null,
    histo_createur_id             integer   not null
        constraint entretienprofessionnel_observation_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification            timestamp,
    histo_modificateur_id         integer
        constraint entretienprofessionnel_observation_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction             timestamp,
    histo_destructeur_id          integer
        constraint entretienprofessionnel_observation_user_id_fk_3
        references unicaen_utilisateur_user
);
create unique index entretienprofessionnel_observation_id_uindex on entretienprofessionnel_observation (id);

create table entretienprofessionnel_validation
(
    entretien_id  integer not null
        constraint entretienprofessionnel_validation_entretien_professionnel_id_fk
        references entretienprofessionnel
        on delete cascade,
    validation_id integer not null
        constraint entretienprofessionnel_validation_unicaen_validation_instance_i
        references unicaen_validation_instance
        on delete cascade,
    constraint entretienprofessionnel_validation_pk
        primary key (entretien_id, validation_id)
);

create table entretienprofessionnel_critere_competence
(
    id                    serial
        constraint entretienprofessionnel_critere_competence_pk
        primary key,
    libelle               varchar(1024)           not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null,
    histo_modification    timestamp,
    histo_modificateur_id integer,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
);
create unique index entretienprofessionnel_critere_competence_id_uindex on entretienprofessionnel_critere_competence (id);

create table entretienprofessionnel_critere_contribution
(
    id                    serial
        constraint entretienprofessionnel_critere_contribution_pk
        primary key,
    libelle               varchar(1024)           not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null,
    histo_modification    timestamp,
    histo_modificateur_id integer,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
);
create unique index entretienprofessionnel_critere_contribution_id_uindex on entretienprofessionnel_critere_contribution (id);

create table entretienprofessionnel_critere_personnelle
(
    id                    serial
        constraint entretienprofessionnel_critere_qualitepersonnelle_pk
        primary key,
    libelle               varchar(1024)           not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null,
    histo_modification    timestamp,
    histo_modificateur_id integer,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
);
create unique index entretienprofessionnel_critere_qualitepersonnelle_id_uindex on entretienprofessionnel_critere_personnelle (id);

create table entretienprofessionnel_critere_encadrement
(
    id                    serial
        constraint entretienprofessionnel_critere_encadrement_pk
        primary key,
    libelle               varchar(1024)           not null,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null,
    histo_modification    timestamp,
    histo_modificateur_id integer,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
);
create unique index entretienprofessionnel_critere_encadrement_id_uindex on entretienprofessionnel_critere_encadrement (id);

create table entretienprofessionnel_etat
(
    entretien_id integer not null
        constraint entretienprofessionnel_etat_entretien_id_fk
        references entretienprofessionnel
        on delete cascade,
    etat_id      integer not null
        constraint entretienprofessionnel_etat_etat_id_fk
        references unicaen_etat_instance
        on delete cascade,
    constraint entretienprofessionnel_etat_pk
        primary key (entretien_id, etat_id)
);

create table entretienprofessionnel_agent_force_sansobligation
(
    id                    serial
        constraint entretienprofessionnel_agent_force_sansobligation_pk
        primary key,
    agent_id              varchar(40) not null
        constraint ep_agent_force_sansobligation_agent_c_individu_fk
        references agent
        on delete cascade,
    campagne_id           integer     not null
        constraint ep_agent_force_sansobligation_campagne_id_fk
        references entretienprofessionnel_campagne
        on delete cascade,
    raison                text,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint ep_agent_force_sansobligation_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint ep_agent_force_sansobligation_unicaen_utilisateur_user_id_fk2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint ep_agent_force_sansobligation_unicaen_utilisateur_user_id_fk3
        references unicaen_utilisateur_user
);
comment on table entretienprofessionnel_agent_force_sansobligation is 'Table listant les agents pour lesquels on a forcé le fait qu''ils n''avait pas d''obligation d''entretien professionnel';

