<?php

namespace FicheMetier\Assertion;

use FicheMetier\Entity\Db\CodeFonction;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;

class CodeFonctionAssertion extends AbstractAssertion {

    use ParametreServiceAwareTrait;

    public function computeAssertion(?CodeFonction $entity, ?string $privilege) : bool
    {
        $on = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::CODE_FONCTION);
        if ($on === true) return true;
        return false;
    }

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null): bool
    {
        return $this->computeAssertion($entity, $privilege);
    }

    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        return $this->computeAssertion(null, $privilege);
    }
}
