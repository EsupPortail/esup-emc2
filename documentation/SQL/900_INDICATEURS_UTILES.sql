INSERT INTO unicaen_indicateur_indicateur (titre, description, requete, dernier_rafraichissement, view_id, entity, namespace)
VALUES ('Macros non utilisées', e'Liste les macros déclarées et non encore utilisés.
Peut-être utile pour vérifier qu\'une macro est bien non utilisées avant de la supprimer.', e'select m.*
from unicaen_renderer_macro m
where m.code not in (select m.code
                     from unicaen_renderer_macro m
                              join unicaen_renderer_template t on true
                     where t.document_sujet like \'%\' || m.code || \'%\'
                        OR t.document_corps like \'%\' || m.code || \'%\'
                     group by m.code)', '2023-11-29 11:58:48.000000', 'mv_macros_non_utilisees', 'Libre', 'Administration');

TRUNCATE TABLE unicaen_privilege_privilege_role_linker;
INSERT INTO unicaen_privilege_privilege_role_linker (privilege_id, role_id)
WITH d(privilege_id) AS (
    SELECT id FROM unicaen_privilege_privilege
)
SELECT d.privilege_id, cp.id
FROM d
         JOIN unicaen_utilisateur_role cp ON cp.role_id = 'Administrateur·trice technique'
;