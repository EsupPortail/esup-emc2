<?php

namespace Application\Service\Affectation;

use Application\Entity\Db\Affectation;
use Application\Service\User\UserServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class AffectationService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $order
     * @return Affectation[]
     */
    public function getAffections($order = 'id')
    {
        $qb = $this->getEntityManager()->getRepository(Affectation::class)->createQueryBuilder('affectation')
            ->addOrderBy('affectation.'.$order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getAffectation($id)
    {
        $qb = $this->getEntityManager()->getRepository(Affectation::class)->createQueryBuilder('affectation')
            ->andWhere('affectation.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs affectations partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param Affectation $affectation
     * @return Affectation
     */
    public function create($affectation)
    {
        $this->getEntityManager()->persist($affectation);
        $affectation->setHistoCreation(new DateTime());
        $affectation->setHistoCreateur($this->getUserService()->getConnectedUser());
        $affectation->setHistoModification(new DateTime());
        $affectation->setHistoModificateur($this->getUserService()->getConnectedUser());
        try {
            $this->getEntityManager()->flush($affectation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la création en BD', $e);
        }
        return $affectation;
    }

    /**
     * @param Affectation $affectation
     * @return Affectation
     */
    public function update($affectation)
    {
        $affectation->setHistoModification(new DateTime());
        $affectation->setHistoModificateur($this->getUserService()->getConnectedUser());
        try {
            $this->getEntityManager()->flush($affectation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $affectation;
    }

    /**
     * @param Affectation $affectation
     */
    public function delete($affectation)
    {
        $this->getEntityManager()->remove($affectation);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la suppression en BD', $e);
        }
    }

}