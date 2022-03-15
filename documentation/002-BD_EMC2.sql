-- NIVEAU --------------------------------------------------------------------------------------------------------------

create table niveau_definition
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
create unique index niveau_definition_id_uindex on niveau_definition (id);

create table niveau_enveloppe
(
    id serial not null constraint niveau_enveloppe_pk primary key,
    borne_inferieure_id integer not null constraint niveau_enveloppe_niveau_definition_id_fk references niveau_definition,
    borne_superieure_id integer not null constraint niveau_enveloppe_niveau_definition_id_fk_2 references niveau_definition,
    valeur_recommandee_id integer constraint niveau_enveloppe_niveau_definition_id_fk_3 references niveau_definition on delete set null,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction integer,
    histo_destructeur_id integer constraint niveau_enveloppe_unicaen_utilisateur_user_id_fk_3 references unicaen_utilisateur_user,
    description text
);
create unique index niveau_enveloppe_id_uindex on niveau_enveloppe (id);

-- MODULE CARRIERE -----------------------------------------------------------------------------------------------------

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
    niveau_id integer constraint corps_niveau_definition_id_fk references niveau_definition on delete set null
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
    niveaux_id integer constraint metier_niveau_enveloppe_id_fk references niveau_enveloppe on delete set null,
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


