SELECT concat(COALESCE(max(a.nom_usage::text), max(s.nom::text)), ' ', COALESCE(max(a.prenom::text), max(s.prenom::text))) AS denimination,
    max(f.libelle::text) AS formation,
    array_agg(seance.jour) AS seance
   FROM formation_presence fp
     JOIN formation_inscription fi ON fp.inscription_id = fi.id
     JOIN formation_instance session ON fi.session_id = session.id
     JOIN formation f ON session.formation_id = f.id
     LEFT JOIN agent a ON fi.agent_id::text = a.c_individu::text
     LEFT JOIN formation_stagiaire_externe s ON fi.stagiaire_id = s.id
     LEFT JOIN formation_seance seance ON session.id = seance.instance_id
  WHERE fp.statut::text = 'ABSENCE_NON_JUSTIFIEE'::text AND seance.jour > (CURRENT_DATE - '1 year'::interval)
  GROUP BY fp.inscription_id