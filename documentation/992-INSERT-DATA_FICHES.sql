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

INSERT INTO unicaen_etat_etat_type (code, libelle, icone, couleur, histo_creation, histo_createur_id)
    VALUES ('FICHE_POSTE', 'États associés aux fiches de poste', 'fas fa-book-reader', '#00d88d', current_date, 0);
INSERT INTO unicaen_etat_etat(type_id, code, libelle, ordre, icone, couleur, histo_creation, histo_createur_id)
WITH d(code, libelle, ordre, icone, couleur, histo_creation, histo_createur_id) AS (
    SELECT 'FICHE_POSTE_REDACTION', 'Fiche de poste en cours de rédaction', 1, 'fas fa-pencil-ruler', '#ff9500', current_date, 0 UNION
    SELECT 'FICHE_POSTE_OK', 'Fiche de poste validée', 2, 'fas fa-check', '#00a004', current_date, 0 UNION
    SELECT 'FICHE_POSTE_MASQUEE', 'Fiche de poste masquée', 4, 'fas fa-mask', '#cb0000', current_date, 0 UNION
    SELECT 'FICHE_POSTE_SIGNEE', 'Fiche de poste signée', 3, 'fas fa-signature', '#000099', current_date, 0
)
SELECT cp.id, d.code, d.libelle, d.ordre, d.icone, d.couleur, d.histo_creation, d.histo_createur_id
FROM d
JOIN unicaen_etat_etat_type cp ON cp.CODE = 'FICHE_POSTE';

-- ---------------------------------------------------------------------------------------
-- TEMPLATE ------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('MISSION_SPECIFIQUE_LETTRE', '<p>Lettre associée à une mission spécifique</p>',
        'pdf',
        'mission.pdf',
        'Contenu de Mission');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('FICHE_METIER', '<p>Fiche métier</p>',
        'pdf',
        'fiche_metier.pdf',
        'Contenu de FICHE METIER');
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (60, 'MAIL_VALIDATION_RESPONSABLE', '<p>Mail envoyé à l''agent·e après la validation d''une fiche de poste par le·la responsable de l''agent·e</p>', 'mail', 'Votre fiche de poste de poste vient d''être validée par votre responsable', '<p>Université de Caen Normandie<br />Direction des Ressources Humaines<br /><br />Bonjour,<br /><br />Votre fiche de poste vient d''être validée par votre responsable.</p>
<p>Vous pouvez maintenant vous rendre dans EMC2 pour valider à votre tour celle-ci.<br />Vous retrouverez celle-ci à l''adresse suivante : VAR[URL#FichePosteAcceder]</p>
<p><br />Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences<br />VAR[EMC2#Nom]</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (61, 'MAIL_VALIDATION_AGENT', null, 'mail', 'Validation de VAR[AGENT#Denomination] de sa fiche de poste', '<p>Bonjour,</p>
<p>VAR[AGENT#Denomination] vient de valider sa fiche de poste.</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences<br />VAR[EMC2#Nom]</p>', null);
