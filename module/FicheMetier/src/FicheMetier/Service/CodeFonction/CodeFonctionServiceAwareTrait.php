<?php

namespace FicheMetier\Service\CodeFonction;

trait CodeFonctionServiceAwareTrait
{
    protected CodeFonctionService $codeFonctionService;

    public function getCodeFonctionService(): CodeFonctionService
    {
        return $this->codeFonctionService;
    }

    public function setCodeFonctionService(CodeFonctionService $codeFonctionService): void
    {
        $this->codeFonctionService = $codeFonctionService;
    }

}
