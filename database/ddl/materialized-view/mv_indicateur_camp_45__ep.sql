SELECT e.id,
    e.agent,
    e.responsable_id,
    e.formulaire_instance,
    e.date_entretien,
    e.campagne_id,
    e.formation_instance,
    e.lieu,
    e.token,
    e.acceptation,
    e.histo_creation,
    e.histo_createur_id,
    e.histo_modification,
    e.histo_modificateur_id,
    e.histo_destruction,
    e.histo_destructeur_id,
    e.duree_estimee,
    e.convocation
   FROM entretienprofessionnel e
     JOIN entretienprofessionnel_campagne ec ON e.campagne_id = ec.id
  WHERE ec.id = 45