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

create table configuration_entretienpro
(
    id serial not null constraint configuration_entretienpro_pk primary key,
    operation varchar(64) not null,
    valeur varchar(128) not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint configuration_entretienpro_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint configuration_entretienpro_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint configuration_entretienpro_destructeur_fk references unicaen_utilisateur_user
);
create unique index configuration_entretienpro_id_uindex on configuration_entretienpro (id);

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

-- INSERT ----------------------

INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('maîtrise technique ou expertise scientifique du domaine d’activité', '2022-08-31 14:39:02.233354', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('connaissance de l’environnement professionnel et capacité à s’y situer', '2022-08-31 14:39:55.516559', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('capacité à appréhender les enjeux des dossiers et des affaires traités', '2022-08-31 14:40:05.319325', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('capacité d’anticipation et d’innovation', '2022-08-31 14:40:15.797077', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('implication dans l’actualisation de ses connaissances professionnelles, volonté de s’informer et de se former', '2022-08-31 14:39:02.233354', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('capacité d’analyse, de synthèse et de résolution des problèmes ', '2022-08-31 14:40:15.797077', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('qualités d’expression écrite', '2022-08-31 14:41:17.417040', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('qualités d’expression orale', '2022-08-31 14:41:25.827256', 0);

INSERT INTO entretienprofessionnel_critere_contribution (libelle, histo_creation, histo_createur_id, histo_modification) VALUES ('sens du service public et conscience professionnelle', '2022-08-31 14:43:02.387624', 0, null);
INSERT INTO entretienprofessionnel_critere_contribution (libelle, histo_creation, histo_createur_id, histo_modification) VALUES ('capacité à respecter l’organisation collective du travail', '2022-08-31 14:43:02.387624', 0, null);
INSERT INTO entretienprofessionnel_critere_contribution (libelle, histo_creation, histo_createur_id, histo_modification) VALUES ('rigueur et efficacité (fiabilité et qualité du travail effectué, respect des délais, des normes et des procédures, sens de l’organisation, sens de la méthode, attention portée à la qualité du service rendu)', '2022-08-31 14:43:02.387624', 0, null);
INSERT INTO entretienprofessionnel_critere_contribution (libelle, histo_creation, histo_createur_id, histo_modification) VALUES ('aptitude à exercer des responsabilités particulières ou à faire face à des sujétions spécifiques au poste occupé', '2022-08-31 14:44:38.878588', 0, null);
INSERT INTO entretienprofessionnel_critere_contribution (libelle, histo_creation, histo_createur_id, histo_modification) VALUES ('capacité à partager l’information, à transférer les connaissances et à rendre compte', '2022-08-31 14:45:30.693717', 0, null);
INSERT INTO entretienprofessionnel_critere_contribution (libelle, histo_creation, histo_createur_id, histo_modification) VALUES ('dynamisme et capacité à réagir', '2022-08-31 14:45:30.693717', 0, null);
INSERT INTO entretienprofessionnel_critere_contribution (libelle, histo_creation, histo_createur_id, histo_modification) VALUES ('sens des responsabilités', '2022-08-31 14:45:50.740260', 0, null);
INSERT INTO entretienprofessionnel_critere_contribution (libelle, histo_creation, histo_createur_id, histo_modification) VALUES ('capacité de travail', '2022-08-31 14:45:50.740260', 0, null);
INSERT INTO entretienprofessionnel_critere_contribution (libelle, histo_creation, histo_createur_id, histo_modification) VALUES ('capacité à s’investir dans des projets ', '2022-08-31 14:45:50.740260', 0, null);
INSERT INTO entretienprofessionnel_critere_contribution (libelle, histo_creation, histo_createur_id, histo_modification) VALUES ('contribution au respect des règles d’hygiène et de sécurité', '2022-08-31 14:45:58.690756', 0, null);

INSERT INTO entretienprofessionnel_critere_encadrement (libelle, histo_creation, histo_createur_id) VALUES ('capacité à animer une équipe ou un réseau', '2022-08-31 14:50:58.105979', 0);
INSERT INTO entretienprofessionnel_critere_encadrement (libelle, histo_creation, histo_createur_id) VALUES ('capacité à identifier, mobiliser et valoriser les compétences individuelles et collectives', '2022-08-31 14:50:58.105979', 0);
INSERT INTO entretienprofessionnel_critere_encadrement (libelle, histo_creation, histo_createur_id) VALUES ('capacité d’organisation et de pilotage', '2022-08-31 14:51:06.688632', 0);
INSERT INTO entretienprofessionnel_critere_encadrement (libelle, histo_creation, histo_createur_id) VALUES ('aptitude à la conduite de projets', '2022-08-31 14:51:14.732843', 0);
INSERT INTO entretienprofessionnel_critere_encadrement (libelle, histo_creation, histo_createur_id) VALUES ('capacité à déléguer', '2022-08-31 14:51:25.652370', 0);
INSERT INTO entretienprofessionnel_critere_encadrement (libelle, histo_creation, histo_createur_id) VALUES ('capacité à former', '2022-08-31 14:51:33.228772', 0);
INSERT INTO entretienprofessionnel_critere_encadrement (libelle, histo_creation, histo_createur_id) VALUES ('aptitude au dialogue, à la communication et à la négociation', '2022-08-31 14:51:49.148644', 0);
INSERT INTO entretienprofessionnel_critere_encadrement (libelle, histo_creation, histo_createur_id) VALUES ('aptitude à prévenir, arbitrer et gérer les conflits', '2022-08-31 14:52:05.640716', 0);
INSERT INTO entretienprofessionnel_critere_encadrement (libelle, histo_creation, histo_createur_id) VALUES ('aptitude à faire des propositions, à prendre des décisions et à les faire appliquer', '2022-08-31 14:52:22.356878', 0);

INSERT INTO entretienprofessionnel_critere_personnelle (libelle, histo_creation, histo_createur_id) VALUES ('autonomie, discernement et sens des initiatives dans l’exercice de ses attributions', '2022-08-31 14:49:05.349125', 0);
INSERT INTO entretienprofessionnel_critere_personnelle (libelle, histo_creation, histo_createur_id) VALUES ('capacité d’adaptation', '2022-08-31 14:49:23.001930', 0);
INSERT INTO entretienprofessionnel_critere_personnelle (libelle, histo_creation, histo_createur_id) VALUES ('capacité à travailler en équipe', '2022-08-31 14:49:30.966011', 0);
INSERT INTO entretienprofessionnel_critere_personnelle (libelle, histo_creation, histo_createur_id) VALUES ('aptitudes relationnelles (avec le public et dans l’environnement professionnel), notamment maîtrise de soi', '2022-08-31 14:49:30.966011', 0);
