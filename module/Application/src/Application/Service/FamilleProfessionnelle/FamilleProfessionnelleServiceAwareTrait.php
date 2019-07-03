<?php

namespace Application\Service\FamilleProfessionnelle;

trait  FamilleProfessionnelleServiceAwareTrait
{

    /** @var FamilleProfessionnelleService $familleProfessionnelleService */
    private $familleProfessionnelleService;

    /**
     * @return FamilleProfessionnelleService
     */
    public function getFamilleProfessionnelleService()
    {
        return $this->familleProfessionnelleService;
    }

    /**
     * @param FamilleProfessionnelleService $familleProfessionnelleService
     * @return FamilleProfessionnelleService
     */
    public function setFamilleProfessionnelleService($familleProfessionnelleService)
    {
        $this->familleProfessionnelleService = $familleProfessionnelleService;
        return $this->familleProfessionnelleService;
    }
}