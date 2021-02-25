UnicaenGlossaire
============

Unicaen Glossaire est une biliothèque permettant la gestion d'un glossaire.
On retrouve dans le menu glossaire l'ensemble des définitions : terme -> definition.

Lors de l'édition d'une définition on peut utiliser le tooltip {;} pour faire une référence à une autre définition.
Celle-ci est alors cherchée parmi les définitions disponibles dans le glossaire.

De plus il est possible de compléter un terme par des ortographes alternatives (par exemple des féminins ou des pluriels)
afin d'amélioration le lien dans le glossaire

- - - - - - - - - - - - - 

Installation
============

Dans le répertoire **doc** du module, on retrouve le fichier *database.sql* qui permet de construire la table associée au
 module et d'ajouter les privilèges associés.
 
Il est aussi possible de reprendre le fichier *unicaen-glossaire.css* du répertoire **public/css** pour améliorer l'affichage
du glossaire et des liens.
```bash
cp monprojet/module/UnicaenGlossaire/public/css/unicaen-glossaire.css public/css
```

La génération du glossaire est faite grâce à une aide de vue.
```php
/** @see \UnicaenGlossaire\View\Helper\DictionnaireGenerationViewHelper */
echo $this->dictionnaireGeneration(['historise' => false]);
```

Et l'usage du dictionnaire via un morceau de jquery (à placer en fin de fichier).
```jquery-css
$("abbr").each(function() {
    $(this).html(display_definition($(this).text()));
});
```

Parametrage de l'aide de vue de génération du dictionnaire
----------------------------
 
Dans l'aide de vue est paramètrable via le tableau *options*.
+ *historise => false* : les définitions historisées ne seront pas utilisée
+ *historise => true* : les définitions historisées seront utilisée mais pas affichées dans la liste du glossaire.

- - - - - - - - - - - - - 
Historique
==========

version 0.1.0 25/02/2021
------------------------
Version initiale du module.

Améliorations possibles
-----------------------
+ Meilleure gestion des historisations
+ Réutiliser sur une page que l'index du module
+ Invoquer une définition via un viewhelper