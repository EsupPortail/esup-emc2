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

