SELECT max((a.prenom::text || ' '::text) || COALESCE(a.nom_usage, a.nom_famille)::text) AS responsable,
    max(c.annee::text) AS campagne,
    count(*) AS nb
   FROM entretienprofessionnel e
     JOIN entretienprofessionnel_campagne c ON e.campagne_id = c.id
     JOIN agent a ON a.c_individu::text = e.responsable_id::text
  GROUP BY e.campagne_id, e.responsable_id