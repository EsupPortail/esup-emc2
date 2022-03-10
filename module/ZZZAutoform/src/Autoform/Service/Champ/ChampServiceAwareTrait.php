<?php

namespace Autoform\Service\Champ;

trait ChampServiceAwareTrait {

    /** @var ChampService $champService */
    private $champService;

    /**
     * @return ChampService
     */
    public function getChampService()
    {
        return $this->champService;
    }

    /**
     * @param ChampService $champService
     * @return ChampService
     */
    public function setChampService($champService)
    {
        $this->champService = $champService;
        return $this->champService;
    }


}