SELECT a.c_individu AS octo_id,
    max(a.prenom::text) AS prenom,
    max(COALESCE(a.nom_usage, a.nom_famille)::text) AS nom,
    array_remove(array_agg(DISTINCT et.code), NULL::character varying) AS emploitype_code,
    array_remove(array_agg(DISTINCT et.libelle_long), NULL::character varying) AS emploitype_libelle,
    array_remove(array_agg(DISTINCT mr.code), NULL::character varying) AS metier_code,
    array_remove(array_agg(DISTINCT m.libelle_default), NULL::character varying) AS metier_libelle
   FROM agent a
     JOIN agent_carriere_grade acg ON a.c_individu::text = acg.agent_id::text
     JOIN agent_carriere_affectation aca ON a.c_individu::text = aca.agent_id::text
     JOIN structure s ON aca.structure_id = s.id
     JOIN structure s2 ON s.niv2_id = s2.id
     LEFT JOIN carriere_emploitype et ON acg.emploitype_id = et.id
     JOIN ficheposte fp ON fp.agent::text = a.c_individu::text
     JOIN ficheposte_fichemetier fpfm ON fp.id = fpfm.fiche_poste
     JOIN fichemetier fm ON fm.id = fpfm.fiche_type
     JOIN metier_metier m ON fm.metier_id = m.id
     LEFT JOIN metier_reference mr ON m.id = mr.metier_id
  WHERE (s.libelle_court::text = 'DSI'::text OR s2.libelle_court::text = 'DSI'::text) AND acg.d_debut < now() AND (acg.d_fin IS NULL OR acg.d_fin > now()) AND aca.date_debut < now() AND (aca.date_fin IS NULL OR aca.date_fin > now()) AND fp.histo_destruction IS NULL
  GROUP BY a.c_individu