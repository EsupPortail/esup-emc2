<?php

namespace Fichier\Service\Fichier;

trait FichierServiceAwareTrait {

    /** @var FichierService $fichierService */
    private $fichierService;

    /**
     * @return FichierService
     */
    public function getFichierService()
    {
        return $this->fichierService;
    }

    /**
     * @param FichierService $fichierService
     * @return FichierService
     */
    public function setFichierService($fichierService)
    {
        $this->fichierService = $fichierService;
        return $this->fichierService;
    }


}