SELECT aha.agent_id,
    aha.autorite_id,
    to_char(aha.date_debut, 'dd/mm/yyyy'::text) AS date_debut,
    to_char(aha.date_fin, 'dd/mm/yyyy'::text) AS date_fin,
    concat(COALESCE(a1.nom_usage, a1.nom_famille), ' ', a1.prenom) AS agent_denomination,
    concat(COALESCE(a2.nom_usage, a2.nom_famille), ' ', a2.prenom) AS autorite_denomination
   FROM agent_hierarchie_autorite aha
     JOIN agent a1 ON aha.agent_id::text = a1.c_individu::text
     JOIN agent a2 ON aha.autorite_id::text = a2.c_individu::text
  WHERE aha.deleted_on IS NULL AND aha.histo_destruction IS NULL AND a1.deleted_on IS NULL AND a2.deleted_on IS NULL AND (aha.date_debut IS NULL OR aha.date_debut <= now()) AND (aha.date_fin IS NULL OR aha.date_fin >= now())