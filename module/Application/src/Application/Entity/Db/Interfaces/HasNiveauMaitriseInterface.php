<?php

namespace Application\Entity\Db\Interfaces;

use Element\Entity\Db\Niveau;

interface HasNiveauMaitriseInterface {

    public function getNiveauMaitrise() : ?Niveau;
    public function setNiveauMaitrise(?Niveau $competenceMaitrise);

    public function isClef() : ?bool;
    public function setClef(?bool $clef);

}