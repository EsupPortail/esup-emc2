<?php

namespace Application\Assertion;

use Application\Entity\Db\Agent;
use UnicaenAuthentification\Assertion\AbstractAssertion;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class AgentAssertion extends AbstractAssertion {

    protected function assertEntity(ResourceInterface $entity = null,  $privilege = null)
    {
        if (!$entity instanceof Agent) {
            return false;
        }

        return true;
    }
}