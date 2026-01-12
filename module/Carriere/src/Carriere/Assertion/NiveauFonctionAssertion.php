<?php

namespace Carriere\Assertion;

use Carriere\Entity\Db\NiveauFonction;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;

class NiveauFonctionAssertion extends AbstractAssertion {

    use ParametreServiceAwareTrait;

    public function computeAssertion(?NiveauFonction $entity, ?string $privilege) : bool
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



