<?php

namespace Application\Service\Activite;

use Application\Entity\Db\Activite;
use Application\Entity\Db\FicheMetierType;
use Application\Entity\Db\FicheMetierTypeActivite;
use Application\Service\User\UserServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class ActiviteService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $ordre nom de champ présent dans l'entité
     * @return Activite[]
     */
    public function getActivites($ordre = null)
    {
        $qb = $this->getEntityManager()->getRepository(Activite::class)->createQueryBuilder('activite')
        ;
        if ($ordre) $qb = $qb->orderBy('activite.' . $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return Activite mixed
     */
    public function getActivite($id)
    {
        $qb = $this->getEntityManager()->getRepository(Activite::class)->createQueryBuilder('activite')
            ->andWhere('activite.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs activités portent le même identifiant ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param Activite $activite
     * @return Activite
     */
    public function create($activite)
    {
        $this->getEntityManager()->persist($activite);
        $activite->setHistoCreation(new DateTime());
        $activite->setHistoCreateur($this->getUserService()->getConnectedUser());
        $activite->setHistoModification(new DateTime());
        $activite->setHistoModificateur($this->getUserService()->getConnectedUser());
        try {
            $this->getEntityManager()->flush($activite);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la création en BD', $e);
        }
        return $activite;
    }

    /**
     * @param Activite $activite
     * @return Activite
     */
    public function update($activite)
    {
        $activite->setHistoModification(new DateTime());
        $activite->setHistoModificateur($this->getUserService()->getConnectedUser());
        try {
            $this->getEntityManager()->flush($activite);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $activite;
    }

    /**
     * @param Activite $activite
     */
    public function delete($activite)
    {
        $this->getEntityManager()->remove($activite);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la suppression en BD', $e);
        }
    }


    public function getFicheMetierTypeActivite($id)
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetierTypeActivite::class)->createQueryBuilder('activite')
            ->andWhere('activite.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs couples (FicheMetierType,Activite) portent le même identifiant ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param FicheMetierType $fiche
     * @return FicheMetierTypeActivite[]
     */
    public function getActivitesByFicheMetierType($fiche)
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetierTypeActivite::class)->createQueryBuilder('couple')
            ->addSelect('fiche')
            ->join('couple.fiche', 'fiche')
            ->addSelect('activite')
            ->join('couple.activite', 'activite')
            ->andWhere('fiche.id = :id')
            ->setParameter('id', $fiche->getId())
            ->orderBy('couple.position')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param FicheMetierTypeActivite$couple
     */
    public function moveUp($couple) {
        $currentPosition = $couple->getPosition();
        if ($currentPosition !== 1) {
            $activites = $this->getActivitesByFicheMetierType($couple->getFiche());

            $swapWith = null;
            foreach ($activites as $activite) {
                if ($activite->getPosition() === $currentPosition - 1) {
                    $swapWith = $activite;
                    break;
                }
            }

            if ($swapWith) {
                $swapWith->setPosition($currentPosition);
                $couple->setPosition($currentPosition-1);
                $this->updateFicheMetierTypeActivite($swapWith);
                $this->updateFicheMetierTypeActivite($couple);
            }
        }
    }

    /**
     * @param FicheMetierTypeActivite$couple
     */
    public function moveDown($couple) {
        $currentPosition = $couple->getPosition();
        $activites = $this->getActivitesByFicheMetierType($couple->getFiche());

        if ($currentPosition < count($activites)) {

            $swapWith = null;
            foreach ($activites as $activite) {
                if ($activite->getPosition() === $currentPosition + 1) {
                    $swapWith = $activite;
                    break;
                }
            }

            if ($swapWith) {
                $swapWith->setPosition($currentPosition);
                $couple->setPosition($currentPosition+1);
                $this->updateFicheMetierTypeActivite($swapWith);
                $this->updateFicheMetierTypeActivite($couple);
            }
        }
    }

    public function createFicheMetierTypeActivite($fiche, $activite)
    {
        $activites = $this->getActivitesByFicheMetierType($fiche);

        $couple = new FicheMetierTypeActivite();
        $couple->setFiche($fiche);
        $couple->setActivite($activite);
        $couple->setPosition(count($activites) + 1);

        $this->getEntityManager()->persist($couple);
        try {
            $this->getEntityManager()->flush($couple);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la création en BD', $e);
        }
        return $couple;
    }

    public function updateFicheMetierTypeActivite($couple)
    {
        try {
            $this->getEntityManager()->flush($couple);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $couple;

    }

    public function removeFicheMetierTypeActivite($couple)
    {
        $this->getEntityManager()->remove($couple);
        try {
            $this->getEntityManager()->flush($couple);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la suppression en BD', $e);
        }
    }
}