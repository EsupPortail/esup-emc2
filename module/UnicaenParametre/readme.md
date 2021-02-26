UnicaenParametre
============

UnicaenParametre est une biltiothèque de gestion de catégories de paramètre et de paramètres.

Route d'accès
-------------
- */parametre/index* donne accès aux paramètres de toutes les catégories
- */parametre/index/categorieId* donne accès aux paramètres de la catégorie *categorieId*

Récupération de la valeur d'un paramètre
----------------------------------------
```php
$this->getParametreService()->getParametreByCode('ma_categorie','mon_parametre')->getValeur();
```

**N.B.:** Il n'y a pas d'historisation des paramètres ou de leur catégorie. Est-ce bien nécessaire ?

- - - - - - - - - - - - - 

Installation
============

Dans le répertoire **doc** du module, on retrouve le fichier *database.sql* qui permet de construire la table associée au
 module et d'ajouter les privilèges associés.
 
Le module s'attend à avoir un menu **Administration** pour mettre un menu secondaire **Paramètres**. 
Pensez à l'ajouter s'il n'existe pas ou à modifier la naviguation dans *categorie.config.php*.

- - - - - - - - - - - - - 

Historique
==========

version 0.1.0 25/02/2021
------------------------
Version initiale du module.

Améliorations possibles
-----------------------
- Ajouter des "shorthands" vers l'accés aux valeurs des paramètres.