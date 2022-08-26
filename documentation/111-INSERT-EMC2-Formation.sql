-- FORMATION

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formation', 'Gestion des formations ', 310, 'Formation\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
SELECT 'formation_acces', 'Accés à l''index des formations', 10 UNION
SELECT 'formation_afficher', 'Afficher une formation ', 20 UNION
SELECT 'formation_ajouter', 'Ajouter une formation ', 30 UNION
SELECT 'formation_modifier', 'Modifier une formation ', 40 UNION
SELECT 'formation_historiser', 'Historiser/Restaurer une formation ', 50 UNION
SELECT 'formation_supprimer', 'Supprimer une formation ', 60 UNION
SELECT 'formation_questionnaire_visualiser', 'Afficher les questionnaires de retour de formation', 110 UNION
SELECT 'formation_questionnaire_modifier', 'Renseigner les questionnaires de retour de formation', 120
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formation';

-- FORMATION GROUPE

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationgroupe', 'Gestion des formations - Groupe', 311, 'Formation\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
SELECT 'formationgroupe_afficher', 'Afficher un groupe de formation', 10 UNION
SELECT 'formationgroupe_ajouter', 'Ajouter un groupe de formation', 20 UNION
SELECT 'formationgroupe_modifier', 'Modifier un groupe de formation', 30 UNION
SELECT 'formationgroupe_historiser', 'Historiser/Restaurer un groupe de formation', 40 UNION
SELECT 'formationgroupe_supprimer', 'Supprimer un groupe de formation ', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationgroupe';

-- FORMATION THEME TODO  -- SERT ENCORE --

INSERT INTO public.unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationtheme', 'Gestion des formations - Thème', 312, 'Formation\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationtheme_afficher', 'Afficher un thème de formation ', 10 UNION
    SELECT 'formationtheme_ajouter', 'Ajouter un thème de formation', 20 UNION
    SELECT 'formationtheme_modifier', 'Modifier un thème de formation', 30 UNION
    SELECT 'formationtheme_historiser', 'Modifier un thème de formation ', 40 UNION
    SELECT 'formationtheme_supprimer', 'Supprimer un thème de formation ', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationtheme';

-- ACTION --

INSERT INTO public.unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationinstance', 'Gestion des formations - Actions de formation', 313, 'Formation\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
SELECT 'formationinstance_afficher', 'Afficher une action de formation', 10 UNION
SELECT 'formationinstance_ajouter', 'Ajouter une action de formation', 20 UNION
SELECT 'formationinstance_modifier', 'Modifier une action de formation', 30 UNION
SELECT 'formationinstance_historiser', 'Historiser/Restaurer une action de formation', 40 UNION
SELECT 'formationinstance_supprimer', 'Supprimer une instance de formation', 50 UNION
SELECT 'formationinstance_gerer_inscription', 'Gérer les inscriptions à une instance de formation', 100 UNION
SELECT 'formationinstance_gerer_seance', 'Gérer les séances à une instance de formation', 110 UNION
SELECT 'formationinstance_gerer_formateur', 'Gérer les formateurs à une instance de formation', 120
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstance';

INSERT INTO public.unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationinstancedocument', 'Gestion des formations - Documents', 319, 'Formation\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
SELECT 'formationinstancedocument_convocation', 'Génération des convocations', 10 UNION
SELECT 'formationinstancedocument_emargement', 'Génération des listes d''émargement', 20 UNION
SELECT 'formationinstancedocument_attestation', 'Génération des attestations de formation', 30

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstancedocument';

INSERT INTO public.unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationinstancefrais', 'Gestion des formations - Frais', 317, 'Formation\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
SELECT 'formationinstancefrais_afficher', 'Afficher les frais d''un agent', 10 UNION
SELECT 'formationinstancefrais_modifier', 'Modifier les frais d''un agent', 20
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstancefrais';

INSERT INTO public.unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationinstanceinscrit', 'Gestion des formations - Inscrits', 316, 'Formation\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationinstanceinscrit_modifier', 'Modifier un inscrit à une action de formation', 30
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstanceinscrit';

INSERT INTO public.unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('formationinstancepresence', 'Gestion des formations - Présences', 314, 'Formation\Provider\Privilege');

INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
SELECT 'formationinstancepresence_afficher', 'Afficher les présences d''une action de formation', 10 UNION
SELECT 'formationinstancepresence_modifier', 'Modifier les présences d''une action de formation', 30

)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationinstancepresence';

-- PLAN DE FORMATION

INSERT INTO public.unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('planformation', 'Gestion du plan de formation', 'Formation\Provider\Privilege', 1000);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'planformation_acces', 'Accéder au plan de formation', 0
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'planformation';

-- ABONNEMENT

INSERT INTO public.unicaen_privilege_categorie (code, libelle, namespace, ordre)
VALUES ('formationabonnement', 'Gestion du abonnement aux formations', 'Formation\Provider\Privilege', 1100);
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'formationabonnement_abonner', 'S''abonner une formation', 0 UNION
    SELECT 'formationabonnement_desabonner', 'Se desinscrire d''une formation', 10 UNION
    SELECT 'formationabonnement_liste_agent', 'Lister les abonnements par agents', 20 UNION
    SELECT 'formationabonnement_liste_formation', 'Lister les abonnements par foramtions', 40 UNION
    SELECT 'formationabonnement_gerer', 'Gérer les abonnements', 50
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'formationabonnement';
-- ATTRIBUTION A L'ADMIN TECH ---------------------------------------------------------------------------------

TRUNCATE TABLE unicaen_privilege_privilege_role_linker;
INSERT INTO unicaen_privilege_privilege_role_linker (privilege_id, role_id)
WITH d(privilege_id) AS (
    SELECT id FROM unicaen_privilege_privilege
)
SELECT d.privilege_id, cp.id
FROM d
         JOIN unicaen_utilisateur_role cp ON cp.role_id = 'Administrateur·trice technique'
;

-- ETAT ----

INSERT INTO unicaen_etat_etat_type (code, libelle, icone, couleur)
VALUES ('FORMATION_SESSION', 'Gestion des sessions de formation', 'fas fa-graduation-cap', '#3465a4');

INSERT INTO unicaen_etat_etat(type_id, code, libelle, icone, couleur, ordre)
WITH e(code, libelle, icone, couleur, ordre) AS (
    SELECT 'EN_CREATION', 'En cours de saisie', 'fas fa-edit', '#75507b', 10 UNION
    SELECT 'INSCRIPTION_OUVERTE', 'Inscription ouverte', 'fas fa-book-open', '#729fcf', 20 UNION
    SELECT 'INSCRIPTION_FERMEE', 'Inscription close', 'fas fa-book', '#204a87', 30 UNION
    SELECT 'CONVOCATION', 'Convocations envoyées', 'fas fa-file-contract', '#fcaf3e', 40 UNION
    SELECT 'ATTENTE_RETOUR', 'Demande des retours', 'far fa-comments', '#ce5c00', 50 UNION
    SELECT 'FERMEE', 'Session fermée', 'far fa-check-square', '#4e9a06', 60
)
SELECT et.id, e.code, e.libelle, e.icone, e.couleur, e.ordre
FROM e
JOIN unicaen_etat_etat_type et ON et.CODE = 'FORMATION_SESSION';

-- EVENEMENT ----

INSERT INTO public.unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('notification_nouvelle_session', 'notification_nouvelle_session', 'Notification hebdomadaire de nouvelle session de formation', null, 'P1W');
INSERT INTO public.unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('notification_rappel_session_imminente', 'notification_rappel_session_imminente', 'Notification de rappel d''une session de formation', null, null);
INSERT INTO public.unicaen_evenement_type (code, libelle, description, parametres, recursion)
VALUES ('cloture_automatique_inscription', 'cloture_automatique_inscription', 'cloture_automatique_inscription', null, 'P1D');