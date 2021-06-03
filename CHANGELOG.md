Version 1.3.0 *Module de bourse emploi*
-------------
- TODO creation module

Version 1.2.0 *Module de formation*
-------------

Version 1.1.5
-------------
- [Correction bug] l'encart Mes données ne marchait plus faute d'agent fourni
- Parametrage et ajout d'une adresse par défaut pour les profils de recrutement
- Refonte macro pour le profil

Version 1.1.4
-------------
+ [Base de données] Reprise des données de LAGAF (1er batch)
+ Réactivation des indicateurs + nouveaux types : Libre, Adaptatif


Version 1.1.3
-------------
+ [Correction bug] Ajout d'une garde pour les entretiens professionnels d'a gent sans fiche de poste
+ [Correction bug] Ajout d'un message d'alerte lorsque un mail lié à une campagne d'entretien professionnel n'a pu être envoyé
+ [Interface] filtre pour les mails envoyés
+ [Interface] filtre pour les fiches métiers
+ [Qualité de vie] Les adresses électroniques de diffusion liées à la création de campagne d'entretien professionnel devenu un paramètre.
+ Suppression du rôle de responsable d'entretien professionnaire au profit des rôles de gestionnaire/responsable de structures
+ Ajout du mailtype dans les mails pour savoir si un mail est issue d'un mail type
+ Ajout d'une source et d'un id source dans les formations et les actions de formations
  
Version 1.1.2
-------------
+ Ajout de niveau de maîtrise pour les compétences et de compétences clefs + gestion de leur affection au niveau des ``CompetenceElement``.
+ Premier rendu visuel des graphiques en radar des applications liées aux fiches métiers et des couples (fiche métier, agent).
+ Premier rendu visuel des graphiques en radar des compétences liées aux fiches métiers et des couples (fiche métier, agent).
+ [Interface] changement de l'intitulé 'applicatif' en 'logiciels métiers' 
+ [Interface] éclatement en onglet de la page 'Agent' devenu trop longue
+ Refonte des ElementBlocs : applicationBloc, CompetenceBloc, formationBloc remplacent les agentApplication, agentCompetence, agentFormation
+ Possibilité de liés des compétences et des applications dans une formation qui sont transmises sur la fiche de l'agent si la formation a été suivie.
+ Ajout d'un attachement au mail pour pouvoir lister ceux-ci en fonction d'un élément (un entretien, une action de formation, ...)
+ Attachement des mails aux entretiens professionnels et aux campagnes d'entretien professionnel
+ [Interface] ajout d'un onglet 'Mails' dans les entretiens professionnels

Version 1.1.1
-------------
+ Affichage des structures de l'agent dans l'entête des entretiens professionnels (lien vers la structure si l'utilisateur a les droits de visualiser la structure). 
+ Division de l'onglet *Compte-rendu d'évaluation de formation* en trois onglets : *Compte-rendu d'évaluation de formation*, *Parcours de formation et applicatif*, *Acquis de l'agent*
+ Possibilité de renseigner les applications dans le parcours applicatif