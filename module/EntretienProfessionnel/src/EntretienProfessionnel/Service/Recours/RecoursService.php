<?php

namespace EntretienProfessionnel\Service\Recours;

use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Entity\Db\Recours;

class RecoursService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Recours $recours): Recours
    {
        $this->getObjectManager()->persist($recours);
        $this->getObjectManager()->flush($recours);
        return $recours;
    }

    public function update(Recours $recours): Recours
    {
        $this->getObjectManager()->flush($recours);
        return $recours;
    }

    public function historise(Recours $recours): Recours
    {
        $recours->historiser();
        $this->getObjectManager()->flush($recours);
        return $recours;
    }

    public function restore(Recours $recours): Recours
    {
        $recours->dehistoriser();
        $this->getObjectManager()->flush($recours);
        return $recours;
    }

    public function delete(Recours $recours): Recours
    {
        $this->getObjectManager()->remove($recours);
        $this->getObjectManager()->flush($recours);
        return $recours;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Recours::class)->createQueryBuilder('recours')
            ->join('recours.entretien', 'entretien')->addSelect('entretien');
        return $qb;
    }

    /** @return Recours[] */
    public function getRecoursByEntretien(EntretienProfessionnel $entretien, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('recours.entretien = :entretien')->setParameter('entretien', $entretien);
        if (!$withHisto) $qb = $qb->andWhere('recours.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/
}