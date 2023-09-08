create table ficheposte
(
    id                    serial
        primary key,
    libelle               varchar(256),
    agent                 varchar(40),
    rifseep               integer,
    nbi                   integer,
    fin_validite          timestamp,
    histo_creation        timestamp not null,
    histo_modification    timestamp,
    histo_destruction     timestamp,
    histo_createur_id     integer   not null
        constraint ficheposte_createur_fk
        references unicaen_utilisateur_user
        on delete cascade,
    histo_modificateur_id integer
        constraint ficheposte_modificateur_fk
        references unicaen_utilisateur_user
        on delete cascade,
    histo_destructeur_id  integer
);
create unique index fiche_metier_id_uindex on ficheposte (id);

create table ficheposte_expertise
(
    id                    serial
        constraint expertise_pk
        primary key,
    ficheposte_id         integer   not null
        constraint expertise_ficheposte_fk
        references ficheposte
        on delete cascade,
    libelle               text,
    description           text,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint expertise_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint expertise_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint expertise_destructeur_fk
        references unicaen_utilisateur_user
);

create table ficheposte_specificite
(
    id                 serial
        constraint ficheposte_specificite_pk
        primary key,
    ficheposte_id      integer
        constraint ficheposte_specificite_fiche_metier_id_fk
        references ficheposte
        on delete cascade,
    specificite        text,
    encadrement        text,
    relations_internes text,
    relations_externes text,
    contraintes        text,
    moyens             text,
    formations         text
);
create unique index ficheposte_specificite_id_uindex
    on ficheposte_specificite (id);

create table ficheposte_missionsadditionnelles
(
    id  serial
        constraint specificite_activite_pk
        primary key,
    ficheposte_id         integer                                                                         not null
        constraint ficheposte_missionsadditionnelles_ficheposte_id_fk
        references ficheposte
        on delete cascade,
    mission_id            integer                                                                         not null
        constraint ficheposte_activite_specifique_missionprincipale_id_fk
        references missionprincipale
        on delete cascade,
    retrait               varchar(1024),
    description           text,
    histo_creation        timestamp default now()                                                         not null,
    histo_createur_id     integer   default 0                                                             not null
        constraint specificite_activite_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint specificite_activite_unicaen_utilisateur_user_id_fk_2
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint specificite_activite_unicaen_utilisateur_user_id_fk_3
        references unicaen_utilisateur_user
);
create unique index specificite_activite_id_uindex on ficheposte_missionsadditionnelles (id);

create table ficheposte_fichemetier
(
    id          serial
        constraint fiche_type_externe_pk
        primary key,
    fiche_poste integer not null,
    fiche_type  integer not null
        constraint ficheposte_fichemetier_fichemetier_id_fk
        references fichemetier,
    quotite     integer not null,
    principale  boolean,
    activites   varchar(128)
);
create unique index fiche_type_externe_id_uindex
    on ficheposte_fichemetier (id);

create table ficheposte_activitedescription_retiree
(
    id                    serial
        constraint ficheposte_activitedescription_retiree_pk
        primary key,
    ficheposte_id         integer   not null
        constraint fadr_ficheposte_fk
        references ficheposte
        on delete cascade,
    fichemetier_id        integer   not null
        constraint fadr_fichemetier_fk
        references fichemetier
        on delete cascade,
    activite_id           integer   not null,
    description_id        integer   not null
        constraint fadr_description_fk
        references missionprincipale_activite
        on delete cascade,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint fadr_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint fadr_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint fadr_destructeur_id
        references unicaen_utilisateur_user
);

create unique index ficheposte_activitedescription_retiree_id_uindex
    on ficheposte_activitedescription_retiree (id);

create table ficheposte_application_retiree
(
    id                    serial
        constraint ficheposte_application_conservee_pk
        primary key,
    ficheposte_id         integer   not null
        constraint fcc_ficheposte_fk
        references ficheposte
        on delete cascade,
    application_id        integer   not null
        constraint fcc_application_fk
        references element_application
        on delete cascade,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint fcc_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint fcc_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint fcc_destructeur_fk
        references unicaen_utilisateur_user
);

create unique index ficheposte_application_conservee_id_uindex
    on ficheposte_application_retiree (id);

create table ficheposte_competence_retiree
(
    id                    serial
        constraint ficheposte_competence_conservee_pk
        primary key,
    ficheposte_id         integer   not null
        constraint fcc_ficheposte_fk
        references ficheposte
        on delete cascade,
    competence_id         integer   not null
        constraint fcc_competence_fk
        references element_competence
        on delete cascade,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint fcc_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint fcc_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint fcc_destructeur_fk
        references unicaen_utilisateur_user
);

create unique index ficheposte_competence_conservee_id_uindex
    on ficheposte_competence_retiree (id);

create table ficheposte_fichemetier_domaine
(
    id                    serial
        constraint ficheposte_fichemetier_domaine_pk
        primary key,
    fichemetierexterne_id integer             not null
        constraint ficheposte_fichemetier_domaine_fiche_type_externe_id_fk
        references ficheposte_fichemetier
        on delete cascade,
    domaine_id            integer             not null
        constraint ficheposte_fichemetier_domaine_domaine_id_fk
        references metier_domaine
        on delete cascade,
    quotite               integer default 100 not null
);

create unique index ficheposte_fichemetier_domaine_id_uindex
    on ficheposte_fichemetier_domaine (id);

create table ficheposte_validation
(
    ficheposte_id integer not null
        constraint ficheposte_validations_ficheposte_id_fk
        references ficheposte
        on delete cascade,
    validation_id integer not null
        constraint ficheposte_validations_unicaen_validation_instance_id_fk
        references unicaen_validation_instance
        on delete cascade,
    constraint ficheposte_validations_pk
        primary key (ficheposte_id, validation_id)
);

create table ficheposte_etat
(
    ficheposte_id integer not null
        constraint ficheposte_etat_fichemetier_id_fk
        references ficheposte
        on delete cascade,
    etat_id       integer not null
        constraint ficheposte_etat_etat_id_fk
        references unicaen_etat_instance
        on delete cascade,
    constraint ficheposte_etat_pk
        primary key (ficheposte_id, etat_id)
);

-- PRIVILEGES -------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('', 'Fiche de poste', 'FichePoste\Provider\Privilege', 2);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'ficheposte_index', 'Accéder à l''index des fiches de poste', 0 UNION
    SELECT 'ficheposte_afficher', 'Afficher une fiche de poste', 10 UNION
    SELECT 'ficheposte_ajouter', 'Ajouter une fiche de poste', 20 UNION
    SELECT 'ficheposte_modifier', 'Modifier une fiche de poste', 30 UNION
    SELECT 'ficheposte_associeragent', 'Associer un agent à une fiche de poste', 31 UNION
    SELECT 'ficheposte_historiser', 'Historiser/Restaurer une fiche de poste', 40 UNION
    SELECT 'ficheposte_detruire', 'Détruire une fiche de poste ', 50 UNION
    SELECT 'ficheposte_afficher_poste', 'Afficher les informations sur le poste', 100 UNION
    SELECT 'ficheposte_graphique', 'Affiche graphique sur les fiche de poste', 100 UNION
    SELECT 'ficheposte_modifier_poste', 'Modifier les informations sur le poste', 110 UNION
    SELECT 'ficheposte_etat', 'Modifier l''état d''une fiche de poste', 200 UNION
    SELECT 'ficheposte_valider_responsable', 'Valider la fiche de poste en tant que responsable', 250 UNION
    SELECT 'ficheposte_valider_agent', 'Valider la fiche de poste en tant qu''agent', 260
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'ficheposte';