CHANGES
=======

Version 5.x
-----

### Version 5.0.1 - 05/02/2025

[AMELIORATION]
* Changement du code pour la remontée des agents dans l'onglet "liste des agents" 

[CORRECTION]
* Changement dans le filtrage des populations des entretiens professionnels ; les emploi-types, grades, corps et status ne font plus références aux structures car ils sont "globaux" 

### Version 5.0.0 - 30/01/2025

[NOUVEAUTÉS]
* Séparation EMC2 et Mes formations (https://git.unicaen.fr/open-source/mes-formations)
* Gestion de la base de données est maintenant faite par la biliothèque unicaen/bdd-admin
* Refonte des données associées aux chaînes hierarchiques (pour ne plus avoir à faire de déduction)
* Chaînes hiérarchiques importables (sans retirer les précédents systèmes)
* Ajout de la notion de code fonction sur les fiches métiers et les fiches de postes
* Ajout de la notion de périmètres pour les indicateurs

[CORRECTION]
* Amélioration de l'assertion contrôlant les personnes pouvant modifier les comptes-rendus
* Correction du problème lié à l'affichage des blocs "Raison d'être" et "Missions" dans la fiche métier
* Correction de la méthode de remonté des EPs excluant certains agents en retraite maintenant mais en poste à lors de la campagne
