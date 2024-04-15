<?php

namespace Formation\Service\ActionType;

trait ActionTypeServiceAwareTrait {

    protected ActionTypeService $actionTypeService;

    public function getActionTypeService(): ActionTypeService
    {
        return $this->actionTypeService;
    }

    public function setActionTypeService(ActionTypeService $actionTypeService): void
    {
        $this->actionTypeService = $actionTypeService;
    }

}