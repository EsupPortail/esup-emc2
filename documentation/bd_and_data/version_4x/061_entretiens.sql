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

-- EVENEMENT ---------------------------------------------

INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('rappel_entretienpro', 'rappel_entretienpro', 'rappel_entretienpro', 'entretien', null);
INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('rappel_campagne_autorite', 'Rappel avancement campagne EP [Autorité hiérachique]', 'Rappel avancement campagne EP [Autorité hiérachique]', 'campagne', 'P4W');
INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('rappel_campagne_superieur', 'Rappel avancement campagne EP [Supérieur·e hiérachique]', 'Rappel avancement campagne EP [Supérieur·e hiérachique]', 'campagne', 'P2W');
INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('rappel_pas_observation_entretienpro', 'rappel_pas_observation_entretienpro', 'rappel_pas_observation_entretienpro', 'entretien', null);

-- PARAMETRES --------------------------------------------

INSERT INTO unicaen_parametre_categorie (code, libelle, ordre, description)
VALUES ('ENTRETIEN_PROFESSIONNEL', 'Paramètres liés aux entretiens professionnels', 500, null);
INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, ORDRE) AS (
    SELECT 'MAIL_LISTE_DAC', 'Adresse électronique de la liste de liste de diffusion pour les DAC', null, 'String', 10 UNION
    SELECT 'MAIL_LISTE_BIATS', 'Adresse électronique de la liste de liste de diffusion pour le personnel', null, 'String', 20 UNION
    SELECT 'DELAI_ACCEPTATION_AGENT', 'Délai d''accepation de l''entretien par l''agent (en jours)', null, 'Number', 30 UNION
    SELECT 'INTRANET_DOCUMENT', 'Lien vers les documents associés à l''entretien professionnel', null, 'String', 100 UNION
    SELECT 'TEMOIN_AFFECTATION', 'Temoin d''affectation à considérer', '<p>les temoins sont s&eacute;parer par des ;</p>', 'String', 100 UNION
    SELECT 'TEMOIN_EMPLOITYPE', 'Filtres associés aux emploi-types', null, 'String', 250
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES, d.ORDRE
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'ENTRETIEN_PROFESSIONNEL';

-- ETATS -------------------------------------------------------

INSERT INTO unicaen_etat_categorie (code, libelle, icone, couleur, ordre) VALUES ('ENTRETIEN_PROFESSIONNEL', 'États associés aux entretiens professionnels', 'fas fa-briefcase', '#75507b', 200);
INSERT INTO unicaen_etat_type(categorie_id, code, libelle, icone, couleur, ordre)
WITH e(code, libelle, icone, couleur, ordre) AS (
    SELECT 'ENTRETIEN_ACCEPTATION', 'En attente confirmation de l’agent', 'fas fa-user-clock', '#729fcf', 10 UNION
    SELECT 'ENTRETIEN_ACCEPTER', 'Entretien accepté par l''agent', 'fas fa-user-check', '#3465a4', 20 UNION
    SELECT 'ENTRETIEN_VALIDATION_RESPONSABLE', 'Validation du responsable de l''entretien professionnel', 'fas fa-user', '#f57900', 30 UNION
    SELECT 'ENTRETIEN_VALIDATION_OBSERVATION', 'Expression des observations faite', 'far fa-comments', '#fcaf3e', 40 UNION
    SELECT 'ENTRETIEN_VALIDATION_HIERARCHIE', 'Validation de l''autorité hiérarchique', 'fas fa-user-tie', '#edd400', 50 UNION
    SELECT 'ENTRETIEN_VALIDATION_AGENT', 'Validation de l''agent',  'far fa-check-square', '#4e9a06', 60
)
SELECT et.id, e.code, e.libelle, e.icone, e.couleur, e.ordre
FROM e
JOIN unicaen_etat_categorie et ON et.CODE = 'ENTRETIEN_PROFESSIONNEL';

-- VALIDATIONS -------------------------------------------------

INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id, histo_modification, histo_modificateur_id, histo_destruction, histo_destructeur_id) VALUES
('ENTRETIEN_AGENT', 'Validation de l''entretien par l''agent', false, '2023-05-04 12:01:12.000000', 1, '2023-05-04 12:01:12.000000', 1, null, null),
('ENTRETIEN_RESPONSABLE', 'Validation de l''entretien par le responsable', false, '2023-05-04 12:01:33.000000', 1, '2023-05-04 12:01:33.000000', 1, null, null),
('ENTRETIEN_HIERARCHIE', 'Validation de l''entretien par l''autorité', false, '2023-05-04 12:01:54.000000', 1, '2023-05-04 12:01:54.000000', 1, null, null),
('ENTRETIEN_OBSERVATION', 'Validation des observations', false, '2023-05-04 12:02:09.000000', 1, '2023-05-04 12:02:09.000000', 1, null, null);

-- TEMPLATE ET MACRO -------------------------------------------

INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('CAMPAGNE#annee', 'Année associée à la campagne d''entretien professionnel', 'campagne', 'getAnnee');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('CAMPAGNE#datecirculaire', 'Date de la circulaire de la campagne', 'campagne', 'getDateCirculaireToString');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('CAMPAGNE#debut', 'Date de début de la campagne', 'campagne', 'getDateDebutToString');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('CAMPAGNE#fin', 'Date de fin de la campagne', 'campagne', 'getDateFinToString');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, namespace) VALUES (
    'CAMPAGNE_OUVERTURE_BIATSS',
    'Mail envoyé aux personnels lors de l''ouverture d''une campagne d''entretien professionnel',
    'mail',
    'Ouverture de la campagne d''entretien professionnel VAR[CAMPAGNE#annee]',
e'<p><span style="text-decoration: underline;">Objet :</span> ouverture de la campagne d\'entretien professionnel VAR[CAMPAGNE#annee] </p>
<p>Bonjour,</p>
<p>La campagne d\'entretien professionnel au titre de l\'année universitaire VAR[CAMPAGNE#annee] est ouverte (voir la circulaire du VAR[CAMPAGNE#datecirculaire]).</p>
<p>Vous recevrez prochainement une convocation par courrier électronique.</p>
<p>Pour tout renseignement complémentaire, vous pouvez contacter votre responsable hiérarchique.</p>
<p>Cordialement,</p>',
    'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps,namespace) VALUES (
    'CAMPAGNE_OUVERTURE_DAC',
    'Mail envoyé aux responsables de service lors de l''ouverture d''une campagne d''entretien professionnel',
    'mail',
    'Ouverture de la campagne d''entretien professionnel VAR[CAMPAGNE#annee]',
e'<p><span style="text-decoration: underline;">Objet :</span> ouverture de la campagne d\'entretien professionnel VAR[CAMPAGNE#annee] </p>
<p>Bonjour,</p>
<p>La campagne d\'entretien professionnel au titre de l\'année universitaire VAR[CAMPAGNE#annee] est ouverte (voir la circulaire du VAR[CAMPAGNE#datecirculaire]).</p>
<p>Le retour des comptes-rendus (CREP) est demandé pour le : VAR[CAMPAGNE#fin]</p>
<p>La DRH reste à votre disposition pour toute demande de renseignement complémentaire.</p>
<p>Cordialement</p>',
    'EntretienProfessionnel\Provider\Template');


INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#ActiviteService', null, 'entretien', 'toStringActiviteService');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#Agent', '<p>Affiche la d&eacute;nomination de l''agent passant son entretien professionnel</p>', 'entretien', 'toStringAgent');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#CompetencesPersonnelles', null, 'entretien', 'toStringCompetencesPersonnelles');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#CompetencesTechniques', null, 'entretien', 'toStringCompetencesTechniques');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#CREF_Champ', '<p>Retourne le contenu du champ (AutoForm) dans l''identification passe par les mots clefs passés en paramètre</p>', 'entretien', 'toStringCREF_Champ');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#CREF_Champs', '', 'entretien', 'toStringCREF_Champs');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#CREP_Champ', '<p>Retourne le contenu du champ (AutoForm) dans l''identification passe par les mots clefs passés en paramètre</p>', 'entretien', 'toStringCREP_Champ');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#CREP_encadrement', null, 'entretien', 'toString_CREP_encadrement');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#CREP_encadrementA', null, 'entretien', 'toString_CREP_encadrementA');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#CREP_encadrementB', null, 'entretien', 'toString_CREP_encadrementB');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#CREP_encadrementC', null, 'entretien', 'toString_CREP_encadrementC');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#CREP_experiencepro', null, 'entretien', 'toString_CREP_experiencepro');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#CREP_projet', null, 'entretien', 'toString_CREP_projet');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#date', '<p>Retourne la date de l''entretien professionnel</p>', 'entretien', 'toStringDate');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#EncadrementConduite', null, 'entretien', 'toStringEncadrementConduite');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#Id', '', 'entretien', 'getId');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#lieu', '<p>Retourne le lieu de l''entretien professionnel</p>', 'entretien', 'toStringLieu');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#ObservationEntretien', null, 'entretien', 'toStringObservationEntretien');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#ObservationPerspective', null, 'entretien', 'toStringObservationPerspective');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#ReponsableCorpsGrade', null, 'entretien', 'toStringReponsableCorpsGrade');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#ReponsableIntitlePoste', null, 'entretien', 'toStringReponsableIntitulePoste');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#ReponsableNomFamille', null, 'entretien', 'toStringReponsableNomFamille');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#ReponsableNomUsage', null, 'entretien', 'toStringReponsableNomUsage');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#ReponsablePrenom', null, 'entretien', 'toStringReponsablePrenom');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#ReponsableStructure', '<p>Affiche le libellé long d''affectation du responsable de l''entretien professionnel</p>', 'entretien', 'toStringReponsableStructure');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#responsable', null, 'entretien', 'toStringResponsable');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#Responsable', '<p>Retourne la d&eacute;nomination du responsable de l''entretien professionnel</p>', 'entretien', 'toStringResponsable');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#VALIDATION_AGENT', '', 'entretien', 'toStringValidationAgent');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#VALIDATION_AUTORITE', '', 'entretien', 'toStringValidationHierarchie');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('ENTRETIEN#VALIDATION_SUPERIEUR', '', 'entretien', 'toStringValidationResponsable');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('URL#EntretienAccepter', null, 'UrlService', 'getUrlEntretienAccepter');
INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('URL#EntretienRenseigner', null, 'UrlService', 'getUrlEntretienRenseigner');



INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('CREF - Compte rendu d''entretien de formation', '<p>..</p>', 'pdf', 'Entretien_formation_VAR[CAMPAGNE#annee]_VAR[AGENT#NomUsage]_VAR[AGENT#Prenom].pdf', e'<h1>Annexe C9 bis - Compte rendu d\'entretien de formation</h1>
<p><strong>Année : VAR[CAMPAGNE#annee]</strong></p>
<table style="width: 998.25px;">
<tbody>
<tr>
<th style="width: 482px; text-align: center;"><strong>AGENT</strong></th>
<th style="width: 465.25px; text-align: center;"><strong>SUPÉRIEUR·E HIÉRARCHIQUE DIRECT·E</strong></th>
</tr>
<tr>
<td style="width: 482px;">
<p>Nom d\'usage : VAR[AGENT#NomUsage]</p>
<p>Nom de famille : VAR[AGENT#NomFamille]</p>
<p>Prénom : VAR[AGENT#Prenom]</p>
<p>Date de naissance: VAR[AGENT#DateNaissance]</p>
<p>Corps-grade : VAR[AGENT#CorpsGrade]<strong><br /></strong></p>
<p> </p>
</td>
<td style="width: 465.25px;">
<p>Nom d\'usage : VAR[ENTRETIEN#ReponsableNomUsage]</p>
<p>Nom de famille : VAR[ENTRETIEN#ReponsableNomFamille]</p>
<p>Prénom : VAR[ENTRETIEN#ReponsablePrenom]</p>
<p>Corps-grade : VAR[ENTRETIEN#ReponsableCorpsGrade]</p>
<p>Intitulé de la fonction : VAR[ENTRETIEN#ReponsableIntitlePoste]VAR[ENTRETIEN#CREP_Champ|CREP;responsable_date]</p>
<p>Structure : VAR[ENTRETIEN#ReponsableStructure]</p>
</td>
</tr>
</tbody>
</table>
<p> </p>
<table style="width: 1002px;">
<tbody>
<tr>
<td style="width: 346.797px;">Date de l\'entretien de formation</td>
<td style="width: 659.203px;">VAR[ENTRETIEN#date]</td>
</tr>
<tr>
<td style="width: 346.797px;">Date du précédent entretien de formation</td>
<td style="width: 659.203px;">VAR[ENTRETIEN#CREF_Champ|CREF;precedent]</td>
</tr>
<tr>
<td style="width: 346.797px;">Solde des droits CPF au 1er janvier</td>
<td style="width: 659.203px;">VAR[ENTRETIEN#CREF_Champ|CREF;CPF_solde]</td>
</tr>
<tr>
<td style="width: 346.797px;">L\'agent envisage-t\'il de mobiliser son CPF cette annee ?</td>
<td style="width: 659.203px;">VAR[ENTRETIEN#CREF_Champ|CREF;CPF_mobilisation] </td>
</tr>
</tbody>
</table>
<h2>1. Description du poste occupé par l\'agent</h2>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 722px;">
<p>Structure : VAR[AGENT#AffectationStructure]</p>
<p>Intitulé du poste : VAR[AGENT#IntitulePoste]VAR[ENTRETIEN#CREP_Champ|CREP;agent_poste]</p>
<p>Date d\'affectation : VAR[ENTRETIEN#CREP_Champ|CREP;affectation_date]</p>
<p>Emploi type (cf. REME ou REFERENS) : VAR[AGENT#EmploiType] VAR[ENTRETIEN#CREP_Champ|CREP;emploi-type]</p>
<p>Positionnement du poste dans le structure : VAR[AGENT#AffectationStructureFine]</p>
<p>Quotité travaillée : VAR[AGENT#Quotite]</p>
<p>Quotité d\'affectation : VAR[AGENT#QuotiteAffectation]</p>
</td>
</tr>
<tr>
<td style="width: 722px;">
<p>Missions du postes :<br />VAR[AGENT#Missions]</p>
<p> VAR[ENTRETIEN#CREP_Champ|CREP;missions]</p>
</td>
</tr>
<tr>
<td style="width: 722px;">
<p>Le cas échéant, fonctions d\'encadrement ou de conduite de projet :</p>
<ul>
<li>l\'agent assume-t\'il des fonctions de conduite de projet ? VAR[ENTRETIEN#CREP_projet]</li>
<li>l\'agent  assume-t\'il des fonctions d\'encadrements ? VAR[ENTRETIEN#CREP_encadrement]<br />Si oui préciser le nombre d\'agents et leur répartition par catégorie : VAR[ENTRETIEN#CREP_encadrementA] A - VAR[ENTRETIEN#CREP_encadrementB] B - VAR[ENTRETIEN#CREP_encadrementC] C</li>
</ul>
</td>
</tr>
</tbody>
</table>
<h3>Activités de transfert de compétences ou d’accompagnement des agents</h3>
<table style="width: 726px;">
<tbody>
<tr>
<td style="width: 726px;">VAR[ENTRETIEN#CREF_Champ|CREF;1.1]</td>
</tr>
</tbody>
</table>
<h3>Formation dispensé par l\'agent</h3>
<table style="width: 730px;">
<tbody>
<tr>
<td style="width: 730px;">VAR[ENTRETIEN#CREF_Champs|CREF;1.2]</td>
</tr>
</tbody>
</table>
<h2>2. Bilan des formations suivies sur la période écoulée</h2>
<p>Session réalisée du 1er septembre au 31 aout de l\'année VAR[CAMPAGNE#annee]</p>
<table style="width: 726px;">
<tbody>
<tr>
<td style="width: 716px;">
<p>VAR[ENTRETIEN#CREF_Champs|CREF;2]</p>
<p>VAR[ENTRETIEN#CREF_Champs|CREF;AutresFormations]</p>
</td>
</tr>
</tbody>
</table>
<h2>3. Formations demandées sur la période écoulée et non suivies</h2>
<p>(Formation demandées lors de l\'entretien précédent)</p>
<table style="width: 737px;">
<tbody>
<tr>
<td style="width: 737px;">VAR[ENTRETIEN#CREF_Champs|CREF;3]</td>
</tr>
</tbody>
</table>
<h1>FORMATIONS DEMANDÉES POUR LA NOUVELLE PÉRIODE</h1>
<h2>4. Formation continue</h2>
<p><strong>Type 1 : formations d\'adaptation immédiate au poste de travail</strong><br />Stage d\'adaptation à l\'emploi, de prise de poste après une mutation ou une promotion</p>
<p><strong>Type 2 : formations à l\'évolution des métiers ou des postes de travail</strong><br />Approfondir ses compétences techniques, actualiser ses savoir-faire professionnels, acquérir des fondamentaux ou remettre à niveau ses connaissances pour se préparer à des changements fortement probables, induits par la mise en place d\'une réforme, d\'un nouveau système d\'information ou de nouvelles techniques.</p>
<p><strong>Type 3 : formations d\'acquisition de qualifications nouvelles</strong><br />Favoriser sa culture professionnelle ou son niveau d\'expertise, approfondir ses connaissances dans un domaine qui ne relève pas de son activité actuelle, pour se préparer à de nouvelles fonctions, surmonter des difficultés sur son poste actuel.</p>
<table>
<tbody>
<tr>
<td>VAR[ENTRETIEN#CREF_Champs|CREF;4.1]</td>
</tr>
</tbody>
</table>
<p><strong>Actions de formations demandées par l\'agent et recueillant un avis défavorable du supérieur hiérarchique direct</strong></p>
<table>
<tbody>
<tr>
<td>VAR[ENTRETIEN#CREF_Champs|CREF;4.2]</td>
</tr>
</tbody>
</table>
<p>N.B. : l\'avis défavorable émis par le supérieur hiérarchique direct conduisant l\'entretien ne préjuge pas de la suite donnée à la demande de formation</p>
<h2>5. Formation de préparation à un concours ou examen professionnel</h2>
<p>Pour acquérir les bases et connaissances générales utiles à un concours, dans le cadre de ses perspectives professionnelles pour préparer un changement d\'orientation pouvant impliquer le départ de son ministère ou de la fonction publique</p>
<table style="width: 723px;">
<tbody>
<tr>
<td style="width: 713px;">VAR[ENTRETIEN#CREF_Champs|CREF;5]</td>
</tr>
</tbody>
</table>
<h2>6. Formations pour construire un projet personnel à caractère professionnel</h2>
<p><strong>VAE : Validation des acquis de l\'expérience<br /></strong>Pour obtenir un diplôme, d\'un titre ou d\'une certification inscrite au répertoire national des certifications professionnelles</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;VAE]</td>
</tr>
</tbody>
</table>
<p><strong>Bilan de compétences</strong><br />Pour permettre une mobilité fonctionnelle ou géographique</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;bilan]</td>
</tr>
</tbody>
</table>
<p><strong>Période de professionnalisation<br /></strong>Pour prévenir des risques d\'inadaptation à l\'évolution des méthodes et techniques, pour favoriser l\'accès à des emplois exigeant des compétences nouvelles ou qualifications différentes, pour accéder à un autre corps ou cadre d\'emplois, pour les agents qui reprennent leur activité professionnelle après un congé maternité ou parental.</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;periode]</td>
</tr>
</tbody>
</table>
<p><strong>Congé de formation professionnelle</strong><br />Pour suivre une formation</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;conge]</td>
</tr>
</tbody>
</table>
<p><strong>Entretien de carrière<br /></strong>Pour évaluer son parcours et envisager des possibilités d\'évolution professionnelle à 2~3 ans</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;ecarriere]</td>
</tr>
</tbody>
</table>
<p><strong>Bilan de carrière<br /></strong>Pour renouveler ses perspectives professionnelles à 4~5 ans ou préparer un projet de seconde carrière</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;bcarriere]</td>
</tr>
</tbody>
</table>
<h2>7. Signature du supérieur·e hiérarchique direct·e</h2>
<table style="width: 648px;">
<tbody>
<tr>
<td style="width: 648px;"> Date de l\'entretien : VAR[ENTRETIEN#date]
<p>Date de transmission du compte rendu : </p>
<p>Nom, qualité et signature du responsable hiérarchique :</p>
<p> </p>
<p>VAR[ENTRETIEN#VALIDATION_SUPERIEUR]</p>
</td>
</tr>
</tbody>
</table>
<h2>8. Signature et observation de l\'agent sur son entretien de formation</h2>
<table style="width: 648px;">
<tbody>
<tr>
<td style="width: 648px;">Date de l\'entretien : VAR[ENTRETIEN#date]
<p>Date de transmission du compte rendu : </p>
<p>Nom, qualité et signature du responsable hiérarchique :</p>
<p> </p>
<p> </p>
<p>VAR[ENTRETIEN#VALIDATION_AGENT]</p>
</td>
</tr>
<tr>
<td style="width: 648px;">
<p>Observations :</p>
<p> </p>
<p> </p>
<p> </p>
</td>
</tr>
</tbody>
</table>
<h2>9. Réglementation</h2>
<p>Décret n°2007-1470 du 15 octobre 2007 relatif à la formation professionnelle tout au long de la vie des fonctionnaires de l\'État</p>
<p>Article 5 :</p>
<ul>
<li>Le compte rendu de l\'entretien de formation est établi sous la responsabilité du supérieur hiérarchique.</li>
<li>Les objectifs de formation proposés pour l\'agent y sont inscrits.</li>
<li>Le fonctionnaire en reçoit communication et peut y ajouter ses observations.</li>
<li>Ce compte rendu ainsi qu\'une fiche retraçant les actions de formation auxquelles le fonctionnaire a participé sont versés à son dossier.</li>
<li>Les actions conduites en tant que formateur y figurent également.</li>
<li>Le fonctionnaire est informé par son supérieur hiérarchique des suites données à son entretien de formation.</li>
<li>Les refus opposés aux demandes de formation présentées à l\'occasion de l\'entretien de formation sont motivés.</li>
</ul>', 'body {font-size:9pt;} h1 {font-size: 14pt; color: #154360;} h2 {font-size:12pt; color:#154360;} h3 {font-size: 11pt; color: #154360;}table {border:1px solid black;border-collapse:collapse; width: 100%;} td {border:1px solid black;} th {border:1px solid black; color:#154360;}', 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('CREP - Compte rendu d''entretien professionnel', '<p>Compte-rendu de l''entretien professionnel d''un agent</p>', 'pdf', 'Entretien_professionnel_VAR[CAMPAGNE#annee]_VAR[AGENT#NomUsage]_VAR[AGENT#Prenom].pdf', e'<h1>Annexe C9 - Compte rendu de l\'entretien professionnel</h1>
<p><strong>Année : VAR[CAMPAGNE#annee]</strong></p>
<table style="width: 998.25px;">
<tbody>
<tr>
<th style="width: 482px; text-align: center;"><strong>AGENT</strong></th>
<th style="width: 465.25px; text-align: center;"><strong>SUPÉRIEUR·E HIÉRARCHIQUE DIRECT·E</strong></th>
</tr>
<tr>
<td style="width: 482px;">
<p>Nom d\'usage : VAR[AGENT#NomUsage]</p>
<p>Nom de famille : VAR[AGENT#NomFamille]</p>
<p>Prénom : VAR[AGENT#Prenom]</p>
<p>Date de naissance: VAR[AGENT#DateNaissance]</p>
<p>Corps-grade : VAR[AGENT#CorpsGrade]<strong><br /></strong></p>
<p>Échelon : VAR[Agent#Echelon]</p>
<p>Date de promotion dans l\'échelon : VAR[Agent#EchelonDate]</p>
</td>
<td style="width: 465.25px;">
<p>Nom d\'usage : VAR[ENTRETIEN#ReponsableNomUsage]</p>
<p>Nom de famille : VAR[ENTRETIEN#ReponsableNomFamille]</p>
<p>Prénom : VAR[ENTRETIEN#ReponsablePrenom]</p>
<p>Corps-grade : VAR[ENTRETIEN#ReponsableCorpsGrade]</p>
<p>Intitulé de la fonction : VAR[ENTRETIEN#ReponsableIntitlePoste]VAR[ENTRETIEN#CREP_Champ|CREP;responsable_date]</p>
<p>Structure : VAR[ENTRETIEN#ReponsableStructure]</p>
<p>Date de l\'entretien professionnel : VAR[ENTRETIEN#date]</p>
</td>
</tr>
</tbody>
</table>
<h2>1. Description du poste occupé par l\'agent</h2>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 722px;">
<p>Structure : VAR[AGENT#AffectationStructure]</p>
<p>Intitulé du poste : VAR[AGENT#IntitulePoste]VAR[ENTRETIEN#CREP_Champ|CREP;agent_poste]</p>
<p>Date d\'affectation : VAR[ENTRETIEN#CREP_Champ|CREP;affectation_date]</p>
<p>Emploi type (cf. REME ou REFERENS) : VAR[AGENT#EmploiType] VAR[ENTRETIEN#CREP_Champ|CREP;emploi-type]</p>
<p>Positionnement du poste dans le structure : VAR[AGENT#AffectationStructureFine]</p>
<p>Quotité travaillée : VAR[AGENT#Quotite]</p>
<p>Quotité d\'affectation : VAR[AGENT#QuotiteAffectation]</p>
</td>
</tr>
<tr>
<td style="width: 722px;">
<p>Missions du postes :<br />VAR[AGENT#Missions]</p>
</td>
</tr>
<tr>
<td style="width: 722px;">
<p>Missions du postes (compléments fournis dans l\'entretien professionnel) :</p>
<p> VAR[ENTRETIEN#CREP_Champ|CREP;missions]</p>
</td>
</tr>
<tr>
<td style="width: 722px;">
<p>Le cas échéant, fonctions d\'encadrement ou de conduite de projet :</p>
<ul>
<li>l\'agent assume-t\'il des fonctions de conduite de projet ? VAR[ENTRETIEN#CREP_projet]</li>
<li>l\'agent  assume-t\'il des fonctions d\'encadrements ? VAR[ENTRETIEN#CREP_encadrement]<br />Si oui préciser le nombre d\'agents et leur répartition par catégorie : VAR[ENTRETIEN#CREP_encadrementA] A - VAR[ENTRETIEN#CREP_encadrementB] B - VAR[ENTRETIEN#CREP_encadrementC] C</li>
</ul>
</td>
</tr>
</tbody>
</table>
<h2>2. Évaluation de l\'année écoulée</h2>
<h3>2.1 Rappel des objectifs d\'activités attendus fixés l\'année précédente</h3>
<p>(merci d\'indiquer si des démarches ou moyens spécifiques ont été mis en œuvres pour atteindre ces objectifs)</p>
<table style="width: 891px;">
<tbody>
<tr>
<td style="width: 891px;">VAR[ENTRETIEN#CREP_Champ|CREP;2.1]</td>
</tr>
</tbody>
</table>
<h3>2.2 Événements survenus au cours de la période écoulée ayant entraîné un impact sur l\'activité</h3>
<p>(nouvelles orientations, réorganisations, nouvelles méthodes, nouveaux outils, etc.) </p>
<table style="width: 888px;">
<tbody>
<tr>
<td style="width: 888px;">VAR[ENTRETIEN#CREP_Champ|CREP;2.2]</td>
</tr>
</tbody>
</table>
<h2>3. Valeur professionnelle et manière de servir du fonctionnaire</h2>
<h3>3.1 Critères d\'appréciation</h3>
<p>L’évaluateur retient, pour apprécier la valeur professionnelle des agents au cours de l\'entretien professionnel, les critères annexés à l’arrêté ministériel et qui sont adaptés à la nature des tâches qui leur sont confiées, au niveau de leurs responsabilités et au contexte professionnel. Pour les infirmiers et les médecins seules les parties 2, 3 et 4 doivent être renseignées en tenant compte des limites légales et règlementaires en matière de secret professionnel imposées à ces professionnels.</p>
<p><strong>1. Les compétences professionnelles et technicité</strong></p>
<p>Maîtrise technique ou expertise scientifique du domaine d\'activité, connaissance de l\'environnement professionnel et capacité à s\'y situer, qualité d\'expression écrite, qualité d\'expression orale, ...</p>
<table style="width: 840px;">
<tbody>
<tr style="height: 14.7344px;">
<td style="width: 840px; height: 14.7344px;">VAR[ENTRETIEN#CompetencesTechniques]</td>
</tr>
</tbody>
</table>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;3.1.1old]</p>
<p><strong>2. La contribution à l’activité du service</strong></p>
<p>Capacité à partager l\'information, à transférer les connaissances et à rendre compte, capacité à s\'invertir dans des projets, sens du service public et conscience professionnelle, capacité à respecter l\'organisation collective du travail, ...</p>
<table style="width: 560px;">
<tbody>
<tr>
<td style="width: 560px;">VAR[ENTRETIEN#ActiviteService]</td>
</tr>
</tbody>
</table>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;3.1.2old]</p>
<p><strong>3. Les capacités professionnelles et relationnelles </strong></p>
<p>Autonomie, discernement et sens des initiatives dans l\'exercice de ses attributions, capacité d\'adaptation, capacité à travailler en équipe, ...</p>
<table style="width: 560px;">
<tbody>
<tr>
<td style="width: 560px;">VAR[ENTRETIEN#CompetencesPersonnelles]</td>
</tr>
</tbody>
</table>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;3.1.3old]</p>
<p><strong>4. Le cas échéant, aptitude à l\'encadrement et/ou à la conduite de projets</strong></p>
<p>Capacité d\'organisation et de pilotage, aptitude à la conduite de projets, capacité à déléguer, aptitude au dialogue, à la communication et à la négociation, ...</p>
<table style="width: 560px;">
<tbody>
<tr>
<td style="width: 560px;">VAR[ENTRETIEN#EncadrementConduite]</td>
</tr>
</tbody>
</table>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;3.1.4old]</p>
<h3>3.2 Appréciation générale sur la valeur professionnelle, la manière de servir et la réalisation des objectifs</h3>
<table style="width: 764px;">
<tbody>
<tr>
<td style="width: 413.297px;"><strong>Compétences professionnelles et technicité</strong></td>
<td style="width: 352.703px;">VAR[ENTRETIEN#CREP_Champ|CREP;3.2.1]</td>
</tr>
<tr>
<td style="width: 413.297px;"><strong>Contribution à l\'activité du service</strong></td>
<td style="width: 352.703px;">VAR[ENTRETIEN#CREP_Champ|CREP;3.2.2]</td>
</tr>
<tr>
<td style="width: 413.297px;"><strong>Capacités professionnelles et relationnelles</strong></td>
<td style="width: 352.703px;">VAR[ENTRETIEN#CREP_Champ|CREP;3.2.3]</td>
</tr>
<tr>
<td style="width: 413.297px;"><strong>Aptitude à l\'encadrement et/ou à la conduite de projet</strong></td>
<td style="width: 352.703px;">VAR[ENTRETIEN#CREP_Champ|CREP;3.2.4]</td>
</tr>
</tbody>
</table>
<p> </p>
<table style="width: 763px;">
<tbody>
<tr>
<td style="width: 763px;">
<h3>Réalisation des objectifs de l\'année écoulée</h3>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;realisation]</p>
</td>
</tr>
<tr>
<td style="width: 763px;">
<h3>Appréciation littérale</h3>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;appreciation]</p>
</td>
</tr>
</tbody>
</table>
<h3>4. Acquis de l\'expérience professionnelle</h3>
<p>Vous indiquerez également dans cette rubrique si l\'agent occupe des fonctions de formateur, de membre du jury, d\'assistant de prévention, mandat électif, ...</p>
<table style="width: 755px;">
<tbody>
<tr>
<td style="width: 745px;">
<p>VAR[ENTRETIEN#CREP_Champ|CREP;exppro_1]</p>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;exppro_2]</p>
</td>
</tr>
</tbody>
</table>
<h2>5. Objectifs fixés pour la nouvelle année</h2>
<h3>5.1 Objectifs d\'activités attendus</h3>
<table style="width: 757px;">
<tbody>
<tr>
<td style="width: 747px;">VAR[ENTRETIEN#CREP_Champ|CREP;5.1]</td>
</tr>
</tbody>
</table>
<h3>5.2 Démarche envisagée, et moyens à prévoir dont la formation, pour faciliter l\'atteinte des objectifs</h3>
<table style="width: 755px;">
<tbody>
<tr>
<td style="width: 745px;">VAR[ENTRETIEN#CREP_Champ|CREP;5.2]</td>
</tr>
</tbody>
</table>
<h2>6. Perspectives d\'évolution professionnelle</h2>
<h3>6.1 Évolution des activités (préciser l\'échéance envisagée)</h3>
<table style="width: 758px;">
<tbody>
<tr>
<td style="width: 748px;">VAR[ENTRETIEN#CREP_Champ|CREP;6.1]</td>
</tr>
</tbody>
</table>
<h3>6.2 Évolution de carrière</h3>
<p><strong>Attention</strong> : à compléter obligatoirement pour les agent ayant atteint le dernier échelon de leur grade depuis au moins trois ans au 31/12 de l\'année au titre de la présente évaluation, et lorsque la nomination à ce grade ne résulte pas d\'un avancement de grade ou d\'un accès à celui-ci par concours ou promotion interne.</p>
<table style="width: 757px;">
<tbody>
<tr>
<td style="width: 747px;">VAR[ENTRETIEN#CREP_Champ|CREP;6.2]</td>
</tr>
</tbody>
</table>
<h2>7. Signature du supérieur·e hiérarchique direct·e</h2>
<table style="width: 718px;">
<tbody>
<tr>
<td style="width: 718px;">
<p>Date de l\'entretien : VAR[ENTRETIEN#date]</p>
<p>Date de transmission du compte rendu : </p>
<p>Nom, qualité et signature du responsable hiérarchique :<br /><br /><br /><br />VAR[ENTRETIEN#VALIDATION_SUPERIEUR]</p>
</td>
</tr>
</tbody>
</table>
<h2> 8. Observations de l\'agent sur son évaluation</h2>
<p>(dans un délai d\'une semaine à compter de la date de transmission du compte rendu)</p>
<table style="width: 721px;">
<tbody>
<tr>
<td style="width: 711px;">
<p>Sur l\'entretien : VAR[ENTRETIEN#ObservationEntretien]</p>
<p>Sur les perspectives de carrière et de mobilité : VAR[ENTRETIEN#ObservationPerspective]</p>
</td>
</tr>
</tbody>
</table>
<h2>9. Signature de l\'autorité hiérarchique</h2>
<table style="width: 720px;">
<tbody>
<tr>
<td style="width: 720px;">
<p>Date :</p>
<p>Nom, qualité et signature de l\'autorité hiérarchique :<br /><br /></p>
<p><br />VAR[ENTRETIEN#VALIDATION_AUTORITE]</p>
</td>
</tr>
</tbody>
</table>
<h2>10. Signature de l\'agent</h2>
<table style="width: 720px;">
<tbody>
<tr>
<td style="width: 720px;">
<p>Date :</p>
<p>Nom, qualité et signature de l\'autorité hiérarchique :<br /><br /><br />VAR[ENTRETIEN#VALIDATION_AGENT]</p>
</td>
</tr>
</tbody>
</table>
<h3> Modalité de recours </h3>
<ul>
<li><strong>Recours spécifique (Article 6 du décret n° 2010-888 du 28 juillet 2010)</strong><br />L’agent peut saisir l’autorité hiérarchique d’une demande de révision de son compte rendu d’entretien professionnel. Ce recours hiérarchique doit être exercé dans le délai de 15 jours francs suivant la notification du compte rendu d’entretien professionnel. La réponse de l’autorité hiérarchique doit être notifiée dans un délai de 15 jours francs à compter de la date de réception de la demande de révision du compte rendu de l’entretien professionnel. A compter de la date de la notification de cette réponse l’agent peut saisir la commission administrative paritaire dans un délai d\'un mois. Le recours hiérarchique est le préalable obligatoire à la saisine de la CAP.</li>
<li><strong>Recours de droit commun<br /></strong>L’agent qui souhaite contester son compte rendu d’entretien professionnel peut exercer un recours de droit commun devant le juge administratif dans les 2 mois suivant la notification du compte rendu de l’entretien professionnel, sans exercer de recours gracieux ou hiérarchique (et sans saisir la CAP) ou après avoir exercé un recours administratif de droit commun (gracieux ou hiérarchique). <br />Il peut enfin saisir le juge administratif à l’issue de la procédure spécifique définie par l’article 6 précité. Le délai de recours contentieux, suspendu durant cette procédure, repart à compter de la notification de la décision finale de l’administration faisant suite à l’avis rendu par la CAP.</li>
</ul>
<p><code></code></p>', 'body {font-size:9pt;} h1 {font-size: 14pt; color: #154360;} h2 {font-size:12pt; color:#154360;} h3 {font-size: 11pt; color: #154360;}table {border:1px solid black;border-collapse:collapse; width: 100%;} td {border:1px solid black;} th {border:1px solid black; color:#154360;}', 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_CONVOCATION_ACCEPTER', '<p>Mail de notification de l''acceptation de l''agent de son entretien professionnel à son responsable hiérarchique direct</p>', 'mail', 'Acceptation de l''entretien professionnel par VAR[AGENT#denomination]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="text-decoration: underline;">Objet :</span> acceptation de l\'entretien professionnel par VAR[AGENT#denomination]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">VAR[AGENT#denomination] vient de prendre note et accepte l\'entretien professionnel pour la campagne VAR[CAMPAGNE#annee].</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Celui-ci se déroule le VAR[ENTRETIEN#date] dans VAR[ENTRETIEN#lieu].</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_CONVOCATION_ENVOI', '<p>Mail de convocation à une entretien professionnel d''un agent</p>', 'mail', 'Convocation à votre entretien professionnel pour la campagne VAR[CAMPAGNE#annee]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong><span style="font-size: 9.0pt; font-family: \'Calibri\',sans-serif; mso-bidi-font-family: \'Times New Roman\';">objet :    </span></strong><strong><span style="font-size: 9.0pt; font-family: \'Calibri\',sans-serif; mso-bidi-font-family: Arial;">Convocation à votre entretien professionnel VAR[CAMPAGNE#annee].</span></strong></p>
<p class="MsoNormal" style="mso-margin-top-alt: auto; margin-bottom: .0001pt; text-align: justify; line-height: normal; tab-stops: 35.45pt;"><span style="font-size: 9.0pt; font-family: \'Calibri Light\',sans-serif; mso-ascii-theme-font: major-latin; mso-hansi-theme-font: major-latin; mso-bidi-theme-font: major-latin;">Réf. :       </span><span style="font-size: 9.0pt; font-family: \'Calibri\',sans-serif; mso-bidi-font-family: Arial;">- </span><span style="font-size: 8.0pt; mso-bidi-font-size: 9.0pt; font-family: \'Calibri Light\',sans-serif; mso-ascii-theme-font: major-latin; mso-hansi-theme-font: major-latin; mso-bidi-theme-font: major-latin;">Loi n°84-16 du 11 janvier 1984 modifiée portant dispositions statutaires à la fonction publique d’Etat</span></p>
<p class="MsoNormal" style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 35.45pt;"><span style="font-size: 8.0pt; mso-bidi-font-size: 9.0pt; font-family: \'Calibri Light\',sans-serif; mso-ascii-theme-font: major-latin; mso-hansi-theme-font: major-latin; mso-bidi-theme-font: major-latin;">- Décret n°2010-888 du 28/07/2010 modifié relatif aux conditions générales d’appréciation de la valeur professionnelle des fonctionnaires de l’Etat</span></p>
<p class="MsoNormal" style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 35.45pt;"><span style="font-size: 8.0pt; mso-bidi-font-size: 9.0pt; font-family: \'Calibri Light\',sans-serif; mso-ascii-theme-font: major-latin; mso-hansi-theme-font: major-latin; mso-bidi-theme-font: major-latin;">- Décret n°2011-2041 du 29 décembre 2011 modifiant le décret n° 2010-888 du 28 juillet 2010</span></p>
<p class="MsoNormal" style="text-align: justify; text-indent: -.1pt; line-height: normal; margin: 0cm 0cm .0001pt 35.45pt;"><span style="font-size: 8.0pt; mso-bidi-font-size: 9.0pt; font-family: \'Calibri Light\',sans-serif; mso-ascii-theme-font: major-latin; mso-hansi-theme-font: major-latin; mso-bidi-theme-font: major-latin;">- Arrêté du 18 mars 2013 relatif aux modalités d’application à certains fonctionnaires relevant des ministres chargés de l’éducation nationale et de l’enseignement supérieur du décret n°2010-888 du 28 juillet 2010 relatif aux conditions générales de l’appréciation de la valeur professionnelle des fonctionnaires de l’Etat</span></p>
<p class="MsoNormal" style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 35.45pt;"><span style="font-size: 8.0pt; mso-bidi-font-size: 9.0pt; font-family: \'Calibri Light\',sans-serif; mso-ascii-theme-font: major-latin; mso-hansi-theme-font: major-latin; mso-bidi-theme-font: major-latin;">- Circulaire interne du 25 mars 2019</span></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Le décret n°2011-2041 généralise le dispositif des entretiens professionnels. La circulaire du Président de l\'Université signée du VAR[CAMPAGNE#datecirculaire], vous informe des directives de la campagne pour les personnels de l\'AENES, de l\'ITRF et des bibliothèques pour l\'année VAR[CAMPAGNE#annee] ainsi que pour les agents non titulaires recrutés par contrat à durée déterminée de plus d\'un an comme le prévoit le décret n°2014-364.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Votre responsable hiérarchique vous recevra pour réaliser votre entretien professionnel<strong> le VAR[ENTRETIEN#date] à VAR[ENTRETIEN#lieu]</strong>.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Votre responsable hiérarchique vous invite à préparer votre entretien en consultant sous le lien suivant les documents : http://intranet.unicaen.fr/services-/ressources-humaines/gestion-des-personnels/entretien-professionnel-540109.kjsp?RH=1574253529391</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Si la date ne vous convient pas veuillez vous adresser à votre responsable d\'entretien professionnel ou à votre responsable de structure.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Merci d\'accuser réception de cette convocation en cliquant sur le lien suivant : VAR[URL#EntretienAccepter]<br />En cas d’empêchement, veuillez contacter votre supérieur·e hiérarchique direct·e.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_VALIDATION_1-RESPONSABLE', null, 'mail', 'Validation de l''entretien professionnel pour la campagne VAR[CAMPAGNE#annee] de VAR[AGENT#denomination] par le responsable de l''entretien VAR[ENTRETIEN#responsable]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Votre entretien professionnel pour la campagne VAR[CAMPAGNE#annee] a été validé par VAR[ENTRETIEN#responsable], vous êtes invité<span style="color: #4d5156; font-family: arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;">·</span>e à en prendre connaissance et y apporter des observations si vous l\'estimez nécessaire.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Vous pouvez dés à présent émettre, si besoin, des observations en lien avec :</p>
<ul>
<li style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">votre entretien professionnel et son déroulé ;</li>
<li style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">vos perspectives d\'évolution professionnelle.</li>
</ul>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Votre entretien professionnel est disponible sur l\'application VAR[EMC2#Nom] : VAR[URL#EntretienRenseigner].</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="text-decoration: underline;">Attention :</span> Vous disposez d\'un délai d\'une semaine pour émettre, si besoin, vos observations. <br />N\'oubliez pas de valider celles-ci.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Pour rappel, l\'entretien professionnel est un moment privilégié d\'échange et de dialogue avec votre responsable hiérarchique direct.<br />Nous vous invitons, si besoin, à vous rapprocher de votre responsable hiérarchique direct avant d\'émettre vos observations.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><br />Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_VALIDATION_2-OBSERVATION', '<p>Mail envoyé au responsable d''entretien vers le responsable hiérarchique</p>', 'mail', 'Observations de VAR[AGENT#denomination] sur son entretien professionnel', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="text-decoration: underline;">Objet :</span> observation de VAR[AGENT#denomination] sur son entretien professionnel,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;">VAR[AGENT#denomination]</span> vient d\'émettre des observations en lien avec son entretien professionnel.<br />Vous pouvez maintenant consulter et valider son entretien et ses observations en suivant le lien : https://emc2.unicaen.fr/entretien-professionnel/acceder/VAR[ENTRETIEN#Id]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br /><br /></p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_VALIDATION_2-OBSERVATION_TRANSMISSION', '<p>Transmission des observations aux responsable d''entretien professionnel</p>', 'mail', 'L''expression des observations de VAR[AGENT#Denomination] sur son entretien professionnel de la campagne VAR[CAMPAGNE#annee]', e'<p>VAR[AGENT#Denomination] vient de valider ses observations pour l\'entretien professionnel de la campagne VAR[CAMPAGNE#annee].</p>
<p><span style="text-decoration: underline;">Observations sur l\'entretien professionnel</span></p>
<p>VAR[ENTRETIEN#ObservationEntretien]</p>
<p><span style="text-decoration: underline;">Observation sur les perspectives</span></p>
<p>VAR[ENTRETIEN#ObservationPerspective]</p>
<p> </p>
<p>Cordialement,<br />EMC2</p>
<p> </p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_VALIDATION_2-PAS_D_OBSERVATION', '<p>Mail envoyé au responsable hiérarchique après le dépassement du délai d''émission des observation</p>', 'mail', 'Ouverture de la validation de l''entretien professionnel de VAR[ENTRETIEN#Agent]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="text-decoration: underline;">Objet :</span> ouverture de la validation de l\'entretien professionnel de VAR[ENTRETIEN#Agent]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p>Vous pouvez maintenant valider l\'entretien professionnel de VAR[ENTRETIEN#Agent].<br />Vous pouvez consulter et valider cet entretien en suivant le lien : https://emc2.unicaen.fr/entretien-professionnel/acceder/VAR[ENTRETIEN#Id]</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br /><br /></p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_VALIDATION_3-HIERARCHIE', '<p>Validation du responsable hierarchique</p>', 'mail', 'Validation de l''autorité hiérarchique de votre entretien professionnel VAR[CAMPAGNE#annee]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span style="text-decoration: underline;">Objet :</span> validation de l\'autorité hiérarchique de votre entretien professionnel VAR[CAMPAGNE#annee]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">L\'autorité hiérarchique vient de valider votre entretien professionnel pour la campagne VAR[CAMPAGNE#annee].<br />Vous êtes invité-e à accuser réception de votre compte-rendu en cliquant dans l\'onglet validation de votre entretien : VAR[URL#EntretienRenseigner]<br />Cet accusé de réception clôturera votre entretien professionnel.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('ENTRETIEN_VALIDATION_4-AGENT', null, 'mail', 'VAR[AGENT#Denomination] vient de valider son entretien professionnel', e'<p>Bonjour,</p>
<p>VAR[AGENT#Denomination] vient de valider son entretien professionnel pour la campagne VAR[CAMPAGNE#annee].<br />Ceci, clôt son entretien professionnel.</p>
<p>Bonne journée,<br />L\'application EMC2</p>
<p> </p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('NOTIFICATION_RAPPEL_CAMPAGNE_AUTORITE', '<p>Mail envoyé au autorité pour l''avancement de la campagne</p>', 'mail', 'Avancement de la campagne d''entretien professionnel VAR[CAMPAGNE#annee]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p><span style="text-decoration: underline;">Objet :</span> état d\'avancement de la campagne d\'entretien professionnel VAR[CAMPAGNE#annee] en tant qu\'autorité</p>
<p>Bonjour,</p>
<p>Vous recevez ce courrier électronique concernant l\'avancement du ou des entretiens professionnels de la campagne VAR[CAMPAGNE#annee] dont vous avez la responsabilité. Attention cette campagne sera clôturée le VAR[CAMPAGNE#fin].</p>
<p>###A REMPLACER###</p>
<p>Pour gérer vos entretiens professionnels vous pouvez vous rendre dans EMC2.</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences</p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('NOTIFICATION_RAPPEL_CAMPAGNE_SUPERIEUR', '<p>Mail envoyé au supérieurs pour l''avancement de la campagne</p>', 'mail', 'Avancement de la campagne d''entretien professionnel VAR[CAMPAGNE#annee]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p><span style="text-decoration: underline;">Objet :</span> état d\'avancement de la campagne d\'entretien professionnel VAR[CAMPAGNE#annee] en tant que supérieur·e</p>
<p>Bonjour,</p>
<p>Vous recevez ce courrier électronique concernant l\'avancement du ou des entretiens professionnels de la campagne VAR[CAMPAGNE#annee] dont vous avez la responsabilité. Attention cette campagne sera clôturée le VAR[CAMPAGNE#fin].</p>
<p>###A REMPLACER###</p>
<p>Pour gérer vos entretiens professionnels vous pouvez vous rendre dans EMC2.</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences</p>
<p> </p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('RAPPEL_ATTENTE_VALIDATION_AGENT', null, 'mail', 'Entretien·s professionnel·s en attente de votre validation', e'<p>Bonjour VAR[AGENT#Denomination],</p>
<p>Vous êtes un·e agent de l\'Université de Caen Normandie et au moins un entretien professionnel de la campagne VAR[CAMPAGNE#annee] attend votre validation en tant qu\'Agent.<br />Veuillez vous connecter à l\'application EMC2 (VAR[URL#App]) afin de valider ceux-ci.</p>
<p>Bonne journée,<br />L\'équipe EMC2</p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('RAPPEL_ATTENTE_VALIDATION_AUTORITE', null, 'mail', 'Entretien·s professionnel·s en attente de votre validation', e'<p>Bonjour VAR[AGENT#Denomination],<br /><br />Vous êtes l\'autorité hiérarchique d\'au moins un·e agent de l\'Université de Caen Normandie dont l\'entretien professionnel de la campagne VAR[CAMPAGNE#annee] attend votre validation en tant qu\'Autorité hiérarchique.<br />Veuillez vous connecter à l\'application EMC2 (VAR[URL#App]) afin de valider ceux-ci.</p>
<p>Voici la liste des entretiens professionnels en attente :<br />###SERA REMPLACÉ###</p>
<p><br />Bonne journée,<br />L\'équipe EMC2</p>', null, 'EntretienProfessionnel\Provider\Template');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace) VALUES ('RAPPEL_ATTENTE_VALIDATION_SUPERIEUR', null, 'mail', 'Entretien·s professionnel·s en attente de votre validation ', '<p><br /><br />Bonjour VAR[AGENT#Denomination],<br /><br />Vous êtes le supérieur·e hiérarchique direct·e d''au moins un·e agent de l''Université de Caen Normandie dont l''entretien professionnel de la campagne VAR[CAMPAGNE#annee] attend votre validation en tant que Supérieur·e hiérarchique direct·e.<br />Veuillez vous connecter à l''application EMC2 (VAR[URL#App]) afin de valider ceux-ci.<br /><br />Voici la liste des entretiens professionnels en attente :<br />###SERA REMPLACÉ###<br /><br /><br />Bonne journée,<br />L''équipe EMC2<br /><br /></p>', null, 'EntretienProfessionnel\Provider\Template');

-- Formulaire -------------------------------------------

INSERT INTO unicaen_autoform_formulaire (libelle, code)
VALUES ('Compte-Rendu d''Entretien Professionnel (CREP)', 'CREP');
INSERT INTO unicaen_autoform_categorie (formulaire, code, libelle, ordre, mots_clefs)
WITH d(code, libelle, ordre, mots_clefs) AS (
    SELECT '1_621f65bb938e6', 'Compléments d''informations', 1, null UNION
    SELECT '1_5d137a9a599ae', 'ACQUIS DE L’EXPERIENCE PROFESSIONNELLE', 5, null UNION
    SELECT '1_5d1379332f0da', 'VALEUR PROFESSIONNELLE ET MANIERE DE SERVIR DU FONCTIONNAIRE', 4, null UNION
    SELECT '1_5cab477d6ebce', 'ÉVALUATION DE L’ANNEE ECOULÉE', 3, null UNION
    SELECT '1_5cab4652d229b', 'DESCRIPTION DU POSTE OCCUPE PAR L’AGENT', 2, null UNION
    SELECT '1_5f5a3e8fc5a1f', 'MODALITÉS DE RECOURS', 8, null UNION
    SELECT '1_5d137af8278b2', 'PERSPECTIVES D’ÉVOLUTION PROFESSIONNELLE', 7, null UNION
    SELECT '1_5d137abaa7421', 'OBJECTIFS FIXÉS POUR LA NOUVELLE ANNÉE', 6, null
)
SELECT cp.id, d.code, d.libelle, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_formulaire cp ON cp.CODE = 'CREP';
-- categorie "Compléments d'informations"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '1_22_621f66ec87b1e', 'Remarque', 'Toutes les fiches de poste ne sont pas encore présentes dans EMC2. Merci de compléter les informations suivantes si l''agent ou le responsable de l''entretien n''ont pas de fiche de poste EMC2. Sinon laisser vide.', 'Label', '', 4, null UNION
    SELECT '1_22_621f98213fe4e', 'Date affectation de l''agent', '', 'Text', '', 2, 'CREP;affectation_date;' UNION
    SELECT '1_22_621f66a5a2f7e', 'SPACER', '', 'Spacer', '', 3, null UNION
    SELECT '1_22_621f670a1f912', 'Intitulé du poste de l''agent', '', 'Text', '', 5, null UNION
    SELECT '1_22_621f673370344', 'Intitulé du poste du responsable de l''entretien', '', 'Text', '', 7, null UNION
    SELECT '1_22_621f671898bfc', 'Emploi-type de l''agent', '', 'Text', '', 6, 'CREP;emploi-type;' UNION
    SELECT '1_22_621f660627418', 'Remarque', 'Les informations suivantes ne sont pas encore disponible dans le référentiel OCTOPUS. Merci de les compléter afin de remonter celle-ci dans le compte-rendu d''entretien professionnel (CREP).', 'Label', '', 1, null

)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '1_621f65bb938e6';
-- categorie "ACQUIS DE L’EXPERIENCE PROFESSIONNELLE"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '1_4_5ec4f4d8f18c7', 'Missions spécifiques', '', 'Multiple', 'Référent formation professionnelle (FC);Référent formation intiale;Membre de jury;Référent assistant prévention;Mandat électif', 1, 'CREP;exppro_1' UNION
    SELECT '1_4_5d137aae286e4', 'Autres', '', 'Textarea', '', 2, 'CREP;exppro_2'
)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '1_5d137a9a599ae';
-- categorie "VALEUR PROFESSIONNELLE ET MANIERE DE SERVIR DU FONCTIONNAIRE"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '1_3_5d137a74c3969', 'SPACER', '', 'Spacer', '', 18, null UNION
    SELECT '1_3_60743d71e5ca5', 'empty', '3. Les capacités professionnelles et relationnelles : autonomie, discernement et sens des initiative dans l''exercice de ses attributions, capacité d''adaptation, capacité à travailler en équipe, ... ', 'Label', '', 14, null UNION
    SELECT '1_3_60743d1605955', 'empty', '2. La contribution à l''activité du service : capacité à partager l''information, à transférer les connaissances et à rendre compte, capacité à s''investir dans des projets, sens du service public et conscience professionnelle, capacité à respecter l''organisation collective du travail, ...', 'Label', '', 12, null UNION
    SELECT '1_3_60743c9141737', 'empty', '1. Les compétences professionnelles et technicités : maîtrise technique ou expertise scientifique du domaine d''activité, connaissance de l''environnement professionnel et capacité à s''y situer, qualité d''expression écrite, qualité orale, ...', 'Label', '', 10, null UNION
    SELECT '1_3_60743dcea4a1d', 'empty', '4. Le cas échéant, aptitude à l''encadrement et/ou à la conduite de projets : capacité d''organisation et de pilotage, aptitude à la conduite de projets, capacité à déléguer, aptitude au dialogue, à la communication et à la négociation, ...', 'Label', '', 16, null UNION
    SELECT '1_3_5d1379989b6a2', 'Appréciation générale sur la valeur professionnelle, la manière de servir et la réalisation des objectifs', '', 'Label', '', 9, null UNION
    SELECT '1_3_5d13794397825', 'Critères d’appréciation', '', 'Label', '', 1, null UNION
    SELECT '1_4_60743e9db5ee9', 'ATTENTION', 'Merci d''apporter un soin particulier à cette appréciation qui constitue un critère pour l''avancement de grade des agents et pourra être repris dans les rapports liés à la promotion de grade.', 'Label', '', 21, null UNION
    SELECT '1_3_5d137a8a5954e', 'Appréciation littérale', '', 'Textarea', '', 20, 'CREP;appreciation;' UNION
    SELECT '1_3_5d137a7f1e54d', 'Réalisation des objectifs de l’année écoulée', '', 'Textarea', '', 19, 'CREP;realisation;' UNION
    SELECT '1_3_5d1379913541d', 'SPACER', '', 'Spacer', '', 8, null UNION
    SELECT '1_3_5f5a3e0e540dc', 'ATTENTION', 'L’évaluateur retient, pour apprécier la valeur professionnelle des agents au cours de l''entretien professionnel, les critères annexés à l’arrêté ministériel et qui sont adaptés à la nature des tâches qui leur sont confiées, au niveau de leurs responsabilités et au contexte professionnel. Pour les infirmiers et les médecins seules les parties 2, 3 et 4 doivent être renseignées en tenant compte des limites légales et règlementaires en matière de secret professionnel imposées à ces professionnels', 'Label', '', 0, null UNION
    SELECT '1_3_634d6c3912fcd', 'Les compétences professionnelles et technicité', 'Maîtrise technique ou expertise scientifique du domaine d''activité, connaissance de l''environnement professionnel et capacité à s''y situer, qualité d''expression écrite, qualité d''expression orale, ... ', 'Entity Multiple', 'EntretienProfessionnel\Entity\Db\CritereCompetence', 0, 'CREP;3.1.1;' UNION
    SELECT '1_3_5d1379d3c13fb', 'Contribution à l’activité du service', '', 'Select', 'à acquerir;à développer;maîtrise;expert', 13, 'CREP;3.2.2;' UNION
    SELECT '1_3_5d1379c58b19d', 'Compétences professionnelles et technicité', '', 'Select', 'à acquerir;à développer;maîtrise;expert', 11, 'CREP;3.2.1;' UNION
    SELECT '1_3_5d1379efc6c51', 'Aptitude à l’encadrement et/ou à la conduite de projets (le cas échéant)', '', 'Select', 'à acquerir;à développer;maîtrise;expert', 17, 'CREP;3.2.4;' UNION
    SELECT '1_3_5d1379e00e967', 'Capacités professionnelles et relationnelles', '', 'Select', 'à acquerir;à développer;maîtrise;expert', 15, 'CREP;3.2.3;' UNION
    SELECT '1_3_5d13795e6ae93', 'La contribution à l’activité du service', 'Capacité à partager l''information, à transférer les connaissances et à rendre compte, capacité à s''invertir dans des projets, sens du service public et conscience professionnelle, capacité à respecter l''organisation collective du travail, ...', 'Entity Multiple', 'EntretienProfessionnel\Entity\Db\CritereContribution', 2, 'CREP;3.1.2;' UNION
    SELECT '1_3_5d13796aafdec', 'Les capacités professionnelles et relationnelles', 'Autonomie, discernement et sens des initiatives dans l''exercice de ses attributions, capacité d''adaptation, capacité à travailler en équipe, etc.', 'Entity Multiple', 'EntretienProfessionnel\Entity\Db\CriterePersonnelle', 4, 'CREP;3.1.3;' UNION
    SELECT '1_3_5d137977d1aa2', 'Compléments à la section cas échéant, aptitude à l’encadrement et/ou à la conduite de projets', '', 'Textarea', '', 7, 'CREP;3.1.4old;' UNION
    SELECT '1_3_5d137977d1aa2', 'Le cas échéant, aptitude à l’encadrement et/ou à la conduite de projets', 'Capacité d''organisation et de pilotage, aptitude à la conduite de projets, capacité à déléguer, aptitude au dialogue, à la communication et à la négociation, ... ', 'Entity Multiple', 'EntretienProfessionnel\Entity\Db\CritereEncadrement', 6, 'CREP;3.1.4;' UNION
    SELECT '1_3_5d13795e6ae93', 'Compléments à la section contributions à l’activité du service', '', 'Textarea', '', 3, 'CREP;3.1.2old;' UNION
    SELECT '1_3_5d1379525e0fb', 'Compléments à la section compétences professionnelles et technicité', '', 'Textarea', '', 0, 'CREP;3.1.1old;' UNION
    SELECT '1_3_5d13796aafdec', 'Compléments à la section capacités professionnelles et relationnelles', '', 'Textarea', '', 5, 'CREP;3.1.3old;'

)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '1_5d1379332f0da';
-- categorie "ÉVALUATION DE L’ANNEE ECOULÉE"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '1_2_5cab47aedf9d6', 'Événements survenus au cours de la période écoulée', 'Nouvelles orientations, réorganisations, nouvelles méthodes, nouveaux outils, ... ', 'Textarea', '', 2, 'CREP;2.2;' UNION
    SELECT '1_2_5cab479a1c8d1', 'Rappel des objectifs d’activités attendus', 'Merci d''indiquer si des démarches ou moyens spécifiques ont été mis en oeuvre pour atteindre ces objectifs', 'Textarea', '', 1, 'CREP;2.1;'
)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '1_5cab477d6ebce';
-- categorie "DESCRIPTION DU POSTE OCCUPE PAR L’AGENT"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '1_1_5ea7f8e36f50b', 'Nombre d’agents encadrés de catégorie C', '', 'Number', '', 9, 'CREP;encadrement_C;' UNION
    SELECT '1_1_5cab46a9a8177', 'SPACER', '', 'Spacer', '', 2, null UNION
    SELECT '1_1_5ea7f8f881344', 'SPACER', '', 'Spacer', '', 6, null UNION
    SELECT '1_1_5cab470a5fa12', 'l''agent assume des fonctions d''encadrement', '', 'Checkbox', '', 5, 'CREP;encadrement' UNION
    SELECT '1_1_5cab4698af8f9', 'Fonctions d’encadrement ou de conduite de projet :', '', 'Label', '', 3, null UNION
    SELECT '1_1_5cab46ef716d3', 'l''agent assume des fonctions de conduite de projet', '', 'Checkbox', '', 4, 'CREP;projet' UNION
    SELECT '1_1_5cab47308ce7e', 'Nombre d’agents encadrés de catégorie A', '', 'Number', '', 7, 'CREP;encadrement_A;' UNION
    SELECT '1_1_5ea7f8d6c78fe', 'Nombre d’agents encadrés de catégorie B', '', 'Number', '', 8, 'CREP;encadrement_B;' UNION
    SELECT '1_1_5cab466f19d47', 'Compléments relatif aux missions du poste', '', 'Textarea', '', 1, 'CREP;missions;'
)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '1_5cab4652d229b';
-- categorie "MODALITÉS DE RECOURS"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '1_10_5f5a3ec4ad926', 'Recours de droit commun', 'L’agent qui souhaite contester son compte rendu d’entretien professionnel peut exercer un recours de droit commun devant le juge administratif dans les 2 mois suivant la notification du compte rendu de l’entretien professionnel, sans exercer de recours gracieux ou hiérarchique (et sans saisir la CAP) ou après avoir exercé un recours administratif de droit commun (gracieux ou hiérarchique). Il peut enfin saisir le juge administratif à l’issue de la procédure spécifique définie par l’article 6 précité. Le délai de recours contentieux, suspendu durant cette procédure, repart à compter de la notification de la décision finale de l’administration faisant suite à l’avis rendu par la CAP. ', 'Label', '', 2, null UNION
    SELECT '1_10_5f5a3ea78845a', 'Recours spécifique (Article 6 du décret n° 2010-888 du 28 juillet 2010)', 'L’agent peut saisir l’autorité hiérarchique d’une demande de révision de son compte rendu d’entretien professionnel. Ce recours hiérarchique doit être exercé dans le délai de 15 jours francs suivant la notification du compte rendu d’entretien professionnel. La réponse de l’autorité hiérarchique doit être notifiée dans un délai de 15 jours francs à compter de la date de réception de la demande de révision du compte rendu de l’entretien professionnel.  A compter de la date de la notification de cette réponse l’agent peut saisir la commission administrative paritaire dans un délai d''un mois. Le recours hiérarchique est le préalable obligatoire à la saisine de la CAP.', 'Label', '', 1, null
)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '1_5f5a3e8fc5a1f';
-- categorie "PERSPECTIVES D’ÉVOLUTION PROFESSIONNELLE"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '1_6_5d137b0c3ad1a', 'Évolution de carrière', '', 'Textarea', '', 2, 'CREP;6.2;' UNION
    SELECT '1_6_5f5a3b70e0ea9', 'ATTENTION', 'À compléter obligatoirement pour les agents ayant atteint le dernier échelon de leur grade depuis au moins trois ans au 31/12 de l''année au titre de la présente évaluation, et lorsque la nomination à ce grade ne résulte pas d''un avancement de grade ou d''un accès à celui-ci par concours ou promotion internes (Décret n° 2017-722 du 02/05/2017  relatif aux modalités d''appréciation de la valeur et de l''expérience professionnelles de certains fonctionnaires éligibles à un avancement de grade)', 'Label', '', 3, null UNION
    SELECT '1_6_5d137b04786f3', 'Évolution des activités (préciser l''échéance envisagée)', '', 'Textarea', '', 1, 'CREP;6.1;'

)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '1_5d137af8278b2';
-- categorie "OBJECTIFS FIXÉS POUR LA NOUVELLE ANNÉE"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '1_5_5d137ac5c2f5f', 'Objectifs d''activités attendus', '', 'Textarea', '', 1, 'CREP;5.1;' UNION
    SELECT '1_5_5d137ad379d3c', 'Démarche envisagée, et moyens à prévoir dont la formation, pour faciliter l’atteinte des objectifs', '', 'Textarea', '', 2, 'CREP;5.2;'
)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '1_5d137abaa7421';


INSERT INTO unicaen_autoform_formulaire (libelle, code)
VALUES ('Compte-Rendu d''Entretien de formation (CREF)', 'CREF');
INSERT INTO unicaen_autoform_categorie (formulaire, code, libelle, ordre, mots_clefs)
WITH d(code, libelle, ordre, mots_clefs) AS (
    SELECT '2_62289f96346ec', 'Informations complémentaires', 1, null UNION
    SELECT '2_60744315c477c', 'Activités de transfert de compétences ou d''accompagnement des agents', 2, null UNION
    SELECT '2_6218b5ee5b3e5', 'Bilan des formations suivies sur la période écoulée', 3, 'CREF;Bilan' UNION
    SELECT '2_6074464c768c6', 'Formations demandées sur la période écoulée et non suivies', 4, null UNION
    SELECT '2_607447155a1f3', 'Formation continue (demandée pour la nouvelle période)', 5, null UNION
    SELECT '2_60744aa24dcab', 'Formation de préparation à un concours ou examen professionnel', 6, null UNION
    SELECT '2_60744b5f4443d', 'Formations pour construire un projet personnel à caractère professionnel', 7, null UNION
    SELECT '2_60744ea79edce', 'Règlementation', 11, null
)
SELECT cp.id, d.code, d.libelle, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_formulaire cp ON cp.CODE = 'CREF';
-- categorie "Informations complémentaires"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '2_23_62289ff6f20b6', 'Remarque', 'Les informations suivantes ne sont pas encore disponible dans le référentiel OCTOPUS. Merci de les compléter afin de remonter celle-ci dans le compte-rendu de formation (CREF).', 'Label', '', 1, null UNION
    SELECT '2_23_6228a12b4f737', 'Date du dernier entretien professionnel', '', 'Text', '', 6, 'CREF;precedent' UNION
    SELECT '2_23_6228a0589d84b', 'Solde du CPF', '', 'Text', '', 2, 'CREF;CPF_solde' UNION
    SELECT '2_23_6228a0c290261', 'SPACER', '', 'Spacer', '', 4, null UNION
    SELECT '2_23_6228a0db1e483', 'Remarque', 'Tous les agents n''ont pas participé à la campagne précédente d''entretien professionnel sur EMC2. Pour ceux-ci veuillez compléter les informations suivantes.', 'Label', '', 5, null UNION
    SELECT '2_23_6228a0ae78c65', 'L''agent envisage-t''il de mobiliser son CPF cette année', '', 'Select', 'Oui;Non', 3, 'CREF;CPF_mobilisation'
)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '2_62289f96346ec';
-- categorie "Activités de transfert de compétences ou d'accompagnement des agents"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '2_15_6074438447701', 'Activités ', '', 'Multiple', 'Formateur;Tuteur/Mentor;Président/Vice-Président de jury;Membre de jury', 1, 'CREF;1.1' UNION
    SELECT '2_15_60744459b46fe', 'Formations dispensées', 'Préciser les activités de formation encadrée par l''agent hors des activités de sa fiche de poste.', 'Label', '', 2, null UNION
    SELECT '2_15_607548067e991', 'Formation 4', '', 'Multiple_champs_paramètrables', 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 6, 'CREF;1.2' UNION
    SELECT '2_15_607547f62955e', 'Formation 3', '', 'Multiple_champs_paramètrables', 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 5, 'CREF;1.2' UNION
    SELECT '2_15_6075478d8c15d', 'Formation 1', '', 'Multiple_champs_paramètrables', 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 3, 'CREF;1.2' UNION
    SELECT '2_15_6075481869150', 'Formation 5', '', 'Multiple_champs_paramètrables', 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 7, 'CREF;1.2' UNION
    SELECT '2_15_607547e8d6b8b', 'Formation 2', '', 'Multiple_champs_paramètrables', 'texte court|Discipline de formation;texte court|Année universitaire de formation;texte court|Titre de la formation;texte court|Organisme concerné', 4, 'CREF;1.2'
)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '2_60744315c477c';
-- categorie "Bilan des formations suivies sur la période écoulée"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '2_21_627d2a0187a25', 'Autres formations', '', 'Textarea', '', 7, 'CREF;AutresFormations' UNION
    SELECT '2_21_6218b697c42ef', 'Sessions réalisées du 1er septembre au 31 août de l''année de la campagne en cours', '', 'Label', '', 1, null UNION
    SELECT '2_21_6218b76610c37', 'Formation 2', '', 'Multiple_champs_paramètrables', 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 3, 'CREF;2' UNION
    SELECT '2_21_6218b78c7739e', 'Formation 4', '', 'Multiple_champs_paramètrables', 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 5, 'CREF;2' UNION
    SELECT '2_21_6218b75939b77', 'Formation 1', '', 'Multiple_champs_paramètrables', 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 2, 'CREF;2' UNION
    SELECT '2_21_6218b77fb5e18', 'Formation 3', '', 'Multiple_champs_paramètrables', 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 4, 'CREF;2' UNION
    SELECT '2_21_6218b799a5357', 'Formation 5', '', 'Multiple_champs_paramètrables', 'texte court|Libellé de la formation;texte court|Nombre d''heures;texte court|Nombre d''heures CPF utilisés;texte court|Nombre d''heures suivi effectif (si absence partielle)', 6, 'CREF;2'
)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '2_6218b5ee5b3e5';
-- categorie "Formations demandées sur la période écoulée et non suivies"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '2_16_6074468e37a47', 'Formations demandées lors de l''entretien précédent', '', 'Label', '', 1, null UNION
    SELECT '2_16_60754015d1010', 'Formation 5', '', 'Multiple Text', 'Action de formation;Nombre d''heures', 6, 'CREF;3' UNION
    SELECT '2_16_60753ff8a5d43', 'Formation 3', '', 'Multiple Text', 'Action de formation;Nombre d''heures', 4, 'CREF;3' UNION
    SELECT '2_16_60753fb0ec093', 'Formation 1', '', 'Multiple Text', 'Action de formation;Nombre d''heures', 2, 'CREF;3' UNION
    SELECT '2_16_6075400784143', 'Formation 4', '', 'Multiple Text', 'Action de formation;Nombre d''heures', 5, 'CREF;3' UNION
    SELECT '2_16_60753fe993b28', 'Formation 2', '', 'Multiple Text', 'Action de formation;Nombre d''heures', 3, 'CREF;3'

)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '2_6074464c768c6';
-- categorie "Formation continue (demandée pour la nouvelle période)"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '2_17_607550eeb08f5', 'S', '', 'Spacer', '', 10, null UNION
    SELECT '2_17_607449d5be974', 'Actions de formations demandées par l''agent et recueillant un avis défavorable du supérieur hiérarchique direct', 'N.B. : l''avis défavorable émis par le supérieur hiérarchique direct conduisant l''entretien ne préjuge pas de la suite donnée à la demande de formation', 'Label', '', 11, null UNION
    SELECT '2_17_607448d43441d', 'Type 3 : formations d''acquisition de qualifications nouvelles', 'Favoriser sa culture professionnelle ou son niveau d''expertise, approfondir ses connaissances dans un domaine qui ne relève pas de son activité actuelle, pour se préparer à de nouvelles fonctions, surmonter des difficultés sur son poste actuel.', 'Label', '', 3, null UNION
    SELECT '2_17_6074499de3661', 'SPACER', '', 'Spacer', '', 4, null UNION
    SELECT '2_17_6075508684e5a', 'Formation 4', '', 'Multiple_champs_paramètrables', 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', 8, 'CREF;4.1' UNION
    SELECT '2_17_60754efa821b5', 'Formation 1', '', 'Multiple_champs_paramètrables', 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', 5, 'CREF;4.1' UNION
    SELECT '2_17_60744863be8e2', 'Type 2 : formations à l''évolution des métiers ou des postes de travail', 'Approfondir ses compétences techniques, actualiser ses savoir-faire professionnels, acquérir des fondamentaux ou remettre à niveau ses connaissances pour se préparer à des changements fortement probables, induits par la mise en place d''une réforme, d''un nouveau système d''information ou de nouvelles techniques.', 'Label', '', 2, null UNION
    SELECT '2_17_607447d31b71e', 'Type 1 : formations d''adaptation immédiate au poste de travail', 'Stage d''adaptation à l''emploi, de prise de poste après une mutation ou une promotion', 'Label', '', 1, null UNION
    SELECT '2_17_6075518c15cb4', 'Formation 5', '', 'Multiple_champs_paramètrables', 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 16, 'CREF;4.2' UNION
    SELECT '2_17_60755072d7b3e', 'Formation 3', '', 'Multiple_champs_paramètrables', 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', 7, 'CREF;4.1' UNION
    SELECT '2_17_6075514d57c98', 'Formation 2', '', 'Multiple_champs_paramètrables', 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 13, 'CREF;4.2' UNION
    SELECT '2_17_6075509516253', 'Formation 5', '', 'Multiple_champs_paramètrables', 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', 9, 'CREF;4.1' UNION
    SELECT '2_17_6075500f85b1a', 'Formation 2', '', 'Multiple_champs_paramètrables', 'texte court|Libellé;select|Type||Type1|Type2|Type3;select|Origine de la demande||Responsable|Agent;texte court|Durée', 6, 'CREF;4.1' UNION
    SELECT '2_17_6075516c4fe19', 'Formation 4', '', 'Multiple_champs_paramètrables', 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 15, 'CREF;4.2' UNION
    SELECT '2_17_607551207d6f6', 'Formation 1', '', 'Multiple_champs_paramètrables', 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 12, 'CREF;4.2' UNION
    SELECT '2_17_60755158e867c', 'Formation 3', '', 'Multiple_champs_paramètrables', 'texte court|Libellé;texte long|Motivation du responsable conduisant l''entretien', 14, 'CREF;4.2'

)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '2_607447155a1f3';
-- categorie "Formation de préparation à un concours ou examen professionnel"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '2_18_60744b38c89a6', 'Libellé des formations', '', 'Textarea', '', 2, 'CREF;5' UNION
    SELECT '2_18_60744afe9f52a', 'empty', 'Pour acquérir les bases et connaissances générales utiles à un concours, dans le cadre de ses perspectives professionnelles pour préparer un changement d''orientation pouvant impliquer le départ de son ministère ou de la fonction publique', 'Label', '', 1, null
)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '2_60744aa24dcab';
-- categorie "Formations pour construire un projet personnel à caractère professionnel"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '2_19_60744d51db3cd', 'Entretien de carrière', 'Pour évaluer son parcours et envisager des possibilités d''évolution professionnelle à 2~3 ans', 'Label', '', 9, 'CREF;6;ecarriere' UNION
    SELECT '2_19_60744dbef393b', 'Bilan de carrière', 'Pour renouveler ses perspectives professionnelles à 4~5 ans ou préparer un projet de seconde carrière ', 'Label', '', 11, 'CREF;6;bcarriere' UNION
    SELECT '2_19_60744d0f0925f', 'Congé de formation professionnelle', 'Pour suivre une formation', 'Label', '', 7, 'CREF;6;conge' UNION
    SELECT '2_19_60744bd69fb44', 'Bilan de compétences', 'Pour permettre une mobilité fonctionnelle ou géographique', 'Label', '', 3, 'CREF;6;bilan' UNION
    SELECT '2_19_60744cee1b307', 'empty', '', 'Textarea', '', 6, null UNION
    SELECT '2_19_60744d1729cb0', 'empty', '', 'Textarea', '', 8, null UNION
    SELECT '2_19_60744c902899b', 'Période de professionnalisation', 'Pour prévenir des risques d''inadaptation à l''évolution des méthodes et techniques, pour favoriser l''accès à des emplois exigeant des compétences nouvelles ou qualifications différentes, pour accéder à un autre corps ou cadre d''emplois, pour les agents qui reprennent leur activité professionnelle après un congé maternité ou parental.', 'Label', '', 5, 'CREF;6;periode' UNION
    SELECT '2_19_60744d68d6786', 'empty', '', 'Textarea', '', 10, null UNION
    SELECT '2_19_60744dceac729', 'empty', '', 'Textarea', '', 12, null UNION
    SELECT '2_19_60744bb9411f9', 'VAE : Validation des acquis de l''expérience', 'Pour obtenir un diplôme, d''un titre ou d''une certification inscrite au répertoire national des certifications professionnelles', 'Label', '', 1, 'CREF;6;VAE' UNION
    SELECT '2_19_60744cdce435b', 'empty', '', 'Textarea', '', 4, null UNION
    SELECT '2_19_60744cc11f86e', 'empty', '', 'Textarea', '', 2, null
)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '2_60744b5f4443d';
-- categorie "Règlementation"
INSERT INTO unicaen_autoform_champ (categorie, code, libelle, texte, element, options, ordre, mots_clefs)
WITH d(code, libelle, texte, element, options, ordre, mots_clefs ) AS (
    SELECT '2_20_60744f2c924a7', 'empty', 'Décret n°2007-1470 du 15 octobre 2007 relatif à la formation professionnelle tout au long de la vie des fonctionnaires de l''État <br> Article 5 :  <ul><li>  Le compte rendu de l''entretien de formation est établi sous la responsabilité du supérieur  hiérarchique.  </li><li>  Les objectifs de formation proposés pour l''agent y sont inscrits.  </li><li>  Le fonctionnaire en reçoit communication et peut y ajouter ses observations.  </li><li>  Ce compte rendu ainsi qu''une fiche retraçant les actions de formation auxquelles le fonctionnaire a participé sont versés à son dossier.  </li><li>  Les actions conduites en tant que formateur y figurent également.  </li><li>  Le fonctionnaire est informé par son supérieur hiérarchique des suites données à son entretien de formation.  </li><li>  Les refus opposés aux demandes de formation présentées à l''occasion de l''entretien de formation sont motivés. </li></ul>', 'Label', '', 1, null
)
SELECT cp.id, d.code, d.libelle, d.texte, d.element, d.options, d.ordre, d.mots_clefs
FROM d
JOIN unicaen_autoform_categorie cp ON cp.CODE = '2_60744ea79edce';