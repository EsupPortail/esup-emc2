-- TABLES - AIDE ------------------------------------------------------------------------------

create table unicaen_aide_glossaire_definition
(
    id serial constraint unicaen_glossaire_definition_pk primary key,
    terme        varchar(1024)                                                                 not null,
    definition   text                                                                          not null,
    alternatives text,
    historisee   boolean default false                                                         not null
);
create unique index unicaen_glossaire_definition_id_uindex
    on unicaen_aide_glossaire_definition (id);
create unique index unicaen_glossaire_definition_terme_uindex
    on unicaen_aide_glossaire_definition (terme);

create table unicaen_aide_faq_question
(
    id         serial
        constraint unicaen_faq_question_pk
        primary key,
    question   varchar(4096) not null,
    reponse    text          not null,
    historisee boolean default false,
    ordre      integer
);
create unique index unicaen_faq_question_id_uindex
    on unicaen_aide_faq_question (id);

create table unicaen_aide_documentation_lien
(
    id          serial
        constraint unicaen_aide_documentation_lien_pk
        primary key,
    texte       varchar(1024),
    lien_texte  varchar(1024) not null,
    lien_url    varchar(1024) not null,
    description text,
    ordre       integer,
    historisee  boolean default false,
    role_ids    varchar(4096)
);
create unique index unicaen_aide_documentation_lien_id_uindex
    on unicaen_aide_documentation_lien (id);

-- TABLES - AUTOFORM ---------------------------------------------------------

create table unicaen_autoform_formulaire
(
    id                    serial
        constraint autoform_formulaire_pk
            primary key,
    libelle               varchar(128)            not null,
    description           varchar(2048),
    code                  varchar(256),
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint autoform_formulaire_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint autoform_formulaire_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint autoform_formulaire_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index autoform_formulaire_id_uindex on unicaen_autoform_formulaire (id);

create table unicaen_autoform_categorie
(
    id                    serial
        constraint autoform_categorie_pk
            primary key,
    code                  varchar(64)           not null,
    libelle               varchar(256)          not null,
    ordre                 integer default 10000 not null,
    formulaire            integer               not null
        constraint autoform_categorie_formulaire_fk
            references unicaen_autoform_formulaire,
    mots_clefs            varchar(1024),
    histo_creation        timestamp              default now() not null,
    histo_createur_id     integer                default 0 not null
        constraint autoform_categorie_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp             ,
    histo_modificateur_id integer
        constraint autoform_categorie_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint autoform_categorie_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index autoform_categorie_code_uindex on unicaen_autoform_categorie (code);
create unique index autoform_categorie_id_uindex on unicaen_autoform_categorie (id);

create table unicaen_autoform_champ
(
    id                    serial
        constraint autoform_champ_pk
            primary key,
    categorie             integer               not null
        constraint autoform_champ_categorie_fk
            references unicaen_autoform_categorie,
    code                  varchar(64)           not null,
    libelle               varchar(1024)         not null,
    texte                 text                  not null,
    ordre                 integer default 10000 not null,
    element               varchar(64),
    balise                boolean,
    options               varchar(1024),
    mots_clefs            varchar(1024),
    histo_creation        timestamp              default now() not null,
    histo_createur_id     integer                default 0 not null
        constraint autoform_champ_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp            ,
    histo_modificateur_id integer
        constraint autoform_champ_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint autoform_champ_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index autoform_champ_id_uindex on unicaen_autoform_champ (id);

create table unicaen_autoform_formulaire_instance
(
    id                    serial
        constraint autoform_formulaire_instance_pk
            primary key,
    formulaire            integer   not null
        constraint autoform_formulaire_instance_autoform_formulaire_id_fk
            references unicaen_autoform_formulaire,
    histo_creation        timestamp not null,
    histo_createur_id     integer
        constraint autoform_formulaire_instance_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp not null,
    histo_modificateur_id integer   not null
        constraint autoform_formulaire_instance_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint autoform_formulaire_instance_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index autoform_formulaire_instance_id_uindex  on unicaen_autoform_formulaire_instance (id);

create table unicaen_autoform_formulaire_reponse
(
    id                    serial
        constraint autoform_reponse_pk
            primary key,
    instance              integer   not null
        constraint autoform_formulaire_reponse_instance_fk
            references unicaen_autoform_formulaire_instance
            on delete cascade,
    champ                 integer   not null
        constraint autoform_reponse_champ_fk
            references unicaen_autoform_champ
            on delete cascade,
    reponse               text,
    histo_creation        timestamp not null,
    histo_createur_id     integer   not null
        constraint autoform_reponse_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp not null,
    histo_modificateur_id integer   not null
        constraint autoform_reponse_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint autoform_reponse_destructeur_fk
            references unicaen_utilisateur_user
);
create unique index autoform_reponse_id_uindex  on unicaen_autoform_formulaire_reponse (id);

create table unicaen_autoform_validation
(
    id                    serial
        constraint validation_pk
            primary key,
    type                  varchar(64) not null,
    instance              integer     not null
        constraint validation_instance_fk
            references unicaen_autoform_formulaire_instance
            on delete cascade,
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint validation_createur_fk
            references unicaen_utilisateur_user,
    histo_modification    timestamp   not null,
    histo_modificateur_id integer     not null
        constraint validation_modificateur_fk
            references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint validation_destructeur_fk
            references unicaen_utilisateur_user,
    type_validation       varchar(64),
    complement            text,
    informations          text,
    differences           text,
    reference             integer
);
create unique index validation_id_uindex  on unicaen_autoform_validation (id);

create table unicaen_autoform_validation_reponse
(
    id         serial
        constraint validation_reponse_pk
            primary key,
    validation integer               not null
        constraint autoform_validation_reponse_autoform_validation_id_fk
            references unicaen_autoform_validation
            on delete cascade,
    reponse    integer               not null
        constraint validation_reponse_autoform_reponse_id_fk
            references unicaen_autoform_formulaire_reponse
            on delete cascade,
    value      boolean default false not null
);
create unique index validation_reponse_id_uindex  on unicaen_autoform_validation_reponse (id);

-- TABLES - MAIL -------------------------------------------------------------

create table unicaen_mail_mail
(
    id                     serial
        constraint umail_pkey
            primary key,
    date_envoi             timestamp    not null,
    status_envoi           varchar(256) not null,
    destinataires          text         not null,
    destinataires_initials text,
    sujet                  text,
    corps                  text,
    mots_clefs             text,
    log                    text
);
create unique index ummail_id_uindex on unicaen_mail_mail (id);

-- TABLES - RENDERER ------------------------------------------------------------------------------

create table unicaen_renderer_macro
(
    id            serial
        constraint unicaen_document_macro_pk
            primary key,
    code          varchar(256) not null,
    description   text,
    variable_name varchar(256) not null,
    methode_name  varchar(256) not null
);
create unique index unicaen_document_macro_code_uindex  on unicaen_renderer_macro (code);
create unique index unicaen_document_macro_id_uindex on unicaen_renderer_macro (id);

create table unicaen_renderer_template
(
    id             serial
        constraint unicaen_content_content_pk
            primary key,
    code           varchar(256) not null,
    description    text,
    document_type  varchar(256) not null,
    document_sujet text         not null,
    document_corps text         not null,
    document_css   text,
    namespace      varchar(1024)
);
create unique index unicaen_content_content_code_uindex on unicaen_renderer_template (code);
create unique index unicaen_content_content_id_uindex on unicaen_renderer_template (id);
create unique index unicaen_document_rendu_id_uindex on unicaen_renderer_template (id);

create table unicaen_renderer_rendu
(
    id              serial
        constraint unicaen_document_rendu_pk
            primary key,
    template_id     integer
        constraint unicaen_document_rendu_template_id_fk
            references unicaen_renderer_template
            on delete set null,
    date_generation timestamp not null,
    sujet           text      not null,
    corps           text      not null
);

-- PRIVILEGES - AIDE ---------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace)
VALUES ('unicaenaidedocumentation', 'UnicaenAide - Documentation', 'UnicaenAide\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documentation_afficher', 'Afficher la documentation', 10 UNION
    SELECT 'documentation_index', 'Accès à l''index des documentations', 20 UNION
    SELECT 'documentation_ajouter', 'Ajouter une documentation', 30 UNION
    SELECT 'documentation_modifier', 'Modifier une documentation', 40 UNION
    SELECT 'documentation_historiser', 'Historiser/restaurer une documentation', 50 UNION
    SELECT 'documentation_supprimer', 'Supprimer une documentation', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaenaidedocumentation';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace)
VALUES ('unicaenaidefaq', 'UnicaenAide - F.A.Q.', 'UnicaenAide\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'faq_afficher', 'Afficher la FAQ', 10 UNION
    SELECT 'faq_index', 'Accès à l''index des questions', 20 UNION
    SELECT 'faq_ajouter', 'Ajouter une question', 30 UNION
    SELECT 'faq_modifier', 'Modifier une question', 40 UNION
    SELECT 'faq_historiser', 'Historiser/restaurer une question', 50 UNION
    SELECT 'faq_supprimer', 'Supprimer une question', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaenaidefaq';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace)
VALUES ('unicaenaideglossaire', 'UnicaenAide - Glossaire', 'UnicaenAide\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'glossaire_afficher', 'Affichage du glossaire', 10 UNION
    SELECT 'glossaire_index', 'Accès à l''index des défintions', 100 UNION
    SELECT 'glossaire_ajouter', 'Ajouter une définition', 110 UNION
    SELECT 'glossaire_modifier', 'Modifier une définition', 120 UNION
    SELECT 'glossaire_historiser', 'Historiser/restaurer une définition', 130 UNION
    SELECT 'glossaire_supprimer', 'Supprimer une supprimer', 140
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'unicaenaideglossaire';

-- PRIVILEGES - AUTOFORM ----------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('autoformcategorie', 'Autoform - Gestion des catégories', 'UnicaenAutoform\Provider\Privilege', 5200);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'categorief_index', 'Accéder à l''index', 10 UNION
    SELECT 'categorief_afficher', 'Afficher', 20 UNION
    SELECT 'categorief_ajouter', 'Ajouter', 30 UNION
    SELECT 'categorief_modifier', 'Modifier', 40 UNION
    SELECT 'categorief_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'categorief_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformcategorie';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('autoformchamp', 'Autoform - Gestion des champs', 'UnicaenAutoform\Provider\Privilege', 5300);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'champ_index', 'Accéder à l''index', 10 UNION
    SELECT 'champ_afficher', 'Afficher', 20 UNION
    SELECT 'champ_ajouter', 'Ajouter', 30 UNION
    SELECT 'champ_modifier', 'Modifier', 40 UNION
    SELECT 'champ_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'champ_supprimer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformchamp';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('autoformformulaire', 'Autoform - Gestion des formulaires', 'UnicaenAutoform\Provider\Privilege', 5100);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formulaire_index', 'Accéder à l''index', 10 UNION
    SELECT 'formulaire_afficher', 'Afficher', 20 UNION
    SELECT 'formulaire_ajouter', 'Ajouter', 30 UNION
    SELECT 'formulaire_modifier', 'Modifier', 40 UNION
    SELECT 'formulaire_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'formulaire_supprimer', 'Supprimer', 60

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformformulaire';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('autoformindex', 'Autoform - Gestion de l''index', 'UnicaenAutoform\Provider\Privilege', 5000);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'index', 'Afficher le menu', 10
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformindex';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('autoformvalidation', 'Autoform - Gestion des validations', 'UnicaenAutoform\Provider\Privilege', 5400);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'validationf_index', 'Accéder à l''index', 10 UNION
    SELECT 'validationf_afficher', 'Afficher', 20 UNION
    SELECT 'validationf_ajouter', 'Ajouter', 30 UNION
    SELECT 'validationf_modifier', 'Modifier', 40 UNION
    SELECT 'validationf_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'validationf_supprimer', 'Supprimer', 60

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'autoformvalidation';

-- PRIVILEGES - MAIL --------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('mail', 'UnicaenMail - Gestion des mails', 'UnicaenMail\Provider\Privilege', 9000);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'mail_index', 'Affichage de l''index', 10 UNION
    SELECT 'mail_afficher', 'Afficher un mail', 20 UNION
    SELECT 'mail_reenvoi', 'Ré-envoi d''un mail', 30 UNION
    SELECT 'mail_supprimer', 'Suppression d''un mail', 40 UNION
    SELECT 'mail_test', 'Envoi d''un mail de test', 100

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'mail';

-- PRIVILEGES - RENDERER -----------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('documentcontenu', 'UnicaenRenderer - Gestion des contenus', 'UnicaenRenderer\Provider\Privilege', 11030);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documentcontenu_index', 'Accès à l''index des contenus', 10 UNION
    SELECT 'documentcontenu_afficher', 'Afficher un contenu', 20 UNION
    SELECT 'documentcontenu_supprimer', 'Supprimer un contenu ', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documentcontenu';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('documentmacro', 'UnicaenRenderer - Gestion des macros', 'UnicaenRenderer\Provider\Privilege', 11010);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documentmacro_index', 'Afficher l''index des macros', 1 UNION
    SELECT 'documentmacro_ajouter', 'Ajouter une macro', 10 UNION
    SELECT 'documentmacro_modifier', 'Modifier une macro', 20 UNION
    SELECT 'documentmacro_supprimer', 'Supprimer une macro', 40

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documentmacro';

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('documenttemplate', 'UnicaenRenderer - Gestion des templates', 'UnicaenRenderer\Provider\Privilege', 11020);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'documenttemplate_index', 'Afficher l''index des contenus', 1 UNION
    SELECT 'documenttemplate_afficher', 'Afficher un template', 10 UNION
    SELECT 'documenttemplate_ajouter', 'Ajouter un contenu', 15 UNION
    SELECT 'documenttemplate_modifier', 'Modifier un contenu', 20 UNION
    SELECT 'documenttemplate_supprimer', 'Supprimer un contenu', 40
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'documenttemplate';

