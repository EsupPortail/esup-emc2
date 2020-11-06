<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Application;

interface HasApplicationCollectionInterface {

    public function getApplicationCollection() ;
    public function getApplicationListe(bool $avecHisto = false) : array;
    public function hasApplication(Application $application) : bool;

}