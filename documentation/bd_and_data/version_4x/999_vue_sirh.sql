-- --------------------------------------------
-- Vue sur les données sources ----------------
-- --------------------------------------------

-- DONNEES RHS --------------------------------

create or replace view v_emc2_correspondance_type as (
                                                     select
                                                         cast(id as varchar(50)) as id,
                                                         code as code,
                                                         libelle_court as libelle_court,
                                                         libelle_long as libelle_long,
                                                         description as description,
                                                         d_ouverture as date_debut,
                                                         d_fermeture as date_fin
                                                     from correspondance_type
                                                         );

create or replace view v_emc2_correspondance as (
                                                select
                                                    cast(id as varchar(50)) as id,
                                                    code as code,
                                                    type_id as type_id,
                                                    lib_court as lib_court,
                                                    lib_long as lib_long,
                                                    d_ouverture as date_debut,
                                                    d_fermeture as date_fin
                                                from correspondance
                                                    );

create or replace view v_emc2_corps as (
                                       select
                                           cast(id as varchar(50)) as id,
                                           code as code,
                                           lib_court as lib_court,
                                           lib_long as lib_long,
                                           categorie_code as categorie,
                                           d_ouverture as date_debut,
                                           d_fermeture as date_fin
                                       from corps
                                           );

create or replace view v_emc2_grade as (
                                       select
                                           cast(id as varchar(50)) as id,
                                           code as code,
                                           lib_court as lib_court,
                                           lib_long as lib_long,
                                           d_ouverture as date_debut,
                                           d_fermeture as date_fin
                                       from grade
                                           );

create or replace view v_emc2_emploitype as (
                                            select
                                                cast(id as varchar(50)) as id,
                                                c_emploitype as code,
                                                lib_court as lib_court,
                                                lib_long as lib_long,
                                                d_ouverture as date_debut,
                                                d_fermeture as date_fin
                                            from emploitype
                                                );

-- STRUCTURE ----------------------------------

create or replace view v_emc2_structure_type as (
                                                select
                                                    id as id,
                                                    code as code,
                                                    libelle as libelle,
                                                    description as description
                                                from structure_type
                                                where code <> 'FICT'
                                                    );

create or replace view v_emc2_structure as (
                                           select
                                               id as ID,
                                               code as CODE,
                                               libelle_court as LIBELLE_COURT,
                                               coalesce(libelle_communication, libelle_long) as LIBELLE_LONG,
                                               sigle as SIGLE,
                                               type_id as TYPE_ID,
                                               adresse_fonctionnelle as EMAIL_GENERIQUE,
                                               d_ouverture as DATE_OUVERTURE,
                                               d_fermeture as DATE_FERMETURE,
                                               parent_id as PARENT_ID,
                                               niv2_id as NIV2_ID
                                           from structure
                                               );

create or replace view v_emc2_agent as
(
select
    cast(c_individu_chaine as varchar(50)) as c_individu,
    cast(c_individu_chaine as varchar(50)) as id,
    prenom as prenom,
    nom_usage as nom_usage,
    nom_famille as nom_famille,
    sexe as sexe,
    d_naissance as date_naissance,
    ic.login as login,
    email as email,
    'O' as t_contrat_long
from individu i
         join individu_affectation ia on i.c_individu_chaine = ia.individu_id
         join individu_affectation_type iat on ia.type_id = iat.id
         join individu_compte ic on ic.individu_id=i.c_individu_chaine
where iat.nom = 'EMPLOI'
    );

create or replace view v_emc2_structure_responsable as (
                                                       select
                                                           id as id,
                                                           structure_id as structure_id,
                                                           individu_id as agent_id,
                                                           fonction_id as fonction_id,
                                                           date_debut as date_debut,
                                                           date_fin as date_fin
                                                       from structure_responsable
                                                           );

create or replace view v_emc2_structure_gestionnaire as (
                                                        select
                                                            id as id,
                                                            structure_id as structure_id,
                                                            individu_id as agent_id,
                                                            fonction_id as fonction_id,
                                                            date_debut as date_debut,
                                                            date_fin as date_fin
                                                        from structure_gestionnaire
                                                            );

create or replace view v_emc2_agent_affectation as (
                                                   select
                                                       ia.id as id,
                                                       ia.individu_id as agent_id,
                                                       ia.structure_id as structure_id,
                                                       t_principale as t_principale,
                                                       t_hierarchique as t_hierarchique,
                                                       t_fonctionnelle as t_fonctionnelle,
                                                       quotite as quotite,
                                                       date_debut as date_debut,
                                                       date_fin as date_fin
                                                   from individu_affectation ia
                                                            join individu i on ia.individu_id = i.c_individu_chaine
                                                            join individu_affectation_type iat on ia.type_id = iat.id
                                                   where iat.id in (1,2)
                                                       );

create or replace view v_emc2_agent_grade as
(
select
    ia.id as id,
    ia.agent_id as agent_id,
    ia.structure_id as structure_id,
    ia.corps_id as corps_id,
    ia.correspondance_id as correspondance_id,
    ia.grade_id as grade_id,
    ia.emploitype_id as emploitype_id,
    ia.d_debut as date_debut,
    ia.d_fin as date_fin
from individu_grade ia
         join individu i on ia.agent_id = i.c_individu_chaine
    );

create or replace view v_emc2_agent_echelon as
(
select
    ia.id as id,
    ia.individu_id as agent_id,
    echelon as echelon,
    ia.d_debut as date_debut,
    ia.d_fin as date_fin
from individu_echelon ia
         join individu i on ia.individu_id = i.c_individu_chaine
    );

create or replace view v_emc2_agent_quotite as
(
select
    ia.id as id,
    ia.individu_id as agent_id,
    ia.quotite as quotite,
    ia.debut as date_debut,
    ia.fin as date_fin
from individu_quotite ia
         join individu i on ia.individu_id = i.c_individu_chaine
    );

create or replace view v_emc2_agent_poste as
(
select
    ia.id as id,
    ia.individu_id as agent_id,
    ia.code_poste as code_poste,
    ia.libelle_poste as libelle_poste
from individu_poste ia
         join individu i on ia.individu_id = i.c_individu_chaine
    );

create or replace view v_emc2_agent_statut as
(
select
    ia.id as id,
    ia.individu_id as agent_id,
    ia.structure_id as structure_id,
    ia.d_debut as date_debut,
    ia.d_fin as date_fin,
    t_titulaire,
    t_cdi,
    t_cdd,
    t_administratif,
    t_enseignant,
    t_chercheur,
    t_vacataire,
    t_doctorant,
    t_detache_in,
    t_detache_out,
    t_heberge,
    t_dispo,
    t_emerite,
    t_retraite
from individu_statut ia
         join individu i on ia.individu_id = i.c_individu_chaine
    );