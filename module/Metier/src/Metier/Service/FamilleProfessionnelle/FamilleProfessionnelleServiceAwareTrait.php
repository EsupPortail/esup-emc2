<?php

namespace Metier\Service\FamilleProfessionnelle;

trait  FamilleProfessionnelleServiceAwareTrait
{

    /** @var FamilleProfessionnelleService $familleProfessionnelleService */
    private $familleProfessionnelleService;

    /**
     * @return FamilleProfessionnelleService
     */
    public function getFamilleProfessionnelleService() : FamilleProfessionnelleService
    {
        return $this->familleProfessionnelleService;
    }

    /**
     * @param FamilleProfessionnelleService $familleProfessionnelleService
     * @return FamilleProfessionnelleService
     */
    public function setFamilleProfessionnelleService(FamilleProfessionnelleService $familleProfessionnelleService) : FamilleProfessionnelleService
    {
        $this->familleProfessionnelleService = $familleProfessionnelleService;
        return $this->familleProfessionnelleService;
    }
}