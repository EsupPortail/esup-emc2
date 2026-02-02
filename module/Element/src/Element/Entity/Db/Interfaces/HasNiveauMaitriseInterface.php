<?php

namespace Element\Entity\Db\Interfaces;

use Element\Entity\Db\NiveauMaitrise;

interface HasNiveauMaitriseInterface {

    public function getNiveauMaitrise() : ?NiveauMaitrise;
    public function setNiveauMaitrise(?NiveauMaitrise $competenceMaitrise) : void;

    public function isClef() : ?bool;
    public function setClef(?bool $clef) : void;
}