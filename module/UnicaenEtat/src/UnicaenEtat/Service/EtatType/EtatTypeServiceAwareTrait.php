<?php

namespace UnicaenEtat\Service\EtatType;

trait EtatTypeServiceAwareTrait {

    /** @var EtatTypeService */
    private $etatTypeService;

    /**
     * @return EtatTypeService
     */
    public function getEtatTypeService(): EtatTypeService
    {
        return $this->etatTypeService;
    }

    /**
     * @param EtatTypeService $etatTypeService
     * @return EtatTypeService
     */
    public function setEtatTypeService(EtatTypeService $etatTypeService): EtatTypeService
    {
        $this->etatTypeService = $etatTypeService;
        return $this->etatTypeService;
    }


}