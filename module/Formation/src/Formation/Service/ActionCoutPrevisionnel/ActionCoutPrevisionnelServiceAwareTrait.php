<?php

namespace Formation\Service\ActionCoutPrevisionnel;

trait  ActionCoutPrevisionnelServiceAwareTrait {

    private ActionCoutPrevisionnelService $actionCoutPrevisionnelService;

    public function getActionCoutPrevisionnelService(): ActionCoutPrevisionnelService
    {
        return $this->actionCoutPrevisionnelService;
    }

    public function setActionCoutPrevisionnelService(ActionCoutPrevisionnelService $actionCoutPrevisionnelService): void
    {
        $this->actionCoutPrevisionnelService = $actionCoutPrevisionnelService;
    }

}