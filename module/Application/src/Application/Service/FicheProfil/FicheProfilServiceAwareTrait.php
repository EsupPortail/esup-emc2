<?php

namespace Application\Service\FicheProfil;

trait FicheProfilServiceAwareTrait {

    /** @var FicheProfilService */
    private $ficheProfilService;

    /**
     * @return FicheProfilService
     */
    public function getFicheProfilService(): FicheProfilService
    {
        return $this->ficheProfilService;
    }

    /**
     * @param FicheProfilService $ficheProfilService
     * @return FicheProfilService
     */
    public function setFicheProfilService(FicheProfilService $ficheProfilService): FicheProfilService
    {
        $this->ficheProfilService = $ficheProfilService;
        return $this->ficheProfilService;
    }

}