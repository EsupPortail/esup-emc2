-- ---------------------------------------------------------------------------------------
-- EVENEMENT -----------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------

INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
    VALUES ('rappel_entretienpro', 'Rappel de l''entretien professionnel', 'Rappel à J-4 de l''entretien professionnel', 'entretien', null);
INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
    VALUES ('rappel_campagne', 'Rappel de l''avancement de la campagne', 'Mail envoyé périodiquement lors de la campagne aux responsables de structures pour leur rappeler l''état d''avancement de la campagne', 'campagne', 'P2W');