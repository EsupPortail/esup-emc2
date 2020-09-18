<?php

namespace UnicaenDocument\Service\Macro;

trait MacroServiceAwareTrait {

    /** @var MacroService */
    private $macroService;

    /**
     * @return MacroService
     */
    public function getMacroService()
    {
        return $this->macroService;
    }

    /**
     * @param MacroService $macroService
     * @return MacroService
     */
    public function setMacroService(MacroService $macroService)
    {
        $this->macroService = $macroService;
        return $this->macroService;
    }


}