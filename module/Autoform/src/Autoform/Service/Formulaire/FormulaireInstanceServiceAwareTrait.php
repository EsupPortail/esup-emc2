<?php

namespace Autoform\Service\Formulaire;

trait FormulaireInstanceServiceAwareTrait {

    /** @var FormulaireInstanceService $formulaireInstanceService */
    private $formulaireInstanceService;

    /**
     * @return FormulaireInstanceService
     */
    public function getFormulaireInstanceService()
    {
        return $this->formulaireInstanceService;
    }

    /**
     * @param FormulaireInstanceService $formulaireInstanceService
     * @return FormulaireInstanceService
     */
    public function setFormulaireInstanceService($formulaireInstanceService)
    {
        $this->formulaireInstanceService = $formulaireInstanceService;
        return $this->formulaireInstanceService;
    }


}