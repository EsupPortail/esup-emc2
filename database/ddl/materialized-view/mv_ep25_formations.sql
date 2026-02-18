WITH parts AS (
         SELECT lower(btrim(split_part(t.part, ';'::text, 1))) AS val
           FROM unicaen_autoform_formulaire_reponse r
             JOIN entretienprofessionnel e ON e.formation_instance = r.instance
             JOIN entretienprofessionnel_campagne ec ON e.campagne_id = ec.id,
            LATERAL unnest(string_to_array(r.reponse, '|'::text)) t(part)
          WHERE ec.annee::text = '2024/2025'::text AND r.champ = 1011
        )
 SELECT parts.val,
    count(*) AS nb
   FROM parts
  WHERE parts.val <> ''::text
  GROUP BY parts.val
  ORDER BY (count(*)) DESC