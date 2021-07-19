<?php

namespace Application\Service\NiveauEnveloppe;

trait NiveauEnveloppeServiceAwareTrait {

    /** @var NiveauEnveloppeService */
    private $metierNiveauService;

    /**
     * @return NiveauEnveloppeService
     */
    public function getNiveauEnveloppeService() : NiveauEnveloppeService
    {
        return $this->metierNiveauService;
    }

    /**
     * @param NiveauEnveloppeService $metierNiveauService
     * @return NiveauEnveloppeService
     */
    public function setNiveauEnveloppeService(NiveauEnveloppeService $metierNiveauService) : NiveauEnveloppeService
    {
        $this->metierNiveauService = $metierNiveauService;
        return $this->metierNiveauService;
    }


}