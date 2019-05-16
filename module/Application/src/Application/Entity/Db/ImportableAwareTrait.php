<?php

namespace Application\Entity\Db;

use DateTime;

trait ImportableAwareTrait {

    /** @var Datetime */
    private $importationCreation;
    /** @var Datetime */
    private $importationModification;
    /** @var Datetime */
    private $importationHistorisation;

    /**
     * @return DateTime
     */
    public function getImportationCreation()
    {
        return $this->importationCreation;
    }

    /**
     * @param DateTime $importationCreation
     * @return ImportableAwareTrait
     */
    public function setImportationCreation($importationCreation)
    {
        $this->importationCreation = $importationCreation;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getImportationModification()
    {
        return $this->importationModification;
    }

    /**
     * @param DateTime $importationModification
     * @return ImportableAwareTrait
     */
    public function setImportationModification($importationModification)
    {
        $this->importationModification = $importationModification;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getImportationHistorisation()
    {
        return $this->importationHistorisation;
    }

    /**
     * @param DateTime $importationHistorisation
     * @return ImportableAwareTrait
     */
    public function setImportationHistorisation($importationHistorisation)
    {
        $this->importationHistorisation = $importationHistorisation;
        return $this;
    }



}