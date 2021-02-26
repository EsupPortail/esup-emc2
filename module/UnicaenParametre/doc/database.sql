-- CREATION DES TABLES --------------------------------------------------------------------------

-- table catégorie
create table unicaen_parametre_categorie
(
    id serial not null,
    code varchar(1024) not null,
    libelle varchar(1024) not null,
    description text,
    ordre integer default 9999
);

create unique index unicaen_parametre_categorie_code_uindex
    on unicaen_parametre_categorie (code);

create unique index unicaen_parametre_categorie_id_uindex
    on unicaen_parametre_categorie (id);

alter table unicaen_parametre_categorie
    add constraint unicaen_parametre_categorie_pk
        primary key (id);

-- table parametre
create table unicaen_parametre_parametre
(
    id serial not null
        constraint unicaen_parametre_parametre_pk
            primary key,
    categorie_id integer not null
        constraint unicaen_parametre_parametre_unicaen_parametre_categorie_id_fk
            references unicaen_parametre_categorie,
    code varchar(1024) not null,
    libelle varchar(1024) not null,
    description text,
    valeurs_possibles text,
    valeur text,
    ordre integer default 9999
);

create unique index unicaen_parametre_parametre_id_uindex
    on unicaen_parametre_parametre (id);

create unique index unicaen_parametre_parametre_code_categorie_id_uindex
    on unicaen_parametre_parametre (code, categorie_id);

-- PRIVILEGES ----------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (id, code, libelle, ordre, namespace) VALUES (nextval(unicaen_privilege_categorie_id_seq), 'parametrecategorie', 'UnicaenParametre - Gestion des catégories de paramètres', 70000, 'UnicaenParametre\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege (categorie_id, code, libelle, ordre) VALUES (lastval(unicaen_privilege_categorie_id_seq), 'parametrecategorie_index', 'Affichage de l''index des paramètres', 10);
INSERT INTO unicaen_privilege_privilege (categorie_id, code, libelle, ordre) VALUES (lastval(unicaen_privilege_categorie_id_seq), 'parametrecategorie_modifier', 'Modifier une catégorie de paramètre', 40);
INSERT INTO unicaen_privilege_privilege (categorie_id, code, libelle, ordre) VALUES (lastval(unicaen_privilege_categorie_id_seq), 'parametrecategorie_ajouter', 'Ajouter une catégorie de paramètre', 30);
INSERT INTO unicaen_privilege_privilege (categorie_id, code, libelle, ordre) VALUES (lastval(unicaen_privilege_categorie_id_seq), 'parametrecategorie_supprimer', 'Supprimer une catégorie de paramètre', 60);
INSERT INTO unicaen_privilege_privilege (categorie_id, code, libelle, ordre) VALUES (lastval(unicaen_privilege_categorie_id_seq), 'parametrecategorie_afficher', 'Affichage des détails d''une catégorie', 20);

INSERT INTO unicaen_privilege_categorie (id, code, libelle, ordre, namespace) VALUES (nextval(unicaen_privilege_categorie_id_seq), 'parametre', 'UnicaenParametre - Gestion des paramètres', 70001, 'UnicaenParametre\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege (categorie_id, code, libelle, ordre) VALUES (lastval(unicaen_privilege_categorie_id_seq), 'parametre_afficher', 'Afficher un paramètre', 10);
INSERT INTO unicaen_privilege_privilege (categorie_id, code, libelle, ordre) VALUES (lastval(unicaen_privilege_categorie_id_seq), 'parametre_ajouter', 'Ajouter un paramètre', 20);
INSERT INTO unicaen_privilege_privilege (categorie_id, code, libelle, ordre) VALUES (lastval(unicaen_privilege_categorie_id_seq), 'parametre_modifier', 'Modifier un paramètre', 30);
INSERT INTO unicaen_privilege_privilege (categorie_id, code, libelle, ordre) VALUES (lastval(unicaen_privilege_categorie_id_seq), 'parametre_supprimer', 'Supprimer un paramètre', 50);
INSERT INTO unicaen_privilege_privilege (categorie_id, code, libelle, ordre) VALUES (lastval(unicaen_privilege_categorie_id_seq), 'parametre_valeur', 'Modifier la valeur d''un parametre', 100);

--TODO penser à accorder les privilèges selon le besoin