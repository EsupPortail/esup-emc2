<?php

namespace UnicaenParametre\Service\Parametre;


trait ParametreServiceAwareTrait
{
    /** @var ParametreService */
    protected $parametreService;

    /**
     * @param ParametreService $parametreService
     * @return ParametreService
     */
    public function setParametreService(ParametreService $parametreService) : ParametreService
    {
        $this->parametreService = $parametreService;
        return $this->parametreService;
    }

    /**
     * @return ParametreService
     */
    public function getParametreService() : ParametreService
    {
        return $this->parametreService;
    }
}