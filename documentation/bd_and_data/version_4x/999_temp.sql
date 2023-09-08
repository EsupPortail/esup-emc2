INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Agent', 'Agent', true, true);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Administrateur·trice technique', 'Administrateur·trice technique', false, false);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Administrateur·trice fonctionnel·le', 'Administrateur·trice fonctionnel·le', false, false);

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace)
VALUES ('EMC2_ACCUEIL', '<p>Texte de la page d''accueil</p>', 'texte', 'Instance de démonstration de EMC2', 'Instance de démonstration de EMC2', null, 'Application\Provider\Template');


INSERT INTO unicaen_privilege_privilege_role_linker (role_id, privilege_id)
SELECT 2 as role_id, p.id as privilege_id
FROM unicaen_privilege_privilege p
LEFT JOIN unicaen_privilege_categorie c on p.categorie_id = c.id and c.namespace like 'Unicaen%';