<?php

namespace UnicaenEtat\Service\ActionType;

trait ActionTypeServiceAwareTrait {

    /** @var ActionTypeService */
    private $etatTypeService;

    /**
     * @return ActionTypeService
     */
    public function getActionTypeService(): ActionTypeService
    {
        return $this->etatTypeService;
    }

    /**
     * @param ActionTypeService $etatTypeService
     * @return ActionTypeService
     */
    public function setActionTypeService(ActionTypeService $etatTypeService): ActionTypeService
    {
        $this->etatTypeService = $etatTypeService;
        return $this->etatTypeService;
    }


}