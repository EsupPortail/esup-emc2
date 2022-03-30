-- MODULE CARRIERE -----------------------------------------------------------------------------------------------------

create table carriere_niveau
(
    id serial not null constraint niveau_definition_pk primary key,
    niveau integer not null,
    libelle varchar(1024) not null,
    description text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint niveau_definition_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint niveau_definition_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint niveau_definition_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user,
    label varchar(64) not null
);
create unique index niveau_definition_id_uindex on carriere_niveau (id);

create table carriere_niveau_enveloppe
(
    id serial not null constraint niveau_enveloppe_pk primary key,
    borne_inferieure_id integer not null constraint niveau_enveloppe_niveau_definition_id_fk references carriere_niveau,
    borne_superieure_id integer not null constraint niveau_enveloppe_niveau_definition_id_fk_2 references carriere_niveau,
    valeur_recommandee_id integer constraint niveau_enveloppe_niveau_definition_id_fk_3 references carriere_niveau on delete set null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction integer,
    histo_destructeur_id integer constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user,
    description text
);
create unique index niveau_enveloppe_id_uindex on carriere_niveau_enveloppe (id);

create table carriere_categorie
(
    id serial not null constraint categorie_pk primary key,
    code varchar(255) not null,
    libelle varchar(1024) not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint categorie_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint categorie_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint categorie_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index categorie_code_uindex on carriere_categorie (code);
create unique index categorie_id_uindex on carriere_categorie (id);

create table carriere_corps
(
    id integer not null constraint corps_pk primary key,
    lib_court varchar(20),
    lib_long varchar(200),
    code varchar(10) not null,
    categorie varchar(10),
    histo timestamp,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0),
    niveau integer,
    niveau_id integer constraint corps_niveau_definition_id_fk references carriere_niveau on delete set null
);
create unique index corps_code_uindex on carriere_corps (code);

create table carriere_correspondance
(
    id serial not null constraint correspondance_pk primary key,
    c_bap varchar(10),
    lib_court varchar(20),
    lib_long varchar(200),
    old_id integer,
    histo timestamp,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0)
);

create table carriere_grade
(
    id integer not null constraint grade_pk primary key,
    lib_court varchar(20),
    lib_long varchar(200),
    code varchar(10) not null,
    histo timestamp,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0)
);

-- MODULE METIER -------------------------------------------------------------------------------------------------------

create table metier_familleprofessionnelle
(
    id serial not null constraint metier_famille_pk primary key,
    libelle varchar(128) not null,
    couleur varchar(64),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint famille_professionnelle_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint famille_professionnelle_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint famille_professionnelle_user_id_fk_3 references unicaen_utilisateur_user
);

create table metier_domaine
(
    id serial not null constraint domaine_pk primary key,
    libelle varchar(256) not null,
    famille_id integer constraint domaine_famille_professionnelle_id_fk references metier_familleprofessionnelle,
    type_fonction varchar(256),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint domaine_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint domaine_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint domaine_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index domaine_id_uindex on metier_domaine (id);
create unique index metier_famille_id_uindex on metier_familleprofessionnelle (id);

create table metier_metier
(
    id serial not null constraint metier_pkey primary key,
    libelle_default varchar(256) not null,
    niveau integer,
    libelle_feminin varchar(256),
    libelle_masculin varchar(256),
    categorie_id integer constraint metier_categorie_id_fk references carriere_categorie on delete set null,
    niveaux_id integer constraint metier_niveau_enveloppe_id_fk references carriere_niveau_enveloppe on delete set null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint metier_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint metier_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint metier_user_id_fk_3 references unicaen_utilisateur_user
);

create unique index metier_id_uindex on metier_metier (id);

create table metier_metier_domaine
(
    metier_id integer not null constraint metier_domaine_metier_id_fk references metier_metier on delete cascade,
    domaine_id integer not null constraint metier_domaine_domaine_id_fk references metier_domaine on delete cascade,
    constraint metier_domaine_pk primary key (metier_id, domaine_id)
);

create table metier_referentiel
(
    id serial not null constraint metier_referentiel_pk primary key,
    libelle_court varchar(256) not null,
    libelle_long varchar(1024) not null,
    prefix varchar(1024) not null,
    type varchar(255),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint metier_referentiel_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint metier_referentiel_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint metier_referentiel_user_id_fk_3 references unicaen_utilisateur_user
);

create table metier_reference
(
    id serial not null constraint metier_reference_pk primary key,
    metier_id integer not null constraint metier_reference_metier_id_fk references metier_metier on delete cascade,
    referentiel_id integer not null constraint metier_reference_metier_referentiel_id_fk references metier_referentiel on delete cascade,
    code varchar(256) not null,
    lien varchar(1024),
    page integer,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint metier_reference_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint metier_reference_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint metier_reference_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index metier_reference_id_uindex on metier_reference (id);
create unique index metier_referentiel_id_uindex on metier_referentiel (id);

-- ELEMENT -----------------------------------------------------------------------------------------------------------

create table element_application_theme
(
    id serial not null constraint application_groupe_pk primary key,
    libelle varchar(1024),
    couleur varchar(255),
    ordre integer default 9999,
    histo_creation timestamp default ('now'::text)::date not null,
    histo_createur_id integer default 0 not null constraint application_groupe_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer  constraint application_groupe_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint application_groupe_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index application_groupe_id_uindex on element_application_theme (id);

create table element_application
(
    id serial not null constraint element_application_pkey primary key,
    libelle varchar(128) not null,
    description varchar(2048),
    url varchar(128),
    actif boolean default true not null,
    groupe_id integer constraint element_application_theme_id_fk references element_application_theme on delete set null,
    histo_creation timestamp default ('now'::text)::date not null,
    histo_createur_id integer default 0 not null constraint element_application_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer  constraint element_application_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint element_application_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index application_informations_id_uindex on element_application (id);

create table element_niveau
(
    id serial not null constraint maitrise_niveau_pk primary key,
    type varchar(256) not null,
    libelle varchar(256) not null,
    niveau integer not null,
    description text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint maitrise_niveau_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint maitrise_niveau_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint maitrise_niveau_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index maitrise_niveau_type_niveau_uindex on element_niveau (type, niveau);



create table element_application_element
(
    id serial not null constraint application_element_pk primary key,
    application_id integer not null constraint application_element_application_informations_id_fk references element_application on delete cascade,
    commentaire text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint application_element_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint application_element_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint application_element_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user,
    validation_id integer constraint application_element_unicaen_validation_instance_id_fk references unicaen_validation_instance on delete set null,
    niveau_id integer constraint application_element_maitrise_niveau_id_fk references element_niveau on delete set null,
    clef boolean
);
create unique index application_element_id_uindex on element_application_element (id);

create table element_competence_theme
(
    id serial not null constraint competence_theme_pk primary key,
    libelle varchar(256) not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint competence_theme_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer  constraint competence_theme_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint competence_theme_user_id_fk references unicaen_utilisateur_user
);
create unique index competence_theme_id_uindex on element_competence_theme (id);

create table element_competence_type
(
    id serial not null constraint competence_type_pk primary key,
    libelle varchar(256) not null,
    ordre integer,
    couleur varchar(255),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint competence_type_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp ,
    histo_modificateur_id integer constraint competence_type_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint competence_type_user_id_fk references unicaen_utilisateur_user
);
create unique index competence_type_id_uindex on element_competence_type (id);

create table element_competence
(
    id serial not null constraint competence_pk primary key,
    libelle varchar(256) not null,
    description text,
    histo_creation timestamp not null,
    type_id integer constraint competence_type__fk references element_competence_type on delete set null,
    theme_id integer constraint competence_theme__fk references element_competence_theme on delete set null,
    source varchar(256),
    id_source integer,
    histo_createur_id integer not null constraint competence_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint competence_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint competence_destructeur_fk references unicaen_utilisateur_user
);
create unique index competence_id_uindex on element_competence (id);

create table element_competence_element
(
    id serial not null constraint competence_element_pk primary key,
    competence_id integer not null constraint competence_element_competence_informations_id_fk references element_competence on delete cascade,
    commentaire text,
    validation_id integer constraint competence_element_unicaen_validation_instance_id_fk references unicaen_validation_instance on delete set null,
    niveau_id integer constraint competence_element_maitrise_niveau_id_fk references element_niveau on delete set null,
    clef boolean default false,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint competence_element_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer constraint competence_element_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint competence_element_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index competence_element_id_uindex on element_competence_element (id);

-- ELEMENT -> FORMATION ------------------------------------------------------------------------------------------------

create table formation_groupe
(
    id serial not null constraint formation_groupe_pk primary key,
    libelle varchar(1024),
    couleur varchar(255),
    ordre integer default 9999,
    source varchar(64),
    id_source integer,
    histo_createur_id integer not null constraint formation_groupe_createur_fk references unicaen_utilisateur_user,
    histo_creation timestamp not null,
    histo_modificateur_id integer constraint formation_groupe_modificateur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_destructeur_id integer constraint formation_groupe_destructeur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp
);
create unique index formation_groupe_id_uindex on formation_groupe (id);

create table formation_theme
(
    id serial not null constraint formation_theme_pk primary key,
    libelle varchar(1024) not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint formation_theme_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint formation_theme_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint formation_theme_destructeur_fk references unicaen_utilisateur_user
);
create unique index formation_theme_id_uindex on formation_theme (id);

create table formation
(
    id serial not null constraint formation_pk primary key,
    libelle varchar(256) not null,
    description text,
    lien varchar(1024),
    theme_id integer constraint formation_formation_theme_id_fk references formation_theme  on delete set null,
    groupe_id integer constraint formation_formation_groupe_id_fk references formation_groupe on delete set null,
    source varchar(64),
    id_source integer,
    histo_createur_id integer not null constraint formation_createur_fk references unicaen_utilisateur_user,
    histo_creation timestamp not null,
    histo_modificateur_id integer constraint formation_modificateur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_destructeur_id integer constraint formation_destructeur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp
);
create unique index formation_id_uindex on formation (id);

create table formation_element
(
    id serial not null constraint formation_element_pk primary key,
    formation_id integer not null constraint formation_element_formation_informations_id_fk references formation on delete cascade,
    commentaire text,
    validation_id integer constraint formation_element_unicaen_validation_instance_id_fk references unicaen_validation_instance on delete set null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint formation_element_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint formation_element_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint formation_element_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index formation_element_id_uindex on formation_element (id);

create table formation_obtenue_application
(
    formation_id integer not null constraint formation_application_obtenue_formation_id_fk references formation on delete cascade,
    application_element_id integer not null constraint formation_application_obtenue_application_element_id_fk references element_application_element on delete cascade,
    constraint formation_application_obtenue_pk primary key (formation_id, application_element_id)
);

create table formation_obtenue_competence
(
    formation_id integer not null constraint formation_obtenue_competence_formation_id_fk references formation on delete cascade,
    competence_element_id integer not null constraint formation_obtenue_competence_competence_element_id_fk references element_competence_element on delete cascade,
    constraint formation_obtenue_competence_pk primary key (formation_id, competence_element_id)
);

create table formation_parcours
(
    id serial not null constraint formation_parcours_pk primary key,
    type varchar(255),
    reference_id integer,
    libelle varchar(1024),
    description text,
    histo_creation timestamp not null,
    histo_createur_id integer not null,
    histo_modification timestamp,
    histo_modificateur_id integer,
    histo_destruction timestamp,
    histo_destructeur_id integer
);
create unique index formation_parcours_id_uindex on formation_parcours (id);

create table formation_parcours_formation
(
    id serial not null constraint formation_parcours_formation_pk primary key,
    parcours_id integer not null constraint formation_parcours_formation_formation_parcours_id_fk references formation_parcours on delete cascade,
    formation_id integer constraint formation_parcours_formation_formation_id_fk references formation on delete cascade,
    ordre integer,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint formation_parcours_formation_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint formation_parcours_formation_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint formation_parcours_formation_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index formation_parcours_formation_id_uindex on formation_parcours_formation (id);

create table formation_instance
(
    id serial not null constraint formation_instance_pk primary key,
    formation_id integer not null constraint formation_instance_formation_id_fk references formation on delete cascade,
    nb_place_principale integer default 0 not null,
    nb_place_complementaire integer default 0 not null,
    complement text,
    lieu varchar(256),
    type varchar(256),
    etat_id integer constraint formation_instance_unicaen_etat_etat_id_fk references unicaen_etat_etat,
    auto_inscription boolean default false not null,
    source varchar(64),
    id_source varchar(256),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint formation_instance_user_id_fk_1 references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint formation_instance_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint formation_instance_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index formation_instance_id_uindex on formation_instance (id);

create table formation_instance_inscrit
(
    id serial not null constraint formation_instance_inscrit_pk primary key,
    instance_id integer not null constraint formation_instance_inscrit_formation_instance_id_fk references formation_instance on delete cascade,
    agent_id varchar(40) not null constraint formation_instance_inscrit_agent_c_individu_fk references agent on delete cascade,
    liste varchar(64),
    questionnaire_id integer constraint formation_instance_inscrit_autoform_formulaire_instance_id_fk references unicaen_autoform_formulaire_instance on delete set null,
    source varchar(64),
    id_source integer,
    etat_id integer constraint formation_instance_inscrit_unicaen_etat_etat_id_fk references unicaen_etat_etat on delete set null,
    complement text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint formation_instance_inscrit_unicaen_utilisateur_user_id_fk_1 references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint formation_instance_inscrit_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint formation_instance_inscrit_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index formation_instance_inscrit_id_uindex on formation_instance_inscrit (id);

create table formation_instance_frais
(
    id serial not null constraint formation_instance_frais_pk primary key,
    inscrit_id integer not null constraint formation_instance_frais_formation_instance_inscrit_id_fk references formation_instance_inscrit on delete cascade,
    frais_repas double precision default 0,
    frais_hebergement double precision default 0,
    frais_transport double precision default 0,
    histo_creation timestamp not null,
    source varchar(64),
    id_source varchar(64),
    histo_createur_id integer not null constraint formation_instance_frais_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint formation_instance_frais_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint formation_instance_frais_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index formation_instance_frais_id_uindex on formation_instance_frais (id);

create table formation_instance_journee
(
    id serial not null constraint formation_instance_journee_pk primary key,
    instance_id integer not null constraint formation_instance_journee_formation_instance_id_fk references formation_instance on delete cascade,
    jour timestamp not null,
    debut varchar(64) not null,
    fin varchar(64) not null,
    lieu varchar(1024) not null,
    remarque text,
    source varchar(64),
    id_source varchar(64),
    histo_creation timestamp not null,
    histo_createur_id integer not null  constraint formation_instance_journee_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint formation_instance_journee_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint formation_instance_journee_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index formation_instance_journee_id_uindex on formation_instance_journee (id);

create table formation_instance_formateur
(
    id serial not null constraint formation_instance_formateur_pk primary key,
    instance_id integer not null constraint formation_instance_formateur_formation_instance_id_fk references formation_instance on delete cascade,
    prenom varchar(256) not null,
    nom varchar(256) not null,
    email varchar(1024),
    attachement varchar(1024),
    volume double precision,
    montant double precision,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint formation_instance_formateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint formation_instance_formateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint formation_instance_formateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index formation_instance_formateur_id_uindex on formation_instance_formateur (id);

create table formation_instance_presence
(
    id serial not null constraint formation_instance_presence_pk primary key,
    journee_id integer not null constraint formation_instance_presence_formation_instance_journee_id_fk references formation_instance_journee on delete cascade,
    inscrit_id integer not null constraint formation_instance_presence_formation_instance_inscrit_id_fk references formation_instance_inscrit on delete cascade,
    presence_type varchar(256) not null,
    presence_temoin boolean not null,
    commentaire text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint formation_instance_presence_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint formation_instance_presence_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint formation_instance_presence_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index formation_instance_presence_id_uindex on formation_instance_presence (id);




-- COMPLEMENT --------------------------------------------------------------------------------------------------------

create table complement
(
    id serial not null constraint complement_pk primary key,
    attachment_type varchar(1024) not null,
    attachment_id varchar(40) not null,
    complement_type varchar(1024),
    complement_id varchar(40),
    complement_text text,
    type varchar(1024) not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint complement_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint complement_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint complement_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index complement_id_uindex on complement (id);

-- AGENT -------------------------------------------------------------------------------------------------------------

create table agent
(
    c_individu varchar(40) not null constraint agent_pk primary key,
    utilisateur_id integer constraint agent_user_id_fk references unicaen_utilisateur_user on delete set null,
    prenom varchar(64),
    nom_usage varchar(64),
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0),
    octo_id varchar(40),
    preecog_id varchar(40),
    harp_id integer,
    login varchar(256),
    email varchar(1024),
    sexe varchar(1),
    t_contrat_long varchar(1),
    date_naissance date,
    nom_famille varchar(256)
);

-- STRUCTURE -----------------------------------------------------------------------------------------------------------

create table structure_type
(
    id integer not null constraint structure_type_pk primary key,
    code varchar(64) not null,
    libelle varchar(256) not null,
    synchro timestamp,
    histo timestamp,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0)
);
create unique index structure_type_source_id_uindex on structure_type (id);

create table structure
(
    id integer not null constraint structure_pk primary key,
    code varchar(40),
    libelle_court varchar(128),
    libelle_long varchar(1024),
    histo timestamp,
    ouverture timestamp,
    fermeture timestamp,
    resume_mere boolean default false,
    description text,
    type_id integer constraint structure_structure_type_id_fk references structure_type on delete set null,
    parent_id integer constraint structure_structure_source_id_fk references structure on delete set null,
    niv2_id integer constraint structure_structure_id_fk references structure on delete set null,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0)
);
create unique index structure_source_id_uindex on structure (id);

-- AGENT <-> CARRIERE --------------------------------------------------------------------------------------------------

create table agent_carriere_grade
(
    id varchar(40) not null constraint agent_grade_pk primary key,
    id_orig varchar(255),
    agent_id varchar(40) not null,
    structure_id integer,
    grade_id integer,
    corps_id integer,
    bap_id integer,
    d_debut timestamp,
    d_fin timestamp,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0)
);

create table agent_carriere_quotite
(
    id integer not null  constraint agent_quotite_pk primary key,
    agent_id varchar(40) not null constraint agent_quotite_agent_c_individu_fk references agent on delete cascade,
    debut timestamp,
    fin timestamp,
    quotite integer not null,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0)
);
create unique index agent_quotite_id_uindex on agent_carriere_quotite (id);

create table agent_carriere_affectation
(
    affectation_id integer not null constraint agent_affectation_pk primary key,
    agent_id varchar(40) not null,
    structure_id integer not null,
    date_debut timestamp not null,
    date_fin timestamp,
    id_orig varchar(255),
    t_principale varchar(1) default 'N'::character varying,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0)
);
create unique index agent_affectation_id_uindex on agent_carriere_affectation (affectation_id);

create table agent_carriere_statut
(
    id varchar(40) not null constraint agent_statut_pk primary key,
    id_orig varchar(256),
    c_source varchar(40),
    agent_id varchar(40) not null,
    structure_id integer constraint agent_statut_structure_source_id_fk references structure  on delete set null,
    d_debut timestamp not null,
    d_fin timestamp,
    structure_id_old integer,
    octo_id integer,
    preecog_id integer,
    t_titulaire varchar(1) not null,
    t_cdi varchar(1) not null,
    t_cdd varchar(1) not null,
    t_vacataire varchar(1) not null,
    t_enseignant varchar(1) not null,
    t_administratif varchar(1) not null,
    t_chercheur varchar(1) not null,
    t_etudiant varchar(1) not null,
    t_auditeur_libre varchar(1) not null,
    t_doctorant varchar(1) not null,
    t_detache_in varchar(1) not null,
    t_detache_out varchar(1) not null,
    t_dispo varchar(1) not null,
    t_heberge varchar(1) not null,
    t_emerite varchar(1) not null,
    t_retraite varchar(1) not null,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0)
);

create table agent_element_application
(
    agent_id varchar(40) not null constraint agent_application_agent_c_individu_fk references agent on delete cascade,
    application_element_id integer not null constraint agent_application_application_element_id_fk references element_application_element on delete cascade,
    constraint agent_application_pk primary key (agent_id, application_element_id)
);

create table agent_element_competence
(
    agent_id varchar(40) not null constraint agent_competence_agent_c_individu_fk references agent on delete cascade,
    competence_element_id integer not null constraint agent_competence_competence_element_id_fk references element_competence_element on delete cascade,
    constraint agent_competence_pk primary key (agent_id, competence_element_id)
);

create table agent_element_formation
(
    agent_id varchar(40) not null constraint agent_formation_agent_c_individu_fk references agent on delete cascade,
    formation_element_id integer not null constraint agent_formation_formation_element_id_fk references formation_element on delete cascade,
    constraint agent_formation_pk primary key (agent_id, formation_element_id)
);

create table agent_complement
(
    agent_id varchar(40) not null constraint agent_complement_agent_c_individu_fk references agent on delete cascade,
    complement_id integer not null constraint agent_complement_complement_id_fk references complement on delete cascade,
    constraint agent_complement_pk primary key (agent_id, complement_id)
);



-- STRUCTURE <-> AGENT -------------------------------------------------------------------------------------------------

create table structure_gestionnaire
(
    agent_id varchar(40) not null constraint structure_gestionnaire_agent_c_individu_fk references agent on delete cascade,
    structure_id integer not null constraint structure_gestionnaire_structure_source_id_fk references structure on delete cascade,
    constraint structure_gestionnaire_pk primary key (agent_id, structure_id)
);

create table structure_responsable
(
    id integer not null constraint structure_responsable_pk primary key,
    structure_id integer not null,
    agent_id varchar(40) not null,
    fonction_id integer,
    date_debut timestamp,
    date_fin timestamp,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0),
    imported boolean default true
);

create table structure_gestionnaire
(
    id integer not null constraint structure_gestionnaire_pk primary key,
    structure_id integer not null,
    agent_id varchar(40) not null,
    fonction_id integer,
    date_debut timestamp,
    date_fin timestamp,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0),
    imported boolean default true
);

create table structure_agent_force
(
    id serial not null constraint structure_agent_force_pk  primary key,
    structure_id integer not null constraint structure_agent_force_structure_id_fk references structure on delete cascade,
    agent_id varchar(40) not null constraint structure_agent_force_agent_c_individu_fk references agent,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint structure_agent_force_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint structure_agent_force_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint structure_agent_force_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index structure_agent_force_id_uindex on structure_agent_force (id);

-- AGENT <-> CCC -------------------------------------------------------------------------------------------------------

create table agent_ccc_ppp
(
    id serial not null constraint agent_ppp_pk primary key,
    agent_id varchar(64) not null constraint agent_ppp_agent_c_individu_fk references agent on delete cascade,
    type varchar(1024) not null,
    libelle varchar(1024) not null,
    complement text,
    date_debut timestamp,
    date_fin timestamp,
    etat_id integer constraint agent_ppp_unicaen_etat_etat_id_fk references unicaen_etat_etat on delete set null,
    formation_cpf double precision,
    formation_cout double precision,
    formation_prisencharge double precision,
    formation_organisme varchar(1024),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint agent_ppp_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint agent_ppp_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint agent_ppp_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index agent_ppp_id_uindex on agent_ccc_ppp (id);

create table agent_ccc_stageobs
(
    id serial not null constraint agent_stageobs_pk primary key,
    agent_id varchar(64) not null constraint agent_stageobs_agent_c_individu_fk references agent on delete cascade,
    structure_id integer constraint agent_stageobs_structure_id_fk references structure on delete set null,
    metier_id integer constraint agent_stageobs_metier_id_fk references metier_metier on delete set null,
    complement text,
    date_debut timestamp,
    date_fin timestamp,
    etat_id integer constraint agent_stageobs_unicaen_etat_etat_id_fk references unicaen_etat_etat on delete set null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint agent_stageobs_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint agent_stageobs_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint agent_stageobs_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index agent_stageobs_id_uindex on agent_ccc_stageobs (id);

create table agent_ccc_tutorat
(
    id serial not null constraint agent_tutorat_pk primary key,
    agent_id varchar(64) not null constraint agent_tutorat_agent_c_individu_fk references agent on delete cascade,
    cible_id varchar(64) constraint agent_tutorat_agent_c_individu_fk_2 references agent on delete set null,
    metier_id integer constraint agent_tutorat_metier_id_fk references metier_metier on delete set null,
    date_debut timestamp,
    date_fin timestamp,
    complement text,
    formation boolean,
    etat_id integer constraint agent_tutorat_unicaen_etat_etat_id_fk references unicaen_etat_etat on delete set null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint agent_tutorat_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint agent_tutorat_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint agent_tutorat_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index agent_tutorat_id_uindex on agent_ccc_tutorat (id);

create table agent_ccc_accompagnement
(
    id serial not null constraint agent_accompagnement_pk primary key,
    agent_id varchar(64) not null constraint agent_accompagnement_agent_c_individu_fk references agent on delete cascade,
    cible_id varchar(64) constraint agent_accompagnement_agent_c_individu_fk_2 references agent on delete set null,
    bap_id integer constraint agent_accompagnement_correspondance_id_fk references carriere_correspondance on delete set null,
    corps_id integer constraint agent_accompagnement_corps_id_fk references carriere_corps on delete set null,
    complement text,
    resultat boolean,
    etat_id integer constraint agent_accompagnement_unicaen_etat_etat_id_fk references unicaen_etat_etat on delete set null,
    date_debut timestamp,
    date_fin timestamp,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint agent_accompagnement_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint agent_accompagnement_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint agent_accompagnement_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index agent_accompagnement_id_uindex on agent_ccc_accompagnement (id);

-- MISSIONS PRINCIPALES ------------------------------------------------------------------------------------------------

create table activite
(
    id serial not null constraint activite_pkey primary key,
    histo_creation timestamp not null,
    histo_modification timestamp,
    histo_destruction timestamp,
    histo_createur_id integer not null constraint activite_user_id_fk references unicaen_utilisateur_user,
    histo_modificateur_id integer constraint activite_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destructeur_id integer constraint activite_user_id_fk_3 references unicaen_utilisateur_user,
    niveaux_id integer constraint activite_niveau_enveloppe_id_fk references carriere_niveau_enveloppe on delete set null
);
create unique index activite_id_uindex on activite (id);

create table activite_libelle
(
    id serial not null constraint activite_libelle_pk primary key,
    activite_id integer not null,
    libelle text not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint activite_description_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint activite_description_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint activite_description_user_id_fk references unicaen_utilisateur_user
);

create table activite_description
(
    id serial not null constraint activite_description_pk primary key,
    activite_id integer not null constraint activite_description_activite_fk references activite on delete cascade,
    description text not null,
    ordre integer,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint activite_description_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint activite_description_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint activite_description_user_id_fk references unicaen_utilisateur_user
);
create unique index activite_description_id_uindex on activite_description (id);

create table activite_application
(
    activite_id integer not null constraint activite_application_activite_id_fk references activite on delete cascade,
    application_element_id integer not null constraint activite_application_application_element_id_fk references element_application_element on delete cascade,
    constraint activite_application_pk primary key (activite_id, application_element_id)
);

create table activite_competence
(
    activite_id integer not null constraint activite_competence_activite_id_fk references activite on delete cascade,
    competence_element_id integer not null constraint activite_competence_competence_element_id_fk references element_competence_element on delete cascade,
    constraint activite_competence_pk primary key (activite_id, competence_element_id)
);

create table activite_formation
(
    activite_id integer not null constraint activite_formation_activite_id_fk references activite on delete cascade,
    formation_element_id integer not null constraint activite_formation_formation_element_id_fk references formation_element on delete cascade,
    constraint activite_formation_pk primary key (activite_id, formation_element_id)
);

create table activite_domaine
(
    activite_id integer not null constraint activite_domaine_activite_id_fk references activite on delete cascade,
    domaine_id integer not null constraint activite_domaine_domaine_id_fk references metier_domaine on delete cascade,
    constraint activite_domaine_pk primary key (activite_id, domaine_id)
);

-- MISSIONS SPECIFIQUES ------------------------------------------------------------------------------------------------

create table mission_specifique_theme
(
    id serial not null constraint mission_specifique_theme_pk primary key,
    libelle varchar(256) not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint mission_specifique_theme_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint mission_specifique_theme_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint mission_specifique_theme_destructeur_fk references unicaen_utilisateur_user
);
create unique index mission_specifique_theme_id_uindex on mission_specifique_theme (id);

create table mission_specifique_type
(
    id serial not null constraint mission_specifique_type_pk primary key,
    libelle varchar(256) not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint mission_specifique_type_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint mission_specifique_type_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint mission_specifique_type_destructeur_fk references unicaen_utilisateur_user
);

create table mission_specifique
(
    id serial not null constraint mission_specifique_pk primary key,
    libelle varchar(256) not null,
    theme_id integer constraint mission_specifique_mission_specifique_theme_id_fk references mission_specifique_theme on delete set null,
    type_id integer constraint mission_specifique_mission_specifique_type_id_fk references mission_specifique_type on delete set null,
    description text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint mission_specifique_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint mission_specifique_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint mission_specifique_destructeur_fk references unicaen_utilisateur_user
);
create unique index mission_specifique_id_uindex on mission_specifique (id);
create unique index mission_specifique_type_id_uindex on mission_specifique_type (id);

create table agent_missionspecifique
(
    id serial not null constraint agent_missionspecifique_pk primary key,
    agent_id varchar(40) not null,
    mission_id integer not null constraint agent_missionspecifique_mission_specifique_id_fk references mission_specifique on delete cascade,
    structure_id integer constraint agent_missionspecifique_structure_source_id_fk references structure on delete set null,
    date_debut timestamp,
    date_fin timestamp,
    commentaire varchar(2048),
    decharge double precision,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint agent_missionspecifique_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint agent_missionspecifique_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint agent_missionspecifique_destructeur_fk references unicaen_utilisateur_user
);
create unique index agent_missionspecifique_id_uindex on agent_missionspecifique (id);

create table agent_fichier
(
    agent varchar(40) not null,
    fichier varchar(13) not null constraint agent_fichier_fichier_fk references fichier_fichier on delete cascade,
    constraint agent_fichier_pk primary key (agent, fichier)
);





-- FICHE_METIER --------------------------------------------------------------------------------------------------------

create table fichemetier
(
    id serial not null constraint fiche_type_metier_pkey primary key,
    metier_id integer not null constraint fichetype_metier__fk references metier_metier,
    expertise boolean default false,
    etat_id integer default 0 constraint fichemetier_unicaen_etat_etat_id_fk references unicaen_etat_etat on delete set null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint fichemetier_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp not null,
    histo_modificateur_id integer not null,
    histo_destruction timestamp,
    histo_destructeur_id integer
);

create table fichemetier_application
(
    fichemetier_id integer not null constraint fichemetier_application_fichemetier_id_fk references fichemetier on delete cascade,
    application_element_id integer not null constraint fichemetier_application_application_element_id_fk references element_application_element on delete cascade,
    constraint fichemetier_application_pk primary key (fichemetier_id, application_element_id)
);

create table fichemetier_competence
(
    fichemetier_id integer not null constraint fichemetier_competence_fichemetier_id_fk references fichemetier on delete cascade,
    competence_element_id integer not null constraint fichemetier_competence_competence_element_id_fk references element_competence_element on delete cascade,
    constraint fichemetier_competence_pk primary key (fichemetier_id, competence_element_id)
);

create table fichemetier_activite
(
    id serial not null constraint fichemetier_activite_pkey primary key,
    fiche integer not null constraint fichemetier_activite_fichemetier_id_fk references fichemetier on delete cascade,
    activite integer not null constraint fichemetier_activite_activite_id_fk references activite on delete cascade,
    position integer default 0 not null
);
create unique index fichemetier_activite_id_uindex on fichemetier_activite (id);

create table fichemetier_formation
(
    fiche_metier_id integer not null constraint fiche_metier_formation_fiche_metier_id_fk references fichemetier on delete cascade,
    formation_id integer not null constraint fiche_metier_formation_formation_id_fk references formation on delete cascade,
    constraint fiche_metier_formation_pk primary key (fiche_metier_id, formation_id)
);

create table configuration_fichemetier
(
    id serial not null constraint configuration_fichemetier_pk primary key,
    operation varchar(64) not null,
    entity_type varchar(255),
    entity_id varchar(255),
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint configuration_fichemetier_createur_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint configuration_fichemetier_modificateur_fk references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint configuration_fichemetier_destructeur_fk references unicaen_utilisateur_user
);
create unique index configuration_fichemetier_id_uindex on configuration_fichemetier (id);

-- FICHE POSTE -------------------------------------------------------------------------------------------

create table ficheposte
(
    id serial not null constraint ficheposte_pkey primary key,
    libelle varchar(256),
    agent varchar(40),
    octo_id varchar(40),
    preecog_id varchar(40),
    etat_id integer constraint ficheposte_unicaen_etat_etat_id_fk references unicaen_etat_etat on delete set null,
    histo_creation timestamp not null,
    histo_modification timestamp,
    histo_destruction timestamp,
    histo_createur_id integer not null constraint ficheposte_createur_fk references unicaen_utilisateur_user on delete cascade,
    histo_modificateur_id integer constraint ficheposte_modificateur_fk references unicaen_utilisateur_user on delete cascade,
    histo_destructeur_id integer
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



create table fichemetier_fichetype
(
    id serial not null constraint fichemetier_fichetype_pkey primary key,
    fiche_id integer not null constraint fichemetier_fichetype_fiche_type_metier_id_fk references fichemetier on delete cascade,
    activite integer not null constraint fichemetier_fichetype_activite_id_fk references activite on delete cascade,
    position integer default 0 not null
);
create unique index fichemetier_fichetype_id_uindex on ficheposte_fichemetier (id);

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

create table structure_ficheposte
(
    structure_id integer not null constraint structure_ficheposte_structure_id_fk references structure on delete cascade,
    ficheposte_id integer not null constraint structure_ficheposte_fiche_poste_id_fk references ficheposte on delete cascade,
    constraint structure_ficheposte_pk primary key (structure_id, ficheposte_id)
);
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

-- FICHE PROFIL ---------------

create table ficheprofil
(
    id serial not null constraint ficheprofil_pk primary key,
    ficheposte_id integer not null constraint ficheprofil_fiche_poste_id_fk references ficheposte on delete cascade,
    contexte text,
    mission text,
    niveau text,
    contrat text,
    renumeration text,
    date_dossier timestamp not null,
    lieu text,
    structure_id integer not null constraint ficheprofil_structure_id_fk references structure,
    vacance_emploi boolean default false not null,
    adresse varchar(1024) not null,
    date_audition date,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint ficheprofil_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint ficheprofil_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer constraint ficheprofil_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index ficheprofil_id_uindex on ficheprofil (id);



