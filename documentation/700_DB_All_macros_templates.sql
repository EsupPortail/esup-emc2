-- nettoyage
truncate table unicaen_renderer_macro;
truncate table unicaen_renderer_template;

-- macros
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (101, 'AGENT#DateNaissance', '', 'agent', 'toStringDateNaissance');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (102, 'AGENT#Prenom', '', 'agent', 'toStringPrenom');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (104, 'AGENT#NomFamille', '', 'agent', 'toStringNomFamille');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (103, 'AGENT#NomUsage', '', 'agent', 'toStringNomUsage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (237, 'SESSION#duree', '', 'session', 'getDuree');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (238, 'SESSION#formateurs', '<p>retourne la liste des formateurs sous la forme d un tableau</p>', 'session', 'getListeFormateurs');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (239, 'SESSION#identification', 'Retour l identifiant unique de la session sous la forme <em>ACTION/SESSION</em>', 'session', 'getInstanceCode');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (240, 'SESSION#libelle', '', 'session', 'getInstanceLibelle');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (242, 'SESSION#periode', '<p>Affiche la période sous la forme <em>DEBUT au FIN</em></p>', 'session', 'getPeriode');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (243, 'SESSION#seances', '', 'session', 'getListeJournees');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (244, 'EMC2#appname', '', 'MacroService', 'getAppName');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (245, 'EMC2#date', 'Affiche la date du jour d/m/Y', 'MacroService', 'getDate');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (246, 'EMC2#datetime', 'Affiche la date et l heure d/m/Y à H:i', 'MacroService', 'getDateTime');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (247, 'INSCRIPTION#duree', '<p>Affiche la durée de présence de l inscrit</p>', 'inscription', 'getDureePresence');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (248, 'INSCRIPTION#justificationAgent', '<p>Retourne la motivation de l agent</p>', 'inscription', 'getJustificationAgent');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (249, 'INSCRIPTION#justificationRefus', '<p>Retourne la motivation de la désinscription ou du refus</p>', 'inscription', 'getJustificationRefus');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (250, 'INSCRIPTION#justificationResponsable', '<p>Retourne la motivation du responsable</p>', 'inscription', 'getJustificationResponsable');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (241, 'SESSION#lieu', '', 'session', 'getLieuString');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (187, 'ENTRETIEN#CREP_experiencepro', null, 'entretien', 'toString_CREP_experiencepro');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (190, 'ENTRETIEN#CREP_encadrementA', null, 'entretien', 'toString_CREP_encadrementA');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (191, 'ENTRETIEN#CREP_encadrementB', null, 'entretien', 'toString_CREP_encadrementB');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (192, 'ENTRETIEN#CREP_encadrementC', null, 'entretien', 'toString_CREP_encadrementC');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (193, 'ENTRETIEN#CREP_projet', null, 'entretien', 'toString_CREP_projet');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (194, 'ENTRETIEN#CREP_encadrement', null, 'entretien', 'toString_CREP_encadrement');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (195, 'AGENT#StructureAffectationPrincipale', '', 'agent', 'toStringAffectationStructure');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (196, 'AGENT#DateAffectationPrincipale', '', 'agent', 'toStringAffectationDate');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (197, 'AGENT#CorpsGrade', '', 'agent', 'toStringCorpsGrade');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (202, 'AGENT#AffectationStructure', '<p>Affiche le libellé long de la structure de niveau 2 de l''agent.</p>', 'agent', 'toStringAffectationStructure');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (205, 'AGENT#IntitulePoste', '', 'agent', 'toStringIntitulePoste');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (208, 'AGENT#EmploiType', '', 'agent', 'toStringEmploiType');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (209, 'AGENT#Missions', '', 'agent', 'toStringMissions');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (231, 'STRUCTURE#bloc', null, 'structure', 'toStringStructureBloc');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (232, 'STRUCTURE#gestionnaires', '<p>Affiche sous la forme d''un listing les Gestionnaires de la structure</p>', 'structure', 'toStringGestionnaires');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (198, 'ENTRETIEN#ReponsableCorpsGrade', null, 'entretien', 'toStringReponsableCorpsGrade');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (175, 'ENTRETIEN#ObservationEntretien', null, 'entretien', 'toStringObservationEntretien');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (217, 'ENTRETIEN#CREP_Champ', '<p>Retourne le contenu du champ (AutoForm) dans l''identification passe par les mots clefs passés en paramètre</p>', 'entretien', 'toStringCREP_Champ');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (218, 'METIER#Domaine', '<p>Affichage des domaines sous la forme d''une liste à puce</p>', 'metier', 'getDomainesAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (219, 'FICHE_POSTE#SpecificiteActivites', '', 'ficheposte', 'toStringSpecificiteActivites');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (220, 'ENTRETIEN#CREF_Champ', '<p>Retourne le contenu du champ (AutoForm) dans l''identification passe par les mots clefs passés en paramètre</p>', 'entretien', 'toStringCREF_Champ');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (221, 'ENTRETIEN#CREF_Champs', '', 'entretien', 'toStringCREF_Champs');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (222, 'Agent#Echelon', '', 'agent', 'toStringEchelon');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (224, 'AGENT#MissionsSpecifiques', '<p>Affiches la section ''mission spécifique'' de la fiche de poste d''un agent (si il y a des missions spécifique)</p>', 'agent', 'toStringMissionsSpecifiques');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (225, 'ENTRETIEN#VALIDATION_AGENT', '', 'entretien', 'toStringValidationAgent');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (226, 'ENTRETIEN#VALIDATION_SUPERIEUR', '', 'entretien', 'toStringValidationResponsable');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (228, 'ENTRETIEN#Id', '', 'entretien', 'getId');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (229, 'URL#FichePosteAcceder', '', 'UrlService', 'getUrlFichePoste');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (230, 'AGENT#QuotiteAffectation', '', 'agent', 'getQuotiteAffectation');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (233, 'STRUCTURE#libelle', '<p>Retourne le libellé de la structure</p>', 'structure', 'toStringLibelle');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (234, 'STRUCTURE#libelle_long', '<p>Retourne le libellé de la structure + le libell&amp;eacute de la structure de niveau 2</p>', 'structure', 'toStringLibelleLong');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (235, 'STRUCTURE#responsables', '<p>Affiches sous la forme d''un listing les Responsables d''une structure</p>', 'structure', 'toStringResponsables');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (236, 'STRUCTURE#resume', '', 'structure', 'toStringResume');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (203, 'AGENT#AffectationStructureFine', '<p>Affiche le libellé long de la structure fine de l''agent</p>', 'agent', 'toStringAffectationStructureFine');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (176, 'ENTRETIEN#ObservationPerspective', null, 'entretien', 'toStringObservationPerspective');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (199, 'ENTRETIEN#ReponsableNomUsage', null, 'entretien', 'toStringReponsableNomUsage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (206, 'ENTRETIEN#ReponsableIntitlePoste', null, 'entretien', 'toStringReponsableIntitulePoste');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (223, 'Agent#EchelonDate', '', 'agent', 'toStringEchelonPassage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (227, 'ENTRETIEN#VALIDATION_AUTORITE', '', 'entretien', 'toStringValidationHierarchie');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (3, 'ENTRETIEN#lieu', '<p>Retourne le lieu de l''entretien professionnel</p>', 'entretien', 'toStringLieu');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (5, 'ENTRETIEN#date', '<p>Retourne la date de l''entretien professionnel</p>', 'entretien', 'toStringDate');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (6, 'FORMATION_INSTANCE#JourneeTableau', '<p>Affiche sous la forme d''un tableau la liste des (demi-)journées de formation associ&eacute; &agrave; l''action de formation.</p>', 'instance', 'getListeJournees');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (7, 'FORMATION_INSTANCE#InstanceId', '<p>Retourne le code de l''action de formation. Celui-ci est compos&eacute; de l''identifiant de la formation et de l''identifiant de l''action.</p>', 'instance', 'getInstanceCode');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (8, 'FORMATION#TITRE', '<p>Retourne le titre de la formation</p>', 'formation', 'getLibelle');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (9, 'FORMATION_INSTANCE#Debut', '<p>Retourne le jour de la premi&egrave;re journ&eacute;e de formation</p>', 'instance', 'toStringDebut');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (10, 'FORMATION_INSTANCE#Fin', '<p>Retourne le jour de la derni&egrave;re journ&eacute;e de formation</p>', 'instance', 'toStringFin');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (11, 'FORMATION_INSTANCE#Lieu', '<p>Retourne le lieu de la formation (!!! que mettre ?)</p>', 'instance', 'toStringLieu');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (12, 'FORMATION_INSTANCE#Duree', '<p>Retourne la dur&eacute;e de la formation en nombre d''heure</p>', 'instance', 'toStringDuree');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (13, 'FORMATION_INSTANCE#FormateursTableau', '<p>Retourne la liste des formateurs de l''action de formation sous la forme d''un tableau</p>', 'instance', 'getListeFormateurs');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (14, 'FORMATION_INSTANCE#ListeInscritPrincipaleTableau', '<p>Retourne la liste principale sous la forme d''un tableau des agents contenus dans celle-ci.</p>', 'instance', 'toStringListeInscritPrincipal');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (15, 'FORMATION_INSCRIT#TEMPS_PRESENCE', '<p>Dur&eacute;e de pr&eacute;sence d''un agent inscrit &agrave; une action de formation.</p>', 'inscrit', 'getDureePresence');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (16, 'FICHE_METIER#INTITULE', '<p>Retourne le titre du m&eacute;tier associ&eacute; &agrave; la fiche m&eacute;tier</p>', 'fichemetier', 'getIntitule');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (18, 'MISSION_SPECIFIQUE_AFFECTATION#ID', '<p>Retroune l''identifiant de l''affectation de mission sp&eacute;cifique</p>', 'affectation', 'getId');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (19, 'MISSION_SPECIFIQUE#LIBELLE', '<p>Retourne le libell&eacute; d''une mission sp&eacute;cifique</p>', 'mission', 'getLibelle');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (20, 'MISSION_SPECIFIQUE_AFFECTATION#PERIODE', '<p>Retourne la p&eacute;riode de l''affectation de la mission sp&eacute;cifique</p>', 'affectation', 'getPeriode');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (21, 'MISSION_SPECIFIQUE_AFFECTATION#DECHARGE', '<p>Retourne le volume horaire associ&eacute; &agrave; la d&eacute;charge</p>', 'affectation', 'getDechargeTexte');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (22, 'PROFIL#LIEU', '<p>Affichage du paragraphe de lieu pour le profil de recrutement</p>', 'ficheprofil', 'getLieuAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (23, 'PROFIL#CONTEXTE', '<p>Paragraphe de contexte</p>', 'ficheprofil', 'getContexteAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (24, 'PROFIL#MISSION', '<p>Paragraphe de mission</p>', 'ficheprofil', 'getMissionAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (204, 'ENTRETIEN#ReponsableStructure', '<p>Affiche le libellé long d''affectation du responsable de l''entretien professionnel</p>', 'entretien', 'toStringReponsableStructure');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (207, 'AGENT#Quotite', '', 'agent', 'toStringQuotite');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (200, 'ENTRETIEN#ReponsableNomFamille', null, 'entretien', 'toStringReponsableNomFamille');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (31, 'FICHE_POSTE#ConnaissancesToutes', '<p>Affiche les connaissances (y compris les retir&eacute;es)</p>', 'ficheposte', 'toStringAllCompetencesConnaissances');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (32, 'FICHE_POSTE#CompetencesComportementalesToutes', '<p>Affiche la liste des comp&eacute;tences comportementales (y compris les retir&eacute;es)</p>', 'ficheposte', 'toStringAllCompetencesComportementales');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (33, 'FICHE_POSTE#CompetencesOperationnellesToutes', '<p>Affiche la liste des comp&eacute;tences op&eacute;rationnelles d''une fiche de poste (y compris les retir&eacute;es)</p>', 'ficheposte', 'toStringAllCompetencesOperationnelles');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (34, 'FICHE_METIER#MISSIONS_PRINCIPALES', '<p>Retour le paragraphe des missions principales d''une fiche m&eacute;tier</p>', 'fichemetier', 'getMissions');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (35, 'FICHE_METIER#COMPETENCES', '<p>Retourne la liste des comp&eacute;tences d''une fiche m&eacute;tier</p>', 'fichemetier', 'getCompetences');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (39, 'METIER#REFERENCE', '<p>Affiche la liste des r&eacute;f&eacute;rences (Referens III, RIME, ...) d''un m&eacute;tier</p>', 'metier', 'getReferencesAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (40, 'FICHE_METIER#APPLICATIONS', '<p>Affiche les applications associ&eacute;s &agrave; une fiche m&eacute;tier</p>', 'fichemetier', 'getApplicationsAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (42, 'AGENT#AffectationsActives', '<p>Affiche sous la forme d''une liste à puces les affectations actives d''un agent</p>', 'agent', 'toStringAffectationsActives');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (43, 'AGENT#StatutsActifs', '<p>Affiche la liste des statuts actifs d''un agent sous la forme d''une liste &agrave; puce</p>', 'agent', 'toStringStatutsActifs');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (44, 'AGENT#GradesActifs', '<p>Affiche les grades actifs d''un agent sous la forme d''une liste &agrave; puce</p>', 'agent', 'toStringGradesActifs');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (45, 'AGENT#AgentBloc', '<p>Bloc de description d''un agent (<strong>TODO</strong> A remplacer lorsque l''on aura les macros de macro <strong>TODO</strong>)</p>', 'agent', 'toStringAgentBloc');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (46, 'AGENT#StructuresBloc', '<p>Affichage en bloc des structures de l''agent (depuis les affectations actives)</p>', 'agent', 'toStringStructuresBloc');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (47, 'FICHE_POSTE#Composition', '<p>Listing des fiches m&eacute;tiers composant une fiche de poste</p>', 'ficheposte', 'toStringCompositionFichesMetiers');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (48, 'FICHE_POSTE#Specificite', '<p>Affichage des sp&eacute;cificit&eacute; du poste</p>', 'ficheposte', 'toStringSpecificiteComplete');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (49, 'FICHE_POSTE#Applications', '<p>Affichage des applications li&eacute;es &agrave; une fiche de poste (comprend les applications li&eacute;es aux activit&eacute;s si non retir&eacute;es)</p>', 'ficheposte', 'toStringApplications');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (51, 'FICHE_POSTE#Formations', '<p>Affichages des formations associ&eacute;es &agrave; une fiche de poste</p>', 'ficheposte', 'toStringFormations');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (52, 'FICHE_POSTE#Parcours', '<p>Affichages des parcours de formations associ&eacute;s &agrave; une fiche de poste</p>', 'ficheposte', 'toStringParcours');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (53, 'FICHE_POSTE#FichesMetiers', '<p>Affichage des fiches m&eacute;tiers</p>', 'ficheposte', 'toStringFichesMetiers');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (54, 'FICHE_POSTE#LIBELLE_COMPLEMENTAIRE', '<p>Affichage du libell&eacute; compl&eacute;mentaire</p>', 'ficheposte', 'toStringLibelleComplementaire');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (55, 'MISSION_SPECIFIQUE#DESCRIPTION', '<p>Affiche la description d''une mission sp&eacute;cifique ou&nbsp; ''Aucune description fournie pour cette mission sp&eacute;cifique'' si la description est manquante</p>', 'mission', 'toStringDescription');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (56, 'FICHE_POSTE#Connaissances', '<p>Affiche la liste des connaissances (non retir&eacute;es)</p>', 'ficheposte', 'toStringCompetencesConnaissances');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (57, 'FICHE_POSTE#CompetencesOperationnelles', '<p>Affiche la liste des comp&eacute;tences op&eacute;rationnelles (non retir&eacute;es)</p>', 'ficheposte', 'toStringCompetencesOperationnelles');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (58, 'FICHE_POSTE#CompetencesComportementales', '<p>Affiche les comp&eacute;tences comportementales (non retir&eacute;es)</p>', 'ficheposte', 'toStringCompetencesComportementales');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (59, 'PROFIL#VACANCE', '<p>Affiche la mention Vacance d''emploi si n&eacute;cessaire</p>', 'ficheprofil', 'getVacanceEmploiAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (60, 'FICHE_POSTE#FichesMetiersCourt', '<p>Liste des fiches m&eacute;tiers et des libell&eacute;s de activit&eacute;s des m&eacute;tiers</p>', 'ficheposte', 'toStringFichesMetiersCourt');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (62, 'FICHE_POSTE#Cadre', '<p>Affichage du cadre du m&eacute;tier (de plus haut niveau) ! manque le lien vers le corps ! manque le lien vers la bap</p>', 'ficheposte', 'toStringCadre');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (65, 'FICHE_POSTE#SpecificiteSpecificites', '<p>Liste des sp&eacute;cificit&eacute;s pr&eacute;cis&eacute;es dans la bloc ''Sp&eacute;cificit&eacute;'' de la partie ''Sp&eacute;cificit&eacute; du poste'' de la fiche de poste</p>', 'ficheposte', 'toStringSpecificiteSpecificite');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (70, 'CAMPAGNE#annee', '<p>Ann&eacute;e de la campagne d''entretien professionnel</p>', 'campagne', 'getAnnee');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (73, 'FORMATION#Libelle', '<p>Retourne le libell&eacute; de la formation</p>', 'formation', 'toStringLibelle');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (74, 'AGENT#Denomination', '<p>Retourne la d&eacute;nomination de l''agent (Pr&eacute;nom1 NomUsuel)</p>', 'agent', 'getDenomination');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (75, 'FORMATION_INSTANCE#Periode', '<p>Retourne la p&eacute;riode de la session de formation</p>', 'instance', 'getPeriode');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (76, 'ENTRETIEN#Agent', '<p>Affiche la d&eacute;nomination de l''agent passant son entretien professionnel</p>', 'entretien', 'toStringAgent');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (77, 'ENTRETIEN#Responsable', '<p>Retourne la d&eacute;nomination du responsable de l''entretien professionnel</p>', 'entretien', 'toStringResponsable');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (78, 'FORMATION_INSTANCE#TypeInscription', '<p>Retourne le type d''inscription (&agrave; savoir manuelle ou libre)</p>', 'instance', 'toStringTypeInscription');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (79, 'FORMATION_INSTANCE#Libelle', '<p>Retourne le libell&eacute; de la formation attach&eacute; &agrave; l''instance donn&eacute;e</p>', 'instance', 'getInstanceLibelle');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (81, 'EMC2#Nom', '<p>Retourne le libellé de l''application</p>', 'UrlService', 'getUrlApp');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (82, 'EMC2#Date', '<p>Retourne la date au format jj/mm/aaaa</p>', 'EMC2', 'toStringDate');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (83, 'EMC2#DateTime', '<p>Retourne la date au format jj/mm/aaa H:m:s</p>', 'EMC2', 'toStringDateTime');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (86, 'FORMATION_INSTANCE#LienSession', '<p>Lien vers la page associ&eacute;e &agrave; la session de formation</p>', 'instance', 'toStringLienSession');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (87, 'FORMATION_INSTANCE#LienEmargements', '<p>Lien pour t&eacute;l&eacute;charger les listes d''&eacute;margement d''une session de formation.</p>', 'instance', 'toStringLienEmargements');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (96, 'METIER#Libelle', '<p>Retourne le libell&eacute; du m&eacute;tier</p>', 'metier', 'getLibelle');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (97, 'URL#FormationInstanceAfficher', '<p>Retourne l''URL pour accéder à l''affichage d''une instance de formation</p>', 'UrlService', 'getUrlFormationInstanceAfficher');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (66, 'FICHE_POSTE#SpecificiteEncadrement', null, 'ficheposte', 'toStringSpecificiteEncadrement');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (68, 'FICHE_POSTE#SpecificiteFormations', null, 'ficheposte', 'toStringSpecificiteFormations');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (64, 'FICHE_POSTE#SpecificiteMoyens', null, 'ficheposte', 'toStringSpecificiteMoyens');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (71, 'CAMPAGNE#debut', null, 'campagne', 'getDateDebutToString');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (69, 'PROFIL#DateDossier', null, 'ficheprofil', 'getDateDossierAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (72, 'CAMPAGNE#fin', null, 'campagne', 'getDateFinToString');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (98, 'FORMATION_INSCRIT#Complement', null, 'inscrit', 'getComplement');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (63, 'FICHE_POSTE#SpecificiteRelations', null, 'ficheposte', 'toStringSpecificiteRelations');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (27, 'PROFIL#RENUMERATION', null, 'ficheprofil', 'getRenumerationAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (26, 'PROFIL#CONTRAT', null, 'ficheprofil', 'getContratAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (25, 'PROFIL#NIVEAU', null, 'ficheprofil', 'getNiveauAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (38, 'FICHE_METIER#COMPETENCES_COMPORTEMENTALES', null, 'fichemetier', 'getCompetencesComportementales');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (36, 'FICHE_METIER#CONNAISSANCES', null, 'fichemetier', 'getConnaissances');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (29, 'FICHE_POSTE#LIBELLE', null, 'ficheposte', 'toStringFicheMetierPrincipal');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (28, 'PROFIL#DateAudition', null, 'ficheprofil', 'getDateAuditionAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (41, 'PARCOURS#FORMATIONS', null, 'parcours', 'getFormationsAffichage');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (37, 'FICHE_METIER#COMPETENCES_OPERATIONNELLES', null, 'fichemetier', 'getCompetencesOperationnelles');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (91, 'ENTRETIEN#responsable', null, 'entretien', 'toStringResponsable');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (90, 'AGENT#denomination', null, 'agent', 'getDenomination');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (99, 'CAMPAGNE#datecirculaire', null, 'campagne', 'getDateCirculaireToString');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (95, 'URL#EntretienAccepter', null, 'UrlService', 'getUrlEntretienAccepter');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (94, 'URL#EntretienRenseigner', null, 'UrlService', 'getUrlEntretienRenseigner');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (93, 'URL#App', null, 'UrlService', 'getUrlApp');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (80, 'FORMATION_INSTANCE#ListeInscritSecondaireTableau', null, 'instance', 'toStringListeInscritSecondaire');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (67, 'FICHE_POSTE#SpecificiteContraintes', null, 'ficheposte', 'toStringSpecificiteContraintes');
INSERT INTO unicaen_renderer_macro (id, code, description, variable_name, methode_name) VALUES (201, 'ENTRETIEN#ReponsablePrenom', null, 'entretien', 'toStringReponsablePrenom');

-- templates
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (63, 'STRUCTURE_UPDATE_INFOS', '<p>Courrier électronique envoyé périodiquement pour la mise à jour des informations à propos de la structure</p>', 'mail', 'Données connues sur la structure VAR[STRUCTURE#libelle] par l''application VAR[EMC2#appname]', e'<p>Bonjour,</p>
<p>Ce courrier électronique est un courrier rappelant les informations de responsabilité et de gestion de la structure  VAR[STRUCTURE#libelle_long] .</p>
<p>Liste des responsables : VAR[STRUCTURE#responsables]</p>
<p>Liste des gestionnaires : VAR[STRUCTURE#gestionnaires]</p>
<p>Vous avez reçu ce mail  car :</p>
<ul>
<li>vous êtes Gestionnaire de la structure;</li>
<li>vous êtes Responsable de la structure et celle-ci n''a pas de gestionnaire;</li>
</ul>
<p>Bonne journée,<br />L''application VAR[EMC2#appname]</p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (65, 'FORMATION_CONVOCATION', null, 'pdf', 'Convocation à la session de formation VAR[SESSION#libelle]', e'<p> </p>
<p> </p>
<p> </p>
<p> </p>
<h1 style="text-align: center;">Convocation à une action de formation</h1>
<p style="text-align: right;"> À Caen, le VAR[EMC2#date]</p>
<p>Bonjour <strong>VAR[AGENT#denomination]</strong>,</p>
<p> </p>
<p>Le bureau conseil carrière compétences vous informe que vous êtes retenu-e à la formation :</p>
<p style="text-align: center;"><strong>VAR[SESSION#libelle]<br /></strong></p>
<p>à laquelle vous êtes inscrit-e et se déroulera selon le calendrier ci-dessous :</p>
<p>VAR[SESSION#seances] </p>
<p>En cas d''impossibilité d''assister à tout ou partie de ce stage, vous êtes invité-e à vous connecter dans la partie "Formation"/"S''inscrire à une formation" de VAR[EMC2#appname], dans les meilleurs délais, afin de nous permettre de contacter une personne sur liste d''attente.</p>
<p style="text-align: justify;"> </p>
<p style="text-align: justify;">Le bureau conseil, carrière, compétences vous souhaite un stage fructueux.</p>
<p style="text-align: justify;"> </p>
<p style="text-align: justify;">La responsable du bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr </p>
<p style="text-align: left;"><em>P.S. : Cette convocation vaut ordre de mission</em></p>', 'table { border-collapse: collapse;} tr th {border-bottom:1px solid black;} td {text-align:center;}');
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (64, 'FORMATION_ATTESTATION', null, 'pdf', 'Attestation de formation de VAR[AGENT#denomination] à la formation VAR[SESSION#libelle]', e'<p> </p>
<p> </p>
<p> </p>
<p> </p>
<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p> </p>
<p style="text-align: right;">À Caen le VAR[EMC2#date]</p>
<p> </p>
<h1 style="text-align: center;">Attestation de stage</h1>
<p> </p>
<p>Le bureau conseil, carrière, compétences, certifie que le stagiaire :</p>
<p>VAR[AGENT#denomination] a suivi la formation : <strong>VAR[SESSION#libelle] </strong>qui s''est déroulée du VAR[SESSION#periode] (Durée : VAR[SESSION#duree])VAR[SESSION#lieu].</p>
<p> </p>
<p>L''agent a suivi VAR[INSCRIPTION#duree] de formation.</p>
<p> </p>
<p> </p>
<p style="text-align: left;">Le bureau conseil, carrière, compétences.</p>
<p><span dir="ltr" style="left: 70.8661px; top: 794.321px; font-size: 16.6667px; font-family: sans-serif; transform: scaleX(1);" role="presentation"> </span></p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (61, 'MAIL_VALIDATION_AGENT', null, 'mail', 'Validation de VAR[AGENT#Denomination] de sa fiche de poste', e'<p>Bonjour,</p>
<p>VAR[AGENT#Denomination] vient de valider sa fiche de poste.</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences<br />VAR[EMC2#Nom]</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (60, 'MAIL_VALIDATION_RESPONSABLE', '<p>Mail envoyé à l''agent·e après la validation d''une fiche de poste par le·la responsable de l''agent·e</p>', 'mail', 'Votre fiche de poste de poste vient d''être validée par votre responsable', e'<p>Université de Caen Normandie<br />Direction des Ressources Humaines<br /><br />Bonjour,<br /><br />Votre fiche de poste vient d''être validée par votre responsable.</p>
<p>Vous pouvez maintenant vous rendre dans EMC2 pour valider à votre tour celle-ci.<br />Vous retrouverez celle-ci à l''adresse suivante : VAR[URL#FichePosteAcceder]</p>
<p><br />Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences<br />VAR[EMC2#Nom]</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (57, 'CREF - Compte rendu d''entretien de formation', '<p>..</p>', 'pdf', 'Entretien_formation_VAR[CAMPAGNE#annee]_VAR[AGENT#NomUsage]_VAR[AGENT#Prenom].pdf', e'<h1>Annexe C9 bis - Compte rendu d''entretien de formation</h1>
<p><strong>Année : VAR[CAMPAGNE#annee]</strong></p>
<table style="width: 998.25px;">
<tbody>
<tr>
<th style="width: 482px; text-align: center;"><strong>AGENT</strong></th>
<th style="width: 465.25px; text-align: center;"><strong>SUPÉRIEUR·E HIÉRARCHIQUE DIRECT·E</strong></th>
</tr>
<tr>
<td style="width: 482px;">
<p>Nom d''usage : VAR[AGENT#NomUsage]</p>
<p>Nom de famille : VAR[AGENT#NomFamille]</p>
<p>Prénom : VAR[AGENT#Prenom]</p>
<p>Date de naissance: VAR[AGENT#DateNaissance]</p>
<p>Corps-grade : VAR[AGENT#CorpsGrade]<strong><br /></strong></p>
<p> </p>
</td>
<td style="width: 465.25px;">
<p>Nom d''usage : VAR[ENTRETIEN#ReponsableNomUsage]</p>
<p>Nom de famille : VAR[ENTRETIEN#ReponsableNomFamille]</p>
<p>Prénom : VAR[ENTRETIEN#ReponsablePrenom]</p>
<p>Corps-grade : VAR[ENTRETIEN#ReponsableCorpsGrade]</p>
<p>Intitulé de la fonction : VAR[ENTRETIEN#ReponsableIntitlePoste]VAR[ENTRETIEN#CREP_Champ|CREP;responsable_date]</p>
<p>Structure : VAR[ENTRETIEN#ReponsableStructure]</p>
</td>
</tr>
</tbody>
</table>
<p> </p>
<table style="width: 1002px;">
<tbody>
<tr>
<td style="width: 346.797px;">Date de l''entretien de formation</td>
<td style="width: 659.203px;">VAR[ENTRETIEN#date]</td>
</tr>
<tr>
<td style="width: 346.797px;">Date du précédent entretien de formation</td>
<td style="width: 659.203px;">VAR[ENTRETIEN#CREF_Champ|CREF;precedent]</td>
</tr>
<tr>
<td style="width: 346.797px;">Solde des droits CPF au 1er janvier</td>
<td style="width: 659.203px;">VAR[ENTRETIEN#CREF_Champ|CREF;CPF_solde]</td>
</tr>
<tr>
<td style="width: 346.797px;">L''agent envisage-t''il de mobiliser son CPF cette annee ?</td>
<td style="width: 659.203px;">VAR[ENTRETIEN#CREF_Champ|CREF;CPF_mobilisation] </td>
</tr>
</tbody>
</table>
<h2>1. Description du poste occupé par l''agent</h2>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 722px;">
<p>Structure : VAR[AGENT#AffectationStructure]</p>
<p>Intitulé du poste : VAR[AGENT#IntitulePoste]VAR[ENTRETIEN#CREP_Champ|CREP;agent_poste]</p>
<p>Date d''affectation : VAR[ENTRETIEN#CREP_Champ|CREP;affectation_date]</p>
<p>Emploi type (cf. REME ou REFERENS) : VAR[AGENT#EmploiType] VAR[ENTRETIEN#CREP_Champ|CREP;emploi-type]</p>
<p>Positionnement du poste dans le structure : VAR[AGENT#AffectationStructureFine]</p>
<p>Quotité travaillée : VAR[AGENT#Quotite]</p>
<p>Quotité d''affectation : VAR[AGENT#QuotiteAffectation]</p>
</td>
</tr>
<tr>
<td style="width: 722px;">
<p>Missions du postes :<br />VAR[AGENT#Missions]</p>
<p> VAR[ENTRETIEN#CREP_Champ|CREP;missions]</p>
</td>
</tr>
<tr>
<td style="width: 722px;">
<p>Le cas échéant, fonctions d''encadrement ou de conduite de projet :</p>
<ul>
<li>l''agent assume-t''il des fonctions de conduite de projet ? VAR[ENTRETIEN#CREP_projet]</li>
<li>l''agent  assume-t''il des fonctions d''encadrements ? VAR[ENTRETIEN#CREP_encadrement]<br />Si oui préciser le nombre d''agents et leur répartition par catégorie : VAR[ENTRETIEN#CREP_encadrementA] A - VAR[ENTRETIEN#CREP_encadrementB] B - VAR[ENTRETIEN#CREP_encadrementC] C</li>
</ul>
</td>
</tr>
</tbody>
</table>
<h3>Activités de transfert de compétences ou d’accompagnement des agents</h3>
<table style="width: 726px;">
<tbody>
<tr>
<td style="width: 726px;">VAR[ENTRETIEN#CREF_Champ|CREF;1.1]</td>
</tr>
</tbody>
</table>
<h3>Formation dispensé par l''agent</h3>
<table style="width: 730px;">
<tbody>
<tr>
<td style="width: 730px;">VAR[ENTRETIEN#CREF_Champs|CREF;1.2]</td>
</tr>
</tbody>
</table>
<h2>2. Bilan des formations suivies sur la période écoulée</h2>
<p>Session réalisée du 1er septembre au 31 aout de l''année VAR[CAMPAGNE#annee]</p>
<table style="width: 726px;">
<tbody>
<tr>
<td style="width: 716px;">
<p>VAR[ENTRETIEN#CREF_Champs|CREF;2]</p>
<p>VAR[ENTRETIEN#CREF_Champs|CREF;AutresFormations]</p>
</td>
</tr>
</tbody>
</table>
<h2>3. Formations demandées sur la période écoulée et non suivies</h2>
<p>(Formation demandées lors de l''entretien précédent)</p>
<table style="width: 737px;">
<tbody>
<tr>
<td style="width: 737px;">VAR[ENTRETIEN#CREF_Champs|CREF;3]</td>
</tr>
</tbody>
</table>
<h1>FORMATIONS DEMANDÉES POUR LA NOUVELLE PÉRIODE</h1>
<h2>4. Formation continue</h2>
<p><strong>Type 1 : formations d''adaptation immédiate au poste de travail</strong><br />Stage d''adaptation à l''emploi, de prise de poste après une mutation ou une promotion</p>
<p><strong>Type 2 : formations à l''évolution des métiers ou des postes de travail</strong><br />Approfondir ses compétences techniques, actualiser ses savoir-faire professionnels, acquérir des fondamentaux ou remettre à niveau ses connaissances pour se préparer à des changements fortement probables, induits par la mise en place d''une réforme, d''un nouveau système d''information ou de nouvelles techniques.</p>
<p><strong>Type 3 : formations d''acquisition de qualifications nouvelles</strong><br />Favoriser sa culture professionnelle ou son niveau d''expertise, approfondir ses connaissances dans un domaine qui ne relève pas de son activité actuelle, pour se préparer à de nouvelles fonctions, surmonter des difficultés sur son poste actuel.</p>
<table>
<tbody>
<tr>
<td>VAR[ENTRETIEN#CREF_Champs|CREF;4.1]</td>
</tr>
</tbody>
</table>
<p><strong>Actions de formations demandées par l''agent et recueillant un avis défavorable du supérieur hiérarchique direct</strong></p>
<table>
<tbody>
<tr>
<td>VAR[ENTRETIEN#CREF_Champs|CREF;4.2]</td>
</tr>
</tbody>
</table>
<p>N.B. : l''avis défavorable émis par le supérieur hiérarchique direct conduisant l''entretien ne préjuge pas de la suite donnée à la demande de formation</p>
<h2>5. Formation de préparation à un concours ou examen professionnel</h2>
<p>Pour acquérir les bases et connaissances générales utiles à un concours, dans le cadre de ses perspectives professionnelles pour préparer un changement d''orientation pouvant impliquer le départ de son ministère ou de la fonction publique</p>
<table style="width: 723px;">
<tbody>
<tr>
<td style="width: 713px;">VAR[ENTRETIEN#CREF_Champs|CREF;5]</td>
</tr>
</tbody>
</table>
<h2>6. Formations pour construire un projet personnel à caractère professionnel</h2>
<p><strong>VAE : Validation des acquis de l''expérience<br /></strong>Pour obtenir un diplôme, d''un titre ou d''une certification inscrite au répertoire national des certifications professionnelles</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;VAE]</td>
</tr>
</tbody>
</table>
<p><strong>Bilan de compétences</strong><br />Pour permettre une mobilité fonctionnelle ou géographique</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;bilan]</td>
</tr>
</tbody>
</table>
<p><strong>Période de professionnalisation<br /></strong>Pour prévenir des risques d''inadaptation à l''évolution des méthodes et techniques, pour favoriser l''accès à des emplois exigeant des compétences nouvelles ou qualifications différentes, pour accéder à un autre corps ou cadre d''emplois, pour les agents qui reprennent leur activité professionnelle après un congé maternité ou parental.</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;periode]</td>
</tr>
</tbody>
</table>
<p><strong>Congé de formation professionnelle</strong><br />Pour suivre une formation</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;conge]</td>
</tr>
</tbody>
</table>
<p><strong>Entretien de carrière<br /></strong>Pour évaluer son parcours et envisager des possibilités d''évolution professionnelle à 2~3 ans</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;ecarriere]</td>
</tr>
</tbody>
</table>
<p><strong>Bilan de carrière<br /></strong>Pour renouveler ses perspectives professionnelles à 4~5 ans ou préparer un projet de seconde carrière</p>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 712px;">VAR[ENTRETIEN#CREF_Champ|CREF;6;bcarriere]</td>
</tr>
</tbody>
</table>
<h2>7. Signature du supérieure hiérarchique direct</h2>
<table style="width: 648px;">
<tbody>
<tr>
<td style="width: 648px;"> Date de l''entretien : VAR[ENTRETIEN#date]
<p>Date de transmission du compte rendu : </p>
<p>Nom, qualité et signature du responsable hiérarchique :</p>
<p> </p>
<p>VAR[ENTRETIEN#VALIDATION_SUPERIEUR]</p>
</td>
</tr>
</tbody>
</table>
<h2>8. Signature et observation de l''agent sur son entretien de formation</h2>
<table style="width: 648px;">
<tbody>
<tr>
<td style="width: 648px;">Date de l''entretien : VAR[ENTRETIEN#date]
<p>Date de transmission du compte rendu : </p>
<p>Nom, qualité et signature du responsable hiérarchique :</p>
<p> </p>
<p> </p>
<p>VAR[ENTRETIEN#VALIDATION_AGENT]</p>
</td>
</tr>
<tr>
<td style="width: 648px;">
<p>Observations :</p>
<p> </p>
<p> </p>
<p> </p>
</td>
</tr>
</tbody>
</table>
<h2>9. Réglementation</h2>
<p>Décret n°2007-1470 du 15 octobre 2007 relatif à la formation professionnelle tout au long de la vie des fonctionnaires de l''État</p>
<p>Article 5 :</p>
<ul>
<li>Le compte rendu de l''entretien de formation est établi sous la responsabilité du supérieur hiérarchique.</li>
<li>Les objectifs de formation proposés pour l''agent y sont inscrits.</li>
<li>Le fonctionnaire en reçoit communication et peut y ajouter ses observations.</li>
<li>Ce compte rendu ainsi qu''une fiche retraçant les actions de formation auxquelles le fonctionnaire a participé sont versés à son dossier.</li>
<li>Les actions conduites en tant que formateur y figurent également.</li>
<li>Le fonctionnaire est informé par son supérieur hiérarchique des suites données à son entretien de formation.</li>
<li>Les refus opposés aux demandes de formation présentées à l''occasion de l''entretien de formation sont motivés.</li>
</ul>', 'body {font-size:9pt;} h1 {font-size: 14pt; color: #154360;} h2 {font-size:12pt; color:#154360;} h3 {font-size: 11pt; color: #154360;}table {border:1px solid black;border-collapse:collapse; width: 100%;} td {border:1px solid black;} th {border:1px solid black; color:#154360;}');
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (62, 'PARCOURS_ENTREE_TEXTE', '<p>Texte descriptif du parcours d''entrée</p>', 'texte', '...', e'<p>Ceci est le texte d''introduction au parcours d''entrée à la formation</p>
<p> </p>
<p>&gt;&gt; ICI LIEN VERS MOODLE ET LE PARCOURS DE L''AGENT</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (66, 'FORMATION_DEMANDE_EXTERNE_TOTALEMENT_VALIDEE', '<p>Mail envoyé vers le CCC lorsque d''une demande extérieur est totalement validée</p>', 'mail', 'La demande stage externe de VAR[AGENT#denomination] est maintenant totalement validée', e'<p>Bonjour,</p>
<p>La demande de stage externe suivante est maintenant totalement validée.</p>
<table style="width: 450px; height: 116px;">
<tbody>
<tr>
<td style="width: 197.717px;">Agent</td>
<td style="width: 234.283px;">VAR[AGENT#denomination]</td>
</tr>
<tr>
<td style="width: 197.717px;">Libellé du stage</td>
<td style="width: 234.283px;">VAR[DEMANDE#libelle]</td>
</tr>
<tr>
<td style="width: 197.717px;">Organisme formateur</td>
<td style="width: 234.283px;">VAR[DEMANDE#organisme]</td>
</tr>
<tr>
<td style="width: 197.717px;">Date de début</td>
<td style="width: 234.283px;">VAR[DEMANDE#debut]</td>
</tr>
<tr>
<td style="width: 197.717px;">Date de fin</td>
<td style="width: 234.283px;">VAR[DEMANDE#fin]</td>
</tr>
</tbody>
</table>
<p>L''application VAR[EMC2#appname]</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (67, 'FORMATION_DEMANDE_EXTERNE_VALIDATION_AGENT', '<p>Courrier envoyé vers le responsable de l''agent lorsque celui-ci valide un demande de formation externe.</p>', 'mail', 'Demande de validation d''une demande de formation externe de VAR[AGENT#denomination]', e'<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p> </p>
<p>Bonjour,</p>
<p> </p>
<p>VAR[AGENT#denomination] vient d''effectuer une demande de formation externe (hors plan de formation).<br />Vous pouvez désormais en tant que Responsable de VAR[AGENT#denomination] valider (ou refuser) cette demande.</p>
<p> </p>
<p>Informations à propos de la demande :</p>
<table style="width: 572px; height: 89px;">
<tbody>
<tr>
<td style="width: 184.217px;"><strong>Intitulé de la formation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#libelle]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Organisme formateur :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#organisme]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Lieu :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#lieu]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Début :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#debut]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Fin :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#fin]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Motivation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#motivation]</td>
</tr>
</tbody>
</table>
<p> </p>
<p>Vous pouvez valider (ou refuser) celle-ci en vous connectant à EMC2 VAR[URL#App], dans l''onglet "Demande de formation de votre structure".</p>
<p> </p>
<p>Le bureau conseil carrière compétences,</p>
<p>drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (68, 'FORMATION_DEMANDE_EXTERNE_VALIDATION_DRH', '<p>Courrier envoyé lors de la validation par la drh d''une demande externe (Agent et Responsable)</p>', 'mail', 'Validation de la demande de formation externe de VAR[AGENT#denomination] par le bureau gérant les formations', e'<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p> </p>
<p>Bonjour,</p>
<p>Le demande suivante de formation externe (hors plan de formation) pour VAR[AGENT#denomination] est <strong>validée</strong>.</p>
<table style="width: 572px; height: 89px;">
<tbody>
<tr>
<td style="width: 184.217px;"><strong>Intitulé de la formation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#libelle]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Organisme formateur :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#organisme]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Lieu :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#lieu]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Début :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#debut]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Fin :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#fin]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Motivation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#motivation]</td>
</tr>
</tbody>
</table>
<p> </p>
<p>VAR[AGENT#denomination] est invité-e à procéder à son inscription auprès de l''organisme sollicité.</p>
<p> </p>
<p>Le bureau conseil carrière compétences est à votre disposition et se charge des démarches administratives.</p>
<p> </p>
<p>Le bureau conseil carrière compétences,</p>
<p>drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (69, 'FORMATION_DEMANDE_EXTERNE_VALIDATION_REFUS', '<p>Mail envoyé lors du refus par le responsable ou la DRH</p>', 'mail', 'Refus de la demande de formation externe de VAR[AGENT#denomination]', e'<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p> </p>
<p>Bonjour,</p>
<p>Le demande suivante de formation externe (hors plan de formation) pour VAR[AGENT#denomination] vient d''être refusée.</p>
<table style="width: 572px; height: 89px;">
<tbody>
<tr>
<td style="width: 184.217px;"><strong>Intitulé de la formation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#libelle]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Organisme formateur :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#libelle]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Lieu :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#lieu]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Début :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#debut]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Fin :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#fin]</td>
</tr>
</tbody>
</table>
<p> Voici le motif du refus : <br />VAR[DEMANDE#refus]</p>
<p> Le bureau conseil carrière compétences,</p>
<p>drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (70, 'FORMATION_DEMANDE_EXTERNE_VALIDATION_RESP_AGENT', '<p>Courrier envoyé vers la DRH lorsque celui-ci valide une demande de formation externe est validé par un responsable.</p>', 'mail', 'Validation de votre demande de formation externe de votre responsable', e'<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN 5</p>
<p>A Caen, le VAR[EMC2#date]</p>
<p> </p>
<p>Bonjour,</p>
<p>Votre responsable vient de valider votre demande de formation externe (hors plan de formation), toutefois votre demande reste soumise à la validation du bureau conseil carrière compétences.</p>
<p><br />Informations à propos de la demande :</p>
<table style="width: 572px; height: 89px;">
<tbody>
<tr style="height: 35px;">
<td style="width: 184.217px; height: 35px;"><strong>Intitulé de la formation :</strong></td>
<td style="width: 368.783px; height: 35px;"> VAR[DEMANDE#libelle]</td>
</tr>
<tr style="height: 17px;">
<td style="width: 184.217px; height: 17px;"><strong>Organisme formateur :</strong></td>
<td style="width: 368.783px; height: 17px;"> VAR[DEMANDE#organisme]</td>
</tr>
<tr style="height: 17px;">
<td style="width: 184.217px; height: 17px;"><strong>Lieu :</strong></td>
<td style="width: 368.783px; height: 17px;"> VAR[DEMANDE#lieu]</td>
</tr>
<tr style="height: 17px;">
<td style="width: 184.217px; height: 17px;"><strong>Début :</strong></td>
<td style="width: 368.783px; height: 17px;"> VAR[DEMANDE#debut]</td>
</tr>
<tr style="height: 20.6px;">
<td style="width: 184.217px; height: 20.6px;"><strong>Fin :</strong></td>
<td style="width: 368.783px; height: 20.6px;"> VAR[DEMANDE#fin]</td>
</tr>
<tr style="height: 17px;">
<td style="width: 184.217px; height: 17px;"><strong>Motivation :</strong></td>
<td style="width: 368.783px; height: 17px;"> VAR[DEMANDE#motivation]</td>
</tr>
</tbody>
</table>
<p>Vous serez prochainement informé-e par courrier électronique de la suite réservée à votre demande.</p>
<p> </p>
<p>Le bureau conseil carrière compétences,</p>
<p>drh.formation@unicaen.fr</p>
<p> </p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (71, 'FORMATION_DEMANDE_EXTERNE_VALIDATION_RESP_DRH', '<p>Courrier envoyé vers la DRH lorsque celui-ci valide une demande de formation externe validée par un responsable.</p>', 'mail', 'Validation d''une demande de formation externe de VAR[AGENT#denomination]', e'<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p> </p>
<p>Bonjour,</p>
<p>Le responsable de l''agent VAR[AGENT#denomination] vient de valider une demande de formation externe (hors plan de formation).<br /><br /></p>
<p>La responsable du bureau conseil carrière compétences est chargée de procéder à la validation définitive de cette demande.</p>
<p>Informations à propos de la demande :</p>
<table style="width: 572px; height: 89px;">
<tbody>
<tr>
<td style="width: 184.217px;"><strong>Intitulé de la formation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#libelle]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Organisme formateur :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#organisme]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Lieu :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#lieu]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Début :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#debut]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Fin :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#fin]</td>
</tr>
<tr>
<td style="width: 184.217px;"><strong>Motivation :</strong></td>
<td style="width: 368.783px;"> VAR[DEMANDE#motivation]</td>
</tr>
</tbody>
</table>
<p> Vous pouvez valider celle-ci en vous connectant à VAR[URL#App] &gt; Formations externes</p>
<p> </p>
<p><strong>EMC2</strong></p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (73, 'FORMATION_INSCRIPTION_1_AGENT', '<p>Demande d''inscription à une formation (Agent &gt; Responsable)</p>', 'mail', 'Demande d''inscription de VAR[AGENT#denomination] à la formation VAR[SESSION#libelle] du VAR[SESSION#periode]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Université de Caen Normandie<br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">                                                                                                                                                            A Caen, le VAR[EMC2#date]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;">VAR[AGENT#denomination]</span> vient de procéder à une demande d''inscription pour la formation <span style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;">VAR[SESSION#libelle] </span>du VAR[SESSION#periode].</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Merci de vous connecter à l''application EMC2 (VAR[URL#App]) afin d''accepter ou de refuser sa demande.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">La liste des "demandes en attente" est accessible dans l''onglet ''Demande de formation'' dans votre structure.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr</p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (74, 'FORMATION_INSCRIPTION_2_RESPONSABLE_REFUS', '<p>Refus de la demande d''inscription par le responsable (Responsable &gt; Agent)</p>', 'mail', 'Refus de la demande d''inscription à la formation VAR[SESSION#libelle] du VAR[SESSION#periode] par votre responsable', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p><br />Bonjour,<br /><br />Votre responsable hiérarchique vient de refuser votre demande d''inscription à la formation VAR[SESSION#libelle] du VAR[SESSION#periode].<br /><br />Vous trouverez ci-après le motif du refus de votre inscription :</p>
<table style="height: 30px;" width="648">
<tbody>
<tr>
<td style="width: 638px;">VAR[INSCRIPTION#complement]</td>
</tr>
</tbody>
</table>
<p><br /><br />La responsable du bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr</p>', 'table {border:1px solid black;}');
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (75, 'FORMATION_INSCRIPTION_2_RESPONSABLE_VALIDATION', '<p>Demande d''inscription à une formation (Responsable &gt; DRH)</p>', 'mail', 'Validation par le responsable de structure de la demande de formation de VAR[AGENT#denomination] à la formation VAR[SESSION#libelle] du VAR[SESSION#periode]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]<br /><br /> <br /><br />Bonjour,<br /><br />La demande de formation de VAR[AGENT#denomination] à VAR[SESSION#libelle] du VAR[SESSION#periode] vient d''être validée par le responsable hiérarchique.<br />Vous pouvez maintenant valider ou refuser cette demande de formation via l''application EMC2 : VAR[URL#FormationInstanceAfficher]<br /><br /></p>
<p>L''application VAR[EMC2#appname]</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (76, 'FORMATION_INSCRIPTION_3_DRH_REFUS', '<p>Refus de la demande d''inscription par la DRH (DRH &gt; Agent)</p>', 'mail', 'Refus de la demande d''inscription à la formation VAR[FORMATION_INSTANCE#Libelle] du VAR[FORMATION_INSTANCE#Periode] par la direction des ressources humaines.', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p><br />Bonjour,<br /><br />Le bureau conseil carrière compétences vient de refuser votre demande d''inscription à la formation VAR[SESSION#libelle] du VAR[SESSION#periode].<br />Vous trouverez ci-après le motif du refus de votre inscription :</p>
<table style="height: 30px;" width="648">
<tbody>
<tr>
<td style="width: 638px;">VAR[INSCRIPTION#complement]</td>
</tr>
</tbody>
</table>
<p> </p>
<p>Le bureau conseil carrière compétences se tient à votre disposition pour toute information complémentaire.</p>
<p><br />La responsable du bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr</p>
<p> </p>', 'table {border:1px solid black;}');
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (77, 'FORMATION_INSCRIPTION_3_DRH_VALIDATION', '<p>Demande d''inscription à une formation (DRH &gt; Agent)</p>', 'mail', 'Validation de la demande de formation de VAR[AGENT#denomination] à la formation VAR[SESSION#libelle] du VAR[SESSION#periode]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]<br /><br /> <br /><br />Bonjour,<br /><br /><br />La demande de formation de VAR[AGENT#denomination] à VAR[SESSION#libelle] du VAR[SESSION#periode] vient d''être validée par le bureau conseil, carrière, compétences.<br />Vous recevrez une notification quelques jours avant la formation et pourrez ainsi télécharger si besoin votre convocation directement sur l''application EMC2 (VAR[URL#App])<br /><br />Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (37, 'NOTIFICATION_RAPPEL_ENTRETIEN', '<p>Courrier envoyé à l''agent lui rappelant la date de son entretien professionnel</p>', 'mail', 'Rappel de votre entretien professionnel du VAR[ENTRETIEN#date] pour la campagne VAR[CAMPAGNE#annee]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p><span style="text-decoration: underline;">Objet :</span> rappel de votre entretien professionnel du VAR[ENTRETIEN#date] pour la campagne VAR[CAMPAGNE#annee]</p>
<p> </p>
<p>Bonjour,</p>
<p>Vous avez été informé<span style="color: #4d5156; font-family: arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;">·</span>e par votre supérieure hiérarchique direct des éléments concernant votre entretien professionnel :</p>
<table style="width: 443px;">
<tbody>
<tr>
<td style="width: 200.55px;">Date</td>
<td style="width: 246.45px;">VAR[ENTRETIEN#date]</td>
</tr>
<tr>
<td style="width: 200.55px;">Lieu</td>
<td style="width: 246.45px;">VAR[ENTRETIEN#lieu]</td>
</tr>
<tr>
<td style="width: 200.55px;">Responsable</td>
<td style="width: 246.45px;">VAR[ENTRETIEN#responsable]</td>
</tr>
</tbody>
</table>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences<br /><br /><br /></p>', 'table { border : 1px; }');
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (78, 'FORMATION_INSCRIPTION_PREVENTION', null, 'mail', 'Validation de l''inscription de VAR[AGENT#denomination] à une formation de prévention', e'<p>Bonjour,</p>
<p>VAR[AGENT#denomination] vient classé en liste principale pour une formation de prévention</p>
<table style="width: 573px;">
<tbody>
<tr>
<td style="width: 241.65px;">Agent</td>
<td style="width: 335.35px;">VAR[AGENT#denomination]</td>
</tr>
<tr>
<td style="width: 241.65px;">Date de naissance</td>
<td style="width: 335.35px;">VAR[AGENT#datenaissance]</td>
</tr>
<tr>
<td style="width: 241.65px;">Libellé de la formation</td>
<td style="width: 335.35px;">VAR[SESSION#libelle]</td>
</tr>
<tr>
<td style="width: 241.65px;">Date de la session</td>
<td style="width: 335.35px;">VAR[SESSION#periode]</td>
</tr>
</tbody>
</table>
<p> </p>
<p>Bonne journée,<br />L''application VAR[EMC2#appname]</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (79, 'FORMATION_NOTIFICATION_ABONNEMENT_FORMATION', '<p>Mail envoyé aux agents abonnés lors d''une nouvelle session</p>', 'mail', 'Un session de formation vient être ouverte pour l''action de formation "VAR[FORMATION_INSTANCE#Libelle]"', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br />DRH Bureau de la formation, conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">A Caen, le VAR[EMC2#date]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Une nouvelle session de formation (<em>VAR[FORMATION_INSTANCE#InstanceId]</em>) vient d''ouvrir pour la formation "<strong>VAR[FORMATION_INSTANCE#Libelle]</strong>".<br />Cette session se déroulera sur la période du VAR[FORMATION_INSTANCE#Periode] aux dates suivantes : VAR[FORMATION_INSTANCE#JourneeTableau]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">La formation sera assurée par : VAR[FORMATION_INSTANCE#FormateursTableau]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Pour vous inscrire à cette formation, connectez vous à l''application EMC2 (VAR[URL#App]) dans l''onglet ''Formations''.<br /><br /></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Le bureau conseil carrière compétences,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">drh.formation@unicaen.fr</p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (80, 'FORMATION_NOTIFICATION_NOUVELLES_SESSIONS', '<p>Mail envoyé périodiquement pour annoncer les nouvelles sessions</p>', 'mail', 'Nouvelles sessions de formation', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p> </p>
<p>Bonjour,<br /><br />Vous trouverez la liste des nouvelles sessions de formation ouvertes à l''inscription : ###A REMPLACER###<br /><br />Si vous souhaitez vous inscrire à une de ces sessions, connectez vous sur EMC2 : VAR[URL#App].<br /><br /><br /></p>
<p>Le bureau conseil carrière compétences,</p>
<p>drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (81, 'FORMATION_NOTIFICATION_SESSION_IMMINENTE', '<p>Mail de rappel envoyé aux agents inscrits à une session de formation quelques jours avant la celle-ci</p>', 'mail', 'Rappel de votre inscription à la formation VAR[FORMATION_INSTANCE#Libelle] du VAR[FORMATION_INSTANCE#Periode]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p>                                                                                                                                                                          <br /> <br /><br />Bonjour,<br /><br /> <br />Ce mail automatique est un rappel de votre inscription à la formation suivante :<br /><br />Libellé    VAR[FORMATION_INSTANCE#Libelle]<br />Période    VAR[FORMATION_INSTANCE#Periode]<br />Lieu    VAR[FORMATION_INSTANCE#Lieu]</p>
<p>En cas d''empêchement, merci de vous désinscrire via l''application EMC2 : VAR[URL#App].<br /><br /> <br /><br />Le bureau conseil, carrière, compétences vous souhaite une session de formation enrichissante.<br /><br /> <br /><br />La responsable du bureau conseil, carrière, compétences.<br /><br />drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (11, 'ENTRETIEN_CONVOCATION_ENVOI', '<p>Mail de convocation &agrave; une entretien professionnel d''un agent</p>', 'mail', 'Convocation à votre entretien professionnel pour la campagne VAR[CAMPAGNE#annee]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong><span style="font-size: 9.0pt; font-family: ''Calibri'',sans-serif; mso-bidi-font-family: ''Times New Roman'';">objet :    </span></strong><strong><span style="font-size: 9.0pt; font-family: ''Calibri'',sans-serif; mso-bidi-font-family: Arial;">Convocation à votre entretien professionnel VAR[CAMPAGNE#annee].</span></strong></p>
<p class="MsoNormal" style="mso-margin-top-alt: auto; margin-bottom: .0001pt; text-align: justify; line-height: normal; tab-stops: 35.45pt;"><span style="font-size: 9.0pt; font-family: ''Calibri Light'',sans-serif; mso-ascii-theme-font: major-latin; mso-hansi-theme-font: major-latin; mso-bidi-theme-font: major-latin;">Réf. :       </span><span style="font-size: 9.0pt; font-family: ''Calibri'',sans-serif; mso-bidi-font-family: Arial;">- </span><span style="font-size: 8.0pt; mso-bidi-font-size: 9.0pt; font-family: ''Calibri Light'',sans-serif; mso-ascii-theme-font: major-latin; mso-hansi-theme-font: major-latin; mso-bidi-theme-font: major-latin;">Loi n°84-16 du 11 janvier 1984 modifiée portant dispositions statutaires à la fonction publique d’Etat</span></p>
<p class="MsoNormal" style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 35.45pt;"><span style="font-size: 8.0pt; mso-bidi-font-size: 9.0pt; font-family: ''Calibri Light'',sans-serif; mso-ascii-theme-font: major-latin; mso-hansi-theme-font: major-latin; mso-bidi-theme-font: major-latin;">- Décret n°2010-888 du 28/07/2010 modifié relatif aux conditions générales d’appréciation de la valeur professionnelle des fonctionnaires de l’Etat</span></p>
<p class="MsoNormal" style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 35.45pt;"><span style="font-size: 8.0pt; mso-bidi-font-size: 9.0pt; font-family: ''Calibri Light'',sans-serif; mso-ascii-theme-font: major-latin; mso-hansi-theme-font: major-latin; mso-bidi-theme-font: major-latin;">- Décret n°2011-2041 du 29 décembre 2011 modifiant le décret n° 2010-888 du 28 juillet 2010</span></p>
<p class="MsoNormal" style="text-align: justify; text-indent: -.1pt; line-height: normal; margin: 0cm 0cm .0001pt 35.45pt;"><span style="font-size: 8.0pt; mso-bidi-font-size: 9.0pt; font-family: ''Calibri Light'',sans-serif; mso-ascii-theme-font: major-latin; mso-hansi-theme-font: major-latin; mso-bidi-theme-font: major-latin;">- Arrêté du 18 mars 2013 relatif aux modalités d’application à certains fonctionnaires relevant des ministres chargés de l’éducation nationale et de l’enseignement supérieur du décret n°2010-888 du 28 juillet 2010 relatif aux conditions générales de l’appréciation de la valeur professionnelle des fonctionnaires de l’Etat</span></p>
<p class="MsoNormal" style="text-align: justify; line-height: normal; margin: 0cm 0cm .0001pt 35.45pt;"><span style="font-size: 8.0pt; mso-bidi-font-size: 9.0pt; font-family: ''Calibri Light'',sans-serif; mso-ascii-theme-font: major-latin; mso-hansi-theme-font: major-latin; mso-bidi-theme-font: major-latin;">- Circulaire interne du 25 mars 2019</span></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Le décret n°2011-2041 généralise le dispositif des entretiens professionnels. La circulaire du Président de l''Université signée du VAR[CAMPAGNE#datecirculaire], vous informe des directives de la campagne pour les personnels de l''AENES, de l''ITRF et des bibliothèques pour l''année VAR[CAMPAGNE#annee] ainsi que pour les agents non titulaires recrutés par contrat à durée déterminée de plus d''un an comme le prévoit le décret n°2014-364.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Votre responsable hiérarchique vous recevra pour réaliser votre entretien professionnel<strong> le VAR[ENTRETIEN#date] à VAR[ENTRETIEN#lieu]</strong>.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Votre responsable hiérarchique vous invite à préparer votre entretien en consultant sous le lien suivant les documents : http://intranet.unicaen.fr/services-/ressources-humaines/gestion-des-personnels/entretien-professionnel-540109.kjsp?RH=1574253529391</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Si la date ne vous convient pas veuillez vous adresser à votre responsable d''entretien professionnel ou à votre responsable de structure.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Merci d''accuser réception de cette convocation en cliquant sur le lien suivant : VAR[URL#EntretienAccepter]<br />En cas d’empêchement, veuillez contacter votre supérieur·e hiérarchique direct·e.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (82, 'FORMATION_RAPPORT_CLOTURE_AUTOMATIQUE', '<p>Mail envoyé lors de clôture automatique des inscriptions ( &gt; DRH)</p>', 'mail', 'Rapport de clôture automatique des inscriptions à une session de formation', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p> </p>
<p>Bonjour,<br /><br />Voici la liste des sessions de formations dont les inscriptions ont été automatiquement clôturées par EMC2 :<br />###A REMPLACER###<br /><br /><br />EMC2</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (83, 'FORMATION_RAPPORT_CONVOCATION_AUTOMATIQUE', '<p>Mail envoyé lors de clôture automatique des inscriptions ( &gt; DRH)</p>', 'mail', 'Rapport de clôture automatique des inscriptions à une session de formation', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN CEDEX 5</p>
<p>À Caen, le VAR[EMC2#date]</p>
<p>Bonjour,<br /><br />Voici la liste des sessions de formation dont les convocations ont été automatiquement envoyées par EMC2 :<br />###A REMPLACER###<br /><br /><br />EMC2</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (84, 'FORMATION_SESSION_ANNULEE', '<p>Mail envoyé aux inscrits d''une session venant d''être annulée</p>', 'mail', 'La session de formation VAR[SESSION#libelle] du VAR[SESSION#periode] est annulée', e'<p><strong>Université de Caen Normandie</strong><br /><strong>DRH - Bureau conseil, carrière, compétences</strong><br />Esplanade de la Paix<br />14032 CAEN                                                                                                                                                                                                 </p>
<p>A Caen, le VAR[EMC2#date]</p>
<p> </p>
<p>Bonjour VAR[AGENT#denomination],</p>
<p>Nous vous informons que la session de formation VAR[SESSION#libelle] du VAR[SESSION#periode] pour laquelle vous étiez inscrit, est annulée.</p>
<p>Si vous souhaitez vous inscrire de nouveau sur cette même formation, vous serez alors notifié dès la réouverture d''une prochaine session.</p>
<p> </p>
<p>Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (85, 'FORMATION_SESSION_CONVOCATION', '<p>Mail de convocation envoyé aux agents</p>', 'mail', 'La session de formation VAR[SESSION#libelle] du VAR[SESSION#periode] va bientôt commencer', '<p><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN                                                                                                                       <br /><br />A Caen, le VAR[EMC2#date]<br /><br /> <br />Bonjour VAR[AGENT#denomination],<br /> <br /><br />La session de formation VAR[SESSION#libelle] (VAR[SESSION#identification]) du VAR[SESSION#periode] à laquelle vous êtes inscrit va débuter prochainement.<br /><br /><br />Pour rappel, cette formation se déroulera selon le calendrier suivant :<br />VAR[SESSION#seances]<br /><br />Vous pouvez retrouver sur l''application EMC2 (VAR[URL#App]) votre convocation qui vaut ordre de mission.<br />N''imprimez celle-ci que si nécessaire.<br /><br /> <br />Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr<br /><br /> P.S.: Vous pouvez retrouver les plans d’accès et des campus sur le site de l''Université https://www.unicaen.fr/universite/decouvrir/territoire/caen</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (86, 'FORMATION_SESSION_DEMANDE_RETOUR', e'<p>Mail demandant aux agents ayant suivi la formation de remplir le formulaire</p>
<p> </p>', 'mail', 'Demande de saisie des formulaires de retour de la formation VAR[SESSION#libelle] du VAR[SESSION#periode]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN 5</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">À Caen, le VAR[EMC2#date]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour VAR[AGENT#denomination],</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Vous venez de participer à la formation VAR[SESSION#libelle] du VAR[SESSION#periode]. Afin d''obtenir votre attestation de présence, merci de compléter le questionnaire de satisfaction à votre disposition dans l''application VAR[EMC2#appname].</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Le bureau conseil carrière compétences vous remercie.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (87, 'FORMATION_SESSION_EMARGEMENT', '<p>Mail notifiant des listes d''émargement</p>', 'mail', 'La session de formation VAR[FORMATION_INSTANCE#Libelle] du VAR[FORMATION_INSTANCE#Periode] va bientôt commencer', e'<p>Université de Caen Normandie<br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN 5                                                                                                                                                     <br /><br />A Caen, le VAR[EMC2#date]</p>
<p><br /><br />Bonjour,<br /> <br /><br />Vous animez prochainement la formation VAR[FORMATION_INSTANCE#Libelle] du VAR[FORMATION_INSTANCE#Periode].<br /><br /><br />En vous connectant à l''application EMC2, vous pourrez récupérer les listes d''émargement de cette session de formation à l''adresse suivante : VAR[URL#FormationInstanceAfficher].<br /><br /> <br /><br />Le bureau conseil, carrière, compétences vous souhaite une bonne animation de formation.<br /><br /> <br /><br />Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (88, 'FORMATION_SESSION_LISTE_COMPLEMENTAIRE', '<p>Mail envoyé aux agents (individuellement) lors d''une inscription en liste complémentaire</p>', 'mail', 'Vous êtes inscrit sur la liste complémentaire de la session de formation VAR[SESSION#libelle]', e'<p>Université de Caen Normandie<br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN 5                                                                                                                                     <br /><br />A Caen, le VAR[EMC2#date]</p>
<p><br /><br />Bonjour VAR[AGENT#denomination],<br /><br /> <br />Vous êtes positionné-e sur liste complémentaire pour la formation VAR[SESSION#libelle] (VAR[SESSION#identification]) du VAR[SESSION#periode].<br /><br />Dès qu''une place se libère, vous en serez informé-e par courrier électronique.<br /><br /> <br /><br />Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (89, 'FORMATION_SESSION_LISTE_PRINCIPALE', '<p>Mail envoyé (individuellement) aux agents de la liste principale.</p>', 'mail', 'Vous êtes inscrit sur la liste principale de la session de formation VAR[SESSION#libelle]', e'<p>Université de Caen Normandie<br />DRH - Bureau conseil, carrière, compétences<br />Esplanade de la Paix<br />14032 CAEN 5                                                                                                                                                                                      <br /><br />A Caen, le VAR[EMC2#date]</p>
<p><br /><br />Bonjour VAR[AGENT#denomination],<br /> <br /><br />Votre inscription à la session de formation formation VAR[SESSION#libelle] (formation VAR[SESSION#identification]) du VAR[SESSION#periode] est confirmée.<br /><br /><br />Vous allez prochainement recevoir par courrier électronique votre convocation et vous pourrez la télécharger via l''application EMC2 (VAR[URL#App]). <br /><br />Cette convocation vaut ordre de mission.<br /><br /><br /><br />Le bureau conseil, carrière, compétences.<br />drh.formation@unicaen.fr</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (8, 'FICHE_METIER', '<p>Exportation pdf d''une fiche métier</p>', 'pdf', 'Fiche_Métier_VAR[FICHE_METIER#INTITULE].pdf', e'<h1>VAR[FICHE_METIER#INTITULE]</h1>
<table style="width: 517px;">
<tbody>
<tr>
<td style="width: 234.533px;">VAR[METIER#REFERENCE]</td>
<td style="width: 265.467px;">VAR[METIER#Domaine]</td>
</tr>
</tbody>
</table>
<h2>Missions principales</h2>
<p>VAR[FICHE_METIER#MISSIONS_PRINCIPALES]</p>
<h2>Compétences</h2>
<p>VAR[FICHE_METIER#COMPETENCES_COMPORTEMENTALES]<br />VAR[FICHE_METIER#CONNAISSANCES]<br />VAR[FICHE_METIER#COMPETENCES_OPERATIONNELLES]</p>
<h2>Applications</h2>
<p>VAR[FICHE_METIER#APPLICATIONS]</p>
<h2>Parcours de formation</h2>
<p>VAR[PARCOURS#FORMATIONS]</p>', 'body {font-size: 9pt;}h1 {font-size: 14pt; color:#123456;}h2 {font-size: 12pt; color:#123456; border-bottom: 1px solid #123456;}h3 {font-size: 10pt; color:#123456;}li.formation-groupe {font-weight:bold;} .mission-principale { padding-bottom:0; margin-bottom:0;}');
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (20, 'CREP - Compte rendu d''entretien professionnel', '<p>Compte-rendu de l''entretien professionnel d''un agent</p>', 'pdf', 'Entretien_professionnel_VAR[CAMPAGNE#annee]_VAR[AGENT#NomUsage]_VAR[AGENT#Prenom].pdf', e'<h1>Annexe C9 - Compte rendu de l''entretien professionnel</h1>
<p><strong>Année : VAR[CAMPAGNE#annee]</strong></p>
<table style="width: 998.25px;">
<tbody>
<tr>
<th style="width: 482px; text-align: center;"><strong>AGENT</strong></th>
<th style="width: 465.25px; text-align: center;"><strong>SUPÉRIEUR·E HIÉRARCHIQUE DIRECT·E</strong></th>
</tr>
<tr>
<td style="width: 482px;">
<p>Nom d''usage : VAR[AGENT#NomUsage]</p>
<p>Nom de famille : VAR[AGENT#NomFamille]</p>
<p>Prénom : VAR[AGENT#Prenom]</p>
<p>Date de naissance: VAR[AGENT#DateNaissance]</p>
<p>Corps-grade : VAR[AGENT#CorpsGrade]<strong><br /></strong></p>
<p>Échelon : VAR[Agent#Echelon]</p>
<p>Date de promotion dans l''échelon : VAR[Agent#EchelonDate]</p>
</td>
<td style="width: 465.25px;">
<p>Nom d''usage : VAR[ENTRETIEN#ReponsableNomUsage]</p>
<p>Nom de famille : VAR[ENTRETIEN#ReponsableNomFamille]</p>
<p>Prénom : VAR[ENTRETIEN#ReponsablePrenom]</p>
<p>Corps-grade : VAR[ENTRETIEN#ReponsableCorpsGrade]</p>
<p>Intitulé de la fonction : VAR[ENTRETIEN#ReponsableIntitlePoste]VAR[ENTRETIEN#CREP_Champ|CREP;responsable_date]</p>
<p>Structure : VAR[ENTRETIEN#ReponsableStructure]</p>
<p>Date de l''entretien professionnel : VAR[ENTRETIEN#date]</p>
</td>
</tr>
</tbody>
</table>
<h2>1. Description du poste occupé par l''agent</h2>
<table style="width: 722px;">
<tbody>
<tr>
<td style="width: 722px;">
<p>Structure : VAR[AGENT#AffectationStructure]</p>
<p>Intitulé du poste : VAR[AGENT#IntitulePoste]VAR[ENTRETIEN#CREP_Champ|CREP;agent_poste]</p>
<p>Date d''affectation : VAR[ENTRETIEN#CREP_Champ|CREP;affectation_date]</p>
<p>Emploi type (cf. REME ou REFERENS) : VAR[AGENT#EmploiType] VAR[ENTRETIEN#CREP_Champ|CREP;emploi-type]</p>
<p>Positionnement du poste dans le structure : VAR[AGENT#AffectationStructureFine]</p>
<p>Quotité travaillée : VAR[AGENT#Quotite]</p>
<p>Quotité d''affectation : VAR[AGENT#QuotiteAffectation]</p>
</td>
</tr>
<tr>
<td style="width: 722px;">
<p>Missions du postes :<br />VAR[AGENT#Missions]</p>
</td>
</tr>
<tr>
<td style="width: 722px;">
<p>Missions du postes (compléments fournis dans l''entretien professionnel) :</p>
<p> VAR[ENTRETIEN#CREP_Champ|CREP;missions]</p>
</td>
</tr>
<tr>
<td style="width: 722px;">
<p>Le cas échéant, fonctions d''encadrement ou de conduite de projet :</p>
<ul>
<li>l''agent assume-t''il des fonctions de conduite de projet ? VAR[ENTRETIEN#CREP_projet]</li>
<li>l''agent  assume-t''il des fonctions d''encadrements ? VAR[ENTRETIEN#CREP_encadrement]<br />Si oui préciser le nombre d''agents et leur répartition par catégorie : VAR[ENTRETIEN#CREP_encadrementA] A - VAR[ENTRETIEN#CREP_encadrementB] B - VAR[ENTRETIEN#CREP_encadrementC] C</li>
</ul>
</td>
</tr>
</tbody>
</table>
<h2>2. Évaluation de l''année écoulée</h2>
<h3>2.1 Rappel des objectifs d''activités attendus fixés l''année précédente</h3>
<p>(merci d''indiquer si des démarches ou moyens spécifiques ont été mis en œuvres pour atteindre ces objectifs)</p>
<table style="width: 891px;">
<tbody>
<tr>
<td style="width: 891px;">VAR[ENTRETIEN#CREP_Champ|CREP;2.1]</td>
</tr>
</tbody>
</table>
<h3>2.2 Événements survenus au cours de la période écoulée ayant entraîné un impact sur l''activité</h3>
<p>(nouvelles orientations, réorganisations, nouvelles méthodes, nouveaux outils, etc.) </p>
<table style="width: 888px;">
<tbody>
<tr>
<td style="width: 888px;">VAR[ENTRETIEN#CREP_Champ|CREP;2.2]</td>
</tr>
</tbody>
</table>
<h2>3. Valeur professionnelle et manière de servir du fonctionnaire</h2>
<h3>3.1 Critères d''appréciation</h3>
<p>L’évaluateur retient, pour apprécier la valeur professionnelle des agents au cours de l''entretien professionnel, les critères annexés à l’arrêté ministériel et qui sont adaptés à la nature des tâches qui leur sont confiées, au niveau de leurs responsabilités et au contexte professionnel. Pour les infirmiers et les médecins seules les parties 2, 3 et 4 doivent être renseignées en tenant compte des limites légales et règlementaires en matière de secret professionnel imposées à ces professionnels.</p>
<p><strong>1. Les compétences professionnelles et technicité</strong></p>
<p>Maîtrise technique ou expertise scientifique du domaine d''activité, connaissance de l''environnement professionnel et capacité à s''y situer, qualité d''expression écrite, qualité d''expression orale, ...</p>
<table style="width: 840px;">
<tbody>
<tr style="height: 14.7344px;">
<td style="width: 840px; height: 14.7344px;">VAR[ENTRETIEN#CREP_Champ|CREP;3.1.1]</td>
</tr>
</tbody>
</table>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;3.1.1old]</p>
<p><strong>2. La contribution à l’activité du service</strong></p>
<p>Capacité à partager l''information, à transférer les connaissances et à rendre compte, capacité à s''invertir dans des projets, sens du service public et conscience professionnelle, capacité à respecter l''organisation collective du travail, ...</p>
<table style="width: 560px;">
<tbody>
<tr>
<td style="width: 560px;">VAR[ENTRETIEN#CREP_Champ|CREP;3.1.2]</td>
</tr>
</tbody>
</table>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;3.1.2old]</p>
<p><strong>3. Les capacités professionnelles et relationnelles </strong></p>
<p>Autonomie, discernement et sens des initiatives dans l''exercice de ses attributions, capacité d''adaptation, capacité à travailler en équipe, ...</p>
<table style="width: 560px;">
<tbody>
<tr>
<td style="width: 560px;">VAR[ENTRETIEN#CREP_Champ|CREP;3.1.3]</td>
</tr>
</tbody>
</table>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;3.1.3old]</p>
<p><strong>4. Le cas échéant, aptitude à l''encadrement et/ou à la conduite de projets</strong></p>
<p>Capacité d''organisation et de pilotage, aptitude à la conduite de projets, capacité à déléguer, aptitude au dialogue, à la communication et à la négociation, ...</p>
<table style="width: 560px;">
<tbody>
<tr>
<td style="width: 560px;">VAR[ENTRETIEN#CREP_Champ|CREP;3.1.4]</td>
</tr>
</tbody>
</table>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;3.1.4old]</p>
<h3>3.2 Appréciation générale sur la valeur professionnelle, la manière de servir et la réalisation des objectifs</h3>
<table style="width: 764px;">
<tbody>
<tr>
<td style="width: 413.297px;"><strong>Compétences professionnelles et technicité</strong></td>
<td style="width: 352.703px;">VAR[ENTRETIEN#CREP_Champ|CREP;3.2.1]</td>
</tr>
<tr>
<td style="width: 413.297px;"><strong>Contribution à l''activité du service</strong></td>
<td style="width: 352.703px;">VAR[ENTRETIEN#CREP_Champ|CREP;3.2.2]</td>
</tr>
<tr>
<td style="width: 413.297px;"><strong>Capacités professionnelles et relationnelles</strong></td>
<td style="width: 352.703px;">VAR[ENTRETIEN#CREP_Champ|CREP;3.2.3]</td>
</tr>
<tr>
<td style="width: 413.297px;"><strong>Aptitude à l''encadrement et/ou à la conduite de projet</strong></td>
<td style="width: 352.703px;">VAR[ENTRETIEN#CREP_Champ|CREP;3.2.4]</td>
</tr>
</tbody>
</table>
<p> </p>
<table style="width: 763px;">
<tbody>
<tr>
<td style="width: 763px;">
<h3>Réalisation des objectifs de l''année écoulée</h3>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;realisation]</p>
</td>
</tr>
<tr>
<td style="width: 763px;">
<h3>Appréciation littérale</h3>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;appreciation]</p>
</td>
</tr>
</tbody>
</table>
<h3>4. Acquis de l''expérience professionnelle</h3>
<p>Vous indiquerez également dans cette rubrique si l''agent occupe des fonctions de formateur, de membre du jury, d''assistant de prévention, mandat électif, ...</p>
<table style="width: 755px;">
<tbody>
<tr>
<td style="width: 745px;">
<p>VAR[ENTRETIEN#CREP_Champ|CREP;exppro_1]</p>
<p>VAR[ENTRETIEN#CREP_Champ|CREP;exppro_2]</p>
</td>
</tr>
</tbody>
</table>
<h2>5. Objectifs fixés pour la nouvelle année</h2>
<h3>5.1 Objectifs d''activités attendus</h3>
<table style="width: 757px;">
<tbody>
<tr>
<td style="width: 747px;">VAR[ENTRETIEN#CREP_Champ|CREP;5.1]</td>
</tr>
</tbody>
</table>
<h3>5.2 Démarche envisagée, et moyens à prévoir dont la formation, pour faciliter l''atteinte des objectifs</h3>
<table style="width: 755px;">
<tbody>
<tr>
<td style="width: 745px;">VAR[ENTRETIEN#CREP_Champ|CREP;5.2]</td>
</tr>
</tbody>
</table>
<h2>6. Perspectives d''évolution professionnelle</h2>
<h3>6.1 Évolution des activités (préciser l''échéance envisagée)</h3>
<table style="width: 758px;">
<tbody>
<tr>
<td style="width: 748px;">VAR[ENTRETIEN#CREP_Champ|CREP;6.1]</td>
</tr>
</tbody>
</table>
<h3>6.2 Évolution de carrière</h3>
<p><strong>Attention</strong> : à compléter obligatoirement pour les agent ayant atteint le dernier échelon de leur grade depuis au moins trois ans au 31/12 de l''année au titre de la présente évaluation, et lorsque la nomination à ce grade ne résulte pas d''un avancement de grade ou d''un accès à celui-ci par concours ou promotion interne.</p>
<table style="width: 757px;">
<tbody>
<tr>
<td style="width: 747px;">VAR[ENTRETIEN#CREP_Champ|CREP;6.2]</td>
</tr>
</tbody>
</table>
<h2>7. Signature du supérieur hiérarchique direct</h2>
<table style="width: 718px;">
<tbody>
<tr>
<td style="width: 718px;">
<p>Date de l''entretien : VAR[ENTRETIEN#date]</p>
<p>Date de transmission du compte rendu : </p>
<p>Nom, qualité et signature du responsable hiérarchique :<br /><br /><br /><br />VAR[ENTRETIEN#VALIDATION_SUPERIEUR]</p>
</td>
</tr>
</tbody>
</table>
<h2> 8. Observations de l''agent sur son évaluation</h2>
<p>(dans un délai d''une semaine à compter de la date de transmission du compte rendu)</p>
<table style="width: 721px;">
<tbody>
<tr>
<td style="width: 711px;">
<p>Sur l''entretien : VAR[ENTRETIEN#ObservationEntretien]</p>
<p>Sur les perspectives de carrière et de mobilité : VAR[ENTRETIEN#ObservationPerspective]</p>
</td>
</tr>
</tbody>
</table>
<h2>9. Signature de l''autorité hiérarchique</h2>
<table style="width: 720px;">
<tbody>
<tr>
<td style="width: 720px;">
<p>Date :</p>
<p>Nom, qualité et signature de l''autorité hiérarchique :<br /><br /></p>
<p><br />VAR[ENTRETIEN#VALIDATION_AUTORITE]</p>
</td>
</tr>
</tbody>
</table>
<h2>10. Signature de l''agent</h2>
<table style="width: 720px;">
<tbody>
<tr>
<td style="width: 720px;">
<p>Date :</p>
<p>Nom, qualité et signature de l''autorité hiérarchique :<br /><br /><br />VAR[ENTRETIEN#VALIDATION_AGENT]</p>
</td>
</tr>
</tbody>
</table>
<h3> Modalité de recours </h3>
<ul>
<li><strong>Recours spécifique (Article 6 du décret n° 2010-888 du 28 juillet 2010)</strong><br />L’agent peut saisir l’autorité hiérarchique d’une demande de révision de son compte rendu d’entretien professionnel. Ce recours hiérarchique doit être exercé dans le délai de 15 jours francs suivant la notification du compte rendu d’entretien professionnel. La réponse de l’autorité hiérarchique doit être notifiée dans un délai de 15 jours francs à compter de la date de réception de la demande de révision du compte rendu de l’entretien professionnel. A compter de la date de la notification de cette réponse l’agent peut saisir la commission administrative paritaire dans un délai d''un mois. Le recours hiérarchique est le préalable obligatoire à la saisine de la CAP.</li>
<li><strong>Recours de droit commun<br /></strong>L’agent qui souhaite contester son compte rendu d’entretien professionnel peut exercer un recours de droit commun devant le juge administratif dans les 2 mois suivant la notification du compte rendu de l’entretien professionnel, sans exercer de recours gracieux ou hiérarchique (et sans saisir la CAP) ou après avoir exercé un recours administratif de droit commun (gracieux ou hiérarchique). <br />Il peut enfin saisir le juge administratif à l’issue de la procédure spécifique définie par l’article 6 précité. Le délai de recours contentieux, suspendu durant cette procédure, repart à compter de la notification de la décision finale de l’administration faisant suite à l’avis rendu par la CAP.</li>
</ul>
<p><code></code></p>', 'body {font-size:9pt;} h1 {font-size: 14pt; color: #154360;} h2 {font-size:12pt; color:#154360;} h3 {font-size: 11pt; color: #154360;}table {border:1px solid black;border-collapse:collapse; width: 100%;} td {border:1px solid black;} th {border:1px solid black; color:#154360;}');
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (90, 'FICHE_POSTE_VALIDATION_AGENT', null, 'mail', 'Validation de VAR[AGENT#Denomination] de sa fiche de poste', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">VAR[AGENT#Denomination] vient de valider sa fiche de poste.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences<br />VAR[EMC2#Nom]</p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (91, 'FICHE_POSTE_VALIDATION_RESPONSABLE', '<p>Mail envoyé à l''agent·e après la validation d''une fiche de poste par le·la responsable de l''agent·e</p>', 'mail', 'Votre fiche de poste de poste vient d''être validée par votre responsable', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Université de Caen Normandie<br />Direction des Ressources Humaines<br /><br />Bonjour,<br /><br />Votre fiche de poste vient d''être validée par votre responsable.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Vous pouvez maintenant vous rendre dans EMC2 pour valider à votre tour celle-ci.<br />Vous retrouverez celle-ci à l''adresse suivante : VAR[URL#FichePosteAcceder]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><br />Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences<br />VAR[EMC2#Nom]</p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (72, 'FORMATION_HISTORIQUE', null, 'pdf', 'Historique des formations de VAR[AGENT#denomination]', e'<h1 style="text-align: center;"> Historique de formation</h1>
<p> </p>
<p>L''agent <strong>VAR[AGENT#denomination]</strong> a suivi les formations suivantes  : ###A REMPLACER###</p>
<p> </p>
<p style="text-align: right;">La responsable du bureau conseil, carrière, compétences.<br /><br /></p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (7, 'FICHE_POSTE', '<p>Fiche de poste de l''agent</p>', 'pdf', 'FICHE_POSTE_VAR[AGENT#Denomination].pdf', e'<h1>VAR[FICHE_POSTE#LIBELLE]</h1>
<h2>Agent occupant le poste</h2>
<table style="width: 595px;">
<tbody>
<tr>
<td><strong>Dénomination</strong></td>
<td>VAR[AGENT#Denomination]</td>
</tr>
<tr>
<td><strong>Affectation principale<br /></strong></td>
<td>VAR[AGENT#AffectationStructure] / VAR[AGENT#AffectationStructureFine]</td>
</tr>
<tr>
<td><strong>Statut</strong></td>
<td> VAR[AGENT#StatutsActifs]</td>
</tr>
<tr>
<td><strong>Corps / Grade</strong></td>
<td> VAR[AGENT#GradesActifs]</td>
</tr>
<tr>
<td><strong>Quotité travaillée</strong></td>
<td>VAR[AGENT#Quotite]</td>
</tr>
<tr>
<td><strong>Échelon</strong></td>
<td>VAR[Agent#Echelon] (Date de passage : VAR[Agent#EchelonDate])</td>
</tr>
</tbody>
</table>
<p>VAR[AGENT#MissionsSpecifiques]</p>
<h2>Environnement du poste de travail</h2>
<p>VAR[STRUCTURE#resume]</p>
<h2>Composition de la fiche de poste</h2>
<p>VAR[FICHE_POSTE#FichesMetiers]</p>
<h2>Applications et compétences associées</h2>
<p>VAR[FICHE_POSTE#Applications]<br />VAR[FICHE_POSTE#Connaissances]<br />VAR[FICHE_POSTE#CompetencesOperationnelles]<br />VAR[FICHE_POSTE#CompetencesComportementales]</p>
<h2>Spécificité du poste</h2>
<p>VAR[FICHE_POSTE#Specificite]</p>
<p>VAR[FICHE_POSTE#SpecificiteActivites]</p>', 'body {font-size: 9pt;}h1 {font-size: 14pt; color:#123456;}h2 {font-size: 12pt; color:#123456; border-bottom: 1px solid #123456;}h3 {font-size: 10pt; color:#123456;}li.formation-groupe {font-weight:bold;} .mission-principale { padding-bottom:0; margin-bottom:0;}span.activite{border-left:1px solid #123456; display:block; }');
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (6, 'MISSION_SPECIFIQUE_LETTRE', '<p>Lettre type associée à une mission spécifique d''un agent</p>', 'pdf', 'Mission_specifique_-_VAR[MISSION_SPECIFIQUE_AFFECTATION#ID]_-_VAR[AGENT#Denomination]', e'<h1>Justificatif de mission spécifique</h1>
<p> </p>
<p>L''agent <strong>VAR[AGENT#Denomination]</strong> assure la mission spécifique <strong>VAR[MISSION_SPECIFIQUE#LIBELLE]</strong> dans le structure <strong>VAR[STRUCTURE#LIBELLE]</strong>.</p>
<ul>
<li>Il assume cette mission spécifique pour la période <strong>VAR[MISSION_SPECIFIQUE_AFFECTATION#PERIODE]</strong>.</li>
<li>Cette mission spécique permet une décharge horaire (<strong>VAR[MISSION_SPECIFIQUE_AFFECTATION#DECHARGE]</strong>)</li>
</ul>
<p>Ce document fait preuve.</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (4, 'PROFIL_DE_RECRUTEMENT', '<p>Profil de recrutement utilis&eacute; pour le partage de l''ouverture d''une poste au recrutement</p>', 'pdf', 'Profil de recrutement - VAR[STRUCTURE#LIBELLE] - VAR[FICHE_POSTE#LIBELLE]', e'<p>&nbsp;</p>
<h1>L&rsquo;Universit&eacute; de Caen Normandie recrute <br />pour sa structure <strong>VAR[STRUCTURE#LIBELLE]</strong><br />un&middot;e <strong>VAR[FICHE_POSTE#LIBELLE]</strong></h1>
<p><strong>VAR[PROFIL#VACANCE]</strong></p>
<p>&nbsp;</p>
<p>UNICAEN avec ses 32 000 &eacute;tudiants, est un acteur majeur et un moteur de d&eacute;veloppement de l''enseignement sup&eacute;rieur et de la recherche en Normandie. UNICAEN est membre de Normandie Universit&eacute;</p>
<h2>Cadre statutaire du poste</h2>
<p>VAR[FICHE_POSTE#Cadre]VAR[PROFIL#LIEU]VAR[PROFIL#CONTEXTE]VAR[PROFIL#MISSION]</p>
<h2>Activit&eacute;s principales</h2>
<p>VAR[FICHE_POSTE#FichesMetiersCourt]</p>
<p>VAR[FICHE_POSTE#SpecificiteRelations]</p>
<h2>Exigences requises</h2>
<p>VAR[PROFIL#NIVEAU]</p>
<p>VAR[FICHE_POSTE#SpecificiteFormations]</p>
<h2>Les comp&eacute;tences clefs du poste</h2>
<h3>Connaissances</h3>
<p>VAR[FICHE_POSTE#ConnaissancesToutes]</p>
<h3>Comp&eacute;tences op&eacute;rationnelles</h3>
<p>VAR[FICHE_POSTE#CompetencesOperationnellesToutes]</p>
<h3>Comp&eacute;tences comportementales</h3>
<p>VAR[FICHE_POSTE#CompetencesComportementalesToutes]</p>
<p>VAR[FICHE_POSTE#SpecificiteContraintes]<br />VAR[FICHE_POSTE#SpecificiteMoyens]</p>
<p>VAR[PROFIL#CONTRAT]<br />VAR[PROFIL#RENUMERATION]</p>
<h2>Modalit&eacute;s de candidature</h2>
<p>Les candidats pourront d&eacute;poser leur dossier par mail &agrave; <strong>drh.recrutement.biatss@unicaen.fr</strong> avant le <strong>VAR[PROFIL#DateDossier]</strong> comportant :</p>
<ul>
<li>une lettre de motivation</li>
<li>un curriculum vitae d&eacute;crivant le parcours ant&eacute;rieur de formation et l&rsquo;exp&eacute;rience du candidat</li>
</ul>
<p>Les candidatures seront examin&eacute;es par une commission de s&eacute;lection et seuls seront convoqu&eacute;s &agrave; l&rsquo;entretien les candidats retenus par cette commission.<br />VAR[PROFIL#DateAudition]</p>
<p>&nbsp;</p>
<p><em>Seuls les dossiers complets seront &eacute;tudi&eacute;s.</em></p>', 'body { font-size: 9pt; font-family: Ubuntu, Arial, Courrier;} h1 {background: none; font-size: 12pt;  font-weight:normal; margin-bottom: 0.25rem; } h2 {background: none; font-size: 12pt; color:grey; margin-bottom: 0.25rem; } h3 {background: none; font-size: 10pt; margin:0 ; padding :0; font-weight:normal;} p { margin: 0 padding:0} ul {margin:0, padding:0 }');
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (59, 'ENTRETIEN_VALIDATION_2-OBSERVATION_TRANSMISSION', 'Transmission des observations aux responsable d''entretien professionnel', 'mail', 'L''expression des observations de VAR[AGENT#Denomination] sur son entretien professionnel de la campagne VAR[CAMPAGNE#annee]', e'<p>VAR[AGENT#Denomination] vient de valider ses observations pour l''entretien professionnel de la campagne VAR[CAMPAGNE#annee].</p>
<p><span style="text-decoration: underline;">Observations sur l''entretien professionnel</span></p>
<p>VAR[ENTRETIEN#ObservationEntretien]</p>
<p><span style="text-decoration: underline;">Observation sur les perspectives</span></p>
<p>VAR[ENTRETIEN#ObservationPerspective]</p>
<p> </p>
<p>Cordialement,<br />EMC2</p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (21, 'CAMPAGNE_OUVERTURE_BIATSS', 'Mail envoyé au personnel lors de l''ouverture d''une campagne d''entretien professionnel', 'mail', 'Ouverture de la campagne d''entretien professionnel VAR[CAMPAGNE#annee]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p><span style="text-decoration: underline;">Objet :</span> ouverture de la campagne d''entretien professionnel VAR[CAMPAGNE#annee] </p>
<p>Bonjour,</p>
<p>La campagne d''entretien professionnel au titre de l''année universitaire VAR[CAMPAGNE#annee] est ouverte, vous pourrez trouver sous le lien suivant la circulaire d''ouverture en date du VAR[CAMPAGNE#datecirculaire] ainsi que plusieurs documents pouvant vous aider à préparer votre entretien : http://intranet.unicaen.fr/services-/ressources-humaines/gestion-des-personnels/entretien-professionnel-540109.kjsp?RH=1574253529391</p>
<p>Vous recevrez prochainement une convocation par courrier électronique.</p>
<p>Pour tout renseignement complémentaire, vous pouvez contacter votre responsable hiérarchique.</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (22, 'CAMPAGNE_OUVERTURE_DAC', '<p>Mail envoy&eacute; au Directeur/Responsable de service lors de l''ouverture d''une campagne d''entretien professionnel</p>', 'mail', 'Ouverture de la campagne d''entretien professionnel VAR[CAMPAGNE#annee]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p><span style="text-decoration: underline;">Objet :</span> ouverture de la campagne d''entretien professionnel VAR[CAMPAGNE#annee] </p>
<p>Bonjour,</p>
<p>La campagne d''entretien professionnel au titre de l''année universitaire VAR[CAMPAGNE#annee] est ouverte, vous pourrez trouver sous le lien suivant la circulaire d''ouverture en date du VAR[CAMPAGNE#datecirculaire] ainsi que plusieurs documents pouvant vous aider à préparer les entretiens de vos agents : http://intranet.unicaen.fr/services-/ressources-humaines/gestion-des-personnels/entretien-professionnel-540109.kjsp?RH=1574253529391</p>
<p>Le retour des comptes-rendus (CREP) est demandé pour le : VAR[CAMPAGNE#fin]</p>
<p>La DRH reste à votre disposition pour toute demande de renseignement complémentaire.</p>
<p>Cordialement<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (23, 'ENTRETIEN_CONVOCATION_ACCEPTER', '<p>Mail de notification de l''acceptation de l''agent de son entretien professionnel à son responsable hiérarchique direct</p>', 'mail', 'Acceptation de l''entretien professionnel par VAR[AGENT#denomination]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="text-decoration: underline;">Objet :</span> acceptation de l''entretien professionnel par VAR[AGENT#denomination]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">VAR[AGENT#denomination] vient de prendre note et accepte l''entretien professionnel pour la campagne VAR[CAMPAGNE#annee].</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Celui-ci se déroule le VAR[ENTRETIEN#date] dans VAR[ENTRETIEN#lieu].</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (24, 'ENTRETIEN_VALIDATION_1-RESPONSABLE', null, 'mail', 'Validation de l''entretien professionnel pour la campagne VAR[CAMPAGNE#annee] de VAR[AGENT#denomination] par le responsable de l''entretien VAR[ENTRETIEN#responsable]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Votre entretien professionnel pour la campagne VAR[CAMPAGNE#annee] a été validé par VAR[ENTRETIEN#responsable], vous êtes invité<span style="color: #4d5156; font-family: arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;">·</span>e à en prendre connaissance et y apporter des observations si vous l''estimez nécessaire.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Vous pouvez dés à présent émettre, si besoin, des observations en lien avec :</p>
<ul>
<li style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">votre entretien professionnel et son déroulé ;</li>
<li style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">vos perspectives d''évolution professionnelle.</li>
</ul>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Votre entretien professionnel est disponible sur l''application VAR[EMC2#Nom] : VAR[URL#EntretienRenseigner].</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="text-decoration: underline;">Attention :</span> Vous disposez d''un délai d''une semaine pour émettre, si besoin, vos observations. <br />N''oubliez pas de valider celles-ci.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Pour rappel, l''entretien professionnel est un moment privilégié d''échange et de dialogue avec votre responsable hiérarchique direct.<br />Nous vous invitons, si besoin, à vous rapprocher de votre responsable hiérarchique direct avant d''émettre vos observations.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><br />Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (19, 'ENTRETIEN_VALIDATION_2-OBSERVATION', '<p>Mail envoyé au responsable d''entretien vers le responsable hiérarchique</p>', 'mail', 'Observations de VAR[AGENT#denomination] sur son entretien professionnel', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="text-decoration: underline;">Objet :</span> observation de VAR[AGENT#denomination] sur son entretien professionnel,</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;">VAR[AGENT#denomination]</span> vient d''émettre des observations en lien avec son entretien professionnel.<br />Vous pouvez maintenant consulter et valider son entretien et ses observations en suivant le lien : https://emc2.unicaen.fr/entretien-professionnel/acceder/VAR[ENTRETIEN#Id]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br /><br /></p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (26, 'ENTRETIEN_VALIDATION_3-HIERARCHIE', '<p>Validation du responsable hierarchique</p>', 'mail', 'Validation de l''autorité hiérarchique de votre entretien professionnel VAR[CAMPAGNE#annee]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><span style="text-decoration: underline;">Objet :</span> validation de l''autorité hiérarchique de votre entretien professionnel VAR[CAMPAGNE#annee]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">L''autorité hiérarchique vient de valider votre entretien professionnel pour la campagne VAR[CAMPAGNE#annee].<br />Vous êtes invité-e à accuser réception de votre compte-rendu en cliquant dans l''onglet validation de votre entretien : VAR[URL#EntretienRenseigner]<br />Cet accusé de réception clôturera votre entretien professionnel.</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (33, 'FICHEMETIER_ENTETE', '<p>Entête des fiches métiers</p>', 'texte', '.', e'<table style="width: 959px; height: 60px;">
<tbody>
<tr>
<td style="width: 280.4px;">VAR[METIER#Libelle]<br />VAR[METIER#References]</td>
<td style="width: 662.6px;">VAR[METIER#Domaines]</td>
</tr>
</tbody>
</table>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (42, 'CAMPAGNE_DELEGUE', '<p>Courrier électronique envoyé vers les délégués lors de leur nomination</p>', 'mail', 'Nomination en tant que délégué·e pour la campagne d''entretien professionnel VAR[CAMPAGNE#annee]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p> </p>
<p><span style="text-decoration: underline;">Objet :</span> Nomination en tant que délégué·e pour la campagne d''entretien professionnel VAR[CAMPAGNE#annee]</p>
<p>Bonjour VAR[AGENT#denomination],</p>
<p>Vous avez été nommé·e délégué·e pour la campagne d''entretien professionnel VAR[CAMPAGNE#annee] pour la structure VAR[STRUCTURE#LIBELLE].<br />Vous serez informé par courrier électronique des entretiens professionnel dont vous aurez la charge.</p>
<p>Vous pourrez trouver sous le lien suivant la circulaire d''ouverture en date du VAR[CAMPAGNE#datecirculaire] ainsi que plusieurs documents pouvant vous aider à préparer votre entretien : http://intranet.unicaen.fr/services-/ressources-humaines/gestion-des-personnels/entretien-professionnel-540109.kjsp?RH=1574253529391</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br />VAR[EMC2#Nom]</p>
<p> </p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (40, 'NOTIFICATION_RAPPEL_CAMPAGNE', '<p>Mail envoyé périodiquement pour informer de l''état d''avancement de la campagne</p>', 'mail', 'État d''avancement de la campagne d''entretien professionnel VAR[CAMPAGNE#annee] pour la structure VAR[STRUCTURE#LIBELLE]', e'<p><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p><span style="text-decoration: underline;">Objet :</span> état d''avancement de la campagne d''entretien professionnel VAR[CAMPAGNE#annee] pour la structure VAR[STRUCTURE#LIBELLE]</p>
<p>Bonjour,</p>
<p>Vous recevez ce courrier électronique concernant l''avancement du ou des entretiens professionnels de la campagne VAR[CAMPAGNE#annee] dont vous avez la responsabilité. Attention cette campagne sera clôturée le VAR[CAMPAGNE#fin].</p>
<p>###A REMPLACER###</p>
<p>Pour gérer vos entretiens professionnels vous pouvez vous rendre dans EMC2 : https://emc2.unicaen.fr/</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences</p>', null);
INSERT INTO unicaen_renderer_template (id, code, description, document_type, document_sujet, document_corps, document_css) VALUES (41, 'ENTRETIEN_VALIDATION_2-PAS_D_OBSERVATION', '<p>Mail envoyé au responsable hiérarchique après le dépassement du délai d''émission des observation</p>', 'mail', 'Ouverture de la validation de l''entretien professionnel de VAR[ENTRETIEN#Agent]', e'<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><strong>Université de Caen Normandie</strong><br /><strong>Direction des Ressources Humaines</strong></p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><span style="text-decoration: underline;">Objet :</span> ouverture de la validation de l''entretien professionnel de VAR[ENTRETIEN#Agent]</p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"> </p>
<p style="line-height: 1.2em; color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">Bonjour,</p>
<p>Vous pouvez maintenant valider l''entretien professionnel de VAR[ENTRETIEN#Agent].<br />Vous pouvez consulter et valider cet entretien en suivant le lien : https://emc2.unicaen.fr/entretien-professionnel/acceder/VAR[ENTRETIEN#Id]</p>
<p>Cordialement,<br />Le bureau de gestion des personnels BIATSS<br />Le bureau Conseil Carrière Compétences <br /><br /></p>', null);

