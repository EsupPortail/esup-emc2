<?php

namespace UnicaenDocument\Service\Macro;

trait MacroServiceAwareTrait {

    /** @var MacroService */
    private $macroService;

    /**
     * @return MacroService
     */
    public function getMacroService() : MacroService
    {
        return $this->macroService;
    }

    /**
     * @param MacroService $macroService
     * @return MacroService
     */
    public function setMacroService(MacroService $macroService) : MacroService
    {
        $this->macroService = $macroService;
        return $this->macroService;
    }


}