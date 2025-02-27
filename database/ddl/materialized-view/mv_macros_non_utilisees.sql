SELECT m.id,
    m.code,
    m.description,
    m.variable_name,
    m.methode_name
   FROM unicaen_renderer_macro m
  WHERE NOT (m.code::text IN ( SELECT m_1.code
           FROM unicaen_renderer_macro m_1
             JOIN unicaen_renderer_template t ON true
          WHERE t.document_sujet ~~ (('%'::text || m_1.code::text) || '%'::text) OR t.document_corps ~~ (('%'::text || m_1.code::text) || '%'::text)
          GROUP BY m_1.code))