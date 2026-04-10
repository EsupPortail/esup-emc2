<?php

namespace Agent\Entity;

use Agent\Entity\Db\Agent;

interface HasAgentInterface {

    public function getAgent();
    public function setAgent(?Agent $agent);
}