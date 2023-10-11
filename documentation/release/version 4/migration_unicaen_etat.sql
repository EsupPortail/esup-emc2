-- ----------------------------------------------------------------------------
-- Passage à UnicaenEtat ------------------------------------------------------
-- ----------------------------------------------------------------------------

-- Tables de la librairie -----------------------------------------------------

create table unicaen_etat_categorie
(
    id                      serial          not null constraint unicaen_etat_categorie_pk primary key,
    code                    varchar(256)    not null,
    libelle                 varchar(256)    not null,
    icone                   varchar(256),
    couleur                 varchar(256),
    ordre                   integer
);
create unique index unicaen_etat_categorie_id_uindex on unicaen_etat_categorie (id);

create table unicaen_etat_type
(
    id                      serial          not null constraint unicaen_etat_type_pk primary key,
    code                    varchar(256)    not null,
    libelle                 varchar(256)    not null,
    categorie_id            integer         constraint unicaen_etat_type_categorie_id_fk references unicaen_etat_categorie,
    icone                   varchar(256),
    couleur                 varchar(256),
    ordre                   integer         not null default 9999
);
create unique index unicaen_etat_type_id_uindex on unicaen_etat_type (id);

create table unicaen_etat_instance
(
    id                      serial          constraint unicaen_etat_instance_pk primary key,
    type_id                 integer         not null constraint unicaen_etat_instance_type_id references unicaen_etat_type,
    histo_creation          timestamp       default now() not null,
    histo_createur_id       integer         default 0 not null constraint unicaen_content_content_user_id_fk references unicaen_utilisateur_user,
    histo_modification      timestamp,
    histo_modificateur_id   integer         constraint unicaen_content_content_user_id_fk_2 references unicaen_utilisateur_user,
    histo_destruction       timestamp,
    histo_destructeur_id    integer         constraint unicaen_content_content_user_id_fk_3 references unicaen_utilisateur_user
);
create unique index unicaen_etat_instance_id_index on unicaen_etat_instance (id);

-- Linkers du module formation ------------------------------------------------

create table formation_demande_externe_etat
(
    demande_id              integer         not null constraint formation_demande_externe_etat_formation_demande_externe_id_fk references formation_demande_externe on delete cascade,
    etat_id                 integer         not null constraint formation_demande_externe_etat_unicaen_etat_instance_id_fk references unicaen_etat_instance on delete cascade,
    constraint formation_demande_externe_etat_pk primary key (demande_id, etat_id)
);

create table formation_session_etat
(
    session_id              integer         not null constraint formation_instance_etat_session_id_fk references formation_instance on delete cascade,
    etat_id                 integer         not null constraint formation_instance_etat_etat_id_fk references unicaen_etat_instance on delete cascade,
    constraint formation_session_etat_pk primary key (session_id, etat_id)
);

create table formation_inscription_etat
(
    inscription_id          integer         not null constraint formation_inscription_etat_inscription_id_fk references formation_instance_inscrit on delete cascade,
    etat_id                 integer         not null constraint formation_inscription_etat_etat_id_fk references unicaen_etat_instance on delete cascade,
    constraint formation_inscription_etat_pk primary key (inscription_id, etat_id)
);

-- Linker Entretien Professionnel

create table entretienprofessionnel_etat
(
    entretien_id            integer         not null constraint entretienprofessionnel_etat_entretien_id_fk references entretienprofessionnel on delete cascade,
    etat_id                 integer         not null constraint entretienprofessionnel_etat_etat_id_fk references unicaen_etat_instance on delete cascade,
    constraint entretienprofessionnel_etat_pk primary key (entretien_id, etat_id)
);

-- Linker FichePoste/FicheMetier

create table fichemetier_etat
(
    fichemetier_id          integer         not null constraint fichemetier_etat_fichemetier_id_fk references fichemetier on delete cascade,
    etat_id                 integer         not null constraint fichemetier_etat_etat_id_fk references unicaen_etat_instance on delete cascade,
    constraint fichemetier_etat_pk primary key (fichemetier_id, etat_id)
);

create table ficheposte_etat
(
    ficheposte_id          integer         not null constraint ficheposte_etat_fichemetier_id_fk references ficheposte on delete cascade,
    etat_id                 integer         not null constraint ficheposte_etat_etat_id_fk references unicaen_etat_instance on delete cascade,
    constraint ficheposte_etat_pk primary key (ficheposte_id, etat_id)
);

---------------------------------------------------------------------------------------------------
-- FICHE METIER -----------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------

-- insertion des etats

insert into unicaen_etat_categorie (code, libelle, icone, couleur, ordre)
values ('FICHE_METIER', 'États associés aux fiches métiers', 'fas fa-book-open', '#fcaf3e', 100);
INSERT INTO unicaen_etat_type(code, libelle, categorie_id, icone, couleur)
WITH d(code, libelle, icone, couleur) AS (
    SELECT 'FICHE_METIER_REDACTION', 'Fiche métier en rédaction', 'fas fa-edit', '#729fcf',10 UNION
    SELECT 'FICHE_METIER_OK', 'Fiche métier compléte', 'far fa-check-square', 'darkgreen',20 UNION
    SELECT 'FICHE_METIER_MASQUEE', 'Fiche métier masquée', 'fas fa-mask', 'darkred',30
)
SELECT d.code, d.libelle, cp.id, d.icone, d.couleur
FROM d
         JOIN unicaen_etat_categorie cp ON cp.CODE = 'FICHE_METIER';

-- Insertion des instances et lien avec les fiches métiers

insert into unicaen_etat_instance (type_id, complement)
select uet.id as type_id, concat('FICHEMETIER ',fm.id) as fichemetier_id
from fichemetier fm
         join unicaen_etat_etat uee on fm.etat_id = uee.id
         join unicaen_etat_type uet on uet.code = uee.code;

insert into fichemetier_etat(fichemetier_id, etat_id)
    select cast (split_part(complement, ' ',2) as INTEGER) as fichemetier_id, id as etat_id
    from unicaen_etat_instance
    where complement ilike 'FICHEMETIER%';

-- !!! SI CELA A FONCTIONNEE !!! --
alter table fichemetier drop column etat_id;

-- ------------------------------------------------------------------------------------------------
-- FICHE DE POSTE ---------------------------------------------------------------------------------
-- ------------------------------------------------------------------------------------------------

-- insert etats

insert into unicaen_etat_categorie (code, libelle, icone, couleur, ordre)
values ('FICHE_POSTE', 'États associés aux fiches de poste', 'fas fa-book-reader', '#8ae234', 200);
INSERT INTO unicaen_etat_type(code, libelle, categorie_id, icone, couleur)
WITH d(code, libelle, icone, couleur) AS (
    SELECT uee.code, uee.libelle, uee.icone, uee.couleur
    FROM unicaen_etat_etat uee
     join unicaen_etat_etat_type ueet on uee.type_id = ueet.id
    where ueet.code='FICHE_POSTE'
)
SELECT d.code, d.libelle, cp.id, d.icone, d.couleur
FROM d
JOIN unicaen_etat_categorie cp ON cp.CODE = 'FICHE_POSTE';


-- Insertion des instances et lien avec les fiches métiers

insert into unicaen_etat_instance (type_id, complement)
select uet.id as type_id, concat('FICHEPOSTE ',fp.id) as fichemetier_id
from ficheposte fp
         join unicaen_etat_etat uee on fp.etat_id = uee.id
         join unicaen_etat_type uet on uet.code = uee.code;

insert into ficheposte_etat(ficheposte_id, etat_id)
    select cast (split_part(complement, ' ',2) as INTEGER) as ficheposte_id, id as etat_id
    from unicaen_etat_instance
    where complement ilike 'FICHEPOSTE%';

-- !!! SI CELA A FONCTIONNEE !!! --
alter table ficheposte drop column etat_id;


---------------------------------------------------------------------------------------------------
-- ENTRETIEN PROFESSIONNEL ------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------

-- insert etats

insert into unicaen_etat_categorie (code, libelle, icone, couleur, ordre)
values ('ENTRETIEN_PROFESSIONNEL', 'États associés aux entretiens professionnels', 'fas fa-briefcase', '#75507b', 200);
INSERT INTO unicaen_etat_type(code, libelle, categorie_id, icone, couleur)
WITH d(code, libelle, icone, couleur) AS (
    SELECT uee.code, uee.libelle, uee.icone, uee.couleur
    FROM unicaen_etat_etat uee
             join unicaen_etat_etat_type ueet on uee.type_id = ueet.id
    where ueet.code='ENTRETIEN_PROFESSIONNEL'
)
SELECT d.code, d.libelle, cp.id, d.icone, d.couleur
FROM d
         JOIN unicaen_etat_categorie cp ON cp.CODE = 'ENTRETIEN_PROFESSIONNEL';


-- Insertion des instances et lien avec les fiches métiers

insert into unicaen_etat_instance (type_id, complement)
select uet.id as type_id, concat('ENTRETIENPROFESSIONNEL ',ep.id) as fichemetier_id
from entretienprofessionnel ep
         join unicaen_etat_etat uee on ep.etat_id = uee.id
         join unicaen_etat_type uet on uet.code = uee.code;
insert into entretienprofessionnel_etat(entretien_id, etat_id)
select cast (split_part(complement, ' ',2) as INTEGER) as entretien_id, id as etat_id
from unicaen_etat_instance
where complement ilike 'ENTRETIENPROFESSIONNEL%';

-- !!! SI CELA A FONCTIONNEE !!! --
alter table entretienprofessionnel drop column etat_id;