SELECT ec.annee AS campagne,
    count(*) AS nb_recours
   FROM entretienprofessionnel_recours r
     JOIN entretienprofessionnel e ON r.entretien_id = e.id
     JOIN entretienprofessionnel_campagne ec ON e.campagne_id = ec.id
  WHERE e.histo_destruction IS NULL AND ec.histo_destruction IS NULL AND r.histo_destruction IS NULL
  GROUP BY ec.annee