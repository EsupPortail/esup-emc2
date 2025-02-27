WITH agent_actif AS (
         SELECT agent.c_individu,
            agent.utilisateur_id,
            agent.prenom,
            agent.nom_usage,
            agent.created_on,
            agent.updated_on,
            agent.deleted_on,
            agent.octo_id,
            agent.preecog_id,
            agent.harp_id,
            agent.login,
            agent.email,
            agent.sexe,
            agent.t_contrat_long,
            agent.date_naissance,
            agent.nom_famille,
            agent.id,
            agent.histo_createur_id,
            agent.histo_modificateur_id,
            agent.histo_destructeur_id,
            agent.source_id,
            agent.id_orig,
            aca.agent_id,
            aca.structure_id,
            aca.date_debut,
            aca.date_fin,
            aca.id_orig,
            aca.t_principale,
            aca.created_on,
            aca.updated_on,
            aca.deleted_on,
            aca.id,
            aca.histo_createur_id,
            aca.histo_modificateur_id,
            aca.histo_destructeur_id,
            aca.source_id,
            aca.t_hierarchique,
            aca.t_fonctionnelle,
            aca.quotite
           FROM agent
             JOIN agent_carriere_affectation aca ON aca.agent_id::text = agent.c_individu::text
          WHERE agent.deleted_on IS NULL AND aca.deleted_on IS NULL AND aca.date_debut <= now() AND (aca.date_fin IS NULL OR aca.date_fin >= now())
        )
 SELECT max(a.c_individu::text) AS octo_id,
    max(concat(a.prenom, ' ', COALESCE(a.nom_usage, a.nom_famille))) AS agent,
    array_agg(DISTINCT concat(s2.libelle_court, ' > ', sf.libelle_court)) AS affectation,
    array_agg(DISTINCT concat(sup.prenom, ' ', COALESCE(sup.nom_usage, sup.nom_famille))) AS superieurs,
    array_agg(DISTINCT concat(aut.prenom, ' ', COALESCE(aut.nom_usage, aut.nom_famille))) AS autorites
   FROM agent_actif a(c_individu, utilisateur_id, prenom, nom_usage, created_on, updated_on, deleted_on, octo_id, preecog_id, harp_id, login, email, sexe, t_contrat_long, date_naissance, nom_famille, id, histo_createur_id, histo_modificateur_id, histo_destructeur_id, source_id, id_orig, agent_id, structure_id, date_debut, date_fin, id_orig_1, t_principale, created_on_1, updated_on_1, deleted_on_1, id_1, histo_createur_id_1, histo_modificateur_id_1, histo_destructeur_id_1, source_id_1, t_hierarchique, t_fonctionnelle, quotite)
     LEFT JOIN agent_carriere_statut s ON a.c_individu::text = s.agent_id::text
     LEFT JOIN agent_carriere_affectation f ON f.agent_id::text = a.c_individu::text
     LEFT JOIN structure sf ON f.structure_id = sf.id
     LEFT JOIN structure s2 ON sf.niv2_id = s2.id
     LEFT JOIN agent_hierarchie_autorite aha ON a.c_individu::text = aha.agent_id::text
     LEFT JOIN agent_actif aut(c_individu, utilisateur_id, prenom, nom_usage, created_on, updated_on, deleted_on, octo_id, preecog_id, harp_id, login, email, sexe, t_contrat_long, date_naissance, nom_famille, id, histo_createur_id, histo_modificateur_id, histo_destructeur_id, source_id, id_orig, agent_id, structure_id, date_debut, date_fin, id_orig_1, t_principale, created_on_1, updated_on_1, deleted_on_1, id_1, histo_createur_id_1, histo_modificateur_id_1, histo_destructeur_id_1, source_id_1, t_hierarchique, t_fonctionnelle, quotite) ON aut.c_individu::text = aha.autorite_id::text
     LEFT JOIN agent_hierarchie_superieur ahs ON a.c_individu::text = ahs.agent_id::text
     LEFT JOIN agent_actif sup(c_individu, utilisateur_id, prenom, nom_usage, created_on, updated_on, deleted_on, octo_id, preecog_id, harp_id, login, email, sexe, t_contrat_long, date_naissance, nom_famille, id, histo_createur_id, histo_modificateur_id, histo_destructeur_id, source_id, id_orig, agent_id, structure_id, date_debut, date_fin, id_orig_1, t_principale, created_on_1, updated_on_1, deleted_on_1, id_1, histo_createur_id_1, histo_modificateur_id_1, histo_destructeur_id_1, source_id_1, t_hierarchique, t_fonctionnelle, quotite) ON sup.c_individu::text = ahs.superieur_id::text
  WHERE true AND s.deleted_on IS NULL AND s.t_administratif::text = 'O'::text AND s.d_debut <= now() AND (s.d_fin IS NULL OR s.d_fin >= now()) AND f.deleted_on IS NULL AND f.t_principale::text = 'O'::text AND f.date_debut <= now() AND (f.date_fin IS NULL OR f.date_fin >= now()) AND ahs.histo_destruction IS NULL AND aha.histo_destruction IS NULL
  GROUP BY a.c_individu
 HAVING count(ahs.*) = 0 OR count(aha.*) = 0