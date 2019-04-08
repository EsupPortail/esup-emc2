<?php

namespace Autoform\Service\Formulaire;

trait FormulaireReponseServiceAwareTrait {

    /** @var FormulaireReponseService $reponseService */
    private $formulaireReponseService;

    /**
     * @return FormulaireReponseService
     */
    public function getFormulaireReponseService()
    {
        return $this->formulaireReponseService;
    }

    /**
     * @param FormulaireReponseService $formulaireReponseService
     * @return FormulaireReponseService
     */
    public function setFormulaireReponseService($formulaireReponseService)
    {
        $this->formulaireReponseService = $formulaireReponseService;
        return $this->formulaireReponseService;
    }


}