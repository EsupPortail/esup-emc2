<?php

namespace UnicaenEtat\Service\Action;

trait ActionServiceAwareTrait {

    /** @var ActionService */
    private $etatService;

    /**
     * @return ActionService
     */
    public function getActionService(): ActionService
    {
        return $this->etatService;
    }

    /**
     * @param ActionService $etatService
     * @return ActionService
     */
    public function setActionService(ActionService $etatService): ActionService
    {
        $this->etatService = $etatService;
        return $this->etatService;
    }
}