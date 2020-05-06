<?php

namespace Application\Service\MetierReferentiel;

trait MetierReferentielServiceAwareTrait {

    /** @var MetierReferentielService */
    private $metierReferentielService;

    /**
     * @return MetierReferentielService
     */
    public function getMetierReferentielService()
    {
        return $this->metierReferentielService;
    }

    /**
     * @param MetierReferentielService $metierReferentielService
     * @return MetierReferentielService
     */
    public function setMetierReferentielService(MetierReferentielService $metierReferentielService)
    {
        $this->metierReferentielService = $metierReferentielService;
        return $this->metierReferentielService;
    }


}