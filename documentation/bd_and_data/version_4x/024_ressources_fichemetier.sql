-- TABLES -------------------------------------------------------------------------------

create table fichemetier
(
    id                    serial
        constraint fiche_type_metier_pkey
        primary key,
    metier_id             integer   not null
        constraint fichetype_metier__fk
        references metier_metier,
    expertise             boolean default false,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint fichemetier_user_id_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp not null,
    histo_modificateur_id integer   not null,
    histo_destruction     timestamp,
    histo_destructeur_id  integer,
    raison                text
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

create table fichemetier_mission
(
    id             serial
        constraint fichemetier_activite_pkey
        primary key,
    fichemetier_id integer                                                         not null
        constraint fichemetier_activite_fichemetier_id_fk
        references fichemetier
        on delete cascade,
    mission_id     integer                                                         not null
        constraint fichemetier_activite_missionprincipale_id_fk
        references missionprincipale
        on delete cascade,
    ordre          integer default 0                                               not null
);

create unique index fichemetier_activite_id_uindex
    on fichemetier_mission (id);


-- TODO fait plus reference au fiche de poste ... MOVE IT !!!
create table fichemetier_fichetype
(
    id       serial
        primary key,
    fiche_id integer           not null
        constraint fichemetier_fichetype_fiche_type_metier_id_fk
        references fichemetier
        on delete cascade,
    activite integer           not null,
    position integer default 0 not null
);

create unique index fichemetier_fichetype_id_uindex on fichemetier_fichetype (id);

create table fichemetier_etat
(
    fichemetier_id integer not null
        constraint fichemetier_etat_fichemetier_id_fk
        references fichemetier
        on delete cascade,
    etat_id        integer not null
        constraint fichemetier_etat_etat_id_fk
        references unicaen_etat_instance
        on delete cascade,
    constraint fichemetier_etat_pk
        primary key (fichemetier_id, etat_id)
);

-- PRIVILEGES --------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('fichemetier', 'Fiche métier', 'Application\Provider\Privilege', 1);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'fichemetier_index', 'Accéder à l''index des fiches métiers', 0 UNION
    SELECT 'fichemetier_afficher', 'Afficher une fiche métier', 10 UNION
    SELECT 'fichemetier_ajouter', 'Ajouter une fiche métier', 20 UNION
    SELECT 'fichemetier_modifier', 'Éditer une fiche métier', 30 UNION
    SELECT 'fichemetier_historiser', 'Historiser/Restaurer une fiche métier', 40 UNION
    SELECT 'fichemetier_detruire', 'Détruire une fiche métier', 50 UNION
    SELECT 'fichemetier_etat', 'Gestion des états des fiches métiers', 1000
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'fichemetier';


