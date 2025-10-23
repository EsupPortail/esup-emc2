<?php

namespace Carriere\Service\FonctionType;

trait FonctionTypeServiceAwareTrait
{
    private FonctionTypeService $fonctionTypeService;

    public function getFonctionTypeService(): FonctionTypeService
    {
        return $this->fonctionTypeService;
    }

    public function setFonctionTypeService(FonctionTypeService $fonctionTypeService): void
    {
        $this->fonctionTypeService = $fonctionTypeService;
    }

}
