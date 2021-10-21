#Unicaen Utilisateur Octopus Adpater

## But du module

Le but de cet adaptateur est de fournir une service effectuant une recherche dans Octopus en garantissant le contract des 
interface de `Unicaen/Utilisateur` : `RechercheIndividuServiceInterface` et `RechercheIndividuResultatInterface`.
Cela sans modification de `Unicaen/Octopus`.

## Historique des versions

**version 0.1.1**  16/10/2019
- version initial fournissant le service de recherche

## Amélioration et/ou changement à venir

Lorsque la gestion des comptes sera intégrer à Octopus penser à modifier les fonctions associées :
1. `OctopusIndividu::getUsername()`  
1. `OctopusIndividu::getEmail()`  