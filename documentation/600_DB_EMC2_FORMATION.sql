
---------------------------------------------------------------------------------------
-- TABLES -----------------------------------------------------------------------------
---------------------------------------------------------------------------------------

-- DEMANDE EXTERNE --------------------------------------------------------------------

create table formation_demande_externe
(
    id                    serial
        constraint formation_demande_externe_pk
            primary key,
    agent_id              varchar(40)             not null
        constraint formation_demande_externe_agent_c_individu_fk
            references agent,
    etat_id                 integer
        constraint formation_demande_externe_etat_etat_id_fk
            references unicaen_etat_etat,
    libelle               varchar(1024)           not null,
    organisme             varchar(1024)           not null,
    contact               varchar(1024)           not null,
    pourquoi              text,
    montant               text,
    lieu                  varchar(1024)           not null,
    debut                 date                    not null,
    fin                   date                    not null,
    motivation            text                    not null,
    prise_en_charge       boolean   default false not null,
    cofinanceur           varchar(1024),
    modalite              varchar(1024),
    justification_agent   text,
    justification_responsable   text,
    justification_refus   text,
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint formation_demande_externe_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_demande_externe_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_demande_externe_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user
);

create unique index formation_demande_externe_id_uindex
    on formation_demande_externe (id);

create table formation_demande_externe_validation
(
    demande_id integer not null
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

-- SESSION ------------------------------------------------------------------------------------------------------

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
    etat_id                 integer
        constraint formation_instance_unicaen_etat_etat_id_fk
            references unicaen_etat_etat,
    auto_inscription        boolean default false not null,
    source_id               bigint,
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
    affichage               boolean default true  not null
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
    questionnaire_id          integer
        constraint formation_instance_inscrit_autoform_formulaire_instance_id_fk
            references unicaen_autoform_formulaire_instance
            on delete set null,
    source_id                 bigint,
    id_source                 varchar(100),
    etat_id                   integer
        constraint formation_instance_inscrit_unicaen_etat_etat_id_fk
            references unicaen_etat_etat
            on delete set null,
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
    source                varchar(64),
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
create unique index formation_instance_frais_id_uindex on formation_instance_frais (id);

create table formation_seance
(
    id  serial
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
    source                varchar(64),
    id_source             varchar(64),
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
    volume                double precision
);
create unique index formation_instance_journee_id_uindex on formation_seance (id);

create table formation_presence
(
    id  serial
        constraint formation_instance_presence_pk
            primary key,
    journee_id            integer                                                        not null
        constraint formation_instance_presence_formation_instance_journee_id_fk
            references formation_seance
            on delete cascade,
    inscrit_id            integer                                                        not null
        constraint formation_instance_presence_formation_instance_inscrit_id_fk
            references formation_instance_inscrit
            on delete cascade,
    presence_type         varchar(256)                                                   not null,
    presence_temoin       boolean                                                        not null,
    commentaire           text,
    histo_creation        timestamp                                                      not null,
    histo_createur_id     integer                                                        not null
        constraint formation_instance_presence_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint formation_instance_presence_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint formation_instance_presence_user_id_fk_3
            references unicaen_utilisateur_user
);
create unique index formation_instance_presence_id_uindex  on formation_presence (id);

create table formation_formateur
(
    id serial
        constraint formation_instance_formateur_pk
            primary key,
    instance_id           integer                                                         not null
        constraint formation_instance_formateur_formation_instance_id_fk
            references formation_instance
            on delete cascade,
    prenom                varchar(256)                                                    not null,
    nom                   varchar(256)                                                    not null,
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
            references unicaen_utilisateur_user
);
create unique index formation_instance_formateur_id_uindex on formation_formateur (id);

-- Abonement ----------------------------------------------------------------------------------------

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

-- enquete ------------------------------------------------------------------------------------------------

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
create unique index formation_enquete_categorie_id_uindex on formation_enquete_categorie (id);

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
create unique index formation_enquete_question_id_uindex on formation_enquete_question (id);

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
create unique index formation_enquete_reponse_id_uindex on formation_enquete_reponse (id);

-- parcours --------------------------------------------------------------------------------------------

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


--------------------------------------------------------------------------------
-- INSERT ----------------------------------------------------------------------
--------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_auto) VALUES ('Gestionnaire de formation', 'Gestionnaire de formation', false);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_auto) VALUES ('Formateur·trice', 'Formateur·trice', false);

INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion) VALUES ('notification_nouvelle_session', 'notification_nouvelle_session', 'Notification hebdomadaire de nouvelle session de formation', null, 'P1W');
INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion) VALUES ('notification_rappel_session_imminente', 'notification_rappel_session_imminente', 'Notification de rappel d''une session de formation', null, null);
INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion) VALUES ('cloture_automatique_inscription', 'cloture_automatique_inscription', 'cloture_automatique_inscription', null, 'P1D');

-- VALIDATION --------------------------------------------------------------------

INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id) VALUES ('FORMATION_DEMANDE_AGENT', 'Validation d''un demande de formation externe par l''agent', false, now(), 0);
INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id) VALUES ('FORMATION_DEMANDE_RESPONSABLE', 'Validation d''un demande de formation externe par le responsable de l''agent', false, now(), 0);
INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id) VALUES ('FORMATION_DEMANDE_DRH', 'Validation d''un demande de formation externe par la DRH', false , now(), 0);
INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id) VALUES ('FORMATION_DEMANDE_REFUS', 'Refus d''une demande externe', false, now(), 0);

-- ETAT ---------------------------------------------------------------------------

INSERT INTO unicaen_etat_etat_type (code, libelle, icone, couleur, histo_creation, histo_createur_id)
VALUES ('FORMATION_SESSION', 'Gestion des sessions de formation', 'fas fa-chalkboard', '#3465a4', now(), 0);
INSERT INTO unicaen_etat_etat(type_id, CODE, LIBELLE, ICONE, COULEUR, histo_creation, histo_createur_id)
WITH d(code, lib, icone, couleur, histo_creation, histo_createur) AS (
    SELECT 'EN_CREATION', 'En cours de saisie', 'fas fa-edit', '#75507b', now(), 0 UNION
    SELECT 'INSCRIPTION_OUVERTE', 'Inscription ouverte', 'fas fa-book-open', '#729fcf', now(), 0 UNION
    SELECT 'INSCRIPTION_FERMEE', 'Inscription close', 'fas fa-book', '#204a87', now(), 0 UNION
    SELECT 'CONVOCATION', 'Convocations envoyées', 'fas fa-file-contract', '#fcaf3e', now(), 0 UNION
    SELECT 'ATTENTE_RETOUR', 'Demande des retours', 'far fa-comments', '#ce5c00', now(), 0 UNION
    SELECT 'FERMEE', 'Session fermée', 'far fa-check-square', '#4e9a06', now(), 0 UNION
    SELECT 'SESSION_ANNULEE', 'Session de formation annulée', 'fas fa-times', '#a40000', now(), 0

)
SELECT cp.id, d.code, d.lib, d.icone, d.couleur, d.histo_creation, d.histo_createur
FROM d
JOIN unicaen_etat_etat_type cp ON cp.CODE = 'FORMATION_SESSION';

INSERT INTO unicaen_etat_etat_type (code, libelle, icone, couleur, histo_creation, histo_createur_id)
VALUES ('FORMATION_INSCRIPTION', 'Gestion des inscriptions au formation', 'fas fa-chalkboard-teacher', '#204a87', now(), 0);
INSERT INTO unicaen_etat_etat(type_id, CODE, LIBELLE, ICONE, COULEUR, histo_creation, histo_createur_id)
WITH d(code, lib, icone, couleur, histo_creation, histo_createur) AS (
    SELECT 'FORMATION_INSCRIPTION_DRH', 'Demande validée', 'far fa-check-square', '#4e9a06', now(), 0 UNION
    SELECT 'FORMATION_INSCRIPTION_REFUSER', 'Demande refusée', 'fas fa-times', '#a40000', now(), 0 UNION
    SELECT 'FORMATION_INSCRIPTION_DEMANDE', 'Demande d''inscription en cours de validation', 'fas fa-user', '#f57900', now(), 0 UNION
    SELECT 'FORMATION_INSCRIPTION_RESPONSABLE', 'Demande validée par le responsable', 'fas fa-user-tie', '#edd400', now(), 0
)
SELECT cp.id, d.code, d.lib, d.icone, d.couleur, d.histo_creation, d.histo_createur
FROM d
JOIN unicaen_etat_etat_type cp ON cp.CODE = 'FORMATION_INSCRIPTION';

INSERT INTO unicaen_etat_etat_type (code, libelle, icone, couleur, histo_creation, histo_createur_id)
VALUES ('DEMANDE_EXTERNE', 'Gestion des demandes de formations externes', 'fas fa-school', '#729fcf', now(), 0);
INSERT INTO unicaen_etat_etat(type_id, CODE, LIBELLE, ICONE, COULEUR, histo_creation, histo_createur_id)
WITH d(code, lib, icone, couleur, histo_creation, histo_createur) AS (
    SELECT 'DEMANDE_EXTERNE_AGENT', 'Validation de l''agent', 'fas fa-user', '#f57900', now(), 0 UNION
    SELECT 'DEMANDE_EXTERNE_REDACTION', 'Demande en cours de rédaction', 'fas fa-edit', '#75507b', now(), 0 UNION
    SELECT 'DEMANDE_EXTERNE_RESP', 'Validation du responsable de l''agent', 'fas fa-user-tie', '#edd400', now(), 0 UNION
    SELECT 'DEMANDE_EXTERNE_DRH', 'Validation par le bureau de gestion des formations', 'fas fa-user-check', '#8ae234', now(), 0 UNION
    SELECT 'DEMANDE_EXTERNE_TERMINEE', 'Demande de formation externe traitée', 'far fa-check-square', '#4e9a06', now(), 0 UNION
    SELECT 'DEMANDE_EXTERNE_REJETEE', 'Demande de formation externe rejetée', 'fas fa-times', '#a40000', now(), 0

)
SELECT cp.id, d.code, d.lib, d.icone, d.couleur, d.histo_creation, d.histo_createur
FROM d
JOIN unicaen_etat_etat_type cp ON cp.CODE = 'DEMANDE_EXTERNE';

-- PRIVILEGE ----------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('demandeexterne', 'Gestion des demandes de formations externes', 10000, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'demandeexterne_index', 'Accéder l''index', 10 UNION
    SELECT 'demandeexterne_afficher', 'Afficher une demande', 20 UNION
    SELECT 'demandeexterne_ajouter', 'Ajouter une demande', 30 UNION
    SELECT 'demandeexterne_modifier', 'Modifier une demande', 40 UNION
    SELECT 'demandeexterne_historiser', 'Historiser/restaurer une demande', 50 UNION
    SELECT 'demandeexterne_supprimer', 'Supprimer une demande ', 60 UNION
    SELECT 'demandeexterne_valider_agent', 'Valider une demande en tant qu''agent', 110 UNION
    SELECT 'demandeexterne_valider_responsable', 'Valider une demande en tant que responsable', 120 UNION
    SELECT 'demandeexterne_valider_drh', 'Valider une demande en tant que gestionnaire des formations', 130 UNION
    SELECT 'demandeexterne_gerer', 'Gérer la demande externe', 140
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'demandeexterne';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formation', 'Gestion des formations ', 100, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formation_acces', 'Accés à l''index des formations', 10 UNION
    SELECT 'formation_afficher', 'Afficher une formation ', 20 UNION
    SELECT 'formation_ajouter', 'Ajouter une formation ', 30 UNION
    SELECT 'formation_modifier', 'Modifier une formation ', 40 UNION
    SELECT 'formation_historiser', 'Historiser/Restaurer une formation ', 50 UNION
    SELECT 'formation_supprimer', 'Supprimer une formation ', 60 UNION
    SELECT 'formation_questionnaire_visualiser', 'Afficher les questionnaires de retour de formation', 110 UNION
    SELECT 'formation_questionnaire_modifier', 'Renseigner les questionnaires de retour de formation', 120
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formation';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationabonnement', 'Gestion du abonnement aux formations', 1100, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationabonnement_abonner', 'S''abonner une formation', 0 UNION
    SELECT 'formationabonnement_desabonner', 'Se desinscrire d''une formation', 10 UNION
    SELECT 'formationabonnement_liste_agent', 'Lister les abonnements par agents', 20 UNION
    SELECT 'formationabonnement_liste_formation', 'Lister les abonnements par foramtions', 40 UNION
    SELECT 'formationabonnement_gerer', 'Gérer les abonnements', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationabonnement';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationinstancedocument', 'Gestion des formations - Documents', 319, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationinstancedocument_convocation', 'Génération des convocations', 10 UNION
    SELECT 'formationinstancedocument_emargement', 'Génération des listes d''émargement', 20 UNION
    SELECT 'formationinstancedocument_attestation', 'Génération des attestations de formation', 30 UNION
    SELECT 'formationinstancedocument_historique', 'Génération des historiques de formation', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstancedocument';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationenquete', 'Gestion de l''enquête', 1200, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'enquete_index', 'Accés à la configuration de l''enquête ', 10 UNION
    SELECT 'enquete_ajouter', 'Ajouter une catégorie ou un question', 20 UNION
    SELECT 'enquete_modifier', 'Modifier une catégorie ou une question', 30 UNION
    SELECT 'enquete_historiser', 'Historiser/restaurer une catégorie ou une question', 40 UNION
    SELECT 'enquete_supprimer', 'Supprimer une catégorie ou une question', 50 UNION
    SELECT 'enquete_resultat', 'Afficher les résultats', 100 UNION
    SELECT 'enquete_repondre', 'Répondre à l''enquête', 110
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationenquete';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationinstance', 'Gestion des formations - Actions de formation', 313, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationinstance_afficher', 'Afficher une action de formation', 10 UNION
    SELECT 'formationinstance_ajouter', 'Ajouter une action de formation', 20 UNION
    SELECT 'formationinstance_modifier', 'Modifier une action de formation', 30 UNION
    SELECT 'formationinstance_historiser', 'Historiser/Restaurer une action de formation', 40 UNION
    SELECT 'formationinstance_supprimer', 'Supprimer une instance de formation', 50 UNION
    SELECT 'formationinstance_gerer_inscription', 'Gérer les inscriptions à une instance de formation', 100 UNION
    SELECT 'formationinstance_gerer_seance', 'Gérer les séances d''une instance de formation', 110 UNION
    SELECT 'formationinstance_gerer_formateur', 'Gérer les formations d''une instance de formation', 120 UNION
    SELECT 'formationinstance_annuler', 'Annuler une session', 130
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstance';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('projetpersonnel', 'Gestion du projet personnel', 1050, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'projetpersonnel_acces', 'Accéder au projet personnel', 10
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'projetpersonnel';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('planformation', 'Gestion du plan de formation', 1000, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'planformation_acces', 'Accéder au plan de formation', 0
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'planformation';


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('lagaf', 'Importation depuis les données de LAGAF', 99998, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'import_lagaf', 'Lancer l''importation', 1
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'lagaf';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationgroupe', 'Gestion des formations - Groupe', 311, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationgroupe_afficher', 'Afficher un groupe de formation', 10 UNION
    SELECT 'formationgroupe_ajouter', 'Ajouter un groupe de formation', 20 UNION
    SELECT 'formationgroupe_modifier', 'Modifier un groupe de formation', 30 UNION
    SELECT 'formationgroupe_historiser', 'Historiser/Restaurer un groupe de formation', 40 UNION
    SELECT 'formationgroupe_supprimer', 'Supprimer un groupe de formation ', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationgroupe';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationtheme', 'Gestion des formations - Thème', 312, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationtheme_afficher', 'Afficher un thème de formation ', 10 UNION
    SELECT 'formationtheme_ajouter', 'Ajouter un thème de formation', 20 UNION
    SELECT 'formationtheme_modifier', 'Modifier un thème de formation', 30 UNION
    SELECT 'formationtheme_historiser', 'Modifier un thème de formation ', 40 UNION
    SELECT 'formationtheme_supprimer', 'Supprimer un thème de formation ', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationtheme';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationinstanceinscrit', 'Gestion des formations - Inscrits', 316, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationinstanceinscrit_modifier', 'Modifier un inscrit à une action de formation', 10 UNION
    SELECT 'inscription_valider_superieure', 'Valider une demande en tant que supérieure hiérarchique', 20 UNION
    SELECT 'inscription_valider_gestionnaire', 'Valider une inscription en tant que gestionnaire', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstanceinscrit';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationinstancepresence', 'Gestion des formations - Présences', 314, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationinstancepresence_afficher', 'Afficher les présences d''une action de formation', 10 UNION
    SELECT 'formationinstancepresence_modifier', 'Modifier les présences d''une action de formation', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstancepresence';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationinstancefrais', 'Gestion des formations - Frais', 317, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationinstancefrais_afficher', 'Afficher les frais d''un agent', 10 UNION
    SELECT 'formationinstancefrais_modifier', 'Modifier les frais d''un agent', 20
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstancefrais';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationagent', 'Gestion des agents', 1000, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationagent_mesagents', 'Affichage du menu - Mes agents -', 30 UNION
    SELECT 'formationagent_afficher', 'Afficher un agent', 20 UNION
    SELECT 'formationagent_index', 'Accéder à l''index des agents', 10
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationagent';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationstructure', 'Gestion des structures', 1100, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationstructure_messtructures', 'Affichage du menu - Mes structures -', 30 UNION
    SELECT 'formationstructure_afficher', 'Afficher une structure', 20 UNION
    SELECT 'formationstructure_index', 'Accéder à l''index des structures', 10
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationstructure';


INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationstructure', 'Gestion des structures', 1100, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationstructure_messtructures', 'Affichage du menu - Mes structures -', 30 UNION
    SELECT 'formationstructure_afficher', 'Afficher une structure', 20 UNION
    SELECT 'formationstructure_index', 'Accéder à l''index des structures', 10
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationstructure';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('planformation', 'Gestion du plan de formation', 1100, 'Formation\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'planformation_courant', 'Accéder au plan de formation courant', 10 UNION
    SELECT 'planformation_index', 'Accéder à l''index', 20 UNION
    SELECT 'planformation_afficher', 'Afficher un plan de formation', 30 UNION
    SELECT 'planformation_ajouter', 'Ajouter un plan de formation', 40 UNION
    SELECT 'planformation_modifier', 'Modifier un plan de formation', 50 UNION
    SELECT 'planformation_supprimer', 'Supprimer un plan de formation', 60

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'planformation';