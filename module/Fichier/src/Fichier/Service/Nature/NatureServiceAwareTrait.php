<?php

namespace Fichier\Service\Nature;

trait NatureServiceAwareTrait {

    /** @var NatureService $natureService */
    private $natureService;

    /**
     * @return NatureService
     */
    public function getNatureService()
    {
        return $this->natureService;
    }

    /**
     * @param NatureService $natureService
     * @return NatureService
     */
    public function setNatureService($natureService)
    {
        $this->natureService = $natureService;
        return $this->natureService;
    }


}