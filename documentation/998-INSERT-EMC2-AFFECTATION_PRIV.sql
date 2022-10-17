TRUNCATE TABLE unicaen_privilege_privilege_role_linker;
INSERT INTO unicaen_privilege_privilege_role_linker (privilege_id, role_id)
WITH d(privilege_id) AS (
    SELECT id FROM unicaen_privilege_privilege
)
SELECT d.privilege_id, cp.id
FROM d
JOIN unicaen_utilisateur_role cp ON cp.role_id = 'Administrateur·trice technique';