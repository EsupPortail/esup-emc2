<?php

namespace EntretienProfessionnel\Service\Recours;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Entity\Db\Recours;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

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

    public function getRecours(?int $id): ?Recours
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('recours.id = :id')->setParameter('id', $id);
        try {
            $recours = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Recours::class."] partagent le même id [".$id."]",0,$e);
        }
        return $recours;
    }

    public function getRequestedRecours(AbstractActionController $controller, string $param='recours'): ?Recours
    {
        $id = $controller->params()->fromRoute($param);
        $recours = $this->getRecours($id);
        return $recours;
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