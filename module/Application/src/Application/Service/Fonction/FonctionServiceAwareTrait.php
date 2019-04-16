<?php

namespace Application\Service\Fonction;

trait FonctionServiceAwareTrait {

    /** @var FonctionService $fonctionService*/
    private $fonctionService;

    /**
     * @return FonctionService
     */
    public function getFonctionService()
    {
        return $this->fonctionService;
    }

    /**
     * @param FonctionService $fonctionService
     * @return FonctionService
     */
    public function setFonctionService($fonctionService)
    {
        $this->fonctionService = $fonctionService;
        return $this->fonctionService;
    }


}