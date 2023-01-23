<?php

namespace Application\Service\Poste;

trait PosteServiceAwareTrait {

    private PosteService $posteService;

    public function getPosteService(): PosteService
    {
        return $this->posteService;
    }

    public function setPosteService(PosteService $posteService): void
    {
        $this->posteService = $posteService;
    }
}