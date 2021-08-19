<?php

namespace Metier\Service\Referentiel;

use Metier\Service\Reference\ReferenceService;

trait ReferentielServiceAwareTrait {

    /** @var ReferentielService */
    private $referentielService;

    /**
     * @return ReferentielService
     */
    public function getReferentielService() : ReferentielService
    {
        return $this->referentielService;
    }

    /**
     * @param ReferentielService $referentielService
     * @return ReferentielService
     */
    public function setReferentielService(ReferentielService $referentielService) : ReferentielService
    {
        $this->referentielService = $referentielService;
        return $this->referentielService;
    }


}