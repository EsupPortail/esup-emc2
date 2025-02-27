SELECT (string_to_array(r.reponse, ';'::text))[1] AS string_to_array,
    count(*) AS nbdemande
   FROM unicaen_autoform_formulaire_reponse r
     JOIN entretienprofessionnel e ON e.formation_instance = r.instance
     JOIN entretienprofessionnel_campagne ec ON e.campagne_id = ec.id
  WHERE ec.annee::text = '2022/2023'::text AND (r.champ = ANY (ARRAY[125, 126, 128, 129, 130]))
  GROUP BY ((string_to_array(r.reponse, ';'::text))[1])