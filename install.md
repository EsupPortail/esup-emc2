Installation
===
----
dernière mise à jour : 16/12/2024

## Préambule

Dans la suite nous supposerons que si vous avez un proxy, vous l'avez au préalable paramètré sur le serveur.

```bash
export http_proxy=proxy_url:proxy_port
export https_proxy=proxy_url:proxy_port
```

## Récupération du code

Le code du projet est déposé sur le serveur git et est opensource : `https://git.unicaen.fr/open-source/mes-formations.git`.
Par conséquent, vous n'avez pas besoin d'être authentifier pour cloner le projet.

``` 
user@kmes-formations-crashtest:/var/www/html$ git clone https://git.unicaen.fr/open-source/mes-formations.git html
```

## Installation des biliothèques 

```bash
composer update
```

Pour information sur le serveur de test nous avions la version suivante

```bash 
composer about
#Composer - Dependency Manager for PHP - version 2.8.4
```

## Fichiers locaux de configuration

Les fichiers de configuration locaux vous permettent de paramètrer et d'ajuster le comportement de l'application. 
Des copies (avec l'extension `.dist`) sont mises à disposition dans le répertoire des fichiers de configuration de l'application (`./config/autoload`)
Vous devez les renommer et paramètrer ceux-ci en fonction de votre installation.
```bash
mv ./config/autoload/FICHIER.local.php.dist ./config/autoload/FICHIER.local.php
...
```

Voici la liste des fichiers :
- `database.local.php` : paramètrage des base de données 
- `local.php` : configuration du *reporting* des erreurs 
- `unicaen-app.local.php` : paramètrage de quelques liens du pieds de page
- `unicaen-authentification.local.php` : mode d'authentification et usrupation
- `unicaen-evenement.local.php` : temps maximal d'execution des événements 
- `unicaen-fichier.local.php` : paramètrage du répertoire d'upload
- `unicaen-ldap.local.php` : paramètrage de connextion au ldap et des attributs exploités
- `synchro.local.php` : paramètrage de la synchronisation des données du SI vers Mes Formations
- `unicaen-mail.local.php` : paramètrage de l'envoi de courrier électronique

### Compléments à propos de `database.local.php`
 
Des constantes en haut de ce fichier sont là pour simplifier le paramètrage.

Attention, deux bases de données sont à renseigner : la base de l'application (variables préfixées par `DB_`) et la base contenant les données du SI (variables préfixées par `DB_SYNCHRO_`).  

### Compléments à propos de `unicaen-authentification.local.php`

Ce fichier permet de paramettrer les moyens d'authentifications fournit aux usagers.
Ils peuvent être activés/désactivés et ordonnés comme vous le souhaités.

Parmi ces modes, on retrouve :
- CAS
- ldap et comptes locaux
- shiboleth

La liste des utilisateurs autorisés à usurper est aussi défini dans ce fichier dans la clef `usurpation_allowed_usernames`. 
Il faut préciser dans ce tableau la liste des identifiants de connexion des utilisateurs ayant ce privilège. 

### Compléments à propos de `unicaen-ldap.local.php`

Ce fichier contient des redondances, un travail est en cours pour factoriser celles-ci.

### Compléments à propos de `synchro.local.php`

La documentation de cette partie peut-être trouvée ici : [connecteur.md](connecteur.md)

### Compléments à propos de `unicaen-mail.local.php`

On retrouve dans ce fichier la liste des groupes de mail avec à chaque fois les données suivantes :
- `do_not_send` si à *true* alors les mails ne seront pas envoyés
- `redirect` si à *true* alors tous les mails seront redirigés aux adresses renseignées dans `redirect_to`
- `redirect_to` un tableau contenant les adresses vers qui redigierer les mails
- `subject_prefix` préfixe qui sera ajouté en entête des mails `[subject_prefix]`
- `from_name` le nom affiché comme expéditeur
- `from_email` l'adresse présenté comme expéditrice (est recommandée ici une adresse sans quota ou whitelistée)

## Repertoires de travail de l'application

L'application a besoin de deux répertoires de stockage :
1. data/DoctrineORMModule/Proxy : zone d'échange pour les interactions avec la base de données
2. upload : zone de dépôt de l'application

###  data/DoctrineORMModule/Proxy

Ce répertoire est fixe dans l'application mais ne devrait pas être trop volumineux.

```bash
mkdir -p data/DoctrineORMModule/Proxy
chmod 777 data/DoctrineORMModule/Proxy
```

### upload

Ce répertoire peut être déplacé à l'endroit que vous le souhaiter. Son chemin est défini dans le fichier de configuration `onfig/autoload/unicaen-fichier.local.php`.
Attention, celui-ci peut devenir rapidement volumineux (prévoyer un stockage de quelques dizaines de Go.

Même procédure de création :
```bash
mkdir -p mon_repertoire_upload
chmod 777 mon_repertoire_upload
```

## Création des tables de la base de données

La création des tables (et la mise à jour de leur structure) passe par l'utilisation de la bibliothèque `unicaen/bdd-admin` (qui a été instalée par `composer`).
Pour créer les tables (ou les mettre à jour) vous pouvez utiliser la commande suivante :

```bash
./vendor/bin/laminas update-bdd
```

## Insertion des données 

Les données initiales à inserer sont mise à disposition dans le repertoire database/script.
Veuillez exécuter les scripts dans l'ordre lexicographique.

## Synchronisation

La synchronisation est faites par la bibliothèque `unicaen/synchro` (qui a été instalée par `composer`).
Pour créer les tables (ou les mettre à jour) vous pouvez utiliser la commande suivante :

```bash
php public/index.php synchroniser-all
```

## Nommer un premier adminsitrateur·trice technique

Pour initialiser le premier administrateur, il faut que celui-ci se connecte une première fois.
Puis de mettre dans la table de linker `unicaen_utilisateur_role_linker` la ligne reliant son utilisateur (enregistré dans `unicaen_utilisateur_user`) et son rôle (enregistré dans `unicaen_utilisateur_role`).

## Vérification de l'installation et paramètrage php

Une fois connecté en tant qu'administrateur, vous aurez accés à deux menu pour vérifier l'installation :
1. Administration > Vérification installation : pointe les atttendus
2. Administration > Vérification php : présente le `php_info`

## Troubleshotting

### Déconnection au bout de 24 minutes
_Symptôme_: Les utilisateurs se font déconnecter au bout d'une période relativement courte (24 minutes). 

_Cause_ : Dans la vérification php, les variables locales `cookie_lifetime` et `gc_maxlifetime` ne sont pas à 0 (note: 1440 secondes = 24 minutes)

_Solution_ : Il est possible de corriger ces valeurs dans le fichier `/etc/php/8.2/fpm/php.ini`. 
Nécessite un redémarage de fpm. 


