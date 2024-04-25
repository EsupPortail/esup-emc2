<?php

namespace EntretienProfessionnel\Service\Observateur;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Entity\Db\Observateur;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenUtilisateur\Entity\Db\AbstractUser;

class ObservateurService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Observateur $observateur): Observateur
    {
        $this->getObjectManager()->persist($observateur);
        $this->getObjectManager()->flush($observateur);
        return $observateur;
    }

    public function update(Observateur $observateur): Observateur
    {
        $this->getObjectManager()->flush($observateur);
        return $observateur;
    }

    public function historise(Observateur $observateur): Observateur
    {
        $observateur->historiser();
        $this->getObjectManager()->flush($observateur);
        return $observateur;
    }

    public function restore(Observateur $observateur): Observateur
    {
        $observateur->dehistoriser();
        $this->getObjectManager()->flush($observateur);
        return $observateur;
    }

    public function delete(Observateur $observateur): Observateur
    {
        $this->getObjectManager()->remove($observateur);
        $this->getObjectManager()->flush($observateur);
        return $observateur;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Observateur::class)->createQueryBuilder('observateur')
            ->join('observateur.entretien', 'entretien')->addSelect('entretien')
            ->join('observateur.user', 'user')->addSelect('user')
            ->orderBy('user.displayName')
        ;
        return $qb;
    }

    public function getObservateur(?int $id): ?Observateur
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('observateur.id = :id')->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Observateur::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedObservateur(AbstractActionController $controller, string $param = 'observateur'): ?Observateur
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getObservateur($id);
    }

    /** @return Observateur[] */
    public function getObservateurs(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) { $qb = $qb->andWhere('observateur.histoDestruction IS NULL'); }

        return $qb->getQuery()->getResult();
    }

    /** @return Observateur[] */
    public function getObservateursByEntretienProfessionnel(EntretienProfessionnel $entretienProfessionnel, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('observateur.entretien = :entretien')->setParameter('entretien', $entretienProfessionnel)
        ;
        if (!$withHisto) { $qb = $qb->andWhere('observateur.histoDestruction IS NULL'); }

        return $qb->getQuery()->getResult();
    }

    /** @return Observateur[] */
    public function getObservateursByUser(AbstractUser $user, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('observateur.user = :user')->setParameter('user', $user)
        ;
        if (!$withHisto) { $qb = $qb->andWhere('observateur.histoDestruction IS NULL'); }

        return $qb->getQuery()->getResult();
    }

    /** FACADE ********************************************************************************************************/

}