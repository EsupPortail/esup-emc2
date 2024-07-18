<?php

namespace FicheMetier\Service\Repertoire;

trait RepertoireServiceAwareTrait {

    protected RepertoireService $repertoireService;

    public function getRepertoireService(): RepertoireService
    {
        return $this->repertoireService;
    }

    public function setRepertoireService(RepertoireService $repertoireService): void
    {
        $this->repertoireService = $repertoireService;
    }

}