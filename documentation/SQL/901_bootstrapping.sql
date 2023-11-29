-- PRIVILEGE À L'ADMINISTRATEUR·TRICE TECHNIQUE
TRUNCATE TABLE unicaen_privilege_privilege_role_linker;
INSERT INTO unicaen_privilege_privilege_role_linker (privilege_id, role_id)
WITH d(privilege_id) AS (
    SELECT id FROM unicaen_privilege_privilege
)
SELECT d.privilege_id, cp.id
FROM d
JOIN unicaen_utilisateur_role cp ON cp.role_id = 'Administrateur·trice technique'
;

-- VALEUR DE PARAMETRE POUR AIDER LA VERIFICATION DE L'INSTALLATION
update unicaen_parametre_parametre set valeur='/var/www/html' where code = 'INSTALL_PATH';

