<?php

namespace Metier\Service\Referentiel;

trait ReferentielServiceAwareTrait
{
    private ReferentielService $referentielService;

    public function getReferentielService() : ReferentielService
    {
        return $this->referentielService;
    }

    public function setReferentielService(ReferentielService $referentielService) : void
    {
        $this->referentielService = $referentielService;
    }

}