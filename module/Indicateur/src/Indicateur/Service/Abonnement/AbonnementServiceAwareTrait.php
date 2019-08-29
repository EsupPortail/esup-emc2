<?php

namespace Indicateur\Service\Abonnement;

trait AbonnementServiceAwareTrait {

    /** @var AbonnementService */
    private $abonnementService;

    /**
     * @return AbonnementService
     */
    public function getAbonnementService()
    {
        return $this->abonnementService;
    }

    /**
     * @param AbonnementService $abonnementService
     * @return AbonnementServiceAwareTrait
     */
    public function setAbonnementService($abonnementService)
    {
        $this->abonnementService = $abonnementService;
        return $this;
    }


}