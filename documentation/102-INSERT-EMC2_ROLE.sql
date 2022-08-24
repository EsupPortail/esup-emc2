-- role ----------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Administrateur·trice technique', 'Administrateur·trice technique', false, null, null, false, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Administrateur·trice fonctionnel', 'Administrateur·trice fonctionnel', false, null, null, false, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Gestionnaire de structure', 'Gestionnaire de structure', false, null, null, true, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Agent', 'Agent', true, null, null, true, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Observateur', 'Observateur', false, null, null, false, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Supérieur·e hiérarchique direct·e', 'Supérieur·e hiérarchique direct·e', false, null, null, true, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Direction des ressources humaines ', 'Direction des ressources humaines', false, null, null, false, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Responsable de structure', 'Responsable de structure', false, null, null, true, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Délégué·e pour entretien professionnel', 'Délégué·e pour entretien professionnel', false, null, null, true, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Autorité hiérarchique', 'Autorité hiérarchique', false, null, null, true, null);

INSERT INTO SOURCE (id, code, libelle, importable) VALUES (1, 'OCTOPUS', 'Référentiel OCTOPUS', true);
