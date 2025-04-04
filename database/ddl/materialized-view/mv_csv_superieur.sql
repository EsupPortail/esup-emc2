SELECT ahs.agent_id,
    ahs.superieur_id,
    to_char(ahs.date_debut, 'dd/mm/yyyy'::text) AS date_debut,
    to_char(ahs.date_fin, 'dd/mm/yyyy'::text) AS date_fin,
    concat(COALESCE(a1.nom_usage, a1.nom_famille), ' ', a1.prenom) AS agent_denomination,
    concat(COALESCE(a2.nom_usage, a2.nom_famille), ' ', a2.prenom) AS superieur_denomination
   FROM agent_hierarchie_superieur ahs
     JOIN agent a1 ON ahs.agent_id::text = a1.c_individu::text
     JOIN agent a2 ON ahs.superieur_id::text = a2.c_individu::text
  WHERE ahs.deleted_on IS NULL AND ahs.histo_destruction IS NULL AND a1.deleted_on IS NULL AND a2.deleted_on IS NULL AND (ahs.date_debut IS NULL OR ahs.date_debut <= now()) AND (ahs.date_fin IS NULL OR ahs.date_fin >= now())