<?php

namespace Element\Entity\Db\Interfaces;

use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationElement;

interface HasApplicationCollectionInterface {

    public function getApplicationCollection() ;
    public function getApplicationListe(bool $avecHisto = false) : array;
    public function hasApplication(Application $application) : ?ApplicationElement;
    public function addApplicationElement(ApplicationElement $application) : void ;
    public function removeApplicationElement(ApplicationElement $application) : void;
    public function clearApplications() : void;
}