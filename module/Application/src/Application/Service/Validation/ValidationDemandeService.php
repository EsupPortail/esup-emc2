<?php

namespace Application\Service\Validation;

use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;

class ValidationDemande {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    public function getValidationsDemandes($champ = 'id', $order = 'ASC') {
        $qb = $this->getEntityManager()->getRepository(Validation)
    }
}