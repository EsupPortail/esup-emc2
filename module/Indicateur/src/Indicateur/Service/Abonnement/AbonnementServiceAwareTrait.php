<?php

namespace Indicateur\Service\Abonnement;

trait AbonnementServiceAwareTrait {

    /** @var AbonnementService */
    private $abonnementService;

    /**
     * @return AbonnementService
     */
    public function getAbonnementService() : AbonnementService
    {
        return $this->abonnementService;
    }

    /**
     * @param AbonnementService $abonnementService
     * @return AbonnementService
     */
    public function setAbonnementService(AbonnementService $abonnementService) : AbonnementService
    {
        $this->abonnementService = $abonnementService;
        return $this->abonnementService;
    }


}