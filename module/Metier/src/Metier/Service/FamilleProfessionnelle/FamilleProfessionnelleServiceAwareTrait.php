<?php

namespace Metier\Service\FamilleProfessionnelle;

trait  FamilleProfessionnelleServiceAwareTrait
{

    private FamilleProfessionnelleService $familleProfessionnelleService;

    public function getFamilleProfessionnelleService() : FamilleProfessionnelleService
    {
        return $this->familleProfessionnelleService;
    }

    public function setFamilleProfessionnelleService(FamilleProfessionnelleService $familleProfessionnelleService) : void
    {
        $this->familleProfessionnelleService = $familleProfessionnelleService;
    }
}