SELECT max(s.libelle_long::text) AS structure,
    count(DISTINCT aca.agent_id) AS nb_agent,
    max(s.id) AS perimetre_structure_id
   FROM structure s
     JOIN agent_carriere_affectation aca ON s.id = aca.structure_id
  WHERE aca.deleted_on IS NULL AND (aca.date_debut IS NULL OR aca.date_debut <= CURRENT_DATE) AND (aca.date_fin IS NULL OR aca.date_fin >= CURRENT_DATE)
  GROUP BY s.*