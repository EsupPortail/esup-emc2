SELECT uet.libelle,
    count(ep.id) AS count
   FROM entretienprofessionnel ep
     JOIN entretienprofessionnel_campagne c ON ep.campagne_id = c.id
     JOIN entretienprofessionnel_etat epe ON ep.id = epe.entretien_id
     JOIN unicaen_etat_instance uei ON epe.etat_id = uei.id
     JOIN unicaen_etat_type uet ON uei.type_id = uet.id
  WHERE c.annee::text = '2024/2025'::text AND uei.histo_destruction IS NULL
  GROUP BY uet.libelle