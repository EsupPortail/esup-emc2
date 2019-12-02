<?php

namespace Application\Service\Activite;


trait ActiviteServiceAwareTrait {

    /** @var ActiviteService */
    private $activiteService;

    /**
     * @return ActiviteService
     */
    public function getActiviteService()
    {
        return $this->activiteService;
    }

    /**
     * @param ActiviteService $activiteService
     * @return ActiviteService
     */
    public function setActiviteService($activiteService)
    {
        $this->activiteService = $activiteService;
        return $this->activiteService;
    }


}