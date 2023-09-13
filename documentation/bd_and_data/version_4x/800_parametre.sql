INSERT INTO unicaen_parametre_categorie (code, libelle, ordre, description)
VALUES ('GLOBAL', 'Paramètres globaux', 1, null);
INSERT INTO unicaen_parametre_parametre(CATEGORIE_ID, CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, VALEUR, ORDRE)
WITH d(CODE, LIBELLE, DESCRIPTION, VALEURS_POSSIBLES, VALEUR, ORDRE) AS (
    SELECT 'CODE_UNIV', 'Code de l''établissement porteur principal', '<p>Sert notamment pour l''affichage des status</p>', 'String', 'UNIV', 1000 UNION
    SELECT 'INSTALL_PATH', 'Chemin d''installation (utiliser pour vérification)', null, 'String', '/var/www/html', 1000 UNION
    SELECT 'EMAIL_ASSISTANCE', 'Adresse électronique de l''assistance', null, 'String', 'assistance-emc2@mon-etablisssement.fr', 100
)
SELECT cp.id, d.CODE, d.LIBELLE, d.DESCRIPTION, d.VALEURS_POSSIBLES, d.VALEUR, d.ORDRE
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'GLOBAL';
