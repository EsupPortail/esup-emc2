<?php

namespace Autoform\Service\Formulaire;

trait FormulaireServiceAwareTrait {

    /** @var FormulaireService $formulaireService */
    private $formulaireService;

    /**
     * @return FormulaireService
     */
    public function getFormulaireService()
    {
        return $this->formulaireService;
    }

    /**
     * @param FormulaireService $formulaireService
     * @return FormulaireService
     */
    public function setFormulaireService($formulaireService)
    {
        $this->formulaireService = $formulaireService;
        return $this->formulaireService;
    }


}