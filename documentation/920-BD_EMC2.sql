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
    structure_id bigint not null constraint ficheprofil_structure_id_fk references structure,
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
