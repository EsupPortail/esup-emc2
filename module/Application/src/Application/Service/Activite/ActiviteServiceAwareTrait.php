<?php

namespace Application\Service\Activite;


trait ActiviteServiceAwareTrait {

    /** @var ActiviteService */
    private $activiteService;

    /**
     * @return ActiviteService
     */
    public function getActiviteService() : ActiviteService
    {
        return $this->activiteService;
    }

    /**
     * @param ActiviteService $activiteService
     * @return ActiviteService
     */
    public function setActiviteService(ActiviteService $activiteService) : ActiviteService
    {
        $this->activiteService = $activiteService;
        return $this->activiteService;
    }


}