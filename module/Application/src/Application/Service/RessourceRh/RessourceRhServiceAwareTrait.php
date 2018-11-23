<?php

namespace  Application\Service\RessourceRh;

trait RessourceRhServiceAwareTrait {

    /** @var RessourceRhService */
    private $ressourceRh;

    /**
     * @return RessourceRhService
     */
    public function getRessourceRhService()
    {
        return $this->ressourceRh;
    }

    /**
     * @param RessourceRhService $ressourceRh
     * @return RessourceRhService
     */
    public function setRessourceRhService($ressourceRh)
    {
        $this->ressourceRh = $ressourceRh;
        return $this->ressourceRh;
    }
}