<?php

namespace Metier\Service\Referentiel;

trait ReferentielServiceAwareTrait {

    /** @var ReferentielService */
    private $referentielService;

    /**
     * @return ReferentielService
     */
    public function getReferentielService()
    {
        return $this->referentielService;
    }

    /**
     * @param ReferentielService $referentielService
     * @return ReferentielService
     */
    public function setReferentielService(ReferentielService $referentielService)
    {
        $this->referentielService = $referentielService;
        return $this->referentielService;
    }


}