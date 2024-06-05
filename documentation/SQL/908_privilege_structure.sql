-- select
--     concat(
--             'insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES (',
--             concat('(select id from unicaen_utilisateur_role urr where urr.role_id = ''', uur.role_id, ''')'),
--             ',',
--             concat('(select id from unicaen_privilege_privilege upp where upp.code = ''', upp.code, ''' and upp.categorie_id = (select id from unicaen_privilege_categorie where code =''', upc.code , '''))'),
--             ');'
--     )
-- from unicaen_privilege_privilege_role_linker l
--          join unicaen_privilege_privilege upp on l.privilege_id = upp.id
--          join unicaen_privilege_categorie upc on upp.categorie_id = upc.id
--          join unicaen_utilisateur_role uur on l.role_id = uur.id
-- where upc.namespace = 'Structure\Provider\Privilege';

-- MODULE STRUCTURE
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_gestionnaire' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Responsable de structure'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_gestionnaire' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice fonctionnel·le'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_gestionnaire' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Directeur·trice des ressources humaines'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_gestionnaire' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice fonctionnel·le'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_index' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_index' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Directeur·trice des ressources humaines'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_index' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Observateur·trice'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_index' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Responsable de formation'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_index' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_description' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Responsable de structure'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_description' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice fonctionnel·le'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_description' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Directeur·trice des ressources humaines'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_description' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_agent_force' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Responsable de structure'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_agent_force' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice fonctionnel·le'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_agent_force' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Directeur·trice des ressources humaines'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_agent_force' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Responsable de structure'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice fonctionnel·le'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Directeur·trice des ressources humaines'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Observateur·trice'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Agent'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Autorité hiérarchique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Supérieur·e hiérarchique direct·e'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Observateur·trice de la structure'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Responsable de formation'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_complement_agent' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Responsable de structure'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_complement_agent' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice fonctionnel·le'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_complement_agent' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Directeur·trice des ressources humaines'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_complement_agent' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice fonctionnel·le'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_agent_masque' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_agent_masque' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Directeur·trice des ressources humaines'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_agent_masque' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Observateur·trice'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_agent_masque' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Observateur·trice de la structure'),(select id from unicaen_privilege_privilege upp where upp.code = 'structure_agent_masque' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structure')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice fonctionnel·le'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_index' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_index' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Directeur·trice des ressources humaines'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_index' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Gestionnaire des entretiens professionnels'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_index' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Observateur·trice'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_index' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice fonctionnel·le'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Directeur·trice des ressources humaines'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Gestionnaire de formation'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Autorité hiérarchique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Gestionnaire des entretiens professionnels'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Observateur·trice'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Responsable de structure'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Supérieur·e hiérarchique direct·e'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_afficher' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice fonctionnel·le'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_ajouter' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_ajouter' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Directeur·trice des ressources humaines'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_ajouter' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Gestionnaire des entretiens professionnels'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_ajouter' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice fonctionnel·le'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_modifier' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_modifier' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Directeur·trice des ressources humaines'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_modifier' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Gestionnaire des entretiens professionnels'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_modifier' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice fonctionnel·le'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_historiser' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_historiser' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Directeur·trice des ressources humaines'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_historiser' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Gestionnaire des entretiens professionnels'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_historiser' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Administrateur·trice technique'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_supprimer' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
insert into unicaen_privilege_privilege_role_linker (role_id, privilege_id) VALUES ((select id from unicaen_utilisateur_role urr where urr.role_id = 'Observateur·trice de la structure'),(select id from unicaen_privilege_privilege upp where upp.code = 'structureobservateur_indexobservateur' and upp.categorie_id = (select id from unicaen_privilege_categorie where code ='structureobservateur')));
