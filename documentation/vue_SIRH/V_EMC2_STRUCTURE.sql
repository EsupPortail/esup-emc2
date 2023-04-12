CREATE OR REPLACE VIEW V_STRUCTURE AS
(
WITH multi_heritage AS
         (SELECT STRUCTURE.ID                   id,
                 LEVEL                          niveau,
                 STRUCTURE_PARENT.PARENT_ID     parent_id,
                 CONNECT_BY_ROOT STRUCTURE.ID   racine_id, CONNECT_BY_ROOT STRUCTURE.CODE racine_code, SYS_CONNECT_BY_PATH(LIBELLE_COURT, ' > ') chemin,
                 SYS_CONNECT_BY_PATH(ID, ' > ') chemin_id
          FROM STRUCTURE
                   LEFT JOIN STRUCTURE_PARENT ON STRUCTURE.ID = STRUCTURE_PARENT.STRUCTURE_ID
    CONNECT BY NOCYCLE
    PRIOR STRUCTURE.ID = STRUCTURE_PARENT.PARENT_ID
START WITH STRUCTURE_PARENT.PARENT_ID IS NULL
    )
SELECT STRUCTURE.ID,
       STRUCTURE.CODE,
       STRUCTURE.SIGLE,
       STRUCTURE.LIBELLE_COURT,
       STRUCTURE.LIBELLE_LONG,
       STRUCTURE.LIBELLE_COMMUNICATION,
       STRUCTURE.SITE_URL,
       STRUCTURE.EMAIL_GENERIQUE,
       STRUCTURE.CODE_UAI,
       STRUCTURE.LOGO,
       STRUCTURE.TYPE_ID,
       STRUCTURE.TYPE_SUPANN,
       STRUCTURE.AFFICHAGE_ANNUAIRE,
       STRUCTURE_RECHERCHE.ID                                                          STRUCTURE_RECHERCHE_ID,
       STRUCTURE_RECHERCHE_POLE.LIBELLE                                                STRUCTURE_RECHERCHE_POLE,
       STRUCTURE.LOCAL_PRINCIPAL_ID,
       heritage.racine_id                                                              RACINE_ID,
       REPLACE(regexp_substr(heritage.chemin_id, ' > [^( > )]+( > |$)', 2), ' > ', '') NIV2_ID,
       heritage.parent_id                                                              PARENT_ID,
       heritage.niveau                                                                 NIVEAU,
       CASE
           WHEN heritage.racine_code = 'UNIV'
               THEN 1
           ELSE 0 END                                                                  INTERNE,
       STRUCTURE.DATE_OUVERTURE,
       STRUCTURE.DATE_FERMETURE,
       STRUCTURE.CENTRE_FINANCIER,
       heritage.chemin                                                                 CHEMIN
FROM STRUCTURE
         LEFT JOIN STRUCTURE_RECHERCHE ON STRUCTURE.ID = STRUCTURE_RECHERCHE.STRUCTURE_ID
         LEFT JOIN STRUCTURE_RECHERCHE_POLE ON STRUCTURE_RECHERCHE.POLE_RATTACHEMENT_ID = STRUCTURE_RECHERCHE_POLE.ID
         LEFT JOIN
     (SELECT DISTINCT id,
                      first_value(niveau)
                                  OVER (PARTITION BY id ORDER BY racine_id ASC RANGE UNBOUNDED PRECEDING)         niveau,
                      first_value(parent_id)
                                  OVER (PARTITION BY id ORDER BY racine_id ASC RANGE UNBOUNDED PRECEDING)         parent_id,
                      first_value(racine_id)
                                  OVER (PARTITION BY id ORDER BY racine_id ASC RANGE UNBOUNDED PRECEDING)         racine_id,
                      first_value(racine_code)
                                  OVER (PARTITION BY id ORDER BY racine_id ASC RANGE UNBOUNDED PRECEDING)         racine_code,
                      first_value(chemin)
                                  OVER (PARTITION BY id ORDER BY racine_id ASC RANGE UNBOUNDED PRECEDING)         chemin,
                      first_value(chemin_id)
                                  OVER (PARTITION BY id ORDER BY racine_id ASC RANGE UNBOUNDED PRECEDING)         chemin_id
      FROM multi_heritage
      ORDER BY id ASC) heritage ON STRUCTURE.ID = heritage.id
WHERE HISTO_DESTRUCTION IS NULL
ORDER BY STRUCTURE.ID ASC
);

CREATE OR REPLACE VIEW V_EMC2_STRUCTURE AS (
   select
       ID,
       LIBELLE_COURT,
       LIBELLE_LONG,
       CODE,
       SIGLE,
       TYPE_ID,
       TO_CHAR(DATE_OUVERTURE,'YYYY-MM-DD HH:MM:SS') AS DATE_OUVERTURE,
       TO_CHAR(DATE_FERMETURE,'YYYY-MM-DD HH:MM:SS') AS DATE_FERMETURE,
       PARENT_ID,
       NIV2_ID,
       EMAIL_GENERIQUE
   FROM V_STRUCTURE;
);