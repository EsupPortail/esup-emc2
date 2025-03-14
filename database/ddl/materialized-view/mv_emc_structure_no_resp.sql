WITH sa AS (
         SELECT aca.structure_id
           FROM agent_carriere_affectation aca
          WHERE aca.date_debut < now() AND (aca.date_fin IS NULL OR aca.date_fin >= now())
          GROUP BY aca.structure_id
        )
 SELECT s.id AS structure_id,
    s.code AS structure_code,
    (n2.libelle_court::text || ' > '::text) || s.libelle_court::text AS structure_name
   FROM structure s
     LEFT JOIN structure n2 ON COALESCE(s.niv2_id_ow, s.niv2_id) = n2.id
     LEFT JOIN structure_responsable sr ON s.id = sr.structure_id
     JOIN sa ON sa.structure_id = s.id
  WHERE (s.d_ouverture IS NULL OR s.d_ouverture < now()) AND (s.d_fermeture IS NULL OR s.d_fermeture >= now()) AND s.deleted_on IS NULL AND sr.* IS NULL
  ORDER BY n2.libelle_court, s.libelle_court