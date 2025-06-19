SELECT e.id AS ep_id,
    e.date_entretien,
    (aa.prenom::text || ' '::text) || aa.nom_usage::text AS agent,
    (ar.prenom::text || ' '::text) || ar.nom_usage::text AS resposable
   FROM entretienprofessionnel e
     JOIN agent aa ON e.agent::text = aa.c_individu::text
     JOIN agent ar ON e.responsable_id::text = ar.c_individu::text
  WHERE e.date_entretien > now() AND e.histo_destruction IS NULL
  ORDER BY e.date_entretien