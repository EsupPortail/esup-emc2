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

create unique index formation_formation_abonnement_id_uindex
    on formation_formation_abonnement (id);

