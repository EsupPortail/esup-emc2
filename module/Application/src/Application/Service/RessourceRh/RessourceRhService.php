<?php

namespace Application\Service\RessourceRh;

use Application\Entity\Db\Corps;
use Application\Entity\Db\Correspondance;
use Application\Entity\Db\Grade;
use Application\Entity\Db\Metier;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class RessourceRhService {
    use EntityManagerAwareTrait;

    /** CARTOGRAPHIE **************************************************************************************************/

    /**
     * @return Metier[]
     */
    public function getCartographie()
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
            ->addSelect('famille')->join('metier.famille', 'famille')
            ->addSelect('domaine')->join('metier.domaine', 'domaine')
            ->orderBy('famille.libelle, domaine.libelle, metier.libelle');

        $result = $qb->getQuery()->getResult();
        return $result;
    }


}