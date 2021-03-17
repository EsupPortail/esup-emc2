<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\CompetenceMaitrise;

interface HasNiveauMaitriseInterface {

    public function getNiveauMaitrise() : ?CompetenceMaitrise;
    public function setNiveauMaitrise(?CompetenceMaitrise $competenceMaitrise);

}