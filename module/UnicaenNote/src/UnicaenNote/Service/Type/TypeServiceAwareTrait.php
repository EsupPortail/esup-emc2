<?php

namespace UnicaenNote\Service\Type;

trait TypeServiceAwareTrait {

    /** @var TypeService */
    private $typeService;

    /**
     * @return TypeService
     */
    public function getTypeService(): TypeService
    {
        return $this->typeService;
    }

    /**
     * @param TypeService $typeService
     * @return TypeService
     */
    public function setTypeService(TypeService $typeService): TypeService
    {
        $this->typeService = $typeService;
        return $this->typeService;
    }

}