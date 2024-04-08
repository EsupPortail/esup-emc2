<?php

namespace Application\Assertion;

use Application\Entity\Db\AgentMissionSpecifique;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenPrivilege\Assertion\AbstractAssertion;

class AgentMissionSpecifiqueAssertion extends AbstractAssertion
{
    use AgentMissionSpecifiqueServiceAwareTrait;

    public function computeAssertion(?AgentMissionSpecifique $entity, string $privilege): bool
    {
        return true;
    }

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        return true;
    }

    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        return true;
    }
}