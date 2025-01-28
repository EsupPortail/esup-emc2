# Documentation pour l'installation et la configuration du projet

## Clonage du projet

Clonez le projet dans le répertoire `/var/www/html/` :

```bash
git clone https://git.unicaen.fr/open-source/emc2.git /var/www/html/
```

**Note :** À partir de cette étape, toutes les commandes et chemins supposent que vous êtes dans le répertoire
/var/www/html/.

## Mise à jour avec Composer

Rendez-vous dans le répertoire du projet et exécutez la commande suivante pour mettre à jour les dépendances :

```bash
composer update
```

**Note :** Attention à la configuration de votre proxy

## Configuration

La configuration à la charge de l'établissement est contenue dans les fichiers `local.dist` contenu dans le répertoire
`./config/autoload`.
Ces configurations doivent être adaptées aux besoins spécifiques de l'établissement, et l'extension `.dist` doit être
retirée.

Liste des fichiers distants :

- `data-base.local.php.dist`
- `local.php.dist`
- `unicaen-app.local.php.dist`
- `unicaen-authentification.local.php.dist`
- `unicaen-ldap.local.php.dist`
- `unicaen-mail.local.php.dist`

## Installation sur le serveur

Lancez la commande suivante pour exécuter le processus d'installation :

```bash
./vendor/bin/laminas update-ddl
```

## Création des répertoires nécessaires

Créez les répertoires suivants si ce n'est pas déjà fait :

```bash
mkdir -p ./data/DoctrineORMModule/Proxy
mkdir -p ./upload
```

Assurez-vous que ces répertoires ont les permissions appropriées.

## Configuration des paramètres PHP

Vérifiez que les paramètres suivants dans PHP sont correctement configurés à 0 :

    session.cookie_lifetime
    session.gc_maxlifetime

Pour vérifier, consultez la configuration PHP :

    php -i | grep session.cookie_lifetime
    php -i | grep session.gc_maxlifetime

Modification des paramètres

Si les valeurs ne sont pas correctes, modifiez le fichier /etc/php/8.2/fpm/php.ini et assurez-vous que les lignes
suivantes sont définies :

    session.cookie_lifetime = 0
    session.gc_maxlifetime = 0

Redémarrez le service PHP-FPM pour appliquer les modifications :

systemctl restart php-fpm

## Mise en place de la synchronisation et execution de celle-ci

Configurez le fichier de synchronisation selon les besoins du projet et les fichiers de base de données sources comme
décrit dans [la documentation sur les connecteurs](connecteur.md).
Pour lancer la synchronisation suivez les dernières étapes de la documentation associée.
