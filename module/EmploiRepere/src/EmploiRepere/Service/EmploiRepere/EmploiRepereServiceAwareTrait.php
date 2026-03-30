<?php

namespace EmploiRepere\Service\EmploiRepere;

trait EmploiRepereServiceAwareTrait {

    private EmploiRepereService $emploiRepereService;

    public function getEmploiRepereService(): EmploiRepereService
    {
        return $this->emploiRepereService;
    }

    public function setEmploiRepereService(EmploiRepereService $emploiRepereService): void
    {
        $this->emploiRepereService = $emploiRepereService;
    }

}
