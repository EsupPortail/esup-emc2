<?php

namespace UnicaenEtat\Service\Etat;

trait EtatServiceAwareTrait {

    /** @var EtatService */
    private $etatService;

    /**
     * @return EtatService
     */
    public function getEtatService(): EtatService
    {
        return $this->etatService;
    }

    /**
     * @param EtatService $etatService
     * @return EtatService
     */
    public function setEtatService(EtatService $etatService): EtatService
    {
        $this->etatService = $etatService;
        return $this->etatService;
    }
}