<?php

namespace Application\Entity;

use Application\Entity\Db\Agent;

interface HasAgentInterface {

    public function getAgent();
    public function setAgent(?Agent $agent);
}