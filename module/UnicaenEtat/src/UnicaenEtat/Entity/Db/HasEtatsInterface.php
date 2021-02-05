<?php

namespace UnicaenEtat\Entity\Db;

interface HasEtatsInterface {

    public function getEtats();
    public function getEtatActif();
    public function addEtat(Etat $etat);
    public function removeEtat(Etat $etat);
    public function clearEtats();

}