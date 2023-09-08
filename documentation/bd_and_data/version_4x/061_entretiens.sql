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


create unique index entretienprofessionnel_campagne_id_uindex
    on entretienprofessionnel_campagne (id);

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

create unique index entretien_professionnel_id_uindex
    on entretienprofessionnel (id);

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

create unique index entretienprofessionnel_observation_id_uindex
    on entretienprofessionnel_observation (id);

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

create unique index entretienprofessionnel_critere_competence_id_uindex
    on entretienprofessionnel_critere_competence (id);

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

create unique index entretienprofessionnel_critere_contribution_id_uindex
    on entretienprofessionnel_critere_contribution (id);

create table entretienprofessionnel_critere_personnelle
(
    id  serial
        constraint entretienprofessionnel_critere_qualitepersonnelle_pk
        primary key,
    libelle               varchar(1024)                                                                                   not null,
    histo_creation        timestamp default now()                                                                         not null,
    histo_createur_id     integer   default 0                                                                             not null,
    histo_modification    timestamp,
    histo_modificateur_id integer,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
);

create unique index entretienprofessionnel_critere_qualitepersonnelle_id_uindex
    on entretienprofessionnel_critere_personnelle (id);

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

create unique index entretienprofessionnel_critere_encadrement_id_uindex
    on entretienprofessionnel_critere_encadrement (id);

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

-- PRIVILEGE ---------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('campagne', 'Gestion des campagnes d''entretiens professionnels', 'EntretienProfessionnel\Provider\Privilege', 1050);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'campagne_afficher', 'Afficher les campagnes', 10 UNION
    SELECT 'campagne_ajouter', 'Ajouter une campagne', 20 UNION
    SELECT 'campagne_modifier', 'Modifier une campagne', 30 UNION
    SELECT 'campagne_historiser', 'Historiser une campagne', 40 UNION
    SELECT 'campagne_detruire', 'Supprimer une campagne', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'campagne';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('entretienpro', 'Gestion des entretiens professionnels', 'EntretienProfessionnel\Provider\Privilege', 1000);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'entretienpro_index', 'Afficher l''index des entretiens professionnels', 0 UNION
    SELECT 'entretienpro_mesentretiens', 'Menu --Mes entretiens pro--', 1 UNION
    SELECT 'entretienpro_convoquer', 'Convoquer ou modifier une convocation', 5 UNION
    SELECT 'entretienpro_afficher', 'Afficher les entretiens professionnels', 10 UNION
    SELECT 'entretienpro_exporter', 'Exporter un entretien (CREP, CREF)', 15 UNION
    SELECT 'entretienpro_modifier', 'Modifier un entretien professionnel', 30 UNION
    SELECT 'entretienpro_historiser', 'Historiser/restaurer un entretien professionnel', 40 UNION
    SELECT 'entretienpro_detruire', 'Supprimer un entretien professionnel', 50 UNION
    SELECT 'entretienpro_valider_agent', 'Valider en tant qu''Agent', 900 UNION
    SELECT 'entretienpro_valider_responsable', 'Valider en tant que Responsable', 910 UNION
    SELECT 'entretienpro_valider_drh', 'Valider en tant que DRH', 920 UNION
    SELECT 'entretienpro_valider_observation', 'valider_observation', 921
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'entretienpro';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('observation', 'Gestion des observation d''entretien professionnel', 'EntretienProfessionnel\Provider\Privilege', 1010);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'observation_afficher', 'Afficher une observation', 10 UNION
    SELECT 'observation_ajouter', 'Ajouter une observation ', 20 UNION
    SELECT 'observation_modifier', 'Modifier une observation ', 30 UNION
    SELECT 'observation_historiser', 'Historiser/Restaurer une observation ', 40 UNION
    SELECT 'observation_supprimer', 'Supprimer une observation ', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'observation';
