# Module Unicaen Utilisateur

Ce module a pour but de reprendre la gestion :
* des comptes utilisateurs
* de l'ajout de nouveaux rôles

## Gestion des utilisateurs 

Le partie gestion des utilisateurs permet de gérer la liste de utilisateur de l'application.
Soit en accédant à un utilisateur enregistré (dont le mail paraît en bleu), soit en ajoutant un utilisateur à partir du LDAP (dont le mail apparaît en violet).

Une fois un utilisateur récupérer il est possible de :
* changer son status (i.e. activer ou désactiver le compte) ;
* supprimer le compte associé ;
* ajout ou retirer un compte.

La gestion permet aussi le listing complet des utilisateurs de l'application ainsi que son exportation au format CSV.

Le module permet aussi la création d'utilisateur locaux. Noter que le nom d'utilisateur et l'adresse électronique doivent être unique.

## Gestion des rôles

Le module unicaen/utilisateur met à disposition une interface de gestion des rôles permettant de créer, modifier et supprimer des rôles. 

## Configuration du module

La configuration du module est associé à la recherche d'individu dans d'autres services.
```php
 <?php
 
 use Octopus\Service\Individu\IndividuService;
 use UnicaenUtilisateur\Service\OtherSources\LdapService;
 use UnicaenUtilisateur\Service\User\UserService;
 
 return [
    'zfcuser' => [
        'user_entity_class' => \UnicaenUtilisateur\Entity\Db\User::class,
    ],
    'unicaen-auth' => [
        'role_entity_class' => \UnicaenUtilisateur\Entity\Db\Role::class,
    ],

     'unicaen-utilisateur' => [
         'recherche-individu' => [
             'app'       => UserService::class,
             'ldap'      => LdapService::class,
             'octopus'   => IndividuService::class,
         ],
     ],
 ];
```

La clef *recherche-individu* stocke un tableau contenant un lisiting de service implementant l'interface **RechercheIndividuServiceInterface** permettant la recherche d'utilisateur.
Cette interface force l'implémentation de deux méthodes :
1. *findById($id)* : qui retourne un **RechercheIndividuResultatInteface** associé à l'individu d'identifiant *$id*;
2. *findByTerm($term)* : qui retroune un tableau de **RechercheIndividuResultatInteface** associés au terme *$term*.

L'interface **RechercheIndividuResultatInteface** force l'implementation de quatre getters : *getId()*, *getUsername()*, *getDisplayname()* et *getEmail()*.

## Script pour la gestion de la base de données 

Le fichier `Entity\Db\SQL\db_bootstrapping_utilisateur.sql` contient un script permettant la création des tables associées et des insertions "minimales".
Les tables et les insertions utilisées pour les privilèges sont décrites dans `Entity\Db\SQL\db_bootstrapping_privilege.sql`. 

# Dépendences (ce qui pourra changer par le futur)

Le module réfère pour le moment : 
1. la bibliothèque **unicaen/auth** pour :
* les *Guard* des privilèges;
* les *LDAP/People*;
* les rôles de base *RoleInterface*; 
* les utilisateurs de base *UserInterface*;
* les service de recupération *UserContextService*, *UserContext*, *UserContextAwareTrait*;
* les événements de connexion *AuthenticatedUserSavedAbstractListener*, *UserAuthenticatedEvent*, *UserRoleSelectedEventAbstractListener*, *UserRoleSelectedEvent*; 
2. la bibliothèque **unicaen/app** pour :
* les *SearchAndSelect*;
* le trait *EntityManagerAwareTrait*;
* le *CSVModel*;

L'affichage du listing des utilisateurs utilise DataTable. 
***Remarque :*** En cas d'oubli de cette bibliothèque le listing sera affiché comme un grand tableau.

## Aménagements

HistoriqueAwareTrait a été décallé dans Unicaen/Utilisateur. Il faut penser à changer les **use** associés de

```php  
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
```
en
```php  
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
``` 

# Version

**0.9.0 - 28/11/2019**
- Ajout de documentations
- Ajout des scripts pour gérer la BD  
 