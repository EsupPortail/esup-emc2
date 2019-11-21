# PrEECoG : Prévisionnelle des emplois, des effectifs et des compétences Outil de gestion

## Informations diverses sur le déploiement à l'Université de Caen

_Serveur de test_ : https://preecog-pp.unicaen.fr/

_Serveur de prod_ : https://preecog.unicaen.fr/

## Guide des versions

version **0.4.1** (21/11/2019) :
* Importation et stockage des structures parentes
* Correction du bug de sélection de structure sur la page 'mes-structures'
* Amélioration retour des modales de confirmation
* Ajout d'un ordre pour les type de compétences et de viewhelper competence et competences

version **0.4.0** (31/10/2019) :
* Début de mise en place des validations des fiches metiers  

version **0.3.6** (30/10/2019) :
* Correction du bug empèchant l'affichage des compétences sur l'export PDF des fiches de poste

version **0.3.5** (29/10/2019) :
* Correction du numéro de poste qui était remonté comme l'identifiant du poste sur l'export
* Correction du problème lié au vidage des compétences, formations et applications des fiches metiers (surement lié à une interaction de boostrap-select et multiple) 
* Ajout de garde pour les infos du grade en de données manquantes

version **0.3.4** (17/10/2019) :
* Correction du bug de remonté des agents d'une structure (null est pris en compte et on a plus besoin de 1999/12/31)

version **0.3.3** (11/10/2019) :
* La description des compétences n'est plus obligatoire
* Récupération des grades modifiée pour la gestion des agents ayant plusieurs grades simultanement,
* Amélioration graphique de l'exportation des fiche de poste 

version **0.3.0** (18/09/2019) : 
* Gestion de sa structure : 
    - listing des agents, des fiches de postes et des missions spécifiques ;
    - gestion de l'intégralité des fonctionnalités associés à un gestionnaire de structures sur la page 'Ma structure'.
* Passage en ZF3

## Reste à faire 

* Partie bourse à l'emploi
* Amélioration de la gestion des abonnements aux indicateurs
* Amélioration de la création des requètes associées aux indicateurs