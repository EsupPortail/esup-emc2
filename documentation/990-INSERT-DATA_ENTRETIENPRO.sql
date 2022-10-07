-- ------------------------------------------------------------------------------------
-- ETAT -------------------------------------------------------------------------------
-- ------------------------------------------------------------------------------------

INSERT INTO unicaen_etat_etat_type (code, libelle, icone, couleur, histo_creation, histo_createur_id)
    VALUES ('ENTRETIEN_PROFESSIONNEL', 'États liés aux entretiens professionnels', 'fa fa-briefcase', '#033ed0', '2020-10-13 16:19:06.000000', 0);

INSERT INTO unicaen_etat_etat(type_id, code, libelle, ordre, icone, couleur, histo_creation, histo_createur_id)
WITH d(code, libelle, ordre, icone, couleur, histo_creation, histo_createur_id) AS (
    SELECT 'ENTRETIEN_VALIDATION_AGENT', 'Validation de l&rsquo;agent', 1400, 'far fa-check-square', '#44c200', current_date,0 UNION
    SELECT 'ENTRETIEN_VALIDATION_HIERARCHIE', 'Validation du responsable hiérarchique', 1300, 'fas fa-user-tie', '#ffae00', current_date,0 UNION
    SELECT 'ENTRETIEN_ACCEPTATION', 'En attente confirmation de l&rsquo;agent', 1, 'fas fa-user-clock', '#b90b80', current_date,0 UNION
    SELECT 'ENTRETIEN_ACCEPTER', 'Entretien accepté par l&rsquo;agent', 2, 'fas fa-user-check', '#c100b5', current_date,0 UNION
    SELECT 'ENTRETIEN_VALIDATION_OBSERVATION', 'Expression des observations', 1200, 'far fa-comment-alt', '#ff6c0a', current_date,0 UNION
    SELECT 'ENTRETIEN_VALIDATION_RESPONSABLE', 'Validation du responsable de l&rsquo;entretien professionnel', 1100, 'fas fa-user', '#c75000', current_date,0
)
SELECT cp.id, d.code, d.libelle, d.ordre, d.icone, d.couleur, d.histo_creation, d.histo_createur_id
FROM d
JOIN unicaen_etat_etat_type cp ON cp.CODE = 'ENTRETIEN_PROFESSIONNEL';

-- --------------------------------------------------------------------------------------
-- PARAMETRE ----------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------

INSERT INTO unicaen_parametre_categorie (code, libelle, ordre, description)
    VALUES ('ENTRETIEN_PROFESSIONNEL', 'Paramètres liés aux entretiens professionnels', 500, null);

INSERT INTO unicaen_parametre_parametre (categorie_id, code, libelle, description, valeurs_possibles, valeur, ordre)
WITH d(code, libelle, description, valeurs_possibles, valeur, ordre) AS (
    SELECT 'DELAI_ACCEPTATION_AGENT', 'Délai d''accepation de l''entretien par l''agent (en jours)', null, 'Number', '15', 100 UNION
    SELECT 'MAIL_LISTE_BIATS', 'Adresse électronique de la liste de liste de diffusion pour le personnel', '<p>Utilis&eacute;e lors de la cr&eacute;ation d''une campagne d''entretien profesionnel</p>', 'String', 'ZZZ_unicaen-biats@unicaen.fr', 11 UNION
    SELECT 'MAIL_LISTE_DAC', 'Adresse électronique de la liste de liste de diffusion pour les DAC', '<p>Utilis&eacute;e lors de la cr&eacute;ation d''une campagne d''entretien professionnel</p>', 'String', 'ZZZ_centrale-liste@unicaen.fr', 10 UNION
    SELECT 'INTRANET_DOCUMENT', 'Lien vers les documents associés à l''entretien professionnel', null, 'String', 'Pas d''intranet !!!! ', 1000
)
SELECT cp.id, d.code, d.libelle, d.description, d.valeurs_possibles, d.valeur, d.ordre
FROM d
JOIN unicaen_parametre_categorie cp ON cp.CODE = 'ENTRETIEN_PROFESSIONNEL';

-- --------------------------------------------------------------------------------------
-- ROLE ---------------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------

INSERT INTO unicaen_utilisateur_role (libelle, role_id, is_default, is_auto)
    VALUES ('Délégué·e pour entretien professionnel', 'Délégué·e pour entretien professionnel', false, true);


-- --------------------------------------------------------------------------------------
-- VALIDATION ---------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------

INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id)
    VALUES ('ENTRETIEN_AGENT', 'Validation de l''agent d''un entretien professionnel', false, current_date, 0);
INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id)
    VALUES ('ENTRETIEN_RESPONSABLE', 'Validation du responsable d''un entretien professionnel', false, current_date, 0);
INSERT INTO unicaen_validation_type (code, libelle, refusable, histo_creation, histo_createur_id)
    VALUES ('ENTRETIEN_HIERARCHIE', 'Validation de la DRH d''un entretien professionnel', false, current_date, 0);

-- --------------------------------------------------------------------------------------
-- FORMULAIRE ---------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------

INSERT INTO unicaen_autoform_formulaire (libelle, description, code, histo_creation, histo_createur_id)
    VALUES ('Compte-Rendu d''Entretien Professionnel (CREP)', null, 'ENTRETIEN_PROFESSIONNEL', current_date, 0);
INSERT INTO unicaen_autoform_formulaire (libelle, description, code, histo_creation, histo_createur_id)
    VALUES ('Compte-Rendu d''Entretien de formation(CREF)', null, 'FORMATION', current_date, 0);

-- ---------------------------------------------------------------------------------------
-- TEMPLATE ------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
    VALUES ('CREP - Compte rendu d''entretien professionnel', '<p>Compte-rendu de l''entretien professionnel d''un agent</p>',
            'pdf',
            'CREP.pdf',
            'Contenu du CREP');
INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
    VALUES ('CREF - Compte rendu d''entretien de formation', '<p>Compte-rendu de l''entretien de formation d''un agent</p>',
            'pdf',
            'CREF.pdf',
            'Contenu du CREF');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('CAMPAGNE_OUVERTURE_BIATSS', '<p>Mail envoyé aux personnels lors de l''ouverture d''une campagne</p>',
        'mail',
        'Ouverture campagne (BIATSS)',
        'Contenu Ouverture campagne (BIATSS)');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('CAMPAGNE_OUVERTURE_DAC', '<p>Mail envoyé aux responsables lors de l''ouverture d''une campagne</p>',
        'mail',
        'Ouverture campagne (DAC)',
        'Contenu Ouverture campagne (DAC)');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('CAMPAGNE_AVANCEMENT', '<p>Mail d''avancement de la campagne pour les responsables</p>',
        'mail',
        'Avancement de la campagne',
        'Contenu Avancement de la campagne');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('CAMPAGNE_DELEGUE', '<p>Mail envoyé aux délégué venant d''être nommé</p>',
        'mail',
        'Vous êtes un délégué pour la campagne',
        'Contenu Vous êtes un délégué pour la campagne');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_CONVOCATION_ENVOI', '<p>Convocation à un entretien professionnel</p>',
        'mail',
        'Convocation à votre entretien professionnel',
        'Contenu Convocation à votre entretien professionnel');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_CONVOCATION_ENVOI', '<p>Convocation à un entretien professionnel</p>',
        'mail',
        'Convocation à votre entretien professionnel',
        'Contenu Convocation à votre entretien professionnel');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_CONVOCATION_ACCEPTER', '<p>Acceptation de l''entretien professionnel</p>',
        'mail',
        'Acceptation de l''entretien professionnel',
        'Contenu Acceptation de l''entretien professionnel');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_RAPPEL_AGENT', '<p>Rappel de l''entretien professionnel</p>',
        'mail',
        'Rappel de votre entretien professionnel',
        'Contenu Rappel de votre entretien professionnel');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_VALIDATION_1-RESPONSABLE', '<p>Validation du responsable de l''entretien</p>',
        'mail',
        'Votre responsable vient de valider votre entretien professionnel',
        'Contenu Votre responsable vient de valider votre entretien professionnel');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_VALIDATION_2-OBSERVATION', '<p>Observations faites et validées</p>',
        'mail',
        'Des observations ont été faites et validées',
        'Contenu Des observations ont été faites et validées');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_VALIDATION_2-PAS_D_OBSERVATION', '<p>Pas d''observations validées</p>',
        'mail',
        'Pas d''observations validées',
        'Contenu Pas d''observations validées');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_VALIDATION_2-OBSERVATION_TRANSMISSION', '<p>Transmission des observations</p>',
        'mail',
        'Transmission des observations',
        'Contenu Transmission des observations');

INSERT INTO unicaen_renderer_template (code, description, document_type, document_sujet, document_corps)
VALUES ('ENTRETIEN_VALIDATION_3-HIERARCHIE', '<p>Validation de l''autorité hiérarchique</p>',
        'mail',
        'Validation de l''autorité hiérarchique',
        'Contenu Validation de l''autorité hiérarchique');

-- TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO
-- TODO -- AJOUTER LES MACROS LIES -- TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO
-- TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO

-- ---------------------------------------------------------------------------------------
-- EVENEMENT -----------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------

INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
    VALUES ('rappel_entretienpro', 'Rappel de l''entretien professionnel', 'Rappel à J-4 de l''entretien professionnel', 'entretien', null);
INSERT INTO unicaen_evenement_type (code, libelle, description, parametres, recursion)
    VALUES ('rappel_campagne', 'Rappel de l''avancement de la campagne', 'Mail envoyé périodiquement lors de la campagne aux responsables de structures pour leur rappeler l''état d''avancement de la campagne', 'campagne', 'P2W');