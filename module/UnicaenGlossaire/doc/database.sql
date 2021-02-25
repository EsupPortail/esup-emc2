-- TABLE ---------------------------------------------------------------------------------------------------------------

create table unicaen_glossaire_definition
(
    id serial not null
        constraint unicaen_glossaire_definition_pk
            primary key,
    terme varchar(1024) not null,
    definition text not null,
    histo_creation timestamp not null,
    histo_createur_id integer not null
        constraint unicaen_glossaire_definition_unicaen_utilisateur_user_id_fk
            references unicaen_utilisateur_user,
    histo_modification timestamp,
    histo_modificateur_id integer
        constraint unicaen_glossaire_definition_unicaen_utilisateur_user_id_fk_2
            references unicaen_utilisateur_user,
    histo_destruction timestamp,
    histo_destructeur_id integer
        constraint unicaen_glossaire_definition_unicaen_utilisateur_user_id_fk_3
            references unicaen_utilisateur_user,
    alternatives text
);

alter table unicaen_glossaire_definition owner to ad_preecog_prod;

create unique index unicaen_glossaire_definition_id_uindex
    on unicaen_glossaire_definition (id);

create unique index unicaen_glossaire_definition_terme_uindex
    on unicaen_glossaire_definition (terme);

-- PRIVILEGES ----------------------------------------------------------------------------------------------------------

INSERT INTO public.unicaen_privilege_categorie (id, code, libelle, ordre, namespace) VALUES (nextval(unicaen_privilege_categorie_id_seq), 'definition', 'UnicaenGlossaire - Gestion des définitions', 60000, 'UnicaenGlossaire\Provider\Privilege');
INSERT INTO public.unicaen_privilege_privilege (categorie_id, code, libelle, ordre)  VALUES (lastval(unicaen_privilege_categorie_id_seq), 'definition_index', 'Afficher l''index des définitions', 10);
INSERT INTO public.unicaen_privilege_privilege (categorie_id, code, libelle, ordre)  VALUES (lastval(unicaen_privilege_categorie_id_seq), 'definition_historiser', 'Historiser/Restaurer une définition', 50);
INSERT INTO public.unicaen_privilege_privilege (categorie_id, code, libelle, ordre)  VALUES (lastval(unicaen_privilege_categorie_id_seq), 'definition_modifier', 'Modifier une définition', 40);
INSERT INTO public.unicaen_privilege_privilege (categorie_id, code, libelle, ordre)  VALUES (lastval(unicaen_privilege_categorie_id_seq), 'definition_supprimer', 'Supprimer une définition', 60);
INSERT INTO public.unicaen_privilege_privilege (categorie_id, code, libelle, ordre)  VALUES (lastval(unicaen_privilege_categorie_id_seq), 'definition_afficher', 'Afficher une définition', 20);
INSERT INTO public.unicaen_privilege_privilege (categorie_id, code, libelle, ordre)  VALUES (lastval(unicaen_privilege_categorie_id_seq), 'definition_ajouter', 'Ajouter une définition', 30);
--TODO penser à accorder les privilèges selon le besoin