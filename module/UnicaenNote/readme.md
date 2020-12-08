UnicaenNote
============

Description
-----------

UnicaenNote est un module d'annotation permettant le dépôt de note sur un page.
Ces notes sont éditables, typées et ordonnables.  

Version et changement
---------------------

Partie SGDB 
-----------

Trois tables :
- unicaen_note : ressence les notes (elles sont liées à un porte note et à un type)
- unicaen_note_note_type : ressence les différents types de note et la stylisation associée à ce type de note
- unicaen_note_portenote : regroupe un sous-ensemble de note pour leur affichage.

Service
-------

Privilège
---------

Interfaces et manipulations
---------------------------

Autres remarques
----------------