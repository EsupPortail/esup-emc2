<?php

namespace UnicaenEtat\Entity\Db;

interface HasEtatInterface {

    public function getEtat();

    public function setEtat(?Etat $etat);
}