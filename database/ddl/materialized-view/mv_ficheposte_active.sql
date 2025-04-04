SELECT fp.id AS ficheposte_id,
    max(a.c_individu::text) AS octopus_is,
    max((a.nom_usage::text || ' '::text) || a.prenom::text) AS agent_denomination,
    array_agg(DISTINCT mm.libelle_default) AS metier,
    array_agg(DISTINCT mr.code) AS reference,
    array_agg(DISTINCT md.libelle) AS domaine,
    max(s.libelle_court::text) AS structure,
    array_agg(DISTINCT cc.lib_court) AS corps,
    array_agg(DISTINCT cg.lib_court) AS grade,
    array_agg(DISTINCT cb.lib_court) AS correspondance
   FROM metier_metier mm
     LEFT JOIN metier_reference mr ON mm.id = mr.metier_id
     LEFT JOIN metier_metier_domaine mmd ON mm.id = mmd.metier_id
     LEFT JOIN metier_domaine md ON mmd.domaine_id = md.id
     JOIN fichemetier fm ON mm.id = fm.metier_id
     JOIN ficheposte_fichemetier fpfm ON fm.id = fpfm.fiche_type
     JOIN ficheposte fp ON fp.id = fpfm.fiche_poste
     JOIN ficheposte_etat fpe ON fp.id = fpe.ficheposte_id
     JOIN unicaen_etat_instance uei ON fpe.etat_id = uei.id
     JOIN unicaen_etat_type uet ON uei.type_id = uet.id
     JOIN agent a ON fp.agent::text = a.c_individu::text
     JOIN agent_carriere_affectation aca ON a.c_individu::text = aca.agent_id::text
     JOIN agent_carriere_grade acg ON a.c_individu::text = acg.agent_id::text
     JOIN structure s ON aca.structure_id = s.id
     LEFT JOIN carriere_corps cc ON acg.corps_id = cc.id
     LEFT JOIN carriere_grade cg ON acg.grade_id = cg.id
     LEFT JOIN carriere_correspondance cb ON acg.correspondance_id = cb.id
  WHERE true AND fp.histo_destruction IS NULL AND fp.fin_validite IS NULL AND uei.histo_destruction IS NULL AND uet.code::text = 'FICHE_POSTE_SIGNEE'::text AND aca.deleted_on IS NULL AND aca.date_debut < now() AND (aca.date_fin IS NULL OR aca.date_fin > now()) AND acg.deleted_on IS NULL AND acg.d_debut < now() AND (acg.d_fin IS NULL OR acg.d_fin > now())
  GROUP BY fp.id
UNION
 SELECT fp.id AS ficheposte_id,
    max(a.c_individu::text) AS octopus_is,
    max((a.nom_usage::text || ' '::text) || a.prenom::text) AS agent_denomination,
    array_agg(DISTINCT mm.libelle_default) AS metier,
    array_agg(DISTINCT mr.code) AS reference,
    array_agg(DISTINCT md.libelle) AS domaine,
    max(s.libelle_court::text) AS structure,
    array_agg(DISTINCT cc.lib_court) AS corps,
    array_agg(DISTINCT cg.lib_court) AS grade,
    array_agg(DISTINCT cb.lib_court) AS correspondance
   FROM metier_metier mm
     LEFT JOIN metier_reference mr ON mm.id = mr.metier_id
     LEFT JOIN metier_metier_domaine mmd ON mm.id = mmd.metier_id
     LEFT JOIN metier_domaine md ON mmd.domaine_id = md.id
     JOIN fichemetier fm ON mm.id = fm.metier_id
     JOIN ficheposte_fichemetier fpfm ON fm.id = fpfm.fiche_type
     JOIN ficheposte fp ON fp.id = fpfm.fiche_poste
     JOIN ficheposte_etat fpe ON fp.id = fpe.ficheposte_id
     JOIN unicaen_etat_instance uei ON fpe.etat_id = uei.id
     JOIN unicaen_etat_type uet ON uei.type_id = uet.id
     JOIN agent a ON fp.agent::text = a.c_individu::text
     JOIN agent_carriere_affectation aca ON a.c_individu::text = aca.agent_id::text
     JOIN agent_carriere_grade acg ON a.c_individu::text = acg.agent_id::text
     JOIN structure s ON aca.structure_id = s.id
     LEFT JOIN carriere_corps cc ON acg.corps_id = cc.id
     LEFT JOIN carriere_grade cg ON acg.grade_id = cg.id
     LEFT JOIN carriere_correspondance cb ON acg.correspondance_id = cb.id
  WHERE true AND fp.histo_destruction IS NULL AND fp.fin_validite IS NULL AND uei.histo_destruction IS NULL AND uet.code::text = 'FICHE_POSTE_OK'::text AND aca.deleted_on IS NULL AND aca.date_debut < now() AND (aca.date_fin IS NULL OR aca.date_fin > now()) AND acg.deleted_on IS NULL AND acg.d_debut < now() AND (acg.d_fin IS NULL OR acg.d_fin > now()) AND NOT (a.c_individu::text IN ( SELECT a_1.c_individu
           FROM agent a_1
             JOIN ficheposte fp_1 ON a_1.c_individu::text = fp_1.agent::text
             JOIN ficheposte_etat fpe_1 ON fp_1.id = fpe_1.ficheposte_id
             JOIN unicaen_etat_instance uei_1 ON fpe_1.etat_id = uei_1.id
             JOIN unicaen_etat_type uet_1 ON uei_1.type_id = uet_1.id
          WHERE fp_1.histo_destruction IS NULL AND uet_1.code::text = 'FICHE_POSTE_SIGNEE'::text))
  GROUP BY fp.id