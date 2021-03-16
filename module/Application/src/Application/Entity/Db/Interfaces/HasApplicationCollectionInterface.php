<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Application;
use Application\Entity\Db\ApplicationElement;

interface HasApplicationCollectionInterface {

    public function getApplicationCollection() ;
    public function getApplicationListe(bool $avecHisto = false) : array;
    public function hasApplication(Application $application) : ?ApplicationElement;

}