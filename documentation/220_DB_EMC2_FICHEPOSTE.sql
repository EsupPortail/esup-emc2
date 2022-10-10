-- !!attention!! AGENT doit déjà être dans le schéma
--  ==> utilisé dans ficheposte

-- FICHE POSTE -------------------------------------------------------------------------------------------

create table ficheposte
(
    id serial not null constraint ficheposte_pkey primary key,
    libelle varchar(256),
    agent varchar(40),
    etat_id integer constraint ficheposte_unicaen_etat_etat_id_fk references unicaen_etat_etat on delete set null,
    rifseep integer,
    nbi integer,
    fin_validite timestamp,
    histo_creation timestamp not null,
    histo_modification timestamp,
    histo_destruction timestamp,
    histo_createur_id integer not null constraint ficheposte_createur_fk references unicaen_utilisateur_user on delete cascade,
    histo_modificateur_id integer constraint ficheposte_modificateur_fk references unicaen_utilisateur_user on delete cascade,
    histo_destructeur_id integer constraint ficheposte_destructeur_fk references unicaen_utilisateur_user on delete cascade
);
create unique index fiche_metier_id_uindex on ficheposte (id);

create table ficheposte_expertise
(
    id serial not null constraint expertise_pk primary key,
    ficheposte_id integer not null constraint expertise_ficheposte_fk references ficheposte on delete cascade,
    libelle text,
    description text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint expertise_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint expertise_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint expertise_destructeur_fk references unicaen_utilisateur_user
);

create table ficheposte_specificite
(
    id serial not null constraint ficheposte_specificite_pk primary key,
    ficheposte_id integer constraint ficheposte_specificite_fiche_metier_id_fk references ficheposte on delete cascade,
    specificite text,
    encadrement text,
    relations_internes text,
    relations_externes text,
    contraintes text,
    moyens text,
    formations text
);
create unique index ficheposte_specificite_id_uindex on ficheposte_specificite (id);

create table ficheposte_activite_specifique
(
    id serial not null constraint specificite_activite_pk primary key,
    specificite_id integer not null constraint specificite_activite_specificite_poste_id_fk references ficheposte_specificite on delete cascade,
    activite_id integer not null constraint specificite_activite_activite_id_fk references activite on delete cascade,
    retrait varchar(1024),
    description text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint specificite_activite_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint specificite_activite_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint specificite_activite_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index specificite_activite_id_uindex on ficheposte_activite_specifique (id);

create table ficheposte_fichemetier
(
    id serial not null constraint fiche_type_externe_pk primary key,
    fiche_poste integer not null constraint fiche_type_externe_fiche_metier_id_fk references ficheposte on delete cascade,
    fiche_type integer not null constraint fiche_type_externe_fiche_type_metier_id_fk references ficheposte_fichemetier on delete cascade,
    quotite integer not null,
    principale boolean,
    activites varchar(128)
);
create unique index fiche_type_externe_id_uindex on ficheposte_fichemetier (id);

create table ficheposte_activitedescription_retiree
(
    id serial not null constraint ficheposte_activitedescription_retiree_pk primary key,
    ficheposte_id integer not null constraint fadr_ficheposte_fk references ficheposte on delete cascade,
    fichemetier_id integer not null constraint fadr_fichemetier_fk references fichemetier on delete cascade,
    activite_id integer not null constraint fadr_activite_fk references activite on delete cascade,
    description_id integer not null constraint fadr_description_fk references activite_description on delete cascade,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint fadr_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint fadr_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint fadr_destructeur_id references unicaen_utilisateur_user
);
create unique index ficheposte_activitedescription_retiree_id_uindex on ficheposte_activitedescription_retiree (id);

create table ficheposte_application_retiree
(
    id serial not null constraint ficheposte_application_conservee_pk primary key,
    ficheposte_id integer not null constraint fcc_ficheposte_fk references ficheposte on delete cascade,
    application_id integer not null constraint fcc_application_fk references element_application on delete cascade,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint fcc_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint fcc_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint fcc_destructeur_fk references unicaen_utilisateur_user
);
create unique index ficheposte_application_conservee_id_uindex on ficheposte_application_retiree (id);

create table ficheposte_competence_retiree
(
    id serial not null constraint ficheposte_competence_conservee_pk primary key,
    ficheposte_id integer not null constraint fcc_ficheposte_fk references ficheposte on delete cascade,
    competence_id integer not null constraint fcc_competence_fk references element_competence on delete cascade,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint fcc_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint fcc_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint fcc_destructeur_fk references unicaen_utilisateur_user
);
create unique index ficheposte_competence_conservee_id_uindex on ficheposte_competence_retiree (id);

create table ficheposte_fichemetier_domaine
(
    id serial not null constraint ficheposte_fichemetier_domaine_pk primary key,
    fichemetierexterne_id integer not null constraint ficheposte_fichemetier_domaine_fiche_type_externe_id_fk references ficheposte_fichemetier on delete cascade,
    domaine_id integer not null constraint ficheposte_fichemetier_domaine_domaine_id_fk references metier_domaine on delete cascade,
    quotite integer default 100 not null
);
create unique index ficheposte_fichemetier_domaine_id_uindex on ficheposte_fichemetier_domaine (id);

create table ficheposte_formation_retiree
(
    id serial not null constraint ficheposte_formation_conservee_pk primary key,
    ficheposte_id integer not null constraint ffc_ficheposte_fk references ficheposte on delete cascade,
    formation_id integer not null constraint ffc_formation_fk references formation on delete cascade,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint ffc_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer  constraint ffc_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint ffc_destructeur_fk references unicaen_utilisateur_user
);
create unique index ficheposte_formation_conservee_id_uindex on ficheposte_formation_retiree (id);

create table ficheposte_validation
(
    ficheposte_id integer not null constraint ficheposte_validations_ficheposte_id_fk references ficheposte on delete cascade,
    validation_id integer not null constraint ficheposte_validations_unicaen_validation_instance_id_fk references unicaen_validation_instance on delete cascade,
    constraint ficheposte_validations_pk primary key (ficheposte_id, validation_id)
);


------------------------------------------------------------------------------------------------------------------------
-- PRIVILEGE -----------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('ficheposte', 'Fiche de poste', 2, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'ficheposte_index', 'Accéder à l''index des fiches de poste', 0 UNION
    SELECT 'ficheposte_afficher', 'Afficher une fiche de poste', 10 UNION
    SELECT 'ficheposte_ajouter', 'Ajouter une fiche de poste', 20 UNION
    SELECT 'ficheposte_modifier', 'Modifier une fiche de poste', 30 UNION
    SELECT 'ficheposte_associeragent', 'Associer un agent à une fiche de poste', 31 UNION
    SELECT 'ficheposte_historiser', 'Historiser/Restaurer une fiche de poste', 40 UNION
    SELECT 'ficheposte_detruire', 'Détruire une fiche de poste ', 50 UNION
    SELECT 'ficheposte_etat', 'Modifier l''état', 200 UNION
    SELECT 'ficheposte_valider_responsable', 'Valider en tant que responsable', 250 UNION
    SELECT 'ficheposte_valider_agent', 'Valider en tant qu''agent', 260 UNION
    SELECT 'ficheposte_graphique', 'Afficher les graphiques de compatibilité', 300
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'ficheposte';

------------------------------------------------------------------------------------------------------------------------
-- ETAT ----------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_etat_etat_type (code, libelle, icone, couleur, histo_creation, histo_createur_id)
VALUES ('FICHE_POSTE', 'États associés aux fiches de poste', 'fas fa-book-reader', '#00d88d', current_date, 0);
INSERT INTO unicaen_etat_etat(type_id, code, libelle, ordre, icone, couleur, histo_creation, histo_createur_id)
WITH d(code, libelle, ordre, icone, couleur, histo_creation, histo_createur_id) AS (
    SELECT 'FICHE_POSTE_REDACTION', 'Fiche de poste en cours de rédaction', 1, 'fas fa-pencil-ruler', '#ff9500', current_date, 0 UNION
    SELECT 'FICHE_POSTE_OK', 'Fiche de poste validée', 2, 'fas fa-check', '#00a004', current_date, 0 UNION
    SELECT 'FICHE_POSTE_MASQUEE', 'Fiche de poste masquée', 4, 'fas fa-mask', '#cb0000', current_date, 0 UNION
    SELECT 'FICHE_POSTE_SIGNEE', 'Fiche de poste signée', 3, 'fas fa-signature', '#000099', current_date, 0
)
SELECT cp.id, d.code, d.libelle, d.ordre, d.icone, d.couleur, d.histo_creation, d.histo_createur_id
FROM d
JOIN unicaen_etat_etat_type cp ON cp.CODE = 'FICHE_POSTE';

------------------------------------------------------------------------------------------------------------------------
-- VALIDATION-----------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id) VALUES ('FICHEPOSTE_RESPONSABLE', 'Validation du responsable', false, now(),0);
INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id) VALUES ('FICHEPOSTE_AGENT', 'Validation de l''agent', false, now(),0);

------------------------------------------------------------------------------------------------------------------------
-- TEMPLATE ------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css)
VALUES ('FICHE_POSTE_VALIDATION_AGENT', null, 'mail', 'Validation de VAR[AGENT#Denomination] de sa fiche de poste', '<p>Bonjour,</p>
<p>VAR[AGENT#Denomination] vient de valider sa fiche de poste.</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences<br />VAR[EMC2#Nom]</p>', null);
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css)
VALUES ('FICHE_POSTE_VALIDATION_RESPONSABLE', '<p>Mail envoyé à l''agent·e après la validation d''une fiche de poste par le·la responsable de l''agent·e</p>', 'mail', 'Votre fiche de poste de poste vient d''être validée par votre responsable', '<p>Université de Caen Normandie<br />Direction des Ressources Humaines<br /><br />Bonjour,<br /><br />Votre fiche de poste vient d''être validée par votre responsable.</p>
<p>Vous pouvez maintenant vous rendre dans EMC2 pour valider à votre tour celle-ci.<br />Vous retrouverez celle-ci à l''adresse suivante : VAR[URL#FichePosteAcceder]</p>
<p><br />Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences<br />VAR[EMC2#Nom]</p>', null);
