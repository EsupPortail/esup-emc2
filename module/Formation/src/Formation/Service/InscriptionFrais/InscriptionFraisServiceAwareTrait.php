<?php

namespace Formation\Service\InscriptionFrais;

trait InscriptionFraisServiceAwareTrait
{

    private InscriptionFraisService $instanceFraisService;

    public function getInscriptionFraisService(): InscriptionFraisService
    {
        return $this->instanceFraisService;
    }

    public function setInscriptionFraisService(InscriptionFraisService $instanceFraisService): void
    {
        $this->instanceFraisService = $instanceFraisService;
    }


}