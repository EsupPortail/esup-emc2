-- role ----------------------------------------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Gestionnaire de structure', 'Gestionnaire de structure', false, null, null, true, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Supérieur·e hiérarchique direct·e', 'Supérieur·e hiérarchique direct·e', false, null, null, true, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Responsable de structure', 'Responsable de structure', false, null, null, true, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Délégué·e pour entretien professionnel', 'Délégué·e pour entretien professionnel', false, null, null, true, null);
INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, ldap_filter, parent_id, is_auto, accessible_exterieur) VALUES ('Autorité hiérarchique', 'Autorité hiérarchique', false, null, null, true, null);

INSERT INTO SOURCE (id, code, libelle, importable) VALUES (1, 'OCTOPUS', 'Référentiel OCTOPUS', true);




-- ATTRIBUTION A L'ADMIN TECH ---------------------------------------------------------------------------------

TRUNCATE TABLE unicaen_privilege_privilege_role_linker;
INSERT INTO unicaen_privilege_privilege_role_linker (privilege_id, role_id)
WITH d(privilege_id) AS (
    SELECT id FROM unicaen_privilege_privilege
)
SELECT d.privilege_id, cp.id
FROM d
         JOIN unicaen_utilisateur_role cp ON cp.role_id = 'Administrateur·trice technique';