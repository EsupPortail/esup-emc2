<?php

namespace Metier\Service\MetierNiveau;

trait MetierNiveauServiceAwareTrait {

    /** @var MetierNiveauService */
    private $metierNiveauService;

    /**
     * @return MetierNiveauService
     */
    public function getMetierNiveauService() : MetierNiveauService
    {
        return $this->metierNiveauService;
    }

    /**
     * @param MetierNiveauService $metierNiveauService
     * @return MetierNiveauService
     */
    public function setMetierNiveauService(MetierNiveauService $metierNiveauService) : MetierNiveauService
    {
        $this->metierNiveauService = $metierNiveauService;
        return $this->metierNiveauService;
    }


}