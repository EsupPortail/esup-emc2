<?php

namespace UnicaenDocument\Service\Contenu;

trait ContenuServiceAwareTrait {

    /** @var ContenuService */
    private $contenuService;

    /**
     * @return ContenuService
     */
    public function getContenuService()
    {
        return $this->contenuService;
    }

    /**
     * @param ContenuService $contenuService
     * @return ContenuService
     */
    public function setContenuService(ContenuService $contenuService)
    {
        $this->contenuService = $contenuService;
        return $this->contenuService;
    }

}