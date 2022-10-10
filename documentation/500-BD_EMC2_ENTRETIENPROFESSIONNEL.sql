
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
