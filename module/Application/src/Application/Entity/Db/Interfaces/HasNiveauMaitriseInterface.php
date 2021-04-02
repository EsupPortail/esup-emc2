<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\MaitriseNiveau;

interface HasNiveauMaitriseInterface {

    public function getNiveauMaitrise() : ?MaitriseNiveau;
    public function setNiveauMaitrise(?MaitriseNiveau $competenceMaitrise);

    public function isClef() : ?bool;
    public function setClef(?bool $clef);

}