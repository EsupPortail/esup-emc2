-- ---------------------------------------------------------------------------------------
-- ETAT ----------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------

INSERT INTO unicaen_etat_etat_type (code, libelle, icone, couleur, histo_creation, histo_createur_id)
VALUES ('FICHE_METIER', 'États associés aux fiches métiers', 'fas fa-book-open', '#27807b', current_date, 0);
INSERT INTO unicaen_etat_etat(type_id, code, libelle, ordre, icone, couleur, histo_creation, histo_createur_id)
WITH d(code, libelle, ordre, icone, couleur, histo_creation, histo_createur_id) AS (
    SELECT 'FICHE_METIER_REDACTION', 'Fiche métier en cours de rédaction', 8, 'fas fa-pencil-ruler', '#ff9500', current_date, 0 UNION
    SELECT 'FICHE_METIER_OK', 'Fiche métier validée', 8, 'fas fa-check', '#00a004', current_date, 0 UNION
    SELECT 'FICHE_METIER_MASQUEE', 'Fiche métier masquée', 8, 'fas fa-mask', '#cb0000', current_date, 0
)
SELECT cp.id, d.code, d.libelle, d.ordre, d.icone, d.couleur, d.histo_creation, d.histo_createur_id
FROM d
         JOIN unicaen_etat_etat_type cp ON cp.CODE = 'FICHE_METIER';
-- ---------------------------------------------------------------------------------------
-- TEMPLATE ------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('MISSION_SPECIFIQUE_LETTRE', '<p>Lettre associée à une mission spécifique</p>',
        'pdf',
        'mission.pdf',
        'Contenu de Mission');