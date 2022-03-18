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
    fonction_id integer not null,
    date_debut timestamp,
    date_fin timestamp,
    created_on timestamp(0) default ('now'::text)::timestamp(0) without time zone not null,
    updated_on timestamp(0),
    deleted_on timestamp(0)
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

-- ELEMENTS -------------------------------------------------------------------------------------------------------------


-- ACTIVITE -------------------------------------

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

-- MISSIONS PRINCIPALES ------------------------------------------------------------------------------------------------
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

alter table agent_missionspecifique owner to ad_preecog_prod;

create unique index agent_missionspecifique_id_uindex
    on agent_missionspecifique (id);



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


