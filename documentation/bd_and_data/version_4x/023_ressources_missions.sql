-- TABLE - MISSION PRINCIPALE --------------------------------------------

create table missionprincipale_activite
(
    id  serial
        constraint activite_description_pk
        primary key,
    mission_id            integer                                                                not null,
    libelle               text                                                                   not null,
    ordre                 integer,
    histo_creation        timestamp                                                              not null,
    histo_createur_id     integer                                                                not null
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
create unique index activite_description_id_uindex on missionprincipale_activite (id);

create table missionprincipale
(
    id                    serial
        constraint missionprincipale_pk
        primary key,
    libelle               varchar(1024),
    niveau_id             integer
        constraint missionprincipale_carriere_niveau_enveloppe_id_fk
        references carriere_niveau_enveloppe
        on delete cascade,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint missionprincipale_unicaen_utilisateur_user_id_fk3
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint missionprincipale_unicaen_utilisateur_user_id_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint missionprincipale_unicaen_utilisateur_user_id_fk2
        references unicaen_utilisateur_user
);

create table missionprincipale_application
(
    mission_id             integer not null
        constraint missionprincipale_application_missionprincipale_id_fk
        references missionprincipale
        on delete cascade,
    application_element_id integer not null
        constraint activite_application_application_element_id_fk
        references element_application_element
        on delete cascade,
    constraint activite_application_pk
        primary key (mission_id, application_element_id)
);

create table missionprincipale_competence
(
    mission_id            integer not null
        constraint missionprincipale_competence_missionprincipale_id_fk
        references missionprincipale
        on delete cascade,
    competence_element_id integer not null
        constraint activite_competence_competence_element_id_fk
        references element_competence_element
        on delete cascade,
    constraint activite_competence_pk
        primary key (mission_id, competence_element_id)
);

create table missionprincipale_domaine
(
    mission_id integer not null
        constraint missionprincipale_domaine_missionprincipale_id_fk
        references missionprincipale
        on delete cascade,
    domaine_id integer not null
        constraint activite_domaine_domaine_id_fk
        references metier_domaine
        on delete cascade,
    constraint activite_domaine_pk
        primary key (mission_id, domaine_id)
);


-- TABLE - MISSION SPECIFIQUE --------------------------------------------

create table mission_specifique_theme
(
    id                    serial
        constraint mission_specifique_theme_pk
        primary key,
    libelle               varchar(256) not null,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint mission_specifique_theme_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint mission_specifique_theme_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint mission_specifique_theme_destructeur_fk
        references unicaen_utilisateur_user
);
create unique index mission_specifique_theme_id_uindex on mission_specifique_theme (id);

create table mission_specifique_type
(
    id                    serial
        constraint mission_specifique_type_pk
        primary key,
    libelle               varchar(256) not null,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint mission_specifique_type_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint mission_specifique_type_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint mission_specifique_type_destructeur_fk
        references unicaen_utilisateur_user
);
create unique index mission_specifique_type_id_uindex on mission_specifique_type (id);

create table mission_specifique
(
    id                    serial
        constraint mission_specifique_pk
        primary key,
    libelle               varchar(256) not null,
    theme_id              integer
        constraint mission_specifique_mission_specifique_theme_id_fk
        references mission_specifique_theme
        on delete set null,
    type_id               integer
        constraint mission_specifique_mission_specifique_type_id_fk
        references mission_specifique_type
        on delete set null,
    description           text,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint mission_specifique_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint mission_specifique_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint mission_specifique_destructeur_fk
        references unicaen_utilisateur_user
);
create unique index mission_specifique_id_uindex on mission_specifique (id);

-- PRIVILEGES - MISSION PRINCIPALE ------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('missionprincipale', 'Gestion des missions principales', 'FicheMetier\Provider\Privilege', 800);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionprincipale_index', 'Accéder à l''index', 10 UNION
    SELECT 'missionprincipale_afficher', 'Afficher', 20 UNION
    SELECT 'missionprincipale_ajouter', 'Ajouter', 30 UNION
    SELECT 'missionprincipale_modifier', 'Modifier', 40 UNION
    SELECT 'missionprincipale_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'missionprincipale_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionprincipale';

-- PRIVILEGES - MISSION SPECIFIQUE ------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('missionspecifique', 'Gestion des missions spécifiques', 'Application\Provider\Privilege', 650);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionspecifique_index', 'Gestion - Affichage de l''index des missions specifiques', 100 UNION
    SELECT 'missionspecifique_afficher', 'Gestion - Afficher une mission spécifique', 110 UNION
    SELECT 'missionspecifique_ajouter', 'Gestion - Ajouter une mission spécifiques', 120 UNION
    SELECT 'missionspecifique_modifier', 'Gestion - Modifier une mission spécifique', 130 UNION
    SELECT 'missionspecifique_historiser', 'Gestion - Historiser/restaurer une mission spécifique', 140 UNION
    SELECT 'missionspecifique_detruire', 'Gestion - Détruire une missions spécifique', 150
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionspecifique';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('missionspecifiquetheme', 'Gestion des thèmes de mission spécifique', 'Application\Provider\Privilege', 660);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionspecifiquetheme_index', 'Gestion - Affichage de l''index des thèmes de mission specifique', 100 UNION
    SELECT 'missionspecifiquetheme_afficher', 'Gestion - Afficher un thème de  mission spécifique', 110 UNION
    SELECT 'missionspecifiquetheme_ajouter', 'Gestion - Ajouter un thème de mission spécifique', 120 UNION
    SELECT 'missionspecifiquetheme_modifier', 'Gestion - Modifier un thème mission spécifique', 130 UNION
    SELECT 'missionspecifiquetheme_historiser', 'Gestion - Historiser/restaurer un thème de mission spécifique', 140 UNION
    SELECT 'missionspecifiquetheme_detruire', 'Gestion - Détruire un thème de  mission spécifique', 150
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionspecifique';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('missionspecifiquetype', 'Gestion des types de mission spécifique', 'Application\Provider\Privilege', 670);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'missionspecifiqueaffectation_index', 'Affectation - Afficher l''index des affectation', 200 UNION
    SELECT 'missionspecifiqueaffectation_afficher', 'Affectation - Afficher une affectations de missions spécifiques', 210 UNION
    SELECT 'missionspecifiqueaffectation_ajouter', 'Affectation - Ajouter une affectation de mission spécifique', 220 UNION
    SELECT 'missionspecifiqueaffectation_modifier', 'Affectation - Modifier une affectation de mission specifique', 230 UNION
    SELECT 'missionspecifiqueaffectation_historiser', 'Affectation - Historiser/restaurer une affectation de mission spécifique', 240 UNION
    SELECT 'missionspecifiqueaffectation_detruire', 'Affectation - Détruire une affectation de mission spécifique', 250 UNION
    SELECT 'missionspecifiqueaffectation_onglet', 'Afficher l''onglet associé dans les écrans de la structure', 400
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'missionspecifique';

