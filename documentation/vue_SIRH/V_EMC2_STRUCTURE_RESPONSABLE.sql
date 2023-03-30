-- ---------------------------------------------
-- RESPONSABILITE DE STRUCTURE -----------------
-- ---------------------------------------------

-- Requete remontant les fonctions
SELECT F.ID, MIN(FL.LIBELLE)
FROM FONCTION F
JOIN FONCTION_LIBELLE FL on F.ID = FL.FONCTION_ID
GROUP BY F.ID
;

-- Creation de la vue contenant les fonctions eligibles
CREATE OR REPLACE VIEW V_EMC2_FONCTION_RESPONSABLE AS
(
    SELECT F.ID
    FROM FONCTION F
    WHERE F.ID IN (1, 22, 27, 28, 7, 19, 11, 16, 17, 141)
);

-- Requete recuperant les individus ayant une de ces fonctions "actives"
SELECT *
FROM INDIVIDU_FONCTION IFO
WHERE IFO.FONCTION_ID IN (SELECT * FROM V_EMC2_FONCTION_RESPONSABLE)
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
    WHERE IFO.FONCTION_ID IN (SELECT * FROM V_EMC2_FONCTION_RESPONSABLE)
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

