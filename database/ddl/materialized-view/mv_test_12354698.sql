SELECT uet.libelle AS "État",
    count(ep.id) AS nombre
   FROM entretienprofessionnel ep
     JOIN entretienprofessionnel_etat epe ON ep.id = epe.entretien_id
     JOIN unicaen_etat_instance uei ON epe.etat_id = uei.id
     JOIN unicaen_etat_type uet ON uei.type_id = uet.id
  WHERE uei.histo_destruction IS NULL
  GROUP BY uet.libelle