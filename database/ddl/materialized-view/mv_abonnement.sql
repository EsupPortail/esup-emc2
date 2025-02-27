SELECT abo.formation_id AS id,
    max(f.libelle::text) AS libelle,
    count(*) AS nombre,
    array_agg(DISTINCT concat(a.prenom, ' ', a.nom_usage, ' <', a.email, '>')) AS listing
   FROM formation_formation_abonnement abo
     JOIN formation f ON abo.formation_id = f.id
     JOIN agent a ON abo.agent_id::text = a.c_individu::text
  WHERE abo.histo_destruction IS NULL
  GROUP BY abo.formation_id