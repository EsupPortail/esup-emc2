INSERT INTO unicaen_utilisateur_user (id, username, display_name, email, password, state) VALUES (0, 'EMC2', 'EMC2', null, 'null', false);


INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Agent', 'Agent', true, true);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Administrateur·trice technique', 'Administrateur·trice technique', false, false);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Administrateur·trice fonctionnel·le', 'Administrateur·trice fonctionnel·le', false, false);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Observateur·trice', 'Observateur·trice', false, false);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Supérieur·e hiérarchique direct·e', 'Supérieur·e hiérarchique direct·e', false, true);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Directeur·trice des ressources humaines', 'Directeur·trice des ressources humaines', false, false);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Responsable de structure', 'Responsable de structure', false, true);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Autorité hiérarchique', 'Autorité hiérarchique', false, true);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Gestionnaire de formation', 'Gestionnaire de formation', false, false);
INSERT INTO unicaen_utilisateur_role (role_id, libelle, is_default, is_auto) VALUES ('Formateur·trice', 'Formateur·trice', false, false);


INSERT INTO unicaen_renderer_macro (code, description, variable_name, methode_name) VALUES ('URL#App', null, 'UrlService', 'getUrlApp');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps, document_css, namespace)
VALUES ('EMC2_ACCUEIL', '<p>Texte de la page d''accueil</p>', 'texte', 'Instance de démonstration de EMC2', 'Instance de démonstration de EMC2', null, 'Application\Provider\Template');


INSERT INTO unicaen_privilege_privilege_role_linker (role_id, privilege_id)
SELECT 2 as role_id, p.id as privilege_id
FROM unicaen_privilege_privilege p
LEFT JOIN unicaen_privilege_categorie c on p.categorie_id = c.id and c.namespace like 'Unicaen%';