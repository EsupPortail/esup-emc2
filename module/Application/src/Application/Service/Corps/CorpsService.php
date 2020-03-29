<?php

namespace Application\Service\Corps;

use Application\Entity\Db\Corps;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Service\EntityManagerAwareTrait;

class CorpsService {
    use EntityManagerAwareTrait;

    /** Pas de gestion des entités car les corps sont importés depuis octopus **/

    /** TODO faire une methode pour forcer le rafraichissement depuis octopus ... */

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() {
        $qb = $this->getEntityManager()->getRepository(Corps::class)->createQueryBuilder('corps');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Corps[]
     */
    public function getCorps($champ = 'libelleLong', $ordre = 'ASC') {
        $qb = $this->createQueryBuilder()
            ->andWhere('corps.histo = :histo')
            ->setParameter('histo', 'O')
            ->orderBy('corps.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Corps[]
     */
    public function getCorpsHistorises($champ = 'libelleLong', $ordre = 'ASC') {
        $qb = $this->createQueryBuilder()
            ->andWhere('corps.histo != :histo')
            ->setParameter('histo', 'O')
            ->orderBy('corps.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}
