SELECT a.c_individu,
    COALESCE(a.nom_usage, a.nom_famille) AS "coalesce",
    a.prenom,
    array_agg(DISTINCT s.libelle_court) AS structure,
    array_agg(DISTINCT cc.lib_court) AS corps,
    array_agg(DISTINCT cg.lib_court) AS grade,
    array_agg(DISTINCT cb.lib_court) AS correspondance,
    concat(
        CASE max(acs.t_administratif::text)
            WHEN 'O'::text THEN 'Administratif '::text
            ELSE NULL::text
        END,
        CASE max(acs.t_enseignant::text)
            WHEN 'O'::text THEN 'Enseignant '::text
            ELSE NULL::text
        END,
        CASE max(acs.t_chercheur::text)
            WHEN 'O'::text THEN 'Chercheur '::text
            ELSE NULL::text
        END) AS status_emploi,
    concat(
        CASE max(acs.t_titulaire::text)
            WHEN 'O'::text THEN 'Titulaire '::text
            ELSE NULL::text
        END,
        CASE max(acs.t_cdi::text)
            WHEN 'O'::text THEN 'CDI '::text
            ELSE NULL::text
        END,
        CASE max(acs.t_cdd::text)
            WHEN 'O'::text THEN 'CDD '::text
            ELSE NULL::text
        END) AS status_contrat
   FROM agent a
     JOIN agent_carriere_grade acg ON a.c_individu::text = acg.agent_id::text
     LEFT JOIN carriere_corps cc ON acg.corps_id = cc.id
     LEFT JOIN carriere_grade cg ON acg.grade_id = cg.id
     LEFT JOIN carriere_correspondance cb ON acg.correspondance_id = cb.id
     JOIN agent_carriere_affectation aca ON a.c_individu::text = aca.agent_id::text
     LEFT JOIN structure s ON aca.structure_id = s.id
     JOIN agent_carriere_statut acs ON a.c_individu::text = acs.agent_id::text
  WHERE true AND acg.deleted_on IS NULL AND acg.d_debut < now() AND (acg.d_fin IS NULL OR acg.d_fin > now()) AND aca.deleted_on IS NULL AND acg.d_debut < now() AND (acg.d_fin IS NULL OR acg.d_fin > now()) AND aca.deleted_on IS NULL AND aca.date_debut < now() AND (aca.date_fin IS NULL OR aca.date_fin > now()) AND acs.deleted_on IS NULL AND acs.d_debut < now() AND (acs.d_fin IS NULL OR acs.d_fin > now())
  GROUP BY a.c_individu