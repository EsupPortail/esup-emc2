-- STRUCTURE DE LA BASE ---------------------------------------------------------------------------------

create table public.element_competence_referentiel
(
    id                    serial
        constraint element_competence_referentiel_pk
        primary key,
    libelle_court         varchar(64)             not null,
    libelle_long          varchar(1024)           not null,
    couleur               varchar(64),
    histo_creation        timestamp default now() not null,
    histo_createur_id     integer   default 0     not null
        constraint element_competence_referentiel_unicaen_utilisateur_user_id_fk
        references public.unicaen_utilisateur_user,
    histo_modification    timestamp,
    histo_modificateur_id integer
        constraint element_competence_referentiel_unicaen_utilisateur_user_id_fk2
        references public.unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint element_competence_referentiel_unicaen_utilisateur_user_id_fk3
        references public.unicaen_utilisateur_user
);
comment on column public.element_competence_referentiel.libelle_court is 'Est utilisé pour l''affichage du badge';
comment on column public.element_competence_referentiel.couleur is 'Est utilisée pour l''afficahge du badge';


alter table element_competence
    add referentiel_id integer;
alter table element_competence
    add constraint element_competence_element_competence_referentiel_id_fk
        foreign key (referentiel_id) references element_competence_referentiel
            on delete set null;

-- PRIVILEGES -------------------------------------------------------------------------------------------

INSERT INTO unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('competencereferentiel', 'Gestions des référentiels de compétence', 'Element\Provider\Privilege', 70800);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'competencereferentiel_index', 'Accéder à l''index des référentiels de compétences', 10 UNION
    SELECT 'competencereferentiel_afficher', 'Afficher', 20 UNION
    SELECT 'competencereferentiel_ajouter', 'Ajouter', 30 UNION
    SELECT 'competencereferentiel_modifier', 'Modifier', 40 UNION
    SELECT 'competencereferentiel_historiser', 'Historiser/Restaurer', 50 UNION
    SELECT 'competencereferentiel_effacer', 'Supprimer', 60
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'competencereferentiel';

-- MODIF DE BASE -----------------------------------------------------------------------------------------

INSERT INTO element_competence_referentiel (id,libelle_court, libelle_long, couleur) VALUES
(1,'EMC2', 'Référentiel interne à EMC2', '#3465a4'),
(2,'REFERENS3', 'Référentiel des BIATSS Referens3', '#ce5c00');

update element_competence set referentiel_id=1 where source='EMC2';
update element_competence set referentiel_id=2 where source='REFERENS3';
