<?php

namespace Structure\Service\Type;

trait TypeServiceAwareTrait {

    private TypeService $typeService;

    public function getTypeService(): TypeService
    {
        return $this->typeService;
    }

    public function setTypeService(TypeService $typeService): void
    {
        $this->typeService = $typeService;
    }
}