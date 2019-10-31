-- role de validateur
insert into user_role (id, libelle, role_id, is_default)
values (6, 'Validateur', 'Validateur', false);

-- categorie privilege
insert into categorie_privilege (id, code, libelle, ordre)
values (20, 'validation', 'Gestion des validations', 700);

-- privilege
insert into privilege (id, categorie_id, code, libelle, ordre)
values (1,1,1,1,1);

-- tables

-- VALIDATION TYPE --
create table validation_type
(
    id serial not null constraint validation_type_pk primary key,
    code varchar(255) not null,
    libelle varchar(1024) not null
);
create unique index validation_type_id_uindex on validation_type (id);
create unique index validation_type_code_uindex on validation_type (code);

INSERT INTO validation_type (id, code, libelle)
VALUES (1, 'FICHE-METIER-RELECTURE', 'Relecture de fiche métier');

-- VALIDATION VALEUR
create table if not exists validation_valeur
(
    id serial not null constraint validation_valeur_pk primary key,
    code varchar(255) not null,
    libelle varchar(1023)
);

create unique index if not exists validation_valeur_id_uindex on validation_valeur (id);
create unique index if not exists validation_valeur_code_uindex on validation_valeur (code);

INSERT INTO validation_valeur (id, code, libelle) VALUES (1, 'VALIDER', 'Validé');
INSERT INTO validation_valeur (id, code, libelle) VALUES (2, 'REJETER', 'Rejeté');
INSERT INTO validation_valeur (id, code, libelle) VALUES (3, 'A MODIFIER', 'À modifier');

-- VALIDATION VALIDATION --
create table validation_validation
(
    id serial not null constraint validation_validation_pk primary key,
    type_id integer not null constraint validation_validation_validation_type_id_fk
            references validation_type on delete set null,
    valeur_id integer constraint validation_validation_validation_valeur_id_fk
            references validation_valeur on delete set null,
    commentaire text,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint validation_validation_user_id_fk_2
            references "user",
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint validation_validation_user_id_fk_3
            references "user",
    histo_destruction timestamp,
    histo_destructeur_id integer constraint validation_validation_user_id_fk_4
            references "user",
    object_id varchar(40)
);

create unique index validation_validation_id_uindex on validation_validation (id);

-- VALIDATION DEMANDE --
create table validation_demande
(
    id serial not null constraint validation_demande_pk primary key,
    type_id integer not null constraint demande_validation_type_id_fk
            references validation_type,
    entity varchar(1023),
    object_id varchar(40),
    validateur_id integer not null constraint demande_validateur_id_fk
            references "user",
    validation_id integer constraint demande_validation_id_fk
            references validation_validation,
    histo_creation timestamp not null,
    histo_createur_id integer not null constraint demande_createur_id_fk
            references "user",
    histo_modification timestamp not null,
    histo_modificateur_id integer not null constraint demande_modificateur_id_fk
            references "user",
    histo_destruction timestamp,
    histo_destructeur_id integer constraint demande_destructeur_id_fk
            references "user"
);

create unique index validation_demande_id_uindex on validation_demande (id);



