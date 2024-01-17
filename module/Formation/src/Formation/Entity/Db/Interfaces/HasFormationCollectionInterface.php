<?php

namespace Formation\Entity\Db\Interfaces;

use Doctrine\Common\Collections\Collection;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationElement;

interface HasFormationCollectionInterface {

    public function getFormationCollection(): Collection ;
    public function getFormationListe(bool $avecHisto = false) : array;

    public function getFormations(): Collection;
    public function hasFormation(Formation $formation) : bool;
    public function addFormation(Formation $formation): void;
    public function removeFormation(Formation $formation): void;


}