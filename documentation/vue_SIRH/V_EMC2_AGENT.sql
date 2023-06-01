-- AGENT_STATUT ------------------
CREATE OR REPLACE VIEW V_EMC2_AGENT_STATUT (
                                            "ID_ORIG",
                                            "AGENT_ID",
                                            "STRUCTURE_ID",
                                            "DATE_DEBUT",
                                            "DATE_FIN",
                                            "T_TITULAIRE",
                                            "T_CDI",
                                            "T_CDD",
                                            "T_ADMINISTRATIF",
                                            "T_ENSEIGNANT",
                                            "T_CHERCHEUR",
                                            "T_VACATAIRE",
                                            "T_DOCTORANT",
                                            "T_DETACHE_IN",
                                            "T_DETACHE_OUT",
                                            "T_HEBERGE",
                                            "T_DISPO",
                                            "T_EMERITE",
                                            "T_RETRAITE"
    ) AS (
         SELECT
             DISTINCT (IST.ID) AS ID_ORIG,
                      IST.INDIVIDU_ID as AGENT_ID,
                      IST.STRUCTURE_ID as STRUCTURE_ID,
                      TO_CHAR(IST.D_DEBUT, 'YYYY-MM-DD HH:MM:SS') AS DATE_DEBUT,
                      TO_CHAR(IST.D_FIN, 'YYYY-MM-DD HH:MM:SS')   AS DATE_FIN,
                      IST.T_TITULAIRE,
                      IST.T_CDI,
                      IST.T_CDD,
                      IST.T_ADMINISTRATIF,
                      IST.T_ENSEIGNANT,
                      IST.T_CHERCHEUR,
                      IST.T_VACATAIRE,
                      IST.T_DOCTORANT,
                      IST.T_DETACHE_IN,
                      IST.T_DETACHE_OUT,
                      IST.T_HEBERGE,
                      IST.T_DISPO,
                      IST.T_EMERITE,
                      IST.T_RETRAITE
         FROM V_EMC2_AGENT VPA
                  JOIN INDIVIDU_STATUT IST ON VPA.C_INDIVIDU = IST.INDIVIDU_ID
                  JOIN STRUCTURE S ON IST.STRUCTURE_ID = S.ID
         WHERE 1=1
           AND IST.T_APPRENANT = 'N'
           AND IST.T_HEBERGE = 'N'
           AND IST.T_INVITE = 'N'
             );