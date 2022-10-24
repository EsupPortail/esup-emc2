
----------------------------------------------------------------------------------
-- TABLE -------------------------------------------------------------------------
----------------------------------------------------------------------------------


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
    structure_id bigint not null constraint entretienprofessionnel_delegue_structure_id_fk references structure on delete cascade,
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
create unique index entretienprofessionnel_critere_qualitepersonnelle_id_uindex   on entretienprofessionnel_critere_personnelle (id);

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


--------------------------------------------------------------------------------
-- PRIVILEGE -------------------------------------------------------------------
--------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('campagne', 'Gestion des campagnes d''entretiens professionnels', 1050, 'EntretienProfessionnel\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'campagne_index', 'Accéder à l''index des campagnes', 10 UNION
    SELECT 'campagne_afficher', 'Afficher une campagne', 20 UNION
    SELECT 'campagne_ajouter', 'Ajouter une campagne', 30 UNION
    SELECT 'campagne_modifier', 'Modifier une campagne', 35 UNION
    SELECT 'campagne_historiser', 'Historiser une campagne', 40 UNION
    SELECT 'campagne_detruire', 'Supprimer une campagne', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'campagne';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('entretienpro', 'Gestion des entretiens professionnels', 1000, 'EntretienProfessionnel\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'entretienpro_index', 'Afficher l''index des entretiens professionnels', 0 UNION
    select 'entretienpro_mesentretiens', 'Menu Mes entretiens', 5 UNION
    SELECT 'entretienpro_afficher', 'Afficher les entretiens professionnels', 10 UNION
    SELECT 'entretienpro_exporter', 'Exporter les entretiens professionnels', 15 UNION
    SELECT 'entretienpro_ajouter', 'Ajouter un entretien professionnel', 20 UNION
    SELECT 'entretienpro_modifier', 'Modifier un entretien professionnel', 30 UNION
    SELECT 'entretienpro_historiser', 'Historiser/restaurer un entretien professionnel', 40 UNION
    SELECT 'entretienpro_detruire', 'Supprimer un entretien professionnel', 50 UNION
    SELECT 'entretienpro_valider_agent', 'Valider en tant qu''Agent', 900 UNION
    SELECT 'entretienpro_valider_responsable', 'Valider en tant que Responsable', 910 UNION
    SELECT 'entretienpro_valider_drh', 'Valider en tant que DRH', 920 UNION
    SELECT 'entretienpro_valider_observation', 'Valider les observation', 930
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'entretienpro';

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('observation', 'Gestion des observation d''entretien professionnel', 1010, 'EntretienProfessionnel\Provider\Privilege');
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

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('sursis', 'Gestion des sursis d''entretien professionnel', 1020, 'EntretienProfessionnel\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'sursis_afficher', 'Afficher un sursis', 10 UNION
    SELECT 'sursis_ajouter', 'Ajouter un sursis', 20 UNION
    SELECT 'sursis_modifier', 'Modifier une sursis', 30 UNION
    SELECT 'sursis_historiser', 'Historiser/Restaurer un sursis', 40 UNION
    SELECT 'sursis_supprimer', 'Supprimer un sursis', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
         JOIN unicaen_privilege_categorie cp ON cp.CODE = 'sursis';


-----------------------------------------------------------------------------------------------
-- ETAT ---------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------

INSERT INTO unicaen_etat_etat_type (code, libelle, icone, couleur, histo_creation, histo_createur_id)
VALUES ('ENTRETIEN_PROFESSIONNEL', 'États liés aux entretiens professionnels', 'fa fa-briefcase', '#033ed0', '2020-10-13 16:19:06.000000', 0);

INSERT INTO unicaen_etat_etat(type_id, code, libelle, ordre, icone, couleur, histo_creation, histo_createur_id)
WITH d(code, libelle, ordre, icone, couleur, histo_creation, histo_createur_id) AS (
    SELECT 'ENTRETIEN_VALIDATION_AGENT', 'Validation de l&rsquo;agent', 1400, 'far fa-check-square', '#44c200', current_date,0 UNION
    SELECT 'ENTRETIEN_VALIDATION_HIERARCHIE', 'Validation du responsable hiérarchique', 1300, 'fas fa-user-tie', '#ffae00', current_date,0 UNION
    SELECT 'ENTRETIEN_ACCEPTATION', 'En attente confirmation de l&rsquo;agent', 1, 'fas fa-user-clock', '#b90b80', current_date,0 UNION
    SELECT 'ENTRETIEN_ACCEPTER', 'Entretien accepté par l&rsquo;agent', 2, 'fas fa-user-check', '#c100b5', current_date,0 UNION
    SELECT 'ENTRETIEN_VALIDATION_OBSERVATION', 'Expression des observations', 1200, 'far fa-comment-alt', '#ff6c0a', current_date,0 UNION
    SELECT 'ENTRETIEN_VALIDATION_RESPONSABLE', 'Validation du responsable de l&rsquo;entretien professionnel', 1100, 'fas fa-user', '#c75000', current_date,0
)
SELECT cp.id, d.code, d.libelle, d.ordre, d.icone, d.couleur, d.histo_creation, d.histo_createur_id
FROM d
         JOIN unicaen_etat_etat_type cp ON cp.CODE = 'ENTRETIEN_PROFESSIONNEL';


------------------------------------------------------------------------------------------------
-- PARAMETRE -----------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------


INSERT INTO unicaen_parametre_categorie (code, libelle, ordre, description)
VALUES ('ENTRETIEN_PROFESSIONNEL', 'Paramètres liés aux entretiens professionnels', 500, null);
INSERT INTO unicaen_parametre_parametre (categorie_id, code, libelle, description, valeurs_possibles, valeur, ordre)
WITH d(code, libelle, description, valeurs_possibles, valeur, ordre) AS (
    SELECT 'DELAI_ACCEPTATION_AGENT', 'Délai d''accepation de l''entretien par l''agent (en jours)', null, 'Number', '15', 100 UNION
    SELECT 'MAIL_LISTE_BIATS', 'Adresse électronique de la liste de liste de diffusion pour le personnel', '<p>Utilis&eacute;e lors de la cr&eacute;ation d''une campagne d''entretien profesionnel</p>', 'String', 'ZZZ_unicaen-biats@unicaen.fr', 11 UNION
    SELECT 'MAIL_LISTE_DAC', 'Adresse électronique de la liste de liste de diffusion pour les DAC', '<p>Utilis&eacute;e lors de la cr&eacute;ation d''une campagne d''entretien professionnel</p>', 'String', 'ZZZ_centrale-liste@unicaen.fr', 10 UNION
    SELECT 'INTRANET_DOCUMENT', 'Lien vers les documents associés à l''entretien professionnel', null, 'String', 'Pas d''intranet !!!! ', 1000
)
SELECT cp.id, d.code, d.libelle, d.description, d.valeurs_possibles, d.valeur, d.ordre
FROM d
         JOIN unicaen_parametre_categorie cp ON cp.CODE = 'ENTRETIEN_PROFESSIONNEL';




------------------------------------------------------------------------------------------------
-- VALIDATIONS ---------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------

INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id)
VALUES ('ENTRETIEN_RESPONSABLE', 'Validation du responsable d''un entretien professionnel', false,  now(), 0);
INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id)
VALUES ('ENTRETIEN_OBSERVATION', 'Validation des observations', false, now(), 0);
INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id)
VALUES ('ENTRETIEN_HIERARCHIE', 'Validation de la DRH d''un entretien professionnel', false,  now(), 0);
INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id)
VALUES ('ENTRETIEN_AGENT', 'Validation de l''agent d''un entretien professionnel', false, now(), 0);


--------------------------------------------------------------------------------------------------
-- TEMPLATE --------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------

-- PDF -------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('CREP - Compte rendu d''entretien professionnel', '<p>Compte-rendu de l''entretien professionnel d''un agent</p>',
        'pdf',
        'CREP.pdf',
        'Contenu du CREP');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('CREF - Compte rendu d''entretien de formation', '<p>Compte-rendu de l''entretien de formation d''un agent</p>',
        'pdf',
        'CREF.pdf',
        'Contenu du CREF');

-- MAIL ------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('CAMPAGNE_OUVERTURE_BIATSS', '<p>Mail envoyé aux personnels lors de l''ouverture d''une campagne</p>',
        'mail',
        'Ouverture campagne (BIATSS)',
        'Contenu Ouverture campagne (BIATSS)');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('CAMPAGNE_OUVERTURE_DAC', '<p>Mail envoyé aux responsables lors de l''ouverture d''une campagne</p>',
        'mail',
        'Ouverture campagne (DAC)',
        'Contenu Ouverture campagne (DAC)');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('CAMPAGNE_DELEGUE', '<p>Mail envoyé aux délégué venant d''être nommé</p>',
        'mail',
        'Vous êtes un délégué pour la campagne',
        'Contenu Vous êtes un délégué pour la campagne');


INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_CONVOCATION_ENVOI', '<p>Convocation à un entretien professionnel</p>',
        'mail',
        'Convocation à votre entretien professionnel',
        'Contenu Convocation à votre entretien professionnel');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_CONVOCATION_ACCEPTER', '<p>Acceptation de l''entretien professionnel</p>',
        'mail',
        'Acceptation de l''entretien professionnel',
        'Contenu Acceptation de l''entretien professionnel');


INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_VALIDATION_1-RESPONSABLE', '<p>Validation du responsable de l''entretien</p>',
        'mail',
        'Votre responsable vient de valider votre entretien professionnel',
        'Contenu Votre responsable vient de valider votre entretien professionnel');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_VALIDATION_2-OBSERVATION', '<p>Observations faites et validées</p>',
        'mail',
        'Des observations ont été faites et validées',
        'Contenu Des observations ont été faites et validées');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_VALIDATION_2-PAS_D_OBSERVATION', '<p>Pas d''observations validées</p>',
        'mail',
        'Pas d''observations validées',
        'Contenu Pas d''observations validées');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_VALIDATION_2-OBSERVATION_TRANSMISSION', '<p>Transmission des observations</p>',
        'mail',
        'Transmission des observations',
        'Contenu Transmission des observations');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_VALIDATION_3-HIERARCHIE', '<p>Validation de l''autorité hiérarchique</p>',
        'mail',
        'Validation de l''autorité hiérarchique',
        'Contenu Validation de l''autorité hiérarchique');


INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('NOTIFICATION_RAPPEL_CAMPAGNE', '<p>Mail d''avancement de la campagne pour les responsables</p>',
        'mail',
        'Avancement de la campagne',
        'Contenu Avancement de la campagne');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('NOTIFICATION_RAPPEL_ENTRETIEN', '<p>Rappel de l''entretien professionnel</p>',
        'mail',
        'Rappel de votre entretien professionnel',
        'Contenu Rappel de votre entretien professionnel');

-- MACRO -------------------------------------------------------------------------------------

-- TODO ---


--------------------------------------------------------------------------------------------------
-- EVENEMENT -------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------

INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('rappel_entretienpro', 'Rappel de l''entretien professionnel', 'Rappel à J-4 de l''entretien professionnel', 'entretien', null);
INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('rappel_campagne', 'Rappel de l''avancement de la campagne', 'Mail envoyé périodiquement lors de la campagne aux responsables de structures pour leur rappeler l''état d''avancement de la campagne', 'campagne', 'P2W');
INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('rappel_pas_observation_entretienpro', 'rappel_pas_observation_entretienpro', 'rappel_pas_observation_entretienpro', 'entretien', null);



---------------------------------------------------------------------------------------------------
-- AUTOFORM ---------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------

INSERT INTO unicaen_autoform_formulaire (libelle, description, code, histo_creation, histo_createur_id)
VALUES ('Compte-Rendu d''Entretien Professionnel (CREP)', null, 'ENTRETIEN_PROFESSIONNEL', current_date, 0);
INSERT INTO unicaen_autoform_formulaire (libelle, description, code, histo_creation, histo_createur_id)
VALUES ('Compte-Rendu d''Entretien de formation(CREF)', null, 'FORMATION', current_date, 0);
-- TODO COMPLETE LES FORMULAIRE --


-- INSERT ----------------------

INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('maîtrise technique ou expertise scientifique du domaine d’activité', '2022-08-31 14:39:02.233354', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('connaissance de l’environnement professionnel et capacité à s’y situer', '2022-08-31 14:39:55.516559', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('capacité à appréhender les enjeux des dossiers et des affaires traités', '2022-08-31 14:40:05.319325', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('capacité d’anticipation et d’innovation', '2022-08-31 14:40:15.797077', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('implication dans l’actualisation de ses connaissances professionnelles, volonté de s’informer et de se former', '2022-08-31 14:39:02.233354', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('capacité d’analyse, de synthèse et de résolution des problèmes ', '2022-08-31 14:40:15.797077', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('qualités d’expression écrite', '2022-08-31 14:41:17.417040', 0);
INSERT INTO entretienprofessionnel_critere_competence (libelle, histo_creation, histo_createur_id) VALUES ('qualités d’expression orale', '2022-08-31 14:41:25.827256', 0);

INSERT INTO entretienprofessionnel_critere_contribution (libelle, histo_creation, histo_createur_id, histo_modification) VALUES ('sens du service et conscience professionnelle', '2022-08-31 14:43:02.387624', 0, null);
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
INSERT INTO entretienprofessionnel_critere_personnelle (libelle, histo_creation, histo_createur_id) VALUES ('aptitudes relationnelles (avec le et dans l’environnement professionnel), notamment maîtrise de soi', '2022-08-31 14:49:30.966011', 0);

-- FORM -----------------------------------------------------------------------------------------------------

INSERT INTO unicaen_autoform_formulaire (id, code, libelle, description, histo_creation, histo_createur_id) VALUES (1, 'CREP', 'Compte-Rendu d''Entretien Professionnel (CREP)', null, '2019-04-08 14:56:46.000000', 0);
INSERT INTO unicaen_autoform_formulaire (id, code, libelle, description, histo_creation, histo_createur_id) VALUES (2, 'CREF', 'Compte-Rendu d''Entretien de formation (CREF)', null, '2019-06-27 15:44:54.000000', 0);

INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (1, '1_5cab4652d229b', 'DESCRIPTION DU POSTE OCCUPE PAR L’AGENT', 2, 1, null, '2019-04-08 15:02:10.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (2, '1_5cab477d6ebce', 'ÉVALUATION DE L’ANNEE ECOULÉE', 3, 1, null, '2019-04-08 15:07:09.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (3, '1_5d1379332f0da', 'VALEUR PROFESSIONNELLE ET MANIERE DE SERVIR DU FONCTIONNAIRE', 4, 1, null, '2019-06-26 15:54:59.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (4, '1_5d137a9a599ae', 'ACQUIS DE L’EXPERIENCE PROFESSIONNELLE', 5, 1, null, '2019-06-26 16:00:58.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (5, '1_5d137abaa7421', 'OBJECTIFS FIXÉS POUR LA NOUVELLE ANNÉE', 6, 1, null, '2019-06-26 16:01:30.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (6, '1_5d137af8278b2', 'PERSPECTIVES D’ÉVOLUTION PROFESSIONNELLE', 7, 1, null, '2019-06-26 16:02:32.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (10, '1_5f5a3e8fc5a1f', 'MODALITÉS DE RECOURS', 8, 1, null, '2020-09-10 16:56:15.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (11, '2_5f5a404e10b37', 'COMPÉTENCES À ACQUÉRIR OU À DÉVELOPPER POUR TENIR LE POSTE', 8, 2, null, '2020-09-10 17:03:42.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (12, '2_5f5a40bd57fed', 'COMPÉTENCES À ACQUÉRIR OU À DÉVELOPPER EN VUE D''UNE ÉVOLUTION PROFESSIONNELLE', 9, 2, null, '2020-09-10 17:05:33.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (13, '2_5f5a40ca81b99', 'AUTRES PERSPECTIVES DE FORMATION', 10, 2, null, '2020-09-10 17:05:46.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (14, '2_601a5ffa9a00d', 'UTILISATION DU COMPTE PERSONNEL DE FORMATION (CPF)', 12, 2, null, '2021-02-03 09:34:02.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (15, '2_60744315c477c', 'Activités de transfert de compétences ou d''accompagnement des agents', 2, 2, null, '2021-04-12 14:54:45.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (16, '2_6074464c768c6', 'Formations demandées sur la période écoulée et non suivies', 4, 2, null, '2021-04-12 15:08:28.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (17, '2_607447155a1f3', 'Formation continue (demandée pour la nouvelle période)', 5, 2, null, '2021-04-12 15:11:49.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (18, '2_60744aa24dcab', 'Formation de préparation à un concours ou examen professionnel', 6, 2, null, '2021-04-12 15:26:58.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (19, '2_60744b5f4443d', 'Formations pour construire un projet personnel à caractère professionnel', 7, 2, null, '2021-04-12 15:30:07.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (20, '2_60744ea79edce', 'Règlementation', 11, 2, null, '2021-04-12 15:44:07.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (21, '2_6218b5ee5b3e5', 'Bilan des formations suivies sur la période écoulée', 3, 2, 'CREF;Bilan', '2022-02-25 11:56:46.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (22, '1_621f65bb938e6', 'Compléments d''informations', 1, 1, null, '2022-03-02 13:40:27.000000', 0);
INSERT INTO unicaen_autoform_categorie (id, code, libelle, ordre, formulaire, mots_clefs, histo_creation, histo_createur_id) VALUES (23, '2_62289f96346ec', 'Informations complémentaires', 1, 2, null, '2022-03-09 13:37:42.000000', 0);


INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (1, 1, '1_1_5cab466f19d47', 'Compléments relatif aux missions du poste', '', 1, 'Textarea', null, '', 'CREP;missions;', '2019-04-08 15:02:39.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (2, 1, '1_1_5cab4698af8f9', 'Fonctions d’encadrement ou de conduite de projet :', '', 3, 'Label', null, '', null, '2019-04-08 15:03:20.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (3, 1, '1_1_5cab46a9a8177', 'SPACER', '', 2, 'Spacer', null, '', null, '2019-04-08 15:03:37.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (4, 1, '1_1_5cab46ef716d3', 'l''agent assume des fonctions de conduite de projet', '', 4, 'Checkbox', null, '', 'CREP;projet', '2019-04-08 15:04:47.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (5, 1, '1_1_5cab470a5fa12', 'l''agent assume des fonctions d''encadrement', '', 5, 'Checkbox', null, '', 'CREP;encadrement', '2019-04-08 15:05:14.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (6, 1, '1_1_5cab47308ce7e', 'Nombre d’agents encadrés de catégorie A', '', 7, 'Number', null, '', 'CREP;encadrement_A;', '2019-04-08 15:05:52.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (33, 1, '1_1_5ea7f8d6c78fe', 'Nombre d’agents encadrés de catégorie B', '', 8, 'Number', null, '', 'CREP;encadrement_B;', '2020-04-28 11:35:18.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (34, 1, '1_1_5ea7f8e36f50b', 'Nombre d’agents encadrés de catégorie C', '', 9, 'Number', null, '', 'CREP;encadrement_C;', '2020-04-28 11:35:31.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (35, 1, '1_1_5ea7f8f881344', 'SPACER', '', 6, 'Spacer', null, '', null, '2020-04-28 11:35:52.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (7, 2, '1_2_5cab479a1c8d1', 'Rappel des objectifs d’activités attendus', 'Merci d''indiquer si des démarches ou moyens spécifiques ont été mis en oeuvre pour atteindre ces objectifs', 1, 'Textarea', null, '', 'CREP;2.1;', '2019-04-08 15:07:38.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (8, 2, '1_2_5cab47aedf9d6', 'Événements survenus au cours de la période écoulée', 'Nouvelles orientations, réorganisations, nouvelles méthodes, nouveaux outils, ... ', 2, 'Textarea', null, '', 'CREP;2.2;', '2019-04-08 15:07:58.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (10, 3, '1_3_5d13794397825', 'Critères d’appréciation', '', 1, 'Label', null, '', null, '2019-06-26 15:55:15.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (11, 3, '1_3_5d1379525e0fb', 'Les compétences professionnelles et technicité', 'Maîtrise technique ou expertise scientifique du domaine d''activité, connaissance de l''environnement professionnel et capacité à s''y situer, qualité d''expression écrite, qualité d''expression orale, ...', 3, 'Textarea', null, '', 'CREP;3.1.1;', '2019-06-26 15:55:30.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (12, 3, '1_3_5d13795e6ae93', 'La contribution à l’activité du service', 'Capacité à partager l''information, à transférer les connaissances et à rendre compte, capacité à s''invertir dans des projets, sens du service public et conscience professionnelle, capacité à respecter l''organisation collective du travail, ...', 4, 'Textarea', null, '', 'CREP;3.1.2;', '2019-06-26 15:55:42.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (13, 3, '1_3_5d13796aafdec', 'Les capacités professionnelles et relationnelles', 'Autonomie, discernement et sens des initiatives dans l''exercice de ses attributions, capacité d''adaptation, capacité à travailler en équipe, etc.', 5, 'Textarea', null, '', 'CREP;3.1.3;', '2019-06-26 15:55:54.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (14, 3, '1_3_5d137977d1aa2', 'Le cas échéant, aptitude à l’encadrement et/ou à la conduite de projets', 'Capacité d''organisation et de pilotage, aptitude à la conduite de projets, capacité à déléguer, aptitude au dialogue, à la communication et à la négociation, ... ', 6, 'Textarea', null, '', 'CREP;3.1.4;', '2019-06-26 15:56:07.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (15, 3, '1_3_5d1379913541d', 'SPACER', '', 7, 'Spacer', null, '', null, '2019-06-26 15:56:33.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (16, 3, '1_3_5d1379989b6a2', 'Appréciation générale sur la valeur professionnelle, la manière de servir et la réalisation des objectifs', '', 8, 'Label', null, '', null, '2019-06-26 15:56:40.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (17, 3, '1_3_5d1379c58b19d', 'Compétences professionnelles et technicité', '', 10, 'Select', null, 'à acquerir;à développer;maîtrise;expert', 'CREP;3.2.1;', '2019-06-26 15:57:25.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (18, 3, '1_3_5d1379d3c13fb', 'Contribution à l’activité du service', '', 12, 'Select', null, 'à acquerir;à développer;maîtrise;expert', 'CREP;3.2.2;', '2019-06-26 15:57:39.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (19, 3, '1_3_5d1379e00e967', 'Capacités professionnelles et relationnelles', '', 14, 'Select', null, 'à acquerir;à développer;maîtrise;expert', 'CREP;3.2.3;', '2019-06-26 15:57:52.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (20, 3, '1_3_5d1379efc6c51', 'Aptitude à l’encadrement et/ou à la conduite de projets (le cas échéant)', '', 16, 'Select', null, 'à acquerir;à développer;maîtrise;expert', 'CREP;3.2.4;', '2019-06-26 15:58:07.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (21, 3, '1_3_5d137a74c3969', 'SPACER', '', 17, 'Spacer', null, '', null, '2019-06-26 16:00:20.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (22, 3, '1_3_5d137a7f1e54d', 'Réalisation des objectifs de l’année écoulée', '', 18, 'Textarea', null, '', 'CREP;realisation;', '2019-06-26 16:00:31.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (23, 3, '1_3_5d137a8a5954e', 'Appréciation littérale', '', 19, 'Textarea', null, '', 'CREP;appreciation;', '2019-06-26 16:00:42.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (41, 3, '1_3_5f5a3e0e540dc', 'ATTENTION', 'L’évaluateur retient, pour apprécier la valeur professionnelle des agents au cours de l''entretien professionnel, les critères annexés à l’arrêté ministériel et qui sont adaptés à la nature des tâches qui leur sont confiées, au niveau de leurs responsabilités et au contexte professionnel. Pour les infirmiers et les médecins seules les parties 2, 3 et 4 doivent être renseignées en tenant compte des limites légales et règlementaires en matière de secret professionnel imposées à ces professionnels', 2, 'Label', null, '', null, '2020-09-10 16:54:06.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (72, 3, '1_3_60743c9141737', 'empty', '1. Les compétences professionnelles et technicités : maîtrise technique ou expertise scientifique du domaine d''activité, connaissance de l''environnement professionnel et capacité à s''y situer, qualité d''expression écrite, qualité orale, ...', 9, 'Label', null, '', null, '2021-04-12 14:26:57.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (73, 3, '1_3_60743d1605955', 'empty', '2. La contribution à l''activité du service : capacité à partager l''information, à transférer les connaissances et à rendre compte, capacité à s''investir dans des projets, sens du service public et conscience professionnelle, capacité à respecter l''organisation collective du travail, ...', 11, 'Label', null, '', null, '2021-04-12 14:29:10.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (74, 3, '1_3_60743d71e5ca5', 'empty', '3. Les capacités professionnelles et relationnelles : autonomie, discernement et sens des initiative dans l''exercice de ses attributions, capacité d''adaptation, capacité à travailler en équipe, ... ', 13, 'Label', null, '', null, '2021-04-12 14:30:41.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (75, 3, '1_3_60743dcea4a1d', 'empty', '4. Le cas échéant, aptitude à l''encadrement et/ou à la conduite de projets : capacité d''organisation et de pilotage, aptitude à la conduite de projets, capacité à déléguer, aptitude au dialogue, à la communication et à la négociation, ...', 15, 'Label', null, '', null, '2021-04-12 14:32:14.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (76, 3, '1_4_60743e9db5ee9', 'ATTENTION', 'Merci d''apporter un soin particulier à cette appréciation qui constitue un critère pour l''avancement de grade des agents et pourra être repris dans les rapports liés à la promotion de grade.', 999, 'Label', null, '', null, '2021-04-12 14:35:41.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (24, 4, '1_4_5d137aae286e4', 'Autres', '', 2, 'Textarea', null, '', 'CREP;exppro_2', '2019-06-26 16:01:18.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (36, 4, '1_4_5ec4f4d8f18c7', 'Missions spécifiques', '', 1, 'Multiple', null, 'Référent formation professionnelle (FC);Référent formation intiale;Membre de jury;Référent assistant prévention;Mandat électif', 'CREP;exppro_1', '2020-05-20 11:14:00.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (25, 5, '1_5_5d137ac5c2f5f', 'Objectifs d''activités attendus', '', 1, 'Textarea', null, '', 'CREP;5.1;', '2019-06-26 16:01:41.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (26, 5, '1_5_5d137ad379d3c', 'Démarche envisagée, et moyens à prévoir dont la formation, pour faciliter l’atteinte des objectifs', '', 2, 'Textarea', null, '', 'CREP;5.2;', '2019-06-26 16:01:55.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (27, 6, '1_6_5d137b04786f3', 'Évolution des activités (préciser l''échéance envisagée)', '', 1, 'Textarea', null, '', 'CREP;6.1;', '2019-06-26 16:02:44.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (28, 6, '1_6_5d137b0c3ad1a', 'Évolution de carrière', '', 2, 'Textarea', null, '', 'CREP;6.2;', '2019-06-26 16:02:52.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (40, 6, '1_6_5f5a3b70e0ea9', 'ATTENTION', 'À compléter obligatoirement pour les agents ayant atteint le dernier échelon de leur grade depuis au moins trois ans au 31/12 de l''année au titre de la présente évaluation, et lorsque la nomination à ce grade ne résulte pas d''un avancement de grade ou d''un accès à celui-ci par concours ou promotion internes (Décret n° 2017-722 du 02/05/2017  relatif aux modalités d''appréciation de la valeur et de l''expérience professionnelles de certains fonctionnaires éligibles à un avancement de grade)', 3, 'Label', null, '', null, '2020-09-10 16:42:56.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (42, 10, '1_10_5f5a3ea78845a', 'Recours spécifique (Article 6 du décret n° 2010-888 du 28 juillet 2010)', 'L’agent peut saisir l’autorité hiérarchique d’une demande de révision de son compte rendu d’entretien professionnel. Ce recours hiérarchique doit être exercé dans le délai de 15 jours francs suivant la notification du compte rendu d’entretien professionnel. La réponse de l’autorité hiérarchique doit être notifiée dans un délai de 15 jours francs à compter de la date de réception de la demande de révision du compte rendu de l’entretien professionnel.  A compter de la date de la notification de cette réponse l’agent peut saisir la commission administrative paritaire dans un délai d''un mois. Le recours hiérarchique est le préalable obligatoire à la saisine de la CAP.', 1, 'Label', null, '', null, '2020-09-10 16:56:39.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (43, 10, '1_10_5f5a3ec4ad926', 'Recours de droit commun', 'L’agent qui souhaite contester son compte rendu d’entretien professionnel peut exercer un recours de droit commun devant le juge administratif dans les 2 mois suivant la notification du compte rendu de l’entretien professionnel, sans exercer de recours gracieux ou hiérarchique (et sans saisir la CAP) ou après avoir exercé un recours administratif de droit commun (gracieux ou hiérarchique). Il peut enfin saisir le juge administratif à l’issue de la procédure spécifique définie par l’article 6 précité. Le délai de recours contentieux, suspendu durant cette procédure, repart à compter de la notification de la décision finale de l’administration faisant suite à l’avis rendu par la CAP. ', 2, 'Label', null, '', null, '2020-09-10 16:57:08.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (53, 11, '2_11_5f69f3b4b7f67', 'Formation 2', '', 3, 'Formation', null, '', null, '2020-09-22 14:53:08.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (54, 11, '2_11_5f69f23237357', 'Remarque', 'Laisser vide si aucune formation n''est souhaitée.', 1, 'Label', null, '', null, '2020-09-22 14:46:42.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (55, 11, '2_11_5f69f1b1d31bd', 'Formation 1', '', 2, 'Formation', null, '', null, '2020-09-22 14:44:33.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (59, 11, '2_11_5f6af8177cab2', 'Formation 3', '', 4, 'Formation', null, '', null, '2020-09-23 09:24:07.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (60, 11, '2_11_5f6af822d86a4', 'Formation 4', '', 5, 'Formation', null, '', null, '2020-09-23 09:24:18.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (61, 11, '2_11_5f6af8349f9ed', 'Formation 5', '', 6, 'Formation', null, '', null, '2020-09-23 09:24:36.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (56, 12, '2_12_5f69f3b4b7f67', 'Formation 2', 'Échéance', 3, 'Formation', null, '1 an;2 ans;3 ans;plus de 3 ans', null, '2020-09-22 14:53:08.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (57, 12, '2_12_5f69f23237357', 'Remarque', 'Laisser vide si aucune formation n''est souhaitée.', 1, 'Label', null, '', null, '2020-09-22 14:46:42.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (58, 12, '2_12_5f69f1b1d31bd', 'Formation 1', 'Échéance', 2, 'Formation', null, '1 an;2 ans;3 ans;plus de 3 ans', null, '2020-09-22 14:44:33.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (62, 12, '2_12_5f6af84373134', 'Formation 3', 'Échéance', 4, 'Formation', null, '1 an;2 ans;3 ans;plus de 3 ans', null, '2020-09-23 09:24:51.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (63, 12, '2_12_5f6af84b4d1bb', 'Formation 4', 'Échéance', 5, 'Formation', null, '1 an;2 ans;3 ans;plus de 3 ans', null, '2020-09-23 09:24:59.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (64, 12, '2_12_5f6af855dab0f', 'Formation 5', 'Échéance', 6, 'Formation', null, '1 an;2 ans;3 ans;plus de 3 ans', null, '2020-09-23 09:25:09.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (50, 13, '2_13_5f69f1b1d31bd', 'Formation 1', 'Échéance', 2, 'Formation', null, '1 an;2 ans;3 ans;plus de 3 ans', null, '2020-09-22 14:44:33.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (51, 13, '2_13_5f69f23237357', 'Remarque', 'Laisser vide si aucune formation n''est souhaitée.', 1, 'Label', null, '', null, '2020-09-22 14:46:42.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (67, 13, '2_13_5f6af8708f871', 'Formation 5', 'Échéance', 6, 'Formation', null, '1 an;2 ans;3 ans;plus de 3 ans', null, '2020-09-23 09:25:36.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (52, 13, '2_13_5f69f3b4b7f67', 'Formation 2', 'Échéance', 3, 'Formation', null, '1 an;2 ans;3 ans;plus de 3 ans', null, '2020-09-22 14:53:08.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (66, 13, '2_13_5f6af868b5381', 'Formation 4', 'Échéance', 5, 'Formation', null, '1 an;2 ans;3 ans;plus de 3 ans', null, '2020-09-23 09:25:28.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (65, 13, '2_13_5f6af85ea3849', 'Formation 3', 'Échéance', 4, 'Formation', null, '1 an;2 ans;3 ans;plus de 3 ans', null, '2020-09-23 09:25:18.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (71, 14, '2_14_602507249f8e3', 'Solde du CPF en heure', '', 2, 'Number', null, '', null, '2021-02-11 11:29:56.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (68, 14, '2_14_601a6016540c5', 'CPF', 'Vous pouvez retrouver votre CPF  en suivant ce lien <a href="https://www.moncompteformation.gouv.fr/">https://www.moncompteformation.gouv.fr/</a>', 1, 'Label', null, '', null, '2021-02-03 09:34:30.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (70, 14, '2_14_601a6084ae632', 'L''agent envisage-t''il de mobiliser son CPF cette année', '', 3, 'Select', null, 'Oui;Non', null, '2021-02-03 09:36:20.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (77, 15, '2_15_6074438447701', 'Activités ', '', 1, 'Multiple', null, 'Formateur;Tuteur/Mentor;Président/Vice-Président de jury;Membre de jury', 'CREF;1.1', '2021-04-12 14:56:36.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (79, 15, '2_15_60744459b46fe', 'Formations dispensées', 'Préciser les activités de formation encadrée par l''agent hors des activités de sa fiche de poste.', 2, 'Label', null, '', null, '2021-04-12 15:00:09.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (122, 15, '2_15_607548067e991', 'Formation 4', '', 6, 'Multiple_champs_paramètrables', null, 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 'CREF;1.2', '2021-04-13 09:28:06.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (121, 15, '2_15_607547f62955e', 'Formation 3', '', 5, 'Multiple_champs_paramètrables', null, 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 'CREF;1.2', '2021-04-13 09:27:50.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (119, 15, '2_15_6075478d8c15d', 'Formation 1', '', 3, 'Multiple_champs_paramètrables', null, 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 'CREF;1.2', '2021-04-13 09:26:05.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (123, 15, '2_15_6075481869150', 'Formation 5', '', 7, 'Multiple_champs_paramètrables', null, 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 'CREF;1.2', '2021-04-13 09:28:24.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (120, 15, '2_15_607547e8d6b8b', 'Formation 2', '', 4, 'Multiple_champs_paramètrables', null, 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 'CREF;1.2', '2021-04-13 09:27:36.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (115, 16, '2_16_60753fe993b28', 'Formation 2', '', 3, 'Multiple Text', null, 'Action de formation;Nombre d''heures', 'CREF;3', '2021-04-13 08:53:29.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (117, 16, '2_16_6075400784143', 'Formation 4', '', 5, 'Multiple Text', null, 'Action de formation;Nombre d''heures', 'CREF;3', '2021-04-13 08:53:59.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (83, 16, '2_16_6074468e37a47', 'Formations demandées lors de l''entretien précédent', '', 1, 'Label', null, '', null, '2021-04-12 15:09:34.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (118, 16, '2_16_60754015d1010', 'Formation 5', '', 6, 'Multiple Text', null, 'Action de formation;Nombre d''heures', 'CREF;3', '2021-04-13 08:54:13.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (116, 16, '2_16_60753ff8a5d43', 'Formation 3', '', 4, 'Multiple Text', null, 'Action de formation;Nombre d''heures', 'CREF;3', '2021-04-13 08:53:44.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (113, 16, '2_16_60753fb0ec093', 'Formation 1', '', 2, 'Multiple Text', null, 'Action de formation;Nombre d''heures', 'CREF;3', '2021-04-13 08:52:32.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (134, 17, '2_17_60755158e867c', 'Formation 3', '', 14, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 'CREF;4.2', '2021-04-13 10:07:52.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (88, 17, '2_17_607448d43441d', 'Type 3 : formations d''acquisition de qualifications nouvelles', 'Favoriser sa culture professionnelle ou son niveau d''expertise, approfondir ses connaissances dans un domaine qui ne relève pas de son activité actuelle, pour se préparer à de nouvelles fonctions, surmonter des difficultés sur son poste actuel.', 3, 'Label', null, '', null, '2021-04-12 15:19:16.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (125, 17, '2_17_60754efa821b5', 'Formation 1', '', 5, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', 'CREF;4.1', '2021-04-13 09:57:46.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (86, 17, '2_17_607447d31b71e', 'Type 1 : formations d''adaptation immédiate au poste de travail', 'Stage d''adaptation à l''emploi, de prise de poste après une mutation ou une promotion', 1, 'Label', null, '', null, '2021-04-12 15:14:59.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (87, 17, '2_17_60744863be8e2', 'Type 2 : formations à l''évolution des métiers ou des postes de travail', 'Approfondir ses compétences techniques, actualiser ses savoir-faire professionnels, acquérir des fondamentaux ou remettre à niveau ses connaissances pour se préparer à des changements fortement probables, induits par la mise en place d''une réforme, d''un nouveau système d''information ou de nouvelles techniques.', 2, 'Label', null, '', null, '2021-04-12 15:17:23.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (93, 17, '2_17_6074499de3661', 'SPACER', '', 4, 'Spacer', null, '', null, '2021-04-12 15:22:37.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (135, 17, '2_17_6075516c4fe19', 'Formation 4', '', 15, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 'CREF;4.2', '2021-04-13 10:08:12.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (126, 17, '2_17_6075500f85b1a', 'Formation 2', '', 6, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', 'CREF;4.1', '2021-04-13 10:02:23.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (132, 17, '2_17_607551207d6f6', 'Formation 1', '', 12, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 'CREF;4.2', '2021-04-13 10:06:56.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (131, 17, '2_17_607550eeb08f5', 'S', '', 10, 'Spacer', null, '', null, '2021-04-13 10:06:06.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (130, 17, '2_17_6075509516253', 'Formation 5', '', 9, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', 'CREF;4.1', '2021-04-13 10:04:37.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (133, 17, '2_17_6075514d57c98', 'Formation 2', '', 13, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 'CREF;4.2', '2021-04-13 10:07:41.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (128, 17, '2_17_60755072d7b3e', 'Formation 3', '', 7, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', 'CREF;4.1', '2021-04-13 10:04:02.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (94, 17, '2_17_607449d5be974', 'Actions de formations demandées par l''agent et recueillant un avis défavorable du supérieur hiérarchique direct', 'N.B. : l''avis défavorable émis par le supérieur hiérarchique direct conduisant l''entretien ne préjuge pas de la suite donnée à la demande de formation', 11, 'Label', null, '', null, '2021-04-12 15:23:33.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (129, 17, '2_17_6075508684e5a', 'Formation 4', '', 8, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', 'CREF;4.1', '2021-04-13 10:04:22.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (136, 17, '2_17_6075518c15cb4', 'Formation 5', '', 16, 'Multiple_champs_paramètrables', null, 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 'CREF;4.2', '2021-04-13 10:08:44.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (97, 18, '2_18_60744afe9f52a', 'empty', 'Pour acquérir les bases et connaissances générales utiles à un concours, dans le cadre de ses perspectives professionnelles pour préparer un changement d''orientation pouvant impliquer le départ de son ministère ou de la fonction publique', 1, 'Label', null, '', null, '2021-04-12 15:28:30.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (98, 18, '2_18_60744b38c89a6', 'Libellé des formations', '', 2, 'Textarea', null, '', 'CREF;5', '2021-04-12 15:29:28.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (107, 19, '2_19_60744d51db3cd', 'Entretien de carrière', 'Pour évaluer son parcours et envisager des possibilités d''évolution professionnelle à 2~3 ans', 9, 'Label', null, '', 'CREF;6;ecarriere', '2021-04-12 15:38:25.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (109, 19, '2_19_60744dbef393b', 'Bilan de carrière', 'Pour renouveler ses perspectives professionnelles à 4~5 ans ou préparer un projet de seconde carrière ', 11, 'Label', null, '', 'CREF;6;bcarriere', '2021-04-12 15:40:14.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (105, 19, '2_19_60744d0f0925f', 'Congé de formation professionnelle', 'Pour suivre une formation', 7, 'Label', null, '', 'CREF;6;conge', '2021-04-12 15:37:19.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (100, 19, '2_19_60744bd69fb44', 'Bilan de compétences', 'Pour permettre une mobilité fonctionnelle ou géographique', 3, 'Label', null, '', 'CREF;6;bilan', '2021-04-12 15:32:06.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (104, 19, '2_19_60744cee1b307', 'empty', '', 6, 'Textarea', null, '', null, '2021-04-12 15:36:46.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (106, 19, '2_19_60744d1729cb0', 'empty', '', 8, 'Textarea', null, '', null, '2021-04-12 15:37:27.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (101, 19, '2_19_60744c902899b', 'Période de professionnalisation', 'Pour prévenir des risques d''inadaptation à l''évolution des méthodes et techniques, pour favoriser l''accès à des emplois exigeant des compétences nouvelles ou qualifications différentes, pour accéder à un autre corps ou cadre d''emplois, pour les agents qui reprennent leur activité professionnelle après un congé maternité ou parental.', 5, 'Label', null, '', 'CREF;6;periode', '2021-04-12 15:35:12.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (108, 19, '2_19_60744d68d6786', 'empty', '', 10, 'Textarea', null, '', null, '2021-04-12 15:38:48.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (110, 19, '2_19_60744dceac729', 'empty', '', 12, 'Textarea', null, '', null, '2021-04-12 15:40:30.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (99, 19, '2_19_60744bb9411f9', 'VAE : Validation des acquis de l''expérience', 'Pour obtenir un diplôme, d''un titre ou d''une certification inscrite au répertoire national des certifications professionnelles', 1, 'Label', null, '', 'CREF;6;VAE', '2021-04-12 15:31:37.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (103, 19, '2_19_60744cdce435b', 'empty', '', 4, 'Textarea', null, '', null, '2021-04-12 15:36:28.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (102, 19, '2_19_60744cc11f86e', 'empty', '', 2, 'Textarea', null, '', null, '2021-04-12 15:36:01.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (111, 20, '2_20_60744f2c924a7', 'empty', 'Décret n°2007-1470 du 15 octobre 2007 relatif à la formation professionnelle tout au long de la vie des fonctionnaires de l''État <br> Article 5 :  <ul><li>  Le compte rendu de l''entretien de formation est établi sous la responsabilité du supérieur  hiérarchique.  </li><li>  Les objectifs de formation proposés pour l''agent y sont inscrits.  </li><li>  Le fonctionnaire en reçoit communication et peut y ajouter ses observations.  </li><li>  Ce compte rendu ainsi qu''une fiche retraçant les actions de formation auxquelles le fonctionnaire a participé sont versés à son dossier.  </li><li>  Les actions conduites en tant que formateur y figurent également.  </li><li>  Le fonctionnaire est informé par son supérieur hiérarchique des suites données à son entretien de formation.  </li><li>  Les refus opposés aux demandes de formation présentées à l''occasion de l''entretien de formation sont motivés. </li></ul>', 1, 'Label', null, '', null, '2021-04-12 15:46:20.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (1001, 21, '2_21_627d2a0187a25', 'Autres formations', '', 7, 'Textarea', null, '', 'CREF;AutresFormations', '2022-05-12 17:38:41.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (139, 21, '2_21_6218b76610c37', 'Formation 2', '', 3, 'Multiple_champs_paramètrables', null, 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 'CREF;2', '2022-02-25 12:03:02.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (141, 21, '2_21_6218b78c7739e', 'Formation 4', '', 5, 'Multiple_champs_paramètrables', null, 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 'CREF;2', '2022-02-25 12:03:40.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (138, 21, '2_21_6218b75939b77', 'Formation 1', '', 2, 'Multiple_champs_paramètrables', null, 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 'CREF;2', '2022-02-25 12:02:49.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (137, 21, '2_21_6218b697c42ef', 'Sessions réalisées du 1er septembre au 31 août de l''année de la campagne en cours', '', 1, 'Label', null, '', null, '2022-02-25 11:59:35.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (140, 21, '2_21_6218b77fb5e18', 'Formation 3', '', 4, 'Multiple_champs_paramètrables', null, 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 'CREF;2', '2022-02-25 12:03:27.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (142, 21, '2_21_6218b799a5357', 'Formation 5', '', 6, 'Multiple_champs_paramètrables', null, 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 'CREF;2', '2022-02-25 12:03:53.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (149, 22, '1_22_621f671898bfc', 'Emploi-type de l''agent', '', 6, 'Text', null, '', 'CREP;emploi-type;', '2022-03-02 13:46:16.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (147, 22, '1_22_621f66ec87b1e', 'Remarque', 'Toutes les fiches de poste ne sont pas encore présentes dans EMC2. Merci de compléter les informations suivantes si l''agent ou le responsable de l''entretien n''ont pas de fiche de poste EMC2.', 4, 'Label', null, '', null, '2022-03-02 13:45:32.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (143, 22, '1_22_621f660627418', 'Remarque', 'Les informations suivantes ne sont pas encore disponible dans le référentiel OCTOPUS. Merci de les compléter afin de remonter celle-ci dans le compte-rendu d''entretien professionnel (CREP).', 1, 'Label', null, '', null, '2022-03-02 13:41:42.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (151, 22, '1_22_621f98213fe4e', 'Date affectation de l''agent', '', 2, 'Text', null, '', 'CREP;affectation_date;', '2022-03-02 17:15:29.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (150, 22, '1_22_621f673370344', 'Intitulé du poste du responsable de l''entretien', '', 7, 'Text', null, '', null, '2022-03-02 13:46:43.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (146, 22, '1_22_621f66a5a2f7e', 'SPACER', '', 3, 'Spacer', null, '', null, '2022-03-02 13:44:21.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (148, 22, '1_22_621f670a1f912', 'Intitulé du poste de l''agent', '', 5, 'Text', null, '', null, '2022-03-02 13:46:02.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (152, 23, '2_23_62289ff6f20b6', 'Remarque', 'Les informations suivantes ne sont pas encore disponible dans le référentiel OCTOPUS. Merci de les compléter afin de remonter celle-ci dans le compte-rendu de formation (CREF).', 1, 'Label', null, '', null, '2022-03-09 13:39:18.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (157, 23, '2_23_6228a12b4f737', 'Date du dernier entretien professionnel', '', 6, 'Text', null, '', 'CREF;precedent', '2022-03-09 13:44:27.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (156, 23, '2_23_6228a0db1e483', 'Remarque', 'Tous les agents n''ont pas participé à la campagne précédente d''entretien professionnel sur EMC2. Pour ceux-ci veuillez compléter les informations suivantes.', 5, 'Label', null, '', null, '2022-03-09 13:43:07.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (155, 23, '2_23_6228a0c290261', 'SPACER', '', 4, 'Spacer', null, '', null, '2022-03-09 13:42:42.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (154, 23, '2_23_6228a0ae78c65', 'L''agent envisage-t''il de mobiliser son CPF cette année', '', 3, 'Select', null, 'Oui;Non', 'CREF;CPF_mobilisation', '2022-03-09 13:42:22.000000', 0);
INSERT INTO unicaen_autoform_champ (id, categorie, code, libelle, texte, ordre, element, balise, options, mots_clefs, histo_creation, histo_createur_id) VALUES (153, 23, '2_23_6228a0589d84b', 'Solde du CPF', '', 2, 'Text', null, '', 'CREF;CPF_solde', '2022-03-09 13:40:56.000000', 0);

-- role ----------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Supérieur·e hiérarchique direct·e', 'Supérieur·e hiérarchique direct·e', false, null, null, true, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Délégué·e pour entretien professionnel', 'Délégué·e pour entretien professionnel', false, null, null, true, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Autorité hiérarchique', 'Autorité hiérarchique', false, null, null, true, null);


