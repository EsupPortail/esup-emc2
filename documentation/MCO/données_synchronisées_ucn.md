# Données synchronisées

## Sources
Les instances de Caen sont synchronisées :
- sur le référentiel commun `OCTOPUS` pour les instances de prod et de préprod ; 
- sur `FOCTOPUS` pour les instances de démo et expérimentale.

La déclaration de ces sources de données est faites dans le fichier `config/autoload/database-sirh.local.php`.

## Déclaration des synchronisations

Les synchronisations reposent sur la bibliothèque `unicaen/synchro`.
La description des vues lues et des tables destination est disponible dans le fichier `config/autoload/resynchro.local.php` 
avec le format suivant : 
```php
'STRUCTURE_GESTIONNAIRE' => [
    'order' => 8200,
    'source' => 'OCTOPUS',
    'orm_source' => 'orm_octopus',
    'orm_destination' => 'orm_default',
    'table_source' => 'V_EMC2_STRUCTURE_GESTIONNAIRE',
    'table_destination' => 'structure_gestionnaire',
    'correspondance' => [
        'id' => 'id',
        'structure_id' => 'structure_id',
        'agent_id' => 'agent_id',
        'fonction_id' => 'fonction_id',
        'date_debut' => 'date_debut',
        'date_fin' => 'date_fin',
    ],
    'id' => 'id',
],
```

**N.B. :** la clef `id` représente la clef primaire dans la donnée source et servira à determiner s'il s'agit d'un ajout, 
modification ou suppression.

## Commande pour la lancer la synchronisation

Voici les commandes pour lancer la synchronisation.

Synchronisation complète
```bash
/var/www/html$ ./vendor/bin/laminas synchroniser
```

Synchronisation d'une des données
```php
/var/www/html$ ./vendor/bin/laminas synchroniser STRUCTURE_GESTIONNAIRE
```

**N.B. :** la clef de la table dans le fichier de déclaration est utilisée comme valeur ici.

## Automatisation de la synchronisation

La synchronisation est automatisée via la crontab.
Elle est exécutée en production tous les jours de semaine à 7h00.

*Remarque* : 
C'est suffisant dans le cas d'EMC2. 
En cas d'urgence, on peut l'exécuter à la main. 
