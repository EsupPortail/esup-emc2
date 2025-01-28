SELECT a.c_individu AS agent_id,
    concat(a.prenom, ' ', COALESCE(a.nom_usage, a.nom_famille)) AS agent8denomination,
    s.libelle_court AS structure_libelle,
    s.id AS perimetre_structure_id
   FROM ((agent_carriere_affectation aca
     JOIN agent a ON (((aca.agent_id)::text = (a.c_individu)::text)))
     JOIN structure s ON ((aca.structure_id = s.id)))
  WHERE ((aca.deleted_on IS NULL) AND (aca.histo_destructeur_id IS NULL) AND ((aca.date_debut IS NULL) OR (aca.date_debut < CURRENT_DATE)) AND ((aca.date_fin IS NULL) OR (aca.date_fin > CURRENT_DATE)))