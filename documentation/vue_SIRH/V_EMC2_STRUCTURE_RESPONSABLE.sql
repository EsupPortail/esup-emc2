-- ---------------------------------------------
-- RESPONSABILITE DE STRUCTURE -----------------
-- ---------------------------------------------
CREATE OR REPLACE VIEW V_EMC2_FONCTION_RESPONSABLE AS
(
    SELECT F.ID, MAX(F.CODE) AS CODE, MIN(FL.LIBELLE) AS LIBELLE_1, MAX(FL.LIBELLE) AS LIBELLE_2
    FROM FONCTION F
             JOIN FONCTION_LIBELLE FL on FL.FONCTION_ID = F.ID
    WHERE F.ID IN (
        -- Directeur                                 et Sous-fonction
                   1,                                   2, 3, 4, 5, 121,
        -- Responsable de bureau
                   7,
        -- Directeur adjoint                         et Sous-fonction
                   11, 12,
        -- Directeur Général des Services            et Sous-fonction
                   16, 17, 141,
        -- Responsable                               et Sous-fonction
                   19, 20, 21, 161,
        -- Responsable Adminsitratif                 et Sous-fonction
                   22, 23, 24, 25, 26,
        -- Agent comptable                           et Sous-fonction
                   27, 28
    )
GROUP BY F.ID
    );

-- Requete recuperant les individus ayant une de ces fonctions "actives"
SELECT *
FROM INDIVIDU_FONCTION IFO
WHERE IFO.FONCTION_ID IN (SELECT ID FROM V_EMC2_FONCTION_RESPONSABLE)
  AND DATE_DEBUT <= sysdate
  AND (DATE_FIN IS NULL OR DATE_FIN >= sysdate)
  AND IFO.HISTO_DESTRUCTION IS NULL
;

-- Creation de la vue "finale" pour EMC2
CREATE OR REPLACE VIEW V_EMC2_STRUCTURE_RESPONSABLE AS
(
    SELECT IFO.ID                                         AS ID,
           IFO.INDIVIDU_ID                                AS AGENT_ID,
           IFO.STRUCTURE_ID                               AS STRUCTURE_ID,
           IFO.FONCTION_ID                                AS FONCTION_ID,
           TO_CHAR(IFO.DATE_DEBUT, 'YYYY-MM-DD HH:MM:SS') AS DATE_DEBUT,
           TO_CHAR(IFO.DATE_FIN, 'YYYY-MM-DD HH:MM:SS')   AS DATE_FIN
    FROM INDIVIDU_FONCTION IFO
    join V_EMC2_STRUCTURE VS ON VS.ID = IFO.STRUCTURE_ID
    WHERE IFO.FONCTION_ID IN (SELECT ID FROM V_EMC2_FONCTION_RESPONSABLE)
      AND IFO.HISTO_DESTRUCTION IS NULL
      AND IFO.DATE_DEBUT <= sysdate
      AND (IFO.DATE_FIN IS NULL OR IFO.DATE_FIN >= sysdate)
);

select I.PRENOM, NVL(I.NOM_USAGE, I.NOM_FAMILLE), S.LIBELLE_LONG, V.FONCTION_ID, V.DATE_DEBUT, V.DATE_FIN
from V_EMC2_STRUCTURE_RESPONSABLE V
join INDIVIDU I on I.C_INDIVIDU_CHAINE = V.AGENT_ID
join STRUCTURE S on S.ID = V.STRUCTURE_ID
join FONCTION F on V.FONCTION_ID = F.ID
order by I.NOM_USAGE, I.PRENOM, S.LIBELLE_COURT;

