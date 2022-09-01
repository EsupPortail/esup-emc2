<?php

namespace Application\Service\Macro;

trait MacroServiceAwareTrait {

    private MacroService $macroService;

    /**
     * @return MacroService
     */
    public function getMacroService(): MacroService
    {
        return $this->macroService;
    }

    /**
     * @param MacroService $macroService
     */
    public function setMacroService(MacroService $macroService): void
    {
        $this->macroService = $macroService;
    }

}