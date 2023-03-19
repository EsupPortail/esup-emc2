<?php

namespace Carriere\Service\NiveauEnveloppe;

trait NiveauEnveloppeServiceAwareTrait {

    private NiveauEnveloppeService $metierNiveauService;

    public function getNiveauEnveloppeService() : NiveauEnveloppeService
    {
        return $this->metierNiveauService;
    }

    public function setNiveauEnveloppeService(NiveauEnveloppeService $metierNiveauService) : void
    {
        $this->metierNiveauService = $metierNiveauService;
    }

}